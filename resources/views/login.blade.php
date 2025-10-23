@extends('layouts.app')

@section('content')
<div class="form-card" style="max-width:520px; min-height:440px;">
    <div style="display:flex;justify-content:center;">
        <div style="width:48px;height:48px;background:#10b981;color:#05261d;border-radius:12px;display:grid;place-items:center;font-weight:800;">¥</div>
    </div>
    <div class="form-title">Welcome Back</div>
    <div class="form-subtitle">Sign in to your PF Trackers account</div>

    @if ($errors->any())
        <div style="background:#fee;border:1px solid #fcc;border-radius:8px;padding:12px;margin-bottom:16px;">
            <ul style="margin:0;padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li style="color:#c00;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div style="background:#efe;border:1px solid #cfc;border-radius:8px;padding:12px;margin-bottom:16px;color:#060;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
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
                <input class="form-input pad-left-icon pad-right-icon" id="login-password" name="password" type="password" placeholder="Enter your password" required />
                <span class="input-icon-right" onclick="const i=document.getElementById('login-password'); i.type=i.type==='password'?'text':'password';" style="cursor:pointer;">👁️</span>
            </div>
        </div>

        <div class="form-actions">
            <label style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" name="remember" style="width:16px;height:16px;"> <span class="muted">Remember me</span>
            </label>
            <a href="#" class="help-link">Forgot password?</a>
        </div>

        <div class="form-group" style="margin-top:14px;">
            <button class="btn btn-primary" type="submit">Sign In</button>
        </div>
    </form>

    <div style="text-align:center;margin-top:10px;" class="muted">
        Don’t have an account? <a class="help-link" href="{{ route('register') }}">Sign up</a>
    </div>
</div>
@endsection
