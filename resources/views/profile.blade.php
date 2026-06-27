@extends('layouts.app')

@section('content')
<style>
  .profile-shell {
    max-width: 440px;
    margin: 0 auto 90px;
    padding: 18px 16px 24px;
    background: #f4f6f8;
  }

  .profile-card {
    background: #fff;
    border-radius: 22px;
    padding: 22px;
    box-shadow: 0 16px 40px rgba(3,7,18,0.08);
    border: 1px solid rgba(3,7,18,0.04);
  }

  .profile-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 18px;
  }

  .profile-avatar {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    background: #ed1c24;
    color: #fff;
    display: grid;
    place-items: center;
    font-size: 22px;
    font-weight: 900;
  }

  .profile-name {
    font-size: 22px;
    font-weight: 900;
    margin-bottom: 4px;
  }

  .profile-email {
    font-size: 13px;
    color: #64748b;
    font-weight: 700;
  }

  .profile-details {
    display: grid;
    gap: 12px;
    margin-bottom: 20px;
  }

  .detail-row {
    background: #f8fafc;
    border-radius: 16px;
    padding: 14px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid rgba(3,7,18,0.04);
  }

  .detail-label {
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.02em;
    text-transform: uppercase;
  }

  .detail-value {
    color: #071a44;
    font-size: 15px;
    font-weight: 900;
    text-align: right;
  }

  .logout-form {
    margin-top: 12px;
  }

  .logout-button {
    width: 100%;
    border: none;
    border-radius: 16px;
    padding: 16px 18px;
    background: #ed1c24;
    color: #fff;
    font-size: 15px;
    font-weight: 900;
    cursor: pointer;
  }

  .back-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 14px;
    border-radius: 16px;
    border: 1px solid rgba(3,7,18,0.08);
    text-decoration: none;
    color: #071a44;
    font-weight: 800;
    margin-bottom: 18px;
  }
</style>

<main class="profile-shell">
  <a href="{{ route('dashboard') }}" class="back-link">← Back</a>

  <div class="profile-card">
    @php $user = auth()->user(); @endphp

    <div class="profile-header">
      <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
      <div>
        <div class="profile-name">{{ $user->name }}</div>
        <div class="profile-email">{{ $user->email }}</div>
      </div>
    </div>

    <div class="profile-details">
      <div class="detail-row">
        <div class="detail-label">Username</div>
        <div class="detail-value">{{ $user->username }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Referral</div>
        <div class="detail-value">{{ $user->referred_by ? ($user->referrer?->username ?? 'Linked') : 'None' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Region</div>
        <div class="detail-value">{{ $user->region ?: 'Not set' }}</div>
      </div>
      <div class="detail-row">
        <div class="detail-label">Status</div>
        <div class="detail-value">{{ $user->isOnline() ? 'Online' : 'Offline' }}</div>
      </div>
    </div>

    <form class="logout-form" action="{{ route('logout') }}" method="post">
      @csrf
      <button class="logout-button" type="submit">Log Out</button>
    </form>
  </div>
</main>
@endsection
