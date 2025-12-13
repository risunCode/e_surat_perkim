<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-red-50 flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        <!-- Icon -->
        <div class="mb-6">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-red-100 text-red-600 mb-4">
                <i class="bx bx-shield-x text-5xl"></i>
            </div>
        </div>
        
        <!-- Error Code -->
        <h1 class="text-7xl font-bold text-gray-800 mb-2">403</h1>
        
        <!-- Title -->
        <h2 class="text-xl font-semibold text-gray-700 mb-3">Akses Ditolak</h2>
        
        <!-- Description -->
        <p class="text-gray-500 mb-8">
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
        </p>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="javascript:history.back()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="bx bx-arrow-back"></i>
                Kembali
            </a>
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="bx bx-home"></i>
                Beranda
            </a>
        </div>
        
        <!-- Footer -->
        <p class="mt-10 text-xs text-gray-400">E-Surat Perkim</p>
    </div>
</body>
</html>
