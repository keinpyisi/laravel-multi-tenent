<x-app-layout>
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <!-- Breadcrumb Start -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                クライアント
                <form action="{{ route('admin.tenants.create') }}" method="GET" class="inline">
                    <x-admin::button :action="[
                        'label' => '新規',
                        'type' => 'button',
                        'class' =>
                            'transition duration-150 ease-in-out px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500',
                    ]">
                    </x-admin::button>
                </form>
            </h2>
        </div>
        <!-- Breadcrumb End -->

        <!-- ====== Table Section Start -->
        <div class="flex flex-col gap-10">
            @php
                $headers = ['ID', 'クライアントの名前', '備考', '操作'];
                foreach ($tenents as $tenent) {
                    $rows[] = [
                        $tenent->id,
                        $tenent->client_name,
                        $tenent->note,
                        [
                            [
                                'label' => 'リンク',
                                'class' =>
                                    'transition duration-150 ease-in-out px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500',
                                'type' => 'button',
                                'id' => 'link_btn',
                                'url' => route('tenant.client.index', ['tenant' => $tenent->domain]), // Dynamically generating the route
                                'method' => 'GET',
                                'target' => 'new', // This will open in a new window/tab
                            ],
                            [
                                'label' => '詳細',
                                'class' =>
                                    'transition duration-150 ease-in-out px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500',
                                'id' => 'detail_btn',
                                'url' => route('admin.tenants.show', [$tenent]), // Dynamically setting the detail URL
                                'type' => 'button',
                            ],
                            [
                                'label' => '削除',
                                'class' =>
                                    'transition duration-150 ease-in-out px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500',
                                'id' => 'delete_btn',
                                'method' => 'GET',
                                'type' => 'button',
                            ],
                        ],
                    ];
                }

            @endphp
            <x-admin::tables :headers="$headers" :rows="$rows" />

        </div>
        <!-- ====== Table Section End -->
    </div>
</x-app-layout>
