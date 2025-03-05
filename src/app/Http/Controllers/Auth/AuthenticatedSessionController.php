<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController as FortifyAuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;

class AuthenticatedSessionController extends FortifyAuthenticatedSessionController
{
    public function show()
    {
        return view('auth.login');
    }

    public function store(FortifyLoginRequest $request): mixed
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['ログイン情報が登録されていません。'],
            ]);
        }

        return redirect()->intended('/');
    }
}
