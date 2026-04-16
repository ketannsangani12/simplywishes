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

    public function privacyPolicy(): View
    {
        return view('users.user.privacy-policy');
    }

    public function termsOfUse(): View
    {
        return view('users.user.terms-of-use');
    }
}
