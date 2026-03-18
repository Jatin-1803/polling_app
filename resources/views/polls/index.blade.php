@extends('layouts.app')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h3 class="mb-0">Public Polls</h3>
    <div class="text-muted small">Open polls you can vote on.</div>
  </div>
</div>

@if($polls->isEmpty())
  <div class="alert alert-info mb-0">No active polls yet.</div>
@else
  <div class="row g-3">
    @foreach($polls as $poll)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <div class="fw-semibold mb-2">{{ $poll->question }}</div>
            <a class="btn btn-primary btn-sm" href="{{ route('polls.public.show', $poll->uuid) }}">Open poll</a>
            <button class="btn btn-outline-secondary btn-sm" type="button"
              onclick="navigator.clipboard?.writeText('{{ route('polls.public.show', $poll->uuid) }}'); this.innerText='Copied'; setTimeout(()=>this.innerText='Copy link',1200);">
              Copy link
            </button>
          </div>
          <div class="card-footer text-muted small">
            {{ $poll->created_at->diffForHumans() }}
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endif
@endsection

