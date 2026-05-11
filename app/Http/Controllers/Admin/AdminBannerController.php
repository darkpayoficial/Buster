<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class AdminBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $banners = Banner::ordered()
            ->get()
            ->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'button_link' => $banner->button_link,
                    'image_url' => $banner->image_url,
                    'order' => $banner->order,
                    'active' => $banner->active,
                    'created_at' => $banner->created_at->format('d/m/Y H:i:s'),
                ];
            });

        return Inertia::render('admin/banners/index', [
            'banners' => $banners
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('admin/banners/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'button_link' => 'required|string|max:255',
            'image' => 'required|image|max:2048',
            'order' => 'required|integer|min:0',
            'active' => 'required|boolean'
        ]);

        $validated['image_url'] = '/' . $request->file('image')->store('banners', 'public');

        Banner::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner criado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner): Response
    {
        return Inertia::render('admin/banners/edit', [
            'banner' => [
                'id' => $banner->id,
                'title' => $banner->title,
                'button_link' => $banner->button_link,
                'image_url' => $banner->image_url,
                'order' => $banner->order,
                'active' => $banner->active,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'button_link' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'order' => 'required|integer|min:0',
            'active' => 'required|boolean'
        ]);

        /* `//` is used in PHP to add a single-line comment. Any text following `//` on the same line
        is considered a comment and is not executed by the PHP interpreter. Comments are used to
        document code, provide explanations, or temporarily disable code without removing it. */
        if ($request->hasFile('image')) {
            if ($banner->image_url) {
                Storage::delete('public' . $banner->image_url);
            }
            $validated['image_url'] = '/' . $request->file('image')->store('banners', 'public');
        }

        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner): RedirectResponse
    {
        if ($banner->image_url) {
            Storage::delete('public' . $banner->image_url);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner excluído com sucesso!');
    }

    /**
     * Toggle banner status
     */
    public function toggleStatus(Banner $banner): RedirectResponse
    {
        $banner->update([
            'active' => !$banner->active
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Status do banner atualizado com sucesso!');
    }

    /**
     * Reorder banners
     */
    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:banners,id',
            'orders.*.order' => 'required|integer|min:0'
        ]);

        foreach ($request->orders as $item) {
            Banner::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return redirect()->route('admin.banners.index')->with('success', 'Ordem dos banners atualizada com sucesso!');
    }
} 