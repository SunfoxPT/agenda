<?php

use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;
use App\Livewire\Staff\StaffIndex;
use App\Livewire\Staff\StaffEdit;
use App\Livewire\Service\ServiceIndex;
use App\Livewire\Service\ServiceEdit;
use App\Livewire\Space\SpaceIndex;
use App\Livewire\Space\SpaceEdit;

Route::get('/', Welcome::class);

Route::prefix('admin')->group(function () {
    Route::get('staffs', StaffIndex::class)->name('admin.staffs');
    Route::get('staffs/{id}/edit', StaffEdit::class)->name('admin.staffs.edit');

    Route::get('services', ServiceIndex::class)->name('admin.services');
    Route::get('services/{id}/edit', ServiceEdit::class)->name('admin.services.edit');

    Route::get('spaces', SpaceIndex::class)->name('admin.spaces');
    Route::get('spaces/{id}/edit', SpaceEdit::class)->name('admin.spaces.edit');
});
