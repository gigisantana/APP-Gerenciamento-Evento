@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-lime-700 text-start text-base font-medium text-lime-700 focus:outline-none focus:border-lime-700 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-lime-500 hover:text-lime-700  hover:border-lime-500 focus:outline-none focus:text-lime-700 focus:border-lime-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
