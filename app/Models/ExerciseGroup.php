<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseGroup extends Model
{
    use HasFactory;

    protected $fillable=['date','description','plan_id'];

    protected $table='exercise_groups';

    //Relations
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }
    public function exercises(){
        return $this->hasMany(ExerciseGroup::class,'group_id','id');
    }
    //Scopes

    //Functions
}
