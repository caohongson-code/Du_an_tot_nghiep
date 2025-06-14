<?php

namespace App\Http\Controllers;

use App\Models\AdminKhachHang;
use App\Models\AdminsDonHang;
use App\Models\AdminsSanPham;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminsDonHangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $donHangs = AdminsDonHang::select(
        'don_hangs.*',
        'kh.ten_khach_hang',
        'kh.sdt',
        'sp.ten_san_pham'
    )
    ->leftJoin('khach_hangs as kh', 'don_hangs.khach_hang_id', '=', 'kh.id')
    ->leftJoin('san_phams as sp', 'don_hangs.san_pham_id', '=', 'sp.id')
    ->orderByDesc('don_hangs.created_at')
    ->paginate(10);

    return view('admins.donhangs.index', compact('donHangs'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $khachHangs = AdminKhachHang::select('id', 'ten_khach_hang', 'dia_chi', 'sdt')->get();
        $sanPhams = AdminsSanPham::select('id', 'ten_san_pham', 'gia', 'gia_khuyen_mai')->get();
        return view('admins.donhangs.create', compact('khachHangs', 'sanPhams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'ma_don_hang' => 'required|unique:don_hangs,ma_don_hang',
                'khach_hang_id' => 'required|exists:khach_hangs,id',
                'san_pham_id' => 'required|exists:san_phams,id',
                'so_luong' => 'required|integer|min:1',
                'tien_ship' => 'required|numeric|min:0',
            ]);

            $sanPham = AdminsSanPham::findOrFail($request->san_pham_id);

            // Nếu giá khuyến mãi null thì gán 0
            $giaKhuyenMai = $sanPham->gia_khuyen_mai ?? 0;

            $tongTien = ($sanPham->gia - $giaKhuyenMai) * $request->so_luong + $request->tien_ship;

            $dataDonHang = [
                'ma_don_hang' => $request->ma_don_hang,
                'khach_hang_id' => $request->khach_hang_id,
                'san_pham_id' => $request->san_pham_id,
                'so_luong' => $request->so_luong,
                'tien_ship' => $request->tien_ship,
                'created_at' => now(),
                'tong_tien' => $tongTien,
                'trang_thais' => $request->trang_thais,
            ];

            AdminsDonHang::create($dataDonHang);
            DB::commit();

            return redirect()->route('donhang.index')->with('success', 'Thêm đơn hàng thành công!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('donhang.create')->with('error', 'Có lỗi xảy ra khi thêm đơn hàng: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show()
    {


    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $donHang = AdminsDonHang::findOrFail($id);


        return view('admins.donhangs.edit', compact('donHang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'so_luong' => 'required|integer|min:1',
            'tien_ship' => 'required|numeric|min:0',
            'trang_thais' => 'required|in:0,1,2,3,4',
        ]);

        $donHang = AdminsDonHang::findOrFail($id);

        $sanPham = AdminsSanPham::findOrFail($donHang->san_pham_id);
        $tongTien = ($sanPham->gia - $sanPham->gia_khuyen_mai) * $request->so_luong + $request->tien_ship;

        $donHang->update([
            'so_luong' => $request->so_luong,
            'tien_ship' => $request->tien_ship,
            'trang_thais' => $request->trang_thais,
            'tong_tien' => $tongTien,
        ]);


        return redirect()->route('donhang.index')->with('success', 'Cập nhật đơn hàng thành công');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function DetailKH(string $id)
    {

    }
}
