<x-layout title="Unreleased events">
    <div class="events">
        <div class="special_header">
                <span>
                    <i class="fal fa-events"></i>
                    فعاليات غير منشورة
                </span>
            </div>
            @if($events->count() > 0)
                <table class="rwd-table" style="max-width: 1200px;margin-inline: auto;">
                    <tr>
                        <th>عنوان الفعالية</th>
                        <th>تاريخ الإصدار</th>
                        <th>منشئ الفعالية</th>
                        <th>{{ __('layout.settings') }}</th>
                    </tr>
                    @foreach ($events as $event)
                        <tr>
                            <td>
                                <a href="/events/{{ $event->id }}/preview">
                                    {{ $event->title }}
                                </a>
                            </td>
                            <td>{{ $event->created_at->diffForHumans() }}</td>
                            <td>{{ $event->user->name }}</td>
                            <td class="operations">
                                <span class="delete">
                                    <form action="/events/{{ $event->id }}/release" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit">
                                            <i class="fas fa-badge-check" style="color: green;"></i>
                                        </button>
                                    </form>
                                </span>
                                <span class="delete">
                                    <form action="/events/{{ $event->id }}" method="POST">
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
                {{ $events->links() }}
            @else
                <p class="no_records">
                    {{ __('layout.no_records') }}
                </p>
            @endif
        </div>
</x-layout>
