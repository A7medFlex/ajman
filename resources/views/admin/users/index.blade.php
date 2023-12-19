<x-layout title="Users">
    <div class="users">
        <div class="special_header">
                <span>
                    <i class="fal fa-users"></i>
                    {{ __('layout.users') }}
                </span>
                <a href="/admin/users/create" class="create_user">{{ __('layout.create_user') }}</a>
            </div>
            @if($users->count() > 0)
                <table class="rwd-table">
                    <tr>
                        <th>{{ __('layout.image') }}</th>
                        <th>{{ __('layout.name') }}</th>
                        <th>{{ __('layout.job_name') }}</th>
                        <th>{{ __('layout.email') }}</th>
                        <th>رقم الهوية</th>
                        <th>{{ __('layout.settings') }}</th>
                    </tr>
                    @foreach ($users as $user)
                        <tr>
                            <td class="image">
                                <a href="/users/{{ $user->id }}">
                                    @if($user->profile_image)
                                        <img src="{{ asset('storage/'. $user->profile_image) }}" alt="">
                                    @else
                                        <i class="fal fa-user"></i>
                                    @endif
                                </a>
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->job_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->idn }}</td>
                            <td class="operations">
                                <span class="edit">
                                    <a href="/admin/users/{{ $user->id }}/edit">
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                </span>
                                <span class="delete">
                                    <form action="/admin/users/{{ $user->id }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">
                                            <i class="fas fa-user-times"></i>
                                        </button>
                                    </form>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </table>
                {{ $users->links() }}
            @else
                <p class="no_records">
                    {{ __('layout.no_records') }}
                </p>
            @endif
        </div>
</x-layout>
