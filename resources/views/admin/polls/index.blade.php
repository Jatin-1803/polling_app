@extends('layouts.app')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h3 class="mb-0">Admin Dashboard</h3>
    <div class="text-muted small">Create polls and view results in real-time.</div>
  </div>
  <a class="btn btn-primary" href="{{ route('admin.polls.create') }}">Create poll</a>
</div>

@if($polls->isEmpty())
  <div class="alert alert-info mb-0">You haven’t created any polls yet.</div>
@else
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>Question</th>
            <th class="text-nowrap">Votes</th>
            <th class="text-nowrap">Share</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($polls as $poll)
            <tr>
              <td class="fw-semibold">{{ $poll->question }}</td>
              <td>{{ $poll->votes_count }}</td>
              <td class="text-nowrap">
                <a href="{{ route('polls.public.show', $poll->uuid) }}" target="_blank">Open</a>
                <span class="text-muted">·</span>
                <button class="btn btn-link p-0" type="button"
                  onclick="navigator.clipboard?.writeText('{{ route('polls.public.show', $poll->uuid) }}'); this.innerText='Copied'; setTimeout(()=>this.innerText='Copy',1200);">
                  Copy
                </button>
              </td>
              <td class="text-end">
                <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.polls.show', $poll->uuid) }}">Results</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif
@endsection
