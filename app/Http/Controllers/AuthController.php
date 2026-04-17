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

        return view('users.signup', compact('countries'));
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
            'avatar' => ['nullable', 'image', 'max:5120'],
        ]);

        $country = Country::find($validated['country']);
        $state = State::find($validated['state']);
        $city = City::find($validated['city']);

        $profileImage = null;
        if ($request->hasFile('avatar')) {
            $extension = strtolower($request->file('avatar')->getClientOriginalExtension() ?: $request->file('avatar')->extension() ?: 'jpg');
            $fileName = Str::uuid()->toString() . '.' . $extension;

            $candidateDirectories = [
                public_path('uploads/users'),
                base_path('../public_html/uploads/users'),
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

        $user = User::where('email', $email)->first();
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
