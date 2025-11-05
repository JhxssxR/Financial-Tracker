@extends('layouts.app')

@section('content')
<div style="max-width:1400px;margin:0 auto;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
        <div>
            <h1 style="font-size:32px;font-weight:700;margin:0;">Notifications</h1>
            <p class="muted" style="margin:4px 0 0;font-size:14px;">All caught up!</p>
        </div>
        <a href="{{ route('settings.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:#1e293b;border:1px solid #334155;border-radius:8px;color:#e2e8f0;text-decoration:none;font-weight:600;transition:all .2s;" onmouseover="this.style.background='#334155'" onmouseout="this.style.background='#1e293b'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Settings
        </a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 420px;gap:24px;margin-top:24px;">
        <!-- Left: Recent Notifications -->
        <div style="grid-column: 1 / -1;">
            <h2 style="font-size:24px;font-weight:700;margin:0 0 16px;">Recent Notifications</h2>
            
            @forelse($notifications as $notification)
            <div class="card" style="padding:16px 20px;margin-bottom:12px;display:flex;align-items:center;gap:16px;{{ $notification->is_read ? 'opacity:0.7;' : '' }}">
                <div style="width:48px;height:48px;background:{{ $notification->type === 'savings' ? '#059669' : ($notification->type === 'budget' ? '#3b82f6' : '#8b5cf6') }};border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    @if($notification->type === 'savings')
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v4m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    @elseif($notification->type === 'budget')
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    @else
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    @endif
                </div>
                <div style="flex:1;">
                    <h3 style="font-size:16px;font-weight:600;margin:0 0 4px;color:#e2e8f0;">{{ $notification->title }}</h3>
                    <p style="font-size:14px;margin:0;color:#94a3b8;">{{ $notification->message }}</p>
                    <p style="font-size:12px;margin:6px 0 0;color:#64748b;">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
                @if(!$notification->is_read)
                <div style="width:8px;height:8px;background:#3b82f6;border-radius:50%;flex-shrink:0;"></div>
                @endif
            </div>
            @empty
            <!-- Empty State -->
            <div class="card" style="padding:200px 80px;text-align:center;">
                <div style="display:inline-flex;width:80px;height:80px;background:#1a2b3a;border-radius:50%;align-items:center;justify-content:center;margin:0 auto 20px;">
                    <svg width="45" height="45" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="muted" style="margin:0;font-size:16px;">No notifications to display</p>
            </div>
            @endforelse
        </div>

       
@push('scripts')
<script>
    // Initialize all toggle switches
    document.querySelectorAll('.toggle-switch').forEach(checkbox => {
        const label = checkbox.closest('label');
        const track = label.querySelector('.toggle-track');
        const thumb = label.querySelector('.toggle-thumb');
        
        function updateToggle() {
            if (checkbox.checked) {
                track.style.background = '#334155';
                thumb.style.background = '#fff';
                thumb.style.transform = 'translateX(24px)';
            } else {
                track.style.background = '#1e293b';
                thumb.style.background = '#94a3b8';
                thumb.style.transform = 'translateX(0)';
            }
        }
        
        updateToggle();
        checkbox.addEventListener('change', updateToggle);
    });
</script>
@endpush
@endsection
