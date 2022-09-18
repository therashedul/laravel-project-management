<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageUpload extends Model
{
    use HasFactory;
    protected $fillable=['name','title','alt','caption','description','slug','username','path','status','extention','created_at','updated_at']; 
}
