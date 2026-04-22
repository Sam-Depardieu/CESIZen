<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelaxationActivity extends Model
{
    protected $fillable = ['title', 'type', 'duration', 'url', 'description'];

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'id_activite', 'id_user');
    }
}
