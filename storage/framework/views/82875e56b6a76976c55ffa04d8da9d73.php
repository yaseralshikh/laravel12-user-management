<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>استمارة زيارة مشرف الموهوبين</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 11pt;
            line-height: 1.4;
            padding: 20px;
        }
        
        /* Header Section */
        .header {
            width: 100%;
            margin-bottom: 20px;
        }
        .header-table {
            width: 100%;
            border: none;
        }
        .header-table td {
            border: none;
            vertical-align: top;
            padding: 5px;
        }
        .header-right {
            text-align: right;
            font-size: 10pt;
            line-height: 1.6;
        }
        .header-center {
            text-align: center;
        }
        .header-left {
            text-align: left;
            font-size: 10pt;
        }
        .logo {
            width: 80px;
            height: auto;
        }
        .ministry-name {
            color: #0d9488;
            font-weight: bold;
            font-size: 12pt;
            margin-top: 5px;
        }
        
        /* Title */
        .main-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin: 25px 0;
            color: #000;
        }
        
        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .info-table th {
            background-color: #0d9488;
            color: white;
            padding: 10px;
            border: 1px solid #0d9488;
            font-weight: bold;
            text-align: center;
        }
        .info-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            min-height: 30px;
        }
        
        /* Section Headers */
        .section-header {
            background-color: #0d9488;
            color: white;
            padding: 8px 15px;
            font-weight: bold;
            text-align: right;
            margin-bottom: 0;
        }
        
        /* Content Boxes */
        .content-box {
            border: 1px solid #ddd;
            min-height: 120px;
            padding: 15px;
            margin-bottom: 15px;
            line-height: 2;
        }
        
        .content-box-small {
            border: 1px solid #ddd;
            min-height: 80px;
            padding: 15px;
            margin-bottom: 15px;
            line-height: 2;
        }
        
        /* Signatures Table */
        .signatures-table {
            margin-top: 20px;
        }
        .signatures-table th {
            background-color: #0d9488;
            color: white;
            padding: 10px;
            border: 1px solid #0d9488;
            font-weight: bold;
            text-align: center;
            width: 33.33%;
        }
        .signatures-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: right;
            height: 60px;
            vertical-align: top;
        }
        
        /* Footer */
        .footer-bar {
            background-color: #0d9488;
            height: 15px;
            width: 100%;
            margin-top: 20px;
        }
        
        .dotted-line {
            border-bottom: 1px dotted #999;
            display: inline-block;
            width: 150px;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    
    <div class="header">
        <table class="header-table">
            <tr>
                <td width="40%" style="text-align: center;">
                    <div>المملكة العربية السعودية</div>
                    <div>وزارة التعليم</div>
                    <div>الإدارة العامة للتعليم بمنطقة جازان</div>
                    <div>الشؤون التعليمية - إدارة تنمية القدرات</div>
                    <div>قسم الموهوبين</div>
                </td>
                <td width="20%" class="header-center">
                    <img src="<?php echo e(public_path('images/moe-logo.png')); ?>" width="150" alt="شعار وزارة التعليم">
                </td>
                <td width="40%" class="header-left">
                    <div>التاريخ: <?php echo e(\Carbon\Carbon::parse($visit->visit_date)->format('Y/m/d')); ?> هـ</div>
                </td>
            </tr>
        </table>
    </div>

    
    <div class="main-title">استمارة زيارة مشرف الموهوبين للمدارس</div>

    
    <table class="info-table">
        <tr>
            <th>اسم المدرسة</th>
            <th>المرحلة</th>
            <th>تاريخ الزيارة</th>
            <th>اليوم</th>
        </tr>
        <tr>
            <td><?php echo e($visit->school->name); ?></td>
            <td><?php echo e($visit->school->stage ?? '-'); ?></td>
            <td><?php echo e(\Carbon\Carbon::parse($visit->visit_date)->format('Y/m/d')); ?></td>
            <td><?php echo e(\Carbon\Carbon::parse($visit->visit_date)->locale('ar')->dayName); ?></td>
        </tr>
    </table>

    
    <table class="info-table">
        <tr>
            <th></th>
            <th>ابتدائي</th>
            <th>متوسط</th>
            <th>ثانوي</th>
            <th>الاجمالي</th>
        </tr>
        <tr>
            <td>عداد الطلاب</td>
            <td><?php echo e($visit->school->elementary_students ?? ''); ?></td>
            <td><?php echo e($visit->school->middle_students ?? ''); ?></td>
            <td><?php echo e($visit->school->high_students ?? ''); ?></td>
            <td><?php echo e($visit->school->total_students ?? ''); ?></td>
        </tr>
    </table>

    
    <div class="section-header">الهدف من الزيارة</div>
    <div class="content-box">
        <?php echo e($visit->objective ?? ''); ?>

    </div>

    
    <div class="section-header">فعاليات الزيارة:</div>
    <div class="content-box">
        <?php echo e($visit->notes ?? ''); ?>

    </div>

    
    <div class="section-header">توصيات الزيارة:</div>
    <div class="content-box-small">
        <?php echo e($visit->recommendations ?? ''); ?>

    </div>

    
    <table class="signatures-table">
        <tr>
            <th>معلم الموهوبين</th>
            <th>مشرف الموهوبين</th>
            <th>مدير المدرسة</th>
        </tr>
        <tr>
            <td>
                <div>الاسم: <?php echo e($visit->school->coordinator->name ?? '...........................'); ?></div>
                <br>
                <div>التوقيع: .........................</div>
            </td>
            <td>
                <div>الاسم: <?php echo e($visit->user->name); ?></div>
                <br>
                <div>التوقيع: .........................</div>
            </td>
            <td>
                <div>الاسم: <?php echo e($visit->school->principal->name ?? '...........................'); ?></div>
                <br>
                <div>التوقيع: .........................</div>
            </td>
        </tr>
    </table>

    
    <div class="footer-bar"></div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\projects\laravel12-user-management\resources\views/pdf/visit-report.blade.php ENDPATH**/ ?>