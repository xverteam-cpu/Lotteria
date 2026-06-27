@extends('layouts.app')

@section('content')
<style>
  * {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
  }

  body {
    background-color: #f5f6f8;
  }

  .admin-shell {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0;
    min-height: 100vh;
    background-color: #f5f6f8;
  }

  /* Top Navigation Bar */
  .admin-nav {
    display: flex;
    gap: 8px;
    padding: 16px 24px;
    background-color: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    position: sticky;
    top: 0;
    z-index: 100;
    flex-wrap: wrap;
    align-items: center;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
  }

  .admin-nav-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 36px;
    padding: 0 16px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background-color: #ffffff;
    color: #374151;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
  }

  .admin-nav-btn:hover {
    background-color: #f3f4f6;
    border-color: #c40000;
    color: #c40000;
  }

  .admin-nav-btn.active {
    background-color: #c40000;
    border-color: #c40000;
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(196, 0, 0, 0.15);
  }

  /* Header Section */
  .admin-header {
    padding: 20px 24px;
    background-color: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    position: sticky;
    top: 52px;
    z-index: 99;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  }

  .admin-header-content {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
  }

  .admin-header-info {
    flex: 1;
  }

  .admin-title {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    letter-spacing: -0.5px;
  }

  .admin-copy {
    margin: 4px 0 0;
    font-size: 12px;
    color: #6b7280;
    font-weight: 400;
  }

  .admin-header-actions {
    display: flex;
    gap: 12px;
    align-items: center;
  }

  .header-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 36px;
    padding: 0 16px;
    border: none;
    border-radius: 6px;
    background-color: #c40000;
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    text-decoration: none;
  }

  .header-btn:hover {
    background-color: #a30000;
    box-shadow: 0 4px 12px rgba(196, 0, 0, 0.2);
  }

  .header-btn:active {
    transform: scale(0.98);
  }

  /* Content Wrapper */
  .admin-content {
    padding: 24px;
    max-width: 1400px;
    margin: 0 auto;
  }

  /* Statistics Grid */
  .summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }

  .summary-card {
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
  }

  .summary-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border-color: #d1d5db;
  }

  .summary-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #c40000, #e31b23);
  }

  .summary-card-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background-color: #fef2f2;
    color: #c40000;
    font-size: 20px;
    margin-bottom: 12px;
  }

  .summary-value {
    display: block;
    font-size: 32px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1;
    margin-bottom: 4px;
  }

  .summary-label {
    display: block;
    font-size: 12px;
    font-weight: 500;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  /* Users Panel */
  .users-panel {
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    overflow: hidden;
  }

  .users-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
    flex-wrap: wrap;
    gap: 16px;
  }

  .users-panel-title {
    flex: 1;
    min-width: 200px;
  }

  .users-panel-title-main {
    display: block;
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 2px;
  }

  .users-panel-title-sub {
    display: block;
    font-size: 12px;
    color: #9ca3af;
    font-weight: 400;
  }

  .search-box {
    position: relative;
    flex: 1;
    min-width: 250px;
  }

  .search-box form {
    display: flex;
    gap: 8px;
  }

  .search-input {
    flex: 1;
    height: 36px;
    padding: 0 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 13px;
    color: #374151;
    background-color: #f9fafb;
    transition: all 0.2s ease;
  }

  .search-input::placeholder {
    color: #9ca3af;
  }

  .search-input:focus {
    outline: none;
    background-color: #ffffff;
    border-color: #c40000;
    box-shadow: 0 0 0 3px rgba(196, 0, 0, 0.1);
  }

  /* Table Styles */
  .table-wrap {
    overflow-x: auto;
  }

  .users-table {
    width: 100%;
    border-collapse: collapse;
  }

  .users-table thead {
    background-color: #f9fafb;
    border-bottom: 2px solid #e5e7eb;
  }

  .users-table th {
    padding: 12px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .users-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #f3f4f6;
    font-size: 13px;
    color: #374151;
  }

  .users-table tbody tr {
    transition: background-color 0.15s ease;
  }

  .users-table tbody tr:hover {
    background-color: #f9fafb;
  }

  .user-cell-name {
    font-weight: 600;
    color: #1f2937;
    display: block;
    margin-bottom: 2px;
  }

  .user-cell-email {
    font-size: 12px;
    color: #9ca3af;
  }

  .status-badges {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
  }

  /* Status Badges */
  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
  }

  .status-badge.online {
    background-color: #ecfdf5;
    color: #047857;
  }

  .status-badge.online::before {
    content: '●';
    font-size: 8px;
  }

  .status-badge.offline {
    background-color: #f3f4f6;
    color: #6b7280;
  }

  .status-badge.offline::before {
    content: '●';
    font-size: 8px;
  }

  .status-badge.new {
    background-color: #fffbeb;
    color: #b45309;
  }

  .status-badge.admin {
    background-color: #f0f1f9;
    color: #4f46e5;
  }

  .view-link {
    display: inline-flex;
    align-items: center;
    color: #c40000;
    font-weight: 600;
    text-decoration: none;
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s ease;
    cursor: pointer;
  }

  .view-link:hover {
    background-color: #fef2f2;
    text-decoration: underline;
  }

  /* Pagination */
  .pagination {
    display: flex;
    justify-content: center;
    gap: 4px;
    padding: 16px 24px;
    border-top: 1px solid #f3f4f6;
  }

  .pagination a,
  .pagination span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    text-decoration: none;
    color: #374151;
    background-color: #ffffff;
    transition: all 0.2s ease;
  }

  .pagination a:hover {
    background-color: #f3f4f6;
    border-color: #9ca3af;
  }

  .pagination .active {
    background-color: #c40000;
    color: #ffffff;
    border-color: #c40000;
  }

  .pagination .disabled {
    color: #d1d5db;
    cursor: not-allowed;
  }

  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 40px 24px;
    color: #9ca3af;
  }

  /* Responsive Design */
  @media (max-width: 1024px) {
    .summary-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 768px) {
    .admin-nav {
      padding: 12px 16px;
      gap: 6px;
    }

    .admin-nav-btn {
      padding: 0 12px;
      height: 32px;
      font-size: 12px;
    }

    .admin-header {
      padding: 16px 12px;
      top: 48px;
    }

    .admin-header-content {
      flex-direction: column;
      align-items: flex-start;
    }

    .admin-header-actions {
      width: 100%;
      gap: 8px;
    }

    .header-btn {
      flex: 1;
      padding: 0 12px;
      height: 32px;
      font-size: 12px;
    }

    .admin-title {
      font-size: 20px;
    }

    .admin-content {
      padding: 16px;
    }

    .summary-grid {
      grid-template-columns: 1fr;
    }

    .users-panel-header {
      flex-direction: column;
      align-items: flex-start;
    }

    .search-box {
      width: 100%;
    }

    .search-box form {
      flex-direction: column;
    }

    .users-table {
      font-size: 12px;
    }

    .users-table th,
    .users-table td {
      padding: 10px 12px;
    }

    .status-badges {
      gap: 4px;
    }

    .status-badge {
      padding: 3px 8px;
      font-size: 10px;
    }
  }

  @media (max-width: 480px) {
    .admin-shell {
      padding: 0;
    }

    .admin-nav {
      overflow-x: auto;
      padding: 8px 12px;
    }

    .admin-nav-btn {
      flex-shrink: 0;
      padding: 0 10px;
      height: 30px;
      font-size: 11px;
    }

    .admin-header {
      padding: 16px 12px;
    }

    .admin-title {
      font-size: 18px;
    }

    .admin-copy {
      font-size: 12px;
    }

    .admin-content {
      padding: 12px;
    }

    .users-table th {
      font-size: 10px;
    }

    .users-table td {
      padding: 8px 10px;
      font-size: 11px;
    }

    .user-cell-name {
      font-size: 12px;
    }

    .user-cell-email {
      font-size: 11px;
    }

    .summary-card {
      padding: 16px;
    }

    .summary-value {
      font-size: 28px;
    }
  }
</style>

<div class="admin-shell">
  <!-- Top Navigation Bar -->
  <nav class="admin-nav">
    <a class="admin-nav-btn active" href="{{ route('admin.dashboard') }}" title="View all users">
      <span>👥</span>
      <span>All Users</span>
    </a>
    <a class="admin-nav-btn" href="{{ route('admin.withdrawals') }}" title="Pending withdrawal requests">
      <span>💸</span>
      <span>Withdrawals</span>
    </a>
    <a class="admin-nav-btn" href="{{ route('admin.investments', ['status' => 'pending']) }}" title="Pending investment deposits">
      <span>💰</span>
      <span>Deposits</span>
    </a>
    <form action="{{ route('logout') }}" method="post" style="display:inline; margin-left:auto;">
      @csrf
      <button class="admin-nav-btn" type="submit" title="Sign out from admin panel">
        <span>🚪</span>
        <span>Logout</span>
      </button>
    </form>
  </nav>

  <!-- Header Section -->
  <div class="admin-header">
    <div class="admin-header-content">
      <div class="admin-header-info">
        <h1 class="admin-title">Admin Dashboard</h1>
        <p class="admin-copy">Monitor all registered users, track activity, and manage user accounts</p>
      </div>
      <div class="admin-header-actions">
        <button class="header-btn" type="button" onclick="document.getElementById('sendPackageModal').style.display='block';">
          <span>📦</span>
          <span>Send Package</span>
        </button>
        <button class="header-btn" type="button" onclick="document.getElementById('sendFundsModal').style.display='block';">
          <span>💳</span>
          <span>Send Funds</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Send Package Modal -->
  <div id="sendPackageModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 200; padding: 20px;">
    <div style="background: white; border-radius: 8px; max-width: 500px; margin: 60px auto; padding: 32px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: #1f2937;">Send Package to User</h2>
        <button onclick="document.getElementById('sendPackageModal').style.display='none';" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #9ca3af;">&times;</button>
      </div>
      <form method="POST" action="{{ route('admin.send-package') ?? '#' }}" style="display: flex; flex-direction: column; gap: 16px;">
        @csrf
        <div>
          <label style="display: block; font-size: 13px; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Select User</label>
          <select name="user_id" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; color: #1f2937;">
            <option value="">-- Choose a user --</option>
            @foreach($users as $user)
              <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display: block; font-size: 13px; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Select Package</label>
          <select name="package" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; color: #1f2937;">
            <option value="">-- Choose a package --</option>
            <option value="starter">Starter - $100</option>
            <option value="professional">Professional - $500</option>
            <option value="premium">Premium - $1000</option>
            <option value="enterprise">Enterprise - $5000</option>
          </select>
        </div>
        <div style="display: flex; gap: 12px;">
          <button type="submit" class="header-btn" style="flex: 1; margin: 0;">
            ✓ Send Package
          </button>
          <button type="button" onclick="document.getElementById('sendPackageModal').style.display='none';" style="flex: 1; padding: 0 16px; height: 36px; border: 1px solid #d1d5db; border-radius: 6px; background: white; color: #374151; font-weight: 600; cursor: pointer;">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Send Funds Modal -->
  <div id="sendFundsModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 200; padding: 20px;">
    <div style="background: white; border-radius: 8px; max-width: 500px; margin: 60px auto; padding: 32px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: #1f2937;">Send Funds to User</h2>
        <button onclick="document.getElementById('sendFundsModal').style.display='none';" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #9ca3af;">&times;</button>
      </div>
      <form method="POST" action="{{ route('admin.send-funds') ?? '#' }}" style="display: flex; flex-direction: column; gap: 16px;">
        @csrf
        <div>
          <label style="display: block; font-size: 13px; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Select User</label>
          <select name="user_id" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; color: #1f2937;">
            <option value="">-- Choose a user --</option>
            @foreach($users as $user)
              <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display: block; font-size: 13px; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Amount (USD)</label>
          <input type="number" name="amount" step="0.01" min="0.01" required placeholder="Enter amount" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; color: #1f2937; box-sizing: border-box;">
        </div>
        <div>
          <label style="display: block; font-size: 13px; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Reason/Note</label>
          <textarea name="reason" placeholder="Enter reason for sending funds" rows="3" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; color: #1f2937; box-sizing: border-box; font-family: inherit;"></textarea>
        </div>
        <div style="display: flex; gap: 12px;">
          <button type="submit" class="header-btn" style="flex: 1; margin: 0;">
            ✓ Send Funds
          </button>
          <button type="button" onclick="document.getElementById('sendFundsModal').style.display='none';" style="flex: 1; padding: 0 16px; height: 36px; border: 1px solid #d1d5db; border-radius: 6px; background: white; color: #374151; font-weight: 600; cursor: pointer;">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Content Area -->
  <div class="admin-content">
    <!-- Users Panel -->
    <div class="users-panel">
      <!-- Panel Header with Search -->
      <div class="users-panel-header">
        <div class="users-panel-title">
          <span class="users-panel-title-main">User Management</span>
          <span class="users-panel-title-sub">{{ $users->total() }} matching accounts</span>
        </div>
        <div class="search-box">
          <form method="get" action="{{ route('admin.dashboard') }}">
            <input
              class="search-input"
              name="search"
              value="{{ $search }}"
              type="search"
              placeholder="Search by name, email, phone..."
              aria-label="Search users"
            >
          </form>
        </div>
      </div>

      <!-- Data Table -->
      <div class="table-wrap">
        <table class="users-table">
          <thead>
            <tr>
              <th>User Information</th>
              <th>Contact</th>
              <th>Region</th>
              <th>Status</th>
              <th>IP Address</th>
              <th>Registered</th>
              <th style="width: 60px; text-align: center;">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $user)
              <tr>
                <!-- User Information -->
                <td>
                  <span class="user-cell-name">{{ $user->name }}</span>
                  <span class="user-cell-email">{{ $user->email }}</span>
                </td>
                <!-- Contact -->
                <td>{{ $user->phone ?: '—' }}</td>
                <!-- Region -->
                <td>{{ $user->region ?: '—' }}</td>
                <!-- Status Badges -->
                <td>
                  <div class="status-badges">
                    <span class="status-badge {{ $user->isOnline() ? 'online' : 'offline' }}">
                      {{ $user->isOnline() ? 'Online' : 'Offline' }}
                    </span>
                    @if ($user->created_at && $user->created_at->greaterThan(now()->subDay()))
                      <span class="status-badge new">New</span>
                    @endif
                    @if ($user->is_admin)
                      <span class="status-badge admin">Admin</span>
                    @endif
                  </div>
                </td>
                <!-- IP Address -->
                <td style="font-family: 'Courier New', monospace; font-size: 11px;">
                  {{ $user->last_ip_address ?: '—' }}
                </td>
                <!-- Registration Date -->
                <td style="white-space: nowrap;">
                  {{ $user->created_at?->format('M d, Y') ?: '—' }}
                </td>
                <!-- Action -->
                <td style="text-align: center;">
                  <a class="view-link" href="{{ route('admin.users.show', $user) }}">View</a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="empty-state">
                  <p style="margin: 0;">No users found. Try adjusting your search filters.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="pagination">
        {{ $users->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
