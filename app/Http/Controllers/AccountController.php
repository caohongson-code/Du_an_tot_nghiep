<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Product;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Account::with('role')->whereIn('role_id', [1, 2]);

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $accounts->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%{$keyword}%");
            });
        }

        $listQT = $accounts->orderByDesc('id')->paginate(3)->withQueryString();

        $admin = Auth::user();
return view('admin.accounts.index', compact('listQT', 'admin'));

    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.accounts.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $message = [
            'role_id.required'   => 'Vui lòng chọn quyền.',
            'role_id.exists'     => 'Quyền không tồn tại.',
            'full_name.required' => 'Vui lòng nhập họ tên.',
            'email.required'     => 'Vui lòng nhập email.',
            'email.email'        => 'Email không đúng định dạng.',
            'email.unique'       => 'Email đã tồn tại.',
            'gender.required'    => 'Vui lòng chọn giới tính.',
            'gender.in'          => 'Giới tính không hợp lệ.',
        ];

        $data = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'full_name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_of_birth' => 'nullable|date',
            'email' => 'required|email|unique:accounts,email',
            'phone' => 'nullable|string|max:10',
            'gender' => 'required|in:0,1',
            'address' => 'nullable|string',
        ], $message);

        try {
            DB::beginTransaction();

            if ($request->hasFile('avatar')) {
                $data['avatar'] = $request->file('avatar')->store('uploads/quantri', 'public');
            }

            $data['password'] = bcrypt('1234'); // mật khẩu mặc định

            Account::create($data);
            DB::commit();

            return redirect()->route('accounts.index')->with('success', 'Tạo tài khoản thành công!');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('accounts.create')->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $account = Account::findOrFail($id);
        $roles = Role::all();

        return view('admin.accounts.edit', compact('account', 'roles'));
    }

    public function update(Request $request, $id)
    {
        try {
            $account = Account::findOrFail($id);

            DB::beginTransaction();

            $data = $request->validate([
                'role_id' => 'required|exists:roles,id',
                'full_name' => 'required|string|max:255',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'date_of_birth' => 'nullable|date',
                'email' => 'required|email',
                'phone' => 'nullable|string|max:10',
                'gender' => 'required|in:0,1',
                'address' => 'nullable|string',
            ]);

            if ($request->hasFile('avatar')) {
                $data['avatar'] = $request->file('avatar')->store('uploads/quantri', 'public');
                if ($account->avatar) {
                    Storage::disk('public')->delete($account->avatar);
                }
            }

            $account->update($data);

            DB::commit();

            return redirect()->route('accounts.index')->with('success', 'Cập nhật thành công!');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('accounts.index')->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $account = Account::findOrFail($id);

        if ($account) {
            $filePath = $account->avatar;
            $account->delete();

            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->route('accounts.index')->with('success', 'Xoá thành công!');
        }

        return redirect()->route('accounts.index')->with('error', 'Tài khoản không tồn tại!');
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (in_array($user->role_id, [1, 2])) {
                return redirect()->route('accounts.index')->with('success', 'Đăng nhập quản trị thành công!');
            }

            return redirect()->route('home')->with('success', 'Đăng nhập người dùng thành công!');
        }

        return back()->with('error', 'Email hoặc mật khẩu không đúng');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('taikhoan.showLoginForm')->with('success', 'Đăng xuất thành công!');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:accounts,email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ], [
            'full_name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải từ 6 ký tự.',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp.',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only(['full_name', 'email', 'password']);
            $data['password'] = bcrypt($data['password']);
            $data['role_id'] = 3; // Người dùng mặc định

            Account::create($data);

            DB::commit();

            return redirect()->route('taikhoan.showLoginForm')->with('success', 'Đăng ký thành công!');
        } catch (Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}
