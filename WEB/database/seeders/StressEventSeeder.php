<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StressEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            ['event_name' => 'Décès du conjoint', 'points' => 100],
            ['event_name' => 'Divorce', 'points' => 73],
            ['event_name' => 'Séparation conjugale', 'points' => 65],
            ['event_name' => 'Emprisonnement', 'points' => 63],
            ['event_name' => 'Décès d’un proche parent', 'points' => 63],
            ['event_name' => 'Blessure ou maladie grave', 'points' => 53],
            ['event_name' => 'Mariage', 'points' => 50],
            ['event_name' => 'Perte d’emploi', 'points' => 47],
            ['event_name' => 'Réconciliation conjugale', 'points' => 45],
            ['event_name' => 'Retraite', 'points' => 45],
            ['event_name' => 'Changement de santé d’un membre de la famille', 'points' => 44],
            ['event_name' => 'Grossesse', 'points' => 40],
            ['event_name' => 'Difficultés sexuelles', 'points' => 39],
            ['event_name' => 'Arrivée d’un nouveau membre dans la famille', 'points' => 39],
            ['event_name' => 'Réorganisation professionnelle', 'points' => 39],
            ['event_name' => 'Changement de situation financière', 'points' => 38],
            ['event_name' => 'Décès d’un ami proche', 'points' => 37],
            ['event_name' => 'Changement de métier', 'points' => 36],
            ['event_name' => 'Augmentation de disputes conjugales', 'points' => 35],
            ['event_name' => 'Hypothèque importante', 'points' => 31],
            ['event_name' => 'Saisie de biens', 'points' => 30],
            ['event_name' => 'Changement de responsabilités professionnelles', 'points' => 29],
            ['event_name' => 'Départ d’un enfant du foyer', 'points' => 29],
            ['event_name' => 'Problèmes avec la belle-famille', 'points' => 29],
            ['event_name' => 'Succès personnel remarquable', 'points' => 28],
            ['event_name' => 'Conjoint commence ou arrête de travailler', 'points' => 26],
            ['event_name' => 'Début ou fin d’études', 'points' => 26],
            ['event_name' => 'Changement de conditions de vie', 'points' => 25],
            ['event_name' => 'Révision d’habitudes personnelles', 'points' => 24],
            ['event_name' => 'Problèmes avec le supérieur hiérarchique', 'points' => 23],
            ['event_name' => 'Changement d’horaires ou de conditions de travail', 'points' => 20],
            ['event_name' => 'Déménagement', 'points' => 20],
            ['event_name' => 'Changement d’école', 'points' => 20],
            ['event_name' => 'Changement d’activités sociales', 'points' => 18],
            ['event_name' => 'Petit prêt ou hypothèque', 'points' => 17],
            ['event_name' => 'Changement d’habitudes de sommeil', 'points' => 16],
            ['event_name' => 'Changement d’habitudes alimentaires', 'points' => 15],
            ['event_name' => 'Vacances', 'points' => 13],
            ['event_name' => 'Fêtes de fin d’année', 'points' => 12],
            ['event_name' => 'Petites infractions à la loi', 'points' => 11],
        ];

        foreach ($events as $event) {
            \App\Models\StressEvent::create($event);
        }
    }
}
