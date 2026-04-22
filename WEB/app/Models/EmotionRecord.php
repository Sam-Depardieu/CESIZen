<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmotionRecord extends Model
{
    protected $fillable = ['user_id', 'emotion', 'intensity', 'note'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
