@extends('layouts.app')
@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">{{ $poll->question }}</h4>
    <div id="poll-area">
      <ul class="list-group" id="options-list">
        @foreach($poll->options as $opt)
          <li class="list-group-item d-flex justify-content-between align-items-center" data-option-id="{{ $opt->id }}">
            <span>{{ $opt->option_text }}</span>
            <span><span class="badge bg-primary votes-count">{{ $opt->votes_count }}</span></span>
          </li>
        @endforeach
      </ul>
      <div class="mt-3">
        <button id="vote-btn" class="btn btn-success">Vote</button>
      </div>
      <div class="mt-3">
        <small>Shareable link: <a href="{{ route('polls.public.show', $poll->uuid) }}">{{ url()->current() }}</a></small>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  let selectedOptionId = null;
  document.querySelectorAll('#options-list li').forEach(li=>{
    li.addEventListener('click', ()=> {
      document.querySelectorAll('#options-list li').forEach(i=>i.classList.remove('active'));
      li.classList.add('active');
      selectedOptionId = li.dataset.optionId;
    });
  });

  function renderCounts(counts) {
    if (!Array.isArray(counts)) return;
    counts.forEach(c=>{
      const li = document.querySelector('#options-list li[data-option-id="'+c.id+'"]');
      if (li) li.querySelector('.votes-count').innerText = c.votes_count;
    });
  }

  document.getElementById('vote-btn').addEventListener('click', async () => {
    if (!selectedOptionId) { alert('Select an option'); return; }
    try {
      const resp = await axios.post('{{ route("polls.public.vote", $poll->uuid) }}', { option_id: selectedOptionId });
      if (resp?.data?.counts) renderCounts(resp.data.counts);
      document.getElementById('vote-btn').disabled = true;
      alert(resp.data.message || 'Voted');
    } catch (err) {
      alert(err.response?.data?.message || 'Could not vote');
    }
  });

  // Listen for broadcasts
  Echo.channel('poll.{{ $poll->uuid }}')
    .listen('.vote.cast', (e) => {
      // e.counts is an array of {id, option_text, votes_count}
      renderCounts(e.counts);
    });
});
</script>
@endsection