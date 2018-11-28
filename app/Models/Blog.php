<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{

    use LikesTrait, UnlikesTrait;

    protected $guarded = ['id'];

    public function user()
    {
    
        return $this->belongsTo('App\Models\User');
    
    }

    public function tags() 
    {
        
        return $this->belongsToMany('App\Models\Tag');

    }

   
   
    public function comments()
    {

        return $this->hasMany('App\Models\BlogComment');
        
    }

      public function notifications()
    {
        
        return $this->hasMany('App\Models\Notification');

    }

}
