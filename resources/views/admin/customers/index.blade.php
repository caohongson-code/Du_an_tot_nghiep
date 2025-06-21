@extends('admin.layouts.app')

@section('content')
<h1>Account List</h1>

<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            </div>
        </div>
    </div>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
       <strong> {{session('success')}} </strong>
       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
   </div>
   @endif
   @if ($errors->any())
   <div class="alert alert-danger">
       <ul class="mb-0">
           @foreach ($errors->all() as $error)
               <li>{{ $error }}</li>
           @endforeach
       </ul>
   </div>
@endif
    <div class="row">
        <div class="col">

            <div class="h-100">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Danh sách</h4>

                        <a href="{{route('customers.create')}}" class="btn btn-success">
                            Add Account
                        </a>

                    </div>
                    <form action="{{route('accounts.index')}}" method="GET" class="d-flex mb-4">
                        <input type="text" name="keyword" class="form-control me-2" placeholder="Nhập mã hoặc tên " value="{{ request('keyword') }}">

                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    </form>
                    <div class="card-body">
                        <div class="live-preview">
                            <div class="table-responsive">
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($listKH as $customer)
                                            <tr>
                                                <td>{{ $customer->id }}</td>
                                                <td><img src="{{ asset('storage/' . $customer->avatar)  }}"class="img-thumbnail" alt="hinh anh" width="100px" height= "auto"></td>
                                                <td>{{ $customer->full_name }}</td>
                                                <td>{{ $customer->email }}</td>
                                                <td>{{ $customer->role->role_name }}</td>
                                                <td>

                                                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Delete this customer?')">Delete</button>
                                                    </form>
                                                    {{-- @else --}}
                                          {{-- <span class="text-muted">No permission</span> --}}

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    {{$listKH->links("pagination::bootstrap-5")}}
                                </div>

                            </div>
                        </div>

                    </div><!-- end card-body -->
                </div><!-- end card -->

            </div> <!-- end .h-100-->

        </div> <!-- end col -->
    </div>

</div>
@endsection
