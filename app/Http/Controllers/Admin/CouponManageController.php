<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponManageController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.form', ['coupon' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Coupon::create([
            ...$request->only([
                'code', 'description', 'discount_type', 'discount_value',
                'max_discount', 'min_purchase', 'usage_limit', 'start_date', 'end_date',
            ]),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'สร้างคูปองสำเร็จ');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.form', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $coupon->update([
            ...$request->only([
                'code', 'description', 'discount_type', 'discount_value',
                'max_discount', 'min_purchase', 'usage_limit', 'start_date', 'end_date',
            ]),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'แก้ไขคูปองสำเร็จ');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'ลบคูปองสำเร็จ');
    }
}
