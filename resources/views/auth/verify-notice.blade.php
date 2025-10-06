@extends('layouts.onboarding')

@section('title','Verify your email')
@section('card-content')
  <div style="max-width:520px;margin:0 auto;text-align:center;">
    <h1>Check your inbox</h1>
    <p>We sent a verification link to <strong>{{ $email ?? 'your email' }}</strong>.</p>

    @if (session('status'))
      <div style="margin:12px 0;color:green;">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('auth.verification.resend') }}" style="margin-top:12px;">
      @csrf
      <button class="btn btn-secondary" type="submit">Resend verification email</button>
    </form>

    <p style="margin-top:16px;color:#666;">Didn’t get it? Check spam or try resending.</p>
  </div>
@endsection
