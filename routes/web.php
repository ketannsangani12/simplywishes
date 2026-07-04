<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\FriendController;
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
Route::get('/happy-stories/{story}', [SiteController::class, 'happyStory'])->name('happy.stories.show')->middleware('auth')->whereNumber('story');
Route::post('/happy-stories/{story}/report', [SiteController::class, 'reportHappyStory'])->name('happy.stories.report')->middleware('auth')->whereNumber('story');
Route::get('/wishers-granters-donors', [SiteController::class, 'wishersGrantersDonors'])->name('wishers.granters.donors');
Route::get('/privacy-policy', [SiteController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/terms-of-use', [SiteController::class, 'termsOfUse'])->name('terms.of.use');
Route::get('/inbox', [SiteController::class, 'inbox'])->name('inbox')->middleware('auth');
Route::get('/forum', [ForumController::class, 'index'])->name('forum')->middleware('auth');
Route::post('/forum', [ForumController::class, 'store'])->name('forum.store')->middleware('auth');
Route::get('/forum/{forum}', [ForumController::class, 'show'])->name('forum.show')->middleware('auth');
Route::post('/forum/{forum}/like', [ForumController::class, 'toggleLike'])->name('forum.like')->middleware('auth');
Route::post('/forum/{forum}/report', [ForumController::class, 'report'])->name('forum.report')->middleware('auth');
Route::post('/forum/{forum}/comments', [ForumController::class, 'storeComment'])->name('forum.comments.store')->middleware('auth');
Route::put('/forum/{forum}/comments/{comment}', [ForumController::class, 'updateComment'])->name('forum.comments.update')->middleware('auth');
Route::delete('/forum/{forum}/comments/{comment}', [ForumController::class, 'destroyComment'])->name('forum.comments.destroy')->middleware('auth');
Route::post('/forum/{forum}/comments/{comment}/like', [ForumController::class, 'toggleCommentLike'])->name('forum.comments.like')->middleware('auth');
Route::post('/forum/{forum}/comments/{comment}/report', [ForumController::class, 'reportComment'])->name('forum.comments.report')->middleware('auth');
Route::get('/my-wishes', [SiteController::class, 'myWishes'])->name('my.wishes')->middleware('auth');
Route::get('/happy-stories/create', [SiteController::class, 'addHappyStory'])->name('happy.stories.create')->middleware('auth');
Route::post('/happy-stories', [SiteController::class, 'storeHappyStory'])->name('happy.stories.store')->middleware('auth');
Route::post('/happy-stories/{story}/comments', [SiteController::class, 'storeHappyStoryComment'])->name('happy.stories.comments.store')->middleware('auth')->whereNumber('story');
Route::put('/happy-stories/{story}/comments/{comment}', [SiteController::class, 'updateHappyStoryComment'])->name('happy.stories.comments.update')->middleware('auth')->whereNumber('story')->whereNumber('comment');
Route::delete('/happy-stories/{story}/comments/{comment}', [SiteController::class, 'destroyHappyStoryComment'])->name('happy.stories.comments.destroy')->middleware('auth')->whereNumber('story')->whereNumber('comment');
Route::post('/happy-stories/{story}/comments/{comment}/like', [SiteController::class, 'toggleHappyStoryCommentLike'])->name('happy.stories.comments.like')->middleware('auth')->whereNumber('story')->whereNumber('comment');
Route::post('/happy-stories/{story}/comments/{comment}/report', [SiteController::class, 'reportHappyStoryComment'])->name('happy.stories.comments.report')->middleware('auth')->whereNumber('story')->whereNumber('comment');
Route::get('/my-friends', [SiteController::class, 'myFriends'])->name('my.friends')->middleware('auth');
Route::post('/friends/{user}/request', [FriendController::class, 'sendRequest'])->name('friends.requests.send')->middleware('auth');
Route::post('/friends/requests/{request}/accept', [FriendController::class, 'accept'])->name('friends.requests.accept')->middleware('auth');
Route::post('/friends/requests/{request}/reject', [FriendController::class, 'reject'])->name('friends.requests.reject')->middleware('auth');
Route::delete('/friends/{user}', [FriendController::class, 'unfriend'])->name('friends.unfriend')->middleware('auth');
Route::get('/my-profile', [SiteController::class, 'updateProfile'])->name('profile.edit')->middleware('auth');
Route::put('/my-profile', [AuthController::class, 'updateProfile'])->name('profile.update')->middleware('auth');
Route::put('/my-profile/password', [AuthController::class, 'updatePassword'])->name('profile.password.update')->middleware('auth');
Route::get('/wishes/create', [WishController::class, 'create'])->name('wishes.create')->middleware('auth');
Route::post('/wishes', [WishController::class, 'store'])->name('wishes.store')->middleware('auth');
Route::get('/wishes/active', [WishController::class, 'active'])->name('wishes.active');
Route::get('/wishes/drafts', [WishController::class, 'drafts'])->name('wishes.drafts')->middleware('auth');
Route::get('/wishes/{wish}/edit', [WishController::class, 'edit'])->name('wishes.edit')->middleware('auth');
Route::put('/wishes/{wish}', [WishController::class, 'update'])->name('wishes.update')->middleware('auth');
Route::delete('/wishes/{wish}', [WishController::class, 'destroy'])->name('wishes.destroy')->middleware('auth');
Route::post('/wishes/{wish}/grant', [WishController::class, 'grant'])->name('wishes.grant')->middleware('auth');
Route::post('/wishes/{wish}/fulfill', [WishController::class, 'fulfill'])->name('wishes.fulfill')->middleware('auth');
Route::post('/wishes/{wish}/report', [WishController::class, 'report'])->name('wishes.report')->middleware('auth');
Route::post('/wishes/{wish}/comments', [WishController::class, 'storeComment'])->name('wishes.comments.store')->middleware('auth');
Route::put('/wishes/{wish}/comments/{comment}', [WishController::class, 'updateComment'])->name('wishes.comments.update')->middleware('auth');
Route::delete('/wishes/{wish}/comments/{comment}', [WishController::class, 'destroyComment'])->name('wishes.comments.destroy')->middleware('auth');
Route::post('/wishes/{wish}/comments/{comment}/like', [WishController::class, 'toggleCommentLike'])->name('wishes.comments.like')->middleware('auth');
Route::post('/wishes/{wish}/comments/{comment}/report', [WishController::class, 'reportComment'])->name('wishes.comments.report')->middleware('auth');
Route::get('/wishes/{wish}', [WishController::class, 'show'])->name('wishes.show')->middleware('auth');
Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store')->middleware('auth');
Route::delete('/activities', [ActivityController::class, 'destroy'])->name('activities.destroy')->middleware('auth');
Route::get('/donations/create', [DonationController::class, 'create'])->name('donations.create')->middleware('auth');
Route::post('/donations', [DonationController::class, 'store'])->name('donations.store')->middleware('auth');
Route::get('/donations/drafts', [DonationController::class, 'drafts'])->name('donations.drafts')->middleware('auth');
Route::get('/donations/{donation}/edit', [DonationController::class, 'edit'])->name('donations.edit')->middleware('auth');
Route::put('/donations/{donation}', [DonationController::class, 'update'])->name('donations.update')->middleware('auth');
Route::delete('/donations/{donation}', [DonationController::class, 'destroy'])->name('donations.destroy')->middleware('auth');
Route::post('/donations/{donation}/accept', [DonationController::class, 'accept'])->name('donations.accept')->middleware('auth');
Route::post('/donations/{donation}/complete', [DonationController::class, 'complete'])->name('donations.complete')->middleware('auth');
Route::post('/donations/{donation}/report', [DonationController::class, 'report'])->name('donations.report')->middleware('auth');
Route::post('/donations/{donation}/comments', [DonationController::class, 'storeComment'])->name('donations.comments.store')->middleware('auth');
Route::put('/donations/{donation}/comments/{comment}', [DonationController::class, 'updateComment'])->name('donations.comments.update')->middleware('auth');
Route::delete('/donations/{donation}/comments/{comment}', [DonationController::class, 'destroyComment'])->name('donations.comments.destroy')->middleware('auth');
Route::post('/donations/{donation}/comments/{comment}/like', [DonationController::class, 'toggleCommentLike'])->name('donations.comments.like')->middleware('auth');
Route::post('/donations/{donation}/comments/{comment}/report', [DonationController::class, 'reportComment'])->name('donations.comments.report')->middleware('auth');
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
