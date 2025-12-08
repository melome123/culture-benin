<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Commentaire
 * 
 * @property int $id
 * @property string $texte
 * @property Carbon $published_at
 * @property int $idcontenu
 * @property int $user_id
 *
 * @package App\Models
 */
class Commentaire extends Model
{
	protected $table = 'commentaires';
	public $timestamps = false;

	protected $casts = [
		'published_at' => 'datetime',
		'idcontenu' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'texte',
		'published_at',
		'idcontenu',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function contenu()
	{
		return $this->belongsTo(Contenu::class, 'idcontenu');
	}
}
