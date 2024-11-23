@props([
    'label',
    'name',
    'type' => 'text',
    'placeholder' => '',
    'description' => '',
    'value' => '',
    'rows' => '',
    'cols' => '',
])

<div>
    <label for="{{ $name }}"
        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
    <textarea name="{{ $name }}" id="{{ $name }}" class="border border-gray-300 rounded-md p-2"
        rows="{{ $rows }}" cols="{{ $cols }}"> {{ $value }}</textarea>
    @if ($description)
        <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
    @endif
</div>
