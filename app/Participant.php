<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable=['conversation_id','user_id','type'];

    protected $appends=['contact_name'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function conversation(){
        return $this->belongsTo(Conversation::class);
    }


    public function getContactNameAttribute()
    {
      return $this->user()->value('display_name');
    }
}
