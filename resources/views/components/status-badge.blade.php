@props(['status'])

@php
    $classes = [
        'pending' => 'bg-[#fff6d8] text-[#7a5a00] border-[#ead58a]',
        'verified' => 'bg-[#e7f1ff] text-[#225a92] border-[#b9d4ee]',
        'in_progress' => 'bg-[#edf7ef] text-[#25613c] border-[#b9dcbc]',
        'resolved' => 'bg-[#e9f8f4] text-[#0f6b5b] border-[#a9d8cf]',
        'rejected' => 'bg-[#f7e8e8] text-[#8d3131] border-[#e0bbbb]',
    ][$status] ?? 'bg-white text-[#415047] border-[#d9ded4]';
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-md border px-2.5 py-1 text-xs font-semibold '.$classes]) }}>
    {{ \App\Models\IssueReport::STATUSES[$status] ?? ucfirst($status) }}
</span>
