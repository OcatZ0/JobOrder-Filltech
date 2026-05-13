<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Job
 * 
 * @property int $id
 * @property int $number_of_the_day
 * @property int $created_by
 * @property string $job_description
 * @property Carbon $start_at
 * @property int $duration
 * @property Carbon|null $end_at
 * @property string $status
 * @property string|null $pending_reason
 * @property bool $is_deleted
 * 
 * @property User $user
 * @property Collection|JobDetail[] $job_details
 *
 * @package App\Models
 */
class Job extends Model
{
	protected $table = 'job';
	public $timestamps = false;

	protected $casts = [
		'number_of_the_day' => 'int',
		'created_by' => 'int',
		'start_at' => 'datetime',
		'duration' => 'int',
		'end_at' => 'datetime',
		'is_deleted' => 'bool'
	];

	protected $fillable = [
		'number_of_the_day',
		'created_by',
		'job_description',
		'start_at',
		'duration',
		'end_at',
		'status',
		'pending_reason',
		'is_deleted'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function job_details()
	{
		return $this->hasMany(JobDetail::class);
	}
}
