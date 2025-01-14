@props(['disabled' => false, 'value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-md text-lime-700']) }}>
    {{ $value ?? $slot }}
    {{ $disabled ? 'readonly' : '' }}
     
</label>
