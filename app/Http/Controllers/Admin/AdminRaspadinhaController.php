<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Raspadinha;
use App\Models\RaspadinhaPrize;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AdminRaspadinhaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $query = Raspadinha::with(['prizes']);

        if ($request->has('active')) {
            $query->where('active', $request->boolean('active'));
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $raspadinhas = $query->orderBy('created_at', 'desc')->paginate(15)->through(function ($raspadinha) {
            return [
                'id' => $raspadinha->id,
                'name' => $raspadinha->name,
                'title' => $raspadinha->title,
                'description' => $raspadinha->description,
                'photo' => $raspadinha->photo,
                'value' => $raspadinha->value,
                'totalbuy' => $raspadinha->totalbuy,
                'max_sales' => $raspadinha->max_sales,
                'active' => $raspadinha->active,
                'hot' => $raspadinha->hot,
                'start_date' => $raspadinha->start_date,
                'end_date' => $raspadinha->end_date,
                'created_at' => $raspadinha->created_at,
                'prizes' => $raspadinha->prizes,
                'formatted_value' => $raspadinha->getFormattedValueAttribute(),
                'remaining' => $raspadinha->getRemainingAttribute(),
            ];
        });

        return Inertia::render('admin/raspadinhas/index', [
            'raspadinhas' => $raspadinhas,
            'filters' => $request->only(['search', 'active']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('admin/raspadinhas/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        \Log::info('=== STORE METHOD CALLED ===');
        \Log::info('Store method called', ['request_data' => $request->all()]);

        try {
            \Log::info('Starting validation...');

            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:raspadinhas,name',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'value' => 'required|numeric|min:0.01',
                'max_sales' => 'nullable|integer|min:1',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after:start_date',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4048',
                'active' => 'boolean',
                'hot' => 'boolean',
            ], [
                'name.required' => 'O nome da raspadinha é obrigatório.',
                'name.unique' => 'Já existe uma raspadinha com este nome.',
                'title.required' => 'O título é obrigatório.',
                'description.required' => 'A descrição é obrigatória.',
                'value.required' => 'O valor é obrigatório.',
                'value.min' => 'O valor deve ser maior que zero.',
                'photo.image' => 'O arquivo deve ser uma imagem.',
                'photo.mimes' => 'A imagem deve ser do tipo: JPEG, PNG, JPG, GIF ou WEBP.',
                'photo.max' => 'A imagem deve ter no máximo 2MB.',
            ]);

            \Log::info('Validation passed', ['validated_data' => $validatedData]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Unexpected error during validation', ['error' => $e->getMessage()]);
            throw $e;
        }

        $prizes = $request->input('prizes', []);
        \Log::info('Prizes data', ['prizes' => $prizes, 'count' => count($prizes)]);

        if (count($prizes) < 5) {
            \Log::warning('Not enough prizes', ['count' => count($prizes)]);
            return back()->withErrors(['prizes' => 'Uma raspadinha deve ter no mínimo 5 prêmios.']);
        }

        $totalProbability = array_sum(array_column($prizes, 'probability'));
        \Log::info('Total probability', ['total' => $totalProbability]);

        if ($totalProbability != 100) {
            \Log::warning('Invalid probability sum', ['total' => $totalProbability]);
            return back()->withErrors(['prizes' => 'A soma das probabilidades deve ser exatamente 100%.']);
        }

        try {
            DB::beginTransaction();

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('raspadinhas', 'public');
                $validatedData['photo'] = '/' . $photoPath;
            }

            $raspadinha = Raspadinha::create($validatedData);

            foreach ($prizes as $index => $prizeData) {
                $prizeImagePath = null;

                if ($request->hasFile("prizes.{$index}.img")) {
                    $prizeImagePath = '/' . $request->file("prizes.{$index}.img")->store('prizes', 'public');
                }

                RaspadinhaPrize::create([
                    'raspadinha_id' => $raspadinha->id,
                    'name' => $prizeData['name'],
                    'value' => $prizeData['value'],
                    'probability' => $prizeData['probability'],
                    'display_value' => $prizeData['display_value'],
                    'img' => $prizeImagePath,
                    'is_jackpot' => $prizeData['is_jackpot'],
                    'max_wins' => $prizeData['max_wins'],
                    'active' => $prizeData['active'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.raspadinhas.index')->with('success', 'Raspadinha criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }

            return back()->withErrors(['error' => 'Erro ao criar raspadinha: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Raspadinha $raspadinha): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $raspadinha->load([
                'prizes' => function ($query) {
                    $query->orderBy('probability', 'desc');
                }
            ]),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Raspadinha $raspadinha): Response
    {
        $raspadinha->load([
            'prizes' => function ($query) {
                $query->orderBy('probability', 'desc');
            }
        ]);

        return Inertia::render('admin/raspadinhas/edit', [
            'raspadinha' => [
                'id' => $raspadinha->id,
                'name' => $raspadinha->name,
                'title' => $raspadinha->title,
                'description' => $raspadinha->description,
                'photo' => $raspadinha->photo,
                'value' => $raspadinha->value,
                'totalbuy' => $raspadinha->totalbuy,
                'max_sales' => $raspadinha->max_sales,
                'active' => $raspadinha->active,
                'hot' => $raspadinha->hot,
                'start_date' => $raspadinha->start_date,
                'end_date' => $raspadinha->end_date,
                'prizes' => $raspadinha->prizes,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Raspadinha $raspadinha): RedirectResponse
    {
        \Log::info('=== UPDATE METHOD CALLED ===');
        \Log::info('Update method called', ['raspadinha_id' => $raspadinha->id, 'request_data' => $request->all()]);

        try {
            \Log::info('Starting validation for update...');

            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255', Rule::unique('raspadinhas')->ignore($raspadinha->id)],
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'value' => 'required|numeric|min:0.01',
                'max_sales' => 'nullable|integer|min:1',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after:start_date',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4048',
                'active' => 'boolean',
                'hot' => 'boolean',
            ], [
                'name.required' => 'O nome da raspadinha é obrigatório.',
                'name.unique' => 'Já existe uma raspadinha com este nome.',
                'title.required' => 'O título é obrigatório.',
                'description.required' => 'A descrição é obrigatória.',
                'value.required' => 'O valor é obrigatório.',
                'value.min' => 'O valor deve ser maior que zero.',
                'photo.image' => 'O arquivo deve ser uma imagem.',
                'photo.mimes' => 'A imagem deve ser do tipo: JPEG, PNG, JPG, GIF ou WEBP.',
                'photo.max' => 'A imagem deve ter no máximo 2MB.',
                'end_date.after' => 'A data de fim deve ser posterior à data de início.',
            ]);

            \Log::info('Validation passed for update', ['validated_data' => $validatedData]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed for update', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Unexpected error during update validation', ['error' => $e->getMessage()]);
            throw $e;
        }

        // Validar prêmios se foram enviados
        $prizes = $request->input('prizes', []);
        \Log::info('Prizes data for update', ['prizes' => $prizes, 'count' => count($prizes)]);

        if (!empty($prizes)) {
            if (count($prizes) < 5) {
                \Log::warning('Not enough prizes for update', ['count' => count($prizes)]);
                return back()->withErrors(['prizes' => 'Uma raspadinha deve ter no mínimo 5 prêmios.']);
            }

            $totalProbability = array_sum(array_column($prizes, 'probability'));
            \Log::info('Total probability for update', ['total' => $totalProbability]);

            if ($totalProbability != 100) {
                \Log::warning('Invalid probability sum for update', ['total' => $totalProbability]);
                return back()->withErrors(['prizes' => 'A soma das probabilidades deve ser exatamente 100%.']);
            }
        }

        try {
            DB::beginTransaction();

            \Log::info('Starting update process...');

            if ($request->hasFile('photo')) {
                \Log::info('Processing photo upload for update...');
                if ($raspadinha->photo) {
                    $photoToDelete = ltrim($raspadinha->photo, '/');
                    Storage::disk('public')->delete($photoToDelete);
                    \Log::info('Deleted old photo', ['old_photo' => $photoToDelete]);
                }

                $photoPath = $request->file('photo')->store('raspadinhas', 'public');
                $validatedData['photo'] = '/' . $photoPath;
                \Log::info('New photo uploaded', ['new_photo' => $validatedData['photo']]);
            }

            $raspadinha->update($validatedData);
            \Log::info('Raspadinha updated successfully', ['raspadinha_id' => $raspadinha->id]);

            if (!empty($prizes)) {
                \Log::info('Updating prizes...');

                $oldPrizes = $raspadinha->prizes;
                foreach ($oldPrizes as $oldPrize) {
                    if ($oldPrize->img) {
                        $imgToDelete = ltrim($oldPrize->img, '/');
                        Storage::disk('public')->delete($imgToDelete);
                    }
                }
                $raspadinha->prizes()->delete();

                foreach ($prizes as $index => $prizeData) {
                    $prizeImagePath = null;

                    if ($request->hasFile("prizes.{$index}.img")) {
                        $prizeImagePath = '/' . $request->file("prizes.{$index}.img")->store('prizes', 'public');
                    }

                    RaspadinhaPrize::create([
                        'raspadinha_id' => $raspadinha->id,
                        'name' => $prizeData['name'],
                        'value' => $prizeData['value'],
                        'probability' => $prizeData['probability'],
                        'display_value' => $prizeData['display_value'],
                        'img' => $prizeImagePath,
                        'is_jackpot' => $prizeData['is_jackpot'],
                        'max_wins' => $prizeData['max_wins'],
                        'active' => $prizeData['active'],
                    ]);
                }

                \Log::info('Prizes updated successfully');
            }

            DB::commit();

            return redirect()->route('admin.raspadinhas.index')->with('success', 'Raspadinha atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating raspadinha', ['error' => $e->getMessage(), 'raspadinha_id' => $raspadinha->id]);
            return back()->withErrors(['error' => 'Erro ao atualizar raspadinha: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Raspadinha $raspadinha): RedirectResponse
    {
        try {
            if ($raspadinha->photo) {
                $photoToDelete = ltrim($raspadinha->photo, '/');
                Storage::disk('public')->delete($photoToDelete);
            }

            $raspadinha->delete();

            return redirect()->route('admin.raspadinhas.index')->with('success', 'Raspadinha excluída com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erro ao excluir raspadinha: ' . $e->getMessage()]);
        }
    }

    /**
     * Listar prêmios de uma raspadinha
     */
    public function prizes(Raspadinha $raspadinha): JsonResponse
    {
        $prizes = $raspadinha->prizes()->orderBy('probability', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $prizes,
        ]);
    }

    /**
     * Adicionar/atualizar prêmio
     */
    public function storePrize(Request $request, Raspadinha $raspadinha): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
            'probability' => 'required|numeric|min:0|max:100',
            'display_value' => 'required|string|max:255',
            'is_jackpot' => 'required|boolean',
            'max_wins' => 'nullable|integer|min:0',
            'active' => 'required|boolean',
            'img' => 'nullable|image|max:2048'
        ], [
            'max_wins.min' => 'O limite de vitórias deve ser 0 (bloqueado) ou maior que 0 (limitado)',
        ]);

        if (!RaspadinhaPrize::validateTotalProbability($raspadinha->id, $validatedData['probability'])) {
            return response()->json([
                'success' => false,
                'message' => 'A soma das probabilidades não pode exceder 100%.',
            ], 422);
        }

        try {
            if ($request->hasFile('img')) {
                $validatedData['img'] = '/' . $request->file('img')->store('prizes', 'public');
            }

            $validatedData['raspadinha_id'] = $raspadinha->id;
            $prize = RaspadinhaPrize::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Prêmio adicionado com sucesso!',
                'data' => $prize,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar prêmio: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Atualizar prêmio
     */
    public function updatePrize(Request $request, Raspadinha $raspadinha, RaspadinhaPrize $prize): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
            'probability' => 'required|numeric|min:0|max:100',
            'display_value' => 'required|string|max:255',
            'is_jackpot' => 'required|boolean',
            'max_wins' => 'nullable|integer|min:0',
            'active' => 'required|boolean',
            'img' => 'nullable|image|max:2048'
        ], [
            'max_wins.min' => 'O limite de vitórias deve ser 0 (bloqueado) ou maior que 0 (limitado)',
        ]);

        if (!RaspadinhaPrize::validateTotalProbability($raspadinha->id, $validatedData['probability'], $prize->id)) {
            return response()->json([
                'success' => false,
                'message' => 'A soma das probabilidades não pode exceder 100%.',
            ], 422);
        }

        try {
            if ($request->hasFile('img')) {
                if ($prize->img) {
                    $imgToDelete = ltrim($prize->img, '/');
                    Storage::disk('public')->delete($imgToDelete);
                }
                $validatedData['img'] = '/' . $request->file('img')->store('prizes', 'public');
            }

            $prize->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Prêmio atualizado com sucesso!',
                'data' => $prize->fresh(),
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating prize: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar prêmio: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Excluir prêmio
     */
    public function destroyPrize(Raspadinha $raspadinha, RaspadinhaPrize $prize): JsonResponse
    {
        if ($prize->raspadinha_id !== $raspadinha->id) {
            return response()->json([
                'success' => false,
                'message' => 'Prêmio não pertence a esta raspadinha.',
            ], 404);
        }

        try {
            if ($prize->img) {
                $imgToDelete = ltrim($prize->img, '/');
                Storage::disk('public')->delete($imgToDelete);
            }

            $prize->delete();

            return response()->json([
                'success' => true,
                'message' => 'Prêmio excluído com sucesso!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir prêmio: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Testar raspadinha
     */
    public function test(Request $request, Raspadinha $raspadinha): JsonResponse
    {
        $times = $request->input('times', 100);

        if ($times < 10 || $times > 10000) {
            return response()->json([
                'success' => false,
                'message' => 'Número de testes deve estar entre 10 e 10.000.',
            ], 422);
        }

        try {
            $results = [];
            $totalValue = 0;
            $investmentTotal = $raspadinha->value * $times;

            for ($i = 1; $i <= $times; $i++) {
                $prize = $raspadinha->drawPrize();

                if ($prize) {
                    $results[$prize->name] = ($results[$prize->name] ?? 0) + 1;
                    $totalValue += $prize->value;
                } else {
                    $results['Erro'] = ($results['Erro'] ?? 0) + 1;
                }
            }

            $formattedResults = [];
            foreach ($raspadinha->prizes as $prize) {
                $count = $results[$prize->name] ?? 0;
                $percentage = ($count / $times) * 100;

                $formattedResults[] = [
                    'name' => $prize->name,
                    'display_value' => $prize->display_value,
                    'expected_percentage' => $prize->probability,
                    'times_won' => $count,
                    'real_percentage' => number_format($percentage, 2),
                    'total_won' => $prize->value * $count,
                ];
            }

            $profit = $investmentTotal - $totalValue;
            $profitPercentage = ($profit / $investmentTotal) * 100;
            $totalProbability = $raspadinha->prizes->sum('probability');

            return response()->json([
                'success' => true,
                'data' => [
                    'results' => $formattedResults,
                    'investment_total' => $investmentTotal,
                    'total_won' => $totalValue,
                    'profit' => $profit,
                    'profit_percentage' => $profitPercentage,
                    'total_probability' => $totalProbability,
                    'times_executed' => $times,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao executar teste: ' . $e->getMessage(),
            ], 500);
        }
    }
}
