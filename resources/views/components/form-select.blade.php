@props(['name', 'label' => null, 'required' => false, 'help' => null])

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label fw-semibold small text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <select id="{{ $name }}"
            name="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'form-select ' . ($errors->has($name) ? 'is-invalid' : '')]) }}>
        {{ $slot }}
    </select>

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if($help && !$errors->has($name))
        <div class="form-text small text-muted">{{ $help }}</div>
    @endif
</div>
