@extends('layouts.app')

@section('content')
<div class="form-card" style="max-width:640px; min-height:620px;">
    <div style="display:flex;justify-content:center;">
        <div style="width:48px;height:48px;background:#10b981;color:#05261d;border-radius:12px;display:grid;place-items:center;font-weight:800;">¥</div>
    </div>
    <div class="form-title">Create Account</div>
    <div class="form-subtitle">Join PF Trackers and start managing your finances</div>

    @if ($errors->any())
        <div style="background:#fee;border:1px solid #fcc;border-radius:8px;padding:12px;margin-bottom:16px;">
            <ul style="margin:0;padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li style="color:#c00;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <div class="input-wrap">
                <span class="input-icon-left">👤</span>
                <input class="form-input pad-left-icon" type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required />
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Email Address</label>
            <div class="input-wrap">
                <span class="input-icon-left">📧</span>
                <input class="form-input pad-left-icon" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required />
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <div class="input-wrap">
                <span class="input-icon-left">🔒</span>
                <input class="form-input pad-left-icon pad-right-icon" id="reg-password" name="password" type="password" placeholder="Create password (min 6 characters)" required />
                <span class="input-icon-right" onclick="const i=document.getElementById('reg-password'); i.type=i.type==='password'?'text':'password';" style="cursor:pointer;">👁️</span>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <div class="input-wrap">
                <span class="input-icon-left">🔒</span>
                <input class="form-input pad-left-icon pad-right-icon" id="reg-password2" name="password_confirmation" type="password" placeholder="Confirm password" required />
                <span class="input-icon-right" onclick="const i=document.getElementById('reg-password2'); i.type=i.type==='password'?'text':'password';" style="cursor:pointer;">👁️</span>
            </div>
        </div>

        <div class="form-group" style="display:flex;gap:12px;align-items:flex-start;">
            <input type="checkbox" style="margin-top:3px;">
            <div class="muted" style="font-size:14px;">I agree to the <a href="#" class="help-link">Terms of Service</a> and <a href="#" class="help-link">Privacy Policy</a></div>
        </div>

        <div class="form-group" style="display:flex;gap:12px;align-items:flex-start;">
            <input type="checkbox" style="margin-top:3px;">
            <div class="muted" style="font-size:14px;">I want to receive updates about new features and financial tips</div>
        </div>

        <div class="form-group" style="margin-top:6px;">
            <button class="btn btn-primary" type="submit">Create Account</button>
        </div>
    </form>

    <div style="text-align:center;margin-top:10px;" class="muted">
        Already have an account? <a class="help-link" href="{{ route('login') }}">Sign in</a>
    </div>
</div>
@endsection
