<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    protected $fillable = ['task_id', 'name', 'status', 'tenggat_waktu', 'keterangan_skor'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
