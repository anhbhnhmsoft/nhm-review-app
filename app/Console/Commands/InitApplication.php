<?php

namespace App\Console\Commands;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Config;
use App\Models\District;
use App\Models\Province;
use App\Models\User;
use App\Models\Utility;
use App\Models\Ward;
use App\Utils\Constants\CategoryStatus;
use App\Utils\Constants\ConfigName;
use App\Utils\Constants\ConfigType;
use App\Utils\Constants\StoragePath;
use App\Utils\Constants\UserRole;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

        $this->info('--- Khởi tạo admin user!');
        User::query()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'role' => UserRole::ADMIN->value,
            'email_verified_at' => now(),
            'password' => Hash::make('test1234567@'),
        ]);

        $this->info('--- Seeding tỉnh thành');
        $rProvince = $this->initProvinces();
        if ($rProvince === true) {
            $this->info('Seeding tỉnh thành thành công');
        }else{
            $this->error('Lỗi khi chạy Seeding tỉnh thành!');
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

        $r3 = $this->seedingUtils();
        if (!$r3) {
            DB::rollBack();
            $this->error('Lỗi khi chạy Seeding demo database r3!');
            return Command::FAILURE;
        }

        $r4 = $this->seedingConfig();
        if (!$r4) {
            DB::rollBack();
            $this->error('Lỗi khi chạy Seeding demo database r4!');
            return Command::FAILURE;
        }

        DB::commit();
        $this->info('Seeding database thành công');
        return Command::SUCCESS;
    }

    private function initProvinces(): bool
    {
        DB::beginTransaction();
        try {
            $responseProvince = Http::get('https://provinces.open-api.vn/api/v1/p/');
            if ($responseProvince->successful()) {
                $data = $responseProvince->json();  // Lấy dữ liệu dưới dạng mảng
                // Lưu dữ liệu vào bảng provinces
                foreach ($data as $provinceData) {
                    Province::query()->updateOrCreate(
                        ['code' => $provinceData['code']],
                        [
                            'name' => $provinceData['name'],
                            'division_type' => $provinceData["division_type"],
                        ]
                    );
                }
            } else {
                DB::rollBack();
                return false;
            }

            $responseDistricts = Http::get('https://provinces.open-api.vn/api/v1/d/');
            if ($responseDistricts->successful()) {
                $data = $responseDistricts->json();  // Lấy dữ liệu dưới dạng mảng
                // Lưu dữ liệu vào bảng provinces
                foreach ($data as $district) {
                    District::query()->updateOrCreate(
                        ['code' => $district['code']],
                        [
                            'name' => $district['name'],
                            'division_type' => $district["division_type"],
                            'province_code' => $district['province_code']
                        ]
                    );
                }
            } else {
                DB::rollBack();
                return false;
            }

            $responseWards = Http::get('https://provinces.open-api.vn/api/v1/w/');
            if ($responseWards->successful()) {
                $data = $responseWards->json();  // Lấy dữ liệu dưới dạng mảng
                // Lưu dữ liệu vào bảng provinces
                foreach ($data as $ward) {
                    Ward::query()->updateOrCreate(
                        ['code' => $ward['code']],
                        [
                            'name' => $ward['name'],
                            'division_type' => $ward["division_type"],
                            'district_code' => $ward['district_code']
                        ]
                    );
                }
            } else {
                DB::rollBack();
                return false;
            }

            DB::commit();
            return true;
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

    private function seedingCategory(): bool
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

    private function seedingUtils(): bool
    {
        try {
            Utility::query()->insert([
                [
                    'name' => 'Bàn ngoài trời',
                    'description' => 'Khu vực dành cho bàn ngoài trời.',
                    'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /></svg>',
                ],
                [
                    'name' => 'Chỉ Bán Mang Đi',
                    'description' => 'Cửa hàng chỉ cung cấp dịch vụ bán mang đi.',
                    'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>',
                ],
                [
                    'name' => 'Chỗ đậu ôtô',
                    'description' => 'Khu vực đậu xe ôtô.',
                    'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" stroke="currentColor"  viewBox="0 0 16 16"><path d="M4 9a1 1 0 1 1-2 0 1 1 0 0 1 2 0m10 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0M6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2zM4.862 4.276 3.906 6.19a.51.51 0 0 0 .497.731c.91-.073 2.35-.17 3.597-.17s2.688.097 3.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 10.691 4H5.309a.5.5 0 0 0-.447.276"/><path d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679q.05.242.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.8.8 0 0 0 .381-.404l.792-1.848ZM4.82 3a1.5 1.5 0 0 0-1.379.91l-.792 1.847a1.8 1.8 0 0 1-.853.904.8.8 0 0 0-.43.564L1.03 8.904a1.5 1.5 0 0 0-.03.294v.413c0 .796.62 1.448 1.408 1.484 1.555.07 3.786.155 5.592.155s4.037-.084 5.592-.155A1.48 1.48 0 0 0 15 9.611v-.413q0-.148-.03-.294l-.335-1.68a.8.8 0 0 0-.43-.563 1.8 1.8 0 0 1-.853-.904l-.792-1.848A1.5 1.5 0 0 0 11.18 3z"/></svg>',
                ],
                [
                    'name' => 'Giao hàng',
                    'description' => 'Cung cấp dịch vụ giao hàng.',
                    'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>', // Thêm icon SVG nếu có
                ],
                [
                    'name' => 'Giữ xe máy',
                    'description' => 'Dịch vụ giữ xe máy cho khách hàng.',
                    'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" stroke="currentColor" viewBox="0 0 640 512"><path d="M512.9 192c-14.9-.1-29.1 2.3-42.4 6.9L437.6 144H520c13.3 0 24-10.7 24-24V88c0-13.3-10.7-24-24-24h-45.3c-6.8 0-13.3 2.9-17.8 7.9l-37.5 41.7-22.8-38C392.2 68.4 384.4 64 376 64h-80c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16h66.4l19.2 32H227.9c-17.7-23.1-44.9-40-99.9-40H72.5C59 104 47.7 115 48 128.5c.2 13 10.9 23.5 24 23.5h56c24.5 0 38.7 10.9 47.8 24.8l-11.3 20.5c-13-3.9-26.9-5.7-41.3-5.2C55.9 194.5 1.6 249.6 0 317c-1.6 72.1 56.3 131 128 131 59.6 0 109.7-40.8 124-96h84.2c13.7 0 24.6-11.4 24-25.1-2.1-47.1 17.5-93.7 56.2-125l12.5 20.8c-27.6 23.7-45.1 58.9-44.8 98.2.5 69.6 57.2 126.5 126.8 127.1 71.6.7 129.8-57.5 129.2-129.1-.7-69.6-57.6-126.4-127.2-126.9zM128 400c-44.1 0-80-35.9-80-80s35.9-80 80-80c4.2 0 8.4.3 12.5 1L99 316.4c-8.8 16 2.8 35.6 21 35.6h81.3c-12.4 28.2-40.6 48-73.3 48zm463.9-75.6c-2.2 40.6-35 73.4-75.5 75.5-46.1 2.5-84.4-34.3-84.4-79.9 0-21.4 8.4-40.8 22.1-55.1l49.4 82.4c4.5 7.6 14.4 10 22 5.5l13.7-8.2c7.6-4.5 10-14.4 5.5-22l-48.6-80.9c5.2-1.1 10.5-1.6 15.9-1.6 45.6-.1 82.3 38.2 79.9 84.3z"/></svg>', // Thêm icon SVG nếu có
                ],
                [
                    'name' => 'Máy lạnh & điều hòa',
                    'description' => 'Cung cấp máy lạnh và điều hòa cho khách hàng.',
                    'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" stroke="currentColor" viewBox="0 0 512 512"><path d="M352.57 128c-28.09 0-54.09 4.52-77.06 12.86l12.41-123.11C289 7.31 279.81-1.18 269.33.13 189.63 10.13 128 77.64 128 159.43c0 28.09 4.52 54.09 12.86 77.06L17.75 224.08C7.31 223-1.18 232.19.13 242.67c10 79.7 77.51 141.33 159.3 141.33 28.09 0 54.09-4.52 77.06-12.86l-12.41 123.11c-1.05 10.43 8.11 18.93 18.59 17.62 79.7-10 141.33-77.51 141.33-159.3 0-28.09-4.52-54.09-12.86-77.06l123.11 12.41c10.44 1.05 18.93-8.11 17.62-18.59-10-79.7-77.51-141.33-159.3-141.33zM256 288a32 32 0 1 1 32-32 32 32 0 0 1-32 32z"/></svg>', // Thêm icon SVG nếu có
                ],
                [
                    'name' => 'Thanh toán bằng thẻ',
                    'description' => 'Chấp nhận thanh toán qua thẻ.',
                    'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>', // Thêm icon SVG nếu có
                ],
                [
                    'name' => 'Wi-Fi miễn phí',
                    'description' => 'Cung cấp Wi-Fi miễn phí cho khách hàng.',
                    'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 0 1 1.06 0Z" /></svg>',
                ],
            ]);
            return true;
        }catch (\Exception $exception){
            return false;
        }
    }

    private function seedingConfig(): bool
    {
        $logoPath = StoragePath::makePath(StoragePath::CONFIG_PATH, 'logo.svg');
        Storage::disk('public')->put($logoPath, file_get_contents(public_path('images/logo.svg')));

        try {
            Config::query()->insert([
                [
                    'config_key' => ConfigName::LOGO->value,
                    'config_type' => ConfigType::IMAGE->value,
                    'config_value' => $logoPath,
                    'description' => 'Cấu hình logo cửa hàng',
                ],
                [
                    'config_key' => ConfigName::FACEBOOK->value,
                    'config_type' => ConfigType::STRING->value,
                    'config_value' => 'https://www.facebook.com/profile.php?id=100000000000000',
                    'description' => 'Cấu hình trang Facebook cửa hàng',
                ],
                [
                    'config_key' => ConfigName::YOUTUBE->value,
                    'config_type' => ConfigType::STRING->value,
                    'config_value' => 'https://www.youtube.com/channel/UC1234567890',
                    'description' => 'Cấu hình trang Youtube cửa hàng',
                ],
                [
                    'config_key' => ConfigName::TIKTOK->value,
                    'config_type' => ConfigType::STRING->value,
                    'config_value' => 'https://www.tiktok.com/@username',
                    'description' => 'Cấu hình trang TikTok cửa hàng',
                ],
                [
                    'config_key' => ConfigName::INSTAGRAM->value,
                    'config_type' => ConfigType::STRING->value,
                    'config_value' => 'https://www.instagram.com/username',
                    'description' => 'Cấu hình trang Instagram cửa hàng',
                ],
                [
                    'config_key' => ConfigName::FOOTER_COPYRIGHT->value,
                    'config_type' => ConfigType::STRING->value,
                    'config_value' => 'Copyright © 2025 Cửa hàng',
                    'description' => 'Cấu hình copyright cửa hàng',
                ],
                [
                    'config_key' => ConfigName::MANAGING_UNIT->value,
                    'config_type' => ConfigType::STRING->value,
                    'config_value' => 'Công Ty Công Nghệ MGS Địa chỉ:765A Âu Cơ, P. Tân Định, Q1, TP.HCM',
                    'description' => 'Đơn vị chủ quản của cửa hàng',
                ],
            ]);
            return true;
        }catch (\Exception $exception){
            return false;
        }
    }
}
