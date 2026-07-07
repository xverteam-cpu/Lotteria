@extends('layouts.app')

@section('content')
@include('partials.admin-dashboard-styles')

<style>
  .user-row {
    cursor: pointer;
    transition: background-color 0.18s ease;
  }

  .user-row:hover,
  .user-row:focus-visible {
    background-color: #fff8f8;
    outline: none;
  }

  .user-modal-overlay {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: rgba(0, 0, 0, 0.6);
    z-index: 300;
  }

  .user-modal-overlay.is-open {
    display: flex;
  }

  .user-modal-card {
    width: min(980px, 100%);
    max-height: 90vh;
    overflow-y: auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 24px 70px rgba(0, 0, 0, 0.22);
    padding: 24px;
    position: relative;
  }

  .user-modal-close {
    position: absolute;
    top: 14px;
    right: 14px;
    border: 0;
    background: transparent;
    color: #64748b;
    font-size: 24px;
    cursor: pointer;
  }

  .user-modal-header {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f1f5f9;
  }

  .user-modal-title {
    margin: 0;
    color: #111827;
    font-size: 22px;
  }

  .user-modal-subtitle {
    margin: 4px 0 0;
    color: #64748b;
    font-size: 13px;
  }

  .user-modal-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 18px;
  }

  .user-modal-field {
    padding: 12px 14px;
    border-radius: 10px;
    background: #fff7f7;
    border: 1px solid #ffe2e6;
  }

  .user-modal-field-label {
    display: block;
    margin-bottom: 4px;
    color: #c40000;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.06em;
  }

  .user-modal-field-value {
    color: #111827;
    font-size: 14px;
    line-height: 20px;
    overflow-wrap: anywhere;
  }

  .user-history-section {
    margin-top: 16px;
  }

  .user-history-title {
    margin: 0 0 10px;
    color: #111827;
    font-size: 16px;
  }

  .user-history-list {
    display: grid;
    gap: 10px;
  }

  .user-history-item {
    padding: 12px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    background: #f9fafb;
  }

  .user-history-item strong {
    display: block;
    color: #111827;
    margin-bottom: 4px;
  }

  .user-history-meta {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    color: #64748b;
    font-size: 12px;
    flex-wrap: wrap;
  }

  @media (max-width: 640px) {
    .user-modal-grid {
      grid-template-columns: 1fr;
    }

    .user-modal-card {
      padding: 20px;
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
        <button class="header-btn" type="button" onclick="document.getElementById('manageSlotsModal').style.display='block';">
          <span>🎛️</span>
          <span>Manage Slots</span>
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

  <!-- Manage Package Slots Modal -->
  <div id="manageSlotsModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 200; padding: 20px;">
    <div style="background: white; border-radius: 8px; max-width: 520px; margin: 60px auto; padding: 32px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: #1f2937;">Manage Package Slot Counts</h2>
        <button onclick="document.getElementById('manageSlotsModal').style.display='none';" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #9ca3af;">&times;</button>
      </div>
      <form method="POST" action="{{ route('admin.package-slots.update') ?? '#' }}" style="display: flex; flex-direction: column; gap: 18px;">
        @csrf
        @foreach ($packages as $packageKey => $package)
          <div>
            <label style="display: block; font-size: 13px; font-weight: 600; color: #1f2937; margin-bottom: 8px;">{{ $package['name'] }} Remaining Slots</label>
            <input
              type="number"
              name="slots[{{ $packageKey }}]"
              min="0"
              value="{{ $packageSlots[$packageKey] ?? 0 }}"
              required
              style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; color: #1f2937; box-sizing: border-box;">
          </div>
        @endforeach
        <div style="display: flex; gap: 12px;">
          <button type="submit" class="header-btn" style="flex: 1; margin: 0;">
            ✓ Update Slots
          </button>
          <button type="button" onclick="document.getElementById('manageSlotsModal').style.display='none';" style="flex: 1; padding: 0 16px; height: 36px; border: 1px solid #d1d5db; border-radius: 6px; background: white; color: #374151; font-weight: 600; cursor: pointer;">Cancel</button>
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
              <th>Referred By</th>
              <th>Available Balance</th>
              <th>Status</th>
              <th>Registered</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $user)
              <tr
                class="user-row"
                tabindex="0"
                role="button"
                aria-label="View details for {{ $user->name }}"
                onclick="openUserModal('userModal-{{ $user->id }}')"
                onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); openUserModal('userModal-{{ $user->id }}'); }"
              >
                <!-- User Information -->
                <td>
                  <span class="user-cell-name">{{ $user->name }}</span>
                  <span class="user-cell-email">{{ $user->email }}</span>
                </td>
                <!-- Referred By -->
                <td>
                  @php
                    $referrer = $user->referrer;
                  @endphp
                  {{ $referrer ? ($referrer->name ?: $referrer->email) : '—' }}
                </td>
                <!-- Available Balance -->
                <td style="white-space: nowrap;">
                  {{ $user->balance != null ? '$' . number_format((float) $user->balance, 2) : '$0.00' }}
                </td>
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
                <!-- Registration Date -->
                <td style="white-space: nowrap;">
                  {{ $user->created_at?->format('M d, Y') ?: '—' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="empty-state">
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

@foreach ($users as $user)
  <template id="userModal-{{ $user->id }}">
    <div class="user-modal-card">
      <button class="user-modal-close" type="button" onclick="closeUserModal()" aria-label="Close user details">&times;</button>
      <div class="user-modal-header">
        <div>
          <h2 class="user-modal-title">{{ $user->name }}</h2>
          <p class="user-modal-subtitle">Registered {{ $user->created_at?->format('M d, Y h:i A') ?: '—' }}</p>
        </div>
        <span class="status-badge {{ $user->isOnline() ? 'online' : 'offline' }}">
          {{ $user->isOnline() ? 'Online' : 'Offline' }}
        </span>
      </div>

      <div class="user-modal-grid">
        <div class="user-modal-field">
          <span class="user-modal-field-label">Email</span>
          <div class="user-modal-field-value">{{ $user->email }}</div>
        </div>
        <div class="user-modal-field">
          <span class="user-modal-field-label">Phone</span>
          <div class="user-modal-field-value">{{ $user->phone ?: '—' }}</div>
        </div>
        <div class="user-modal-field">
          <span class="user-modal-field-label">Referred By</span>
          <div class="user-modal-field-value">
            @php $referrer = $user->referrer; @endphp
            {{ $referrer ? ($referrer->name ?: $referrer->email) : '—' }}
          </div>
        </div>
        <div class="user-modal-field">
          <span class="user-modal-field-label">Available Balance</span>
          <div class="user-modal-field-value">{{ $user->balance != null ? '$' . number_format((float) $user->balance, 2) : '$0.00' }}</div>
        </div>
        <div class="user-modal-field">
          <span class="user-modal-field-label">Region</span>
          <div class="user-modal-field-value">{{ $user->region ?: '—' }}</div>
        </div>
        <div class="user-modal-field">
          <span class="user-modal-field-label">Address</span>
          <div class="user-modal-field-value">{{ $user->address ?: '—' }}</div>
        </div>
        <div class="user-modal-field">
          <span class="user-modal-field-label">IP Address</span>
          <div class="user-modal-field-value">{{ $user->last_ip_address ?: '—' }}</div>
        </div>
        <div class="user-modal-field">
          <span class="user-modal-field-label">IP Location</span>
          <div class="user-modal-field-value">{{ $user->region ?: $user->address ?: 'Not available' }}</div>
        </div>
        <div class="user-modal-field" style="grid-column: 1 / -1;">
          <span class="user-modal-field-label">Last Seen</span>
          <div class="user-modal-field-value">{{ $user->last_seen_at?->format('M d, Y h:i A') ?: '—' }}</div>
        </div>
      </div>

      <div class="user-history-section">
        <h3 class="user-history-title">Deposit / Investment History</h3>
        @if($user->investments()->latest()->get()->isNotEmpty())
          <div class="user-history-list">
            @foreach($user->investments()->latest()->get() as $investment)
              <div class="user-history-item">
                <strong>{{ $investment->package_name ?: 'Investment' }}</strong>
                <div class="user-history-meta">
                  <span>{{ $investment->status }}</span>
                  <span>${{ number_format((float) $investment->amount, 2) }}</span>
                  <span>{{ $investment->created_at?->format('M d, Y') ?: '—' }}</span>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <p style="margin:0;color:#64748b;">No investment history.</p>
        @endif
      </div>

      <div class="user-history-section">
        <h3 class="user-history-title">Withdraw History</h3>
        @if($user->withdrawals()->latest()->get()->isNotEmpty())
          <div class="user-history-list">
            @foreach($user->withdrawals()->latest()->get() as $withdrawal)
              <div class="user-history-item">
                <strong>${{ number_format((float) $withdrawal->amount, 2) }}</strong>
                <div class="user-history-meta">
                  <span>{{ $withdrawal->status }}</span>
                  <span>{{ $withdrawal->payment_method }}</span>
                  <span>{{ $withdrawal->created_at?->format('M d, Y') ?: '—' }}</span>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <p style="margin:0;color:#64748b;">No withdrawal history.</p>
        @endif
      </div>

      <div class="user-history-section">
        <h3 class="user-history-title">Income History</h3>
        @if($user->referralEarnings()->latest()->get()->isNotEmpty())
          <div class="user-history-list">
            @foreach($user->referralEarnings()->latest()->get() as $earning)
              <div class="user-history-item">
                <strong>${{ number_format((float) $earning->amount, 2) }}</strong>
                <div class="user-history-meta">
                  <span>Referral commission</span>
                  <span>{{ $earning->created_at?->format('M d, Y') ?: '—' }}</span>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <p style="margin:0;color:#64748b;">No income history.</p>
        @endif
      </div>
    </div>
  </template>
@endforeach

<div id="userDetailsModal" class="user-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="userDetailsTitle">
  <div id="userDetailsModalBody" class="user-modal-card"></div>
</div>

<script>
  function openUserModal(templateId) {
    var template = document.getElementById(templateId);
    var body = document.getElementById('userDetailsModalBody');
    var modal = document.getElementById('userDetailsModal');

    if (!template || !body || !modal) {
      return;
    }

    body.innerHTML = template.innerHTML;
    modal.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function closeUserModal() {
    var modal = document.getElementById('userDetailsModal');
    if (modal) {
      modal.classList.remove('is-open');
      document.body.style.overflow = '';
    }
  }

  document.addEventListener('click', function (event) {
    var modal = document.getElementById('userDetailsModal');
    if (!modal || !modal.classList.contains('is-open')) {
      return;
    }

    if (event.target === modal) {
      closeUserModal();
    }
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
      closeUserModal();
    }
  });
</script>
@endsection
