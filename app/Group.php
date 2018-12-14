<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable=['group_name'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
