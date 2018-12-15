<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable =[
        'conversation_id',
        'sender_id',
        'message_type',
        'message',
        'attachment_thumb_url',
        'attachment_url'
    ];

    public function sender(){
        return $this->belongsTo(User::class,'sender_id');
    }
}