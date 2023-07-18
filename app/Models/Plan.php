<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @property string $description
 * @property string $level
 * @property string $name
 */
class Plan extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'plans';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description','name', 'plandate','user_id','trainer_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'description' => 'string', 'level' => 'string', 'name' => 'string'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    public function getPlandateAttribute($value)
    {
        return (new Carbon($value))->format('Y-m-d');
    }

    // Scopes...
    public function scopeDate($query,$date)
    {
        return $query->where('plandate','like', '%' .$date. '%');
    }
    public function scopePlan($query,$plan_id)
    {
        return $query->where('plan_id','like', '%' .$plan_id. '%');
    }
    // Functions ...

    // Relations ...

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function groups(){
        return $this->hasMany(Plan::class, 'plan_id', 'id');
    }

    public  function trainer(){
        return $this->belongsTo(User::class,'trainer_id','id');
    }

}
