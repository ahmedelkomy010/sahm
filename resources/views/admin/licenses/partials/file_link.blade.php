@php
    $isMultiple = isset($multiple) && $multiple;
    $files = $isMultiple && $file ? json_decode($file, true) : ($file ? [$file] : []);
@endphp
@if($files && count($files))
    @foreach($files as $f)
        <a href="{{ asset('storage/' . $f) }}" target="_blank" class="btn btn-sm btn-info mb-1">عرض/تحميل</a><br>
    @endforeach
@elseif($file && !$isMultiple)
    <a href="{{ asset('storage/' . $file) }}" target="_blank" class="btn btn-sm btn-info">عرض/تحميل</a>
@else
    <span class="text-muted">لا يوجد مرفق</span>
@endif 