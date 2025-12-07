@extends('layouts.app')

@section('content')
<style>
    .profile-container {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
    }

    .profile-card {
        background: #1e293b;
        border-radius: 16px;
        padding: 32px;
        text-align: center;
        margin-bottom: 24px;
        border: 1px solid rgba(148, 163, 184, 0.1);
    }

    .profile-avatar {
        width: 140px;
        height: 140px;
        background: #3d4a5a;
        border-radius: 50%;
        display: grid;
        place-items: center;
        margin: 0 auto 20px;
        color: #94a3b8;
    }

    .profile-name {
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 8px;
        color: #e2e8f0;
    }

    .profile-email {
        color: #94a3b8;
        margin: 0 0 16px;
        font-size: 15px;
    }

    .profile-member-since {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #94a3b8;
        font-size: 14px;
        background: rgba(148, 163, 184, 0.05);
        padding: 8px 16px;
        border-radius: 20px;
    }

    .summary-card {
        background: #1e293b;
        border-radius: 16px;
        padding: 28px;
        border: 1px solid rgba(148, 163, 184, 0.1);
    }

    .summary-title {
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 20px;
        color: #e2e8f0;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #334155;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: #94a3b8;
        font-size: 15px;
    }

    .summary-link {
        color: #10b981;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
    }

    .summary-link:hover {
        color: #34d399;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .profile-container {
            padding: 16px;
        }

        .profile-card {
            padding: 24px 20px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
        }

        .profile-avatar svg {
            width: 50px;
            height: 50px;
        }

        .profile-name {
            font-size: 22px;
        }

        .profile-email {
            font-size: 14px;
            word-break: break-word;
        }

        .profile-member-since {
            font-size: 13px;
            padding: 6px 12px;
        }

        .summary-card {
            padding: 20px;
        }

        .summary-title {
            font-size: 18px;
            margin-bottom: 16px;
        }

        .summary-row {
            padding: 12px 0;
        }

        .summary-label,
        .summary-link {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .profile-container {
            padding: 12px;
        }

        .profile-card {
            padding: 20px 16px;
            border-radius: 12px;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
        }

        .profile-avatar svg {
            width: 45px;
            height: 45px;
        }

        .profile-name {
            font-size: 20px;
        }

        .profile-email {
            font-size: 13px;
        }

        .profile-member-since {
            font-size: 12px;
            padding: 5px 10px;
        }

        .profile-member-since svg {
            width: 14px;
            height: 14px;
        }

        .summary-card {
            padding: 16px;
            border-radius: 12px;
        }

        .summary-title {
            font-size: 16px;
        }

        .summary-row {
            padding: 10px 0;
        }

        .summary-label,
        .summary-link {
            font-size: 13px;
        }
    }
</style>

<div class="profile-container">
    <!-- Profile Card -->
    <div class="profile-card">
        <div class="profile-avatar">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h2 class="profile-name">{{ Auth::user()->name }}</h2>
        <p class="profile-email">{{ Auth::user()->email }}</p>
        <div class="profile-member-since">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Member since {{ Auth::user()->created_at->format('F Y') }}
        </div>
    </div>

    <!-- Account Summary -->
    <div class="summary-card">
        <h3 class="summary-title">Account Summary</h3>
        
        <div class="summary-row">
            <span class="summary-label">Total Transactions</span>
        </div>
        
        <div class="summary-row">
            <span class="summary-label">Budgets Created</span>
        </div>
        
        <div class="summary-row">
            <span class="summary-label">Goals Achieved</span>
        </div>
        
        <div class="summary-row">
            <span class="summary-label">Data Export</span>
            <a href="#" class="summary-link">Download</a>
        </div>
    </div>
</div>
@endsection
