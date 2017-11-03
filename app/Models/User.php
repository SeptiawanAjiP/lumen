<?php

namespace App;

use Helpers;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
	use Authenticatable, Authorizable;
	protected $table = 'users';

	protected $fillable = [
			'email'];

	protected $hidden = [
			'password'];

	protected static function boot(){
        parent::boot();
        static::addGlobalScope('activated', function(Builder $builder){
            $builder->whereNull('activation_id');
        });
    }

	public function scopeForList($query)
	{
		return $query->select('id_user','email');
	}
}