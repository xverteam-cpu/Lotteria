@extends('layouts.app')

@section('content')
<style>
  body { background:#ffffff !important; }
  .invest-page {
    position:relative;
    min-height:100vh;
    overflow:hidden;
    background:#ffffff;
  }
  .invest-art {
    display:block;
    width:100%;
    min-height:100vh;
    object-fit:cover;
    object-position:top center;
  }
  .invest-back {
    position:fixed;
    top:14px;
    left:14px;
    z-index:5;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:42px;
    height:42px;
    border-radius:50%;
    background:rgba(255,255,255,.92);
    color:#c40000;
    font-size:24px;
    line-height:1;
    font-weight:900;
    text-decoration:none;
    box-shadow:0 8px 22px rgba(15,23,42,.14);
  }
  @media (min-width:760px) {
    .invest-page {
      display:flex;
      justify-content:center;
      background:#f5f5f5;
    }
    .invest-art {
      width:auto;
      max-width:540px;
      height:100vh;
      box-shadow:0 14px 34px rgba(15,23,42,.16);
    }
  }
</style>

<main class="invest-page">
  <a class="invest-back" href="{{ route('dashboard') }}" aria-label="Back to dashboard">&lsaquo;</a>
  <img class="invest-art" src="{{ asset('images/Crunch.svg') }}" alt="Lotteria investment">
</main>
@endsection
