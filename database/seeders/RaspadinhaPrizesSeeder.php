<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Raspadinha;
use App\Models\RaspadinhaPrize;

class RaspadinhaPrizesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $raspadinhasPrizes = [
            'sorte-verde' => [
                ['name' => 'Nada', 'value' => 0.00, 'probability' => 60.00, 'is_jackpot' => false],
                ['name' => '1 Real', 'value' => 1.00, 'probability' => 25.00, 'is_jackpot' => false],
                ['name' => '5 Reais', 'value' => 5.00, 'probability' => 10.00, 'is_jackpot' => false],
                ['name' => '20 Reais', 'value' => 20.00, 'probability' => 4.00, 'is_jackpot' => true],
                ['name' => '100 Reais', 'value' => 100.00, 'probability' => 1.00, 'is_jackpot' => true],
            ],
            'mega-premios' => [
                ['name' => 'Nada', 'value' => 0.00, 'probability' => 70.00, 'is_jackpot' => false],
                ['name' => '2 Reais', 'value' => 2.00, 'probability' => 20.00, 'is_jackpot' => false],
                ['name' => '10 Reais', 'value' => 10.00, 'probability' => 8.00, 'is_jackpot' => false],
                ['name' => '100 Reais', 'value' => 100.00, 'probability' => 2.00, 'is_jackpot' => true],
            ],
            'fortuna-facil' => [
                ['name' => 'Nada', 'value' => 0.00, 'probability' => 50.00, 'is_jackpot' => false],
                ['name' => '0,50 Centavos', 'value' => 0.50, 'probability' => 30.00, 'is_jackpot' => false],
                ['name' => '2 Reais', 'value' => 2.00, 'probability' => 15.00, 'is_jackpot' => false],
                ['name' => '10 Reais', 'value' => 10.00, 'probability' => 5.00, 'is_jackpot' => true],
            ],
            'jackpot-especial' => [
                ['name' => 'Nada', 'value' => 0.00, 'probability' => 75.00, 'is_jackpot' => false],
                ['name' => '5 Reais', 'value' => 5.00, 'probability' => 15.00, 'is_jackpot' => false],
                ['name' => '50 Reais', 'value' => 50.00, 'probability' => 8.00, 'is_jackpot' => false],
                ['name' => '500 Reais', 'value' => 500.00, 'probability' => 2.00, 'is_jackpot' => true],
            ],
        ];

        $raspadinhas = Raspadinha::active()->get();

        foreach ($raspadinhas as $raspadinha) {
            $this->command->info("Configurando prêmios para: {$raspadinha->name}");
            
            RaspadinhaPrize::where('raspadinha_id', $raspadinha->id)->delete();
            
            if (isset($raspadinhasPrizes[$raspadinha->name])) {
                $prizes = $raspadinhasPrizes[$raspadinha->name];
                
                foreach ($prizes as $prizeData) {
                    RaspadinhaPrize::create([
                        'raspadinha_id' => $raspadinha->id,
                        'name' => $prizeData['name'],
                        'value' => $prizeData['value'],
                        'img' => $this->getPrizeImage($prizeData['name'], $prizeData['value']),
                        'probability' => $prizeData['probability'],
                        'display_value' => $prizeData['value'] > 0 ? 'R$ ' . number_format($prizeData['value'], 2, ',', '.') : 'Tente novamente',
                        'is_jackpot' => $prizeData['is_jackpot'],
                        'max_wins' => $prizeData['is_jackpot'] ? 5 : null,
                        'current_wins' => 0,
                        'active' => true,
                    ]);
                    
                    $this->command->info("  - Prêmio '{$prizeData['name']}' criado");
                }
                
                $totalProb = array_sum(array_column($prizes, 'probability'));
                $this->command->info("  Total de probabilidades: {$totalProb}%");
            } else {
                $this->command->warn("  Nenhuma configuração encontrada para: {$raspadinha->name}");
            }
        }

        $this->command->info("Prêmios configurados com sucesso!");
    }
    
    private function getPrizeImage($name, $value)
    {
        if ($value == 0) {
            return 'https://ik.imagekit.io/azx3nlpdu/nada.png';
        }
        
        $imageMap = [
            '0,50 Centavos' => 'https://ik.imagekit.io/azx3nlpdu/50-CENTAVOS-2.png?updatedAt=1752864509979',
            '1 Real' => 'https://ik.imagekit.io/azx3nlpdu/Notas/1%20REAL.png?updatedAt=1752047821586',
            '2 Reais' => 'https://ik.imagekit.io/azx3nlpdu/Notas/2%20REAIS.png?updatedAt=1752047821644',
            '5 Reais' => 'https://ik.imagekit.io/azx3nlpdu/Notas/5%20REAIS.png?updatedAt=1752047821734',
            '10 Reais' => 'https://ik.imagekit.io/azx3nlpdu/Notas/10%20REAIS.png?updatedAt=1752047821875',
            '20 Reais' => 'https://ik.imagekit.io/azx3nlpdu/Notas/20%20REAIS.png?updatedAt=1752047821716',
            '50 Reais' => 'https://ik.imagekit.io/azx3nlpdu/Notas/50%20REAIS.png?updatedAt=1752047821745',
            '100 Reais' => 'https://ik.imagekit.io/azx3nlpdu/Notas/100%20REAIS.png?updatedAt=1752047821876',
            '500 Reais' => 'https://ik.imagekit.io/azx3nlpdu/500-REAIS.png?updatedAt=1752856623150',
        ];
        
        return $imageMap[$name] ?? 'https://ik.imagekit.io/azx3nlpdu/default.png';
    }
}
