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

    $tasks = $tasksQuery->paginate(5)->appends(request()->query());

    return view('todo.app', compact('tasks'));
}


    

    



    public function store(Request $request)
    {
    $user_id = Auth::user()->id; 

    $request->validate([
        'name' => 'required',
        'tenggat_waktu' => 'required',
         'tenggat_waktu.required' => 'Tenggat waktu harus diisi.',
         'prioritas' => 'required'
    ]);
    
    if($request->tenggat_waktu < Carbon::now()){
        return redirect()->back()->with('error', 'waktu deadline harus lebih dari waktu sekarang');
    }else{
        Task::create([
            'name' => $request->name,
            'user_id' => $user_id,
            'deskripsi' => $request->deskripsi,
            'reminder' => $request->reminder,
            'tenggat_waktu' => $request->tenggat_waktu,
            'prioritas' => $request->prioritas
        ]);
    }

  

    return redirect()->back()->with('success', 'Task berhasil di buat');
    }

    public function destroy(string $id)
    {
       
        $subtasks = Subtask::where('task_id', $id)->get();
    
       
        foreach ($subtasks as $subtask) {
            if ($subtask->status == 'belum') {
                return redirect()->back()->with('error', 'Ada subtask yang belum selesai');
            }
        }
    
     
        Task::where('id', $id)->delete();
        Subtask::where('task_id', $id)->delete();
    
        return redirect()->back()->with('success', 'Berhasil menghapus data');
    }
    

    public function subtaskStore(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required',
            'tenggat_waktu' => 'required|date',
        ]);
    
        if (strtotime($request->tenggat_waktu) < strtotime(Carbon::now()->format('Y-m-d'))) {
            return redirect()->back()->with('error', 'Tenggat waktu subtask tidak boleh di masa lalu.');
        }
        
        if (strtotime($request->tenggat_waktu) > strtotime($task->tenggat_waktu) ) {
            return redirect()->back()->with('error', 'Tenggat waktu subtask tidak boleh lebih dari tenggat waktu task.');
        }
    
       
        $task->subtasks()->create([
            'name' => $request->name,
            'tenggat_waktu' => $request->tenggat_waktu,
        ]);
    
        return redirect()->back()->with('success', 'Subtask berhasil di tambahkan');
    }
    
    
    

    public function toggleDone(Task $task)
    {
        $subtaskBelumSelesai = $task->subtasks()->where('status', 'belum')->exists();

        if ($subtaskBelumSelesai) {
            return redirect()->back()->with('error', 'Masih ada subtask yang belum selesai!');
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
    

    public function subtaskToggleDone(Subtask $subtask)
    {
         
        if ($subtask->status == 'belum') {
            $subtask->update([
                'status' => 'selesai',     
                'keterangan_skor' => true
            ]);
            $user = $subtask->task->user;
            $user->increment('skor', 5);
        }

        return redirect()->back();
    }

    public function deleteSubtask(Subtask $subtask)
    {
        $subtask->delete();
        return redirect()->back();
    }

    public function editTask(Request $request, $id)
{
    $task = Task::findOrFail($id);

    $request->validate([
        'name' => 'nullable|string|max:255',
        'deskripsi' => 'nullable|string|max:255',
        'tenggat_waktu' => 'nullable|date',
        'prioritas' => 'nullable|integer|min:1|max:3',
    ]);

    // Update task jika input terisi
    $task->update([
        'name' => $request->name ?? $task->name,
        'deskripsi' => $request->deskripsi ?? $task->deskripsi,
        'tenggat_waktu' => $request->tenggat_waktu ?? $task->tenggat_waktu,
        'prioritas' => $request->prioritas ?? $task->prioritas,
    ]);

    return redirect()->back()->with('success', 'Task berhasil di edit!');
}

    public function editSubtask(Request $request, $id) {
        $subtask = Subtask::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'tenggat_waktu' => 'nullable|date',
           
        ]);

        $task = $subtask->task;

        if (strtotime($request->tenggat_waktu) < strtotime(Carbon::now()->format('Y-m-d'))) {
            return redirect()->back()->with('error', 'Tenggat waktu subtask tidak boleh di masa lalu.');
        }
        
        if (strtotime($request->tenggat_waktu) > strtotime($task->tenggat_waktu) ) {
            return redirect()->back()->with('error', 'Tenggat waktu subtask tidak boleh lebih dari tenggat waktu task.');
        }

        $subtask->update([
            'name' => $request->name ?? $subtask->name,
            'tenggat_waktu' => $request->tenggat_waktu ?? $subtask->tenggat_waktu,
        ]);

        return redirect()->back()->with('success', 'Subtasak berhasil di edit!');
    
    }

}

