<?php

namespace App\Livewire\Store;

use App\Services\ReviewService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewSection extends Component
{
    use WithPagination;

    private ReviewService $reviewService;

    /**
     *  State
     */
    public $store_id;

    public function boot(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function mount($store_id)
    {
        $this->store_id = $store_id;
    }
    #[On('review-created')]
    public function reloadAllPage(): void
    {
        $this->js('window.location.reload()');
    }

    public function render()
    {
        $reviews = $this->reviewService->paginationReviewByStoreId($this->store_id);

        return view('components.store.review-section',[
            'reviews' => $reviews
        ]);
    }
}
