<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    public function run()
    {
        $specialties = [
            'جراحة القلب و القسطرة',
            'طب القلب العامة',
            'الجراحة العامة',
            'طب و جراحة العيون',
            'جراحة العظام',
            'جراحة المسالك البولية',
            'جراحة الأطفال',
            'جراحة الحروق و التجميل',
            'جراحة المخ والأعصاب',
            'طب أمراض الكلى',
            'التخدير',
            'أمراض النساء و الولادة',
            'الجهاز الهضمي والكبد',
            'طب الباطني عام',
            'طب الاسنان',
            'جراحة الفم والوجه والفكين',
            'أمراض الجلدية',
            'السكري',
            'طب الامراض النفسية'
        ];

        foreach ($specialties as $specialty) {
            Specialty::create(['name' => $specialty]);
        }
    }
}
