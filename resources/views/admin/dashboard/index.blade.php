@extends('layouts.admin.main')
@section('content')
    <div class="dashboard-header">
        <h1 class="dashboard-title">Dashboard Overview</h1>
        <p class="dashboard-subtitle">Welcome back! Here's a comprehensive view of your business
            performance.</p>
    </div>

    <!-- KPI Cards -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-header">
                <div class="kpi-info">
                    <div class="kpi-title">Total Blogs</div>
                    <div class="kpi-value">{{ $blogs_count }}</div>
                </div>
                <div class="kpi-icon" style="background-color: #e0f2f1;">
                    <i data-lucide="file-text" style="color: #004d40;"></i>
                </div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-header">
                <div class="kpi-info">
                    <div class="kpi-title">Total Testimonials</div>
                    <div class="kpi-value">{{ $testimonials_count }}</div>
                </div>
                <div class="kpi-icon" style="background-color: #d1fae5;">
                    <i data-lucide="star" style="color: #10b981;"></i>
                </div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-header">
                <div class="kpi-info">
                    <div class="kpi-title">Total Enquiries</div>
                    <div class="kpi-value">{{ $enquiries_count }}</div>
                </div>
                <div class="kpi-icon" style="background-color: #e0f2fe;">
                    <i data-lucide="message-square" style="color: #0284c7;"></i>
                </div>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-header">
                <div class="kpi-info">
                    <div class="kpi-title">Total Users</div>
                    <div class="kpi-value">{{ $users_count }}</div>
                </div>
                <div class="kpi-icon" style="background-color: #fef3c7;">
                    <i data-lucide="users" style="color: #d97706;"></i>
                </div>
            </div>
        </div>
    </div>
@endsection
