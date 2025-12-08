<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Typemedia
 * 
 * @property int $id
 * @property string $nomtypem
 *
 * @package App\Models
 */
class Typemedia extends Model
{
	protected $table = 'typemedias';
	public $timestamps = false;

	protected $fillable = [
		'nomtypem'
	];
}
