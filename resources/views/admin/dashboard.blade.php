@extends('layouts.app')

@section('content')
<style>
  .admin-shell {
    max-width:1180px;
    margin:30px auto;
  }
  .admin-hero {
    position:relative;
    overflow:hidden;
    display:flex;
    justify-content:space-between;
    gap:18px;
    padding:26px;
    border-radius:18px;
    background:#b00000;
    color:#ffffff;
    box-shadow:0 14px 34px rgba(176,0,0,0.2);
  }
  .admin-hero::before {
    content:'';
    position:absolute;
    inset:0;
    background-image:url('/MGames%20Festival.png');
    background-size:cover;
    background-position:center;
    opacity:.24;
  }
  .admin-hero::after {
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(90deg, rgba(176,0,0,.92), rgba(227,27,35,.68), rgba(245,164,0,.32));
  }
  .admin-hero > * {
    position:relative;
    z-index:1;
  }
  .admin-title {
    margin:0;
    font-size:32px;
    line-height:38px;
  }
  .admin-copy {
    margin:8px 0 0;
    color:#fff8e8;
    font-size:15px;
    line-height:22px;
  }
  .admin-actions {
    display:flex;
    gap:10px;
    align-items:flex-start;
    flex-wrap:wrap;
  }
  .admin-button {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-height:38px;
    padding:0 16px;
    border:0;
    border-radius:22px;
    background:#ffffff;
    color:#c40000;
    font-size:14px;
    font-weight:700;
    text-decoration:none;
    cursor:pointer;
  }
  .summary-grid {
    display:grid;
    grid-template-columns:repeat(4, minmax(0, 1fr));
    gap:12px;
    margin:16px 0;
  }
  .summary-card,
  .users-panel {
    background:#ffffff;
    border:1px solid #ffc5cd;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
  }
  .summary-card {
    padding:16px;
  }
  .summary-value {
    display:block;
    color:#c40000;
    font-size:28px;
    line-height:34px;
    font-weight:700;
  }
  .summary-label {
    display:block;
    color:#263238;
    font-size:13px;
    line-height:18px;
  }
  .users-panel {
    padding:18px;
  }
  .search-row {
    display:flex;
    justify-content:space-between;
    gap:12px;
    margin-bottom:16px;
  }
  .search-input {
    width:100%;
    max-width:360px;
    height:40px;
    box-sizing:border-box;
    border:1px solid #ff4f62;
    border-radius:6px;
    padding:8px 12px;
    font-size:15px;
  }
  .table-wrap {
    overflow-x:auto;
  }
  .users-table {
    width:100%;
    min-width:980px;
    border-collapse:collapse;
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
    color:#c40000;
    font-size:12px;
    line-height:16px;
    text-transform:uppercase;
  }
  .status-pill {
    display:inline-flex;
    align-items:center;
    padding:4px 8px;
    border-radius:999px;
    background:#f3f4f6;
    color:#374151;
    font-size:12px;
    line-height:16px;
    font-weight:700;
  }
  .status-pill.online {
    background:#fff2cc;
    color:#b36b00;
  }
  .status-pill.new {
    background:#fff4d6;
    color:#9a6700;
  }
  .view-link {
    color:#c40000;
    font-weight:700;
    text-decoration:none;
  }
  .pagination {
    margin-top:16px;
  }
  @media (max-width:820px) {
    .admin-hero,
    .summary-grid,
    .search-row {
      display:block;
    }
    .admin-actions,
    .summary-card {
      margin-top:12px;
    }
  }
</style>

<div class="admin-shell">
  <section class="admin-hero">
    <div>
      <h1 class="admin-title">Admin Dashboard</h1>
      <p class="admin-copy">Track newly registered partners, active accounts, and user records from one place.</p>
    </div>
    <div class="admin-actions">
      <a class="admin-button" href="{{ route('admin.investments', ['status' => 'pending']) }}">Investment Approvals</a>
      <a class="admin-button" href="{{ route('dashboard') }}">User Dashboard</a>
      <form action="{{ route('logout') }}" method="post">
        @csrf
        <button class="admin-button" type="submit">Logout</button>
      </form>
    </div>
  </section>

  <div class="summary-grid">
    <div class="summary-card">
      <span class="summary-value">{{ $totalUsers }}</span>
      <span class="summary-label">Total users</span>
    </div>
    <div class="summary-card">
      <span class="summary-value">{{ $newUsersCount }}</span>
      <span class="summary-label">New in 24 hours</span>
    </div>
    <div class="summary-card">
      <span class="summary-value">{{ $onlineUsersCount }}</span>
      <span class="summary-label">Online now</span>
    </div>
    <div class="summary-card">
      <span class="summary-value">{{ $adminUsersCount }}</span>
      <span class="summary-label">Admins</span>
    </div>
  </div>

  <section class="users-panel">
    <form class="search-row" method="get" action="{{ route('admin.dashboard') }}">
      <div>
        <strong style="display:block;color:#e31b23;font-size:15px;line-height:20px;">Users</strong>
        <span style="color:#667085;font-size:13px;">{{ $users->total() }} matching accounts</span>
      </div>
      <input class="search-input" name="search" value="{{ $search }}" type="search" placeholder="Search name, email, phone, region">
    </form>

    <div class="table-wrap">
      <table class="users-table">
        <thead>
          <tr>
            <th>User</th>
            <th>Phone</th>
            <th>Region</th>
            <th>Status</th>
            <th>IP Address</th>
            <th>Registered</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($users as $user)
            <tr>
              <td>
                <strong>{{ $user->name }}</strong><br>
                <span>{{ $user->email }}</span>
              </td>
              <td>{{ $user->phone ?: '-' }}</td>
              <td>{{ $user->region ?: '-' }}</td>
              <td>
                <span class="status-pill {{ $user->isOnline() ? 'online' : '' }}">{{ $user->isOnline() ? 'Online' : 'Offline' }}</span>
                @if ($user->created_at && $user->created_at->greaterThan(now()->subDay()))
                  <span class="status-pill new">New</span>
                @endif
                @if ($user->is_admin)
                  <span class="status-pill">Admin</span>
                @endif
              </td>
              <td>{{ $user->last_ip_address ?: '-' }}</td>
              <td>{{ $user->created_at?->format('M d, Y h:i A') ?: '-' }}</td>
              <td><a class="view-link" href="{{ route('admin.users.show', $user) }}">View</a></td>
            </tr>
          @empty
            <tr>
              <td colspan="7">No users found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="pagination">
      {{ $users->links() }}
    </div>
  </section>
</div>
@endsection
