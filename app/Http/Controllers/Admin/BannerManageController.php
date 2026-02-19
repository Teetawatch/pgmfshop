<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerManageController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.form', ['banner' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'image' => '/storage/' . $path,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'เพิ่มแบนเนอร์สำเร็จ');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image && Str::startsWith($banner->image, '/storage/')) {
                Storage::delete(str_replace('/storage/', 'public/', $banner->image));
            }
            $path = $request->file('image')->store('banners', 'public');
            $banner->image = '/storage/' . $path;
        }

        $banner->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'image' => $banner->image,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'แก้ไขแบนเนอร์สำเร็จ');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image && Str::startsWith($banner->image, '/storage/')) {
            Storage::delete(str_replace('/storage/', 'public/', $banner->image));
        }
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'ลบแบนเนอร์สำเร็จ');
    }
}
