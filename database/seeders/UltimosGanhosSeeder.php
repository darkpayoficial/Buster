<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UltimosGanhos;

class UltimosGanhosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ganhos = [
            [
                'namewin' => 'João S***',
                'prizename' => 'Raspadinha Premium',
                'valueprize' => 25.00,
                'imgprize' => 'https://ik.imagekit.io/azx3nlpdu/Notas/25%20REAIS.png?updatedAt=1752047821875',
                'active' => true,
            ],
            [
                'namewin' => 'Maria A***',
                'prizename' => 'Super Prêmio',
                'valueprize' => 50.00,
                'imgprize' => 'https://ik.imagekit.io/azx3nlpdu/Notas/50%20REAIS.png?updatedAt=1752047821875',
                'active' => true,
            ],
            [
                'namewin' => 'Pedro L***',
                'prizename' => 'Mega Sorte',
                'valueprize' => 100.00,
                'imgprize' => 'https://ik.imagekit.io/azx3nlpdu/Notas/100%20REAIS.png?updatedAt=1752047821875',
                'active' => true,
            ],
            [
                'namewin' => 'Ana C***',
                'prizename' => 'Jackpot',
                'valueprize' => 500.00,
                'imgprize' => 'https://ik.imagekit.io/azx3nlpdu/Notas/500%20REAIS.png?updatedAt=1752047821875',
                'active' => true,
            ],
            [
                'namewin' => 'Carlos M***',
                'prizename' => 'Prêmio Especial',
                'valueprize' => 10.00,
                'imgprize' => 'https://ik.imagekit.io/azx3nlpdu/Notas/10%20REAIS.png?updatedAt=1752047821875',
                'active' => true,
            ],
            [
                'namewin' => 'Lucia F***',
                'prizename' => 'Sorte Grande',
                'valueprize' => 200.00,
                'imgprize' => 'https://ik.imagekit.io/azx3nlpdu/Notas/200%20REAIS.png?updatedAt=1752047821875',
                'active' => true,
            ],
            [
                'namewin' => 'Roberto K***',
                'prizename' => 'Prêmio de Ouro',
                'valueprize' => 1000.00,
                'imgprize' => 'https://ik.imagekit.io/azx3nlpdu/Notas/1000%20REAIS.png?updatedAt=1752047821875',
                'active' => true,
            ],
            [
                'namewin' => 'Sandra P***',
                'prizename' => 'Mini Jackpot',
                'valueprize' => 75.00,
                'imgprize' => 'https://ik.imagekit.io/azx3nlpdu/Notas/75%20REAIS.png?updatedAt=1752047821875',
                'active' => true,
            ]
        ];

        foreach ($ganhos as $ganho) {
            UltimosGanhos::create($ganho);
        }
    }
}
