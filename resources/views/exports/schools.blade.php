<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <style>
            body {
                font-family: 'XBRiyaz', sans-serif;
                direction: rtl;
                margin: 40px;
            }

            .header-table {
                width: 100%;
                border: none;
                margin-bottom: 5px;
            }

            .header-table td {
                border: none;
                vertical-align: top;
                font-size: 11px;
                font-weight: bold;
            }

            .header-right {
                text-align: center;
                line-height: 1.5;
            }

            .header-center {
                text-align: center;
            }

            .header-left {
                text-align: center;
            }

            h2 {
                text-align: center;
                margin-top: 5px;
            }

            .table-content {
                width: 100%;
                border-collapse: collapse;
                margin-top: 15px;
                font-size: 14px;
            }

            .table-content th, .table-content td {
                border: 1px solid #000;
                padding: 8px;
                text-align: center;
            }

            .table-content th {
                background-color: #eee;
            }
            @page {
                footer: html_myFooter;
            }

            .footer {
                font-size: 12px;
                text-align: center;
                color: #444;
            }
        </style>
    </head>
    <body>

        {{-- الترويسة --}}
        <table class="header-table">
            <tr>
                <!-- يمين: معلومات الوزارة -->
                <td class="header-right" style="width: 33%;">
                    المملكة العربية السعودية<br>
                    وزارة التعليم<br>
                    الإدارة العامة للتعليم بمنطقة جازان<br>
                    الشؤون التعليمية – إدارة تنمية القدرات<br>
                    قسم الموهوبين
                </td>

                <!-- وسط: الشعار -->
                <td class="header-center" style="width: 34%;">
                    <img src="{{ public_path('images/moe-logo.png') }}" width="150" alt="شعار وزارة التعليم">
                </td>

                <!-- يسار: التاريخ -->
                <td class="header-left" style="width: 33%;">
                    التاريخ: {{ \Carbon\Carbon::now()->format('Y/m/d') }}
                </td>
            </tr>
        </table>

        <hr>

        <h2>تقرير المدارس</h2>

        {{-- جدول المحتوى --}}
        <table class="table-content">
            <thead>
                <tr>
                    <th>م</th>
                    <th>اسم المدرسة</th>
                    <th>الرمز الوزاري</th>
                    <th>النوع</th>
                    <th>المراحل</th>
                    <th>نوع التعليم</th>
                    <th>نوع المبنى</th>
                    <th>الحالة</th>
                    <th>القطاع التعليمي</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $school)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $school->name }}</td>
                        <td>{{ $school->ministry_code }}</td>
                        <td>{{ config('schools.genders')[$school->gender] ?? $school->gender }}</td>
                        <td>{{ $school->stage }}</td>
                        <td>{{ $school->school_type }}</td>
                        <td>{{ $school->building_type }}</td>
                        <td>{{ config('schools.statuses')[$school->status] ?? $school->status }}</td>
                        <td>{{ $school->educational_sector }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- الذيل --}}
        <htmlpagefooter name="myFooter">
            <div class="footer">
                الصفحة {PAGENO} من {nbpg} - تاريخ الطباعة: {{ \Carbon\Carbon::now()->format('Y/m/d') }}
            </div>
        </htmlpagefooter>

    </body>
</html>
