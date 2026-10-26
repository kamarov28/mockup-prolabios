<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class NewsletterAuthController extends Controller
{
    public function showSignup()
    {
        if (Auth::guard('subscriber')->check()) {
            return redirect()->route('newsletter.account');
        }

        $sectors = Sector::orderBy('name')->get();

        return view('newsletter.signup', compact('sectors'));
    }

    public function signup(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:newsletter_subscribers,email'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'company' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:150'],
            'country' => ['nullable', 'string', 'max:100'],
            'sectors' => ['required', 'array', 'min:1'],
            'sectors.*' => ['string', 'exists:sectors,id'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'privacy_consent' => ['required', 'accepted'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar sebagai pelanggan buletin. Silakan login ke akun Anda.',
            'sectors.required' => 'Pilih minimal satu sektor industri yang diminati.',
            'sectors.min' => 'Pilih minimal satu sektor industri yang diminati.',
            'privacy_consent.required' => 'Anda harus menyetujui kebijakan privasi sebelum mendaftar.',
            'privacy_consent.accepted' => 'Anda harus menyetujui kebijakan privasi sebelum mendaftar.',
        ]);

        $subscriber = NewsletterSubscriber::create([
            'email' => strtolower(trim($validated['email'])),
            'first_name' => trim($validated['first_name']),
            'last_name' => trim($validated['last_name'] ?? ''),
            'company' => trim($validated['company']),
            'city' => trim($validated['city'] ?? ''),
            'country' => trim($validated['country'] ?? 'Indonesia') ?: 'Indonesia',
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        $subscriber->sectors()->sync($validated['sectors']);

        Auth::guard('subscriber')->login($subscriber);

        return redirect()->route('newsletter.account')->with('success', 'Akun buletin berhasil didaftarkan! Preferensi sektor Anda telah aktif.');
    }

    public function showLogin()
    {
        if (Auth::guard('subscriber')->check()) {
            return redirect()->route('newsletter.account');
        }

        return view('newsletter.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('subscriber')->attempt(['email' => strtolower(trim($credentials['email'])), 'password' => $credentials['password'], 'is_active' => true], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('newsletter.account'))->with('success', 'Selamat datang kembali di Portal Preferensi Buletin Prolabios.');
        }

        return back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'Email atau kata sandi tidak cocok, atau akun dinonaktifkan.',
        ]);
    }

    public function account()
    {
        $subscriber = Auth::guard('subscriber')->user();
        if (! $subscriber) {
            return redirect()->route('newsletter.login');
        }

        $sectors = Sector::orderBy('name')->get();
        $selectedSectorIds = $subscriber->sectors()->pluck('sectors.id')->toArray();

        return view('newsletter.account', compact('subscriber', 'sectors', 'selectedSectorIds'));
    }

    public function updatePreferences(Request $request)
    {
        /** @var NewsletterSubscriber $subscriber */
        $subscriber = Auth::guard('subscriber')->user();
        if (! $subscriber) {
            return redirect()->route('newsletter.login');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'company' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:150'],
            'sectors' => ['required', 'array', 'min:1'],
            'sectors.*' => ['string', 'exists:sectors,id'],
            'password' => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'sectors.required' => 'Pilih minimal satu sektor industri yang diminati.',
        ]);

        $updateData = [
            'first_name' => trim($validated['first_name']),
            'last_name' => trim($validated['last_name'] ?? ''),
            'company' => trim($validated['company']),
            'city' => trim($validated['city'] ?? ''),
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $subscriber->update($updateData);
        $subscriber->sectors()->sync($validated['sectors']);

        return back()->with('success', 'Preferensi sektor dan profil Anda berhasil diperbarui.');
    }

    public function logout(Request $request)
    {
        Auth::guard('subscriber')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('informasi')->with('success', 'Anda telah keluar dari akun buletin.');
    }

    public function unsubscribe(string $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();
        $subscriber->update(['is_active' => false]);

        return view('newsletter.unsubscribed', compact('subscriber'));
    }
}
