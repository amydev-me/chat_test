<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable=[
        'channel_id',
        'title',
        'creator_id'
    ];

    public function creator(){
        return $this->belongsTo(User::class,'creator_id');
    }

    public function participants(){
        return $this->hasMany(Participant::class);
    }
}