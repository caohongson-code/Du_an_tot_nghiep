<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Role;
use Illuminate\Http\Request;
use Exception;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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
            'role_id.required'   => 'Vui lòng chọn quyền.',
            'role_id.exists'     => 'Quyền không tồn tại.',
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
            'role_id' => 'required|exists:roles,id',
            'full_name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_of_birth' => 'nullable|date',
            'email' => 'required|email|unique:accounts,email',
            'phone' => 'nullable|string|max:10',
            'gender' => 'required|in:0,1',
            'address' => 'required|string',
            'password' => 'required|string|min:6',
        ],$message
    );
        try{
            DB::beginTransaction();
    $filePath = null;
    if ($request->hasFile('avatar')) {
        $filePath = $request->file('avatar')->store('uploads/khachhang', 'public');
        $data['avatar' ] = $filePath;
    }
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customers = Account::findOrFail($id);
        if(!$customers){
            return redirect('accounts.index')->with('error' , 'khach hàng ko tồn tại');
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
