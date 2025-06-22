<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function show()
    {
        return view('client.user.profile');
    }

    public function edit()
    {
        return view('client.user.edit_profile');
    }

    public function update(Request $request)
{
    $request->validate([
        'full_name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'gender' => 'nullable|in:male,female',
        'date_of_birth' => 'nullable|date',
        'address' => 'nullable|string|max:255',
    ]);

    $user = Auth::user();

    $user->full_name = $request->full_name;
    $user->phone = $request->phone;
    $user->gender = $request->gender;
    $user->date_of_birth = $request->date_of_birth;
    $user->address = $request->address;

    $user->save();

    return redirect()->route('user.profile')->with('success', 'Cập nhật thông tin thành công!');
}

}
