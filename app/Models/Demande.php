<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    protected $table = 'demandes';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'date_demande',
        'statut',
    ];

    protected $casts = [
        'date_demande' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

