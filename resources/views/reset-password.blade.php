@extends('layouts.app')

@section('content')
<div class="form-card" style="max-width:520px; min-height:420px;">
    <!-- Key Icon -->
    <div style="display:flex;justify-content:center;margin-bottom:24px;">
        <div style="width:56px;height:56px;background:#10b981;color:#ffffff;border-radius:12px;display:grid;place-items:center;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 7C15 8.65685 13.6569 10 12 10C10.3431 10 9 8.65685 9 7C9 5.34315 10.3431 4 12 4C13.6569 4 15 5.34315 15 7Z" stroke="currentColor" stroke-width="2"/>
                <path d="M12 10V20M12 20L9 17M12 20L15 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>

    <!-- Header -->
    <div class="form-title" style="font-size:28px;margin-bottom:8px;">Reset Password</div>
    <div class="form-subtitle" style="margin-bottom:28px;">Enter your new password to regain access to your account</div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div style="background:rgba(248,113,113,0.1);border:1px solid rgba(248,113,113,0.3);border-radius:8px;padding:12px;margin-bottom:16px;">
            <ul style="margin:0;padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li style="color:#f87171;font-size:14px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">
        
        <!-- New Password Field -->
        <div class="form-group">
            <label class="form-label" style="font-size:14px;font-weight:500;margin-bottom:8px;">New Password</label>
            <div class="input-wrap">
                <span class="input-icon-left" style="font-size:18px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;">
                        <path d="M12 15V17M6 21H18C18.5304 21 19.0391 20.7893 19.4142 20.4142C19.7893 20.0391 20 19.5304 20 19V13C20 12.4696 19.7893 11.9609 19.4142 11.5858C19.0391 11.2107 18.5304 11 18 11H6C5.46957 11 4.96086 11.2107 4.58579 11.5858C4.21071 11.9609 4 12.4696 4 13V19C4 19.5304 4.21071 20.0391 4.58579 20.4142C4.96086 20.7893 5.46957 21 6 21ZM16 11V7C16 5.93913 15.5786 4.92172 14.8284 4.17157C14.0783 3.42143 13.0609 3 12 3C10.9391 3 9.92172 3.42143 9.17157 4.17157C8.42143 4.92172 8 5.93913 8 7V11H16Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <input class="form-input pad-left-icon pad-right-icon" id="reset-password" name="password" type="password" placeholder="Enter new password" required autofocus style="padding:13px 44px 13px 44px;" />
                <span class="input-icon-right" onclick="togglePassword('reset-password', 'eye-icon-1')" style="cursor:pointer;display:flex;align-items:center;">
                    <svg id="eye-icon-1" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2.45825 12C3.73253 7.94288 7.52281 5 12.0004 5C16.4781 5 20.2683 7.94291 21.5426 12C20.2683 16.0571 16.4781 19 12.0005 19C7.52281 19 3.73251 16.0571 2.45825 12Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </div>
            <div style="color:#94a3b8;font-size:13px;margin-top:6px;">Must be at least 6 characters</div>
        </div>

        <!-- Confirm Password Field -->
        <div class="form-group">
            <label class="form-label" style="font-size:14px;font-weight:500;margin-bottom:8px;">Confirm New Password</label>
            <div class="input-wrap">
                <span class="input-icon-left" style="font-size:18px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;">
                        <path d="M12 15V17M6 21H18C18.5304 21 19.0391 20.7893 19.4142 20.4142C19.7893 20.0391 20 19.5304 20 19V13C20 12.4696 19.7893 11.9609 19.4142 11.5858C19.0391 11.2107 18.5304 11 18 11H6C5.46957 11 4.96086 11.2107 4.58579 11.5858C4.21071 11.9609 4 12.4696 4 13V19C4 19.5304 4.21071 20.0391 4.58579 20.4142C4.96086 20.7893 5.46957 21 6 21ZM16 11V7C16 5.93913 15.5786 4.92172 14.8284 4.17157C14.0783 3.42143 13.0609 3 12 3C10.9391 3 9.92172 3.42143 9.17157 4.17157C8.42143 4.92172 8 5.93913 8 7V11H16Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <input class="form-input pad-left-icon pad-right-icon" id="reset-password2" name="password_confirmation" type="password" placeholder="Confirm new password" required style="padding:13px 44px 13px 44px;" />
                <span class="input-icon-right" onclick="togglePassword('reset-password2', 'eye-icon-2')" style="cursor:pointer;display:flex;align-items:center;">
                    <svg id="eye-icon-2" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2.45825 12C3.73253 7.94288 7.52281 5 12.0004 5C16.4781 5 20.2683 7.94291 21.5426 12C20.2683 16.0571 16.4781 19 12.0005 19C7.52281 19 3.73251 16.0571 2.45825 12Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="form-group" style="margin-bottom:20px;">
            <button class="btn btn-primary" type="submit" style="padding:14px;font-size:15px;display:flex;align-items:center;justify-content:center;gap:8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Reset Password
            </button>
        </div>
    </form>

    <!-- Back to Login Link -->
    <div style="text-align:center;font-size:14px;" class="muted">
        Remember your password? <a class="help-link" href="{{ route('login') }}" style="font-weight:600;">Sign in</a>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path d="M13.875 18.825C13.2664 18.9417 12.6418 19 12 19C7.52281 19 3.73251 16.0571 2.45825 12C2.94936 10.3456 3.82894 8.82777 5.02188 7.56406M9.87868 9.87868C10.4216 9.33579 11.1716 9 12 9C13.6569 9 15 10.3431 15 12C15 12.8284 14.6642 13.5784 14.1213 14.1213M9.87868 9.87868L14.1213 14.1213M9.87868 9.87868L6.5 6.5M14.1213 14.1213L17.5 17.5M14.1213 14.1213L17.5 17.5M6.5 6.5L3 3M6.5 6.5C8.02125 5.52625 9.95625 5 12 5C16.4776 5 20.2679 7.94291 21.5421 12C20.8658 14.2083 19.4765 16.0766 17.5 17.5M17.5 17.5L21 21" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.45825 12C3.73253 7.94288 7.52281 5 12.0004 5C16.4781 5 20.2683 7.94291 21.5426 12C20.2683 16.0571 16.4781 19 12.0005 19C7.52281 19 3.73251 16.0571 2.45825 12Z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
    }
}
</script>
@endsection
