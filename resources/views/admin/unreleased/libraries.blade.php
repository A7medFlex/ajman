<x-layout title="Unreleased libraries">
    <div class="libraries">
        <div class="special_header">
                <span>
                    <i class="fal fa-libraries"></i>
                    مكتبات غير منشورة
                </span>
            </div>
            @if($libraries->count() > 0)
                <table class="rwd-table" style="max-width: 1200px;margin-inline: auto;">
                    <tr>
                        <th>عنوان المكتبة</th>
                        <th>تاريخ الإصدار</th>
                        <th>منشئ المكتبة</th>
                        <th>{{ __('layout.settings') }}</th>
                    </tr>
                    @foreach ($libraries as $lib)
                        <tr>
                            <td>
                                <a href="/libraries/{{ $lib->id }}/preview">
                                    {{ $lib->title }}
                                </a>
                            </td>
                            <td>{{ $lib->created_at->diffForHumans() }}</td>
                            <td>{{ $lib->user->name }}</td>
                            <td class="operations">
                                <span class="delete">
                                    <form action="/libraries/{{ $lib->id }}/release" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit">
                                            <i class="fas fa-badge-check" style="color: green;"></i>
                                        </button>
                                    </form>
                                </span>
                                <span class="delete">
                                    <form action="/libraries/{{ $lib->id }}/unrelease" method="POST">
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
                {{ $libraries->links() }}
            @else
                <p class="no_records">
                    {{ __('layout.no_records') }}
                </p>
            @endif
        </div>
</x-layout>
