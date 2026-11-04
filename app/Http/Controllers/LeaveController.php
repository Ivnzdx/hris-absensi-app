<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    // Menampilkan halaman riwayat & form pengajuan cuti karyawan
    public function index()
    {
        $user = Auth::user();
        
        // Ambil daftar pengajuan cuti milik karyawan yang sedang login
        $leaves = Leave::where('user_id', $user->id)
                       ->orderBy('created_at', 'desc')
                       ->get();

        return view('employee.leaves', compact('leaves'));
    }

    // Menyimpan pengajuan cuti baru
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        Leave::create([
            'user_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'Pending', // Status awal saat diajukan
        ]);

        return back()->with('success', 'Pengajuan cuti/izin berhasil dikirim dan menunggu persetujuan HRD.');
    }
}