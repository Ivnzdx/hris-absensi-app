<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Karyawan - HRIS Absensi</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <h1 class="text-xl font-bold text-gray-800">HRIS - Portal Karyawan</h1>
            <div class="flex flex-wrap items-center gap-4">
                <span class="text-gray-600 text-sm">Halo, <b>{{ Auth::user()->name }}</b> ({{ Auth::user()->position }})</span>
                
                <a href="{{ route('employee.leaves') }}" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                    + Pengajuan Cuti
                </a>
                <a href="{{ route('employee.profile') }}" class="bg-gray-800 text-white px-3 py-1.5 rounded-lg text-sm font-semibold hover:bg-gray-900 transition">
                    👤 Profil Saya
                </a>

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-red-600 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-4xl mx-auto p-6 mt-6">

        <!-- Notifikasi Pesan Sukses / Error -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Card Absensi Hari Ini -->
        <div class="bg-white p-6 rounded-xl shadow-md mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-2">Absensi Hari Ini</h2>
            <p class="text-gray-500 text-sm mb-6">Tanggal: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-center">
                <!-- Box Absen Masuk -->
                <div class="p-4 border rounded-xl bg-gray-50">
                    <p class="text-sm text-gray-600 mb-1">Jam Masuk (Clock-In)</p>
                    <p class="text-2xl font-bold text-gray-800 mb-4">
                        {{ $attendance->clock_in ?? '--:--:--' }}
                    </p>
                    @if(!$attendance || !$attendance->clock_in)
                        <form action="{{ route('employee.clockin') }}" method="POST" id="clockInForm">
                            @csrf
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                            
                            <button type="button" onclick="getLocationAndSubmit()" class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition shadow">
                                Absen Masuk
                            </button>
                        </form>
                    @else
                        <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                            Sudah Absen Masuk ({{ $attendance->status }})
                        </span>
                    @endif
                </div>

                <!-- Box Absen Pulang -->
                <div class="p-4 border rounded-xl bg-gray-50">
                    <p class="text-sm text-gray-600 mb-1">Jam Pulang (Clock-Out)</p>
                    <p class="text-2xl font-bold text-gray-800 mb-4">
                        {{ $attendance->clock_out ?? '--:--:--' }}
                    </p>
                    @if($attendance && $attendance->clock_in && !$attendance->clock_out)
                        <form action="{{ route('employee.clockout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-orange-600 text-white py-2 rounded-lg font-semibold hover:bg-orange-700 transition">
                                Absen Pulang
                            </button>
                        </form>
                    @elseif($attendance && $attendance->clock_out)
                        <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                            Sudah Absen Pulang
                        </span>
                    @else
                        <button disabled class="w-full bg-gray-300 text-gray-500 py-2 rounded-lg font-semibold cursor-not-allowed">
                            Absen Pulang
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Riwayat Absensi Terakhir -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Riwayat Absensi Terakhir</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b text-gray-600 text-sm">
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Jam Masuk</th>
                            <th class="py-3 px-4">Jam Pulang</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @forelse($history as $row)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $row->date }}</td>
                                <td class="py-3 px-4">{{ $row->clock_in ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $row->clock_out ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded text-xs font-semibold 
                                        {{ $row->status == 'Hadir' ? 'bg-green-100 text-green-700' : ($row->status == 'Terlambat' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                                        {{ $row->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">Belum ada riwayat absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Script untuk Mengambil Lokasi GPS Geolocation -->
    <script>
    function getLocationAndSubmit() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
                document.getElementById('clockInForm').submit();
            }, function(error) {
                alert('Gagal mendeteksi lokasi GPS. Pastikan izin akses lokasi (Location Permission) di browser Anda diaktifkan!');
            }, {
                enableHighAccuracy: true
            });
        } else {
            alert("Browser Anda tidak mendukung fitur Geolocation.");
        }
    }
    </script>

</body>
</html>