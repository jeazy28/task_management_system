<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['user_id','title','content','has_file','is_published'];

    public function taskStatus()
    {
        return $this->hasOne(TaskStatus::class,'id','status');
    }
}
