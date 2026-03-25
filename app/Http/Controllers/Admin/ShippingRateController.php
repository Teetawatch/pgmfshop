<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    public function index()
    {
        $rates = ShippingRate::orderBy('sort_order')->get();

        return view('admin.shipping.index', compact('rates'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'rates' => 'required|array|min:1',
            'rates.*.id' => 'nullable|integer|exists:shipping_rates,id',
            'rates.*.name' => 'required|string|max:255',
            'rates.*.min_quantity' => 'required|integer|min:1',
            'rates.*.max_quantity' => 'nullable|integer|min:1',
            'rates.*.price' => 'required|numeric|min:0',
            'rates.*.is_active' => 'nullable',
        ]);

        $submittedIds = [];

        foreach ($request->rates as $index => $rateData) {
            if (!empty($rateData['id'])) {
                $rate = ShippingRate::find($rateData['id']);
                if ($rate) {
                    $rate->update([
                        'name' => $rateData['name'],
                        'min_quantity' => $rateData['min_quantity'],
                        'max_quantity' => $rateData['max_quantity'] ?: null,
                        'price' => $rateData['price'],
                        'is_active' => isset($rateData['is_active']),
                        'sort_order' => $index,
                    ]);
                    $submittedIds[] = $rate->id;
                }
            } else {
                $rate = ShippingRate::create([
                    'name' => $rateData['name'],
                    'min_quantity' => $rateData['min_quantity'],
                    'max_quantity' => $rateData['max_quantity'] ?: null,
                    'price' => $rateData['price'],
                    'is_active' => isset($rateData['is_active']),
                    'sort_order' => $index,
                ]);
                $submittedIds[] = $rate->id;
            }
        }

        // Delete rates that were removed from the form
        ShippingRate::whereNotIn('id', $submittedIds)->delete();

        return redirect()->route('admin.shipping.index')->with('success', 'บันทึกค่าจัดส่งเรียบร้อยแล้ว');
    }
}
