<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    protected $casts=[
        'address'=>'object'
    ];
}
