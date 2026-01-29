<div class="mb-3 text-start position-relative">
    <label class="form-label">{{ $label }}</label>
    <input 
        type="{{ $type ?? 'text' }}" 
        name="{{ $name }}" 
        class="form-control rounded @error($name) is-invalid @enderror" 
        placeholder="{{ $placeholder ?? '' }}" 
        value="{{ old($name) }}"
        {{ $attributes }}
    >
    {{-- Optional slot for extra elements, e.g., password toggle --}}
    {{ $slot }}

    @error($name)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>