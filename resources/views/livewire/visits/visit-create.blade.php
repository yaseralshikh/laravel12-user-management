<div>
    <h2>إضافة زيارة جديدة</h2>
    
    <form wire:submit="save">
        <!-- Sector Select -->
        <div>
            <label>القطاع</label>
            <select wire:model.live="sector_id">
                <option value="">-- اختر --</option>
                @foreach($sectors as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Stage Select -->
        <div>
            <label>المرحلة الدراسية</label>
            <select wire:model.live="stage">
                <option value="">-- اختر --</option>
                @foreach($stages as $st)
                    <option value="{{ $st }}">{{ $st }}</option>
                @endforeach
            </select>
        </div>

        <!-- School Select - Disabled until both selected -->
        <div>
            <label>المدرسة</label>
            <select wire:model="school_id" {{ !$sector_id || !$stage ? 'disabled' : '' }}>
                <option value="">-- اختر --</option>
                @foreach($schools as $sch)
                    <option value="{{ $sch->id }}">{{ $sch->name }}</option>
                @endforeach
            </select>
            @if(!$sector_id || !$stage)
                <p>يرجى اختيار القطاع والمرحلة الدراسية أولاً</p>
            @endif
        </div>

        <!-- Other fields -->
        <div>
            <label>المستخدم</label>
            <select wire:model="user_id">
                <option value="">-- اختر --</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>السنة الدراسية</label>
            <select wire:model="academic_year_id">
                <option value="">-- اختر --</option>
                @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}">{{ $ay->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>تاريخ الزيارة</label>
            <input type="date" wire:model="visit_date" />
        </div>

        <div>
            <label>نوع الزيارة</label>
            <select wire:model="visit_type">
                <option value="">-- اختر --</option>
                @foreach($visitTypes as $k => $v)
                    <option value="{{ $k }}">{{ $v }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>الهدف</label>
            <textarea wire:model="objective"></textarea>
        </div>

        <div>
            <label>الملاحظات</label>
            <textarea wire:model="notes"></textarea>
        </div>

        <div>
            <label>التوصيات</label>
            <textarea wire:model="recommendations"></textarea>
        </div>

        <button type="submit">إضافة الزيارة</button>
    </form>
</div>
