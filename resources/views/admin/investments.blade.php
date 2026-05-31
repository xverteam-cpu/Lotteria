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
  .tabs {
    display:flex;
    gap:0;
    margin:24px 0 16px;
    border-bottom:2px solid #e5e7eb;
  }
  .tab {
    padding:12px 20px;
    border:none;
    background:none;
    color:#667085;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    border-bottom:3px solid transparent;
    margin-bottom:-2px;
    transition:all 0.2s;
  }
  .tab.active {
    color:#c40000;
    border-bottom-color:#c40000;
  }
  .tab:hover {
    color:#c40000;
  }
  .summary-grid {
    display:grid;
    grid-template-columns:repeat(3, minmax(0, 1fr));
    gap:12px;
    margin:16px 0;
  }
  .summary-card {
    background:#ffffff;
    border:1px solid #ffc5cd;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
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
  .search-box {
    display:flex;
    gap:8px;
    margin:16px 0;
  }
  .search-box input {
    flex:1;
    padding:10px 14px;
    border:1px solid #ffc5cd;
    border-radius:8px;
    font-size:14px;
  }
  .search-box button {
    padding:10px 20px;
    background:#c40000;
    color:white;
    border:none;
    border-radius:8px;
    font-weight:600;
    cursor:pointer;
  }
  .investments-table {
    width:100%;
    border-collapse:collapse;
    background:#ffffff;
    border:1px solid #ffc5cd;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
  }
  .investments-table thead {
    background:#f9fafb;
    border-bottom:2px solid #ffc5cd;
  }
  .investments-table th {
    padding:14px;
    text-align:left;
    font-size:13px;
    font-weight:700;
    color:#263238;
    text-transform:uppercase;
  }
  .investments-table td {
    padding:14px;
    border-bottom:1px solid #ffc5cd;
    font-size:14px;
    color:#263238;
  }
  .investments-table tbody tr:hover {
    background:#fff5f5;
  }
  .investment-link {
    color:#c40000;
    text-decoration:none;
    font-weight:600;
  }
  .investment-link:hover {
    text-decoration:underline;
  }
  .status-badge {
    display:inline-block;
    padding:4px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
    text-transform:uppercase;
  }
  .status-pending {
    background:#fef08a;
    color:#7c2d12;
  }
  .status-approved {
    background:#dcfce7;
    color:#166534;
  }
  .status-rejected {
    background:#fee2e2;
    color:#991b1b;
  }
  .pagination {
    display:flex;
    justify-content:center;
    gap:8px;
    margin:24px 0;
  }
  .pagination a,
  .pagination span {
    padding:8px 12px;
    border:1px solid #ffc5cd;
    border-radius:6px;
    text-decoration:none;
    color:#c40000;
  }
  .pagination .active {
    background:#c40000;
    color:white;
    border-color:#c40000;
  }
  @media (max-width:768px) {
    .admin-hero {
      flex-direction:column;
    }
    .summary-grid {
      grid-template-columns:1fr;
    }
    .investments-table {
      font-size:12px;
    }
    .investments-table th,
    .investments-table td {
      padding:10px;
    }
  }
</style>

<div class="admin-shell">
  <div class="admin-hero">
    <div>
      <h1 class="admin-title">Investment Approvals</h1>
      <p class="admin-copy">Manage pending bank transfer investments</p>
    </div>
  </div>

  <div class="summary-grid">
    <div class="summary-card">
      <span class="summary-value">{{ $pendingCount }}</span>
      <span class="summary-label">Pending</span>
    </div>
    <div class="summary-card">
      <span class="summary-value">{{ $approvedCount }}</span>
      <span class="summary-label">Approved</span>
    </div>
    <div class="summary-card">
      <span class="summary-value">{{ $rejectedCount }}</span>
      <span class="summary-label">Rejected</span>
    </div>
  </div>

  <div class="tabs">
    <button class="tab {{ $status === 'pending' ? 'active' : '' }}"
            onclick="window.location.href='{{ route('admin.investments', ['status' => 'pending']) }}'">
      Pending
    </button>
    <button class="tab {{ $status === 'approved' ? 'active' : '' }}"
            onclick="window.location.href='{{ route('admin.investments', ['status' => 'approved']) }}'">
      Approved
    </button>
    <button class="tab {{ $status === 'rejected' ? 'active' : '' }}"
            onclick="window.location.href='{{ route('admin.investments', ['status' => 'rejected']) }}'">
      Rejected
    </button>
  </div>

  <form method="GET" class="search-box">
    <input type="hidden" name="status" value="{{ $status }}">
    <input type="text" name="search" placeholder="Search by user name, email, phone, or package..."
           value="{{ $search }}" required>
    <button type="submit">Search</button>
  </form>

  @if($investments->count())
    <table class="investments-table">
      <thead>
        <tr>
          <th>User</th>
          <th>Package</th>
          <th>Amount</th>
          <th>Payment Method</th>
          <th>Status</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($investments as $investment)
          <tr>
            <td>
              <div>
                <div style="font-weight:600;">{{ $investment->user->name }}</div>
                <div style="font-size:12px;color:#667085;">{{ $investment->user->email }}</div>
              </div>
            </td>
            <td>{{ $investment->package_name }}</td>
            <td>${{ number_format($investment->amount, 2) }}</td>
            <td>
              <span style="text-transform:capitalize;">
                {{ str_replace('_', ' ', $investment->payment_method) }}
              </span>
            </td>
            <td>
              <span class="status-badge status-{{ $investment->status }}">
                {{ ucfirst($investment->status) }}
              </span>
            </td>
            <td>{{ $investment->created_at->format('M d, Y') }}</td>
            <td>
              <a href="{{ route('admin.investments.show', $investment) }}" class="investment-link">
                View Details
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="pagination">
      {{ $investments->links() }}
    </div>
  @else
    <div style="text-align:center;padding:40px;color:#667085;">
      <p>No {{ $status }} investments found.</p>
    </div>
  @endif

  <div style="margin-top:20px;">
    <a href="{{ route('admin.dashboard') }}" style="color:#c40000;text-decoration:none;font-weight:600;">
      ← Back to Dashboard
    </a>
  </div>
</div>
@endsection
