<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class UpdateSpecialtiesWithIconsSeeder extends Seeder
{
    public function run()
    {
        $specialties = [
            ['name' => 'طب عام', 'icon' => 'fa-user-md'],
            ['name' => 'طب أسنان', 'icon' => 'fa-tooth'],
            ['name' => 'طب نفسي', 'icon' => 'fa-brain'],
            ['name' => 'طب أطفال', 'icon' => 'fa-baby'],
            ['name' => 'طب نساء وتوليد', 'icon' => 'fa-female'],
            ['name' => 'طب باطني', 'icon' => 'fa-heartbeat'],
            ['name' => 'طب عيون', 'icon' => 'fa-eye'],
            ['name' => 'طب جلدية', 'icon' => 'fa-allergies'],
            ['name' => 'طب عظام', 'icon' => 'fa-bone'],
            ['name' => 'طب مخ وأعصاب', 'icon' => 'fa-brain'],
            ['name' => 'طب أنف وأذن وحنجرة', 'icon' => 'fa-ear'],
            ['name' => 'طب أورام', 'icon' => 'fa-ribbon'],
            ['name' => 'طب قلب وأوعية دموية', 'icon' => 'fa-heart'],
            ['name' => 'طب صدر', 'icon' => 'fa-lungs'],
            ['name' => 'طب مسالك بولية', 'icon' => 'fa-kidneys'],
            ['name' => 'طب روماتيزم', 'icon' => 'fa-bone'],
            ['name' => 'طب غدد صماء وسكر', 'icon' => 'fa-flask'],
            ['name' => 'طب جهاز هضمي وكبد', 'icon' => 'fa-stomach'],
            ['name' => 'طب تجميل', 'icon' => 'fa-magic'],
            ['name' => 'طب نطق وتخاطب', 'icon' => 'fa-comments'],
            ['name' => 'طب طبيعي وتأهيل', 'icon' => 'fa-walking'],
            ['name' => 'طب طوارئ', 'icon' => 'fa-ambulance'],
            ['name' => 'طب أشعة', 'icon' => 'fa-x-ray'],
            ['name' => 'طب مختبرات', 'icon' => 'fa-flask'],
            ['name' => 'طب تخدير', 'icon' => 'fa-procedures'],
            ['name' => 'طب علاج طبيعي', 'icon' => 'fa-hand-holding-medical'],
            ['name' => 'طب رياضي', 'icon' => 'fa-running'],
            ['name' => 'طب شيخوخة', 'icon' => 'fa-user-clock'],
            ['name' => 'طب نووي', 'icon' => 'fa-radiation'],
            ['name' => 'طب دم', 'icon' => 'fa-tint'],
            ['name' => 'طب مناعة', 'icon' => 'fa-shield-virus'],
            ['name' => 'طب وراثة', 'icon' => 'fa-dna'],
            ['name' => 'طب أمراض معدية', 'icon' => 'fa-virus'],
            ['name' => 'طب حساسية ومناعة', 'icon' => 'fa-shield-alt'],
            ['name' => 'طب تغذية', 'icon' => 'fa-apple-alt'],
            ['name' => 'طب نفسي للأطفال', 'icon' => 'fa-child'],
            ['name' => 'طب إدمان', 'icon' => 'fa-pills'],
            ['name' => 'طب أسرة', 'icon' => 'fa-users'],
            ['name' => 'طب مدرسي', 'icon' => 'fa-school'],
            ['name' => 'طب عمل', 'icon' => 'fa-briefcase-medical'],
            ['name' => 'طب رعاية حرجة', 'icon' => 'fa-hospital'],
            ['name' => 'طب ألم', 'icon' => 'fa-band-aid'],
            ['name' => 'طب تلطيفي', 'icon' => 'fa-hand-holding-heart'],
            ['name' => 'طب جراحة عامة', 'icon' => 'fa-cut'],
            ['name' => 'طب جراحة تجميل', 'icon' => 'fa-star'],
            ['name' => 'طب جراحة أطفال', 'icon' => 'fa-baby'],
            ['name' => 'طب جراحة قلب وصدر', 'icon' => 'fa-heartbeat'],
            ['name' => 'طب جراحة مخ وأعصاب', 'icon' => 'fa-brain'],
            ['name' => 'طب جراحة عظام', 'icon' => 'fa-bone'],
            ['name' => 'طب جراحة مسالك بولية', 'icon' => 'fa-kidneys'],
            ['name' => 'طب جراحة أوعية دموية', 'icon' => 'fa-wind'],
            ['name' => 'طب جراحة أورام', 'icon' => 'fa-ribbon']
        ];

        foreach ($specialties as $specialty) {
            Specialty::updateOrCreate(
                ['name' => $specialty['name']],
                ['icon' => $specialty['icon']]
            );
        }
    }
}
