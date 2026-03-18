@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7 col-lg-6">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h4 class="mb-1">Register Admin</h4>
        <div class="text-muted mb-3">Create an admin account to create and manage polls.</div>

        <form method="POST" action="{{ route('admin.register.post') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input name="name" value="{{ old('name') }}" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required minlength="6">
            <div class="form-text">Minimum 6 characters.</div>
          </div>

          <button class="btn btn-primary w-100" type="submit">Create admin account</button>
        </form>
      </div>
      <div class="card-footer bg-white text-muted small">
        Already have an account? <a href="{{ route('admin.login') }}">Login</a>
      </div>
    </div>
  </div>
</div>
@endsection

