<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable=[
        'firebase_token','user_id'
    ];
    //Relations
    public function user(){
        return $this->belongsTo(User::class);
    }
    //Scopes

    //Functions
}
