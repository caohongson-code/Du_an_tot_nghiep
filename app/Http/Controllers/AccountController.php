<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $accounts = Account::with('role')->whereIn('role_id', [2, 1]);
        if ($request->filled('keyword')){
            $keyword = $request->input('keyword');
            $accounts->where(function ($q) use ($keyword){
                $q->where('full_name' ,'like' ,"%{$keyword}%");
        });
    }
    $listQT = $accounts->orderByDesc('id')->paginate(3)->withQueryString();;
        return view('admin.accounts.index', compact('listQT'));

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
    public  function store(Request $request)
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
        $data= $request->validate([
            'role_id' => 'required|exists:roles,id',
            'full_name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_of_birth' => 'nullable|date',
            'email' => 'required|email|unique:accounts,email',
            'phone' => 'nullable|string|max:10',
            'gender' => 'required|in:0,1',
            'address' => 'nullable|string',

        ],$message
    );
        try{
            DB::beginTransaction();
    $filePath = null;
    if ($request->hasFile('avatar')) {
        $filePath = $request->file('avatar')->store('uploads/quantri', 'public');
        $data['avatar' ] = $filePath;
    }
        $data['password'] = bcrypt('1234');

        Account::insert($data);
        DB::commit();
        return redirect()->route('accounts.index')->with('success', 'Account created successfully');
    }catch(Exception $e){
        DB::rollback();
        return redirect()->route('accounts.create')->with('error', 'Có lỗi xảy ra khi thêm : ' . $e->getMessage());
}
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
        try{
        $account = Account::findOrFail($id);

        DB::beginTransaction();


        $data= $request->validate([
            'role_id' => 'required|exists:roles,id',
            'full_name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_of_birth' => 'nullable|date',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:10',
            'gender' => 'required|in:0,1',
            'address' => 'nullable|string',

        ]
    );
    if ($request->hasFile('avatar')) {
        $filePath = $request->file('avatar')->store('uploads/quantri', 'public');
        $data['avatar'] = $filePath;

        if ($account->avatar) {
            Storage::disk('public')->delete($account->avatar);
        }
    }


        $account->update($data);
        DB::commit();
        return redirect()->route('accounts.index')->with('success', 'Account created successfully');
    }catch(Exception $e){
        DB::rollback();
        return redirect()->route('accounts.index')->with('error', 'Có lỗi xảy ra khi cập nhật : ' . $e->getMessage());
}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        if(!$account){
            return redirect('accounts.index')->with('error' , 'khach hàng ko tồn tại');
        }
        $filePath = $account->avatar;
        $account->delete();
        if($account){
            if($account && isset($filePath) && Storage::disk('public')->exists($account->avatar)){
                Storage::disk('public')->delete($filePath);
            }
            return redirect()->route('accounts.index')->with('success',' Xoá thành công !');
            }
                return redirect()->route('accounts.index')->with('error',' Có lỗi xin vui lòng thu lại  !');

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
            'password.required'     => 'Vui lòng nhập password.',
            'email.required'     => 'Vui lòng nhập email.',
            'email.email'        => 'Email không đúng định dạng.',
        ]);
        $admin =Account::where('email', $request->email)->first();
        if($admin && Hash::check($request->password,$admin->password)){

        if (in_array($admin->role_id, [1, 2])) {
            session(['admin_id' => $admin->id]);
            return redirect()->route('accounts.index')->with('success', 'Đăng nhập thành công!');
        } else {

            return redirect()->route('taikhoan.showLoginForm')->with('success', 'Đăng nhập người dùng thành công!');
        }
        }
        return redirect()->back()->with('error', 'Email hoặc mật khẩu không đúng');}
        public function logout(Request $request)
        {
            $request->session()->forget('admin_id');
            return redirect()->route('taikhoan.showLoginForm')->with('success', 'Đăng xuất thành công!');
        }
}
