<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Merge cart session ke DB jika ada
            $this->mergeSessionCart($request);

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            $intendedUrl = $request->session()->pull('url.intended');
            if ($intendedUrl && parse_url($intendedUrl, PHP_URL_PATH) === '/home') {
                $intendedUrl = null;
            }

            return redirect()->to($intendedUrl ?: route('home'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak valid.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda berhasil keluar.');
    }

    protected function mergeSessionCart(Request $request): void
    {
        $sessionId = $request->session()->get('cart_session_id');
        if (!$sessionId) return;

        $sessionCart = \App\Models\Cart::where('session_id', $sessionId)->with('items')->first();
        if (!$sessionCart) return;

        $userCart = \App\Models\Cart::firstOrCreate(['user_id' => Auth::id()]);

        foreach ($sessionCart->items as $item) {
            $existing = $userCart->items()->where('product_id', $item->product_id)->first();
            if ($existing) {
                $existing->increment('quantity', $item->quantity);
            } else {
                $userCart->items()->create([
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                ]);
            }
        }

        $sessionCart->delete();
        $request->session()->forget('cart_session_id');
    }
}
