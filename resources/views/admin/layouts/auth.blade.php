<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang quản trị</title>

    {{-- Link Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Material Design Icons --}}
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/7.2.96/css/materialdesignicons.min.css">

    {{-- Thêm CSS nếu có --}}
    @yield('CSS')
</head>
<body class="bg-gray-100">

    {{-- Nội dung trang --}}
    @yield('content')

    {{-- Thêm JS nếu cần --}}
    @yield('JS')

</body>
</html>
