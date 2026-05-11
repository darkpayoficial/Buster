<?php

namespace App\Console\Commands;

use App\Models\Raspadinha;
use App\Models\RaspadinhaPrize;
use Illuminate\Console\Command;

class TestRaspadinha extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'raspadinha:test 
                            {--id= : ID específico da raspadinha}
                            {--times=100 : Número de testes a executar}
                            {--show-results : Mostra todos os resultados individuais}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa o sistema de sorteio das raspadinhas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $raspadinhaId = $this->option('id');
        $times = (int) $this->option('times');
        $showResults = $this->option('show-results');

        if (!$raspadinhaId) {
            $this->listRaspadinhas();
            $raspadinhaId = $this->ask('Digite o ID da raspadinha para testar');
        }

        $raspadinha = Raspadinha::with('prizes')->find($raspadinhaId);

        if (!$raspadinha) {
            $this->error("Raspadinha com ID {$raspadinhaId} não encontrada!");
            return 1;
        }

        $this->info("Testando raspadinha: {$raspadinha->title}");
        $this->info("Valor: {$raspadinha->formatted_value}");
        $this->info("Executando {$times} sorteios...\n");

        $results = [];
        $totalValue = 0;

        for ($i = 1; $i <= $times; $i++) {
            $prize = $raspadinha->drawPrize();
            
            if ($prize) {
                $results[$prize->name] = ($results[$prize->name] ?? 0) + 1;
                $totalValue += $prize->value;

                if ($showResults) {
                    $this->line("Teste #{$i}: {$prize->name} - {$prize->display_value}");
                }
            } else {
                $results['Erro'] = ($results['Erro'] ?? 0) + 1;
                if ($showResults) {
                    $this->line("Teste #{$i}: ERRO - Nenhum prêmio disponível");
                }
            }
        }

        $this->newLine();
        $this->info('=== RESULTADOS DOS TESTES ===');
        
        $tableData = [];
        foreach ($raspadinha->prizes as $prize) {
            $count = $results[$prize->name] ?? 0;
            $percentage = ($count / $times) * 100;
            $expectedPercentage = $prize->probability;
            
            $tableData[] = [
                $prize->name,
                $prize->display_value,
                $expectedPercentage . '%',
                $count,
                number_format($percentage, 2) . '%',
                $prize->value * $count
            ];
        }

        $this->table([
            'Prêmio',
            'Valor',
            'Prob. Esperada',
            'Vezes Ganho',
            'Prob. Real',
            'Total Ganho'
        ], $tableData);

        $investmentTotal = $raspadinha->value * $times;
        $profit = $investmentTotal - $totalValue;
        $profitPercentage = ($profit / $investmentTotal) * 100;

        $this->newLine();
        $this->info('=== ANÁLISE FINANCEIRA ===');
        $this->line("Total investido: R$ " . number_format($investmentTotal, 2, ',', '.'));
        $this->line("Total ganho pelos jogadores: R$ " . number_format($totalValue, 2, ',', '.'));
        $this->line("Lucro da casa: R$ " . number_format($profit, 2, ',', '.'));
        $this->line("Margem de lucro: " . number_format($profitPercentage, 2) . '%');

        $this->newLine();
        $this->info('=== VALIDAÇÃO ===');
        $totalProbability = $raspadinha->prizes->sum('probability');
        
        if ($totalProbability == 100) {
            $this->info('✅ Probabilidades somam 100% corretamente');
        } else {
            $this->warn("⚠️  Probabilidades somam {$totalProbability}% (deveria ser 100%)");
        }

        return 0;
    }

    private function listRaspadinhas()
    {
        $raspadinhas = Raspadinha::all();
        
        if ($raspadinhas->isEmpty()) {
            $this->warn('Nenhuma raspadinha encontrada! Execute: php artisan db:seed --class=RaspadinhaSeeder');
            return;
        }

        $this->info('=== RASPADINHAS DISPONÍVEIS ===');
        
        $tableData = [];
        foreach ($raspadinhas as $raspadinha) {
            $tableData[] = [
                $raspadinha->id,
                $raspadinha->title,
                $raspadinha->formatted_value,
                $raspadinha->active ? '✅ Ativo' : '❌ Inativo',
                $raspadinha->prizes->count() . ' prêmios'
            ];
        }

        $this->table(['ID', 'Título', 'Valor', 'Status', 'Prêmios'], $tableData);
    }
}
