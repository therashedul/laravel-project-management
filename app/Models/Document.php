<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
     protected $fillable=['document_title','document_image_id','project_id'];

    public function project(){
        return $this->hasOne(Project::class);
    }
}
