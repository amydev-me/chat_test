<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable=[

        'user_id_one',
        'user_id_two',
        'room_title',
        'room_type',
        'status'
    ];
    protected $dates=['send_date'];

    public function user_one(){
        return $this->belongsTo(User::class,'user_id_one');
    }

    public function user_two(){
        return $this->belongsTo(User::class,'user_id_two');
    }

    public function conversation_replies(){
        return $this->hasMany(ConversationReply::class);
    }
}
