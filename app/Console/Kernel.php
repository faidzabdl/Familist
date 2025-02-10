<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        // Daftar command Laravel lainnya, kalau ada
    ];

    protected function schedule(Schedule $schedule)
    {
        // Pengecekan setiap menit untuk reminder
        $schedule->call(function () {
            $tasks = \App\Models\Task::whereNotNull('reminder')
                                     ->where('reminder', '<=', now())
                                     ->whereNull('reminded_at')
                                     ->get();

            foreach ($tasks as $task) {
                // Kirim notifikasi atau update status task
                $task->update([
                    'status' => 'reminded',
                    'reminded_at' => now(),
                ]);

                // Flash notifikasi untuk tampilkan pesan
                session()->flash('toastr', 'Kring Kring! Reminder Tiba: ' . $task->name);
            }
        })->everyMinute();  // Cek setiap menit
    }
}
