@extends('layouts.app')

@section('content')
@include('partials.admin-dashboard-styles')



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
          <select id="adminPackageSelect" name="package" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; color: #1f2937;">
            <option value="">-- Choose a package --</option>
            @foreach($packages as $packageKey => $package)
              <option value="{{ $packageKey }}">{{ $package['name'] }} — ${{ number_format($package['price'], 2, '.', ',') }} — {{ number_format($package['daily_interest_rate'], 2) }}% daily</option>
            @endforeach
          </select>

          <div style="margin-top:10px; display:flex; gap:8px; flex-wrap:wrap;">
            @foreach($packages as $packageKey => $package)
              <button type="button" class="package-quick-btn" data-package-key="{{ $packageKey }}" style="padding:8px 12px; border-radius:6px; border:1px solid #d1d5db; background:white; cursor:pointer; font-weight:700;">
                {{ $package['name'] }}
              </button>
            @endforeach
          </div>
          <script>
            (function () {
              var buttons = document.querySelectorAll('.package-quick-btn');
              var select = document.getElementById('adminPackageSelect');
              buttons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                  var key = btn.getAttribute('data-package-key');
                  if (select) select.value = key;
                });
              });
            }());
          </script>
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
                <td class="user-cell-ip">
                  {{ $user->last_ip_address ?: '—' }}
                </td>
                <!-- Registration Date -->
                <td style="white-space: nowrap;">
                  {{ $user->created_at?->format('M d, Y') ?: '—' }}
                </td>
                <!-- Action -->
                <td style="text-align: center;">
                  <a class="view-link" href="{{ route('admin.users.show', $user) }}">View Details →</a>
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
