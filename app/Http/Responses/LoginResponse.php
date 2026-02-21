<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $role = Auth::user()->role;
        \Illuminate\Support\Facades\Log::info('LoginResponse Hit', ['user_id' => Auth::id(), 'role' => $role]);

        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        switch ($role) {
            case 'admin':
                \Illuminate\Support\Facades\Log::info('Redirecting admin to dashboard');
                return redirect()->intended(route('admin.dashboard'));
            case 'panitia':
                return redirect()->intended(route('panitia.dashboard'));
            case 'mahasiswa':
                return redirect()->intended(route('student.dashboard'));
            default:
                \Illuminate\Support\Facades\Log::warning('Role unknown, redirecting to /', ['role' => $role]);
                return redirect('/');
        }
    }
}
