<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskMeta extends Model
{
    use HasFactory;
    protected $fillable=['user_id','role_id','task_id'];
    //  public function task(){
    //     return $this->hasOne(Task::class);
    // }
}
