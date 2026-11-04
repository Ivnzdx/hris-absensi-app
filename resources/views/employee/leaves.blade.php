<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Cuti - HRIS Absensi</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">HRIS - Portal Karyawan</h1>
            <div class="flex items-center gap-4">
                <a href="{{ route('employee.dashboard') }}" class="text-blue-600 hover:underline text-sm font-semibold">Dashboard Utama</a>
                <form action="{{ route('logout') }}" method="POST">
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

        <h2 class="text-2xl font-bold text-gray-800 mb-6">Pengajuan Cuti / Izin</h2>

        <!-- Notifikasi Pesan -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Pengajuan Cuti -->
        <div class="bg-white p-6 rounded-xl shadow-md mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Form Buat Pengajuan Baru</h3>
            <form action="{{ route('employee.leaves.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Selesai</label>
                        <input type="date" name="end_date" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Alasan Cuti / Izin</label>
                    <textarea name="reason" rows="3" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Keperluan keluarga / Sakit berobat"></textarea>
                </div>
                <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                    Kirim Pengajuan
                </button>
            </form>
        </div>

        <!-- Riwayat Pengajuan Cuti -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Riwayat Pengajuan Cuti Anda</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b text-gray-600 text-sm">
                            <th class="py-3 px-4">Mulai</th>
                            <th class="py-3 px-4">Selesai</th>
                            <th class="py-3 px-4">Alasan</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @forelse($leaves as $leave)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $leave->start_date }}</td>
                                <td class="py-3 px-4">{{ $leave->end_date }}</td>
                                <td class="py-3 px-4">{{ $leave->reason }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold 
                                        {{ $leave->status == 'Approved' ? 'bg-green-100 text-green-700' : ($leave->status == 'Rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ $leave->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">Belum ada riwayat pengajuan cuti.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>