<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JobAssigment
 * 
 * @property int $id
 * @property int $job_detail_id
 * @property int $assigned_to
 * @property bool $is_delete
 * 
 * @property JobDetail $job_detail
 * @property User $user
 * @property Collection|JobDocumentation[] $job_documentations
 *
 * @package App\Models
 */
class JobAssigment extends Model
{
	protected $table = 'job_assigment';
	public $timestamps = false;

	protected $casts = [
		'job_detail_id' => 'int',
		'assigned_to' => 'int',
		'is_delete' => 'bool'
	];

	protected $fillable = [
		'job_detail_id',
		'assigned_to',
		'is_delete'
	];

	public function job_detail()
	{
		return $this->belongsTo(JobDetail::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'assigned_to');
	}

	public function job_documentations()
	{
		return $this->hasMany(JobDocumentation::class, 'job_assignment_id');
	}
}
