<x-layout title="Chat">
    <div class="chat-details relative">
        <div class="chat-details">
            <div class="title_status">
                <h3>{{ $chat->title }}</h3>
                @if($chat->user_id === auth()->id())
                    <form action="/chats/{{$chat->id}}/change" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="status {{ $chat->is_open ? 'active' : 'closed' }}">{{ $chat->is_open ? __('layout.active') : __('layout.archived') }}</button>
                    </form>
                @else
                    <button type="button" class="status {{ $chat->is_open ? 'active' : 'closed' }}">{{ $chat->is_open ? __('layout.active') : __('layout.archived') }}</button>
                @endif
            </div>
            <p class="author">{{ __('layout.created_by') }}: <a href="/users/{{ $chat->user_id }}">{{ $chat->user->name }}</a></p>

            <p class="time">
                <i class="fal fa-clock"></i>
                {{ $chat->created_at->diffForHumans() }}
            </p>

            <p class="body">{{ $chat->description }}</p>
        </div>
        @if ($chat->is_open)
            <h3>{{ __('layout.add_message') }}</h3>
            <div class="add_comment">
                <form action="/chats/{{ $chat->id }}/messages" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group required flex-1">
                        <label for="message">{{ __('layout.message') }}</label>
                        <textarea name="message" id="message" cols="30" rows="10">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="show_attachment">
                        <i class="far fa-paperclip" onclick="this.parentElement.nextElementSibling.classList.toggle('active');"></i>
                    </div>
                    <div class="form-group optional flex-1 attachments_group">
                        <label for="files">{{ __('layout.upload_attachments') }}</label>
                        <div class="uploadAttachments">
                            <input type="file" name="attachments[]" id="files" multiple style="display: none;">
                            <div class="files">
                                <i class="fal fa-cloud-upload"></i>
                            </div>
                        </div>
                        @error('attachments')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group flex-1" style="margin-bottom: 0;">
                        <button type="submit">{{ __('layout.send') }}</button>
                    </div>
                </form>
            </div>
        @endif
        <h3>{{ __('layout.messages') }}</h3>
        <div class="messages">
            @if($messages->count() > 0)
                @foreach ($messages as $message)
                    <div class="message" id="{{ $message->id }}">
                        <a href="/users/{{ $message->user_id }}">
                            <div class="author comment">
                                @if($message->user->profile_image)
                                    <img src="{{ asset('storage/'. $message->user->profile_image) }}" alt="Profile Image">
                                @else
                                    <i class="fal fa-user"></i>
                                @endif
                                <div class="author-info">
                                    <h3>{{ $message->user->name }}</h3>
                                    <span class="time">
                                        <i class="fal fa-clock"></i>
                                        {{ $message->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </a>
                        <p class="body">{{ $message->message }}</p>
                        @if ($message->attachments->count() > 0)
                            <div class="attachments">
                                @foreach ($message->attachments as $attachment)
                                    <a href="{{ asset('storage/' . $attachment->path)}}" download="{{ $attachment->name }}">
                                        <i class="fal fa-file"></i>
                                        {{ $attachment->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
                {{ $messages->links() }}
            @else
                <p class="no_records">
                    {{ __('layout.no_records') }}
                </p>
            @endif
        </div>
    </div>

    <script defer>
       document.addEventListener('DOMContentLoaded', function() {

            const commentsChannel = window.Echo.channel('public');
            commentsChannel.listen('CommentCreated', (e) => {
                const chatId = e.comment.chat_id;
                const url = window.location.href;

                // Split the URL using the forward slash '/'
                const parts = url.split('/');

                // The last part of the URL will be the id
                const id = parts[parts.length - 1];
                // Check if the comment is for this chat

                if(chatId != id) return

                const message = e.comment.message;
                const attachments = e.comment.attachments;
                const messageDiv = document.createElement("div");
                messageDiv.classList.add("message", "new");
                messageDiv.id = e.comment.id;

                const authorDiv = document.createElement("div");
                authorDiv.classList.add("author", "comment");
                let path = 'http://localhost:8000/';
                if(e.comment.user.profile_image != null){
                    const authorImage = document.createElement("img");
                    authorImage.src = path + `storage/${e.comment.user.profile_image}`;
                    authorImage.alt = "Profile Image";
                    authorDiv.appendChild(authorImage);
                }else{
                    const authorIcon = document.createElement("i");
                    authorIcon.classList.add("fal", "fa-user");
                    authorDiv.appendChild(authorIcon);
                }

                const authorInfoDiv = document.createElement("div");
                authorInfoDiv.classList.add("author-info");

                const authorName = document.createElement("h3");
                authorName.innerText = e.comment.user.name;
                authorInfoDiv.appendChild(authorName);

                const timeSpan = document.createElement("span");
                timeSpan.classList.add("time");
                const timeIcon = document.createElement("i");
                timeIcon.classList.add("fal", "fa-clock");
                timeSpan.appendChild(timeIcon);
                timeSpan.innerHTML += " " + e.time;
                authorInfoDiv.appendChild(timeSpan);

                authorDiv.appendChild(authorInfoDiv);
                messageDiv.appendChild(authorDiv);

                const bodyP = document.createElement("p");
                bodyP.classList.add("body");
                bodyP.innerText = message;
                messageDiv.appendChild(bodyP);

                if (attachments.length > 0) {
                    const attachmentsDiv = document.createElement("div");
                    attachmentsDiv.classList.add("attachments");
                    for (const attachment of attachments) {
                        const attachmentLink = document.createElement("a");
                        attachmentLink.href = attachment.path;
                        attachmentLink.download = attachment.name;
                        const attachmentIcon = document.createElement("i");
                        attachmentIcon.classList.add("fal", "fa-file");
                        attachmentLink.appendChild(attachmentIcon);
                        attachmentLink.innerHTML += " " + attachment.name;
                        attachmentsDiv.appendChild(attachmentLink);
                    }
                    messageDiv.appendChild(attachmentsDiv);
                }

                const messagesDiv = document.querySelector(".messages");
                if(document.querySelector(".no_records")){
                    document.querySelector(".no_records").remove();
                    messagesDiv.appendChild(messageDiv);
                }else{
                    messagesDiv.insertBefore(messageDiv, messagesDiv.firstChild);
                }


                document.title = "New Message";

                // Remove the new class after 5 seconds
                setTimeout(() => {
                    messageDiv.classList.remove("new");
                }, 10000);

                setTimeout(() => {
                    document.title = "Chat";
                }, 4000);

            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const uploadAttachments = document.querySelector(".uploadAttachments");
            const fileInput = uploadAttachments.querySelector("input[type='file']");
            const filesDiv = uploadAttachments.querySelector(".files");
            const fileItemsContainer = document.createElement("div"); // Container for file items
            fileItemsContainer.classList.add("file-items-container");
            filesDiv.appendChild(fileItemsContainer);
            let allFiles = new DataTransfer();

            uploadAttachments.addEventListener("click", function() {
                fileInput.click();
            });

            fileInput.addEventListener("change", function() {
                appendNewFiles();
                fileInput.value = "";
                fileInput.files = allFiles.files;
            });

            function appendNewFiles() {
                const files = fileInput.files;
                for (const file of files) {
                    const fileItem = createFileItem(file);
                    fileItemsContainer.appendChild(fileItem); // Append to the container
                    allFiles.items.add(file); // Add to the DataTransfer object
                }

                // Show the upload icon if files are present
                if (files.length > 0) {
                    uploadAttachments.classList.add("active");
                } else {
                    uploadAttachments.classList.remove("active");
                }
            }

            function createFileItem(file) {
                const fileItem = document.createElement("div");
                fileItem.classList.add("file-item");

                const fileIcon = document.createElement("i");
                fileIcon.classList.add("fal", "fa-file");
                fileItem.appendChild(fileIcon);

                const fileName = document.createElement("div");
                fileName.classList.add("file-name");
                fileName.textContent = file.name;
                fileItem.appendChild(fileName);
                fileItem.addEventListener("click", function(e) {
                    e.stopPropagation();
                });

                const removeBtn = document.createElement("span");
                removeBtn.classList.add("remove-btn");
                removeBtn.innerHTML = "x";
                removeBtn.addEventListener("click", function(event) {
                    event.stopPropagation();
                    removeFile(file);
                    fileItem.remove();
                    // updateFilesDisplay(); // Update the display after removing the file
                });
                fileItem.appendChild(removeBtn);

                return fileItem;
            }

            function removeFile(fileToRemove) {
                const dt = new DataTransfer();
                const files = allFiles.files;
                for (const file of files) {
                    if (file !== fileToRemove) {
                        dt.items.add(file);
                    }
                }
                allFiles = dt;
                fileInput.value = "";
                fileInput.files = allFiles.files;
            }
        });
    </script>
</x-layout>
