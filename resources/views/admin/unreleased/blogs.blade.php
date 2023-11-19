<x-layout title="Unreleased blogs">
    <div class="blogs">
        <div class="special_header">
                <span>
                    <i class="fal fa-blogs"></i>
                    مدونات غير منشورة
                </span>
            </div>
            @if($blogs->count() > 0)
                <table class="rwd-table" style="max-width: 1200px;margin-inline: auto;">
                    <tr>
                        <th>عنوان المدونة</th>
                        <th>تاريخ الإصدار</th>
                        <th>منشئ المدونة</th>
                        <th>{{ __('layout.settings') }}</th>
                    </tr>
                    @foreach ($blogs as $blog)
                        <tr>
                            <td>
                                <a href="/blogs/{{ $blog->id }}/preview">
                                    {{ $blog->title }}
                                </a>
                            </td>
                            <td>{{ $blog->created_at->diffForHumans() }}</td>
                            <td>{{ $blog->user->name }}</td>
                            <td class="operations">
                                <span class="delete">
                                    <form action="/blogs/{{ $blog->id }}/release" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit">
                                            <i class="fas fa-badge-check" style="color: green;"></i>
                                        </button>
                                    </form>
                                </span>
                                <span class="delete">
                                    <form action="/blogs/{{ $blog->id }}" method="POST">
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
                {{ $blogs->links() }}
            @else
                <p class="no_records">
                    {{ __('layout.no_records') }}
                </p>
            @endif
        </div>
</x-layout>
