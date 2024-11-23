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
                <button
                    :class="{
                        'text-blue-600 dark:text-blue-400 border-b-2 border-blue-600': activeTab ===
                            0,
                        'text-gray-500 dark:text-gray-400': activeTab !== 0
                    }"
                    @click="activeTab = 0" class="py-2 px-4 text-sm font-medium focus:outline-none">
                    詳細
                </button>
                <button
                    :class="{
                        'text-blue-600 dark:text-blue-400 border-b-2 border-blue-600': activeTab ===
                            1,
                        'text-gray-500 dark:text-gray-400': activeTab !== 1
                    }"
                    @click="activeTab = 1" class="py-2 px-4 text-sm font-medium focus:outline-none">
                    Tab 2
                </button>
                <button
                    :class="{
                        'text-blue-600 dark:text-blue-400 border-b-2 border-blue-600': activeTab ===
                            2,
                        'text-gray-500 dark:text-gray-400': activeTab !== 2
                    }"
                    @click="activeTab = 2" class="py-2 px-4 text-sm font-medium focus:outline-none">
                    Tab 3
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
                                    <x-admin::labels label="{{ $tenant->account_name }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />

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
                                    @error('tel')
                                        <div class="text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex flex-col">
                                    <x-admin::input-field label="郵便番号" name="post_code" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        value="{{ $tenant->post_code }}" placeholder="134-0084" />
                                    @error('tel')
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
                    <h3 class="text-lg font-semibold text-black dark:text-white">Tab 2 Content</h3>
                    <p>Content for the second tab goes here.</p>
                </div>
                <div x-show="activeTab === 2">
                    <!-- Content for Tab 3 -->
                    <h3 class="text-lg font-semibold text-black dark:text-white">Tab 3 Content</h3>
                    <p>Content for the third tab goes here.</p>
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
