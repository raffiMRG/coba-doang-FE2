<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
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
    session([
      'access_token' => $data['access_token'] ?? null,
      'refresh_token' => $data['refresh_token'] ?? null,
    ]);

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

    return redirect('/');
  }
}
