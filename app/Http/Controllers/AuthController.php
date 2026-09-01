<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('users.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            if (Auth::user()->isDeleted()) {
                Auth::logout();

                return back()
                    ->withErrors(['email' => 'The provided credentials do not match our records.'])
                    ->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->route('home')->with('status', 'Logged in successfully.');
        }

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Logged out successfully.');
    }

    public function showSignup(): View
    {
        $countries = Country::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        // Re-hydrate the dependent State/City dropdowns from the previously
        // submitted (and now flashed) country/state, so a failed signup
        // (e.g. "email already registered") redisplays the user's selection
        // instead of resetting the location fields to empty/disabled.
        $states = collect();
        $cities = collect();

        if (old('country')) {
            $states = State::query()
                ->where('country_id', old('country'))
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        if (old('state')) {
            $cities = City::query()
                ->where('state_id', old('state'))
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        return view('users.signup', compact('countries', 'states', 'cities'));
    }

    public function signup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first-name' => ['required', 'string', 'max:255'],
            'last-name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'country' => ['required', 'integer', 'exists:countries,id'],
            'state' => ['required', 'integer', 'exists:states,id'],
            'city' => ['required', 'integer', 'exists:cities,id'],
            'password' => ['required', 'min:6'],
            'confirm' => ['required', 'same:password'],
            'avatar' => ['nullable', 'image', 'max:10240'],
        ]);

        $country = Country::find($validated['country']);
        $state = State::find($validated['state']);
        $city = City::find($validated['city']);

        $profileImage = null;
        if ($request->hasFile('avatar')) {
            $extension = strtolower($request->file('avatar')->getClientOriginalExtension() ?: $request->file('avatar')->extension() ?: 'jpg');
            $fileName = Str::uuid()->toString() . '.' . $extension;

            $candidateDirectories = [
                base_path('../public_html/uploads/users'),
                public_path('uploads/users'),
            ];

            $uploadDirectory = null;
            foreach ($candidateDirectories as $directory) {
                $parentDirectory = dirname($directory);
                if (is_dir($directory) || is_dir($parentDirectory)) {
                    $uploadDirectory = $directory;
                    break;
                }
            }

            $uploadDirectory ??= public_path('uploads/users');

            File::ensureDirectoryExists($uploadDirectory);
            $request->file('avatar')->move($uploadDirectory, $fileName);

            $profileImage = 'uploads/users/' . $fileName;
        } elseif ($request->filled('avatar-default')) {
            $profileImage = 'images/users-default/' . $request->input('avatar-default');
        }

        $user = User::create([
            'name' => trim($validated['first-name'] . ' ' . $validated['last-name']),
            'first_name' => $validated['first-name'],
            'last_name' => $validated['last-name'],
            'email' => $validated['email'],
            'about' => $request->input('about'),
            'country' => $country?->name,
            'state' => $state?->name,
            'city' => $city?->name,
            'profile_image' => $profileImage,
            'password' => Hash::make($validated['password']),
        ]);

        $user->sendEmailVerificationNotification();
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('verification.notice')->with('status', 'Verification link sent to your email.');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'first-name' => ['required', 'string', 'max:255'],
            'last-name' => ['required', 'string', 'max:255'],
            'about' => ['nullable', 'string'],
            'country' => ['required', 'integer', 'exists:countries,id'],
            'state' => ['required', 'integer', 'exists:states,id'],
            'city' => ['required', 'integer', 'exists:cities,id'],
            'avatar' => ['nullable', 'image', 'max:10240'],
            'avatar-default' => ['nullable', 'string'],
            'remove_avatar' => ['nullable', 'boolean'],
        ]);

        $country = Country::find($validated['country']);
        $state = State::find($validated['state']);
        $city = City::find($validated['city']);

        $profileImage = $user->profile_image;

        if ($request->boolean('remove_avatar')) {
            $profileImage = null;
        }

        if ($request->hasFile('avatar')) {
            $extension = strtolower($request->file('avatar')->getClientOriginalExtension() ?: $request->file('avatar')->extension() ?: 'jpg');
            $fileName = Str::uuid()->toString() . '.' . $extension;

            $candidateDirectories = [
                base_path('../public_html/uploads/users'),
                public_path('uploads/users'),
            ];

            $uploadDirectory = null;
            foreach ($candidateDirectories as $directory) {
                $parentDirectory = dirname($directory);
                if (is_dir($directory) || is_dir($parentDirectory)) {
                    $uploadDirectory = $directory;
                    break;
                }
            }

            $uploadDirectory ??= public_path('uploads/users');

            File::ensureDirectoryExists($uploadDirectory);
            $request->file('avatar')->move($uploadDirectory, $fileName);

            $profileImage = 'uploads/users/' . $fileName;
        } elseif ($request->filled('avatar-default')) {
            $profileImage = 'images/users-default/' . $request->input('avatar-default');
        }

        $user->fill([
            'name' => trim($validated['first-name'] . ' ' . $validated['last-name']),
            'first_name' => $validated['first-name'],
            'last_name' => $validated['last-name'],
            'email' => $validated['email'],
            'about' => $validated['about'] ?? null,
            'country' => $country?->name,
            'state' => $state?->name,
            'city' => $city?->name,
            'profile_image' => $profileImage,
        ]);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6'],
            'confirm' => ['required', 'same:password'],
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Password updated successfully.');
    }

    public function deleteAccount(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'delete_password' => ['required', 'string'],
        ], [
            'delete_password.required' => 'Please enter your password to confirm account deletion.',
        ]);

        if (! Hash::check($request->input('delete_password'), $user->password)) {
            return back()
                ->withErrors(['delete_password' => 'The password you entered is incorrect.'])
                ->with('open_delete_account', true);
        }

        // Remove the physical file only for a user-uploaded avatar. Default
        // avatars live under images/users-default/ and are shared assets.
        if ($user->profile_image && str_starts_with($user->profile_image, 'uploads/users/')) {
            $path = public_path($user->profile_image);
            if (is_file($path)) {
                File::delete($path);
            }
        }

        // Deactivate & anonymize rather than hard-delete: wishes, donations,
        // forum posts, chat messages, friend requests, etc. all reference
        // the user by a plain integer column with no DB foreign key, so a
        // hard delete would leave every one of those orphaned instead of
        // cleanly removed. This scrubs personal info, frees the email for
        // reuse, and blocks login (see login()), while their historical
        // content stays intact and shows as "Deleted User" to everyone else.
        $user->forceFill([
            'name' => 'Deleted User',
            'first_name' => 'Deleted',
            'last_name' => 'User',
            'username' => null,
            'email' => 'deleted-user-' . $user->id . '-' . Str::random(10) . '@deleted.simplywishes.invalid',
            'fb_id' => null,
            'google_id' => null,
            'about' => null,
            'country' => null,
            'state' => null,
            'city' => null,
            'profile_image' => null,
            'email_verified_at' => null,
            'password' => Hash::make(Str::random(40)),
            'remember_token' => null,
            'deleted_at' => now(),
        ])->save();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('status', 'Your account has been deleted. We\'re sorry to see you go.');
    }

    public function statesByCountry(int $country): JsonResponse
    {
        $states = State::query()
            ->where('country_id', $country)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($states);
    }

    public function citiesByState(int $state): JsonResponse
    {
        $cities = City::query()
            ->where('state_id', $state)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }

    public function showForgotPassword(): View
    {
        return view('users.forgot-password');
    }

    public function forgotPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'reset-email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink([
            'email' => $request->input('reset-email'),
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return redirect()
                ->route('password.forgot')
                ->with('status', __($status));
        }

        return back()
            ->withErrors(['reset-email' => __($status)])
            ->onlyInput('reset-email');
    }

    public function showResetPassword(Request $request, string $token): View
    {
        return view('users.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('status', __($status));
        }

        return back()
            ->withErrors(['email' => __($status)])
            ->onlyInput('email');
    }

    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $e) {
            // Fallback for local dev if session state is lost.
            $googleUser = Socialite::driver('google')->stateless()->user();
        }

        $email = $googleUser->getEmail();
        $name = $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User';
        $firstName = $googleUser->user['given_name'] ?? null;
        $lastName = $googleUser->user['family_name'] ?? null;

        // Excludes deleted accounts deliberately: their email was replaced
        // with a placeholder on deletion (see deleteAccount()), so a real
        // match here should never happen — this is defense in depth against
        // ever reactivating a deleted account instead of creating a fresh one.
        $user = User::where('email', $email)->whereNull('deleted_at')->first();
        if (!$user) {
            $user = new User();
            $user->email = $email;
            $user->password = Hash::make(Str::random(32));
        }

        $user->name = $name;
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->google_id = $googleUser->getId();
        $user->profile_image = $googleUser->getAvatar();
        $user->email_verified_at = $user->email_verified_at ?? now();
        $user->role = $user->role ?: 'User';
        $user->status = $user->status ?: 'Active';
        $user->udid = $user->udid ?: Str::uuid()->toString();
        $user->save();

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('home')->with('status', 'Logged in with Google.');
    }
}
