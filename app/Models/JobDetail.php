<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JobDetail
 * 
 * @property int $id
 * @property int $job_id
 * @property Carbon $start_at
 * @property Carbon|null $end_at
 * @property bool $is_deleted
 * 
 * @property Job $job
 * @property Collection|JobAssigment[] $job_assigments
 *
 * @package App\Models
 */
class JobDetail extends Model
{
	protected $table = 'job_detail';
	public $timestamps = false;

	protected $casts = [
		'job_id' => 'int',
		'start_at' => 'datetime',
		'end_at' => 'datetime',
		'is_deleted' => 'bool'
	];

	protected $fillable = [
		'job_id',
		'start_at',
		'end_at',
		'is_deleted'
	];

	public function job()
	{
		return $this->belongsTo(Job::class);
	}

	public function job_assigments()
	{
		return $this->hasMany(JobAssigment::class);
	}
}
