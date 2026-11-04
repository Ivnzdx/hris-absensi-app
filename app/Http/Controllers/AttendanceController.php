<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Menampilkan halaman dashboard karyawan & status absen hari ini
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::now()->toDateString();

        // Cek apakah karyawan sudah absen hari ini
        $attendance = Attendance::where('user_id', $user->id)
                                   ->where('date', $today)
                                   ->first();

        // Ambil riwayat absensi 30 hari terakhir
        $history = Attendance::where('user_id', $user->id)
                                   ->orderBy('date', 'desc')
                                   ->take(30)
                                   ->get();

        return view('employee.dashboard', compact('attendance', 'history'));
    }

    // Proses Absen Masuk (Clock-In) dengan Validasi Geofencing GPS
    public function clockIn(Request $request)
    {
        $request->validate([
            'latitude' => ['required'],
            'longitude' => ['required'],
        ]);

        $user = Auth::user();
        $today = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        // Cek apakah sudah absen hari ini
        $exists = Attendance::where('user_id', $user->id)->where('date', $today)->first();
        if ($exists) {
            return back()->with('error', 'Anda sudah melakukan absen masuk hari ini.');
        }

        // Ambil Koordinat Kantor & Radius dari Database Pengaturan Admin
        $officeLat = \App\Models\Setting::where('key', 'office_lat')->value('value') ?? -7.296718002007609; 
        $officeLng = \App\Models\Setting::where('key', 'office_lng')->value('value') ?? 112.39930447663562; 
        $maxRadius = \App\Models\Setting::where('key', 'office_radius')->value('value') ?? 100; // dalam meter
        // Koordinat Kantor & Radius Maksimal (dalam meter)
        $officeLat = -7.296718002007609; 
        $officeLng = 112.39930447663562; 
        $maxRadius = 100; // 100 meter

        // Hitung jarak menggunakan rumus Haversine
        $distance = $this->calculateDistance($officeLat, $officeLng, $request->latitude, $request->longitude);

        if ($distance > $maxRadius) {
            return back()->with('error', 'Gagal Absen! Anda berada di luar radius kantor (Jarak: ' . round($distance) . ' meter dari titik pusat kantor).');
        }

        // Tentukan status terlambat jika lewat dari jam 08:00
        $status = 'Hadir';
        if (Carbon::now()->format('H:i') > '08:00') {
            $status = 'Terlambat';
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'clock_in' => $currentTime,
            'status' => $status,
        ]);

        return back()->with('success', 'Berhasil melakukan Absen Masuk! Anda berada dalam area kantor.');
    }

    // Proses Absen Pulang (Clock-Out)
    public function clockOut(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();

        if (!$attendance) {
            return back()->with('error', 'Anda belum melakukan absen masuk hari ini.');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'Anda sudah melakukan absen pulang hari ini.');
        }

        $attendance->update([
            'clock_out' => $currentTime,
        ]);

        return back()->with('success', 'Berhasil melakukan Absen Pulang. Hati-hati di jalan!');
    }

    // Fungsi helper perhitungan jarak Haversine (dalam meter)
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
}