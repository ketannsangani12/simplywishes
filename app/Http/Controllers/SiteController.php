<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        return view('users.user.home');
    }

    public function aboutUs(): View
    {
        return view('users.user.about-us');
    }

    public function wishersGrantersDonors(): View
    {
        return view('users.user.wishers-granters-donors');
    }

    public function happyStories(): View
    {
        return view('users.user.happy-stories');
    }

    public function inbox(): View
    {
        return view('users.user.inbox');
    }

    public function forum(): View
    {
        return view('users.user.forum');
    }

    public function myWishes(): View
    {
        return view('users.user.my-wishes');
    }

    public function addHappyStory(): View
    {
        return view('users.user.add-happy-story');
    }

    public function myFriends(): View
    {
        return view('users.user.my-friends');
    }

    public function updateProfile(): View
    {
        return view('users.user.update-profile');
    }

    public function privacyPolicy(): View
    {
        return view('users.user.privacy-policy');
    }

    public function termsOfUse(): View
    {
        return view('users.user.terms-of-use');
    }
}
