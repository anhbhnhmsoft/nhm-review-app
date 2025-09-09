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
    public function register(array $data): User
    {
        $payload = [
            'name' => $data['name'] ?? null,
            'email' => isset($data['email']) ? Str::lower(trim($data['email'])) : null,
            'password' => $data['password'] ?? null,
        ];

        Validator::make($payload, [
            'name' => ['bail', 'required', 'string', 'max:120'],
            'email' => ['bail', 'required', 'email', Rule::unique('users', 'email')],
            'password' => ['bail', 'required', 'string', 'min:6'],
        ])->validate();
        try {
            return DB::transaction(function () use ($payload) {
                $user = User::create([
                    'name'     => $payload['name'],
                    'email'    => $payload['email'],
                    'password' => Hash::make($payload['password']),
                    'role'     => UserRole::USER,
                ]);

                event(new Registered($user));
                $this->sendEmailVerificationNotification($user);
                return $user;
            });
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                throw ValidationException::withMessages([
                    'email' => 'Email đã tồn tại.',
                ]);
            }
            throw $e;
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
        $url = route('verify', ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]);
        try {
            $user->notify(new RegisterEmailVerification($url));
        } catch (\Throwable $e) {
            // Bắt lỗi tại đây
            Log::error('Gửi email verify thất bại: ' . $e->getMessage());
            throw $e; // hoặc return message
        }
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
