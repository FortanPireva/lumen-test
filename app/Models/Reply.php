<?php


namespace App\Models;


class Reply extends \Illuminate\Database\Eloquent\Model
{

    public function user() {
        return $this->belongsTo('App\Models\Post');
    }
}
