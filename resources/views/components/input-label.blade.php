@props(['disabled' => false, 'value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-lime-700']) }}>
    {{ $value ?? $slot }}
    {{ $disabled ? 'readonly' : '' }}
     
</label>
