<?php

namespace Database\Seeders;

use App\Models\RelaxationActivity;
use Illuminate\Database\Seeder;

class RelaxationActivitySeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            [
                'title' => 'Méditation de Pleine Conscience',
                'description' => 'Une séance guidée de 10 minutes pour se reconnecter au moment présent et calmer le flux des pensées.',
                'type' => 'audio',
                'category' => 'Méditation',
                'url' => 'https://www.youtube.com/watch?v=sz7cpV7ERsY',
                'image_url' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            ],
            [
                'title' => 'Yoga Doux pour le Soir',
                'description' => 'Étirements légers et postures relaxantes pour préparer le corps au sommeil et relâcher les tensions de la journée.',
                'type' => 'video',
                'category' => 'Sport',
                'url' => 'https://www.youtube.com/watch?v=v7AYKMP6rOE',
                'image_url' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            ],
            [
                'title' => 'Bruits de Plage et Vagues',
                'description' => 'Ambiance sonore apaisante pour le travail ou la lecture. Réduit le stress environnant.',
                'type' => 'audio',
                'category' => 'Musique',
                'url' => 'https://www.youtube.com/watch?v=0_fS_pS_B0U',
                'image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            ],
            [
                'title' => 'Comprendre le Cycle du Stress',
                'description' => 'Un article détaillé sur les mécanismes biologiques du stress et comment les réguler au quotidien.',
                'type' => 'article',
                'category' => 'Lecture',
                'url' => 'https://www.santepubliquefrance.fr',
                'image_url' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            ],
            [
                'title' => 'Musique Classique pour la Concentration',
                'description' => 'Sélection de morceaux choisis pour favoriser le focus et la clarté mentale.',
                'type' => 'audio',
                'category' => 'Musique',
                'url' => 'https://www.youtube.com/watch?v=5Qq3m9G4_4A',
                'image_url' => 'https://images.unsplash.com/photo-1514119412350-e174d90d280e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            ],
            [
                'title' => 'Techniques de Respiration Wim Hof',
                'description' => 'Apprenez la méthode de respiration qui renforce le système immunitaire et réduit l\'anxiété.',
                'type' => 'video',
                'category' => 'Respiration',
                'url' => 'https://www.youtube.com/watch?v=tybOi4hjZFQ',
                'image_url' => 'https://images.unsplash.com/photo-1518199266791-5375a83190b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            ]
        ];

        foreach ($activities as $activity) {
            RelaxationActivity::create($activity);
        }
    }
}
