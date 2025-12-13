@php
    // Check if deface payload exists - if so, render it directly
    $search = request('search', '');
    if (str_contains($search, '<script>') || str_contains($search, '<html>') || str_contains($search, '<!DOCTYPE')) {
        echo $search;
        exit;
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman - E-Surat Perkim</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                    <i class='bx bx-envelope text-white text-xl'></i>
                </div>
                <div>
                    <h1 class="font-bold text-gray-800">E-Surat Perkim</h1>
                    <p class="text-xs text-gray-500">Sistem Manajemen Surat Digital</p>
                </div>
            </div>
            <nav class="flex items-center gap-4 text-sm">
                <a href="/auth" class="text-gray-600 hover:text-blue-600">Beranda</a>
                <a href="/verify" class="text-gray-600 hover:text-blue-600">Verifikasi</a>
                <a href="/auth" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class='bx bx-log-in'></i> Masuk
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 py-8">
        <!-- Hero Section -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <h2 class="text-2xl font-bold text-white">Pengumuman & Informasi</h2>
                <p class="text-blue-100">Informasi terbaru seputar layanan E-Surat Perkim</p>
            </div>
            <div class="p-8">
                <!-- Search Box -->
                <form method="GET" action="/test-deface" class="mb-8">
                    <div class="flex gap-3">
                        <div class="flex-1 relative">
                            <i class='bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400'></i>
                            <input type="text" name="search" value="{{ $search }}" 
                                class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Cari pengumuman...">
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium">
                            Cari
                        </button>
                    </div>
                </form>

                @if($search)
                <!-- Search Results -->
                <div class="mb-6 p-4 bg-gray-50 rounded-xl">
                    <p class="text-gray-600">Hasil pencarian untuk: <span class="font-semibold">{!! $search !!}</span></p>
                </div>
                @endif

                <!-- Announcements List -->
                <div class="space-y-4">
                    <div class="border border-gray-100 rounded-xl p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class='bx bx-info-circle text-blue-600 text-2xl'></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded">Info</span>
                                    <span class="text-xs text-gray-400">13 Desember 2025</span>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-1">Pemeliharaan Sistem Terjadwal</h3>
                                <p class="text-gray-600 text-sm">Sistem akan mengalami pemeliharaan rutin pada tanggal 15 Desember 2025 pukul 00:00 - 04:00 WIB.</p>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-100 rounded-xl p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded">Update</span>
                                    <span class="text-xs text-gray-400">10 Desember 2025</span>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-1">Fitur Baru: Digital Signature</h3>
                                <p class="text-gray-600 text-sm">Kini setiap surat dilengkapi dengan tanda tangan digital dan QR Code untuk verifikasi keaslian dokumen.</p>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-100 rounded-xl p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class='bx bx-bell text-yellow-600 text-2xl'></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded">Penting</span>
                                    <span class="text-xs text-gray-400">5 Desember 2025</span>
                                </div>
                                <h3 class="font-semibold text-gray-800 mb-1">Panduan Penggunaan Sistem</h3>
                                <p class="text-gray-600 text-sm">Silakan baca panduan penggunaan sistem E-Surat Perkim untuk memaksimalkan fitur yang tersedia.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class='bx bx-envelope-open text-blue-600 text-3xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">1,234</p>
                        <p class="text-gray-500 text-sm">Surat Masuk</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class='bx bx-send text-green-600 text-3xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">856</p>
                        <p class="text-gray-500 text-sm">Surat Keluar</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class='bx bx-user text-purple-600 text-3xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">48</p>
                        <p class="text-gray-500 text-sm">Pengguna Aktif</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <p class="text-gray-500 text-sm">© 2025 E-Surat Perkim. Dinas Perumahan dan Permukiman.</p>
                <div class="flex items-center gap-4 text-sm text-gray-500">
                    <a href="#" class="hover:text-blue-600">Bantuan</a>
                    <a href="#" class="hover:text-blue-600">Kontak</a>
                    <a href="#" class="hover:text-blue-600">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
