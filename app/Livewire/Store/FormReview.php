<?php

namespace App\Livewire\Store;

use App\Services\ReviewService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class FormReview extends Component
{
    use WithFileUploads;

    private ReviewService $reviewService;

    /**
     * State
     */
    public bool $isOpen = false;

    /**
     * State form
     */
    public $store_id;
    public $rating_location = 5;
    public $rating_space = 5;
    public $rating_quality = 5;
    public $rating_serve = 5;
    public $review_files = [];

    #[Validate('min:10', message: 'Bạn hãy viết ít nhất 10 kí tự.')]
    public $review = '';
    public $is_anonymous = false;


    /**
     * -----------------------------------------------------
     */
    #[On('open-modal')]
    public function open($store_id = null)
    {
        $this->store_id = $store_id;
        $this->isOpen = true;
        $this->resetForm();
    }

    public function close()
    {
        $this->store_id = null;
        $this->isOpen = false;
        $this->resetForm();
    }

    public function boot(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    #[On('filesUpdated')]
    public function handleFilesUpdated($files)
    {
        $this->review_files = $files;
    }

    public function saveReview()
    {
        $this->validate();
        $res = $this->validateFiles($this->review_files);
        // Nếu có lỗi validation files, return
        if ($this->getErrorBag()->has('review_files')) {
            return;
        }
        if ($res) {
            $form = [
                'store_id' => $this->store_id,
                'rating_location' => $this->rating_location,
                'rating_space' => $this->rating_space,
                'rating_quality' => $this->rating_quality,
                'rating_serve' => $this->rating_serve,
                'review_files' => $this->review_files,
                'review' => $this->review,
                'is_anonymous' => $this->is_anonymous
            ];
            $create = $this->reviewService->createReview($form);
            if ($create){
                flash()->success('Cảm ơn bạn đã review địa điểm này');
                // event reload lại trang
                $this->dispatch('review-created');
                $this->close();
            }else{
                flash()->error('Có lỗi xảy ra, vui lòng thử lại sau');
            }
        }
    }

    public function render()
    {
        return view('components.store.form-review');
    }

    /**
     * --------------------------------------------
     */
    protected function resetForm(): void
    {
        $this->rating_location = 5;
        $this->rating_space = 5;
        $this->rating_quality = 5;
        $this->rating_serve = 5;
        $this->review_files = [];
        $this->review = '';
        $this->is_anonymous = false;
    }

    protected function validateFiles($filesData): bool
    {
        if (!is_array($filesData)) {
            return false;
        }
        if (!empty($filesData)) {
            if (count($filesData) > 5) {
                $this->addError('review_files', 'Tối đa 5 ảnh được phép upload.');
                return false;
            }

            foreach ($filesData as $fileData) {
                // Validate file size (10MB = 10 * 1024 * 1024 bytes)
                if ($fileData['size'] > 10 * 1024 * 1024) {
                    $this->addError('review_files', "Ảnh {$fileData['name']} quá lớn. Kích thước tối đa là 10MB.");
                    return false;
                }
                if (!preg_match('/^data:(image\/[a-zA-Z0-9.+-]+);base64,/', $fileData['content'], $m)) {
                    $this->addError('review_files', "Ảnh {$fileData['name']} không hợp lệ.");
                }
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($fileData['type'], $allowedTypes)) {
                    $this->addError('review_files', "Ảnh {$fileData['name']} không đúng định dạng. Chỉ chấp nhận: JPEG, PNG, JPG.");
                    return false;
                }
            }
        }
        return true;
    }
}
