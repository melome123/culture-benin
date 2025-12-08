<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Media
 * 
 * @property int $id
 * @property string $chemin
 * @property string $description
 * @property int $typemedia_id
 * @property int $contenu_id
 *
 * @package App\Models
 */
class Media extends Model
{
	protected $table = 'medias';
	public $timestamps = false;

	protected $casts = [
		'typemedia_id' => 'int',
		'contenu_id' => 'int'
	];

	protected $fillable = [
		'chemin',
		'description',
		'typemedia_id',
		'contenu_id'
	];
}
