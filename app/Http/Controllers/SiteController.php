<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\City;
use App\Models\Country;
use App\Models\Donation;
use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\Wish;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

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

    public function myWishes(Request $request): View
    {
        $userId = Auth::id();
        $tab = $request->query('tab', 'active');

        $activeWishes = Wish::where('wished_by', $userId)
            ->where('wish_status', 1)
            ->where('wish_progress_status', 0)
            ->orderByDesc('w_id')
            ->get();

        $activeDonations = Donation::where('created_by', $userId)
            ->where('status', 1)
            ->orderByDesc('id')
            ->get();

        $inProgressWishes = Wish::where('wished_by', $userId)
            ->where('wish_status', 1)
            ->where('wish_progress_status', 1)
            ->orderByDesc('w_id')
            ->get();

        $inProgressDonations = Donation::where('created_by', $userId)
            ->where('status', 2)
            ->orderByDesc('id')
            ->get();

        $grantedWishes = Wish::where('wished_by', $userId)
            ->where('wish_status', 1)
            ->where('wish_progress_status', 2)
            ->orderByDesc('w_id')
            ->get();

        $grantedDonations = Donation::where('created_by', $userId)
            ->where('status', 3)
            ->orderByDesc('id')
            ->get();

        $savedWishIds = Activity::where('user_id', $userId)
            ->where('type', 'fav')
            ->whereNotNull('wish_id')
            ->pluck('wish_id');

        $savedDonationIds = Activity::where('user_id', $userId)
            ->where('type', 'fav')
            ->whereNotNull('donation_id')
            ->pluck('donation_id');

        $savedWishes = Wish::whereIn('w_id', $savedWishIds)
            ->orderByDesc('w_id')
            ->get();

        $savedDonations = Donation::whereIn('id', $savedDonationIds)
            ->orderByDesc('id')
            ->get();

        return view('users.user.my-wishes', compact(
            'tab',
            'activeWishes',
            'activeDonations',
            'inProgressWishes',
            'inProgressDonations',
            'grantedWishes',
            'grantedDonations',
            'savedWishes',
            'savedDonations'
        ));
    }

    public function addHappyStory(): View
    {
        return view('users.user.add-happy-story');
    }

    public function myFriends(Request $request): View
    {
        $userId = Auth::id();
        $searchTerm = trim((string) $request->query('q', ''));
        $tab = $request->query('tab', 'friends');
        if (! in_array($tab, ['friends', 'active', 'granted', 'progress'], true)) {
            $tab = 'friends';
        }

        $friendPairs = Friend::where('user_id', $userId)->pluck('friend_id');
        $friendIds = $friendPairs->all();

        $friends = User::whereIn('id', $friendIds)
            ->orderByRaw('COALESCE(NULLIF(first_name, ""), NULLIF(name, ""), email) ASC')
            ->get();

        $incomingRequests = FriendRequest::where('receiver_id', $userId)
            ->where('status', 0)
            ->orderByDesc('created_at')
            ->get();

        $outgoingRequests = FriendRequest::where('sender_id', $userId)
            ->where('status', 0)
            ->orderByDesc('created_at')
            ->get();

        $relatedUserIds = collect($friendIds)
            ->merge($incomingRequests->pluck('sender_id'))
            ->merge($outgoingRequests->pluck('receiver_id'))
            ->filter()
            ->unique()
            ->values();

        $relatedUsers = User::whereIn('id', $relatedUserIds)->get()->keyBy('id');

        $friendWishBase = Wish::whereIn('wished_by', $friendIds)
            ->where('wish_status', 1);

        $friendDonationBase = Donation::whereIn('created_by', $friendIds);

        $activeFriendWishes = (clone $friendWishBase)
            ->where('wish_progress_status', 0)
            ->orderByDesc('w_id')
            ->get();

        $inProgressFriendWishes = (clone $friendWishBase)
            ->where('wish_progress_status', 1)
            ->orderByDesc('w_id')
            ->get();

        $grantedFriendWishes = (clone $friendWishBase)
            ->where('wish_progress_status', 2)
            ->orderByDesc('w_id')
            ->get();

        $activeFriendDonations = (clone $friendDonationBase)
            ->where('status', 1)
            ->orderByDesc('id')
            ->get();

        $inProgressFriendDonations = (clone $friendDonationBase)
            ->where('status', 2)
            ->orderByDesc('id')
            ->get();

        $grantedFriendDonations = (clone $friendDonationBase)
            ->where('status', 3)
            ->orderByDesc('id')
            ->get();

        $tabs = [
            'friends' => 'Friends',
            'active' => 'Active Wishes & Donations',
            'granted' => 'Granted Wishes & Donations',
            'progress' => 'In Progress Wishes & Donations',
        ];

        $friendItemsByTab = [
            'active' => [
                'wishes' => $activeFriendWishes,
                'donations' => $activeFriendDonations,
            ],
            'granted' => [
                'wishes' => $grantedFriendWishes,
                'donations' => $grantedFriendDonations,
            ],
            'progress' => [
                'wishes' => $inProgressFriendWishes,
                'donations' => $inProgressFriendDonations,
            ],
        ];

        $currentFeedItems = $friendItemsByTab[$tab] ?? [];

        $searchResults = collect();

        if ($searchTerm !== '') {
            $searchResults = User::query()
                ->where('id', '!=', $userId)
                ->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('first_name', 'like', "%{$searchTerm}%")
                        ->orWhere('last_name', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%")
                        ->orWhere('username', 'like', "%{$searchTerm}%");
                })
                ->orderByRaw('COALESCE(NULLIF(first_name, ""), NULLIF(name, ""), email) ASC')
                ->limit(24)
                ->get();
        }

        return view('users.user.my-friends', compact(
            'searchTerm',
            'searchResults',
            'friends',
            'incomingRequests',
            'outgoingRequests',
            'friendIds',
            'relatedUsers',
            'tab',
            'tabs',
            'friendItemsByTab',
            'currentFeedItems'
        ));
    }

    public function updateProfile(): View
    {
        $user = Auth::user();
        $countries = Country::query()->orderBy('name')->get(['id', 'name']);
        $selectedCountry = $user?->country ? Country::where('name', $user->country)->first() : null;
        $selectedState = $user?->state ? State::where('name', $user->state)->first() : null;
        $selectedCity = $user?->city ? City::where('name', $user->city)->first() : null;

        $states = $selectedCountry
            ? State::where('country_id', $selectedCountry->id)->orderBy('name')->get(['id', 'name'])
            : collect();

        $cities = $selectedState
            ? City::where('state_id', $selectedState->id)->orderBy('name')->get(['id', 'name'])
            : collect();

        return view('users.user.update-profile', compact(
            'user',
            'countries',
            'states',
            'cities',
            'selectedCountry',
            'selectedState',
            'selectedCity'
        ));
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
