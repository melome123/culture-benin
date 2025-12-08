<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContenuRating extends Model
{
    protected $table = 'contenu_ratings';

    protected $fillable = [
        'contenu_id',
        'user_id',
        'rating',
    ];

    public function contenu()
    {
        return $this->belongsTo(Contenu::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
