<?php

namespace App\Livewire;

use App\Models\Config;
use App\Services\AuthService;
use App\Services\ConfigService;
use App\Utils\Constants\ConfigName;

class Register extends BaseComponent
{
    private AuthService $authService;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected array $rules = [
        'name' => 'required|string|max:120',
        'email' => 'bail|required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ];

    protected array $messages = [
        'name.required' => 'Vui lòng nhập tên.',
        'name.max' => 'Giới hạn độ dài tên 120 ký tự.',
        'email.required' => 'Vui lòng nhập email.',
        'email.email'    => 'Email không đúng định dạng.',
        'email.unique'   => 'Email này đã được sử dụng.',
        'password.required' => 'Vui lòng nhập mật khẩu.',
        'password.min'      => 'Mật khẩu phải có ít nhất :min ký tự.',
        'password.confirmed'      => 'Mật khẩu xác nhận không đúng',
    ];


    public ?Config $logo_app;

    public function boot(ConfigService $configService, AuthService $authService)
    {
        parent::setupBase();
        $this->authService = $authService;
    }

    public function mount()
    {
        $this->logo_app = $this->configService->getConfig(ConfigName::LOGO);
    }

    public function register()
    {
        $this->validate();
        $user = $this->authService->register([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password
        ]);

        if ($user) {
            flash()->success('Vui lòng kiểm tra và xác nhận email để hoàn tất đăng ký!');
            return redirect(route('frontend.login'));
        } else {
            flash()->error('Có lỗi vui lòng thử lại sau!');
        }
    }

    public function render()
    {
        return $this->view('livewire.register', [], ['hideLayout' => true]);
    }
}
