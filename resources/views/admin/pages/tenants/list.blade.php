<x-app-layout>
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <!-- Breadcrumb Start -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                クライアント
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
                                'url' => '/client/1/edit',
                            ],
                            [
                                'label' => '詳細',
                                'class' =>
                                    'transition duration-150 ease-in-out px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500',
                                'id' => 'detail_btn',
                                'url' => '/client/1/view',
                            ],
                            [
                                'label' => '削除',
                                'class' =>
                                    'focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900',
                                'id' => 'delete_btn',
                                'type' => 'button',
                            ],
                        ],
                        // or any action-related button/link
                    ];
                }
            @endphp
            <x-admin::tables :headers="$headers" :rows="$rows" />

        </div>
        <!-- ====== Table Section End -->
    </div>
</x-app-layout>
