<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function verify($id, $hash)
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $result = $this->authService->verifyEmailUser($id, $hash);

        if ($result) {

            flash()->success('Xác minh email thành công!');
            return redirect()->route('frontend.login');
        }
        flash()->error('Liên kết xác minh không hợp lệ hoặc đã hết hạn.');
        return redirect()->route('frontend.login');
    }

    public function logout() {
        $this->authService->logout();
        return redirect()->route('dashboard');
    }
}
