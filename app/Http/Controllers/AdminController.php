<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Menampilkan Dashboard Admin & Rekap Absensi Hari Ini
    public function dashboard()
    {
        $today = Carbon::now()->toDateString();

        // Ambil data absensi semua karyawan hari ini
        $attendances = Attendance::with('user')->where('date', $today)->get();

        // Ambil daftar pengajuan cuti yang masih 'Pending'
        $pendingLeaves = Leave::with('user')->where('status', 'Pending')->get();

        // Total karyawan aktif
        $totalEmployees = User::where('role', 'employee')->count();

        return view('admin.dashboard', compact('attendances', 'pendingLeaves', 'totalEmployees'));
    }
    // Menampilkan halaman pengaturan titik kantor oleh Admin
    public function officeSetting()
    {
        $lat = \App\Models\Setting::where('key', 'office_lat')->value('value') ?? '-7.296718002007609';
        $lng = \App\Models\Setting::where('key', 'office_lng')->value('value') ?? '112.39930447663562';
        $radius = \App\Models\Setting::where('key', 'office_radius')->value('value') ?? '100';

        return view('admin.settings.office', compact('lat', 'lng', 'radius'));
    }

    // Menyimpan perubahan koordinat kantor
    public function updateOfficeSetting(Request $request)
    {
        $request->validate([
            'latitude' => ['required'],
            'longitude' => ['required'],
            'radius' => ['required', 'numeric'],
        ]);

        \App\Models\Setting::updateOrCreate(['key' => 'office_lat'], ['value' => $request->latitude]);
        \App\Models\Setting::updateOrCreate(['key' => 'office_lng'], ['value' => $request->longitude]);
        \App\Models\Setting::updateOrCreate(['key' => 'office_radius'], ['value' => $request->radius]);

        return back()->with('success', 'Titik pusat kantor dan radius berhasil diperbarui!');
    }
    // Menampilkan daftar seluruh karyawan
    public function employees()
    {
        $employees = User::where('role', 'employee')->orderBy('name', 'asc')->get();
        return view('admin.employees.index', compact('employees'));
    }

    // Menampilkan form tambah karyawan
    public function createEmployee()
    {
        return view('admin.employees.create');
    }

    // Menyimpan data karyawan baru
    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'position' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position,
            'role' => 'employee',
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.employees')->with('success', 'Data karyawan baru berhasil ditambahkan.');
    }
// Ekspor Data Absensi ke Excel (Format CSV)
    public function exportExcel()
    {
        $fileName = 'laporan-absensi-' . date('Y-m-d') . '.csv';
        $attendances = \App\Models\Attendance::with('user')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($attendances) {
            $file = fopen('php://output', 'w');
            // Header kolom CSV
            fputcsv($file, ['No', 'Nama Karyawan', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status']);

            $no = 1;
            foreach ($attendances as $att) {
                fputcsv($file, [
                    $no++,
                    $att->user->name ?? 'N/A',
                    $att->date,
                    $att->clock_in ?? '-',
                    $att->clock_out ?? '-',
                    $att->status ?? 'Hadir'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Tampilkan Halaman Cetak PDF / Print Laporan
    public function exportPdf()
    {
        $attendances = \App\Models\Attendance::with('user')->get();
        return view('admin.reports.pdf', compact('attendances'));
    }
    // Menghapus data karyawan
    public function destroyEmployee($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $employee->delete();

        return back()->with('success', 'Data karyawan berhasil dihapus.');
    }

    // Aksi Admin untuk Menyetujui atau Menolak Cuti
    public function updateLeaveStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:Approved,Rejected'],
        ]);

        $leave = Leave::findOrFail($id);
        $leave->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status pengajuan cuti berhasil diperbarui.');
    }
}