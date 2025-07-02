<?php

use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;
use App\Livewire\Staff\StaffIndex;
use App\Livewire\Staff\StaffEdit;
use App\Livewire\Service\ServiceIndex;
use App\Livewire\Service\ServiceEdit;
use App\Livewire\Space\SpaceIndex;
use App\Livewire\Space\SpaceEdit;
use App\Livewire\Appointment\AppointmentIndex;
use App\Livewire\Appointment\AppointmentEdit;	
use App\Livewire\Appointment\AppointmentCreate;	
use App\Livewire\Appointment\BusinessHours;	
use App\Livewire\Client\ClientIndex;
use App\Livewire\Client\ClientEdit;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Dashboard\ChartIndex;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::get('/logout', function () {
    auth()->logout();
    return redirect()->route('login');
})->name('logout');

Route::get('/teste', function () {
    return redirect()->route('admin.staffs')->with('success', 'Email verificado com sucesso! 🎉');
})->name('teste');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('admin.staffs')->with('success', 'Email verificado com sucesso! 🎉');
})->middleware(['signed'])->name('verification.verify');

Route::middleware(['auth'])->group(function () {

    Route::get('/', Welcome::class);
    Route::get('/email/verify', VerifyEmail::class)->name('verification.notice');

    Route::post('/email/resend', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Email de verificação reenviado!');
    })->name('verification.resend');

    Route::middleware(['verified'])->group(function () {
        Route::prefix('admin')->group(function () {
            Route::get('dashboard', ChartIndex::class)->name('admin.dashboard');
            Route::get('staffs', StaffIndex::class)->name('admin.staffs');
            Route::get('staffs/{id}/edit', StaffEdit::class)->name('admin.staffs.edit');
            Route::get('services', ServiceIndex::class)->name('admin.services');
            Route::get('services/{id}/edit', ServiceEdit::class)->name('admin.services.edit');
            Route::get('spaces', SpaceIndex::class)->name('admin.spaces');
            Route::get('spaces/{id}/edit', SpaceEdit::class)->name('admin.spaces.edit');
            Route::get('appointments', AppointmentIndex::class)->name('admin.appointments');
            Route::get('appointments/{appointment}/edit', AppointmentEdit::class)->name('admin.appointments.edit');
            Route::get('appointments/create', AppointmentCreate::class)->name('admin.appointments.create');
            Route::post('appointments/{appointment}/UpdateDragAndDrop', [AppointmentIndex::class, 'UpdateDragAndDrop'])->name('admin.appointments.UpdateDragAndDrop');
            Route::get('business-hours', BusinessHours::class)->name('admin.business-hours');
            Route::get('clients', ClientIndex::class)->name('admin.clients');
            Route::get('clients/{client}/edit', ClientEdit::class)->name('admin.clients.edit');
        });
    });
});
