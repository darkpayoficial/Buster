<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Raspadinha;
use App\Models\UltimosGanhos;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\JogoHistorico;
use Illuminate\Support\Facades\Log;

class RaspadinhasController extends Controller
{
    public function index(Request $request)
    {
        $config = Config::getSystemConfig();
        $user = $request->user();

        if (!$config) {
            $config = [
                'id' => 1,
                'app_name' => 'RASPA GREEN',
                'logo' => '/logo.svg',
                'favicon' => '/favicon.svg',
                'footer_text' => 'Sistema de Raspadinhas - Sua sorte está aqui!',
                'contact_email' => 'contato@raspagreen.com.br',
                'contact_phone' => '(11) 99999-9999',
                'address' => 'São Paulo, SP - Brasil'
            ];
        } else {
            $config = $config->toArray();
        }

        $ultimosGanhos = UltimosGanhos::active()
            ->recent()
            ->limit(50)
            ->orderByDesc('valueprize')
            ->get()
            ->toArray();

        $raspadinhas = Raspadinha::active()->available()->orderBy('value')->get()->map(function ($raspadinha) {
            return [
                'id' => $raspadinha->id,
                'name' => $raspadinha->name,
                'title' => $raspadinha->title,
                'description' => $raspadinha->description,
                'photo' => $raspadinha->photo,
                'value' => $raspadinha->value,
                'max_prize' => $this->getMaxPrize($raspadinha),
                'hot' => $raspadinha->hot,
                'slug' => $this->generateSlug($raspadinha->name),
                'active' => $raspadinha->active,
            ];
        })->toArray();

        return Inertia::render('raspadinhas', [
            'config' => $config,
            'user' => $user ? $user->toArray() : null,
            'raspadinhas' => $raspadinhas,
            'ultimosGanhos' => $ultimosGanhos,
        ]);
    }

    /**
     * Exibir página individual da raspadinha
     */
    public function show(Request $request, $slug)
    {
        $config = Config::getSystemConfig();
        $user = $request->user();

        if (!$config) {
            $config = [
                'id' => 1,
                'app_name' => 'RASPA GREEN',
                'logo' => '/logo.svg',
                'favicon' => '/favicon.svg',
                'footer_text' => 'Sistema de Raspadinhas - Sua sorte está aqui!',
                'contact_email' => 'contato@raspagreen.com.br',
                'contact_phone' => '(11) 99999-9999',
                'address' => 'São Paulo, SP - Brasil'
            ];
        } else {
            $config = $config->toArray();
        }

        $raspadinha = Raspadinha::active()
            ->available()
            ->get()
            ->first(function ($item) use ($slug) {
                return $this->generateSlug($item->name) === $slug;
            });

        if (!$raspadinha) {
            abort(404, 'Raspadinha não encontrada');
        }

        $prizes = $raspadinha->activePrizes()
            ->orderBy('value', 'desc')
            ->get()
            ->map(function ($prize) {
                return [
                    'id' => $prize->id,
                    'name' => $prize->name,
                    'value' => $prize->value,
                    'img' => $prize->img,
                    'probability' => $prize->probability,
                    'display_value' => $prize->display_value,
                    'is_jackpot' => $prize->is_jackpot,
                    'max_wins' => $prize->max_wins,
                    'current_wins' => $prize->current_wins,
                    'active' => $prize->active,
                ];
            })
            ->toArray();

        $ultimosGanhos = UltimosGanhos::active()
            ->recent()
            ->limit(50)
            ->orderByDesc('valueprize')
            ->get()
            ->toArray();


        $raspadinhaData = [
            'id' => $raspadinha->id,
            'name' => $raspadinha->name,
            'title' => $raspadinha->title,
            'description' => $raspadinha->description,
            'photo' => $raspadinha->photo,
            'value' => $raspadinha->value,
            'max_prize' => $this->getMaxPrize($raspadinha),
            'hot' => $raspadinha->hot,
            'slug' => $this->generateSlug($raspadinha->name),
            'active' => $raspadinha->active,
            'totalbuy' => $raspadinha->totalbuy,
            'max_sales' => $raspadinha->max_sales,
            'start_date' => $raspadinha->start_date,
            'end_date' => $raspadinha->end_date,
        ];

        return Inertia::render('Raspadinha', [
            'config' => $config,
            'user' => $user ? $user->toArray() : null,
            'raspadinha' => $raspadinhaData,
            'prizes' => $prizes,
            'ultimosGanhos' => $ultimosGanhos,
        ]);
    }

    /**
     * API endpoint para busca de raspadinhas
     */
    public function search(Request $request)
    {
        $term = $request->get('q', '');

        if (strlen($term) < 3) {
            return response()->json([
                'error' => 'Pesquisa mínima de 3 caracteres',
                'results' => []
            ]);
        }

        $raspadinhas = Raspadinha::active()
            ->available()
            ->where(function ($query) use ($term) {
                $query->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('title', 'LIKE', "%{$term}%")
                    ->orWhere('description', 'LIKE', "%{$term}%");
            })
            ->orderBy('value')
            ->limit(12)
            ->get()
            ->map(function ($raspadinha) {
                return [
                    'id' => $raspadinha->id,
                    'name' => $raspadinha->name,
                    'title' => $raspadinha->title,
                    'description' => $raspadinha->description,
                    'photo' => $raspadinha->photo,
                    'value' => $raspadinha->value,
                    'max_prize' => $this->getMaxPrize($raspadinha),
                    'hot' => $raspadinha->hot,
                    'slug' => $this->generateSlug($raspadinha->name),
                    'active' => $raspadinha->active,
                ];
            });

        return response()->json([
            'results' => $raspadinhas,
            'total' => $raspadinhas->count()
        ]);
    }

    /**
     * Comprar uma raspadinha
     */
    public function buy(Request $request, Raspadinha $raspadinha)
    {
        $user = $request->user();

        if (!$raspadinha->canBeSold()) {
            return response()->json([
                'success' => false,
                'message' => 'Esta raspadinha não está disponível para compra.'
            ], 400);
        }

        if ($user->balance < $raspadinha->value) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo insuficiente para comprar esta raspadinha.',
                'required' => $raspadinha->value,
                'current_balance' => $user->balance
            ], 400);
        }

        $user->decrement('balance', $raspadinha->value);

        $raspadinha->incrementSales();

        if ($user->isInfluencer()) {
            $prize = $this->drawInfluencerPrize($raspadinha);
        } else {
            $prize = $raspadinha->drawPrize();
        }

        if ($prize) {
            $prize->incrementWins();

            if ($prize->value > 0) {
                $user->increment('balance', $prize->value);

                if ($prize->value > 100) {
                    UltimosGanhos::create([
                        'namewin' => $user->nomecompleto ?? $user->username,
                        'prizename' => $prize->name,
                        'valueprize' => $prize->value,
                        'imgprize' => $prize->img,
                        'active' => true
                    ]);
                }
            }

            JogoHistorico::create([
                'user_id' => $user->id,
                'raspadinha_id' => $raspadinha->id,
                'raspadinha_name' => $raspadinha->title,
                'prize_id' => $prize->id,
                'prize_name' => $prize->name,
                'prize_value' => $prize->value,
                'prize_img' => $prize->img,
                'status' => $prize->value > 0 ? 'win' : 'loss'
            ]);

            $grid = $this->generateGrid($raspadinha, $prize);

            return response()->json([
                'success' => true,
                'won' => $prize->value > 0,
                'prize' => [
                    'id' => $prize->id,
                    'name' => $prize->name,
                    'value' => $prize->value,
                    'display_value' => $prize->display_value,
                    'img' => $prize->img,
                    'image' => $prize->img,
                    'is_jackpot' => $prize->is_jackpot,
                ],
                'grid' => $grid,
                'user_balance' => $user->fresh()->balance,
                'message' => $prize->value > 0
                    ? "Parabéns! Você ganhou {$prize->display_value}!"
                    : 'Não foi desta vez! Tente novamente.'
            ]);
        }

        $user->increment('balance', $raspadinha->value);

        return response()->json([
            'success' => false,
            'message' => 'Erro no sorteio. Seu saldo foi reembolsado.',
            'user_balance' => $user->fresh()->balance
        ], 500);
    }

    /**
     * Gera o tabuleiro 3x3 baseado no resultado do sorteio
     */
    /**
     * Gera o tabuleiro 3x3 baseado no resultado do sorteio
     */
    private function generateGrid(Raspadinha $raspadinha, $prize): array
    {
        $allPrizes = $raspadinha->activePrizes()->where('value', '>', 0)->get();
        $grid = [];

        if ($prize->value > 0) {
            $canShowWinningPrize3Times = true;
            if ($prize->max_wins === 0) {
                $canShowWinningPrize3Times = false;
            }

            if ($canShowWinningPrize3Times) {
                $winningPrizeData = [
                    'id' => $prize->id,
                    'name' => $prize->name,
                    'value' => $prize->value,
                    'display_value' => $prize->display_value,
                    'img' => $prize->img,
                    'image' => $prize->img,
                    'is_jackpot' => $prize->is_jackpot,
                ];

                for ($i = 0; $i < 3; $i++) {
                    $grid[] = $winningPrizeData;
                }

                $otherPrizes = $allPrizes->where('id', '!=', $prize->id);
                $this->fillGridPositions($grid, $otherPrizes, 6);
            } else {
                $this->fillGridPositions($grid, $allPrizes, 9, $prize->id);
            }
        } else {
            $this->fillGridPositions($grid, $allPrizes, 9);
        }

        shuffle($grid);

        return $grid;
    }

    /**
     * Preenche posições do grid com prêmios (máximo 2 de cada, exceto o prêmio ganho que aparece 3 vezes)
     */
    private function fillGridPositions(array &$grid, $availablePrizes, int $positions, $forcePrizeId = null): void
    {
        if ($availablePrizes->isEmpty()) {
            return;
        }

        $prizeCount = [];

        if ($forcePrizeId) {
            $forcePrize = $availablePrizes->where('id', $forcePrizeId)->first();
            if ($forcePrize) {
                for ($i = 0; $i < 2 && count($grid) < $positions; $i++) {
                    $grid[] = [
                        'id' => $forcePrize->id,
                        'name' => $forcePrize->name,
                        'value' => $forcePrize->value,
                        'display_value' => $forcePrize->display_value,
                        'img' => $forcePrize->img,
                        'image' => $forcePrize->img,
                        'is_jackpot' => $forcePrize->is_jackpot,
                    ];
                }
                $prizeCount[$forcePrize->id] = 2;
                $positions -= 2;
            }
        }

        for ($i = 0; $i < $positions && count($grid) < 9; $i++) {
            $availableForSelection = $availablePrizes->filter(function ($p) use ($prizeCount) {
                $currentCount = $prizeCount[$p->id] ?? 0;

                if ($p->max_wins === 0) {
                    return $currentCount < 2;
                }

                return $currentCount < 2;
            });

            if ($availableForSelection->isEmpty()) {
                break;
            }

            $selectedPrize = $this->selectPrizeByProbability($availableForSelection, true);

            if (!$selectedPrize) {
                break;
            }

            if (!isset($prizeCount[$selectedPrize->id])) {
                $prizeCount[$selectedPrize->id] = 0;
            }
            $prizeCount[$selectedPrize->id]++;

            $grid[] = [
                'id' => $selectedPrize->id,
                'name' => $selectedPrize->name,
                'value' => $selectedPrize->value,
                'display_value' => $selectedPrize->display_value,
                'img' => $selectedPrize->img,
                'image' => $selectedPrize->img,
                'is_jackpot' => $selectedPrize->is_jackpot,
            ];
        }
    }

    /**
     * Seleciona um prêmio baseado nas probabilidades
     */
    private function selectPrizeByProbability($prizes, $favorLowerValue = false)
    {
        $availablePrizes = collect($prizes)->filter(function ($prize) {
            if ($prize->max_wins === 0) {
                return false;
            }
            if ($prize->max_wins === null) {
                return true;
            }
            return $prize->current_wins < $prize->max_wins;
        });

        if ($availablePrizes->isEmpty()) {
            return collect($prizes)
                ->where('value', 0)
                ->first();
        }

        $totalProbability = $availablePrizes->sum('probability');

        if ($totalProbability === 0) {
            return collect($prizes)
                ->where('value', 0)
                ->first();
        }

        if ($totalProbability !== 100) {
            $factor = 100 / $totalProbability;
            $availablePrizes = $availablePrizes->map(function ($prize) use ($factor) {
                $prize->probability *= $factor;
                return $prize;
            });
        }

        $random = mt_rand(0, 10000) / 100;
        $currentSum = 0;

        if ($favorLowerValue) {
            $availablePrizes = $availablePrizes->sortBy('value');
        }

        foreach ($availablePrizes as $prize) {
            $currentSum += $prize->probability;
            if ($random <= $currentSum) {
                return $prize;
            }
        }

        return collect($prizes)
            ->where('value', 0)
            ->first();
    }

    /**
     * Calcula o prêmio máximo da raspadinha
     */
    private function getMaxPrize(Raspadinha $raspadinha): float
    {
        $maxPrize = $raspadinha->activePrizes()->max('value');
        return $maxPrize ?? 0;
    }

    /**
     * Gera slug a partir do nome
     */
    private function generateSlug(string $name): string
    {
        return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $name));
    }

    /**
     * Sorteia um prêmio aleatório para influenciadores (sem considerar probabilidades)
     */
    private function drawInfluencerPrize(Raspadinha $raspadinha)
    {
        $activePrizes = $raspadinha->activePrizes()->get();

        if ($activePrizes->isEmpty()) {
            \Log::error('Nenhum prêmio ativo encontrado para raspadinha', [
                'user_id' => auth()->id(),
                'raspadinha_id' => $raspadinha->id,
                'raspadinha_name' => $raspadinha->name
            ]);
            return null;
        }

        $availablePrizes = $activePrizes->filter(function ($prize) {
            return ($prize->max_wins === 0 || $prize->current_wins < $prize->max_wins) && $prize->value > 0;
        });

        if ($availablePrizes->isEmpty()) {
            $fallbackPrize = $activePrizes->where('value', '>', 0)->sortBy('value')->first();
            if (!$fallbackPrize) {
                \Log::warning('Nenhum prêmio com valor disponível para influenciador', [
                    'user_id' => auth()->id(),
                    'raspadinha_id' => $raspadinha->id
                ]);
                return null;
            }
            return $fallbackPrize;
        }

        $availablePrizesArray = $availablePrizes->values()->all();

        if (empty($availablePrizesArray)) {
            \Log::warning('Nenhum prêmio disponível para influenciador', [
                'user_id' => auth()->id(),
                'raspadinha_id' => $raspadinha->id,
                'total_prizes' => $activePrizes->count(),
                'available_prizes' => $availablePrizes->count()
            ]);
            return $activePrizes->first();
        }

        $randomIndex = array_rand($availablePrizesArray);
        $selectedPrize = $availablePrizesArray[$randomIndex];

        if (!$selectedPrize) {
            \Log::error('Prêmio selecionado é null para influenciador', [
                'user_id' => auth()->id(),
                'raspadinha_id' => $raspadinha->id,
                'random_index' => $randomIndex,
                'array_count' => count($availablePrizesArray)
            ]);
            return $activePrizes->first();
        }

        \Log::info('Prêmio sorteado para influenciador', [
            'user_id' => auth()->id(),
            'raspadinha_id' => $raspadinha->id,
            'prize_id' => $selectedPrize->id,
            'prize_name' => $selectedPrize->name,
            'prize_value' => $selectedPrize->value,
            'is_influencer' => true,
            'total_available' => count($availablePrizesArray)
        ]);

        return $selectedPrize;
    }
}
