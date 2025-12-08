<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Typecontenu
 * 
 * @property int $id
 * @property string $nomtypec
 *
 * @package App\Models
 */
class Typecontenu extends Model
{
	protected $table = 'typecontenus';
	public $timestamps = false;

	protected $fillable = [
		'nomtypec'
	];
}
