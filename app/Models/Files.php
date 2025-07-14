<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected $table = "task_files";
    //
    protected $fillable = [
        'task_id',
        'path',
        'orig_name',
        'mime_type',
        'size',
    ];
    protected $casts = [
        'size' => 'integer',
    ];
}
