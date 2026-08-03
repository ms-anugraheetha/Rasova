<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AddressController extends Controller
{
    public function update(Request $request, Address $address)
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'address_type' => 'required|in:home,office,other',
            'full_name' => 'required|string|max:255',
            'phone' => ['required', 'digits:10'],
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'state' => ['required', Rule::in(\App\Http\Controllers\CheckoutController::INDIAN_STATES)],
            'postal_code' => ['required', 'digits:6'],
        ]);

        $address->update([
            'address_type' => $validated['address_type'],
            'full_name' => trim($validated['full_name']),
            'phone' => $validated['phone'],
            'address_line_1' => trim($validated['address_line_1']),
            'address_line_2' => isset($validated['address_line_2']) ? trim($validated['address_line_2']) : null,
            'city' => trim($validated['city']),
            'district' => trim($validated['district']),
            'state' => $validated['state'],
            'postal_code' => $validated['postal_code'],
        ]);

        return back()->with('success', 'Address updated.');
    }

    public function destroy(Request $request, Address $address)
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        $address->delete();

        return back()->with('success', 'Address deleted.');
    }

    public function setDefault(Request $request, Address $address)
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        Address::where('user_id', $request->user()->id)
            ->where('id', '!=', $address->id)
            ->update(['is_default' => false]);

        $address->update(['is_default' => true]);

        return back()->with('success', 'Default address updated.');
    }
}