@extends('layouts.app')
@section('content')
<div class="card">
  <div class="card-body">
    <h4>Create Poll</h4>
    <form method="POST" action="{{ route('admin.polls.store') }}">
      @csrf
      <div class="mb-3">
        <label>Question</label>
        <input name="question" class="form-control" required>
      </div>
      <div id="options-wrap">
        <div class="mb-2 input-group">
          <input name="options[]" class="form-control" placeholder="Option 1" required>
          <button class="btn btn-danger remove-option" type="button">-</button>
        </div>
        <div class="mb-2 input-group">
          <input name="options[]" class="form-control" placeholder="Option 2" required>
          <button class="btn btn-danger remove-option" type="button">-</button>
        </div>
      </div>
      <button id="add-opt" type="button" class="btn btn-link">+ Add option</button>
      <div class="mt-3">
        <button class="btn btn-primary">Create Poll</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
document.getElementById('add-opt').addEventListener('click', function(){
  const wrap = document.getElementById('options-wrap');
  const div = document.createElement('div');
  div.className = 'mb-2 input-group';
  div.innerHTML = '<input name="options[]" class="form-control" placeholder="Option" required><button class="btn btn-danger remove-option" type="button">-</button>';
  wrap.appendChild(div);
});
document.addEventListener('click', (e)=>{
  if(e.target.classList.contains('remove-option')){
    const parent = e.target.closest('.input-group');
    parent.remove();
  }
});
</script>
@endpush
@endsection