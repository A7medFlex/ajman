<x-layout title="Unreleased Chats">
    <div class="chats">
        <div class="special_header">
                <span>
                    <i class="fal fa-chats"></i>
                    محادثات غير منشورة
                </span>
            </div>
            @if($chats->count() > 0)
                <table class="rwd-table" style="max-width: 1200px;margin-inline: auto;">
                    <tr>
                        <th>عنوان المحادثة</th>
                        <th>تاريخ الإصدار</th>
                        <th>منشئ المحادثة</th>
                        <th>{{ __('layout.settings') }}</th>
                    </tr>
                    @foreach ($chats as $chat)
                        <tr>
                            <td>
                                <a href="/chats/{{ $chat->id }}/preview">
                                    {{ $chat->title }}
                                </a>
                            </td>
                            <td>{{ $chat->created_at->diffForHumans() }}</td>
                            <td>{{ $chat->user->name }}</td>
                            <td class="operations">
                                <span class="delete">
                                    <form action="/chats/{{ $chat->id }}/release" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit">
                                            <i class="fas fa-badge-check" style="color: green;"></i>
                                        </button>
                                    </form>
                                </span>
                                <span class="delete">
                                    <form action="/chats/{{ $chat->id }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </table>
                {{ $chats->links() }}
            @else
                <p class="no_records">
                    {{ __('layout.no_records') }}
                </p>
            @endif
        </div>
</x-layout>
