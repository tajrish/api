<?php

namespace Tajrish\Models;

use Tajrish\Services\FileModelTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Guarded attrs
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Hidden attrs
     *
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * Tokens of the given user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->hasMany(UserToken::class, 'user_id');
    }

    /**
     * Challenges of the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'own_visits')                                 ;
    }
}
