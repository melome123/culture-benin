<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Langue
 * 
 * @property int $id
 * @property string $nomlang
 * @property string $description
 * @property string $codelang
 *
 * @package App\Models
 */
class Langue extends Model
{
	protected $table = 'langues';
	public $timestamps = false;

	protected $fillable = [
		'nomlang',
		'description',
		'codelang'
	];
}
