<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Parler
 * 
 * @property int $langue_id
 * @property int $region_id
 *
 * @package App\Models
 */
class Parler extends Model
{
	protected $table = 'parlers';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'langue_id' => 'int',
		'region_id' => 'int'
	];
}
