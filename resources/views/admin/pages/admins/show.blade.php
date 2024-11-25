<x-app-layout>
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <!-- Breadcrumb Start -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                クライアント管理

            </h2>
        </div>
        <!-- Breadcrumb End -->

        <!-- Tab Section -->
        <!-- Tab Section -->
        <div x-data="{ activeTab: 0 }" class="mb-6">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                {{ $tenant->client_name }} ( {{ $tenant->domain }})
            </h2>
            <!-- Tab Navigation -->
            <div class="flex space-x-4 border-b border-gray-200 dark:border-gray-700">
                <button :class="{
                        'text-blue-600 dark:text-blue-400 border-b-2 border-blue-600': activeTab ===
                            0,
                        'text-gray-500 dark:text-gray-400': activeTab !== 0
                    }" @click="activeTab = 0" class="py-2 px-4 text-sm font-medium focus:outline-none">
                    詳細
                </button>
                <button :class="{
                        'text-blue-600 dark:text-blue-400 border-b-2 border-blue-600': activeTab ===
                            1,
                        'text-gray-500 dark:text-gray-400': activeTab !== 1
                    }" @click="activeTab = 1" class="py-2 px-4 text-sm font-medium focus:outline-none">
                    使用状況
                </button>
                <button :class="{
                        'text-blue-600 dark:text-blue-400 border-b-2 border-blue-600': activeTab ===
                            3,
                        'text-gray-500 dark:text-gray-400': activeTab !== 3
                    }" @click="activeTab = 3"
                    class="user_setting_btn py-2 px-4 text-sm font-medium focus:outline-none">
                    ユーザー設定
                </button>
                <button :class="{
                        'text-blue-600 dark:text-blue-400 border-b-2 border-blue-600': activeTab ===
                            2,
                        'text-gray-500 dark:text-gray-400': activeTab !== 2
                    }" @click="activeTab = 2" class="py-2 px-4 text-sm font-medium focus:outline-none">
                    認証情報
                </button>
            </div>

            <!-- Tab Content -->
            <div class="mt-4">
                <div x-show="activeTab === 0">
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 mb-6">

                        <form action="{{ route('admin.tenants.update', $tenant->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex flex-col">
                                    <x-admin::labels label="アカウント名*"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />

                                    <x-admin::input-field type="hidden" name="account_name" id="account_name"
                                        label="{{ $tenant->account_name }}" value="{{ $tenant->account_name }}" />
                                </div>
                                <div class="flex flex-col">
                                    <x-admin::input-field type="text" label="クライアント名*" name="client_name"
                                        id="client_name" placeholder="例:アスコン商店" value="{{ $tenant->client_name }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                    @error('client_name')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex flex-col">
                                    <x-admin::input-field type="text" label="フリガナ*" name="kana" id="kana"
                                        placeholder="例:アスコン商店" value="{{ $tenant->kana }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                    @error('kana')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex flex-col">
                                    <x-admin::input-field type="text" label="責任者名" name="person_in_charge"
                                        id="person_in_charge" placeholder="刹那恵" value="{{ $tenant->person_in_charge }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                    @error('kana')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex flex-col">
                                    <x-admin::input-field type="file" label="ロゴ*" name="logo" id="logo"
                                        class="mt-1 block w-full" />
                                    @php
                                    $logoPath = $tenant->logo; // e.g., 'tenants/ecos/logo/logo-dark.png'
                                    $domain = explode('/', $logoPath)[0]; // ecos
                                    $file = basename($logoPath); // logo-dark.png
                                    @endphp
                                    <img src="{{ route('tenant.logo', ['domain' => $domain, 'file' => $file]) }}"
                                        alt='Logo' class='h-25 w-35 object-cover'>
                                    @error('logo')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex flex-col">
                                    <x-admin::input-field label="お問い合わせメールアドレス" name="support_mail" type="email"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        value="{{ $tenant->support_mail }}" placeholder="例:support@ascon.co.jp" />
                                    @error('support_mail')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex flex-col">
                                    <x-admin::input-field label="メールアドレス" name="e_mail" type="email"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        value="{{ $tenant->e_mail }}" placeholder="例:support@ascon.co.jp" />
                                    @error('e_mail')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex flex-col">
                                    <x-admin::input-field label="電話番号" name="tel" type="tel"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        value="{{ $tenant->tel }}" placeholder="01-123-3345" />
                                    @error('tel')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex flex-col">
                                    <x-admin::input-field label="FAX番号" name="fax_number" type="tel"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        value="{{ $tenant->fax_number }}" placeholder="01-123-3345" />
                                    @error('tel')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex flex-col">
                                    <x-admin::input-field label="ホームページ" name="url" type="url"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        value="{{ $tenant->url }}" placeholder="https://www.google.com/" />
                                    @error('url')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex flex-col">
                                    <x-admin::input-field label="郵便番号" name="post_code" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        value="{{ $tenant->post_code }}" placeholder="134-0084" />
                                    @error('post_code')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex flex-col">
                                    <x-admin::textareas label="住所" name="address" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        rows=3 cols=35 value="{{ $tenant->address }}" />
                                    @error('address')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex flex-col">
                                    <x-admin::textareas label="備考" name="note" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        rows=3 cols=35 value="{{ $tenant->note }}" />
                                    @error('note')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <x-admin::button :action="[
                                    'label' => '変更',
                                    'type' => 'submit',
                                    'class' =>
                                        'transition duration-150 ease-in-out px-6 py-3 bg-green-500 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500',
                                ]">
                                </x-admin::button>
                            </div>
                        </form>
                    </div>
                </div>
                <div x-show="activeTab === 1">
                    <!-- Content for Tab 2 -->
                    <!-- Section for Overview -->
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Overview Block 1 -->
                            <div
                                class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 mb-6 p-4 rounded-lg shadow-md">
                                <h3 class="text-lg font-semibold">全体</h3>
                                <p class="text-gray-500 mt-2">全体サイズ: {{ $all_usage['total_size'] }}</p>
                                <p class="text-gray-500">空き容量: {{ $all_usage['free_space'] }}</p>
                                <p class="text-gray-500">使用容量: {{ $all_usage['used_space'] }}</p>
                                <p class="text-gray-500">使用率: {{ $all_usage['usage_rate'] }}</p>
                            </div>

                            <!-- Overview Block 2 -->
                            <div
                                class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 mb-6 p-4 rounded-lg shadow-md">
                                <h3 class="text-lg font-semibold">企画添付ファイル</h3>
                                <p class="text-gray-500 mt-2">使用容量: {{ $client_usage['total_size'] }}</p>
                            </div>
                        </div>
                    </div>

                </div>
                <div x-show="activeTab === 2">
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 mb- max-w-3xl mx-auto py-6">
                        <!-- Tab Navigation -->
                        <label class="text-red-500">クライアント作成後、または、認証リセット後の同一セッション中のみ</label>
                        <br>
                        <label class="text-red-500">パスワードの表示、または、認証情報を一度のみダウンロードできるます。</label>
                        <br>
                        <label class="text-red-500">パスワードが分からなくなった場合は、リセットしてください。 </label>
                        <!-- Tab Content -->
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 rounded-lg shadow-md">
                            <!-- Title -->
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">ユーザー情報</h2>

                            <!-- Form -->
                            <form action="{{ route('admin.tenants.reset', $tenant->domain) }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 gap-4">
                                    <!-- User Name -->
                                    <div class="flex justify-between items-center">
                                        <x-admin::labels label="ユーザー名"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                        <span class="font-semibold"> {{ $tenant->domain }}</span>
                                    </div>

                                    <!-- Password -->
                                    <div class="flex justify-between items-center">
                                        <x-admin::labels label="パスワード"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                        @if (isset(session('success')['basic_pass']) &&
                                        session('success')['basic_pass'])
                                        <span class="font-semibold">{{ session('success')['basic_pass'] }}</span>
                                        @else
                                        <span class="font-semibold">********</span>
                                        @endif

                                    </div>
                                </div>

                                <!-- Reset Password Button -->
                                <div class="mt-6 text-right">
                                    <x-admin::button :action="[
                                        'label' => 'Basic認証リセット',
                                        'id' => 'basic_reset_btn',
                                        'url' => route('admin.tenants.destroy', [$tenant->domain]),
                                        'method' => 'DELETE',
                                        'type' => 'button',
                                        'which_type' => 'button',
                                        'data-id' => $tenant->domain,
                                        'class' =>
                                            'basic_reset_btn transition duration-150 ease-in-out px-6 py-3 basic_reset_btn transition duration-150 ease-in-outpx-4 py-2 text-white bg-red-500',
                                    ]">
                                    </x-admin::button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div x-show="activeTab === 3">
                    <!-- Content for Tab 2 -->
                    <!-- Section for Overview -->
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 mb-6">
                        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
                            <div class="max-w-7xl mx-auto">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-title-md2 font-bold text-black dark:text-white">
                                        アカウント設定
                                        </h1>
                                        <div class="space-x-2">
                                            <x-admin::button :action="[
                            'id' => 'addUserBtn',
                            'label' => '新規',
                            'type' => 'button',
                            'class' =>
                                'transition duration-150 ease-in-out px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500',
                        ]">
                                            </x-admin::button>
                                            <x-admin::button :action="[
                            'id' => 'delUserBtn',
                            'label' => '削除',
                            'type' => 'button',
                            'class' =>
                                'transition duration-150 ease-in-out px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500',
                        ]">
                                            </x-admin::button>
                                        </div>
                                </div>

                                <div class="mb-4">
                                    <x-admin::input type="text" name="user_search" id="user_search" placeholder="検索。。。"
                                        value="{{ old('client_name') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                </div>

                                <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
                                    <table
                                        class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                                        id="dataTable">
                                        <thead
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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
                                        <tbody class="divide-y divide-gray-700 tenent_user_table">
                                            <!-- Add more rows as needed -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Pagination Section -->
                            <div class="mt-6 flex justify-center space-x-2" id="pagination-controls"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <!-- ====== Table Section Start -->
        <div class="flex flex-col gap-10">


            <!-- Pagination Section -->
            <div class="mt-6">
                {{-- <x-admin::paginations :paginator="$tenents" /> <!-- Pagination links --> --}}
            </div>
        </div>
        <!-- ====== Table Section End -->
    </div>
</x-app-layout>