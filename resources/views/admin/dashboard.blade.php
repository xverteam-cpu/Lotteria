@extends('layouts.app')

@section('content')
<style>
  .admin-card {
    max-width:1100px;
    margin:34px auto;
    padding:26px;
    background:#ffffff;
    border-radius:16px;
    box-shadow:0 10px 28px rgba(0,0,0,0.12);
  }
  .admin-top {
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:16px;
    margin-bottom:22px;
  }
  .admin-title {
    margin:0;
    color:#e31b23;
    font-size:28px;
    line-height:34px;
  }
  .admin-summary {
    display:grid;
    grid-template-columns:repeat(3, minmax(0, 1fr));
    gap:12px;
    margin-bottom:20px;
  }
  .summary-tile {
    padding:16px;
    border-radius:10px;
    background:#fff8f9;
    border:1px solid #ffd3d9;
  }
  .summary-value {
    display:block;
    color:#e31b23;
    font-size:26px;
    line-height:30px;
    font-weight:700;
  }
  .summary-label {
    display:block;
    margin-top:4px;
    color:#263238;
    font-size:13px;
    line-height:18px;
  }
  .table-wrap {
    overflow-x:auto;
  }
  .users-table {
    width:100%;
    border-collapse:collapse;
    min-width:840px;
  }
  .users-table th,
  .users-table td {
    padding:12px 10px;
    border-bottom:1px solid #f0d9dc;
    color:#001a33;
    font-size:14px;
    line-height:20px;
    text-align:left;
    vertical-align:top;
  }
  .users-table th {
    color:#e31b23;
    font-size:12px;
    line-height:16px;
    text-transform:uppercase;
  }
  .admin-actions {
    display:flex;
    gap:10px;
    flex-wrap:wrap;
  }
  .admin-button {
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
  @media (max-width:700px) {
    .admin-top,
    .admin-summary {
      display:block;
    }
    .summary-tile {
      margin-bottom:12px;
    }
    .admin-actions {
      margin-top:16px;
    }
  }
</style>

<div class="admin-card">
  <div class="admin-top">
    <div>
      <h1 class="admin-title">Admin Dashboard</h1>
      <p style="margin:8px 0 0;color:#263238;font-size:15px;line-height:22px;">Track newly registered users and the full partner user list.</p>
    </div>
    <div class="admin-actions">
      <a class="admin-button" href="{{ route('dashboard') }}">User Dashboard</a>
      <form action="{{ route('logout') }}" method="post">
        @csrf
        <button class="admin-button" type="submit">Logout</button>
      </form>
    </div>
  </div>

  <div class="admin-summary">
    <div class="summary-tile">
      <span class="summary-value">{{ $users->count() }}</span>
      <span class="summary-label">Total registered users</span>
    </div>
    <div class="summary-tile">
      <span class="summary-value">{{ $users->where('created_at', '>=', now()->subDay())->count() }}</span>
      <span class="summary-label">New in last 24 hours</span>
    </div>
    <div class="summary-tile">
      <span class="summary-value">{{ $users->where('is_admin', true)->count() }}</span>
      <span class="summary-label">Admin users</span>
    </div>
  </div>

  <div class="table-wrap">
    <table class="users-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Region</th>
          <th>Address</th>
          <th>Registered</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($users as $user)
          <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone ?: '-' }}</td>
            <td>{{ $user->region ?: '-' }}</td>
            <td>{{ $user->address ?: '-' }}</td>
            <td>{{ $user->created_at?->format('M d, Y h:i A') ?: '-' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="6">No registered users yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
