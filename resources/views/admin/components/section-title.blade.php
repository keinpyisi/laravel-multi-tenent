<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        {{ $title }}
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ $description }}
        </p>
    </h2>
    <div class="px-4 sm:px-0">
        {{ $aside ?? '' }}
    </div>
</div>
