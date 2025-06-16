<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Role;
use Illuminate\Http\Request;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomersControllerr extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customer = Account::with('role')->whereIn('role_id', [3]);
        if ($request->filled('keyword')){
            $keyword = $request->input('keyword');
            $customer->where(function ($q) use ($keyword){
                $q->where('full_name' ,'like' ,"%{$keyword}%");
        });
    }
    $listKH = $customer->orderByDesc('id')->paginate(3)->withQueryString();;
        return view('admin.customers.index', compact('listKH'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $roles = Role::all();
        return view('admin.customers.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $message = [
            'password_confirmation.required' => 'Vui lòng nhập lại mật khẩu.',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp.',
            'full_name.required' => 'Vui lòng nhập họ tên.',
            'email.required'     => 'Vui lòng nhập email.',
            'email.email'        => 'Email không đúng định dạng.',
            'email.unique'       => 'Email đã tồn tại.',
            'gender.required'    => 'Vui lòng chọn giới tính.',
            'gender.in'          => 'Giới tính không hợp lệ.',
            'password.required'  => 'Vui lòng nhập password.',
            'password.min'       => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'address.required'  => 'Vui lòng nhập address.',

        ];
        $data= $request->validate([

            'full_name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_of_birth' => 'nullable|date',
            'email' => 'required|email|unique:accounts,email',
            'phone' => 'nullable|string|max:10',
            'gender' => 'required|in:0,1',
            'address' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ],$message
    );
        try{
            DB::beginTransaction();
    $filePath = null;
    if ($request->hasFile('avatar')) {
        $filePath = $request->file('avatar')->store('uploads/khachhang', 'public');
        $data['avatar' ] = $filePath;
    }
    $data['password'] = bcrypt($data['password']);
    $data['role_id'] = 3;
    unset($data['password_confirmation']);
        Account::insert($data);
        DB::commit();
        return redirect()->route('customers.index')->with('success', 'Customer created successfully');
    }catch(Exception $e){
        DB::rollback();
        return redirect()->route('customers.create')->withInput()->with('error', 'Có lỗi xảy ra khi thêm : ' . $e->getMessage());
}
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customers = Account::findOrFail($id);
        if(!$customers){
            return redirect('customers.index')->with('error' , 'khach hàng ko tồn tại');
        }
        $roles = Role::where('id', 3)->get();
            return view('admin.customers.edit', compact('customers', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customers = Account::findOrFail($id);
        if (!$customers) {
            return redirect('customers.index')->with('error', 'Khách hàng không tồn tại');
        }

        $message = [
            'old_password.required' => 'Vui lòng nhập mật khẩu cũ.',
            'password_confirmation.required' => 'Vui lòng nhập lại mật khẩu.',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp.',
            'full_name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'gender.required' => 'Vui lòng chọn giới tính.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'password.required' => 'Vui lòng nhập password.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'address.required' => 'Vui lòng nhập địa chỉ.',
            'password_confirmation.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ];

        $rules = [
            'full_name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_of_birth' => 'nullable|date',
            'email' => 'required|email|unique:accounts,email,' . $customers->id,
            'phone' => 'nullable|string|max:10',
            'gender' => 'required|in:0,1',
            'address' => 'required|string',
        ];

        if (
            $request->filled('password') ||
            $request->filled('old_password') ||
            $request->filled('password_confirmation')
        ) {
            $rules['old_password'] = 'required';
            $rules['password'] = 'required|string|min:6|confirmed';
            $rules['password_confirmation'] = 'required|string|min:6';
        }

        $data = $request->validate($rules, $message);

        try {
            DB::beginTransaction();

            if ($request->hasFile('avatar')) {
                $filePath = $request->file('avatar')->store('uploads/quantri', 'public');
                $data['avatar'] = $filePath;

                if ($customers->avatar) {
                    Storage::disk('public')->delete($customers->avatar);
                }
            }

            if ($request->filled('password')) {
                if (!Hash::check($request->input('old_password'), $customers->password)) {
                    return back()->withErrors(['old_password' => 'Mật khẩu cũ không đúng'])->withInput();
                }
                $data['password'] = bcrypt($request->input('password'));
            } else {
                unset($data['password']);
            }

            unset($data['password_confirmation'], $data['old_password']);

            $customers->update($data);

            DB::commit();
            return redirect()->route('customers.index')->with('success', 'Cập nhật tài khoản thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customers = Account::findOrFail($id);
        if(!$customers){
            return redirect('customers.index')->with('error' , 'khach hàng ko tồn tại');
        }
        $filePath = $customers->avatar;
        $customers->delete();
        if($customers){
            if($customers && isset($filePath) && Storage::disk('public')->exists($customers->avatar)){
                Storage::disk('public')->delete($filePath);
            }
            return redirect()->route('customers.index')->with('success',' Xoá thành công !');
            }
                return redirect()->route('customers.index')->with('error',' Có lỗi xin vui lòng thu lại  !');
    }
}
