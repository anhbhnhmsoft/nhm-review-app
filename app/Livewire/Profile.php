<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Profile extends BaseComponent
{
    use WithFileUploads, WithPagination;

    public $name;
    public $email;
    public $address;
    public $phone;
    public $introduce;
    public $avatar;
    public $avatar_preview;
    public $avatar_ready = false;
    public $reviews;
    protected $paginationTheme = 'tailwind';
    public User $user;
    protected array $rules = [
        'name' => 'required|string|max:255',
        'address' => 'nullable|string',
        'phone'     => ['nullable', 'regex:/^(0[3|5|7|8|9][0-9]{8}|(\+84[3|5|7|8|9][0-9]{8}))$/'],
        'introduce' => 'nullable|string',
        'avatar' => 'nullable|image|max:20480',
    ];
    public function mount()
    {
        $this->user = auth('web')->user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->address = $this->user->address;
        $this->phone = $this->user->phone;
        $this->introduce = $this->user->introduce;
        $reviews = $this->user
            ->reviews()
            ->with(['store', 'reviewImages'])
            ->latest()
            ->paginate(5);
    }

    public function boot()
    {

        parent::setupBase();
    }

    protected array $messages = [
        'name.required' => 'Vui lòng nhập tên.',
        'name.max' => 'Giới hạn độ dài tên 255 ký tự.',
        'phone.regex' => 'Vui lòng nhập số điện thoại đúng định dạng.',
        'avatar.image' => 'Vui lòng chọn ảnh đúng định dạng.',
        'avatar.max' => 'Giới hạn dung lượng ảnh 20MB.',
    ];

    public function update()
    {

        $this->validate();
        $this->user->update([
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'introduce' => $this->introduce,
        ]);

        flash()->success('Cập nhật thành công!');
    }
    public function uploadAvatar()
    {
        $this->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if (! $this->avatar) {
            flash()->error('Vui lòng chọn ảnh để upload.');
            return;
        }

        try {
            // xóa avatar cũ nếu có
            if ($this->user->avatar_path && Storage::disk('public')->exists($this->user->avatar_path)) {
                Storage::disk('public')->delete($this->user->avatar_path);
            }

            // lưu file (bạn có thể resize trước khi lưu nếu muốn)
            $filename = 'avatar_' . $this->user->id . '_' . time() . '.' . $this->avatar->getClientOriginalExtension();
            $path = $this->avatar->storeAs('avatars', $filename, 'public');

            // update DB và tải lại model user để view cập nhật
            $this->user->update(['avatar_path' => $path]);
            $this->user->refresh();

            // reset state
            $this->reset(['avatar', 'avatar_preview', 'avatar_ready']);

            flash()->success('Cập nhật ảnh đại diện thành công!');
        } catch (\Exception $e) {
            Log::error('Avatar upload error: ' . $e->getMessage());
            flash()->error('Có lỗi xảy ra khi upload ảnh. Vui lòng thử lại.');
        }
    }

    public function updatedAvatar()
    {
        // validate khi mới chọn file
        $this->validateOnly('avatar');

        $this->avatar_preview = $this->avatar->temporaryUrl();
        $this->avatar_ready = true;
    }

    public function removeAvatar()
    {
        $this->reset(['avatar', 'avatar_preview', 'avatar_ready']);
    }


    public function render()
    {
        return $this->view('livewire.profile', [], []);
    }
}
