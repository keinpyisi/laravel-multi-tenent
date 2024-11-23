<x-app-layout>
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                新規クライアント登録
            </h2>
        </div>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="{{ route('admin.tenants.store') }}" id="create-tenant" method="POST">
                @csrf
                <div class="px-4 py-5 bg-white dark:bg-gray-800 shadow sm:rounded-md">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Client Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">クライアント情報</h3>

                            <div class="mb-4">
                                <x-admin::input-field type="text" label="クライアント名*" name="client_name"
                                    id="client_name" placeholder="例:アスコン商店" value="{{ old('client_name') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                @error('client_name')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <x-admin::input-field type="text" label="フリガナ*" name="kana" id="kana"
                                    placeholder="例:アスコンショウテン" value="{{ old('kana') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                @error('kana')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <x-admin::input-field type="text" label="アカウント名*" name="account_name"
                                    id="account_name" placeholder="例:ascon_shouten" value="{{ old('account_name') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                <p class="mt-1 text-sm text-gray-500">半角英小文字と数字、_のみ。URLのディレクトリ名</p>
                                @error('account_name')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <x-admin::input-field type="file" label="ロゴ*" name="logo" id="logo"
                                    class="mt-1 block w-full" />
                            </div>

                            <div class="mb-4">
                                <x-admin::input-field type="url" label="ホームページ" name="homepage" id="homepage"
                                    placeholder="例:https://www.ascon.co.jp" value="{{ old('homepage') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                <p class="mt-1 text-sm text-gray-500">フロントサイトから遷移するホームページ</p>
                                @error('url')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <x-admin::input-field label="お問い合わせメールアドレス" name="e_mail" type="email"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    value="{{ old('e_mail') }}" placeholder="例:support@ascon.co.jp" />
                                @error('e_mail')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- User Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">ユーザー情報</h3>

                            <div class="mb-4">
                                <x-admin::input-field label="ログインID*" type="text" name="login_id" id="login_id"
                                    placeholder="半角英数字のみ" value="{{ old('login_id') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                @error('login_id')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <x-admin::input-field label="ユーザー名*" type="text" name="user_name" id="user_name"
                                    value="{{ old('user_name') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                @error('user_name')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <x-admin::input-field label="パスワード*" type="password" name="password" id="password"
                                    value="{{ old('password') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                @error('password')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <x-admin::input-field label="パスワードを再度入力*" type="password" name="password_confirmation"
                                    id="password_confirmation" value="{{ old('password_confirmation') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                @error('password_confirmation')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <p class="mt-4 text-sm text-red-600">
                                *ログインIDとパスワードは、クライアントページにログインする際に必要となりますので、大切に保管しておいてください。</p>
                        </div>
                    </div>
                </div>
            </form>
            <div class="flex justify-end mt-6 space-x-3">
                <x-admin::button :action="[
                    'form' => 'create-tenant',
                    'label' => '追加',
                    'type' => 'button',
                    'class' => 'px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600',
                ]">
                </x-admin::button>
                <form action="{{ route('admin.tenants.index') }}" method="GET" class="inline">
                    <x-admin::button :action="[
                        'label' => 'キャンセル',
                        'type' => 'button',
                        'class' => 'px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300',
                    ]">
                    </x-admin::button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
