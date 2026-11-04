<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShiftController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Halaman utama diarahkan langsung ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});
// Profil Karyawan & Ubah Password
    Route::get('/employee/profile', [ProfileController::class, 'index'])->name('employee.profile');
    Route::put('/employee/profile', [ProfileController::class, 'updateProfile'])->name('employee.profile.update');
    Route::put('/employee/password', [ProfileController::class, 'updatePassword'])->name('employee.password.update');
// Rute Autentikasi (Login & Logout)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute yang hanya bisa diakses setelah login (menggunakan middleware 'auth')
Route::middleware(['auth'])->group(function () {
    
    // Dashboard khusus Admin / HRD
    Route::get('/admin/dashboard', function () {
        return "Selamat datang di Panel Admin HRD!";
    })->name('admin.dashboard');

    // Dashboard & Fitur Absensi Karyawan
    Route::get('/employee/dashboard', [AttendanceController::class, 'index'])->name('employee.dashboard');
    Route::post('/employee/clock-in', [AttendanceController::class, 'clockIn'])->name('employee.clockin');
    Route::post('/employee/clock-out', [AttendanceController::class, 'clockOut'])->name('employee.clockout');
// Pengaturan Kantor Admin
    Route::get('/admin/settings/office', [AdminController::class, 'officeSetting'])->name('admin.settings.office');
    Route::post('/admin/settings/office', [AdminController::class, 'updateOfficeSetting'])->name('admin.settings.office.update');
    // Dashboard & Panel Admin / HRD
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/leaves/{id}/update', [AdminController::class, 'updateLeaveStatus'])->name('admin.leaves.update');
 // Ekspor Laporan Absensi
    Route::get('/admin/reports/excel', [AdminController::class, 'exportExcel'])->name('admin.reports.excel');
    Route::get('/admin/reports/pdf', [AdminController::class, 'exportPdf'])->name('admin.reports.pdf');
    // Manajemen Shift Kerja
    Route::get('/admin/shifts', [ShiftController::class, 'index'])->name('admin.shifts');
    Route::post('/admin/shifts', [ShiftController::class, 'store'])->name('admin.shifts.store');
    Route::delete('/admin/shifts/{id}', [ShiftController::class, 'destroy'])->name('admin.shifts.destroy');
    // Fitur Cuti / Izin Karyawan
    Route::get('/employee/leaves', [LeaveController::class, 'index'])->name('employee.leaves');
    Route::post('/employee/leaves', [LeaveController::class, 'store'])->name('employee.leaves.store');
// Manajemen Karyawan oleh Admin
    Route::get('/admin/employees', [AdminController::class, 'employees'])->name('admin.employees');
    Route::get('/admin/employees/create', [AdminController::class, 'createEmployee'])->name('admin.employees.create');
    Route::post('/admin/employees', [AdminController::class, 'storeEmployee'])->name('admin.employees.store');
    Route::delete('/admin/employees/{id}', [AdminController::class, 'destroyEmployee'])->name('admin.employees.destroy');

    });