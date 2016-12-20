<?php

namespace A17\CmsToolkit\Models;

use A17\CmsToolkit\Models\Behaviors\HasMedias;
use A17\CmsToolkit\Models\Enums\UserRole;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as AuthenticatableContract;
use Illuminate\Notifications\Notifiable;
use Session;

class User extends AuthenticatableContract
{
    use Authenticatable, Authorizable, HasMedias, Notifiable;

    public $timestamps = true;

    protected $fillable = [
        'email',
        'name',
        'role',
        'published',
        'password',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    public $mediasParams = [
        'profile' => [
            'square' => '1',
        ],
    ];

    public function getRoleValueAttribute()
    {
        if (!empty($this->role)) {
            if ($this->role == 'SUPERADMIN') {
                return "SUPERADMIN";
            }
            return UserRole::{$this->role}();
        }

        return null;
    }

    public function setImpersonating($id)
    {
        Session::put('impersonate', $id);
    }

    public function stopImpersonating()
    {
        Session::forget('impersonate');
    }

    public function isImpersonating()
    {
        return Session::has('impersonate');
    }

}