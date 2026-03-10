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
                    <form id="message-form">
                        <div class="flex gap-4 items-end">
                            <div class="w-1/4">
                                <x-input-label for="receiver_id" value="Receiver" />
                                <select id="receiver_id" name="receiver_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p id="error-receiver_id" class="text-sm text-red-600 mt-1 hidden"></p>
                            </div>
                            <div class="flex-1">
                                <x-input-label for="text" value="Message" />
                                <x-text-input id="text" name="text" class="mt-1 block w-full"
                                    placeholder="Type your message..." />
                                <p id="error-text" class="text-sm text-red-600 mt-1 hidden"></p>
                            </div>
                            <div>
                                <x-primary-button type="submit">Send</x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <span>
                            Unread: <span id="unread-count" class="font-semibold">{{ $unreadCount }}</span>
                        </span>
                        <button id="read-all-btn"
                            class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 {{ $unreadCount === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $unreadCount === 0 ? 'disabled' : '' }}>
                            Read all
                        </button>
                    </div>
                    <table class="w-full text-left">
                        <thead class="border-b">
                            <tr>
                                <th class="py-3 px-4">ID</th>
                                <th class="py-3 px-4">Sender</th>
                                <th class="py-3 px-4">Receiver</th>
                                <th class="py-3 px-4">Read</th>
                                <th class="py-3 px-4">Text</th>
                                <th class="py-3 px-4">Created</th>
                            </tr>
                        </thead>
                        <tbody id="messages-tbody">
                            @foreach ($messages as $message)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $message->id }}</td>
                                    <td class="py-3 px-4">{{ $message->sender?->name ?? '—' }}</td>
                                    <td class="py-3 px-4">{{ $message->receiver?->name ?? '—' }}</td>
                                    <td class="py-3 px-4">
                                        @if($message->receiver_id === auth()->id())
                                            <button class="read-toggle cursor-pointer" data-id="{{ $message->id }}" data-read="{{ $message->is_read ? '1' : '0' }}">
                                                @if($message->is_read)
                                                    <svg class="w-5 h-5 text-green-500 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M1 12l5 5L17 6" /><path d="M7 12l5 5L23 6" />
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-gray-400 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M5 12l5 5L20 6" />
                                                    </svg>
                                                @endif
                                            </button>
                                        @else
                                            @if($message->is_read)
                                                <svg class="w-5 h-5 text-green-500 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12l5 5L17 6" /><path d="M7 12l5 5L23 6" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-gray-400 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M5 12l5 5L20 6" />
                                                </svg>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 max-w-xs truncate">{{ $message->text }}</td>
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
            function formatDate(isoString) {
                const date = new Date(isoString);
                const y = date.getFullYear();
                const m = String(date.getMonth() + 1).padStart(2, '0');
                const d = String(date.getDate()).padStart(2, '0');
                const h = String(date.getHours()).padStart(2, '0');
                const min = String(date.getMinutes()).padStart(2, '0');
                return `${y}-${m}-${d} ${h}:${min}`;
            }

            const readSvg = `<svg class="w-5 h-5 text-green-500 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12l5 5L17 6" /><path d="M7 12l5 5L23 6" /></svg>`;
            const unreadSvg = `<svg class="w-5 h-5 text-gray-400 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12l5 5L20 6" /></svg>`;

            const unreadCountEl = document.getElementById('unread-count');
            const readAllBtn = document.getElementById('read-all-btn');
            let unreadCount = {{ $unreadCount }};

            function updateUnreadUI() {
                unreadCountEl.textContent = unreadCount;
                readAllBtn.disabled = unreadCount === 0;
                readAllBtn.classList.toggle('opacity-50', unreadCount === 0);
                readAllBtn.classList.toggle('cursor-not-allowed', unreadCount === 0);
            }

            document.getElementById('messages-tbody').addEventListener('click', (e) => {
                const btn = e.target.closest('.read-toggle');
                if (!btn) return;

                const id = btn.dataset.id;
                const isRead = btn.dataset.read === '1';
                const url = isRead ? `/messages/${id}/unread` : `/messages/${id}/read`;

                axios.patch(url).then(() => {
                    btn.dataset.read = isRead ? '0' : '1';
                    btn.innerHTML = isRead ? unreadSvg : readSvg;
                    unreadCount += isRead ? 1 : -1;
                    updateUnreadUI();
                });
            });

            readAllBtn.addEventListener('click', () => {
                axios.patch('/messages/read-all').then(() => {
                    document.querySelectorAll('.read-toggle[data-read="0"]').forEach(btn => {
                        btn.dataset.read = '1';
                        btn.innerHTML = readSvg;
                    });
                    unreadCount = 0;
                    updateUnreadUI();
                });
            });

            const authName = '{{ auth()->user()->name }}';

            document.getElementById('message-form').addEventListener('submit', (e) => {
                e.preventDefault();
                const form = e.target;
                const receiverSelect = form.querySelector('#receiver_id');
                const textInput = form.querySelector('#text');

                document.getElementById('error-receiver_id').classList.add('hidden');
                document.getElementById('error-text').classList.add('hidden');

                axios.post('/messages', {
                    receiver_id: receiverSelect.value,
                    text: textInput.value,
                }).then((response) => {
                    const msg = response.data;
                    const tbody = document.getElementById('messages-tbody');
                    const tr = document.createElement('tr');
                    tr.className = 'border-b hover:bg-gray-50';
                    tr.innerHTML = `
                        <td class="py-3 px-4">${msg.id}</td>
                        <td class="py-3 px-4">${authName}</td>
                        <td class="py-3 px-4">${msg.receiver_name ?? '—'}</td>
                        <td class="py-3 px-4">
                            ${unreadSvg}
                        </td>
                        <td class="py-3 px-4 max-w-xs truncate">${msg.text}</td>
                        <td class="py-3 px-4">${formatDate(msg.created_at)}</td>
                    `;
                    tbody.prepend(tr);
                    textInput.value = '';
                }).catch((error) => {
                    if (error.response?.status === 422) {
                        const errors = error.response.data.errors;
                        if (errors.receiver_id) {
                            const el = document.getElementById('error-receiver_id');
                            el.textContent = errors.receiver_id[0];
                            el.classList.remove('hidden');
                        }
                        if (errors.text) {
                            const el = document.getElementById('error-text');
                            el.textContent = errors.text[0];
                            el.classList.remove('hidden');
                        }
                    }
                });
            });

            window.Echo.private(`messages.{{ auth()->id() }}`)
                .listen('MessageSent', (e) => {
                    const tbody = document.getElementById('messages-tbody');
                    const tr = document.createElement('tr');
                    tr.className = 'border-b hover:bg-gray-50 bg-yellow-50';
                    tr.innerHTML = `
                        <td class="py-3 px-4">${e.id}</td>
                        <td class="py-3 px-4">${e.sender_name ?? '—'}</td>
                        <td class="py-3 px-4">${e.receiver_name ?? '—'}</td>
                        <td class="py-3 px-4">
                            <button class="read-toggle cursor-pointer" data-id="${e.id}" data-read="1">
                                ${readSvg}
                            </button>
                        </td>
                        <td class="py-3 px-4 max-w-xs truncate">${e.text}</td>
                        <td class="py-3 px-4">${formatDate(e.created_at)}</td>
                    `;
                    tbody.prepend(tr);

                    axios.patch(`/messages/${e.id}/read`);
                    // already read via axios, count stays the same
                });
        </script>
    @endpush
</x-app-layout>