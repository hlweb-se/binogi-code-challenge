<?php

namespace App\Models\User;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


/**
 * @mixin Eloquent
 * @mixin Model
 *
 * @property integer                              $id
 * @property string                               $nickname
 * @property string                               $name
 * @property string                               $email
 * @property Carbon                               $email_verified_at
 * @property string                               $password
 * @property string                               $remember_token
 * @property Carbon                               $created_at
 * @property Carbon                               $updated_at
 */
class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nickname',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
