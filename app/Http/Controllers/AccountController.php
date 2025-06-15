<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::with('role')->get();
        return view('admin.accounts.index', compact('accounts'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.accounts.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'full_name' => 'required|string|max:255',
            'avatar' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'email' => 'required|email|unique:accounts,email',
            'phone' => 'nullable|string|max:10',
            'gender' => 'required|in:0,1',
            'address' => 'nullable|string',
            'password' => 'required|string|min:6'
        ]);

        $data['password'] = Hash::make($data['password']);
        Account::create($data);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $account = Account::findOrFail($id);
        $roles = Role::all();
        return view('admin.accounts.edit', compact('account', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $data = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'full_name' => 'required|string|max:255',
            'avatar' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'email' => 'required|email|unique:accounts,email,' . $id,
            'phone' => 'nullable|string|max:10',
            'gender' => 'required|in:0,1',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:6'
        ]);

        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $account->update($data);
        return redirect()->route('accounts.index')->with('success', 'Account updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'Account deleted successfully');
    }
}
