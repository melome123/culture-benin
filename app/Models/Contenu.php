<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Contenu
 * 
 * @property int $id
 * @property string $titre
 * @property string $texte
 * @property Carbon $created_at
 * @property Carbon|null $date_valid
 * @property int $langue_id
 * @property int $region_id
 * @property int|null $contenu_id
 * @property int $typecontenu_id
 * @property int $user_id
 * @property string $statut
 *
 * @package App\Models
 */
class Contenu extends Model
{
	protected $table = 'contenus';
	public $timestamps = false;

	protected $casts = [
		'date_valid' => 'datetime',
		'langue_id' => 'int',
		'region_id' => 'int',
		'contenu_id' => 'int',
		'typecontenu_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'titre',
		'texte',
		'date_valid',
		'langue_id',
		'region_id',
		'contenu_id',
		'typecontenu_id',
		'user_id',
		'statut'
	];

	public function comments()
	{
		return $this->hasMany(Commentaire::class, 'idcontenu');
	}

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
