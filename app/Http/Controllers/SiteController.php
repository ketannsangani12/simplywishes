<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\City;
use App\Models\Country;
use App\Models\Donation;
use App\Models\HappyStory;
use App\Models\HappyStoryComment;
use App\Models\HappyStoryCommentLike;
use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\Wish;
use App\Models\State;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Models\User;

class SiteController extends Controller
{
    public function home(): View
    {
        $currentWishes = Wish::with('creator')
            ->where('wish_status', 1)
            ->where('wish_progress_status', 0)
            ->orderByDesc('w_id')
            ->limit(3)
            ->get();

        $currentDonations = Donation::with('creator')
            ->where('status', 1)
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $grantedWishItems = Wish::with('creator')
            ->where('wish_status', 1)
            ->where('wish_progress_status', 2)
            ->orderByDesc('granted_date')
            ->limit(3)
            ->get()
            ->map(function ($wish) {
                $wish->section_type = 'wish';
                return $wish;
            });

        $grantedDonationItems = Donation::with('creator')
            ->where('status', 3)
            ->orderByDesc('completed_at')
            ->limit(3)
            ->get()
            ->map(function ($donation) {
                $donation->section_type = 'donation';
                return $donation;
            });

        $grantedItems = $grantedWishItems
            ->concat($grantedDonationItems)
            ->sortByDesc(function ($item) {
                return $item->granted_date ?? $item->completed_at ?? $item->created_at ?? null;
            })
            ->take(3)
            ->values();

        $happyStories = HappyStory::with(['user', 'wish'])
            ->where('status', 1)
            ->orderByDesc('hs_id')
            ->limit(3)
            ->get();

        $forumPosts = \App\Models\ForumPost::with('creator')
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        return view('users.user.home', compact(
            'currentWishes',
            'currentDonations',
            'grantedItems',
            'happyStories',
            'forumPosts'
        ));
    }

    public function aboutUs(): View
    {
        return view('users.user.about-us');
    }

    public function wishersGrantersDonors(Request $request): View
    {
        $searchTerm = trim((string) $request->query('q', ''));
        $tab = (string) $request->query('tab', 'wishers');

        if (! in_array($tab, ['wishers', 'granters', 'donors'], true)) {
            $tab = 'wishers';
        }

        $userBaseQuery = function () use ($searchTerm) {
            return User::query()
                ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                    $query->where(function ($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'like', "%{$searchTerm}%")
                            ->orWhere('first_name', 'like', "%{$searchTerm}%")
                            ->orWhere('last_name', 'like', "%{$searchTerm}%")
                            ->orWhereRaw("CONCAT_WS(' ', first_name, last_name) like ?", ["%{$searchTerm}%"]);
                    });
                });
        };

        $wisherCounts = DB::table('wishes')
            ->select('wished_by as user_id', DB::raw('COUNT(*) as items_count'))
            ->groupBy('wished_by');

        $granterCounts = DB::table('wishes')
            ->select('granted_by as user_id', DB::raw('COUNT(*) as items_count'))
            ->whereNotNull('granted_by')
            ->where('granted_by', '!=', '')
            ->groupBy('granted_by');

        $donorCounts = DB::table('donations')
            ->select('created_by as user_id', DB::raw('COUNT(*) as items_count'))
            ->groupBy('created_by');

        $wishers = (clone $userBaseQuery())
            ->joinSub($wisherCounts, 'wish_counts', function ($join) {
                $join->on('users.id', '=', 'wish_counts.user_id');
            })
            ->select('users.*', 'wish_counts.items_count')
            ->orderByDesc('wish_counts.items_count')
            ->orderByRaw('COALESCE(NULLIF(first_name, ""), NULLIF(name, ""), last_name) ASC')
            ->get();

        $granters = (clone $userBaseQuery())
            ->joinSub($granterCounts, 'grant_counts', function ($join) {
                $join->on('users.id', '=', 'grant_counts.user_id');
            })
            ->select('users.*', 'grant_counts.items_count')
            ->orderByDesc('grant_counts.items_count')
            ->orderByRaw('COALESCE(NULLIF(first_name, ""), NULLIF(name, ""), last_name) ASC')
            ->get();

        $donors = (clone $userBaseQuery())
            ->joinSub($donorCounts, 'donor_counts', function ($join) {
                $join->on('users.id', '=', 'donor_counts.user_id');
            })
            ->select('users.*', 'donor_counts.items_count')
            ->orderByDesc('donor_counts.items_count')
            ->orderByRaw('COALESCE(NULLIF(first_name, ""), NULLIF(name, ""), last_name) ASC')
            ->get();

        return view('users.user.wishers-granters-donors', compact('searchTerm', 'tab', 'wishers', 'granters', 'donors'));
    }

    public function memberProfile(int $user): View
    {
        $viewerId = (int) Auth::id();
        $member = User::with('presence')->findOrFail($user);

        $name = trim(implode(' ', array_filter([$member->first_name ?? null, $member->last_name ?? null])));
        $name = $name !== '' ? $name : ($member->name ?: 'Member');

        $avatar = $member->profile_image
            ? (filter_var($member->profile_image, FILTER_VALIDATE_URL) ? $member->profile_image : asset($member->profile_image))
            : 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=E2E8F0&color=0F172A';

        $location = trim(implode(', ', array_filter([$member->city ?? null, $member->state ?? null, $member->country ?? null])));

        $stats = [
            'wishes' => Wish::where('wished_by', $member->id)->where('wish_status', 1)->count(),
            'granted' => Wish::where('granted_by', $member->id)->whereNotNull('granted_by')->where('granted_by', '!=', '')->count(),
            'donations' => Donation::where('created_by', $member->id)->where('status', 1)->count(),
            'stories' => HappyStory::where('user_id', $member->id)->count(),
            'friends' => Friend::where('user_id', $member->id)->count(),
        ];

        $isSelf = $viewerId === (int) $member->id;
        $isFriend = ! $isSelf && Friend::where('user_id', $viewerId)->where('friend_id', $member->id)->exists();
        $requestSent = ! $isSelf && FriendRequest::where('sender_id', $viewerId)->where('receiver_id', $member->id)->where('status', 0)->exists();
        $requestReceived = ! $isSelf && FriendRequest::where('sender_id', $member->id)->where('receiver_id', $viewerId)->where('status', 0)->exists();

        return view('users.user.member-profile', [
            'member' => $member,
            'name' => $name,
            'avatar' => $avatar,
            'location' => $location,
            'isOnline' => $member->presence?->isOnline() ?? false,
            'stats' => $stats,
            'isSelf' => $isSelf,
            'isFriend' => $isFriend,
            'requestSent' => $requestSent,
            'requestReceived' => $requestReceived,
        ]);
    }

    public function happyStories(): View
    {
        $searchTerm = trim((string) request()->query('q', ''));

        $storiesQuery = HappyStory::with(['user', 'wish'])
            ->where('status', 1);

        if ($searchTerm !== '') {
            $storiesQuery->where(function ($query) use ($searchTerm) {
                $query->where('story_text', 'like', "%{$searchTerm}%")
                    ->orWhereHas('wish', function ($wishQuery) use ($searchTerm) {
                        $wishQuery->where('wish_title', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'like', "%{$searchTerm}%")
                            ->orWhere('first_name', 'like', "%{$searchTerm}%")
                            ->orWhere('last_name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        $stories = $storiesQuery
            ->orderByDesc('hs_id')
            ->get();

        return view('users.user.happy-stories', compact('stories', 'searchTerm'));
    }

    public function happyStory(int $story): View
    {
        $userId = Auth::id();

        $story = HappyStory::with(['user', 'wish'])
            ->where('status', 1)
            ->where('hs_id', $story)
            ->firstOrFail();

        $reportedCommentIds = DB::table('reports')
            ->where('reporter_id', $userId)
            ->where('reportable_type', 'comment')
            ->where(function ($query) use ($userId) {
                $query->whereNull('reported_user_id')
                    ->orWhere('reported_user_id', '!=', $userId);
            })
            ->pluck('reportable_id')
            ->all();

        $reportedCommentUserIds = DB::table('reports')
            ->where('reporter_id', $userId)
            ->where('reportable_type', 'comment')
            ->whereNotNull('reported_user_id')
            ->where('reported_user_id', '!=', $userId)
            ->pluck('reported_user_id')
            ->unique()
            ->all();

        $comments = HappyStoryComment::with([
                'user',
                'replies' => fn ($query) => $query
                    ->when($reportedCommentIds !== [], fn ($replyQuery) => $replyQuery->whereNotIn('id', $reportedCommentIds))
                    ->when($reportedCommentUserIds !== [], fn ($replyQuery) => $replyQuery->whereNotIn('user_id', $reportedCommentUserIds))
                    ->with('user')
                    ->withCount('likes'),
            ])
            ->withCount('likes')
            ->where('happy_story_id', $story->hs_id)
            ->whereNull('parent_id')
            ->when($reportedCommentIds !== [], fn ($query) => $query->whereNotIn('id', $reportedCommentIds))
            ->when($reportedCommentUserIds !== [], fn ($query) => $query->whereNotIn('user_id', $reportedCommentUserIds))
            ->latest()
            ->get();

        $hasReportedStory = DB::table('reports')
            ->where('reporter_id', $userId)
            ->where('reportable_type', 'happy_story')
            ->where('reportable_id', $story->hs_id)
            ->exists();

        $likedStoryIds = Activity::where('user_id', $userId)
            ->where('type', 'like')
            ->where('happy_story_id', $story->hs_id)
            ->pluck('happy_story_id')
            ->all();

        $storyLikeCount = Activity::where('type', 'like')
            ->where('happy_story_id', $story->hs_id)
            ->count();

        $commentIds = $comments->pluck('id')
            ->merge($comments->flatMap(fn ($comment) => $comment->replies->pluck('id')))
            ->unique()
            ->values();

        $likedCommentIds = HappyStoryCommentLike::where('user_id', $userId)
            ->whereIn('comment_id', $commentIds)
            ->pluck('comment_id')
            ->all();

        return view('users.user.happy-story-details', compact('story', 'comments', 'likedCommentIds', 'hasReportedStory', 'likedStoryIds', 'storyLikeCount'));
    }

    public function reportHappyStory(Request $request, int $story): RedirectResponse
    {
        $story = HappyStory::where('hs_id', $story)
            ->where('status', 1)
            ->firstOrFail();

        if ((int) $story->user_id === (int) Auth::id()) {
            return redirect()
                ->route('happy.stories.show', $story->hs_id)
                ->with('status', 'You cannot report your own happy story.');
        }

        DB::table('reports')->updateOrInsert(
            [
                'reporter_id' => Auth::id(),
                'reportable_type' => 'happy_story',
                'reportable_id' => $story->hs_id,
            ],
            [
                'reported_user_id' => $story->user_id,
                'reason' => 'Happy Story',
                'details' => $request->input('details', 'Happy story reported from happy story details.'),
                'status' => 0,
                'created_at' => now(),
            ]
        );

        return redirect()
            ->route('happy.stories.show', $story->hs_id)
            ->with('status', 'Happy story reported successfully.');
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
        $wishes = Wish::where('wished_by', Auth::id())
            ->whereNotNull('granted_by')
            ->where('granted_by', '!=', '')
            ->orderByDesc('w_id')
            ->get();

        return view('users.user.add-happy-story', compact('wishes'));
    }

    public function storeHappyStory(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'wish_id' => ['required', 'integer', 'exists:wishes,w_id'],
            'story_text' => ['required', 'string', 'max:5000'],
            'story_image_upload' => ['nullable', 'image', 'max:5120'],
            'story_image_default' => ['nullable', 'string', 'max:500'],
        ])->after(function ($validator) use ($request) {
            $hasUpload = $request->hasFile('story_image_upload');
            $hasDefault = $request->filled('story_image_default');

            if (($hasUpload && $hasDefault) || (! $hasUpload && ! $hasDefault)) {
                $validator->errors()->add(
                    'story_image_choice',
                    'Please upload an image or choose one from the list.'
                );
            }
        })->validate();

        $wish = Wish::where('w_id', $validated['wish_id'])
            ->where('wished_by', Auth::id())
            ->whereNotNull('granted_by')
            ->where('granted_by', '!=', '')
            ->firstOrFail();

        $storyImage = null;
        if ($request->hasFile('story_image_upload')) {
            $file = $request->file('story_image_upload');
            $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
            $fileName = Str::uuid()->toString() . '.' . $extension;

            $candidateDirectories = [
                public_path('uploads/happy-stories'),
                base_path('../public_html/uploads/happy-stories'),
            ];

            $uploadDirectory = null;
            foreach ($candidateDirectories as $directory) {
                $parentDirectory = dirname($directory);
                if (is_dir($directory) || is_dir($parentDirectory)) {
                    $uploadDirectory = $directory;
                    break;
                }
            }

            $uploadDirectory ??= public_path('uploads/happy-stories');

            File::ensureDirectoryExists($uploadDirectory);
            $file->move($uploadDirectory, $fileName);

            $storyImage = 'uploads/happy-stories/' . $fileName;
        } elseif ($request->filled('story_image_default')) {
            $storyImage = $request->input('story_image_default');
        }

        HappyStory::create([
            'user_id' => Auth::id(),
            'wish_id' => $wish?->w_id,
            'story_text' => trim($validated['story_text']),
            'story_image' => $storyImage,
            'status' => 1,
            'created_at' => now(),
        ]);

        return redirect()
            ->route('happy.stories')
            ->with('status', 'Your happy story has been created successfully.');
    }

    public function storeHappyStoryComment(Request $request, int $story): RedirectResponse
    {
        $story = HappyStory::where('hs_id', $story)
            ->where('status', 1)
            ->firstOrFail();

        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer'],
        ]);

        $parentId = $validated['parent_id'] ?? null;
        if ($parentId) {
            HappyStoryComment::where('id', $parentId)
                ->where('happy_story_id', $story->hs_id)
                ->firstOrFail();
        }

        HappyStoryComment::create([
            'happy_story_id' => $story->hs_id,
            'user_id' => Auth::id(),
            'parent_id' => $parentId,
            'comment' => trim($validated['comment']),
        ]);

        return redirect()
            ->route('happy.stories.show', $story->hs_id)
            ->with('status', 'Comment posted successfully.');
    }

    public function updateHappyStoryComment(Request $request, int $story, int $comment): RedirectResponse
    {
        $story = HappyStory::where('hs_id', $story)
            ->where('status', 1)
            ->firstOrFail();

        $comment = HappyStoryComment::where('id', $comment)
            ->where('happy_story_id', $story->hs_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        $comment->update([
            'comment' => trim($validated['comment']),
        ]);

        return redirect()
            ->route('happy.stories.show', $story->hs_id)
            ->with('status', 'Comment updated successfully.');
    }

    public function destroyHappyStoryComment(int $story, int $comment): RedirectResponse
    {
        $story = HappyStory::where('hs_id', $story)
            ->where('status', 1)
            ->firstOrFail();

        $comment = HappyStoryComment::where('id', $comment)
            ->where('happy_story_id', $story->hs_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $commentIds = HappyStoryComment::where('id', $comment->id)
            ->orWhere('parent_id', $comment->id)
            ->pluck('id');

        HappyStoryCommentLike::whereIn('comment_id', $commentIds)->delete();
        HappyStoryComment::whereIn('id', $commentIds)->delete();

        return redirect()
            ->route('happy.stories.show', $story->hs_id)
            ->with('status', 'Comment deleted successfully.');
    }

    public function toggleHappyStoryCommentLike(int $story, int $comment): RedirectResponse
    {
        $story = HappyStory::where('hs_id', $story)
            ->where('status', 1)
            ->firstOrFail();

        $comment = HappyStoryComment::where('id', $comment)
            ->where('happy_story_id', $story->hs_id)
            ->firstOrFail();

        $like = HappyStoryCommentLike::where('comment_id', $comment->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($like) {
            $like->delete();
        } else {
            HappyStoryCommentLike::create([
                'comment_id' => $comment->id,
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);
        }

        return redirect()->route('happy.stories.show', $story->hs_id);
    }

    public function reportHappyStoryComment(Request $request, int $story, int $comment): RedirectResponse
    {
        $story = HappyStory::where('hs_id', $story)
            ->where('status', 1)
            ->firstOrFail();

        $comment = HappyStoryComment::with('user')
            ->where('id', $comment)
            ->where('happy_story_id', $story->hs_id)
            ->firstOrFail();

        if ((int) $comment->user_id === (int) Auth::id()) {
            return redirect()
                ->route('happy.stories.show', $story->hs_id)
                ->with('status', 'You cannot report your own comment.');
        }

        DB::table('reports')->updateOrInsert(
            [
                'reporter_id' => Auth::id(),
                'reportable_type' => 'comment',
                'reportable_id' => $comment->id,
            ],
            [
                'reported_user_id' => $comment->user_id,
                'reason' => 'Comment',
                'details' => $request->input('details', 'Comment reported from happy story details.'),
                'status' => 0,
                'created_at' => now(),
            ]
        );

        return redirect()
            ->route('happy.stories.show', $story->hs_id)
            ->with('status', 'Comment reported successfully.');
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
