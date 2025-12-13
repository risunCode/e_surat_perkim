<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Sedang Maintenance</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-purple-50 flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        <!-- Icon -->
        <div class="mb-6">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-purple-100 text-purple-600 mb-4">
                <i class="bx bx-wrench text-5xl"></i>
            </div>
        </div>
        
        <!-- Error Code -->
        <h1 class="text-7xl font-bold text-gray-800 mb-2">503</h1>
        
        <!-- Title -->
        <h2 class="text-xl font-semibold text-gray-700 mb-3">Sedang Maintenance</h2>
        
        <!-- Description -->
        <p class="text-gray-500 mb-8">
            Sistem sedang dalam pemeliharaan. Silakan kembali beberapa saat lagi.
        </p>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="javascript:location.reload()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="bx bx-refresh"></i>
                Coba Lagi
            </a>
        </div>
        
        <!-- Footer -->
        <p class="mt-10 text-xs text-gray-400">E-Surat Perkim</p>
    </div>
</body>
</html>
