<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AdminConfigController extends Controller
{
    /**
     * Display the settings form
     */
    public function edit(): Response
    {
        $config = Config::getSystemConfig();

        return Inertia::render('admin/settings/edit', [
            'config' => [
                'app_name' => $config->app_name,
                'logo' => $config->logo,
                'favicon' => $config->favicon,
                'footer_text' => $config->footer_text,
                'contact_email' => $config->contact_email,
                'contact_phone' => $config->contact_phone,
                'address' => $config->address,
                'description' => $config->description,
                'keywords' => $config->keywords,
                'min_deposit_amount' => $config->min_deposit_amount,
                'max_deposit_amount' => $config->max_deposit_amount,
                'min_withdraw_amount' => $config->min_withdraw_amount,
                'max_withdraw_amount' => $config->max_withdraw_amount,
                'auto_withdraw_enabled' => $config->auto_withdraw_enabled,
                'auto_withdraw_max_amount' => $config->auto_withdraw_max_amount,
                'primary_color' => $config->primary_color,
                'secondary_color' => $config->secondary_color,
                'accent_color' => $config->accent_color,
                'background_color' => $config->background_color,
                'foreground_color' => $config->foreground_color,
                'muted_color' => $config->muted_color,
                'muted_foreground_color' => $config->muted_foreground_color,
                'card_color' => $config->card_color,
                'card_foreground_color' => $config->card_foreground_color,
                'border_color' => $config->border_color,
                'input_color' => $config->input_color,
                'ring_color' => $config->ring_color,
            ]
        ]);
    }

    /**
     * Update the settings
     */
    public function update(Request $request)
    {
        $config = Config::getSystemConfig();

        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'footer_text' => 'nullable|string',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'min_deposit_amount' => 'required|numeric|min:0',
            'max_deposit_amount' => 'required|numeric|min:0',
            'min_withdraw_amount' => 'required|numeric|min:0',
            'max_withdraw_amount' => 'required|numeric|min:0',
            'auto_withdraw_enabled' => 'required|boolean',
            'auto_withdraw_max_amount' => 'required|numeric|min:0',
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'accent_color' => 'required|string',
            'background_color' => 'required|string',
            'foreground_color' => 'required|string',
            'muted_color' => 'required|string',
            'muted_foreground_color' => 'required|string',
            'card_color' => 'required|string',
            'card_foreground_color' => 'required|string',
            'border_color' => 'required|string',
            'input_color' => 'required|string',
            'ring_color' => 'required|string',
        ], [
            'primary_color.regex' => 'A cor primária deve ser um código hexadecimal (ex: #FF0000), RGB/RGBA (ex: rgb(255,0,0)) ou OKLCH (ex: oklch(26.9% 0 0))',
            'secondary_color.regex' => 'A cor secundária deve ser um código hexadecimal (ex: #00FF00), RGB/RGBA (ex: rgb(0,255,0)) ou OKLCH (ex: oklch(26.9% 0 0))',
            'accent_color.regex' => 'A cor de destaque deve ser um código hexadecimal (ex: #0000FF), RGB/RGBA (ex: rgb(0,0,255)) ou OKLCH (ex: oklch(26.9% 0 0))',
        ]);

        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'image|max:2048']);
            
            if ($config->logo) {
                Storage::delete('public' . $config->logo);
            }
            $validated['logo'] = '/' . $request->file('logo')->store('images', 'public');
        }

        if ($request->hasFile('favicon')) {
            $request->validate(['favicon' => 'image|max:2048']);
            
            if ($config->favicon) {
                Storage::delete('public' . $config->favicon);
            }
            $validated['favicon'] = '/' . $request->file('favicon')->store('images', 'public');
        }

        $config->update($validated);

        return redirect()->back()->with('success', 'Configurações atualizadas com sucesso!');
    }
}