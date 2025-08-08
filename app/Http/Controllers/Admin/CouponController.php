<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Order;
use Session;

class CouponController extends Controller
{
    /**
     * Display a listing of the coupons.
     */
    public function index()
    {
        $user = auth()->user();
        $coupons = Coupon::where('organizer_id', $user->id)
                    ->withCount(['orders as orders_count' => function ($query) {
                        $query->where('paymentstatus', 'Y');
                    }])
                    ->get();

        return view("pages.coupon.index", compact('coupons'));
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create()
    {
        return view('coupons.create');
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code',
            'cost' => 'required|numeric|max:999',
            'expire_at' => 'required|date',
            'coupontype' => 'required|in:percentage,flat',
            'couponlimitation' => 'nullable|integer|min:1',
        ]);

        $validatedData['organizer_id'] = auth()->id();
        $validatedData['couponlimitation'] = $request->couponlimitation ?? null;

        Coupon::create($validatedData);

        return redirect()->route('coupons.index')->with('succes', 'Coupon created successfully.');
    }

    /**
     * Display the specified coupon.
     */
    public function show($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code,' . $coupon->id,
            'cost' => 'required|numeric|max:999',
            'expire_at' => 'required|date',
            'coupontype' => 'required|in:percentage,flat',
            'couponlimitation' => 'nullable|integer|min:1',
        ]);

        $validatedData['organizer_id'] = auth()->id();

        $coupon->update($validatedData);

        return redirect()->route('coupons.index')->with('succes', 'Coupon updated successfully.');
    }

    /**
     * Remove the specified coupon from storage.
     */
     public function destroy($id)
    {
        $coupon_exist_in_order = Order::where('coupon_id', $id)
                                    ->where('paymentstatus', '!=', 'N')
                                    ->exists();

        if($coupon_exist_in_order)
        {
            Session::flash('error_message', 'Coupon Can not be Deleted, coupon exist in order!!!');
            return redirect()->back();
        }


        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('coupons.index')->with('succes', 'Coupon deleted successfully.');
    }
}
