<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - PGMF Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="text-center mb-6">
                <div class="w-12 h-12 bg-teal-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <x-heroicon-o-lock-closed class="w-6 h-6 text-white" />
                </div>
                <h1 class="text-xl font-bold text-gray-800">PGMF Admin</h1>
                <p class="text-sm text-gray-500 mt-1">เข้าสู่ระบบหลังบ้าน</p>
            </div>

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-2.5 rounded-lg text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน</label>
                    <input type="password" name="password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                </div>
                <button type="submit" class="w-full bg-teal-600 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-teal-700 transition-colors">
                    เข้าสู่ระบบ
                </button>
            </form>
        </div>
    </div>
</body>
</html>
