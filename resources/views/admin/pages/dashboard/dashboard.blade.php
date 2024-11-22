<x-app-layout>
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <!-- Breadcrumb Start -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Tables
            </h2>

            <nav>
                <ol class="flex items-center gap-2">
                    <li>
                        <a class="font-medium" href="index.html">Dashboard /</a>
                    </li>
                    <li class="font-medium text-primary">Tables</li>
                </ol>
            </nav>
        </div>
        <!-- Breadcrumb End -->

        <!-- ====== Table Section Start -->
        <div class="flex flex-col gap-10">
            @php
            $headers = ["Product name", "Color", "Category", "Price", "Action"];
            $rows = [
            ["Apple MacBook Pro 17\"", "Silver", "Laptop", "$2999", "#"],
            ["Microsoft Surface Pro", "White", "Laptop PC", "$1999", "#"],
            ["Magic Mouse 2", "Black", "Accessories", "$99", "#"]
            ];
            @endphp
            <x-admin::tables :headers="$headers" :rows="$rows" />

        </div>
        <!-- ====== Table Section End -->
    </div>
</x-app-layout>