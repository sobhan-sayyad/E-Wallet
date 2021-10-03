<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function updateBalance($data){
        $id = $data['user_id'];
        $user = User::findORFail($id);

        if($data['state'] == 1){
            
        $user['balance'] += $data['amount'];
        return $user->save();
        }
        if($data['state'] == 0){
            $user['balance'] -= $data['amount'];
            return $user->save();
        }
        return abort(404);
    }

    public static function findUserById($id){
        return User::findOrFail($id);
    }
}
