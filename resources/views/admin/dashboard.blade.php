<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin HRD - HRIS Absensi</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <!-- Navbar -->
    <nav class="bg-gray-900 text-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold">HRIS - Panel Admin / HRD</h1>
            <div class="flex items-center gap-4">
                <span class="text-gray-300 text-sm">Halo, <b>{{ Auth::user()->name }}</b> (Admin)</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-red-700 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-6xl mx-auto p-6 mt-6">

        <!-- Notifikasi Pesan -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Statistik Singkat -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-600">
                <p class="text-sm text-gray-500 font-semibold">Total Karyawan</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalEmployees }} Orang</p>
            </div>
            <div class="flex flex-wrap gap-3 mb-6">
                <a href="{{ route('admin.settings.office') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-purple-700 transition shadow">
    📍 Pengaturan Koordinat Kantor
</a>
    <a href="{{ route('admin.reports.excel') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-emerald-700 transition shadow">
        📊 Unduh Laporan Excel (CSV)
    </a>
    <a href="{{ route('admin.reports.pdf') }}" class="bg-rose-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-rose-700 transition shadow">
        🖨️ Cetak / Unduh Laporan PDF
    </a>
</div>
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-600">
                <p class="text-sm text-gray-500 font-semibold">Absen Hari Ini</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $attendances->count() }} Orang</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-yellow-500">
                <p class="text-sm text-gray-500 font-semibold">Cuti Menunggu Persetujuan</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $pendingLeaves->count() }} Pengajuan</p>
            </div>
        </div>

        <!-- Tabel Persetujuan Cuti Karyawan -->
        <div class="bg-white p-6 rounded-xl shadow-md mb-8">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Persetujuan Cuti / Izin Karyawan</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b text-gray-600 text-sm">
                            <th class="py-3 px-4">Nama Karyawan</th>
                            <th class="py-3 px-4">Mulai</th>
                            <th class="py-3 px-4">Selesai</th>
                            <th class="py-3 px-4">Alasan</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @forelse($pendingLeaves as $leave)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 font-semibold">{{ $leave->user->name }}</td>
                                <td class="py-3 px-4">{{ $leave->start_date }}</td>
                                <td class="py-3 px-4">{{ $leave->end_date }}</td>
                                <td class="py-3 px-4">{{ $leave->reason }}</td>
                                <td class="py-3 px-4 text-center flex justify-center gap-2">
                                    <form action="{{ route('admin.leaves.update', $leave->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="Approved">
                                        <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-xs font-semibold hover:bg-green-700 transition">
                                            Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.leaves.update', $leave->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="Rejected">
                                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold hover:bg-red-700 transition">
                                            Tolak
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-gray-500">Tidak ada pengajuan cuti yang pending.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
<div class="mb-6 flex justify-end">
    <a href="{{ route('admin.employees') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition shadow">
        ⚙️ Kelola Data Karyawan
    </a>
</div>
        <!-- Rekap Absensi Hari Ini -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Rekap Absensi Karyawan Hari Ini ({{ \Carbon\Carbon::now()->translatedFormat('d F Y') }})</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b text-gray-600 text-sm">
                            <th class="py-3 px-4">Nama Karyawan</th>
                            <th class="py-3 px-4">Jam Masuk</th>
                            <th class="py-3 px-4">Jam Pulang</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @forelse($attendances as $att)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 font-semibold">{{ $att->user->name }}</td>
                                <td class="py-3 px-4">{{ $att->clock_in ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $att->clock_out ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold 
                                        {{ $att->status == 'Hadir' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $att->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">Belum ada karyawan yang melakukan absensi hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>