@extends('layouts.app')

@section('content')
<style>
  .login-shell {
    min-height:calc(100vh - 36px);
    display:flex;
    align-items:center;
    justify-content:center;
    padding:28px 0;
    background-image:url('/MGames%20Festival.png');
    background-size:cover;
    background-position:center top;
  }
  .login-panel {
    width:100%;
    max-width:390px;
    text-align:center;
  }
  .login-logo {
    width:86px;
    height:86px;
    margin:0 auto 18px;
    border-radius:18px;
    overflow:hidden;
    background:#ffffff;
    box-shadow:0 8px 22px rgba(0,0,0,0.12);
  }
  .login-logo img {
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
  }
  .login-card {
    padding:28px 24px 22px;
    background:#ffffff;
    border-radius:16px;
    box-shadow:0 10px 28px rgba(0,0,0,0.14);
    text-align:left;
  }
  .login-title {
    margin:0 0 20px;
    color:#e31b23;
    font-size:26px;
    line-height:32px;
    font-weight:700;
    text-align:center;
  }
  .login-field {
    margin-bottom:16px;
  }
  .login-label {
    display:block;
    margin:0 0 8px;
    color:#001a33;
    font-size:15px;
    line-height:20px;
    font-weight:700;
  }
  .login-input {
    width:100%;
    height:44px;
    box-sizing:border-box;
    border:1px solid #ff4f62;
    border-radius:5px;
    padding:10px 12px;
    color:#001a33;
    font-family:Arial, Helvetica, sans-serif;
    font-size:16px;
    outline:none;
  }
  .login-input::placeholder {
    color:#9aa3ad;
  }
  .login-row {
    display:flex;
    align-items:center;
    justify-content:flex-end;
    margin:-2px 0 18px;
  }
  .forgot-link {
    color:#e31b23;
    font-size:14px;
    line-height:18px;
    font-weight:700;
    text-decoration:none;
  }
  .login-button,
  .signup-button {
    width:100%;
    height:44px;
    border-radius:6px;
    font-size:16px;
    line-height:20px;
    font-weight:700;
    cursor:pointer;
  }
  .login-button {
    border:0;
    background:#e31b23;
    color:#ffffff;
  }
  .signup-button {
    display:flex;
    align-items:center;
    justify-content:center;
    box-sizing:border-box;
    margin-top:12px;
    border:1px solid #e31b23;
    background:#ffffff;
    color:#e31b23;
    text-decoration:none;
  }
</style>

<div class="login-shell">
  <div class="login-panel">
    <div class="login-logo">
      <img src="https://www.lotteria.vn/grs-static/icons/logo_512.png" alt="Lotteria">
    </div>

    <form class="login-card" action="#" method="post">
      @csrf
      <h1 class="login-title">Partner Login</h1>

      <div class="login-field">
        <label class="login-label" for="email">Email</label>
        <input class="login-input" id="email" name="email" type="email" placeholder="Enter your email">
      </div>

      <div class="login-field">
        <label class="login-label" for="password">Password</label>
        <input class="login-input" id="password" name="password" type="password" placeholder="Enter your password">
      </div>

      <div class="login-row">
        <a class="forgot-link" href="#">Forgot password?</a>
      </div>

      <button class="login-button" type="submit">Login</button>
      <a class="signup-button" href="{{ route('franchising') }}">Sign Up</a>
    </form>
  </div>
</div>
@endsection
