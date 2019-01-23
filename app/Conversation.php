<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected  $fillable=['creator_id','channel_id','title','conversation_type'];

}
