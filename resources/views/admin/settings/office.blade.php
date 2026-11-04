<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Titik Kantor - Admin HRIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased p-6">

    <div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-bold text-gray-800">⚙️ Pengaturan Titik Pusat Kantor & Radius GPS</h1>
            <a href="{{ url('/admin/dashboard') }}" class="text-sm bg-gray-500 text-white px-3 py-1.5 rounded-lg hover:bg-gray-600 transition">Kembali</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.office.update') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Latitude Kantor</label>
                <input type="text" name="latitude" value="{{ $lat }}" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:ring focus:ring-blue-300" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Longitude Kantor</label>
                <input type="text" name="longitude" value="{{ $lng }}" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:ring focus:ring-blue-300" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Maksimal Radius (dalam meter)</label>
                <input type="number" name="radius" value="{{ $radius }}" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:ring focus:ring-blue-300" required>
                <p class="text-xs text-gray-500 mt-1">Contoh: 100 berarti karyawan bisa absen dalam radius 100 meter dari titik kantor.</p>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition shadow">
                Simpan Perubahan Koordinat
            </button>
        </form>
    </div>

</body>
</html>