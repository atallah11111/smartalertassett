@props(['disabled' => false, 'type' => 'text', 'name' => null, 'value' => null])

<input 
    {{ $disabled ? 'disabled' : '' }}
    type="{{ $type }}"
    name="{{ $name }}"
    value="{{ old($name, $value) }}"
    {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full']) }}
/>
