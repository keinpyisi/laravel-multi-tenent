@props([
'headers' => [],
'rows' => []
])

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                @foreach($headers as $header)
                <th scope="col" class="px-6 py-3">
                    {{ $header }}
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                @foreach($row as $key => $value)
                @if($loop->first)
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $value }}
                </th>
                @elseif($loop->last)
                <td class="px-6 py-4">
                    <a href="{{ $value }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
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