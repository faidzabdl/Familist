<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
         'status', 
         'user_id', 
         'deskripsi', 
         'tenggat_waktu', 
         'keterangan_skor',
         'prioritas',
        ];

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
