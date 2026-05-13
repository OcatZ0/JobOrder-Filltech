<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class JobDocumentation
 * 
 * @property int $id
 * @property int $job_assignment_id
 * @property string $picture_filename
 * @property string $picture_description
 * @property bool $is_delete
 * 
 * @property JobAssigment $job_assigment
 *
 * @package App\Models
 */
class JobDocumentation extends Model
{
	protected $table = 'job_documentation';
	public $timestamps = false;

	protected $casts = [
		'job_assignment_id' => 'int',
		'is_delete' => 'bool'
	];

	protected $fillable = [
		'job_assignment_id',
		'picture_filename',
		'picture_description',
		'is_delete'
	];

	public function job_assigment()
	{
		return $this->belongsTo(JobAssigment::class, 'job_assignment_id');
	}
}
