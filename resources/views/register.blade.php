@extends('layouts.app')

@section('content')
<div class="form-card" style="max-width:640px; min-height:620px;">
    <div style="display:flex;justify-content:center;">
        <div style="width:48px;height:48px;background:#10b981;color:#05261d;border-radius:12px;display:grid;place-items:center;font-weight:800;">¥</div>
    </div>
    <div class="form-title">Create Account</div>
    <div class="form-subtitle">Join PF Trackers and start managing your finances</div>

    <form method="post" action="#" onsubmit="return false;">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">First Name</label>
                <div class="input-wrap">
                    <span class="input-icon-left">👤</span>
                    <input class="form-input pad-left-icon" type="text" placeholder="First name" required />
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Last Name</label>
                <div class="input-wrap">
                    <span class="input-icon-left">👤</span>
                    <input class="form-input pad-left-icon" type="text" placeholder="Last name" required />
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Email Address</label>
            <div class="input-wrap">
                <span class="input-icon-left">📧</span>
                <input class="form-input pad-left-icon" type="email" placeholder="Enter your email" required />
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <div class="input-wrap">
                <span class="input-icon-left">🔒</span>
                <input class="form-input pad-left-icon pad-right-icon" id="reg-password" type="password" placeholder="Create password" required />
                <span class="input-icon-right" onclick="const i=document.getElementById('reg-password'); i.type=i.type==='password'?'text':'password';">👁️</span>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <div class="input-wrap">
                <span class="input-icon-left">🔒</span>
                <input class="form-input pad-left-icon pad-right-icon" id="reg-password2" type="password" placeholder="Confirm password" required />
                <span class="input-icon-right" onclick="const i=document.getElementById('reg-password2'); i.type=i.type==='password'?'text':'password';">👁️</span>
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
