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
        <div class="meta">تاريخ الطباعة: {{ \Carbon\Carbon::now()->format('Y/m/d H:i') }}</div>

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
                @forelse($data as $index => $visit)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($visit->visit_date)->format('Y-m-d') }}</td>
                        <td>{{ $visit->school->name ?? '' }}</td>
                        <td>{{ $visit->user->name ?? '' }}</td>
                        <td>{{ $visit->visit_type }}</td>
                        <td>{{ $visit->objective }}</td>
                        <td>{{ $visit->academicYear->name ?? '' }}</td>
                        <td>
                            @if($visit->programCycle && $visit->programCycle->program)
                                {{ $visit->programCycle->program->name }} - {{ $visit->programCycle->term }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="empty" colspan="8">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <htmlpagefooter name="exportFooter">
            <div class="footer">الصفحة {PAGENO} من {nbpg}</div>
        </htmlpagefooter>
    </body>
</html>
