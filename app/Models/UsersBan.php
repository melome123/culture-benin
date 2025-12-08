<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UsersBan
 * 
 * @property int $id
 * @property string $nom
 * @property string $prenom
 * @property string $email
 * @property int $user_id
 * @property Carbon $day_ban
 *
 * @package App\Models
 */
class UsersBan extends Model
{
	protected $table = 'users_bans';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'day_ban' => 'datetime'
	];

	protected $fillable = [
		'nom',
		'prenom',
		'email',
		'user_id',
		'day_ban'
	];
}
