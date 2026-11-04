<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi Karyawan - HRIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-white font-sans antialiased p-8" onload="window.print()">

    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center border-b pb-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">PT. PERUSAHAAN ANDA</h1>
                <p class="text-sm text-gray-500">Laporan Rekapitulasi Absensi Karyawan Keseluruhan</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Tanggal Cetak:</p>
                <p class="text-sm font-semibold text-gray-800">{{ date('d-m-Y') }}</p>
            </div>
        </div>

        <div class="overflow-x-auto mb-6">
            <table class="w-full text-left border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm">
                        <th class="border border-gray-300 py-2 px-3">No</th>
                        <th class="border border-gray-300 py-2 px-3">Nama Karyawan</th>
                        <th class="border border-gray-300 py-2 px-3">Tanggal</th>
                        <th class="border border-gray-300 py-2 px-3">Jam Masuk</th>
                        <th class="border border-gray-300 py-2 px-3">Jam Pulang</th>
                        <th class="border border-gray-300 py-2 px-3">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse($attendances as $index => $att)
                        <tr>
                            <td class="border border-gray-300 py-2 px-3 text-center">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 py-2 px-3 font-semibold">{{ $att->user->name ?? 'N/A' }}</td>
                            <td class="border border-gray-300 py-2 px-3">{{ $att->date }}</td>
                            <td class="border border-gray-300 py-2 px-3">{{ $att->clock_in ?? '-' }}</td>
                            <td class="border border-gray-300 py-2 px-3">{{ $att->clock_out ?? '-' }}</td>
                            <td class="border border-gray-300 py-2 px-3">{{ $att->status ?? 'Hadir' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border border-gray-300 py-4 text-center text-gray-500">Belum ada data laporan absensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="no-print flex justify-end gap-3">
            <button onclick="window.history.back()" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-600 transition">Kembali</button>
            <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">Cetak / Simpan PDF</button>
        </div>
    </div>

</body>
</html>