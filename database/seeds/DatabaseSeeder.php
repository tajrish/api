<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::statement("SET foreign_key_checks = 0");

        $this->createProvinces();

        $this->createCategories();

        $this->createPins();

        $this->createBuses();

        $this->createTrains();

        $this->createPlanes();

        $this->createUsers();

        $this->createDailyChallenge();

        Model::reguard();
    }

    protected function createDailyChallenge()
    {
        $province = \Tajrish\Models\Province::where('name', 'تهران')->firstOrFail();
        $pin = \Tajrish\Models\Pin::where('province_id', $province->id)->firstOrFail();
        \Tajrish\Models\Challenge::create([
            'title' => 'بازدید از عمارت مسعودیه در جمعه',
            'description' => 'با مشاهده عمارت مسعودیه در روز جمعه جایزه بگیرید.',
            'pin_id' => $pin->id,
            'starts_at' => \Carbon\Carbon::now(),
            'ends_at' => \Carbon\Carbon::now()->addDay()
        ]);
    }

    protected function createPins()
    {
        $items = [
            'اصفهان' => [
                'اماکن تاریخی' => [
                    'آتشگاه اصفهان',
                    'آرامگاه الراشد بالله',
                    'آرامگاه پیربکران',
                    'آرامگاه سلطان بخت آقا',
                    'سی و سه پل'
                ],
            ],
            'تهران' => [
                'اماکن تاریخی' => [
                    'عمارت مسعودیه',
                    'کاخ نیاوران'
                ]
            ]
        ];

        foreach ($items as $provinceName => $categories) {
            $province = \Tajrish\Models\Province::where('name', $provinceName)->firstOrFail();
            foreach ($categories as $categoryTitle => $pines) {
                $category = \Tajrish\Models\Category::where('title', $categoryTitle)->firstOrFail();
                foreach ($pines as $data) {
                    \Tajrish\Models\Pin::create(array_merge([
                        'category_id' => $category->id,
                        'province_id' => $province->id
                    ], ['title' => $data]));
                }
            }
        }
    }

    protected function createCategories()
    {
        \Tajrish\Models\Category::truncate();

        $categories = [
            'مرکز خرید',
            'مناظر طبیعی',
            'کوه',
            'بوستان',
            'باغ وحش',
            'اماکن تاریخی',
            'استادیوم',
            'مجموعه ورزشی',
            'زیارتی'
        ];

        foreach ($categories as $title) {
            \Tajrish\Models\Category::create(['title' => $title]);
        }
    }

    protected function createProvinces()
    {
        \Tajrish\Models\Province::truncate();

        $provinces = [
            'اصفهان',
            'مشهد',
            'تهران',
            'خوزستان',
            'فارس',
            'همدان'
        ];

        foreach ($provinces as $title) {
            \Tajrish\Models\Province::create(['name' => $title]);
        }
    }

    protected function createBuses()
    {
        /** @var \Tajrish\Models\Bus $instance */
        $instance = new \Tajrish\Models\Bus;

        $now = \Carbon\Carbon::now();

        $instance->create([
            'price' => 40000,
            'provider' => 'سیر و سفر',
            'title' => 'اتوبوس دوطبقه اسکانیا',
            'start_city' => 'تهران',
            'destination_city' => 'مشهد',
            'start_province_id' => 3,
            'destination_province_id' => 2,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 120000,
            'provider' => 'راه پیما',
            'title' => 'اتوبوس لوکس',
            'start_city' => 'تهران',
            'destination_city' => 'مشهد',
            'start_province_id' => 3,
            'destination_province_id' => 2,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(3)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 55000,
            'provider' => 'ایران پیما',
            'title' => 'اتوبوس اسکانیا',
            'start_city' => 'تهران',
            'destination_city' => 'مشهد',
            'start_province_id' => 3,
            'destination_province_id' => 2,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 25000,
            'provider' => 'سریع السیر',
            'title' => 'اتوبوس ۳۰۴',
            'start_city' => 'تهران',
            'destination_city' => 'مشهد',
            'start_province_id' => 3,
            'destination_province_id' => 2,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 25000,
            'provider' => 'مشهد سفر',
            'title' => 'اتوبوس ولوو',
            'start_city' => 'تهران',
            'destination_city' => 'مشهد',
            'start_province_id' => 3,
            'destination_province_id' => 2,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 95000,
            'provider' => 'تهران پیما',
            'title' => 'رفت و برگشت ولوو',
            'start_city' => 'تهران',
            'destination_city' => 'مشهد',
            'start_province_id' => 3,
            'destination_province_id' => 2,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);


        // Here
        $instance->create([
            'price' => 40000,
            'provider' => 'سیر و سفر',
            'title' => 'اتوبوس دوطبقه اسکانیا',
            'start_city' => 'تهران',
            'destination_city' => 'اصفهان',
            'start_province_id' => 3,
            'destination_province_id' => 1,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 120000,
            'provider' => 'راه پیما',
            'title' => 'اتوبوس لوکس',
            'start_city' => 'تهران',
            'destination_city' => 'اصفهان',
            'start_province_id' => 3,
            'destination_province_id' => 1,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(3)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 55000,
            'provider' => 'ایران پیما',
            'title' => 'اتوبوس اسکانیا',
            'start_city' => 'تهران',
            'destination_city' => 'اصفهان',
            'start_province_id' => 3,
            'destination_province_id' => 1,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 25000,
            'provider' => 'سریع السیر',
            'title' => 'اتوبوس ۳۰۴',
            'start_city' => 'تهران',
            'destination_city' => 'اصفهان',
            'start_province_id' => 3,
            'destination_province_id' => 1,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 25000,
            'provider' => 'اصفهان سفر',
            'title' => 'اتوبوس ولوو',
            'start_city' => 'تهران',
            'destination_city' => 'اصفهان',
            'start_province_id' => 3,
            'destination_province_id' => 1,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 95000,
            'provider' => 'تهران پیما',
            'title' => 'رفت و برگشت ولوو',
            'start_city' => 'تهران',
            'destination_city' => 'اصفهان',
            'start_province_id' => 3,
            'destination_province_id' => 1,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $others = ['خوزستان', 'فارس', 'همدان'];
        $each = 5;
        $providers = ['تهران پیما', 'سر و سفر', 'ایران سفر', 'ایران پیما'];
        $title = ['رفت و برگشت ولوو', 'دوطبقه اسکانیا', 'دو طبقه ولوو', 'رفت و برگشت اسکانیا'];
        $prices = [40000, 90000, 80000, 120000];

        foreach ($others as $pro) {
            for($i = 1; $i <= $each; $i++) {
                $instance->create([
                    'price' => $prices[array_rand($prices)],
                    'provider' => $providers[array_rand($providers)],
                    'start_city' => 'تهران',
                    'destination_city' => $pro,
                    'start_province_id' => 3,
                    'title' => $title[array_rand($title)],
                    'destination_province_id' => \Tajrish\Models\Province::where('name', $pro)->firstOrFail()->id,
                    'starts_at' => $now->toDateTimeString(),
                    'ends_at' => $now->addDays(2)->toDateTimeString()
                ]);
            }
        }
    }

    protected function createPlanes()
    {
        /** @var \Tajrish\Models\Bus $instance */
        $instance = new \Tajrish\Models\Plane;

        $now = \Carbon\Carbon::now();

        $instance->create([
            'price' => 650000,
            'provider' => 'علی بابا',
            'title' => 'تابان',
            'start_city' => 'تهران',
            'destination_city' => 'مشهد',
            'start_province_id' => 3,
            'destination_province_id' => 2,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 590000,
            'provider' => 'علی بابا',
            'title' => 'زاگرس',
            'start_city' => 'تهران',
            'destination_city' => 'مشهد',
            'start_province_id' => 3,
            'destination_province_id' => 2,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(3)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 85000,
            'provider' => 'علی بابا',
            'title' => 'ایران ایر',
            'start_city' => 'تهران',
            'destination_city' => 'مشهد',
            'start_province_id' => 3,
            'destination_province_id' => 2,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 250000,
            'provider' => 'علی بابا',
            'title' => 'تابان - چارتر',
            'start_city' => 'تهران',
            'destination_city' => 'مشهد',
            'start_province_id' => 3,
            'destination_province_id' => 2,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 450000,
            'provider' => 'علی بابا',
            'title' => 'آتا',
            'start_city' => 'تهران',
            'destination_city' => 'مشهد',
            'start_province_id' => 3,
            'destination_province_id' => 2,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 750000,
            'provider' => 'زاگرس',
            'title' => 'رفت و برگشت ایران ایر',
            'start_city' => 'تهران',
            'destination_city' => 'مشهد',
            'start_province_id' => 3,
            'destination_province_id' => 2,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);


        // Here
        $instance->create([
            'price' => 750000,
            'provider' => 'زاگرس',
            'title' => 'رفت و برگشت ایران ایر',
            'start_city' => 'تهران',
            'destination_city' => 'اصفهان',
            'start_province_id' => 3,
            'destination_province_id' => 1,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 450000,
            'provider' => 'علی بابا',
            'title' => 'آتا',
            'start_city' => 'تهران',
            'destination_city' => 'اصفهان',
            'start_province_id' => 3,
            'destination_province_id' => 1,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(3)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 85000,
            'provider' => 'علی بابا',
            'title' => 'ایران ایر',
            'start_city' => 'تهران',
            'destination_city' => 'اصفهان',
            'start_province_id' => 3,
            'destination_province_id' => 1,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 450000,
            'provider' => 'علی بابا',
            'title' => 'آتا',
            'start_city' => 'تهران',
            'destination_city' => 'اصفهان',
            'start_province_id' => 3,
            'destination_province_id' => 1,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 650000,
            'provider' => 'علی بابا',
            'title' => 'تابان',
            'start_city' => 'تهران',
            'destination_city' => 'اصفهان',
            'start_province_id' => 3,
            'destination_province_id' => 1,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 450000,
            'provider' => 'علی بابا',
            'title' => 'فیش ایر',
            'start_city' => 'تهران',
            'destination_city' => 'اصفهان',
            'start_province_id' => 3,
            'destination_province_id' => 1,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $others = ['خوزستان', 'فارس', 'همدان'];
        $each = 5;
        $providers = ['علی بابا'];
        $title = ['آتا', 'قشم ایر', 'مشهد ایر', 'تهران ایر', 'تابان'];
        $prices = [40000, 90000, 80000, 120000];

        foreach ($others as $pro) {
            for($i = 1; $i <= $each; $i++) {
                $instance->create([
                    'price' => $prices[array_rand($prices)],
                    'provider' => $providers[array_rand($providers)],
                    'start_city' => 'تهران',
                    'destination_city' => $pro,
                    'start_province_id' => 3,
                    'title' => $title[array_rand($title)],
                    'destination_province_id' => \Tajrish\Models\Province::where('name', $pro)->firstOrFail()->id,
                    'starts_at' => $now->toDateTimeString(),
                    'ends_at' => $now->addDays(2)->toDateTimeString()
                ]);
            }
        }
    }

    protected function createTrains()
    {
        /** @var \Tajrish\Models\Bus $instance */
        $instance = new \Tajrish\Models\Train;

        $now = \Carbon\Carbon::now();

        $instance->create([
            'price' => 120000,
            'provider' => 'رجا',
            'title' => 'رفت و برگشت اتوبوسی',
            'start_city' => 'تهران',
            'destination_city' => 'مشهد',
            'start_province_id' => 3,
            'destination_province_id' => 2,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 220000,
            'provider' => 'رجا',
            'title' => 'رفت و برگشت کوپه‌ای',
            'start_city' => 'تهران',
            'destination_city' => 'مشهد',
            'start_province_id' => 3,
            'destination_province_id' => 2,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 350000,
            'provider' => 'سیمرغ',
            'title' => 'لوکس',
            'start_city' => 'تهران',
            'destination_city' => 'مشهد',
            'start_province_id' => 3,
            'destination_province_id' => 2,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        // Here
        $instance->create([
            'price' => 120000,
            'provider' => 'رجا',
            'title' => 'رفت و برگشت اتوبوسی',
            'start_city' => 'تهران',
            'destination_city' => 'اصفهان',
            'start_province_id' => 3,
            'destination_province_id' => 1,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 220000,
            'provider' => 'رجا',
            'title' => 'رفت و برگشت کوپه‌ای',
            'start_city' => 'تهران',
            'destination_city' => 'اصفهان',
            'start_province_id' => 3,
            'destination_province_id' => 1,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);

        $instance->create([
            'price' => 350000,
            'provider' => 'سیمرغ',
            'title' => 'لوکس',
            'start_city' => 'تهران',
            'destination_city' => 'اصفهان',
            'start_province_id' => 3,
            'destination_province_id' => 1,
            'starts_at' => $now->toDateTimeString(),
            'ends_at' => $now->addDays(2)->toDateTimeString()
        ]);
    }

    protected function createUsers()
    {
        \Tajrish\Models\User::truncate();

        /** @var  \Tajrish\Repositories\UserTokenRepository $repo */
        $repo = app(\Tajrish\Repositories\UserTokenRepository::class);

        $user = \Tajrish\Models\User::create([
            'name' => 'رضا شادمان',
            'email' => 'pcfeeler@gmail.com',
            'password' => $pass = bcrypt('1234567'),
            'province_id' => \Tajrish\Models\Province::where('name', 'تهران')->firstOrFail()->id
        ]);

        $repo->makeUniqueTokenForUser($user, \Tajrish\Services\Tosan\Helpers\TokenGenerator::generate());

        $mockUsers = [
            ['name' => 'تبسم لطیفی', 'email' => 'tabassom@gmail.com', 'password' => $pass],
            ['name' => 'سامان ولی زاده', 'email' => 'saman@gmail.com', 'password' => $pass],
            ['name' => 'سیاوش آقائی', 'email' => 'siavash@gmail.com', 'password' => $pass],
            ['name' => 'علی یوسفی', 'email' => 'ali@yousefi.com', 'password' => $pass],
            ['name' => 'حسین شعبانی', 'email' => 'shabani@gmail.com', 'password' => $pass],
            ['name' => 'حامد دلفان', 'email' => 'hamed@gmail.com', 'password' => $pass],
            ['name' => 'رضا کیانی', 'email' => 'reza@kiyani.com', 'password' => $pass],
            ['name' => 'حامد بهداد', 'email' => 'hamed@behdad.com', 'password' => $pass],
            ['name' => 'بهرام رادانس', 'email' => 'bahram@gmail.com', 'password' => $pass],
            ['name' => 'رضا فروتن', 'email' => 'reza@forootan.com', 'password' => $pass],
            ['name' => 'هدیه تهرانی', 'email' => 'hediye@gmail.com', 'password' => $pass],
        ];

        foreach($mockUsers as $user) {
            \Tajrish\Models\User::create(array_merge($user, ['province_id' => rand(1, 6)]));
            $repo->makeUniqueTokenForUser($user, \Tajrish\Services\Tosan\Helpers\TokenGenerator::generate());
        }
    }
}
