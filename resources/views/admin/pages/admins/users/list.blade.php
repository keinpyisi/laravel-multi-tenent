<x-app-layout>
    <div class="bg-gray-900 min-h-screen p-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold text-white">AccountSetting</h1>
                <div class="space-x-2">
                    <button id="addBtn"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">add</button>
                    <button id="delBtn"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">del</button>
                </div>
            </div>

            <div class="mb-4">
                <input type="text" id="searchInput" placeholder="Search..."
                    class="w-full px-4 py-2 rounded-lg border border-gray-600 bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
                <table class="w-full" id="dataTable">
                    <thead>
                        <tr class="bg-gray-700">
                            <th class="p-3 text-left">
                                <input type="checkbox" id="selectAll"
                                    class="rounded border-gray-600 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">ID</th>
                            <th class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">LoginId
                            </th>
                            <th class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">User
                                Name</th>
                            <th class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Updated
                                Date</th>
                            <th class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Edited
                                User</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <tr>
                            <td class="p-3">
                                <input type="checkbox"
                                    class="rounded border-gray-600 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="p-3 text-sm text-gray-300">1</td>
                            <td class="p-3 text-sm text-gray-300">developer</td>
                            <td class="p-3 text-sm text-gray-300">開発者</td>
                            <td class="p-3 text-sm text-gray-300">2023-08-22 16:44:30</td>
                            <td class="p-3 text-sm text-gray-300">開発者</td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
