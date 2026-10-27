@props(['name', 'label' => null, 'type' => 'text', 'value' => '', 'required' => false, 'placeholder' => '', 'help' => null])

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label fw-semibold small text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <input type="{{ $type }}"
           id="{{ $name }}"
           name="{{ $name }}"
           value="{{ old($name, $value) }}"
           placeholder="{{ $placeholder }}"
           {{ $required ? 'required' : '' }}
           {{ $attributes->merge(['class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : '')]) }}>

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if($help && !$errors->has($name))
        <div class="form-text small text-muted">{{ $help }}</div>
    @endif
</div>
