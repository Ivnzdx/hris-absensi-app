<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Shift Kerja - Panel Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <!-- Navbar -->
    <nav class="bg-gray-900 text-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold">HRIS - Panel Admin</h1>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-white text-sm">Dashboard Utama</a>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-5xl mx-auto p-6 mt-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Shift Kerja</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Form Tambah Shift -->
            <div class="bg-white p-6 rounded-xl shadow-md md:col-span-1">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Tambah Shift Baru</h3>
                <form action="{{ route('admin.shifts.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Shift</label>
                        <input type="text" name="name" required placeholder="Contoh: Shift Pagi" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jam Masuk</label>
                        <input type="time" name="start_time" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jam Pulang</label>
                        <input type="time" name="end_time" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                        Simpan Shift
                    </button>
                </form>
            </div>

            <!-- Tabel Daftar Shift -->
            <div class="bg-white p-6 rounded-xl shadow-md md:col-span-2">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Shift Kerja</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b text-gray-600 text-sm">
                                <th class="py-3 px-4">Nama Shift</th>
                                <th class="py-3 px-4">Jam Masuk</th>
                                <th class="py-3 px-4">Jam Pulang</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700">
                            @forelse($shifts as $shift)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4 font-semibold">{{ $shift->name }}</td>
                                    <td class="py-3 px-4">{{ $shift->start_time }}</td>
                                    <td class="py-3 px-4">{{ $shift->end_time }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <form action="{{ route('admin.shifts.destroy', $shift->id) }}" method="POST" onsubmit="return confirm('Hapus shift ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-xs font-semibold hover:bg-red-600 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-gray-500">Belum ada data shift kerja.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>