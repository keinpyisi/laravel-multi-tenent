@props([
    'headers' => [],
    'rows' => [],
])

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                @foreach ($headers as $header)
                    <th scope="col" class="px-6 py-3">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    @foreach ($row as $key => $value)
                        @if ($loop->first)
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $value }}
                            </th>
                        @elseif(is_array($value))
                            {{-- If it's an array of actions --}}
                            <td class="px-6 py-4">
                                @foreach ($value as $action)
                                    @php
                                        $target =
                                            isset($action['target']) && $action['target'] === 'new'
                                                ? 'target="_blank"'
                                                : '';
                                    @endphp
                                    @if (is_array($action) && isset($action['label'], $action['class']))
                                        @if (isset($action['type']) && $action['type'] === 'button')
                                            @if (isset($action['url']) && isset($action['method']))
                                                {{-- Button Action --}}
                                                <form action="{{ $action['url'] }}"
                                                    method="{{ in_array($action['method'], ['DELETE', 'PUT']) ? 'POST' : $action['method'] }}"
                                                    class="inline" {{ $target }}>
                                                    @csrf
                                                    @if (in_array($action['method'], ['DELETE', 'PUT']))
                                                        @method($action['method'])
                                                    @endif
                                                    <x-admin::button :action="$action">
                                                        {{ $action['label'] }}
                                                    </x-admin::button>
                                                </form>
                                            @else
                                                <x-admin::button :action="$action">
                                                    {{ $action['label'] }}
                                                </x-admin::button>
                                            @endif
                                        @else
                                            <a href="{{ $action['url'] }}" id="{{ $action['id'] }}"
                                                class="{{ $action['class'] }}"
                                                {{ $target }}>{{ $action['label'] }}</a>
                                        @endif
                                        @if (!$loop->last)
                                            |
                                        @endif
                                    @else
                                        {{-- Handle the case where action is a simple string (fallback) --}}
                                        {{ dd($action) }}
                                    @endif
                                @endforeach
                            </td>
                        @else
                            <td class="px-6 py-4">
                                {{ $value }}
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
