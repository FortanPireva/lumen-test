<?php


namespace App\Models;


class Post extends \Illuminate\Database\Eloquent\Model
{

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
