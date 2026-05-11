# Skill Backend - Buster/ALBUM-COPA

## Stack Backend
- **Laravel 12** (PHP 8.5)
- **Inertia.js** para SPA
- **MariaDB/MySQL** banco de dados

## Estrutura de Pastas
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/           # Controllers do painel admin
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   ├── ProfileController.php
│   │   ├── RaspadinhasController.php
│   │   ├── DepositController.php
│   │   ├── WithdrawalController.php
│   │   └── ReferralController.php
│   ├── Middleware/
│   │   ├── AdminMiddleware.php
│   │   ├── HandleInertiaRequests.php
│   │   └── InertiaCSRFProtection.php
│   └── Requests/
├── Models/
│   ├── User.php
│   ├── Raspadinha.php
│   ├── RaspadinhaPrize.php
│   ├── Deposit.php
│   ├── Withdrawal.php
│   ├── Banner.php
│   ├── Config.php
│   └── GatewaysKeys.php
├── Traits/
├── Providers/
└── Console/
    └── Commands/

routes/
├── web.php        # Rotas principais
└── api.php        # Rotas API

database/
├── migrations/
└── seeders/
```

## Padrões de Código

### Controller Padrão
```php
<?php

namespace App\Http\Controllers;

use App\Models\ModelName;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExampleController extends Controller
{
    public function index()
    {
        $data = ModelName::all();
        
        return Inertia::render('PageName', [
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'field' => 'required|string|max:255',
        ]);

        ModelName::create($validated);

        return redirect()->back()->with('success', 'Criado com sucesso!');
    }
}
```

### Model Padrão
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Example extends Model
{
    protected $fillable = [
        'field1',
        'field2',
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
    ];

    // Relacionamentos
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
```

## Rotas

### Rotas Web (routes/web.php)
```php
// Públicas
Route::get('/', [HomeController::class, 'index'])->name('home');

// Autenticadas
Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'index'])->name('profile');
});

// Admin
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});
```

## Regras OBRIGATÓRIAS

1. ✅ SEMPRE usar Inertia::render() para páginas
2. ✅ VALIDAR dados nos controllers ou Form Requests
3. ✅ USAR transações para operações críticas
4. ✅ RETORNAR JSON para rotas API
5. ✅ USAR Eloquent ORM, nunca SQL raw
6. ❌ NUNCA expor dados sensíveis
7. ❌ NUNCA fazer query em loops

## Middlewares Importantes
- `auth` - Verifica autenticação
- `admin` - Verifica se é admin
- `HandleInertiaRequests` - Compartilha dados globais