<?php

namespace App\Console\Commands;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Province;
use App\Models\Ward;
use App\Utils\Constants\CategoryStatus;
use App\Utils\Constants\StoragePath;
use App\Utils\HelperFunction;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InitApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init_application';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use to Init all aplication';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $migrateCode = Artisan::call('migrate');
        $this->info('--- Khởi tạo database');
        if ($migrateCode === Command::SUCCESS) {
            $this->info('Lệnh migrate đã thành công!');
        } else {
            $this->error('Lỗi khi chạy migrate!');
            return Command::FAILURE;
        }


        $this->info('--- Seeding tỉnh thành');
        $resultProvinceWard = $this->initProvinceWards();
        if ($resultProvinceWard === true) {
            $this->info('Seeding tỉnh thành thành công');
        }else{
            $this->error('Lỗi khi chạy Seeding tỉnh thành thành công!');
            return Command::FAILURE;
        }


        $this->info('--- Seeding demo database');
        DB::beginTransaction();
        $r1 = $this->seedingBanner();
        if (!$r1) {
            DB::rollBack();
            $this->error('Lỗi khi chạy Seeding demo database r1!');
            return Command::FAILURE;
        }

        $r2 = $this->seedingCategory();
        if (!$r2) {
            DB::rollBack();
            $this->error('Lỗi khi chạy Seeding demo database r2!');
            return Command::FAILURE;
        }

        DB::commit();
        $this->info('Seeding database thành công');
        return Command::SUCCESS;
    }

    private function initProvinceWards(): bool
    {
        DB::beginTransaction();
        try {
            $responseProvince = Http::get('https://production.cas.so/address-kit/latest/provinces');
            if ($responseProvince->successful()) {
                $data = $responseProvince->json();  // Lấy dữ liệu dưới dạng mảng
                // Lưu dữ liệu vào bảng provinces
                foreach ($data['provinces'] as $provinceData) {
                    Province::query()->updateOrCreate(
                        ['code' => $provinceData['code']],
                        [
                            'name' => $provinceData['name'],
                            'english_name' => $provinceData["englishName"],
                            'administrative_level' => $provinceData["administrativeLevel"],
                            'decree' => $provinceData['decree']
                        ]
                    );
                    $responseWards = Http::get("https://production.cas.so/address-kit/latest/provinces/{$provinceData['code']}/communes");
                    if ($responseWards->successful()) {
                        $dataWards = $responseWards->json();
                        foreach ($dataWards['communes'] as $wardData) {
                            Ward::query()->updateOrCreate(
                                ['code' => $wardData['code'], 'province_code' => $wardData['provinceCode']],
                                [
                                    'name' => $wardData['name'],
                                    'english_name' => $wardData['englishName'],
                                    'administrative_level' => $wardData['administrativeLevel'],
                                    'decree' => $wardData['decree'],
                                    'province_code' => $wardData['provinceCode'] // Province relationship
                                ]
                            );
                        }
                    } else {
                        DB::rollBack();
                        return false;
                    }
                }
                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    private function seedingBanner(): bool
    {
        $existingBanners = [
            public_path('images/banner1.png'),
            public_path('images/banner2.png'),
            public_path('images/banner3.png'),
        ];
        try {
            for ($i = 0; $i < 9; $i++) {
                $randomBanner = Arr::random($existingBanners);
                // Xác định giá trị cho show_index
                $showIndex = ($i < 3) ? true : false;  // 3 bản ghi đầu tiên sẽ có show_index = true
                $fileName = Str::random(24) . ".png";
                $newPath = StoragePath::makePath(StoragePath::BANNER_PATH, $fileName);
                Storage::disk('public')->put($newPath, file_get_contents($randomBanner));
                Banner::create([
                    'banner_index' => $showIndex,
                    'link' => null,
                    'image_path' => $newPath,
                    'sort' => $i,
                    'show' => true,
                    'alt_banner' => "AFY - App review số 1"
                ]);
            }
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    private function seedingCategory()
    {
        try {
            $foodPath = StoragePath::makePath(StoragePath::CATEGORY_PATH, Str::random(24) . ".svg");
            Storage::disk('public')->put($foodPath, file_get_contents(public_path('images/logo/amthuc.svg')));
            // Ẩm thực
            $food = Category::create([
                'name' => 'Ẩm thực',
                'slug' => 'am-thuc',
                'description' => 'Các dịch vụ liên quan đến ăn uống, từ quán ăn, nhà hàng đến các dịch vụ ẩm thực đặc biệt.',
                'show_header_home_page' => true,
                'show_index_home_page' => true,
                'logo' => $foodPath,
                'status' => CategoryStatus::ACTIVE->value
            ]);
            $food->children()->createMany([
                [
                    'name' => 'Ăn uống',
                    'slug' => 'an-uong',
                    'show_header_home_page' => false,
                    'show_index_home_page' => true,
                    'description' => 'Các dịch vụ ăn uống phổ biến, từ quán ăn nhỏ đến tiệm ăn lớn.',
                    'status' => CategoryStatus::ACTIVE->value

                ],
                [
                    'name' => 'Quán nhậu',
                    'slug' => 'quan-nhau',
                    'show_header_home_page' => false,
                    'show_index_home_page' => true,
                    'description' => 'Các quán nhậu phục vụ các món nhậu và thức uống.',
                    'status' => CategoryStatus::ACTIVE->value
                ],
                [
                    'name' => 'Nhà hàng',
                    'slug' => 'nha-hang',
                    'show_header_home_page' => false,
                    'show_index_home_page' => true,
                    'description' => 'Các nhà hàng phục vụ các món ăn đặc sắc cho mọi lứa tuổi.',
                    'status' => CategoryStatus::ACTIVE->value
                ]
            ]);


            // Lưu trú
            $stay = Category::create([
                'name' => 'Lưu trú',
                'slug' => 'luu-tru',
                'description' => 'Các dịch vụ lưu trú từ khách sạn cao cấp đến nhà trọ bình dân.',
                'show_header_home_page' => false,
                'show_index_home_page' => true,
                'status' => CategoryStatus::ACTIVE->value,
            ]);

            $hotelPath = StoragePath::makePath(StoragePath::CATEGORY_PATH, Str::random(24) . ".svg");
            Storage::disk('public')->put($hotelPath, file_get_contents(public_path('images/logo/khachsan.svg')));

            $motelPath = StoragePath::makePath(StoragePath::CATEGORY_PATH, Str::random(24) . ".svg");
            Storage::disk('public')->put($motelPath, file_get_contents(public_path('images/logo/nhatro.svg')));

            $stay->children()->createMany([
                [
                    'name' => 'Khách sạn',
                    'slug' => 'khach-san',
                    'show_header_home_page' => true,
                    'show_index_home_page' => true,
                    'logo' => $hotelPath,
                    'status' => CategoryStatus::ACTIVE->value,
                    'description' => 'Khách sạn với các tiện nghi hiện đại phục vụ du khách.',
                ],
                [
                    'name' => 'Homestay',
                    'slug' => 'homestay',
                    'show_header_home_page' => false,
                    'show_index_home_page' => true,
                    'status' => CategoryStatus::ACTIVE->value,
                    'description' => 'Homestay mang đến trải nghiệm sống cùng gia đình địa phương.'
                ],
                [
                    'name' => 'Nhà trọ',
                    'slug' => 'nha-tro',
                    'show_header_home_page' => true,
                    'show_index_home_page' => true,
                    'status' => CategoryStatus::ACTIVE->value,
                    'logo' => $motelPath,
                    'description' => 'Nhà trọ với mức giá hợp lý cho các bạn sinh viên hoặc khách du lịch.'
                ]
            ]);

            // Y tế
            $health = Category::create([
                'name' => 'Y tế',
                'slug' => 'y-te',
                'description' => 'Các dịch vụ y tế bao gồm bệnh viện, phòng khám và hiệu thuốc.',
                'show_header_home_page' => false,
                'show_index_home_page' => true,
                'status' => CategoryStatus::ACTIVE->value
            ]);

            $clinicPath = StoragePath::makePath(StoragePath::CATEGORY_PATH, Str::random(24) . ".svg");
            Storage::disk('public')->put($clinicPath, file_get_contents(public_path('images/logo/phongkham.svg')));

            $pharmacyPath = StoragePath::makePath(StoragePath::CATEGORY_PATH, Str::random(24) . ".svg");
            Storage::disk('public')->put($pharmacyPath, file_get_contents(public_path('images/logo/hieuthuoc.svg')));
            $health->children()->createMany([
                [
                    'name' => 'Bệnh viện',
                    'slug' => 'benh-vien',
                    'show_header_home_page' => false,
                    'show_index_home_page' => true,
                    'status' => CategoryStatus::ACTIVE->value,
                    'description' => 'Các bệnh viện cung cấp dịch vụ chăm sóc sức khỏe toàn diện.'
                ],
                [
                    'name' => 'Phòng khám',
                    'slug' => 'phong-kham',
                    'show_header_home_page' => true,
                    'show_index_home_page' => true,
                    'logo' => $clinicPath,
                    'status' => CategoryStatus::ACTIVE->value,
                    'description' => 'Phòng khám đa khoa với các dịch vụ khám chữa bệnh đa dạng.'
                ],
                [
                    'name' => 'Hiệu thuốc',
                    'slug' => 'hieu-thuoc',
                    'show_header_home_page' => true,
                    'show_index_home_page' => true,
                    'status' => CategoryStatus::ACTIVE->value,
                    'logo' => $pharmacyPath,
                    'description' => 'Các hiệu thuốc cung cấp thuốc và dịch vụ y tế cho bệnh nhân.'
                ]
            ]);

            // Giáo dục
            $education = Category::create([
                'name' => 'Giáo dục',
                'slug' => 'giao-duc',
                'description' => 'Các dịch vụ giáo dục bao gồm các trường học từ mầm non đến đại học.',
                'show_header_home_page' => false,
                'show_index_home_page' => true,
                'status' => CategoryStatus::ACTIVE->value
            ]);
            $education->children()->createMany([
                [
                    'name' => 'Trường mầm non',
                    'slug' => 'truong-mam-non',
                    'show_header_home_page' => false,
                    'show_index_home_page' => true,
                    'status' => CategoryStatus::ACTIVE->value,
                    'description' => 'Trường mầm non với các chương trình học và chăm sóc trẻ em.'
                ],
                [
                    'name' => 'Trường cấp 1-2-3',
                    'slug' => 'truong-cap-1-2-3',
                    'show_header_home_page' => false,
                    'show_index_home_page' => true,
                    'status' => CategoryStatus::ACTIVE->value,
                    'description' => 'Các trường học từ cấp 1 đến cấp 3 giúp học sinh phát triển toàn diện.'
                ],
                [
                    'name' => 'Trường đại học',
                    'slug' => 'truong-dai-hoc',
                    'show_header_home_page' => false,
                    'show_index_home_page' => true,
                    'status' => CategoryStatus::ACTIVE->value,
                    'description' => 'Trường đại học đào tạo chuyên sâu về các lĩnh vực học thuật và nghề nghiệp.'
                ]
            ]);

            // Tiện ích khác
            $utilities = Category::create([
                'name' => 'Tiện ích khác',
                'slug' => 'tien-ich-khac',
                'description' => 'Các tiện ích như trạm sửa xe, cứu hộ, xăng dầu,... giúp phục vụ cộng đồng.',
                'show_header_home_page' => false,
                'show_index_home_page' => true,
                'status' => CategoryStatus::ACTIVE->value
            ]);

            $vehicleRepairPath = StoragePath::makePath(StoragePath::CATEGORY_PATH, Str::random(24) . ".svg");
            Storage::disk('public')->put($vehicleRepairPath, file_get_contents(public_path('images/logo/tramsuaxe.svg')));

            $gasStationPath = StoragePath::makePath(StoragePath::CATEGORY_PATH, Str::random(24) . ".svg");
            Storage::disk('public')->put($gasStationPath, file_get_contents(public_path('images/logo/tramxang.svg')));

            $electricStationPath = StoragePath::makePath(StoragePath::CATEGORY_PATH, Str::random(24) . ".svg");
            Storage::disk('public')->put($electricStationPath, file_get_contents(public_path('images/logo/tramsacdien.svg')));

            $entertainmentPath = StoragePath::makePath(StoragePath::CATEGORY_PATH, Str::random(24) . ".svg");
            Storage::disk('public')->put($entertainmentPath, file_get_contents(public_path('images/logo/giaitri.svg')));

            $beautyPath = StoragePath::makePath(StoragePath::CATEGORY_PATH, Str::random(24) . ".svg");
            Storage::disk('public')->put($beautyPath, file_get_contents(public_path('images/logo/lamdep.svg')));

            $atmPath = StoragePath::makePath(StoragePath::CATEGORY_PATH, Str::random(24) . ".svg");
            Storage::disk('public')->put($atmPath, file_get_contents(public_path('images/logo/atm.svg')));

            $travelPath = StoragePath::makePath(StoragePath::CATEGORY_PATH, Str::random(24) . ".svg");
            Storage::disk('public')->put($travelPath, file_get_contents(public_path('images/logo/dulich.svg')));

            $shoppingPath = StoragePath::makePath(StoragePath::CATEGORY_PATH, Str::random(24) . ".svg");
            Storage::disk('public')->put($shoppingPath, file_get_contents(public_path('images/logo/muasam.svg')));

            $utilities->children()->createMany([
                [
                    'name' => 'Mua sắm',
                    'slug' => 'mua-sam',
                    'description' => 'Các dịch vụ mua sắm đa dạng từ siêu thị, cửa hàng tiện lợi đến trung tâm thương mại, đáp ứng nhu cầu tiêu dùng hàng ngày.',
                    'show_header_home_page' => true,
                    'show_index_home_page' => true,
                    'logo' => $shoppingPath,
                    'status' => CategoryStatus::ACTIVE->value
                ],
                [
                    'name' => 'Du lịch',
                    'slug' => 'du-lich',
                    'description' => 'Các địa điểm du lịch nổi bật, cung cấp dịch vụ tham quan, nghỉ dưỡng và trải nghiệm văn hóa cho cộng đồng.',
                    'show_header_home_page' => true,
                    'show_index_home_page' => false,
                    'status' => CategoryStatus::ACTIVE->value,
                    'logo' => $travelPath,
                ],
                [
                    'name' => 'Trạm sửa xe',
                    'slug' => 'tram-sua-xe',
                    'show_header_home_page' => true,
                    'show_index_home_page' => true,
                    'logo' => $vehicleRepairPath,
                    'status' => CategoryStatus::ACTIVE->value,
                    'description' => 'Các trạm sửa xe ô tô và xe máy cung cấp dịch vụ sửa chữa và bảo dưỡng.'
                ],
                [
                    'name' => 'Trạm xăng',
                    'slug' => 'tram-xang',
                    'show_header_home_page' => true,
                    'show_index_home_page' => true,
                    'logo' => $gasStationPath,
                    'status' => CategoryStatus::ACTIVE->value,
                    'description' => 'Các trạm xăng dầu cung cấp nhiên liệu cho xe cộ trên các tuyến đường.'
                ],
                [
                    'name' => 'Trạm sạc điện',
                    'slug' => 'tram-sac-dien',
                    'show_header_home_page' => true,
                    'show_index_home_page' => false,
                    'logo' => $electricStationPath,
                    'status' => CategoryStatus::ACTIVE->value,
                    'description' => 'Các trạm xăng dầu cung cấp nhiên liệu cho xe cộ trên các tuyến đường.'
                ],
                [
                    'name' => 'Trạm cứu hộ',
                    'slug' => 'tram-cuu-ho',
                    'show_header_home_page' => false,
                    'show_index_home_page' => true,
                    'status' => CategoryStatus::ACTIVE->value,
                    'description' => 'Trạm cứu hộ hỗ trợ khẩn cấp khi xe gặp sự cố trên đường.'
                ],
                [
                    'name' => 'Giải trí',
                    'slug' => 'giai-tri',
                    'description' => 'Các địa điểm giải trí như rạp chiếu phim, khu vui chơi, công viên, và các hoạt động giải trí khác phục vụ nhu cầu thư giãn và giải trí của cộng đồng.',
                    'show_header_home_page' => true,
                    'show_index_home_page' => false,
                    'logo' => $entertainmentPath,
                    'status' => CategoryStatus::ACTIVE->value,
                ],
                [
                    'name' => 'Làm đẹp',
                    'slug' => 'lam-dep',
                    'description' => 'Các địa điểm làm đẹp như spa, salon tóc, thẩm mỹ viện và các dịch vụ chăm sóc sắc đẹp phục vụ nhu cầu làm đẹp và thư giãn của cộng đồng.',
                    'show_header_home_page' => true,
                    'show_index_home_page' => false,
                    'status' => CategoryStatus::ACTIVE->value,
                    'logo' => $beautyPath,
                ],
                [
                    'name' => 'ATM',
                    'slug' => 'atm',
                    'description' => 'Các địa điểm ATM cung cấp dịch vụ rút tiền, chuyển khoản và các giao dịch tài chính tự động cho cộng đồng.',
                    'show_header_home_page' => true,
                    'show_index_home_page' => false,
                    'status' => CategoryStatus::ACTIVE->value,
                    'logo' => $atmPath,
                ],
            ]);
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

}
