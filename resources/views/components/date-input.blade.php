@props(['disabled' => false, 'value' => null])

<input type="date" {{ $disabled ? 'disabled' : '' }} value="{{ old($attributes->get('name'), $value) ? \Carbon\Carbon::parse(old($attributes->get('name'), $value))->format('Y-m-d') : '' }}" 
{!! $attributes->merge(['class' => 'border-lime-300 focus:border-lime-500 focus:ring-lime-500 rounded-md shadow-sm']) !!}>
