<x-app-layout>
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <!-- Breadcrumb Start -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Maintenance Mode Setting
            </h2>
        </div>
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-red dark:text-red">
                状態は全体設定優先。全体でメンテナンスモードがOFFのときに、個別設定を反映します。
            </h2>
        </div>
        <!-- Breadcrumb End -->
        <div class="container mx-auto p-6 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
            <h1 class="text-2xl font-bold mb-6">管理設定</h1>
            <form action="{{ route('admin.maitenance.store') }}" id="create-tenant" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg">
                        <h2 class="text-xl font-semibold mb-4">target site</h2>
                        <div class="flex space-x-4">
                            <x-admin::checkbox name="front_site" label="フロントサイト" value="frontend"
                                :checked="isset($json_data['front_site']) && $json_data['front_site']" />
                            <x-admin::checkbox name="back_site" label="管理サイト" value="backend"
                                :checked="isset($json_data['back_site']) && $json_data['back_site']" />
                        </div>
                        @php
                        $maintenanceOptions = [[
                        'on' => 'ON',
                        'scheduled' => '設定期間中のみON',
                        'off' => 'OFF'
                        ],];

                        $selectedOptions = isset($json_data['maintenance_0']) ? [$json_data['maintenance_0']] :
                        ['off'];
                        @endphp

                        <x-admin::radio_btn name="maintenance" title="maintenance mode" :options="$maintenanceOptions"
                            :selected="$selectedOptions" />
                        <h3 class="text-lg font-semibold mt-4 mb-2">maintenance term</h3>

                        @php
                        $start = isset($json_data['maintenance_term']['maintanance_term_start']) ?
                        $json_data['maintenance_term']['maintanance_term_start'] : '';
                        $end = isset($json_data['maintenance_term']['maintanance_term_end']) ?
                        $json_data['maintenance_term']['maintanance_term_end'] :
                        '';
                        @endphp

                        <x-admin::datepicker name="maintenance_term" :start="$start" :end="$end" />

                        @error('maintenance_term')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror

                        <x-admin::textareas label="allow IP address when maintenance mode (Newline
                            separator)" name="allow_ip" id="allow_ip" type="text" class="form-textarea w-full h-32"
                            :value="isset($json_data['allow_ip']) ? implode(PHP_EOL, $json_data['allow_ip']) : ''" />
                        <x-admin::labels label=" Your IP Address: {{request()->ip()}}" />
                        @error('allow_ip')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg">
                        <x-admin::textareas label="front site maintenance page message" name="front_main_message"
                            id="front_main_mess" type="text" class="form-textarea w-full h-32"
                            :value="$json_data['front_main_message'] ?? ''" />
                    </div>

                    <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg">
                        <x-admin::textareas label="backend site maintenance page message" name="back_main_message"
                            id="back_main_mess" type="text" class="form-textarea w-full h-32"
                            :value="$json_data['back_main_message'] ?? ''" />
                    </div>
                    <x-admin::button :action="[
                            'label' => '保存',
                            'type' => 'button',
                            'class' =>
                                'main_save_btn bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded',
                        ]">
                    </x-admin::button>
                </div>
            </form>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-title-md2 font-bold text-black dark:text-white">管理設定</h2>
            </div>
            <div class="bg-gray-800 rounded-lg shadow">

                <table class="bg-gray-100 w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                    id="dataTable">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th
                                class="px-6 py-4 font-medium text-black-900 whitespace-nowrap dark:text-white uppercase tracking-wider">
                                Client Name</th>
                            <th
                                class="px-6 py-4 font-medium text-black-900 whitespace-nowrap dark:text-white uppercase tracking-wider">
                                対象
                            </th>
                            <th
                                class="px-6 py-4 font-medium text-black-900 whitespace-nowrap dark:text-white uppercase tracking-wider">
                                期間</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 tenant_table">
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
            <!-- Pagination Section -->
            <div class="mt-6 flex justify-center space-x-2" id="pagination-controls"></div>
        </div>
</x-app-layout>