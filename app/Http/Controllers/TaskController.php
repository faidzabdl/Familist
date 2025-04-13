<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function index(Request $request)
    {
        $user_id = Auth::user()->id;

        $tasksQuery = Task::with('subtasks')
            ->where('user_id', $user_id);

        if ($request->has('search') && !empty($request->search)) {
            $tasksQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $tasks = $tasksQuery->get();

        foreach ($tasks as $task) {
            $tenggatWaktu = Carbon::parse($task->tenggat_waktu);

            // if ($task->reminder && Carbon::now()->greaterThanOrEqualTo(Carbon::parse($task->reminder))) {
            //     session()->flash('toastr', 'Kring Kring! Reminder Tiba: ' . $task->name);
            // }

            if ($task->status != 'selesai' && Carbon::now() > $tenggatWaktu && !$task->keterangan_skor) {
                $task->update([
                    'status' => 'terlambat',
                    'keterangan_skor' => true
                ]);
                $user = $task->user;

                if ($user->skor > 0) {
                    $pointsToDeduct = min(20, $user->skor);
                    $user->decrement('skor', $pointsToDeduct);
                }
            }

            foreach ($task->subtasks as $subtask) {
                if ($subtask->status != 'selesai' && Carbon::now() > Carbon::parse($subtask->tenggat_waktu) && !$subtask->keterangan_skor) {
                    $subtask->update([
                        'status' => 'terlambat',
                        'keterangan_skor' => true
                    ]);
                    $user = $subtask->task->user;

                    if ($user->skor > 0) {
                        $pointsToDeduct = min(10, $user->skor);
                        $user->decrement('skor', $pointsToDeduct);
                    }
                }
            }
        }

        if ($request->get('sort_option')) {
            $sortOption = $request->get('sort_option');

            if ($sortOption == 'name_asc') {
                $tasksQuery->orderBy('name', 'asc');
            } elseif ($sortOption == 'name_desc') {
                $tasksQuery->orderBy('name', 'desc');
            } elseif ($sortOption == 'tenggat_asc') {
                $tasksQuery->where('status', 'belum')->orderBy('tenggat_waktu', 'asc');
            } elseif ($sortOption == 'tenggat_desc') {
                $tasksQuery->where('status', 'belum')->orderBy('tenggat_waktu', 'desc');
            } elseif ($sortOption == 'selesai') {
                $tasksQuery->where('status', 'selesai')->orderBy('name', 'asc');
            } elseif ($sortOption == 'belum') {
                $tasksQuery->where('status', 'belum')->orderBy('name', 'asc');
            } elseif ($sortOption == 'terlambat') {
                $tasksQuery->where('status', 'terlambat')->orderBy('name', 'asc');
            } elseif ($sortOption == 'prioritas_1') {
                $tasksQuery->where('prioritas', '1')->orderBy('created_at', 'desc');
            } elseif ($sortOption == 'prioritas_2') {
                $tasksQuery->where('prioritas', '2')->orderBy('created_at', 'desc');
            } elseif ($sortOption == 'prioritas_3') {
                $tasksQuery->where('prioritas', '3')->orderBy('created_at', 'desc');
            } elseif ($sortOption == 'default') {
                $tasksQuery->orderBy('created_at', 'desc');
            }
        } else {
            $tasksQuery->orderBy('created_at', 'desc');
        }


        $reminders = Task::where('user_id', $user_id)
        ->where('status', 'belum')
        ->where('keterangan_reminder', false)
        ->where('reminder', '<=', Carbon::now())
        ->get();
    

       $tasks = $tasksQuery->paginate(5)->appends(request()->query());


        return view('todo.app', compact('tasks', 'reminders'));
    }








    public function store(Request $request)
    {
        $user_id = Auth::user()->id;

        $request->validate([
            'name' => 'required',
            'tenggat_waktu' => 'required',
            'tenggat_waktu.required' => 'Tenggat waktu harus diisi.',
            'reminder.required' => 'Reminder harus diisi.',
            'prioritas' => 'required'
        ]);

        if ($request->tenggat_waktu < Carbon::now()) {
            return redirect()->back()->withInput()->with('error', 'tenggat waktu harus lebih dari waktu sekarang');
        } elseif (Carbon::parse($request->reminder) > Carbon::parse($request->tenggat_waktu)) {
            return redirect()->back()->withInput()->with('error', 'reminder jangan lebih maju dari tenggat waktu');
        } elseif (Carbon::parse($request->reminder) < Carbon::now()) {
            return redirect()->back()->withInput()->with('error', 'reminder jangan lebih mundur dari waktu sekarang');
        } else {
            Task::create([
                'name' => $request->name,
                'user_id' => $user_id,
                'deskripsi' => $request->deskripsi,
                'reminder' => $request->reminder,
                'tenggat_waktu' => $request->tenggat_waktu,
                'reminder' => $request->reminder,
                'prioritas' => $request->prioritas
            ]);
        }



        return redirect()->back()->with('success', 'Tugas berhasil di buat');
    }

    public function destroy(string $id)
    {

        $subtasks = Subtask::where('task_id', $id)->get();
        $task = Task::where('id', $id)->first();;

        foreach ($subtasks as $subtask) {
            if ($subtask->status == 'belum') {
                return redirect()->back()->with('errorD', 'Ada sub tugas yang belum selesai');
            }
        }

        if ($task->status == "belum") {
            return redirect()->back()->with('errorD', 'Tugas belum selesai');
        }


        Task::where('id', $id)->delete();
        Subtask::where('task_id', $id)->delete();

        return redirect()->back()->with('warning', 'Berhasil menghapus tugas dan subtugas');
    }


    public function subtaskStore(Request $request, Task $task)
{
    $request->validate([
        'name' => 'required',
        'tenggat_waktu' => 'required|date',
    ]);

    if (strtotime($request->tenggat_waktu) < strtotime(Carbon::now()->format('Y-m-d'))) {
        return redirect()->back()
            ->with('errorES_' . $task->id, 'Tenggat waktu subtugas tidak boleh di masa lalu.')
            ->with('SopenSubtaskModal', $task->id);
    }

    if (strtotime($request->tenggat_waktu) > strtotime($task->tenggat_waktu)) {
        return redirect()->back()
            ->with('errorES_' . $task->id, 'Tenggat waktu subtugas tidak boleh lebih dari tenggat waktu tugas.')
            ->with('SopenSubtaskModal', $task->id);
    }

    // Simpan subtugas
    $task->subtasks()->create([
        'name' => $request->name,
        'tenggat_waktu' => $request->tenggat_waktu,
    ]);

    return redirect()->back()
        ->with('successES_' . $task->id, 'Subtugas berhasil ditambahkan.')
        ->with('SopenSubtaskModal', $task->id);
}





    public function toggleDone(Task $task)
    {
        $subtaskBelumSelesai = $task->subtasks()->where('status', 'belum')->exists();

        if ($subtaskBelumSelesai) {
            return redirect()->back()->with('errorTD', 'Masih ada subtask yang belum selesai!');
        }
        if ($task->status == 'belum') {
            $task->update([
                'status' => 'selesai',
                'keterangan_skor' => true
            ]);
            $user = $task->user;
            // dd($user);
            $user->increment('skor', 10);
        }

        return redirect()->back();
    }


    public function subtaskToggleDone(Request $request, Subtask $subtask)
{
    if ($subtask->status == 'belum') {
        $subtask->update([
            'status' => 'selesai',
            'keterangan_skor' => true
        ]);
        $user = $subtask->task->user;
        $user->increment('skor', 5);
    }

    // Tangkap modal asal dari request
    $modalOrigin = $request->input('modal_origin', 'subtaskModal');

    return redirect()->back()
        ->with('successES_' . $subtask->id, 'Sub tugas berhasil diselesaikan')
        ->with('openSubtaskModal', $modalOrigin . '-' . $subtask->task_id);
}


    public function deleteSubtask(Request $request, Subtask $subtask)
    {
        $modalOrigin = $request->input('modal_origin', 'subtaskModal');
        if ($subtask->status == 'belum') {
            return redirect()->back()->with('errorES_' . $subtask->id, 'Sub tugas yang belum selesai tidak bisa di hapus')->with('openSubtaskModal', $modalOrigin . '-' . $subtask->task_id);
        }else{
            
            $subtask->delete();
            return redirect()->back()->with('openSubtaskModal', $modalOrigin . '-' . $subtask->task_id);
        }
    }

    public function editTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'tenggat_waktu' => 'nullable|date',
            'reminder' => 'nullable|date',
            'prioritas' => 'nullable|integer|min:1|max:3',
        ]);
  
        if ($request->name == $task->name && $request->deskripsi == $task->deskripsi && $request->prioritas == $task->prioritas && strtotime($request->tenggat_waktu) == strtotime($task->tenggat_waktu) && strtotime($request->reminder) == strtotime($task->reminder)) {
            return redirect()->back()->with('warning', 'Tugas tidak ada yang berubah');
        }


        if (Carbon::parse($request->reminder) > Carbon::parse($request->tenggat_waktu)) {
            return redirect()->back()->with('errorD', 'reminder jangan lebih maju dari tenggat waktu');
        } elseif (Carbon::parse($request->reminder) < Carbon::now()) {
            return redirect()->back()->with('errorD', 'reminder jangan lebih mundur dari waktu sekarang');
        }


        if (strtotime($request->reminder) != strtotime($task->reminder)){
            $task->update([
                'reminder' => $request->reminder ?? $task->reminder,
                'keterangan_reminder' => false
            ]);
        }
        $task->update([
            'name' => $request->name ?? $task->name,
            'deskripsi' => $request->deskripsi ?? $task->deskripsi,
            'tenggat_waktu' => $request->tenggat_waktu ?? $task->tenggat_waktu,
            'prioritas' => $request->prioritas ?? $task->prioritas,
        ]);


        return redirect()->back()->with('success', 'Tugas berhasil di edit!');
    }

    public function editSubtask(Request $request, $id)
    {
        $subtask = Subtask::findOrFail($id);

        $request->validate([
            'name' => 'string|max:255',
            'tenggat_waktu' => 'date',
        ]);

        $task = $subtask->task;

        
        if($request->name == $subtask->name && strtotime($request->tenggat_waktu) == strtotime($subtask->tenggat_waktu)){
            return redirect()->back()
            ->with('warningES', 'sub tugas tidak ada yang berubah')
            ->with('SopenSubtaskModal', $subtask->task_id);
        }

        if (strtotime($request->tenggat_waktu) < strtotime(Carbon::now()->format('Y-m-d'))) {
            return redirect()->back()->with('errorES_' . $subtask->id, 'Tenggat waktu subtugas tidak boleh di masa lalu.')->with('SopenSubtaskModal', $subtask->task_id);
        }else if (strtotime($request->tenggat_waktu) > strtotime($task->tenggat_waktu)) {
            return redirect()->back()->with('errorES_' . $subtask->id, 'Tenggat waktu subtugas tidak boleh lebih dari tenggat waktu tugas utama.')->with('SopenSubtaskModal', $subtask->task_id);
        }

        $subtask->update([
            'name' => $request->name ?? $subtask->name,
            'tenggat_waktu' => $request->tenggat_waktu ?? $subtask->tenggat_waktu,
        ]);

       

        return redirect()->back()
        ->with('successES_' . $subtask->id, 'sub tugas berhasil diedit!')
        ->with('SopenSubtaskModal', $subtask->task_id); 
    
    
    }

    public function deleteAll() {
        // $subtask = Subtask::where('status', 'selesai');
        $tugasSelesai = Task::where('status', 'selesai')->orwhere('status', 'terlambat')->get();

        foreach($tugasSelesai as $tugas){
            $tugas->subtasks()->delete();

            $tugas->delete();
        }

        return redirect()->back()->with('warning', 'Berhasil menghapus semua tugas dan subtugas');
    }


    public function updateReminderStatus(Request $request)
{
    $taskIds = $request->task_ids;

    Task::whereIn('id', $taskIds)
        ->update(['keterangan_reminder' => true]);

    return response()->json(['message' => 'Reminder updated']);
}






}
