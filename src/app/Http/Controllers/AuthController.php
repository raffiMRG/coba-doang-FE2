<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
  // Matches the backend's refresh token TTL (AuthRepositorys.RefreshTokenTTL).
  private const REMEMBER_COOKIE_MINUTES = 30 * 24 * 60;
  private const REMEMBER_COOKIE = 'remember_refresh_token';

  public function showLogin()
  {
    if (session('access_token')) {
      return redirect('/home');
    }

    return view('login', ['error' => null]);
  }

  public function login(Request $request)
  {
    $request->validate([
      'username' => 'required|string',
      'password' => 'required|string',
    ]);

    $apiUrl = config('app.api_url');
    $response = Http::post("{$apiUrl}/login", [
      'username' => $request->input('username'),
      'password' => $request->input('password'),
    ]);

    if ($response->failed()) {
      return view('login', ['error' => 'Username atau password salah.']);
    }

    $data = $response->json('Data');
    $refreshToken = $data['refresh_token'] ?? null;

    session([
      'access_token' => $data['access_token'] ?? null,
      'refresh_token' => $refreshToken,
    ]);

    // "Remember me" persists the refresh token in its own long-lived cookie,
    // independent of the Laravel session's normal SESSION_LIFETIME — the
    // auth middleware uses it to silently re-establish a session later.
    if ($request->boolean('remember') && $refreshToken) {
      Cookie::queue(self::REMEMBER_COOKIE, $refreshToken, self::REMEMBER_COOKIE_MINUTES);
    }

    return redirect('/home');
  }

  public function logout()
  {
    $apiUrl = config('app.api_url');
    $refreshToken = session('refresh_token');

    if ($refreshToken) {
      Http::withToken(session('access_token'))
        ->post("{$apiUrl}/logout", ['refresh_token' => $refreshToken]);
    }

    session()->forget(['access_token', 'refresh_token']);
    Cookie::queue(Cookie::forget(self::REMEMBER_COOKIE));

    return redirect('/login');
  }
}
