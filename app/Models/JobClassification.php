<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JobClassification
 * 
 * @property int $id
 * @property string $job_name
 * @property int $duration
 * 
 * @property Collection|Job[] $jobs
 *
 * @package App\Models
 */
class JobClassification extends Model
{
	protected $table = 'job_classification';
	public $timestamps = false;

	protected $casts = [
		'duration' => 'int'
	];

	protected $fillable = [
		'job_name',
		'duration'
	];

	public function jobs()
	{
		return $this->hasMany(Job::class);
	}
}
