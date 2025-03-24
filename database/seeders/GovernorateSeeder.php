<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GovernorateSeeder extends Seeder
{
    public function run()
    {
        $governorates = [
            // Lower Egypt (Delta)
            [
                'name' => 'Cairo',
                'name_ar' => 'القاهرة',
                'name_en' => 'Cairo',
                'region' => 'Lower Egypt'
            ],
            [
                'name' => 'Giza',
                'name_ar' => 'الجيزة',
                'name_en' => 'Giza',
                'region' => 'Lower Egypt'
            ],
            [
                'name' => 'Alexandria',
                'name_ar' => 'الإسكندرية',
                'name_en' => 'Alexandria',
                'region' => 'Lower Egypt'
            ],
            [
                'name' => 'Qalyubia',
                'name_ar' => 'القليوبية',
                'name_en' => 'Qalyubia',
                'region' => 'Lower Egypt'
            ],
            [
                'name' => 'Sharqia',
                'name_ar' => 'الشرقية',
                'name_en' => 'Sharqia',
                'region' => 'Lower Egypt'
            ],
            [
                'name' => 'Dakahlia',
                'name_ar' => 'الدقهلية',
                'name_en' => 'Dakahlia',
                'region' => 'Lower Egypt'
            ],
            [
                'name' => 'Beheira',
                'name_ar' => 'البحيرة',
                'name_en' => 'Beheira',
                'region' => 'Lower Egypt'
            ],
            [
                'name' => 'Gharbia',
                'name_ar' => 'الغربية',
                'name_en' => 'Gharbia',
                'region' => 'Lower Egypt'
            ],
            [
                'name' => 'Menoufia',
                'name_ar' => 'المنوفية',
                'name_en' => 'Menoufia',
                'region' => 'Lower Egypt'
            ],
            [
                'name' => 'Kafr El Sheikh',
                'name_ar' => 'كفر الشيخ',
                'name_en' => 'Kafr El Sheikh',
                'region' => 'Lower Egypt'
            ],
            [
                'name' => 'Damietta',
                'name_ar' => 'دمياط',
                'name_en' => 'Damietta',
                'region' => 'Lower Egypt'
            ],

            // Upper Egypt
            [
                'name' => 'Luxor',
                'name_ar' => 'الأقصر',
                'name_en' => 'Luxor',
                'region' => 'Upper Egypt'
            ],
            [
                'name' => 'Aswan',
                'name_ar' => 'أسوان',
                'name_en' => 'Aswan',
                'region' => 'Upper Egypt'
            ],
            [
                'name' => 'Sohag',
                'name_ar' => 'سوهاج',
                'name_en' => 'Sohag',
                'region' => 'Upper Egypt'
            ],
            [
                'name' => 'Qena',
                'name_ar' => 'قنا',
                'name_en' => 'Qena',
                'region' => 'Upper Egypt'
            ],
            [
                'name' => 'Assiut',
                'name_ar' => 'أسيوط',
                'name_en' => 'Assiut',
                'region' => 'Upper Egypt'
            ],
            [
                'name' => 'Minya',
                'name_ar' => 'المنيا',
                'name_en' => 'Minya',
                'region' => 'Upper Egypt'
            ],

            // Canal Region
            [
                'name' => 'Suez',
                'name_ar' => 'السويس',
                'name_en' => 'Suez',
                'region' => 'Canal Region'
            ],
            [
                'name' => 'Ismailia',
                'name_ar' => 'الإسماعيلية',
                'name_en' => 'Ismailia',
                'region' => 'Canal Region'
            ],
            [
                'name' => 'Port Said',
                'name_ar' => 'بورسعيد',
                'name_en' => 'Port Said',
                'region' => 'Canal Region'
            ],

            // Sinai
            [
                'name' => 'North Sinai',
                'name_ar' => 'شمال سيناء',
                'name_en' => 'North Sinai',
                'region' => 'Sinai'
            ],
            [
                'name' => 'South Sinai',
                'name_ar' => 'جنوب سيناء',
                'name_en' => 'South Sinai',
                'region' => 'Sinai'
            ],

            // New Valley
            [
                'name' => 'New Valley',
                'name_ar' => 'الوادي الجديد',
                'name_en' => 'New Valley',
                'region' => 'Desert Regions'
            ],
            [
                'name' => 'Red Sea',
                'name_ar' => 'البحر الأحمر',
                'name_en' => 'Red Sea',
                'region' => 'Desert Regions'
            ],
            [
                'name' => 'Matrouh',
                'name_ar' => 'مطروح',
                'name_en' => 'Matrouh',
                'region' => 'Desert Regions'
            ]
        ];

        // Insert governorates with timestamps
        foreach ($governorates as $governorate) {
            DB::table('governorates')->insert([
                'name' => $governorate['name'],
                'name_ar' => $governorate['name_ar'],
                'name_en' => $governorate['name_en'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
