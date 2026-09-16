@props([
    'title' => '',
    'headerLabel' => 'Formulir',
    'action' => '',
    'method' => 'POST',
    'isEdit' => false,
    'hasFiles' => false,
    'maxWidth' => '720px',
])

<div class="admin-card" style="max-width: {{ $maxWidth }};">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">{{ $headerLabel }}</span>
      <h3 class="admin-card-header-title mb-0">{{ $title }}</h3>
    </div>
  </div>

  <form action="{{ $action }}"
        method="{{ strtoupper($method) === 'GET' ? 'GET' : 'POST' }}"
        @if($hasFiles) enctype="multipart/form-data" @endif
        {{ $attributes->merge(['class' => 'admin-card-body']) }}>
    @if(strtoupper($method) !== 'GET')
      @csrf
      @if(!empty($isEdit))
        @method('PUT')
      @endif
    @endif

    @if ($errors->any())
      <div class="alert alert-danger mb-4">
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{ $slot }}
  </form>
</div>
