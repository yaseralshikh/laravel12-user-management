<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <style>
            body {
                font-family: 'dejavusans', sans-serif;
                direction: rtl;
                margin: 30px;
            }

            h2 {
                text-align: center;
                margin: 0 0 16px 0;
            }

            .meta {
                text-align: left;
                font-size: 12px;
                margin-bottom: 12px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 12px;
            }

            th, td {
                border: 1px solid #222;
                padding: 6px;
                text-align: center;
                vertical-align: middle;
            }

            th {
                background: #efefef;
            }

            .empty {
                padding: 16px;
                text-align: center;
            }

            @page {
                footer: html_exportFooter;
            }

            .footer {
                font-size: 11px;
                text-align: center;
                color: #444;
            }
        </style>
    </head>
    <body>
        <h2>تقرير الزيارات</h2>
        <div class="meta">تاريخ الطباعة: <?php echo e(\Carbon\Carbon::now()->format('Y/m/d H:i')); ?></div>

        <table>
            <thead>
                <tr>
                    <th>م</th>
                    <th>التاريخ</th>
                    <th>المدرسة</th>
                    <th>المستخدم</th>
                    <th>نوع الزيارة</th>
                    <th>الهدف</th>
                    <th>السنة الدراسية</th>
                    <th>برنامج الدورة</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($visit->visit_date)->format('Y-m-d')); ?></td>
                        <td><?php echo e($visit->school->name ?? ''); ?></td>
                        <td><?php echo e($visit->user->name ?? ''); ?></td>
                        <td><?php echo e($visit->visit_type); ?></td>
                        <td><?php echo e($visit->objective); ?></td>
                        <td><?php echo e($visit->academicYear->name ?? ''); ?></td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visit->programCycle && $visit->programCycle->program): ?>
                                <?php echo e($visit->programCycle->program->name); ?> - <?php echo e($visit->programCycle->term); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td class="empty" colspan="8">لا توجد بيانات</td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>

        <htmlpagefooter name="exportFooter">
            <div class="footer">الصفحة {PAGENO} من {nbpg}</div>
        </htmlpagefooter>
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\projects\laravel12-user-management\resources\views/exports/visits.blade.php ENDPATH**/ ?>