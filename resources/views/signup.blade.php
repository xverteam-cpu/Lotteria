@extends('layouts.app')

@section('content')
<style>
  .signup-shell {
    min-height: calc(100vh - 36px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 28px 16px;
    background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
  }
  .signup-card {
    width: 100%;
    max-width: 620px;
    padding: 28px 24px 24px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 28px rgba(0,0,0,0.14);
  }
  .signup-title {
    margin: 0 0 8px;
    color: #e31b23;
    font-size: 28px;
    font-weight: 700;
    text-align: center;
  }
  .signup-subtitle {
    margin: 0 0 20px;
    color: #667085;
    font-size: 15px;
    text-align: center;
  }
  .signup-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }
  .signup-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  .signup-field.full {
    grid-column: 1 / -1;
  }
  .signup-label {
    color: #001a33;
    font-size: 15px;
    font-weight: 700;
  }
  .signup-input,
  .signup-select,
  .signup-textarea {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #ff4f62;
    border-radius: 6px;
    padding: 10px 12px;
    color: #001a33;
    font-size: 15px;
    outline: none;
  }
  .signup-textarea {
    min-height: 96px;
    resize: vertical;
  }
  .signup-actions {
    grid-column: 1 / -1;
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 8px;
  }
  .signup-button,
  .login-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 160px;
    height: 44px;
    border-radius: 6px;
    font-size: 15px;
    font-weight: 700;
    text-decoration: none;
  }
  .signup-button {
    border: 0;
    background: #e31b23;
    color: #ffffff;
    cursor: pointer;
  }
  .login-link {
    border: 1px solid #e31b23;
    color: #e31b23;
    background: #ffffff;
  }
  @media (max-width: 640px) {
    .signup-form {
      grid-template-columns: 1fr;
    }
    .signup-actions {
      flex-direction: column;
    }
    .signup-button,
    .login-link {
      width: 100%;
    }
  }
</style>

<div class="signup-shell">
  <div class="signup-card">
    <h1 class="signup-title">Sign Up</h1>
    <p class="signup-subtitle">Create your partner account to get started.</p>

    <form class="signup-form" action="{{ route('register.partner') }}" method="post">
      @csrf
      @if ($errors->any())
        <div class="signup-field full" style="padding:10px 12px;border-radius:6px;background:#fff0f2;color:#b00000;font-size:14px;line-height:20px;">
          {{ $errors->first() }}
        </div>
      @endif

      <div class="signup-field">
        <label class="signup-label" for="fullname">Fullname</label>
        <input class="signup-input" id="fullname" name="fullname" type="text" value="{{ old('fullname') }}" placeholder="Enter your name">
      </div>

      <div class="signup-field">
        <label class="signup-label" for="email">Email</label>
        <input class="signup-input" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Enter your email">
      </div>

      <div class="signup-field">
        <label class="signup-label" for="phone">Phone</label>
        <input class="signup-input" id="phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="Enter your phone number">
      </div>

      <div class="signup-field">
        <label class="signup-label" for="address">Address</label>
        <input class="signup-input" id="address" name="address" type="text" value="{{ old('address') }}" placeholder="Enter your address">
      </div>

      <div class="signup-field full">
        <label class="signup-label" for="region">Region</label>
        <select class="signup-select" id="region" name="region">
          <option value="" disabled @selected(! old('region'))>Select your region</option>
          <option value="ncr" @selected(old('region') === 'ncr')>National Capital Region (NCR)</option>
          <option value="car" @selected(old('region') === 'car')>Cordillera Administrative Region (CAR)</option>
          <option value="region-1" @selected(old('region') === 'region-1')>Region I - Ilocos Region</option>
          <option value="region-2" @selected(old('region') === 'region-2')>Region II - Cagayan Valley</option>
          <option value="region-3" @selected(old('region') === 'region-3')>Region III - Central Luzon</option>
          <option value="region-4a" @selected(old('region') === 'region-4a')>Region IV-A - CALABARZON</option>
          <option value="mimaropa" @selected(old('region') === 'mimaropa')>MIMAROPA Region</option>
          <option value="region-5" @selected(old('region') === 'region-5')>Region V - Bicol Region</option>
          <option value="region-6" @selected(old('region') === 'region-6')>Region VI - Western Visayas</option>
          <option value="region-7" @selected(old('region') === 'region-7')>Region VII - Central Visayas</option>
          <option value="region-8" @selected(old('region') === 'region-8')>Region VIII - Eastern Visayas</option>
          <option value="region-9" @selected(old('region') === 'region-9')>Region IX - Zamboanga Peninsula</option>
          <option value="region-10" @selected(old('region') === 'region-10')>Region X - Northern Mindanao</option>
          <option value="region-11" @selected(old('region') === 'region-11')>Region XI - Davao Region</option>
          <option value="region-12" @selected(old('region') === 'region-12')>Region XII - SOCCSKSARGEN</option>
          <option value="region-13" @selected(old('region') === 'region-13')>Region XIII - Caraga</option>
          <option value="barmm" @selected(old('region') === 'barmm')>Bangsamoro Autonomous Region in Muslim Mindanao (BARMM)</option>
        </select>
      </div>

      <div class="signup-field">
        <label class="signup-label" for="password">Password</label>
        <input class="signup-input" id="password" name="password" type="password" placeholder="Create password">
      </div>

      <div class="signup-field">
        <label class="signup-label" for="password_confirmation">Confirm Password</label>
        <input class="signup-input" id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm password">
      </div>

      <div class="signup-field full">
        <label class="signup-label" for="message">Message</label>
        <textarea class="signup-textarea" id="message" name="message" placeholder="Leave your message here">{{ old('message') }}</textarea>
      </div>

      <div class="signup-actions">
        <button class="signup-button" type="submit">Create Account</button>
        <a class="login-link" href="{{ route('login') }}">Back to Login</a>
      </div>
    </form>
  </div>
</div>
@endsection
