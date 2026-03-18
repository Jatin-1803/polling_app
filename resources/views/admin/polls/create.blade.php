@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div>
            <h4 class="mb-0">Create Poll</h4>
            <div class="text-muted small">One question, multiple options.</div>
          </div>
          <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.polls.index') }}">Back</a>
        </div>

        <form method="POST" action="{{ route('admin.polls.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Question</label>
            <input name="question" class="form-control" required value="{{ old('question') }}">
          </div>

          <label class="form-label">Options</label>
          <div id="options-wrap" class="mb-2">
            @php($oldOptions = old('options', ['', '']))
            @foreach($oldOptions as $i => $val)
              <div class="mb-2 input-group">
                <input name="options[]" class="form-control" placeholder="Option {{ $i + 1 }}" required value="{{ $val }}">
                <button class="btn btn-outline-danger remove-option" type="button" title="Remove option">Remove</button>
              </div>
            @endforeach
          </div>

          <button id="add-opt" type="button" class="btn btn-link px-0">+ Add option</button>

          <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Create poll</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.polls.index') }}">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.getElementById('add-opt').addEventListener('click', function(){
  const wrap = document.getElementById('options-wrap');
  const div = document.createElement('div');
  div.className = 'mb-2 input-group';
  div.innerHTML = '<input name="options[]" class="form-control" placeholder="Option" required><button class="btn btn-outline-danger remove-option" type="button">Remove</button>';
  wrap.appendChild(div);
});
document.addEventListener('click', (e)=>{
  if(e.target.classList.contains('remove-option')){
    const wrap = document.getElementById('options-wrap');
    if (wrap.querySelectorAll('.input-group').length <= 2) return;
    const parent = e.target.closest('.input-group');
    parent.remove();
  }
});
</script>
@endpush
@endsection
