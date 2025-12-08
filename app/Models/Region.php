<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Region
 * 
 * @property int $id
 * @property string $nom
 * @property string $description
 * @property int $superficie
 * @property int $population
 * @property string|null $localisation
 *
 * @package App\Models
 */
class Region extends Model
{
	protected $table = 'regions';
	public $timestamps = false;

	protected $casts = [
		'superficie' => 'int',
		'population' => 'int'
	];

	protected $fillable = [
		'nom',
		'description',
		'superficie',
		'population',
		'localisation'
	];
}
