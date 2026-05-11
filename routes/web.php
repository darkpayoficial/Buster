<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RaspadinhasController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\Admin\AdminConfigController;
use App\Http\Controllers\Admin\AdminBannerController;
use App\Http\Controllers\Admin\AdminGatewayController;

// Rotas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/raspadinhas', [RaspadinhasController::class, 'index'])->name('raspadinhas');
Route::get('/raspadinhas/{slug}', [RaspadinhasController::class, 'show'])->name('raspadinhas.show');
Route::get('/indique', [HomeController::class, 'referral'])->name('referral');
Route::get('/not-authorized', [HomeController::class, 'notAuthorized'])->name('not-authorized');

Route::get('/r/{code}', [HomeController::class, 'referral'])->name('referral.link');

// Rotas de autenticação (API mas usando sessão web)
Route::prefix('api/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::get('/check', [AuthController::class, 'check'])->name('api.auth.check');
});

// Rotas API que precisam de sessão
Route::prefix('api')->middleware('auth')->group(function () {
    // User
    Route::get('/auth/user', [AuthController::class, 'user'])->name('api.auth.user');
    Route::get('/user/balance', [AuthController::class, 'getBalance'])->name('api.user.balance');
    
    // Raspadinhas
    Route::post('/raspadinhas/{raspadinha}/buy', [RaspadinhasController::class, 'buy'])->name('api.raspadinhas.buy');
    
    // Saques
    Route::get('/withdraw/limits', [WithdrawalController::class, 'limits']);
    Route::get('/withdrawals', [WithdrawalController::class, 'index']);
    Route::post('/withdraw/request', [WithdrawalController::class, 'store']);
    Route::get('/withdrawals/{withdrawal}', [WithdrawalController::class, 'show']);
    
    // Depósitos
    Route::post('/deposit/create', [DepositController::class, 'create']);
    Route::get('/deposit', [DepositController::class, 'index']);
    Route::get('/deposit/{paymentId}/status', [DepositController::class, 'checkStatus']);
});

// Rotas do perfil
Route::middleware('auth')->group(function () {
    Route::get('/perfil/conta', [ProfileController::class, 'index'])->name('profile');
    Route::get('/perfil/historico', [ProfileController::class, 'historico'])->name('profile.historico');
    Route::get('/perfil/transacoes', [ProfileController::class, 'transacoes'])->name('profile.transacoes');
    Route::get('/perfil/entregas', [ProfileController::class, 'entregas'])->name('profile.entregas');
    Route::get('/perfil/seguranca', [ProfileController::class, 'seguranca'])->name('profile.seguranca');
    
    // APIs do perfil
    Route::post('/perfil/update-email', [ProfileController::class, 'updateEmail']);
    Route::post('/perfil/update-username', [ProfileController::class, 'updateUsername']);
    Route::post('/perfil/update-phone', [ProfileController::class, 'updatePhone']);
    Route::post('/perfil/update-document', [ProfileController::class, 'updateDocument']);
    Route::post('/perfil/update-password', [ProfileController::class, 'updatePassword']);
});

// Rotas do Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\AdminController::class, 'login'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AdminController::class, 'authenticate'])->name('authenticate');
    
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [App\Http\Controllers\Admin\AdminController::class, 'logout'])->name('logout');
        
        // Rotas de gerenciamento de usuários
        Route::get('/users', [App\Http\Controllers\Admin\AdminUsersController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\AdminUsersController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\Admin\AdminUsersController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\Admin\AdminUsersController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/update-field', [App\Http\Controllers\Admin\AdminUsersController::class, 'updateField'])->name('users.update-field');
        Route::post('/users/{user}/toggle-field', [App\Http\Controllers\Admin\AdminUsersController::class, 'toggleField'])->name('users.toggle-field');
        Route::post('/users/{user}/generate-referral', [App\Http\Controllers\Admin\AdminUsersController::class, 'generateReferralCode'])->name('users.generate-referral');
        
        // Rotas de gerenciamento de raspadinhas
        Route::resource('raspadinhas', App\Http\Controllers\Admin\AdminRaspadinhaController::class);
        Route::post('/raspadinhas/{raspadinha}/test', [App\Http\Controllers\Admin\AdminRaspadinhaController::class, 'test'])->name('raspadinhas.test');
        
        // Rotas de gerenciamento de prêmios
        Route::get('/raspadinhas/{raspadinha}/prizes', [App\Http\Controllers\Admin\AdminRaspadinhaController::class, 'prizes'])->name('raspadinhas.prizes.index');
        Route::post('/raspadinhas/{raspadinha}/prizes', [App\Http\Controllers\Admin\AdminRaspadinhaController::class, 'storePrize'])->name('raspadinhas.prizes.store');
        Route::put('/raspadinhas/{raspadinha}/prizes/{prize}', [App\Http\Controllers\Admin\AdminRaspadinhaController::class, 'updatePrize'])->name('raspadinhas.prizes.update');
        Route::delete('/raspadinhas/{raspadinha}/prizes/{prize}', [App\Http\Controllers\Admin\AdminRaspadinhaController::class, 'destroyPrize'])->name('raspadinhas.prizes.destroy');
        Route::post('/raspadinhas/{raspadinha}', [App\Http\Controllers\Admin\AdminRaspadinhaController::class, 'update'])->name('raspadinhas.update');
        // Rotas de gerenciamento de saques
        Route::get('/withdrawals', [App\Http\Controllers\Admin\AdminWithdrawalsController::class, 'index'])->name('withdrawals.index');
        Route::get('/withdrawals/{withdrawal}/edit', [App\Http\Controllers\Admin\AdminWithdrawalsController::class, 'edit'])->name('withdrawals.edit');
        Route::post('/withdrawals/{withdrawal}/approve', [App\Http\Controllers\Admin\AdminWithdrawalsController::class, 'approve'])->name('withdrawals.approve');
        Route::post('/withdrawals/{withdrawal}/reject', [App\Http\Controllers\Admin\AdminWithdrawalsController::class, 'reject'])->name('withdrawals.reject');
        
        // Depósitos
        Route::get('/deposits', [App\Http\Controllers\Admin\AdminDepositController::class, 'index'])->name('deposits.index');
        Route::get('/deposits/{deposit}/edit', [App\Http\Controllers\Admin\AdminDepositController::class, 'edit'])->name('deposits.edit');
        Route::post('/deposits/{deposit}/cancel', [App\Http\Controllers\Admin\AdminDepositController::class, 'cancel'])->name('deposits.cancel');
        Route::post('/deposits/{deposit}/approve', [App\Http\Controllers\Admin\AdminDepositController::class, 'approve'])->name('deposits.approve');

        // Configurações
        Route::get('/settings', [AdminConfigController::class, 'edit'])->name('settings.edit');
        Route::post('/settings', [AdminConfigController::class, 'update'])->name('settings.update');

        // Banners
        Route::get('/banners', [AdminBannerController::class, 'index'])->name('banners.index');
        Route::get('/banners/create', [AdminBannerController::class, 'create'])->name('banners.create');
        Route::post('/banners', [AdminBannerController::class, 'store'])->name('banners.store');
        Route::get('/banners/{banner}/edit', [AdminBannerController::class, 'edit'])->name('banners.edit');
        Route::put('/banners/{banner}', [AdminBannerController::class, 'update'])->name('banners.update');
        Route::delete('/banners/{banner}', [AdminBannerController::class, 'destroy'])->name('banners.destroy');
        Route::post('/banners/{banner}/toggle-status', [AdminBannerController::class, 'toggleStatus'])->name('banners.toggle-status');
        Route::post('/banners/reorder', [AdminBannerController::class, 'reorder'])->name('banners.reorder');

        // Gateways
        Route::get('/gateways', [AdminGatewayController::class, 'index'])->name('gateways.index');
        Route::put('/gateways', [AdminGatewayController::class, 'update'])->name('gateways.update');

    });
});

// Rotas de indicação
Route::middleware('auth')->group(function () {
    Route::get('/indique', [ReferralController::class, 'index'])->name('referral.index');
    Route::post('/api/referral/generate', [ReferralController::class, 'generateCode'])->name('referral.generate');
    Route::post('/api/referral/withdraw', [ReferralController::class, 'withdraw'])->name('referral.withdraw');
});

// Rota fallback para 404
Route::fallback([HomeController::class, 'notFound']);
Route::get('/premios',[HomeController::class, 'notFound']);