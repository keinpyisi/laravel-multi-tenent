@props(['label', 'name', 'type' => 'text', 'placeholder' => '', 'description' => '', 'value' => ''])

<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
    @if ($description)
        <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
    @endif
</div>
