<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.','middleware' => ['auth', 'role:admin|superadmin']], function () {
    Volt::route('users', 'users.users-index')->name('users.index');
    Volt::route('schools', 'schools.schools-index')->name('schools.index');
    Volt::route('sectors', 'sectors.sectors-index')->name('sectors.index');
    Volt::route('academic-years', 'academic-years.academic-years-index')->name('academic-years.index');
    Volt::route('program-cycles', 'program-cycles.program-cycles-index')->name('program-cycles.index');
    Volt::route('programs', 'programs.programs-index')->name('programs.index');
    Volt::route('visits', 'visits.visits-index')->name('visits.index');
    Volt::route('work-events', 'work-events.work-events-index')->name('work-events.index');
});
