<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConversationReply extends Model
{
    protected $fillable=['send_date','message','status','user_id','conversation_id'];
    protected $dates =['send_date'];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function conversation(){
        return $this->belongsTo(User::class);
    }
}
