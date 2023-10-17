<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Helpers\FunctionHelper;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = ['name','last_name', 'email', 'password', 'mobile', 'phone_code', 'designation_id', 'department_id', 'dob'];
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Accessor with same filed name
    public function getProfileUrlAttribute($value)
    {
        return ($this->profile != null) ? asset('storage/users/' . auth()->user()->profile) : asset('admin/images/user-icon.png');
    }

    // public function getFormattedDobAttribute()
    // {
    //     return FunctionHelper::dateToTimeZone($this->dob, 'Y-m-d');
    // }
    
    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->last_name;
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    public function phoneCode()
    {
        return $this->belongsTo(Country::class, 'phone_code', 'id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id')->where('is_open', 0)->orderBy('id', 'DESC');
    } 
}