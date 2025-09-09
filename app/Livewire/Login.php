<?php

namespace App\Livewire;

use App\Models\Config;
use App\Services\AuthService;
use App\Services\ConfigService;
use App\Utils\Constants\ConfigName;
use App\Utils\Constants\LoginResult;
use Livewire\Component;

class Login extends BaseComponent
{
    public ?Config $logo_app;
    public string $name = '';
    public string $email = '';
    public string $password = '';

    protected array $rules = [
        'email' => 'bail|required|email',
        'password' => 'required|string|min:6',
    ];

    protected array $messages = [
        'email.required' => 'Vui lòng nhập email.',
        'email.email'    => 'Email không đúng định dạng.',
        'password.required' => 'Vui lòng nhập mật khẩu.',
        'password.min'      => 'Mật khẩu phải có ít nhất :min ký tự.',
    ];

    protected AuthService $authService;

    public function boot(AuthService $authService)
    {
        parent::setupBase();
        $this->authService = $authService;
    }

    public function mount()
    {
        $this->logo_app = $this->configService->getConfig(ConfigName::LOGO);
    }

    public function login()
    {
        $this->validate();
        $data = [
            'email' => $this->email,
            'password' => $this->password,
        ];
        $res = $this->authService->login($data);
        if ($res['result'] == LoginResult::UNVERIFIED_EMAIL) {
            $this->addError('email', $res['message']);
        } else if ($res['result'] == LoginResult::INVALID_CREDENTIALS) {
            $this->addError('email', $res['message']);
        } else {
            flash()->success('Đăng nhập thành công!');
            return redirect(route('dashboard'));
        }
    }

    public function render()
    {
        return $this->view('livewire.login', [], ['hideLayout' => true]);
    }
}
