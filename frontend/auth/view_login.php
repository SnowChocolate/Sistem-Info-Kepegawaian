<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPEG - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen font-sans">

<div class="w-full max-w-md mx-auto px-4 py-4">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center text-blue-600">
            <i class="fa-solid fa-id-card-alt text-5xl"></i>
        </div>
        <h2 class="mt-4 text-center text-3xl font-extrabold text-gray-900 tracking-tight">
            MASUK
        </h2>
        <p class="mt-1 text-center text-sm text-gray-600">
            Sistem Informasi Kepegawaian & Absensi
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-6 px-4 shadow sm:rounded-lg sm:px-10 border border-gray-100">
            
            <?php if (isset($_SESSION['error'])) : ?>
                <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-3 rounded text-sm text-red-700 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-2 flex-shrink-0"></i> 
                    <span><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])) : ?>
                <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-3 rounded text-sm text-green-700 flex items-center">
                    <i class="fa-solid fa-circle-check mr-2 flex-shrink-0"></i> 
                    <span><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
                </div>
            <?php endif; ?>

            <form class="space-y-4" action="index.php?page=login" method="POST">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-user text-xs"></i>
                        </div>
                        <input id="username" name="username" type="text" required 
                            class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                            placeholder="Masukkan username anda">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-lock text-xs"></i>
                        </div>
                        <input id="password" name="password" type="password" required 
                            class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember_me" name="remember_me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">Ingat Saya</label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors cursor-pointer">
                        Login
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Belum punya akun?</span>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <a href="index.php?page=register" class="font-semibold text-blue-600 hover:text-blue-500 text-sm transition-colors block p-2">
                        Daftar Akun Baru
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>