<?php

namespace App\Filament\Resources\Stores\Pages;

use App\Filament\Resources\Stores\StoreResource;
use App\Models\Store;
use App\Models\StoreFile;
use App\Utils\Constants\StoragePath;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Vite;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EditStore extends EditRecord
{
    protected static string $resource = StoreResource::class;

    protected static ?string $title = "Sửa địa điểm";

    protected static ?string $navigationLabel = "Sửa địa điểm";


    protected function mutateFormDataBeforeFill(array $data): array
    {
        $store = Store::query()->find($data['id']);
        $data['store_utility'] = $store->utilities()->allRelatedIds()->toArray();
        if (!$store->storeFiles->isEmpty()){
            $data['store_files'] =  $store->storeFiles->pluck('file_path')->all();
        }
        $location = [
            'lat' => $data['latitude'],
            'lng' => $data['longitude'],
            'address' => $data['address']
        ];
        $data['store_location'] = json_encode($location);
        return $data;
    }

    public function boot()
    {
        FilamentAsset::register([
            Css::make('app-css', Vite::asset('resources/css/app.css')),
        ]);
    }

    /**
     * @param Store $record
     * @param array $data
     * @return Model
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        DB::beginTransaction();
        try {
            $storeLocation = json_decode($data['store_location'], true);
            $latitude = $storeLocation['lat'] ?? null;
            $longitude = $storeLocation['lng'] ?? null;
            $address = $storeLocation['address'] ?? null;

            $update = [
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

            // update
            $record->update($update);

            // sync store_utility
            $record->utilities()->sync($data['store_utility']);

            // Nếu có update logo path vì để là storage file = false
            if (isset($data['logo_path']) && $data['logo_path'] instanceof TemporaryUploadedFile) {
                // Xóa ảnh cũ trước
                if ($record->logo_path && Storage::disk('public')->exists($record->logo_path)) {
                    Storage::disk('public')->delete($record->logo_path);
                }
                $update['logo_path'] = $data['logo_path']->store(StoragePath::makePathById(StoragePath::STORE_PATH, $record->id), 'public');
            }

            $storeFiles = [];
            $existingFilePaths = [];
            // nếu khách hàng cập nhật kho ảnh
            if (isset($data['store_files']) && is_array($data['store_files'])) {
                foreach ($data['store_files'] as $file) {
                    if ($file instanceof TemporaryUploadedFile) {
                        $filePath = $file->store(StoragePath::makePathById(StoragePath::STORE_PATH, $record->id), 'public');
                        $fileName = $file->getClientOriginalName();
                        $fileExtension = $file->getClientOriginalExtension();
                        $fileSize = $file->getSize();
                        $fileType = $file->getMimeType();
                        $storeFiles[] = [
                            'store_id' => $record->id,
                            'file_path' => $filePath,
                            'file_name' => $fileName,
                            'file_extension' => $fileExtension,
                            'file_size' => $fileSize,
                            'file_type' => $fileType,
                        ];
                    }else{
                        $existingFilePaths[] = $file;
                    }
                }

                // xóa những file đã tồn tại
                if (!empty($existingFilePaths)){
                    $filesToDelete = $record->storeFiles()
                        ->whereNotIn('file_path', $existingFilePaths)
                        ->get();
                    foreach ($filesToDelete as $file) {
                        if (Storage::disk('public')->exists($file->file_path)) {
                            Storage::disk('public')->delete($file->file_path);
                        }
                        $file->delete();
                    }
                }

                if (!empty($storeFiles)) {
                    StoreFile::query()->insert($storeFiles);
                }
            }else{
                // Nếu xóa hết ảnh
                $filesToDelete = $record->storeFiles()->get();
                if ($filesToDelete->count() > 0) {
                    foreach ($filesToDelete as $file) {
                        if (Storage::disk('public')->exists($file->file_path)) {
                            Storage::disk('public')->delete($file->file_path);
                        }
                        $file->delete();
                    }
                }
            }
            DB::commit();

            return $record;
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
