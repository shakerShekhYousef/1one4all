<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $allow_part_pay
 * @property string $currency
 * @property string $description
 * @property float  $value
 */
class Cost extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'costs';

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
        'allow_part_pay', 'currency', 'description', 'plan_id', 'value'
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
        'allow_part_pay' => 'int', 'currency' => 'string', 'description' => 'string', 'value' => 'double'
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

    // Scopes...

    // Functions ...

    // Relations ...
}
