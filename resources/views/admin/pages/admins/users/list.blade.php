<x-app-layout>
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-title-md2 font-bold text-black dark:text-white">
                    アカウント設定
                    </h1>
                    <div class="space-x-2">
                        <x-admin::button :action="[
                            'id' => 'addBtn',
                            'label' => '新規',
                            'type' => 'button',
                            'class' =>
                                'transition duration-150 ease-in-out px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500',
                        ]">
                        </x-admin::button>
                        <x-admin::button :action="[
                            'id' => 'delBtn',
                            'label' => '削除',
                            'type' => 'button',
                            'class' =>
                                'transition duration-150 ease-in-out px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500',
                        ]">
                        </x-admin::button>
                    </div>
            </div>

            <div class="mb-4">
                <x-admin::input type="text" name="searchInput" id="searchInput" placeholder="検索。。。"
                    value="{{ old('client_name') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>

            <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id="dataTable">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="p-3 text-left">
                                <input type="checkbox" id="selectAll"
                                    class="rounded border-gray-600 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white uppercase tracking-wider">
                                ID</th>
                            <th
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white uppercase tracking-wider">
                                LoginId
                            </th>
                            <th
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white uppercase tracking-wider">
                                User
                                Name</th>
                            <th
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white uppercase tracking-wider">
                                Updated
                                Date</th>
                            <th
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white uppercase tracking-wider">
                                Edited
                                User</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 admin_table">
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Pagination Section -->
        <div class="mt-6 flex justify-center space-x-2" id="pagination-controls"></div>
    </div>
</x-app-layout>