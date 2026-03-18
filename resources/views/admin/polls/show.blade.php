@extends('layouts.app')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h3 class="mb-1">Poll Results</h3>
    <div class="text-muted">{{ $poll->question }}</div>
  </div>
  <div class="d-flex gap-2">
    <a class="btn btn-outline-secondary" href="{{ route('admin.polls.index') }}">Back</a>
    <a class="btn btn-primary" href="{{ route('polls.public.show', $poll->uuid) }}" target="_blank">Open public page</a>
  </div>
</div>

<div class="card shadow-sm mb-3">
  <div class="card-body">
    <div class="row g-3">
      <div class="col-lg-6">
        <div class="fw-semibold mb-2">Shareable link</div>
        <div class="input-group">
          <input id="share-link" class="form-control" value="{{ route('polls.public.show', $poll->uuid) }}" readonly>
          <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard?.writeText(document.getElementById('share-link').value)">Copy</button>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="fw-semibold mb-2">Status</div>
        <span class="badge {{ $poll->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $poll->is_active ? 'Active' : 'Inactive' }}</span>
      </div>
    </div>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <div class="fw-semibold mb-2">Live results</div>
    <div id="results">
      @php($total = max(1, (int) $poll->options->sum('votes_count')))
      @foreach($poll->options as $opt)
        @php($pct = round(($opt->votes_count / $total) * 100))
        <div class="mb-3" data-option-id="{{ $opt->id }}">
          <div class="d-flex justify-content-between small mb-1">
            <div class="text-truncate me-2">{{ $opt->option_text }}</div>
            <div><span class="votes-count">{{ $opt->votes_count }}</span> · <span class="votes-pct">{{ $pct }}</span>%</div>
          </div>
          <div class="progress" role="progressbar" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar" style="width: {{ $pct }}%"></div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

@push('scripts')
<script>
function renderCounts(counts) {
  const totalVotes = Math.max(1, counts.reduce((sum, c) => sum + (parseInt(c.votes_count, 10) || 0), 0));
  counts.forEach(c => {
    const row = document.querySelector('#results [data-option-id="'+c.id+'"]');
    if (!row) return;
    const votes = parseInt(c.votes_count, 10) || 0;
    const pct = Math.round((votes / totalVotes) * 100);
    row.querySelector('.votes-count').innerText = votes;
    row.querySelector('.votes-pct').innerText = pct;
    const bar = row.querySelector('.progress-bar');
    bar.style.width = pct + '%';
    row.querySelector('.progress')?.setAttribute('aria-valuenow', pct);
  });
}

document.addEventListener('DOMContentLoaded', function(){
  if (!window.Echo) return;
  Echo.channel('poll.{{ $poll->uuid }}')
    .listen('.vote.cast', (e) => {
      if (e?.counts) renderCounts(e.counts);
    });
});
</script>
@endpush
@endsection

