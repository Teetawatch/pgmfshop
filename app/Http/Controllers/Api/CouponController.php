<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();

        return response()->json($coupons);
    }

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json(['message' => 'ไม่พบคูปองนี้'], 404);
        }

        if (!$coupon->isValid()) {
            return response()->json(['message' => 'คูปองนี้หมดอายุหรือใช้งานไม่ได้แล้ว'], 422);
        }

        $discount = $coupon->calculateDiscount($request->subtotal);

        if ($discount === 0.0) {
            return response()->json([
                'message' => "ยอดสั่งซื้อขั้นต่ำ ฿" . number_format($coupon->min_purchase, 0),
            ], 422);
        }

        return response()->json([
            'coupon' => $coupon,
            'discount' => $discount,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_purchase' => 'numeric|min:0',
            'usage_limit' => 'integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $coupon = Coupon::create($request->all());

        return response()->json($coupon, 201);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'sometimes|string|unique:coupons,code,' . $coupon->id,
            'description' => 'nullable|string',
            'discount_type' => 'sometimes|in:percentage,fixed',
            'discount_value' => 'sometimes|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_purchase' => 'numeric|min:0',
            'usage_limit' => 'integer|min:0',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $coupon->update($request->all());

        return response()->json($coupon);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return response()->json(['message' => 'ลบคูปองสำเร็จ']);
    }
}
