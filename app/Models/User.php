<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Boolean;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property string    $age
 * @property int    $role_id
 * @property string $bio
 * @property string $email
 * @property string $full_name
 * @property string $level
 * @property string $mobile
 * @property string $password
 * @property string $profile_pic
 */
class User extends Authenticatable  implements JWTSubject
{
    use Notifiable;
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

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
        'age', 'bio','country_code', 'email', 'name', 'level_id', 'mobile', 'password', 'profile_pic', 'role_id', 'otp', 'approved'
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
        'age' => 'string', 'bio' => 'string', 'email' => 'string', 'full_name' => 'string', 'level' => 'string', 'mobile' => 'string', 'password' => 'string', 'profile_pic' => 'string', 'role_id' => 'int'
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
    //JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Scopes...
    public function scopeName($query,$name)
    {
        return $query->where('name','like', '%' .$name. '%');
    }

    public function scopeSubcategory($query,$id){
        return $query->where('subcategory_id','like', '%' .$id. '%');
    }
    public function scopeLevel($query,$id){
        return $query->where('level_id','like', '%' .$id. '%');
    }
    public function scopeCategory($query,$id){
        $subcategories=SubCategory::where('category_id',$id)->select('id')->get()->toArray();
        return $query->whereIn('subcategory_id',$subcategories);
    }

    // Functions ...

    public function isAdmin()
    {
        if ($this->role_id == 1)
            return true;
        else
            return false;
    }

    public function isTrainer()
    {
        if ($this->role_id == 2)
            return true;
        else
            return false;
    }

    public function isSpecificTrainer($user)
    {
        if ($user->role_id == 2)
            return true;
        else
            return false;
    }

    public function isPlayer()
    {
        if ($this->role_id == 3)
            return true;
        else
            return false;
    }

    // Relations ...
    public function subcategory(){
        return $this->belongsTo(SubCategory::class);
    }
    public function certificate()
    {
        return $this->hasOne(Certificate::class, 'user_id', 'id')->first();
    }
    public function role(){
        return $this->belongsTo(Role::class,'role_id','id');
    }
    public function plans(){
        return $this->hasMany(Plan::class,'user_id','id');
    }
    public function devices(){
        return $this->hasMany(Device::class,'user_id','id');
    }
    public function level(){
        return $this->belongsTo(Level::class);
    }
    public function plansTrainer(){
        return $this->hasMany(Plan::class,'trainer_id','id');
    }
    public function payments(){
        return $this->hasMany(Payment::class,'payment_id','id');
    }
}
