<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        return view('admin.shifts.index', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_time' => ['required'],
            'end_time' => ['required'],
        ]);

        Shift::create($request->all());

        return back()->with('success', 'Shift kerja baru berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Shift::findOrFail($id)->delete();
        return back()->with('success', 'Shift kerja berhasil dihapus.');
    }
}