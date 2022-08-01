<?php

namespace GreyZero\WebCallCenter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @inheritdoc
     */
    protected $guarded = [];

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

    /**
     * @inheritdoc
     */
    public function __construct(array $attributes = []){
        $this->table = config('web-call-center.tables_prefix').'_users';

        parent::__construct($attributes);
    }

    /**
     * Gets the authenticated agent or customer of this user account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function authenticatable(){
        return $this->morphTo();
    }
}
