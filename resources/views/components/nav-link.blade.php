@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-4 border-lime-700 text-sm font-medium leading-5 text-lime-700 focus:outline-none focus:border-lime-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-4 border-transparent text-sm font-medium leading-5 text-lime-500 hover:text-lime-700  hover:border-lime-500 focus:outline-none focus:text-lime-700 focus:border-lime-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
