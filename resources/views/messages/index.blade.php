@php
    use App\Models\Message;
    /** @var Message $message */
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Messages</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('messages.store') }}">
                        @csrf
                        <div class="flex gap-4 items-end">
                            <div class="w-1/4">
                                <x-input-label for="receiver_id" value="Receiver" />
                                <select id="receiver_id" name="receiver_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @selected(old('receiver_id') == $user->id)>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('receiver_id')" class="mt-1" />
                            </div>
                            <div class="flex-1">
                                <x-input-label for="text" value="Message" />
                                <x-text-input id="text" name="text" class="mt-1 block w-full"
                                    :value="old('text')" placeholder="Type your message..." />
                                <x-input-error :messages="$errors->get('text')" class="mt-1" />
                            </div>
                            <div>
                                <x-primary-button>Send</x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-left">
                        <thead class="border-b">
                            <tr>
                                <th class="py-3 px-4">ID</th>
                                <th class="py-3 px-4">Sender</th>
                                <th class="py-3 px-4">Receiver</th>
                                <th class="py-3 px-4">Text</th>
                                <th class="py-3 px-4">Read</th>
                                <th class="py-3 px-4">Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($messages as $message)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $message->id }}</td>
                                    <td class="py-3 px-4">{{ $message->sender?->name ?? '—' }}</td>
                                    <td class="py-3 px-4">{{ $message->receiver?->name ?? '—' }}</td>
                                    <td class="py-3 px-4 max-w-xs truncate">{{ $message->text }}</td>
                                    <td class="py-3 px-4">
                                        <span class="{{ $message->is_read ? 'text-green-600' : 'text-gray-400' }}">
                                            {{ $message->is_read ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">{{ $message->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $messages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            window.Echo.private(`messages.{{ auth()->id() }}`)
                .listen('MessageSent', (e) => {
                    console.log('New message received:', e);
                });
        </script>
    @endpush
</x-app-layout>