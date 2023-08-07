<x-layout title="Tags">
    <div class="users">
        <div class="special_header">
                <span>
                    <i class="fas fa-tags"></i>
                    {{ __('layout.tags') }}
                </span>
                <a href="/admin/tags/create" class="create_user">{{ __('layout.create_tag') }}</a>
            </div>
            @if($tags->count() > 0)
                <table class="rwd-table">
                    <tr>
                        <th>{{ __('layout.name') }}</th>
                        <th>{{ __('layout.tag_for') }}</th>
                        <th>{{ __('layout.settings') }}</th>
                    </tr>
                    @foreach ($tags as $tag)
                        <tr>
                            <td>{{ $tag->name }}</td>
                            @if (collect(explode('\\', $tag->model))->last() === 'Blog')
                                <td>{{ __('layout.blog') }}</td>
                            @else
                                <td>{{ __('layout.library') }}</td>
                            @endif
                            <td class="operations">
                                <span class="edit">
                                    <a href="/admin/tags/{{ $tag->id }}/edit">
                                        <i class="far fa-edit"></i>
                                    </a>
                                </span>
                                <span class="delete">
                                    <form action="/admin/tags/{{ $tag->id }}" method="POST">
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
                {{ $tags->links() }}
            @else
                <p class="no_records">
                    {{ __('layout.no_records') }}
                </p>
            @endif
        </div>
</x-layout>
