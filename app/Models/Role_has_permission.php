<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_has_permission extends Model
{
    use HasFactory;
    protected $fillable=['role_id','permission_id'];

    public function roles()
        {
             return $this->belongsToMany(Role::class);
        } 
    public function permissions()
        {
            return $this->hasMany('App\Permission');
        }
}
