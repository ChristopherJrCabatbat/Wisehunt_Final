<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class UserLevel extends Model implements Authenticatable
{
    use HasFactory;

    protected $table = 'user_levels'; // Specify the table name

    protected $fillable = ['name', 'username', 'password', 'role', 'remember_token'];

    // Customize authentication identifier
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    // Customize authentication identifier
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    // Customize authentication password
    public function getAuthPassword()
    {
        return $this->password;
    }

    // Remember token methods
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}