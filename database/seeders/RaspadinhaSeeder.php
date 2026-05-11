<?php

namespace Database\Seeders;

use App\Models\Raspadinha;
use App\Models\RaspadinhaPrize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RaspadinhaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $raspadinhas = [
            [
                'name' => 'sorte-verde',
                'title' => 'Sorte Verde',
                'description' => 'Sua chance de ganhar está aqui! Raspe e descubra prêmios incríveis.',
                'value' => 2.00,
                'max_sales' => 1000,
                'active' => true,
                'prizes' => [
                    ['name' => 'Nada', 'value' => 0, 'probability' => 50, 'display_value' => 'R$ 0,00'],
                    ['name' => 'R$ 1,00', 'value' => 1, 'probability' => 20, 'display_value' => 'R$ 1,00'],
                    ['name' => 'R$ 5,00', 'value' => 5, 'probability' => 20, 'display_value' => 'R$ 5,00'],
                    ['name' => 'R$ 20,00', 'value' => 20, 'probability' => 10, 'display_value' => 'R$ 20,00', 'is_jackpot' => true],
                ]
            ],
            [
                'name' => 'mega-premios',
                'title' => 'Mega Prêmios',
                'description' => 'Os maiores prêmios estão aqui! Raspe e ganhe até R$ 100,00!',
                'value' => 5.00,
                'max_sales' => 500,
                'active' => true,
                'prizes' => [
                    ['name' => 'Nada', 'value' => 0, 'probability' => 60, 'display_value' => 'Tente novamente'],
                    ['name' => 'R$ 2,00', 'value' => 2, 'probability' => 20, 'display_value' => 'R$ 2,00'],
                    ['name' => 'R$ 10,00', 'value' => 10, 'probability' => 15, 'display_value' => 'R$ 10,00'],
                    ['name' => 'R$ 100,00', 'value' => 100, 'probability' => 5, 'display_value' => 'R$ 100,00', 'is_jackpot' => true, 'max_wins' => 10],
                ]
            ],
            [
                'name' => 'fortuna-facil',
                'title' => 'Fortuna Fácil',
                'description' => 'Entrada barata, prêmios garantidos! Ideal para começar a ganhar.',
                'value' => 1.00,
                'max_sales' => 2000,
                'active' => true,
                'prizes' => [
                    ['name' => 'Nada', 'value' => 0, 'probability' => 40, 'display_value' => 'R$ 0,00'],
                    ['name' => 'R$ 0,50', 'value' => 0.50, 'probability' => 30, 'display_value' => 'R$ 0,50'],
                    ['name' => 'R$ 2,00', 'value' => 2, 'probability' => 25, 'display_value' => 'R$ 2,00'],
                    ['name' => 'R$ 10,00', 'value' => 10, 'probability' => 5, 'display_value' => 'R$ 10,00', 'is_jackpot' => true],
                ]
            ],
            [
                'name' => 'jackpot-especial',
                'title' => 'Jackpot Especial',
                'description' => 'Edição limitada! Prêmios especiais por tempo limitado.',
                'value' => 10.00,
                'max_sales' => 100,
                'active' => true,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'prizes' => [
                    ['name' => 'Nada', 'value' => 0, 'probability' => 70, 'display_value' => 'Sem prêmio'],
                    ['name' => 'R$ 5,00', 'value' => 5, 'probability' => 15, 'display_value' => 'R$ 5,00'],
                    ['name' => 'R$ 50,00', 'value' => 50, 'probability' => 10, 'display_value' => 'R$ 50,00'],
                    ['name' => 'R$ 500,00', 'value' => 500, 'probability' => 5, 'display_value' => 'R$ 500,00', 'is_jackpot' => true, 'max_wins' => 2],
                ]
            ]
        ];

        foreach ($raspadinhas as $raspadinhaData) {
            $prizes = $raspadinhaData['prizes'];
            unset($raspadinhaData['prizes']);
            
            $raspadinha = Raspadinha::create($raspadinhaData);

            foreach ($prizes as $prizeData) {
                RaspadinhaPrize::create(array_merge($prizeData, [
                    'raspadinha_id' => $raspadinha->id,
                    'is_jackpot' => $prizeData['is_jackpot'] ?? false,
                    'max_wins' => $prizeData['max_wins'] ?? null,
                    'current_wins' => 0,
                    'active' => true,
                ]));
            }
        }

        $this->command->info('Raspadinhas de exemplo criadas com sucesso!');
    }
}
