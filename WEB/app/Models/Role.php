<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    // Cette ligne est CRUCIALE : elle autorise Laravel à remplir la colonne 'libelle'
    protected $fillable = ['libelle'];

    public function users()
    {
        return $this->hasMany(User::class, 'id_role');
    }
}
