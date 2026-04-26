<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultatDiag extends Model
{
    use HasFactory;

    protected $table = 'resultat_diags';

    protected $fillable = [
        'id_user',
        'date_passage',
        'score_total',
        'niveau_stress',
        'event_ids'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function getEventsAttribute()
    {
        if (!$this->event_ids) return collect();
        $ids = explode(';', $this->event_ids);
        return StressEvent::whereIn('id', $ids)->get();
    }
}
