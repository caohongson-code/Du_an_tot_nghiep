@extends('client.layouts.app')

@section('content')
<style>
    .contact-form {
        max-width: 600px;
        margin: 40px auto;
        background: #fff;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .contact-form h2 {
        text-align: center;
        margin-bottom: 25px;
        font-weight: bold;
    }

    .form-group label {
        font-weight: 500;
        margin-bottom: 5px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 15px;
    }

    .form-control:focus {
        border-color: #4CAF50;
        outline: none;
    }

    .btn-submit {
        background-color: #4CAF50;
        color: white;
        border: none;
        width: 100%;
        padding: 12px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .btn-submit:hover {
        background-color: #45a049;
    }

    .alert-success {
        color: #155724;
        background: #d4edda;
        border: 1px solid #c3e6cb;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        text-align: center;
    }
</style>

<div class="contact-form">
    <h2>Liên hệ với chúng tôi</h2>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('contact.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Họ và tên <span style="color:red">*</span></label>
            <input type="text" id="name" name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="form-group">
            <label for="email">Email <span style="color:red">*</span></label>
            <input type="email" id="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label for="phone">Số điện thoại</label>
            <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}">
        </div>

        <div class="form-group">
            <label for="message">Nội dung <span style="color:red">*</span></label>
            <textarea id="message" name="message" class="form-control" rows="5" required>{{ old('message') }}</textarea>
        </div>

        <button type="submit" class="btn-submit">Gửi liên hệ</button>
    </form>
</div>
@endsection
