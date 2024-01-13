<x-layout title="Dashbaord">


    <div class="statics">
        <div class="special_header">
            <span>
                <i class="fal fa-chart-line"></i>
                الإحصائيات
            </span>
        </div>
        <div class="statics_container">
            <a href="/admin/users">
                <div class="static">
                    <div class="static_header">
                        <span>
                            <i class="fal fa-users"></i>
                            عدد المستخدمين
                        </span>
                    </div>
                    <div class="static_body">
                        <span>
                            {{ $users_count -1 }}
                        </span>
                    </div>
                </div>
            </a>
            <a href="/admin/tags">
                <div class="static">
                    <div class="static_header">
                        <span>
                            <i class="fal fa-tags"></i>
                            عدد العلامات
                        </span>
                    </div>
                    <div class="static_body">
                        <span>
                            {{ $tags_count }}
                        </span>
                    </div>
                </div>
            </a>
            <a href="/chats">
                <div class="static">
                    <div class="static_header">
                        <span>
                            <i class="fal fa-comments"></i>
                            عدد المحادثات
                        </span>
                    </div>
                    <div class="static_body">
                        <span>
                            {{ $released_chats_count }}
                        </span>
                    </div>
                </div>
            </a>
            <a href="/blog">
                <div class="static">
                    <div class="static_header">
                        <span>
                            <i class="fas fa-blog"></i>
                            عدد المدونات
                        </span>
                    </div>
                    <div class="static_body">
                        <span>
                            {{ $released_blogs_count }}
                        </span>
                    </div>
                </div>
            </a>
            <a href="/library">
                <div class="static">
                    <div class="static_header">
                        <span>
                            <i class="fas fa-photo-video"></i>
                            عدد المكتبات
                        </span>
                    </div>
                    <div class="static_body">
                        <span>
                            {{ $released_libraries_count }}
                        </span>
                    </div>
                </div>
            </a>
            <a href="/events">
                <div class="static">
                    <div class="static_header">
                        <span>
                            <i class="fal fa-calendar-alt"></i>
                            عدد الفعاليات
                        </span>
                    </div>
                    <div class="static_body">
                        <span>
                            {{ $released_events_count }}
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="statics">
        <div class="special_header">
            <span>
                <i class="fal fa-box-check"></i>
                منشورات تحتاج للموافقة
            </span>
        </div>
        <div class="statics_container">
            <a href="/chats/unreleased">
                <div class="static">
                    <div class="static_header">
                        <span>
                            <i class="fal fa-comments"></i>
                             المحادثات
                        </span>
                    </div>
                    <div class="static_body">
                        <span>
                            {{ $unreleased_chats_count }}
                        </span>
                    </div>
                </div>
            </a>
            <a href="/blogs/unreleased">
                <div class="static">
                    <div class="static_header">
                        <span>
                            <i class="fas fa-blog"></i>
                         المدونات
                        </span>
                    </div>
                    <div class="static_body">
                        <span>
                            {{ $unreleased_blogs_count }}
                        </span>
                    </div>
                </div>
            </a>
            <a href="/libraries/unreleased">
                <div class="static">
                    <div class="static_header">
                        <span>
                            <i class="fas fa-photo-video"></i>
                         المكتبات
                        </span>
                    </div>
                    <div class="static_body">
                        <span>
                            {{ $unreleased_libraries_count }}
                        </span>
                    </div>
                </div>
            </a>
            <a href="/events/unreleased">
                <div class="static">
                    <div class="static_header">
                        <span>
                            <i class="fal fa-calendar-alt"></i>
                         الفعاليات
                        </span>
                    </div>
                    <div class="static_body">
                        <span>
                            {{ $unreleased_events_count }}
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>

</x-layout>
