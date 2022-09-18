<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable=['task_title','task_description','assign_by','task_progress','task_status','start_date','end_date','user_id','project_id'];
    public function project(){
        return $this->hasOne(Project::class);
    }
}
