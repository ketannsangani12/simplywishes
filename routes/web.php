<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\WishController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/about-us', [SiteController::class, 'aboutUs'])->name('about');
Route::get('/happy-stories', [SiteController::class, 'happyStories'])->name('happy.stories');
Route::get('/wishers-granters-donors', [SiteController::class, 'wishersGrantersDonors'])->name('wishers.granters.donors');
Route::get('/privacy-policy', [SiteController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/terms-of-use', [SiteController::class, 'termsOfUse'])->name('terms.of.use');
Route::get('/wishes/create', [WishController::class, 'create'])->name('wishes.create')->middleware('auth');
Route::post('/wishes', [WishController::class, 'store'])->name('wishes.store')->middleware('auth');
Route::get('/wishes/active', [WishController::class, 'active'])->name('wishes.active');
Route::get('/wishes/drafts', [WishController::class, 'drafts'])->name('wishes.drafts')->middleware('auth');
Route::get('/wishes/{wish}/edit', [WishController::class, 'edit'])->name('wishes.edit')->middleware('auth');
Route::put('/wishes/{wish}', [WishController::class, 'update'])->name('wishes.update')->middleware('auth');
Route::delete('/wishes/{wish}', [WishController::class, 'destroy'])->name('wishes.destroy')->middleware('auth');
Route::get('/wishes/{wish}', [WishController::class, 'show'])->name('wishes.show')->middleware('auth');
Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store')->middleware('auth');
Route::delete('/activities', [ActivityController::class, 'destroy'])->name('activities.destroy')->middleware('auth');
Route::get('/donations/create', [DonationController::class, 'create'])->name('donations.create')->middleware('auth');
Route::post('/donations', [DonationController::class, 'store'])->name('donations.store')->middleware('auth');
Route::get('/donations/drafts', [DonationController::class, 'drafts'])->name('donations.drafts')->middleware('auth');
Route::get('/donations/{donation}', [DonationController::class, 'show'])->name('donations.show')->middleware('auth');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    $user = $request->user();
    if ($user) {
        $user->role = $user->role ?: 'User';
        $user->status = $user->status ?: 'Active';
        $user->udid = $user->udid ?: Str::uuid()->toString();
        $user->save();
    }

    return redirect()->route('home')->with('status', 'Email verified.');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'Verification link sent.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.submit');
Route::get('/locations/states/{country}', [AuthController::class, 'statesByCountry'])->name('states.by.country');
Route::get('/locations/cities/{state}', [AuthController::class, 'citiesByState'])->name('cities.by.state');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.forgot');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.forgot.submit');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
