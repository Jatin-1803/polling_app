@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h4 class="mb-1">Admin Login</h4>
        <div class="text-muted mb-3">Sign in to manage your polls.</div>

        <form method="POST" action="{{ route('admin.login.post') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button class="btn btn-primary w-100" type="submit">Login</button>
        </form>
      </div>
      <div class="card-footer bg-white text-muted small">
        Need an admin account? <a href="{{ route('admin.register') }}">Register</a>
      </div>
    </div>
  </div>
</div>
@endsection

