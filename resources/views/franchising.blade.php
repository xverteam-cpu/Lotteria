@extends('layouts.app')

@section('content')
<style>
  .franchise-card {
    max-width:560px;
    margin:28px auto;
    padding:28px 24px 16px;
    background:#ffffff;
    border-radius:12px;
    box-shadow:0 10px 26px rgba(0,0,0,0.16);
  }
  .franchise-form {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px 8px;
  }
  .form-field {
    min-width:0;
  }
  .form-field.full {
    grid-column:1 / -1;
  }
  .form-label {
    display:block;
    margin:0 0 10px;
    color:#001a33;
    font-size:16px;
    line-height:20px;
    font-weight:700;
  }
  .form-control,
  .form-select,
  .form-message {
    width:100%;
    box-sizing:border-box;
    border:1px solid #ff4f62;
    border-radius:4px;
    background:#ffffff;
    color:#001a33;
    font-family:Arial, Helvetica, sans-serif;
    font-size:16px;
    line-height:22px;
    outline:none;
  }
  .form-control,
  .form-select {
    height:40px;
    padding:8px 10px;
  }
  .form-message {
    min-height:40px;
    padding:8px 10px;
    resize:vertical;
  }
  .form-control::placeholder,
  .form-message::placeholder {
    color:#9aa3ad;
  }
  .select-wrap {
    position:relative;
  }
  .form-select {
    appearance:none;
    padding-right:52px;
    color:#9aa3ad;
  }
  .select-wrap::after {
    content:'';
    position:absolute;
    top:0;
    right:0;
    width:40px;
    height:40px;
    border-radius:0 4px 4px 0;
    background:#ff5266;
    pointer-events:none;
  }
  .select-wrap::before {
    content:'';
    position:absolute;
    top:13px;
    right:14px;
    z-index:1;
    width:12px;
    height:12px;
    border-right:3px solid #ffffff;
    border-bottom:3px solid #ffffff;
    transform:rotate(45deg);
    pointer-events:none;
  }
  .form-actions {
    grid-column:1 / -1;
    display:flex;
    justify-content:center;
    padding-top:14px;
  }
  .send-button {
    min-width:134px;
    height:40px;
    border:0;
    border-radius:5px;
    background:#ff5266;
    color:#ffffff;
    font-size:16px;
    line-height:20px;
    font-weight:700;
    cursor:pointer;
  }
  @media (max-width:600px) {
    .franchise-card {
      margin:16px auto;
      padding:20px 12px 16px;
    }
    .franchise-form {
      grid-template-columns:1fr 1fr;
      gap:20px 8px;
    }
  }
</style>

<div class="franchise-card">
  <form class="franchise-form" action="{{ route('register.partner') }}" method="post">
    @csrf
    @if ($errors->any())
      <div class="form-field full" style="padding:10px 12px;border-radius:6px;background:#fff0f2;color:#b00000;font-size:14px;line-height:20px;">
        {{ $errors->first() }}
      </div>
    @endif

    <div class="form-field">
      <label class="form-label" for="fullname">Fullname</label>
      <input class="form-control" id="fullname" name="fullname" type="text" value="{{ old('fullname') }}" placeholder="Enter your name">
    </div>

    <div class="form-field">
      <label class="form-label" for="email">Email</label>
      <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Enter your email">
    </div>

    <div class="form-field">
      <label class="form-label" for="phone">Phone</label>
      <input class="form-control" id="phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="Enter your phone number">
    </div>

    <div class="form-field">
      <label class="form-label" for="address">Address</label>
      <input class="form-control" id="address" name="address" type="text" value="{{ old('address') }}" placeholder="Enter your address">
    </div>

    <div class="form-field full">
      <label class="form-label" for="region">Region</label>
      <div class="select-wrap">
        <select class="form-select" id="region" name="region">
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
    </div>

    <div class="form-field">
      <label class="form-label" for="password">Password</label>
      <input class="form-control" id="password" name="password" type="password" placeholder="Create password">
    </div>

    <div class="form-field">
      <label class="form-label" for="password_confirmation">Confirm Password</label>
      <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm password">
    </div>

    <div class="form-field full">
      <label class="form-label" for="message">Message</label>
      <textarea class="form-message" id="message" name="message" placeholder="Leave your message here">{{ old('message') }}</textarea>
    </div>

    <div class="form-actions">
      <button class="send-button" type="submit">Send</button>
    </div>
  </form>
</div>
@endsection
