@props(['title'])

<div class="mt-5 md:mt-0 md:col-span-2">
    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ $title }}</h3>
    <div class="mb-4 space-y-4">
        {{ $slot }}
    </div>
</div>
