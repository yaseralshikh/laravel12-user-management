<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'name' => 'الأولمبياد الوطني للإبداع العلمي',
                'description' => 'عبارة عن مسابقة علمية سنوية تقوم على أساس التنافس في أحد المجالات العلمية، من خلال تقديم مشاريع علمية فردية وفقاً للمعايير والضوابط الخاصة بالمشروع، ويتم تحكيمها إلكترونياً ومباشراً من قبل نخبة من الأكاديميين والمختصين وفق معايير علمية محددة بهدف تحديد المشاريع المتميزة لترشيحها للمراحل التنافسية الأعلى.',
                'status' => 'active',
            ],
            [
                'name' => 'البرنامج الوطني للكشف عن الموهوبين',
                'description' => 'يعتبر "مقياس موهبة للقدرات العقلية المتعددة" المحك الأساسي لتقييم قدرات الطلبة وقبولهم في كافة البرامج والأنشطة المتعلقة بالبرنامج الوطني للكشف عن الموهوبين',
                'status' => 'active',
            ],
            [
                'name' => 'أولمبياد العلوم والرياضيات الوطني (نسمو)',
                'description' => 'أولمبياد العلوم والرياضيات الوطني "نسمو" مسابقةً وطنيةً سنوية رائدة، تُقام في مختلف مناطق المملكة العربية السعودية، تستهدف الطلبة في الصف الأول متوسط وحتى الصف الأول الثانوي في تخصصات العلوم والرياضيات والكيمياء والفيزياء والأحياء والمعلوماتية',
                'status' => 'active',
            ]
        ];

        foreach ($programs as $program) {
            Program::updateOrCreate(
                ['name' => $program['name']],
                [
                    'description' => $program['description'],
                    'status' => $program['status'],
                ]
            );
        }
    }
}
