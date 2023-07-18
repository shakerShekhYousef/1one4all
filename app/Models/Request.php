<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $body
 * @property string $name
 * @property string $request_type
 */
class Request extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'requests';

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
        'body', 'name', 'player_id', 'request_type', 'trainer_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'body' => 'string', 'name' => 'string', 'request_type' => 'string'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [

    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    //default request type

    public const DEFAULT = 'Pending';

    // Scopes...
    public function scopeType($query,$type)
    {
        return $query->where('request_type','like', '%' .$type. '%');
    }
    // Functions ...

    // Relations ...
    public function trainer(){
        return $this->belongsTo(User::class,'trainer_id','id');
    }
    public function player(){
        return $this->belongsTo(User::class,'player_id','id');
    }
    public function notifications(){
        return $this->hasMany(Notification::class,'request_id','id');
    }
    public function payment(){
        return $this->hasOne(Payment::class,'request_id','id');
    }
}
