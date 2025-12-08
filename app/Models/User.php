<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable; // <-- important
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * 
 * @property int $id
 * @property string $nom
 * @property string $prenom
 * @property string $password
 * @property string $email
 * @property Carbon $date_naissance
 * @property string $statut
 * @property int $langue_id
 * @property int $region_id
 * @property int $role_id
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	use HasApiTokens, Notifiable;
	protected $table = 'users';
	public $timestamps = false;

	protected $casts = [
		'date_naissance' => 'datetime',
		'langue_id' => 'int',
		'region_id' => 'int',
		'role_id' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'nom',
		'prenom',
		'password',
		'email',
		'date_naissance',
		'statut',
		'langue_id',
		'region_id',
		'role_id'
	];

	public function langue()
	{
		return $this->belongsTo(Langue::class);
	}
	public function region()
	{
		return $this->belongsTo(Region::class);
	}
	public function role()
	{
		return $this->belongsTo(Role::class);
	}
}
