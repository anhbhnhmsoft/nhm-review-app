<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\RegisterEmailVerification;
use App\Utils\Constants\LoginResult;
use App\Utils\Constants\UserRole;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Đăng ký user mới
     */
    public function register(array $data): ?User
    {
        $payload = [
            'name' => $data['name'] ?? null,
            'email' => isset($data['email']) ? Str::lower(trim($data['email'])) : null,
            'password' => $data['password'] ?? null,
        ];
        DB::beginTransaction();
        try {
            $user = User::query()->create([
                'name'     => $payload['name'],
                'email'    => $payload['email'],
                'password' => Hash::make($payload['password']),
                'role'     => UserRole::USER,
            ]);
            event(new Registered($user));
            // $this->sendEmailVerificationNotification($user);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Đăng nhập user
     */
    public function login(array $data, bool $remember = false): array
    {
        $credentials = [
            'email' => isset($data['email']) ? Str::lower(trim($data['email'])) : null,
            'password' => $data['password'] ?? null,
        ];

        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $user = Auth::guard('web')->user();
            if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail()) {
                Auth::guard('web')->logout();
                return [
                    'result' => LoginResult::UNVERIFIED_EMAIL,
                    'message' => 'Email chưa được xác minh'
                ];
            }
            return [
                'result' => LoginResult::SUCCESS,
                'message' => 'Đăng nhập thành công'
            ];
        }

        return [
            'result' => LoginResult::INVALID_CREDENTIALS,
            'message' => 'Email hoặc mật khẩu không đúng.'
        ];
    }

    /**
     * Đăng xuất user
     */
    public function logout(): void
    {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    protected function sendEmailVerificationNotification(Model $user): void
    {
        if (!$user instanceof MustVerifyEmail) {
            return;
        }
        if ($user->hasVerifiedEmail()) {
            return;
        }
        $url = route('verification.verify', ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]);
        $user->notify(new RegisterEmailVerification($url));
    }

    public function verifyEmailUser($id, $hash)
    {
        try {
            $user = User::find($id);
            if (!$user) return false;

            // Kiểm tra xem hash có hợp lệ không
            if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
                return false;
            }

            // Xác thực email người dùng
            if ($user->hasVerifiedEmail()) {
                return false;
            }

            $user->markEmailAsVerified();
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
