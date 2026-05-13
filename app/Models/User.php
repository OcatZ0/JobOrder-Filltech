<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $role
 * @property bool $is_deleted
 * 
 * @property Collection|Job[] $jobs
 * @property Collection|JobAssigment[] $job_assigments
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	protected $table = 'user';
	public $timestamps = false;

	protected $casts = [
		'is_deleted' => 'bool'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'name',
		'username',
		'password',
		'role',
		'is_deleted'
	];

	public function jobs()
	{
		return $this->hasMany(Job::class, 'created_by');
	}

	public function job_assigments()
	{
		return $this->hasMany(JobAssigment::class, 'assigned_to');
	}
}
