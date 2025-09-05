<?php

namespace App\Services;

use App\Models\Review;
use App\Models\ReviewImage;
use App\Utils\Constants\StoragePath;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReviewService
{
    public function paginationReviewByStoreId($storeId)
    {
        return Review::query()
            ->where('store_id', $storeId)
            ->with(['reviewImages','user'])
            ->orderByDesc('created_at')
            ->paginate(5);
    }

    public function createReview(array $data)
    {
        DB::beginTransaction();
        // dự phòng có gì có thể rollback file
        $written = [];
        try {
            $review = Review::query()->create([
                'store_id' => $data['store_id'],
                'user_id' => auth()->id(),
                'rating_location' => $data['rating_location'],
                'rating_space' => $data['rating_space'],
                'rating_quality' => $data['rating_quality'],
                'rating_serve' => $data['rating_serve'],
                'review' => $data['review'],
                'is_anonymous' => $data['is_anonymous']
            ]);
            if (!empty($data['review_files'])) {
                foreach ($data['review_files'] as $file) {
                    // Decode base64 an toàn
                    [$meta, $base64] = explode(',', $file['content'], 2);
                    $binary = base64_decode($base64, true);
                    if ($binary === false) {
                        throw new \RuntimeException('Ảnh không hợp lệ');
                    }
                    // Xác định extension từ mime
                    $mime = strtolower(explode(';', str_replace('data:', '', $meta))[0] ?? 'image/jpeg');
                    $extension = match ($mime) {
                        'image/png' => 'png',
                        default => 'jpg',
                    };
                    // Tạo tên + path (dùng "/" cho Storage)
                    $filename = Str::uuid() . '.' . $extension;
                    $dir = StoragePath::makePathById(StoragePath::REVIEW_PATH, $review->id); // vd: "reviews/123"
                    $filepath = trim($dir, '/') . '/' . $filename;
                    // Ghi file
                    Storage::disk('public')->put($filepath, $binary);
                    // theo dõi để có gì thì rollback
                    $written[] = $filepath;

                    // Ghi DB ảnh
                    ReviewImage::query()->create([
                        'review_id' => $review->id,
                        'image_path' => $filepath,
                        'image_name' => $filename,
                        'image_extension' => $extension,
                        'image_size' => $file['size'],
                        'image_type' => $mime,
                    ]);
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            if ($written) {
                Storage::disk('public')->delete($written);
            }
            dd($exception);
            return false;
        }
    }

}
