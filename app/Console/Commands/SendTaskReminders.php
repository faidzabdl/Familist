<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Mail\TaskReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendTaskReminders extends Command
{
    protected $signature = 'penginggat:send';
    protected $description = 'Kirim email pengingat hanya jika ada tugas yang mencapai reminder atau 2 hari sebelum tenggat';
    public function handle()
    {
        Log::info('Mulai mengirim reminder'); // Tambah ini
        $now = Carbon::now();
        $tasks = Task::where('status', 'belum')
        ->where('keterangan_reminder', false)
        ->where(function ($query) use ($now) {
            $query->where('reminder', '<=', $now) // Pakai datetime penuh
                  ->orWhereDate('tenggat_waktu', $now->copy()->addDays(2)->toDateString());
        })
        ->get();
    
        Log::info('Jumlah tugas yang perlu dikirim: ' . $tasks->count()); // Tambah ini
        // Di dalam method handle() command
        Log::info('Current ENV: ' . app()->environment());
        Log::info('Mail Driver: ' . config('mail.default'));
    
        if ($tasks->isEmpty()) {
            $this->info("Tidak ada tugas yang perlu dikirimkan reminder.");
            return;
        }
    
        foreach ($tasks as $task) {
            Mail::to($task->user->email)->send(new TaskReminderMail($task));

            $task->keterangan_reminder = true;
            $task->save();
            $this->info("Reminder dikirim ke: {$task->user->email} untuk tugas: {$task->name}");
            Log::info("Reminder dikirim ke: {$task->user->email} untuk tugas: {$task->name}"); // Tambah ini
        }
    }
}