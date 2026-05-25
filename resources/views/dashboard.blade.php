@extends('layouts.app')

@section('content')
<style>
  .dashboard-card {
    max-width:820px;
    margin:42px auto;
    padding:28px;
    background:#ffffff;
    border-radius:16px;
    box-shadow:0 10px 28px rgba(0,0,0,0.12);
  }
  .dashboard-top {
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:16px;
    margin-bottom:24px;
  }
  .dashboard-title {
    margin:0;
    color:#e31b23;
    font-size:28px;
    line-height:34px;
  }
  .dashboard-text {
    margin:8px 0 0;
    color:#263238;
    font-size:15px;
    line-height:22px;
  }
  .detail-grid {
    display:grid;
    grid-template-columns:repeat(2, minmax(0, 1fr));
    gap:14px;
  }
  .detail-item {
    padding:14px;
    border:1px solid #ffd3d9;
    border-radius:8px;
    background:#fff8f9;
  }
  .detail-label {
    display:block;
    margin-bottom:4px;
    color:#e31b23;
    font-size:12px;
    line-height:16px;
    font-weight:700;
    text-transform:uppercase;
  }
  .detail-value {
    color:#001a33;
    font-size:15px;
    line-height:22px;
    overflow-wrap:anywhere;
  }
  .logout-button,
  .admin-link {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-height:38px;
    padding:0 16px;
    border-radius:22px;
    border:0;
    background:#e31b23;
    color:#ffffff;
    font-size:14px;
    font-weight:700;
    text-decoration:none;
    cursor:pointer;
  }
  .dashboard-actions {
    display:flex;
    gap:10px;
    flex-wrap:wrap;
  }
  @media (max-width:640px) {
    .dashboard-top,
    .detail-grid {
      display:block;
    }
    .detail-item {
      margin-bottom:12px;
    }
    .dashboard-actions {
      margin-top:16px;
    }
  }
</style>

@php($user = auth()->user())

<div class="dashboard-card">
  <div class="dashboard-top">
    <div>
      <h1 class="dashboard-title">Welcome, {{ $user->name }}</h1>
      <p class="dashboard-text">Your partner account has been created. Your submitted details are listed below.</p>
    </div>
    <div class="dashboard-actions">
      @if ($user->is_admin)
        <a class="admin-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
      @endif
      <form action="{{ route('logout') }}" method="post">
        @csrf
        <button class="logout-button" type="submit">Logout</button>
      </form>
    </div>
  </div>

  <div class="detail-grid">
    <div class="detail-item">
      <span class="detail-label">Email</span>
      <span class="detail-value">{{ $user->email }}</span>
    </div>
    <div class="detail-item">
      <span class="detail-label">Phone</span>
      <span class="detail-value">{{ $user->phone ?: 'Not provided' }}</span>
    </div>
    <div class="detail-item">
      <span class="detail-label">Address</span>
      <span class="detail-value">{{ $user->address ?: 'Not provided' }}</span>
    </div>
    <div class="detail-item">
      <span class="detail-label">Region</span>
      <span class="detail-value">{{ $user->region ?: 'Not provided' }}</span>
    </div>
    <div class="detail-item" style="grid-column:1 / -1;">
      <span class="detail-label">Message</span>
      <span class="detail-value">{{ $user->message ?: 'No message submitted' }}</span>
    </div>
  </div>
</div>
@endsection
