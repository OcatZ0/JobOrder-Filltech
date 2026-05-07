<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md px-4">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-700 mb-2">{{ config('app.name') }}</h1>
            <p class="text-gray-500 text-sm">Masuk ke akun Anda</p>
        </div>

        <!-- Login Card -->
        <div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6">
            <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-medium text-slate-700 mb-2">
                        Username
                    </label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="input input-bordered w-full @error('username') input-error @enderror"
                        placeholder="Masukkan username Anda"
                        value="{{ old('username') }}"
                        required
                    />
                    @error('username')
                        <span class="text-error text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                        Kata Sandi
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="input input-bordered w-full @error('password') input-error @enderror"
                        placeholder="Masukkan kata sandi Anda"
                        required
                    />
                    @error('password')
                        <span class="text-error text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="btn btn-primary w-full mt-6"
                >
                    Masuk
                </button>
            </form>
        </div>
    </div>
</body>
</html>
