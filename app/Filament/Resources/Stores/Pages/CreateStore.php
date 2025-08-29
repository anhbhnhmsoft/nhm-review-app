<?php

namespace App\Filament\Resources\Stores\Pages;

use App\Filament\Resources\Stores\StoreResource;
use App\Models\Store;
use App\Models\StoreFile;
use App\Utils\Constants\StoragePath;
use App\Utils\HelperFunction;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Vite;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CreateStore extends CreateRecord
{
    protected static string $resource = StoreResource::class;

    protected static ?string $title = "Tạo địa điểm";

    protected static bool $canCreateAnother = false;

    public function boot()
    {
        FilamentAsset::register([
            Css::make('app-css', Vite::asset('resources/css/app.css')),
        ]);
    }

    protected function handleRecordCreation(array $data): Model
    {
        DB::beginTransaction();

        try {
            $storeLocation = json_decode($data['store_location'], true);
            $latitude = $storeLocation['lat'] ?? null;
            $longitude = $storeLocation['lng'] ?? null;
            $address = $storeLocation['address'] ?? null;

            $create = [
                'id' => HelperFunction::getTimestampAsId(),
                'name' => $data['name'],
                'slug' => $data['slug'],
                'category_id' => $data['category_id'],
                'province_code' => $data['province_code'],
                'district_code' => $data['district_code'],
                'ward_code' => $data['ward_code'],
                'address' => $address,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'facebook_page' => $data['facebook_page'] ?? null,
                'instagram_page' => $data['instagram_page'] ?? null,
                'tiktok_page' => $data['tiktok_page'] ?? null,
                'youtube_page' => $data['youtube_page'] ?? null,
                'opening_time' => $data['opening_time'],
                'closing_time' => $data['closing_time'],
                'status' => $data['status'],
                'view' => $data['view'] ?? 0,
                'featured' => $data['featured'],
                'sorting_order' => $data['sorting_order'] ?? 0,
            ];

            if (isset($data['logo_path']) && $data['logo_path'] instanceof TemporaryUploadedFile) {
                $logoPath = $data['logo_path']->store(StoragePath::makePathById(StoragePath::STORE_PATH, $create['id']), 'public');
                $create['logo_path'] = $logoPath;
            }

            $store = Store::query()->create($create);

            $storeFiles = [];
            if (isset($data['store_files']) && is_array($data['store_files'])) {
                foreach ($data['store_files'] as $file) {
                    if ($file instanceof TemporaryUploadedFile) {
                        $filePath = $file->store(StoragePath::makePathById(StoragePath::STORE_PATH, $create['id']), 'public');
                        $fileName = $file->getClientOriginalName();
                        $fileExtension = $file->getClientOriginalExtension();
                        $fileSize = $file->getSize();
                        $fileType = $file->getMimeType();
                        $storeFiles[] = [
                            'store_id' => $store->id,
                            'file_path' => $filePath,
                            'file_name' => $fileName,
                            'file_extension' => $fileExtension,
                            'file_size' => $fileSize,
                            'file_type' => $fileType,
                        ];
                    }
                }
                if (!empty($storeFiles)) {
                    StoreFile::query()->insert($storeFiles);
                }
            }

            // Attach utilities
            if (isset($data['store_utility']) && is_array($data['store_utility'])) {
                $store->utilities()->attach($data['store_utility']);
            }

            DB::commit();

            return $store;

        }catch (\Exception $exception){
            DB::rollBack();
            if (isset($logoPath) && $logoPath) {
                Storage::disk('public')->delete($logoPath);
            }
            if (!empty($storeFiles)) {
                foreach ($storeFiles as $file) {
                    Storage::disk('public')->delete($file['file_path']);
                }
            }
            throw $exception;
        }
    }
}
