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
use App\Models\Article;
use App\Utils\Constants\CategoryStatus;
use App\Utils\Constants\ConfigName;
use App\Utils\Constants\ConfigType;
use App\Utils\Constants\StoragePath;
use App\Utils\Constants\UserRole;
use App\Utils\Constants\ArticleType;
use App\Utils\Constants\ArticleStatus;
use App\Utils\HelperFunction;
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

        $r5 = $this->seedingArticle();
        if (!$r5) {
            DB::rollBack();
            $this->error('Lỗi khi chạy Seeding demo database r5!');
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

    private function seedingArticle(): bool
    {
        try {
            $pages = [
                [
                    'title' => 'Giới thiệu',
                    'excerpt' => 'Giới thiệu về hệ thống và sứ mệnh phục vụ cộng đồng.',
                    'content' => '<h3>Hệ thống AFY</h3><p>AFY là nền tảng website &amp; ứng dụng di động hiện đại, giúp người dùng dễ dàng tìm kiếm và khám phá các địa điểm lưu trú, ăn uống, vui chơi, giải trí một cách nhanh chóng và tiện lợi.</p><p>Tất cả bài viết và đánh giá trên AFY đều được xây dựng dựa trên trải nghiệm thực tế, đảm bảo tính <strong>chân thật</strong> và <strong>tin cậy</strong> từ cộng đồng người dùng.</p><p>AFY được xem như một mạng xã hội thu nhỏ, nơi mọi người có thể để lại nhận xét và đánh giá cho những địa điểm mà họ đã ghé thăm. Từ đó, hình thành nên một cộng đồng văn minh, thân thiện và giàu giá trị.</p><p>Với mong muốn mang lại thật nhiều trải nghiệm đáng nhớ và hữu ích cho người dùng ở khắp mọi nơi, chúng tôi hy vọng AFY sẽ được cộng đồng đón nhận và yêu thích ♥️</p>',
                ],
                [
                    'title' => 'Điều khoản sử dụng',
                    'excerpt' => 'Các điều khoản khi sử dụng dịch vụ của chúng tôi.',
                    'content' => '<h1>ĐIỀU KHOẢN SỬ DỤNG DỊCH VỤ AFY</h1><h2>Điều 1: Quy định về điều khoản sử dụng dịch vụ AFY</h2><p>Trước khi đăng ký tài khoản để sử dụng dịch vụ tại <strong>AFY</strong>, Người Sử Dụng xác nhận đã đọc, hiểu và đồng ý với tất cả các quy định trong <strong>Thỏa Thuận Cung Cấp và Sử Dụng Dịch Vụ AFY</strong> (sau đây gọi tắt là “Thỏa thuận”) thông qua việc hoàn thành việc đăng ký tài khoản AFY.</p><p>Khi xem xét việc sử dụng dịch vụ, Người Sử Dụng cam kết rằng:</p><ul><li><p>Người Sử Dụng có đủ tuổi theo luật định hoặc đã có sự chấp thuận của cha mẹ/người giám hộ hợp pháp.</p></li><li><p>Người Sử Dụng sẽ cung cấp thông tin chính xác, trung thực và chịu trách nhiệm về các thông tin đó.</p></li></ul><p>Mọi nội dung, dữ liệu, văn bản, hình ảnh, âm thanh, video… do Người Sử Dụng đăng tải đều thuộc trách nhiệm của chính Người Sử Dụng. <strong>AFY</strong> có hệ thống kiểm duyệt nội dung và cơ chế xử lý vi phạm theo quy định pháp luật Việt Nam.</p><p><strong>Thông tin liên hệ hỗ trợ 24/7:</strong><br>📧 Email: <a target="_blank" rel="noopener noreferrer nofollow" href="mailto:support@afy.com">support@afy.com</a></p><hr><h2>Điều 2: Đăng ký tài khoản</h2><p>Người Sử Dụng không được:</p><ul><li><p>Đặt tên tài khoản trùng hoặc tương tự gây nhầm lẫn với tên danh nhân, lãnh đạo, tội phạm, tổ chức phản động, cơ quan nhà nước, tổ chức chính trị – xã hội, hoặc tổ chức quốc tế.</p></li><li><p>Sử dụng hình ảnh vi phạm thuần phong mỹ tục, kích động bạo lực, dâm ô, đồi trụy, hoặc xúc phạm tổ chức/cá nhân khác.</p></li><li><p>Giả mạo, xâm phạm quyền sở hữu trí tuệ.</p></li></ul><p>Tài khoản vi phạm sẽ bị khóa và/hoặc xóa vĩnh viễn mà không cần thông báo trước.</p><hr><h2>Điều 3: Nội dung cung cấp, trao đổi thông tin</h2><p>Người Sử Dụng có thể cung cấp, chia sẻ nội dung trên AFY với điều kiện:</p><ul><li><p>Nội dung phù hợp với pháp luật Việt Nam và quy định của AFY.</p></li><li><p>Không vi phạm bản quyền, quyền sở hữu trí tuệ.</p></li><li><p>Ban quản trị có quyền chỉnh sửa, biên soạn, sử dụng nội dung Người Sử Dụng cung cấp.</p></li></ul><p>AFY không chịu trách nhiệm về tính chính xác, hữu ích, an toàn của nội dung do người dùng khác đăng tải.</p><hr><h2>Điều 4: Các nội dung cấm trao đổi, chia sẻ</h2><p>Người Sử Dụng không được đăng tải, chia sẻ các thông tin:</p><ul><li><p>Chống phá Nhà nước Cộng hòa xã hội chủ nghĩa Việt Nam.</p></li><li><p>Kích động chiến tranh, khủng bố, bạo lực, phân biệt chủng tộc, tôn giáo.</p></li><li><p>Dâm ô, đồi trụy, mê tín dị đoan, phá hoại thuần phong mỹ tục.</p></li><li><p>Xuyên tạc, xúc phạm tổ chức, cá nhân.</p></li><li><p>Vi phạm quyền sở hữu trí tuệ.</p></li><li><p>Quảng bá, mua bán dịch vụ/hàng hóa bị cấm.</p></li></ul><hr><h2>Điều 5: Các hành vi bị cấm khác</h2><p>Người Sử Dụng không được:</p><ul><li><p>Cản trở trái phép hệ thống mạng, tấn công máy chủ, phát tán virus, phần mềm độc hại.</p></li><li><p>Đăng nhập trái phép, sử dụng trái phép dữ liệu, mật khẩu của người khác.</p></li><li><p>Lợi dụng AFY để spam, lừa đảo, quảng bá trái phép, phá hoại uy tín của AFY.</p></li><li><p>Quấy rối, chửi bới, làm phiền người sử dụng khác.</p></li></ul><hr><h2>Điều 6: Quyền và nghĩa vụ của Người Sử Dụng</h2><h3>Quyền</h3><ul><li><p>Đăng ký, thay đổi, bổ sung thông tin cá nhân.</p></li><li><p>Được bảo mật thông tin cá nhân theo Chính sách bảo mật của AFY.</p></li></ul><h3>Nghĩa vụ</h3><ul><li><p>Đăng ký đầy đủ thông tin chính xác, trung thực.</p></li><li><p>Bảo mật tài khoản, mật khẩu, thông tin cá nhân.</p></li><li><p>Thông báo ngay cho AFY nếu phát hiện hành vi sử dụng trái phép tài khoản.</p></li><li><p>Chịu trách nhiệm trước pháp luật Việt Nam về mọi nội dung chia sẻ.</p></li></ul><hr><h2>Điều 7: Quyền và trách nhiệm của AFY</h2><h3>Quyền</h3><ul><li><p>Khóa hoặc xóa tài khoản nếu Người Sử Dụng vi phạm điều khoản.</p></li><li><p>Từ chối hỗ trợ các tài khoản cung cấp thông tin không chính xác.</p></li><li><p>Báo cáo vi phạm cho cơ quan chức năng theo quy định pháp luật.</p></li></ul><h3>Trách nhiệm</h3><ul><li><p>Hỗ trợ Người Sử Dụng trong quá trình sử dụng dịch vụ.</p></li><li><p>Giải quyết khiếu nại, tranh chấp theo thẩm quyền.</p></li><li><p>Bảo mật thông tin cá nhân của Người Sử Dụng, không bán hoặc trao đổi cho bên thứ ba (trừ khi có yêu cầu từ cơ quan chức năng).</p></li></ul><hr><h2>Điều 8: Quyền sở hữu trí tuệ</h2><p>AFY sở hữu toàn bộ quyền sở hữu trí tuệ đối với các dịch vụ, nội dung, thương hiệu, hình ảnh, thiết kế, mã nguồn trên nền tảng. Người Sử Dụng không được sao chép, phát tán, sử dụng nếu không có sự đồng ý bằng văn bản từ AFY.</p><hr><h2>Điều 9: Xử lý vi phạm</h2><p>Tùy mức độ vi phạm, AFY có thể:</p><ul><li><p>Khóa tài khoản tạm thời (7 ngày hoặc 30 ngày).</p></li><li><p>Khóa tài khoản vĩnh viễn.</p></li><li><p>Cung cấp thông tin vi phạm cho cơ quan chức năng xử lý theo pháp luật Việt Nam.</p></li></ul><hr><h2>Điều 10: Cảnh báo rủi ro</h2><p>Khi chia sẻ thông tin trên AFY, Người Sử Dụng có thể vô tình để lộ thông tin cá nhân (địa chỉ, số điện thoại, email…). AFY cam kết bảo mật tối đa thông tin cá nhân nhưng không thể đảm bảo tuyệt đối trong môi trường Internet. Người Sử Dụng cần thận trọng khi chia sẻ dữ liệu.</p><hr><h2>Điều 11: Thu thập và bảo vệ thông tin cá nhân</h2><ul><li><p><strong>Thông tin thu thập:</strong> họ tên, ngày sinh, email, số điện thoại, CCCD/CMND, mật khẩu, địa chỉ IP, nhật ký hoạt động.</p></li><li><p><strong>Mục đích sử dụng:</strong> xác thực tài khoản, cung cấp dịch vụ, hỗ trợ khách hàng.</p></li><li><p><strong>Thời gian lưu trữ:</strong> tối thiểu 02 năm.</p></li><li><p><strong>Người quản lý dữ liệu:</strong> AFY.</p></li></ul><p>📍 <strong>Địa chỉ:</strong> [địa chỉ công ty AFY]<br>📧 <strong>Email:</strong> <a target="_blank" rel="noopener noreferrer nofollow" href="mailto:support@afy.com">support@afy.com</a><br>📞 <strong>Điện thoại:</strong> [hotline AFY]</p><p>Người Sử Dụng có quyền truy cập, chỉnh sửa, xóa thông tin cá nhân hoặc gửi khiếu nại đến AFY.</p><hr><h2>Điều 12: Miễn trừ trách nhiệm và bồi thường</h2><p>Người Sử Dụng đồng ý miễn trừ AFY khỏi mọi trách nhiệm phát sinh từ hành vi vi phạm điều khoản sử dụng.<br>Người Sử Dụng phải bồi thường mọi thiệt hại cho AFY trong trường hợp hành vi vi phạm gây tổn thất về tài sản, uy tín.</p><hr><h2>Điều 13: Luật áp dụng</h2><p>Thỏa thuận này được điều chỉnh bởi pháp luật Việt Nam. Mọi tranh chấp sẽ được giải quyết tại tòa án có thẩm quyền tại Việt Nam.</p><hr><h2>Điều 14: Giải quyết khiếu nại, tranh chấp</h2><ul><li><p>Người Sử Dụng có thể gửi khiếu nại trong vòng 10 ngày kể từ khi xảy ra tranh chấp.</p></li><li><p>AFY sẽ tiếp nhận, kiểm tra và xử lý trong thời hạn hợp lý.</p></li><li><p>Nếu không giải quyết được, vụ việc sẽ được đưa ra cơ quan pháp luật có thẩm quyền.</p></li></ul><hr><h2>Điều 15: Hiệu lực của Thỏa thuận</h2><p>Thỏa thuận này có hiệu lực kể từ khi Người Sử Dụng hoàn tất đăng ký tài khoản AFY.</p><p>AFY có quyền sửa đổi, bổ sung Điều khoản sử dụng bất kỳ lúc nào. Thông tin cập nhật sẽ được công bố trên hệ thống của AFY.</p>',
                ],
                [
                    'title' => 'Chính sách bảo mật',
                    'excerpt' => 'Cách chúng tôi thu thập và bảo vệ dữ liệu cá nhân.',
                    'content' => '<h1>🔒 Chính sách bảo mật – AFY</h1><p>AFY (sau đây được gọi là &quot;chúng tôi&quot;) tôn trọng quyền riêng tư của người dùng. Tại <a target="_blank" rel="noopener noreferrer nofollow" href="http://afy.vn"><strong>afy.vn</strong></a>, cách chúng tôi thu thập, sử dụng và bảo vệ dữ liệu người dùng tuân theo Chính sách bảo mật này. Chính sách này áp dụng cho trang web chính của chúng tôi (<a target="_blank" rel="noopener noreferrer nofollow" href="http://afy.vn"><strong>afy.vn</strong></a>) và bất kỳ trang web hoặc ứng dụng di động chính thức nào khác của AFY.</p><p>Vui lòng đọc kỹ Chính sách bảo mật này và không truy cập website nếu bạn không đồng ý với các điều khoản nêu tại đây.</p><p>Chúng tôi có quyền thay đổi Chính sách bảo mật này bất kỳ lúc nào. Thời gian cập nhật gần nhất sẽ được hiển thị rõ ràng trên website. Mọi thay đổi sẽ có hiệu lực ngay khi được đăng tải.</p><hr><h2>1. Thu thập thông tin của bạn</h2><h3>Dữ liệu cá nhân</h3><p>Chúng tôi có thể thu thập các thông tin định danh như:</p><ul><li><p>Họ tên, ngày sinh, email, số điện thoại, ảnh đại diện, quê quán, sở thích, nền tảng giáo dục</p></li><li><p>Lượt thích, bình luận, nội dung bạn cung cấp khi tạo tài khoản, để lại đánh giá hoặc tham gia các hoạt động khác trên website</p></li></ul><p>Một số thông tin là bắt buộc để bạn tham gia, số khác do bạn tự nguyện cung cấp. Nếu bạn từ chối, có thể sẽ không sử dụng được một số tính năng.</p><h3>Dữ liệu dẫn xuất</h3><p>Tự động thu thập qua hệ thống:</p><ul><li><p>Địa chỉ IP</p></li><li><p>Vị trí</p></li><li><p>Trình duyệt, thiết bị, hệ điều hành</p></li></ul><h3>Quyền của Facebook &amp; Google</h3><p>Nếu bạn đăng nhập bằng tài khoản <strong>Facebook</strong> hoặc <strong>Google</strong>, chúng tôi có thể truy cập các thông tin công khai cơ bản (tên, giới tính, ngày sinh, ảnh hồ sơ, email).</p><h3>Dữ liệu thiết bị di động</h3><p>Khi bạn truy cập từ điện thoại, chúng tôi có thể thu thập <strong>ID thiết bị, loại máy, nhà sản xuất, vị trí</strong>.</p><hr><h2>2. Cách sử dụng thông tin của bạn</h2><p>Thông tin được sử dụng nhằm:</p><ul><li><p>Quản lý và tối ưu tài khoản</p></li><li><p>Phân tích dữ liệu (ẩn danh) phục vụ nội bộ</p></li><li><p>Gửi email thông báo, bản tin định kỳ</p></li><li><p>Giải quyết tranh chấp, hỗ trợ khách hàng</p></li><li><p>Phục vụ quảng cáo, gợi ý dịch vụ phù hợp</p></li></ul><hr><h2>3. Chia sẻ thông tin của bạn</h2><ul><li><p><strong>Nhà cung cấp dịch vụ bên thứ ba:</strong> Không chia sẻ thông tin nếu không cần thiết.</p></li><li><p><strong>Người dùng khác:</strong> Bạn có thể nhìn thấy tên, avatar, bút danh của thành viên khác.</p></li><li><p><strong>Bài đăng công khai:</strong> Bình luận, bài viết sẽ hiển thị công khai.</p></li><li><p><strong>Nhà quảng cáo:</strong> Chúng tôi có thể hợp tác với bên thứ ba để hiển thị quảng cáo phù hợp.</p></li><li><p><strong>Đối tác kinh doanh &amp; Affiliates:</strong> Có thể chia sẻ dữ liệu trong phạm vi pháp luật cho phép.</p></li></ul><hr><h2>4. Công nghệ theo dõi</h2><ul><li><p><strong>Cookies:</strong> Website sử dụng cookies để cải thiện trải nghiệm. Bạn có thể tắt cookies, nhưng có thể ảnh hưởng tới chức năng.</p></li><li><p><strong>Quảng cáo trực tuyến:</strong> Có thể sử dụng phần mềm của bên thứ ba để phân phát quảng cáo, email marketing.</p></li><li><p><strong>Google Analytics &amp; công cụ thống kê:</strong> Chúng tôi sử dụng dữ liệu ẩn danh để phân tích hành vi người dùng.</p></li></ul><hr><h2>5. Liên kết bên thứ ba</h2><p>AFY có thể chứa liên kết tới website/dịch vụ khác. Khi bạn truy cập các link này, thông tin cung cấp cho bên thứ ba sẽ không thuộc phạm vi của Chính sách này.</p><hr><h2>6. Bảo mật thông tin</h2><p>Chúng tôi áp dụng biện pháp kỹ thuật &amp; hành chính để bảo vệ dữ liệu. Tuy nhiên, không có hệ thống nào an toàn tuyệt đối. Thông tin truyền qua internet có thể bị truy cập trái phép, do đó chúng tôi <strong>không thể đảm bảo 100% an toàn tuyệt đối</strong>.</p><hr><h2>7. Quyền lựa chọn của bạn</h2><ul><li><p><strong>Thông tin tài khoản:</strong> Bạn có thể thay đổi hoặc yêu cầu xóa tài khoản bất kỳ lúc nào.</p></li><li><p><strong>Email &amp; liên hệ:</strong> Nếu không muốn nhận bản tin, bạn có thể hủy đăng ký hoặc liên hệ trực tiếp với chúng tôi.</p></li></ul><hr><h2>8. Liên hệ</h2><p>Mọi câu hỏi liên quan đến Chính sách bảo mật vui lòng liên hệ:</p><p>📧 <a target="_blank" rel="noopener noreferrer nofollow" href="mailto:privacy@afy.vn"><strong>privacy@afy.vn</strong></a></p>',
                ],
                [
                    'title' => 'Quy định đăng tin',
                    'excerpt' => 'Quy định khi đăng tải nội dung và địa điểm.',
                    'content' => '<h1>Quy định đăng tin trên AFY</h1><p>Để đảm bảo tính minh bạch, hữu ích và văn minh trong cộng đồng <strong>AFY</strong>, tất cả người dùng khi đăng tải nội dung (bao gồm bài viết, hình ảnh, đánh giá, bình luận…) đều phải tuân thủ các quy định sau:</p><hr><h2>1. Nội dung được phép đăng</h2><ul><li><p>Bài viết, đánh giá, hình ảnh liên quan trực tiếp đến cửa hàng, địa điểm dịch vụ (ăn uống, lưu trú, vui chơi, giải trí…).</p></li><li><p>Chia sẻ trải nghiệm cá nhân, cảm nhận, góp ý chân thực.</p></li><li><p>Hình ảnh rõ ràng, chính chủ hoặc có quyền sử dụng, không vi phạm bản quyền.</p></li><li><p>Nội dung mang tính xây dựng, giúp ích cho cộng đồng trong việc lựa chọn địa điểm.</p></li></ul><hr><h2>2. Nội dung bị nghiêm cấm</h2><ul><li><p>Thông tin sai sự thật, bịa đặt, xuyên tạc hoặc bôi nhọ uy tín cửa hàng, cá nhân, tổ chức.</p></li><li><p>Nội dung vi phạm pháp luật Việt Nam (chống phá Nhà nước, tuyên truyền bạo lực, phân biệt chủng tộc, tôn giáo, khiêu dâm, cờ bạc, ma túy…).</p></li><li><p>Hình ảnh đồi trụy, phản cảm, gây kích động, hoặc không liên quan đến địa điểm.</p></li><li><p>Spam, quảng cáo trá hình, link dẫn đến website/dịch vụ bên ngoài khi chưa được AFY chấp thuận.</p></li><li><p>Nội dung vi phạm thuần phong mỹ tục, trái với chuẩn mực cộng đồng.</p></li></ul><hr><h2>3. Quy định về hình ảnh và video</h2><ul><li><p>Hình ảnh/video phải đúng với địa điểm được đánh giá.</p></li><li><p>Không sử dụng hình ảnh bị chỉnh sửa quá mức gây sai lệch thực tế.</p></li><li><p>Không đăng ảnh chứa thông tin cá nhân nhạy cảm của người khác (CMND/CCCD, biển số xe, địa chỉ nhà riêng…) nếu chưa được sự đồng ý.</p></li><li><p>Kích thước và định dạng file phải theo quy định của hệ thống (tự động resize, giới hạn dung lượng).</p></li></ul><hr><h2>4. Trách nhiệm của người đăng tin</h2><ul><li><p>Người dùng tự chịu trách nhiệm trước pháp luật và cộng đồng về nội dung mình đăng.</p></li><li><p>Cam kết nội dung đăng tải là chính xác, khách quan và không xâm phạm quyền lợi của bất kỳ bên thứ ba nào.</p></li><li><p>Đồng ý để AFY được quyền hiển thị, lưu trữ, chỉnh sửa, gỡ bỏ nội dung khi phát hiện vi phạm hoặc có yêu cầu từ cơ quan chức năng.</p></li></ul><hr><h2>5. Cơ chế xử lý vi phạm</h2><p>Tùy theo mức độ vi phạm, AFY có quyền:</p><ul><li><p>Gỡ bỏ bài viết, hình ảnh, bình luận không phù hợp mà không cần báo trước.</p></li><li><p>Cảnh cáo, khóa tài khoản tạm thời (7 ngày, 30 ngày) hoặc vĩnh viễn.</p></li><li><p>Chuyển thông tin cho cơ quan chức năng xử lý theo quy định pháp luật Việt Nam.</p></li></ul><hr><h2>6. Khuyến nghị cho người dùng</h2><ul><li><p>Hãy viết đánh giá trung thực, lịch sự, tôn trọng.</p></li><li><p>Hãy đăng hình ảnh thực tế để cộng đồng có thông tin chính xác.</p></li><li><p>Hãy sử dụng ngôn ngữ rõ ràng, không tục tĩu, không gây kích động.</p></li></ul>',
                ],
                [
                    'title' => 'Quy chế hoạt động',
                    'excerpt' => 'Quy chế vận hành và trách nhiệm các bên.',
                    'content' => '<h1>QUY CHẾ HOẠT ĐỘNG CỦA AFY</h1><h2>Điều 1. Giới thiệu chung</h2><ul><li><p><strong>AFY</strong> là nền tảng trực tuyến (website và ứng dụng di động) cho phép người dùng tìm kiếm, đánh giá và chia sẻ thông tin về các cửa hàng, địa điểm ăn uống, lưu trú, vui chơi và dịch vụ tại Việt Nam.</p></li><li><p>Quy chế hoạt động này quy định quyền và nghĩa vụ của <strong>người dùng</strong> và <strong>ban quản trị AFY</strong>, nhằm đảm bảo một môi trường minh bạch, văn minh và đúng pháp luật.</p></li><li><p>Khi tham gia sử dụng dịch vụ, người dùng được coi là đã đọc, hiểu và đồng ý tuân thủ Quy chế này.</p></li></ul><hr><h2>Điều 2. Phạm vi cung cấp dịch vụ</h2><ul><li><p>Cung cấp công cụ tìm kiếm và lọc địa điểm theo vị trí, danh mục, tiện ích, giờ mở cửa.</p></li><li><p>Cho phép người dùng đăng ký tài khoản, đăng bài viết, đánh giá, chia sẻ hình ảnh/video liên quan đến địa điểm.</p></li><li><p>Cung cấp công cụ bản đồ để hiển thị vị trí và tính toán khoảng cách từ vị trí của người dùng.</p></li><li><p>Hỗ trợ quản lý thông tin cửa hàng, phân loại và hiển thị dữ liệu dựa trên đánh giá của cộng đồng.</p></li></ul><hr><h2>Điều 3. Quyền và nghĩa vụ của người dùng</h2><h3>3.1 Quyền của người dùng</h3><ul><li><p>Được đăng ký tài khoản miễn phí và sử dụng các chức năng cơ bản của hệ thống.</p></li><li><p>Được quyền đăng tải, chia sẻ, đánh giá cửa hàng theo đúng quy định.</p></li><li><p>Được bảo mật thông tin cá nhân theo Chính sách bảo mật của AFY.</p></li><li><p>Được quyền khiếu nại, phản ánh các nội dung vi phạm hoặc hành vi gây hại.</p></li></ul><h3>3.2 Nghĩa vụ của người dùng</h3><ul><li><p>Cung cấp thông tin chính xác khi đăng ký tài khoản.</p></li><li><p>Chịu trách nhiệm về nội dung do mình đăng tải (bài viết, bình luận, hình ảnh, video).</p></li><li><p>Không sử dụng AFY cho các mục đích vi phạm pháp luật, phát tán thông tin sai sự thật, xuyên tạc, bôi nhọ, khiêu dâm, kích động, cờ bạc, lừa đảo.</p></li><li><p>Tuân thủ <strong>Quy định đăng tin</strong> và các quy định khác của AFY.</p></li></ul><hr><h2>Điều 4. Quyền và trách nhiệm của AFY</h2><ul><li><p>Xây dựng, duy trì nền tảng hoạt động ổn định, an toàn, bảo mật.</p></li><li><p>Có quyền kiểm duyệt, chỉnh sửa hoặc gỡ bỏ nội dung vi phạm mà không cần báo trước.</p></li><li><p>Cảnh cáo, khóa tạm thời hoặc vĩnh viễn tài khoản vi phạm.</p></li><li><p>Hợp tác với cơ quan nhà nước khi có yêu cầu điều tra, xử lý hành vi vi phạm pháp luật.</p></li><li><p>Cam kết không bán hoặc tiết lộ thông tin cá nhân của người dùng cho bên thứ ba khi chưa có sự đồng ý, trừ trường hợp pháp luật quy định.</p></li></ul><hr><h2>Điều 5. Cơ chế xử lý vi phạm</h2><ul><li><p>Gỡ bỏ ngay lập tức nội dung vi phạm pháp luật, vi phạm thuần phong mỹ tục hoặc quy định cộng đồng.</p></li><li><p>Áp dụng hình thức xử phạt theo mức độ: cảnh cáo, khóa tài khoản 7 ngày, 30 ngày, hoặc khóa vĩnh viễn.</p></li><li><p>Trường hợp nghiêm trọng, cung cấp thông tin cho cơ quan chức năng để xử lý theo pháp luật.</p></li></ul><hr><h2>Điều 6. Cơ chế giải quyết khiếu nại, tranh chấp</h2><ul><li><p>Người dùng có quyền gửi khiếu nại về nội dung vi phạm, thông tin sai sự thật hoặc hành vi gây hại qua email hỗ trợ chính thức.</p></li><li><p>Ban quản trị AFY có trách nhiệm tiếp nhận, xác minh và xử lý khiếu nại trong vòng 07 ngày làm việc.</p></li><li><p>Trường hợp không thỏa thuận được, tranh chấp sẽ được giải quyết theo pháp luật Việt Nam tại tòa án có thẩm quyền.</p></li></ul><hr><h2>Điều 7. Quy định bảo mật thông tin</h2><ul><li><p>AFY áp dụng các biện pháp kỹ thuật để bảo vệ dữ liệu người dùng.</p></li><li><p>Người dùng có trách nhiệm giữ bí mật thông tin tài khoản, mật khẩu của mình.</p></li><li><p>AFY không chịu trách nhiệm nếu thông tin cá nhân bị lộ do lỗi của chính người dùng.</p></li></ul><hr><h2>Điều 8. Quyền sở hữu trí tuệ</h2><ul><li><p>Tất cả nội dung, thiết kế, logo, mã nguồn, dữ liệu trên AFY thuộc sở hữu trí tuệ của AFY.</p></li><li><p>Người dùng không được sao chép, phân phối, khai thác cho mục đích thương mại nếu không có sự đồng ý bằng văn bản từ AFY.</p></li></ul><hr><h2>Điều 9. Hiệu lực thi hành</h2><ul><li><p>Quy chế hoạt động này có hiệu lực kể từ ngày công bố trên nền tảng AFY.</p></li><li><p>AFY có quyền sửa đổi, bổ sung Quy chế này bất cứ lúc nào. Các thay đổi sẽ được công bố công khai và có hiệu lực ngay khi đăng tải.</p></li></ul><hr><p>📌 <strong>Liên hệ hỗ trợ</strong></p><ul><li><p>Email: [<a target="_blank" rel="noopener noreferrer nofollow" href="mailto:support@afy.vn">support@afy.vn</a>]</p></li><li><p>Điện thoại: [số điện thoại]</p></li><li><p>Địa chỉ: [địa chỉ công ty/bạn]</p></li></ul>',
                ],
                [
                    'title' => 'Chính sách giải quyết khiếu nại',
                    'excerpt' => 'Quy trình tiếp nhận và xử lý khiếu nại.',
                    'content' => '<h1><strong>Chính sách giải quyết khiếu nại</strong></h1><h2><strong>1. Mục đích</strong></h2><p>Chính sách này nhằm quy định trình tự, cách thức tiếp nhận và xử lý các khiếu nại phát sinh trong quá trình người dùng sử dụng dịch vụ trên nền tảng AFY, đảm bảo quyền lợi chính đáng của người dùng và cam kết minh bạch – công bằng trong hoạt động vận hành.</p><hr><h2><strong>2. Phạm vi áp dụng</strong></h2><p>Chính sách áp dụng cho toàn bộ người dùng có tài khoản trên hệ thống AFY (bao gồm website và ứng dụng di động), trong quá trình sử dụng các tính năng như: đăng bài đánh giá, đăng địa điểm, tương tác, phản hồi nội dung và các dịch vụ liên quan.</p><hr><h2><strong>3. Các trường hợp được tiếp nhận khiếu nại</strong></h2><p>AFY tiếp nhận khiếu nại từ người dùng liên quan đến một hoặc nhiều vấn đề sau:</p><ul><li><p>Nội dung đánh giá/bình luận sai sự thật, vu khống hoặc bôi nhọ danh dự.</p></li><li><p>Nội dung vi phạm pháp luật, xúc phạm cá nhân, tổ chức.</p></li><li><p>Hình ảnh, video có tính chất phản cảm, lừa đảo, vi phạm bản quyền.</p></li><li><p>Hành vi giả mạo, sử dụng trái phép tài khoản, đánh giá ảo.</p></li><li><p>Bị xâm phạm quyền riêng tư, thông tin cá nhân.</p></li><li><p>Các lỗi kỹ thuật ảnh hưởng đến quyền lợi người dùng (mất dữ liệu, thao tác lỗi, đăng tin không hiển thị,…).</p></li></ul><hr><h2><strong>4. Kênh tiếp nhận khiếu nại</strong></h2><p>Người dùng gửi khiếu nại thông qua các hình thức sau:</p><ul><li><p><strong>Email</strong>: gửi về địa chỉ [<a target="_blank" rel="noopener noreferrer nofollow" href="mailto:diadiemlongkhanh.com@gmail.com">diadiemlongkhanh.com@gmail.com</a>]</p></li><li><p><strong>Hotline hỗ trợ</strong>: [0792 339 233]</p></li><li><p><strong>Mẫu liên hệ</strong>: tại mục “Liên hệ” hoặc “Hỗ trợ” trên website/app</p></li><li><p><strong>Gửi trực tiếp tại trụ sở</strong> (nếu có): 50 Nguyễn Thái Học, P. Xuân An, Long Khánh, Đồng Nai</p></li></ul><hr><h2><strong>5. Quy trình xử lý khiếu nại</strong></h2><h3><strong>Bước 1 – Tiếp nhận khiếu nại</strong></h3><p>Người dùng cung cấp đầy đủ các thông tin:</p><ul><li><p>Tên tài khoản đăng ký</p></li><li><p>Số điện thoại/email liên hệ</p></li><li><p>Nội dung khiếu nại chi tiết</p></li><li><p>Hình ảnh, video, bằng chứng liên quan (nếu có)</p></li></ul><blockquote><p><strong>Thời hạn tiếp nhận</strong>: Trong vòng <strong>10 ngày</strong> kể từ khi sự việc phát sinh.</p></blockquote><hr><h3><strong>Bước 2 – Xác minh và đánh giá</strong></h3><p>Ban quản trị AFY sẽ:</p><ul><li><p>Xác minh nội dung khiếu nại</p></li><li><p>Kiểm tra lịch sử hoạt động/tương tác của người dùng liên quan</p></li><li><p>Liên hệ lại người khiếu nại nếu cần bổ sung thêm thông tin</p></li></ul><hr><h3><strong>Bước 3 – Phản hồi và xử lý</strong></h3><ul><li><p>Thời gian xử lý: <strong>Tối đa 7 ngày làm việc</strong> kể từ khi tiếp nhận đầy đủ thông tin.</p></li><li><p>Trường hợp đơn giản: xử lý trong vòng 48 giờ.</p></li><li><p>Kết quả xử lý sẽ được thông báo qua email hoặc số điện thoại đã đăng ký.</p></li></ul><hr><h3><strong>Bước 4 – Giải pháp</strong></h3><p>Tùy theo tính chất khiếu nại, các giải pháp có thể bao gồm:</p><ul><li><p>Gỡ bỏ nội dung vi phạm</p></li><li><p>Cảnh cáo hoặc khóa tài khoản người vi phạm</p></li><li><p>Khôi phục nội dung, quyền lợi hợp lệ của người bị ảnh hưởng</p></li><li><p>Chuyển vụ việc đến cơ quan chức năng nếu có dấu hiệu vi phạm pháp luật</p></li></ul><hr><h2><strong>6. Cam kết của AFY</strong></h2><ul><li><p>Xử lý trung thực, khách quan, đúng pháp luật</p></li><li><p>Bảo mật tuyệt đối thông tin người khiếu nại</p></li><li><p>Hợp tác với cơ quan chức năng nếu cần thiết</p></li><li><p>Không thu bất kỳ khoản phí nào cho việc tiếp nhận và giải quyết khiếu nại</p></li></ul><hr><h2><strong>7. Giải quyết tranh chấp pháp lý</strong></h2><p>Nếu người dùng và AFY không thể đạt được thỏa thuận trong việc xử lý khiếu nại, vụ việc sẽ được đưa ra <strong>tòa án có thẩm quyền tại Việt Nam</strong> để giải quyết theo quy định pháp luật hiện hành.</p>',
                ],
                [
                    'title' => 'Trung tâm khách hàng',
                    'excerpt' => 'Nguồn trợ giúp và liên hệ hỗ trợ khách hàng.',
                    'content' => '<h1>Trung tâm khách hàng – AFY</h1><h2>1. Kênh liên hệ chính thức</h2><ul><li><p><strong>Email hỗ trợ:</strong> <a target="_blank" rel="noopener noreferrer nofollow" href="mailto:support@afy.vn">support@afy.vn</a></p></li><li><p><strong>Điện thoại/Zalo:</strong> <a target="_blank" rel="noopener noreferrer nofollow" href="http://0xxx.xxx.xxx">0xxx.xxx.xxx</a> (giờ hành chính)</p></li><li><p><strong>Form liên hệ:</strong> có sẵn trên ứng dụng/web tại mục “Hỗ trợ khách hàng”</p></li><li><p><strong>Địa chỉ văn phòng:</strong> [Địa chỉ công ty bạn]</p></li></ul><hr><h2>2. Dịch vụ hỗ trợ</h2><ul><li><p><strong>Hướng dẫn sử dụng:</strong> Cách đăng ký, đăng nhập, tìm kiếm và đánh giá cửa hàng.</p></li><li><p><strong>Báo lỗi hệ thống:</strong> Tiếp nhận phản hồi về lỗi ứng dụng, lỗi hiển thị, hoặc vấn đề hiệu năng.</p></li><li><p><strong>Khiếu nại &amp; phản hồi:</strong> Giải quyết tranh chấp liên quan đến đánh giá, nội dung, thông tin cửa hàng.</p></li><li><p><strong>Tư vấn tài khoản:</strong> Hỗ trợ thay đổi, khôi phục mật khẩu, xác minh thông tin.</p></li></ul><hr><h2>3. Thời gian làm việc</h2><ul><li><p><strong>Hỗ trợ trực tuyến:</strong> 08:00 – 22:00, tất cả các ngày trong tuần.</p></li><li><p><strong>Xử lý khiếu nại:</strong> trong vòng <strong>02 ngày làm việc</strong> kể từ khi tiếp nhận.</p></li></ul><hr><h2>4. Cam kết dịch vụ</h2><ul><li><p>Phản hồi nhanh chóng, minh bạch, khách quan.</p></li><li><p>Bảo mật tuyệt đối thông tin người dùng.</p></li><li><p>Luôn lắng nghe và cải tiến dịch vụ dựa trên góp ý của khách hàng.</p></li></ul>',
                ],
                [
                    'title' => 'Truyền thông',
                    'excerpt' => 'Thông tin hợp tác truyền thông và báo chí.',
                    'content' => '<h1>📰 Truyền thông – AFY</h1><h2>1. Thông tin chính thức từ AFY</h2><ul><li><p><strong>Thông cáo báo chí:</strong> Cập nhật các sự kiện, sản phẩm/dịch vụ mới, hợp tác chiến lược.</p></li><li><p><strong>Tin tức &amp; sự kiện:</strong> Hoạt động nổi bật của AFY, chương trình ưu đãi, sự kiện cộng đồng.</p></li><li><p><strong>Bản tin công nghệ:</strong> Chia sẻ kiến thức, xu hướng công nghệ và trải nghiệm khách hàng trong lĩnh vực đánh giá cửa hàng.</p></li></ul><hr><h2>2. Quy định về truyền thông</h2><ul><li><p>AFY chỉ công bố thông tin chính thức thông qua <strong>website <a target="_blank" rel="noopener noreferrer nofollow" href="http://afy.vn">afy.vn</a></strong>, các kênh mạng xã hội được xác minh, và email: <a target="_blank" rel="noopener noreferrer nofollow" href="mailto:media@afy.vn"><strong>media@afy.vn</strong></a>.</p></li><li><p>Mọi nội dung truyền thông bên ngoài (báo chí, báo mạng, blog…) liên quan đến AFY cần được xác minh nguồn.</p></li><li><p>Người dùng được khuyến khích chia sẻ trải nghiệm thực tế trên AFY, nhưng không được xuyên tạc, bịa đặt hoặc sử dụng thương hiệu AFY sai mục đích.</p></li></ul><hr><h2>3. Quan hệ báo chí &amp; hợp tác</h2><ul><li><p><strong>Kênh liên hệ báo chí:</strong> <a target="_blank" rel="noopener noreferrer nofollow" href="mailto:media@afy.vn">media@afy.vn</a></p></li><li><p><strong>Đối tác truyền thông:</strong> Liên hệ để hợp tác quảng bá, tài trợ sự kiện, hoặc phát triển nội dung.</p></li><li><p><strong>Chính sách minh bạch:</strong> AFY cam kết cung cấp thông tin trung thực, chính xác, kịp thời đến báo chí và công chúng.</p></li></ul><hr><h2>4. Giá trị truyền thông của AFY</h2><ul><li><p><strong>Minh bạch:</strong> Đưa tin chính xác, rõ ràng.</p></li><li><p><strong>Kết nối:</strong> Tạo cầu nối giữa người dùng, cửa hàng và cộng đồng.</p></li><li><p><strong>Lan tỏa tích cực:</strong> Khuyến khích chia sẻ trải nghiệm chân thực, văn minh.</p></li></ul>',
                ],
            ];

            foreach ($pages as $page) {
                $slug = Str::slug($page['title']);
                Article::query()->updateOrCreate(
                    ['slug' => $slug],
                    [
                        'title'        => $page['title'],
                        'content'      => $page['content'],
                        'author'       => 'ADMIN AFY',
                        'type'         => ArticleType::FIXED->value,
                        'status'       => ArticleStatus::PUBLISHED->value,
                        'view'         => 0,
                        'sort'         => 0,
                        'image_path'   => null,
                        'seo_title'    => $page['title'],
                        'seo_description' => $page['excerpt'],
                        'seo_keywords' => null,
                    ]
                );
            }

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
