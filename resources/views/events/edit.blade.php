<x-layout title="Edit Event">
    <div class="special_header">
        <span>
            <i class="fal fa-plus"></i>
            {{ __('layout.update_event') }}
        </span>
    </div>
    <div class="form-data">
        <form action="/events/{{ $event->id }}" method="POST" class="create_form" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="profile-image-container form-group img-thumbnail flex-1">
                <div class="profile-image-circle" style="{{ $event->thumbnail ? 'display:none;' : ''}}">
                  <input name="thumbnail" type="file" id="imageUpload" accept="image/*" />
                    <label for="imageUpload">
                        <i class="fal fa-cloud-upload"></i>
                        <span>
                            {{ __('layout.upload_thumbnail')}}
                        </span>
                    </label>
                </div>
                <div id="imagePreview">
                    @if ($event->thumbnail)
                        <img src="{{ asset("storage/". $event->thumbnail) }}" alt="Profile Image" />
                    @endif
                </div>
                @error('thumbnail')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group required">
                <label for="title">{{ __('layout.title') }}</label>
                <input type="text" name="title" id="title" value="{{ old("title", $event->title) }}">
                @error('title')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
            {{-- <div class="form-group multi-select select optional">
                <label for="owner_country">{{ __('layout.tags') }}</label>
                <input type="hidden" name="tags">
                <div class="box">
                    <p class="title">
                        <span>{{ __('layout.choose_tag') }}</span>
                        <i class="fal fa-chevron-down"></i>
                    </p>

                    <div class="options">
                        @foreach ($tags as $tag)
                            <div class="option" data-value="{{ $tag->id }}">
                                {{ $tag->name }}
                            </div>
                        @endforeach
                    </div>
                </div>
                @error('tags')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div> --}}

            <div class="form-group required flex-1">
                <label for="start">{{ __('layout.start_date') }}</label>
                <input type="text" name="start" id="start" value="{{ old('start', \Carbon\Carbon::parse($event->start)->format('Y-m-d')) }}" />
                @error('start')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group required flex-1">
                <label for="end">{{ __('layout.end_date') }}</label>
                <input type="text" name="end" id="end" value="{{ old('end', \Carbon\Carbon::parse($event->end)->format('Y-m-d')) }}" />
                @error('end')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group optional flex-1">
                <label for="details">{{ __('layout.description') }}</label>
                <textarea name="details" id="details" cols="30" rows="10">{{ old('details', $event->details) }}</textarea>
                @error('details')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group optional flex-1">
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

            <div class="form-group flex-1">
                <button type="submit">{{ __('layout.update_event') }}</button>
            </div>
        </form>
        @if($event->attachments->count() > 0)
                <div class="attachments">
                    <p class="title">{{ __('layout.attachments') }}</p>
                    <ul>
                        @foreach ($event->attachments as $attachment)
                            <li>
                                <form style="display: block;" action="/attachments/{{$attachment->id}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">
                                        <i class="fal fa-file"></i>
                                        {{ $attachment->name }}
                                        <i class="fal fa-times"></i>
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
    </div>

    @push('styles')
        <link href="{{ asset('calendar/vanilla-calendar.min.css') }}" rel="stylesheet">
        <script src="{{ asset('calendar/vanilla-calendar.min.js') }}" defer></script>
    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const calendarInput1 = document.querySelector('#start');
            const calendar = new VanillaCalendar(calendarInput1, {
                input: true,
                settings: {
                    lang: document.documentElement.lang,
                },
                actions: {
                    changeToInput(e, HTMLInputElement, dates, time, hours, minutes, keeping) {
                    if (dates[0]) {
                        HTMLInputElement.value = dates[0];
                        // if you want to hide the calendar after picking a date
                        calendar.HTMLElement.classList.add('vanilla-calendar_hidden');
                    } else {
                        HTMLInputElement.value = '';
                    }
                    },
                },
            });
            calendar.init();

            const calendarInput2 = document.querySelector('#end');
            const calendar2 = new VanillaCalendar(calendarInput2, {
                input: true,
                settings: {
                    lang: document.documentElement.lang,

                },
                actions: {
                    changeToInput(e, HTMLInputElement, dates, time, hours, minutes, keeping) {
                    if (dates[0]) {
                        HTMLInputElement.value = dates[0];
                        calendar.HTMLElement.classList.add('vanilla-calendar_hidden');
                    } else {
                        HTMLInputElement.value = '';
                    }
                    },
                },
            });
            calendar2.init();
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

                    // console.log(allFiles.files)

                    // popup appear to change the file name
                    const newFileName = prompt("أدخل اسم الملف الجديد");
                    if (newFileName) {

                        fileName.textContent = newFileName;

                        const parts = file.name.split('.');
                        const extension = parts[parts.length - 1];

                        const newFile = new File([file], `${newFileName}.${extension}`, {
                            type: file.type
                        });

                        // Replace the old file with the new one
                        const index = [...allFiles.files].indexOf(file);
                        allFiles.items.remove(index);
                        allFiles.items.add(newFile);

                        // Update the file input
                        fileInput.value = "";
                        fileInput.files = allFiles.files;
                    }
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


      <script>
        // options menu
        let allBoxes = document.querySelectorAll(".form-group.select");
        allBoxes.forEach((ele) => {
          let selectedOptions = [];
          let selectedOptionsSpan = ele.querySelector(".title");
          let optionsMenu = ele.querySelector(".options");
          let inputField = ele.querySelector("input");

          ele.querySelector(".title").addEventListener("click", function (e) {
            ele.querySelector("i")?.classList.toggle("fa-chevron-up");
            ele.querySelector("i")?.classList.toggle("fa-chevron-down");
            optionsMenu.classList.toggle("active");
          });

          ele.querySelectorAll(".options .option").forEach((option) => {
            option.addEventListener("click", function (e) {
              let optionValue = option.dataset.value; // Accessing the data-value attribute
              let optionText = option.textContent.trim();

              // Check if the option is already selected
              let index = selectedOptions.findIndex((selectedOption) => selectedOption.value === optionValue);

              if (index !== -1) {
                // If the option is already selected, remove it from the selectedOptions array
                selectedOptions.splice(index, 1);
                option.classList.remove("selected");
              } else {
                // If the option is not selected, add it to the selectedOptions array
                selectedOptions.push({
                  value: optionValue,
                  text: optionText,
                });
                option.classList.add("selected");
              }
              selectedOptionsSpan.innerHTML = selectedOptions.length > 0 ? selectedOptions.map((option) => `<span class="tag">${option.text}</span>`).join("") : "Choose Tags";
              inputField.value = selectedOptions.length > 0 ? selectedOptions.map((option) => option.value).join(",") : "";
            });
          });
        });

        // when clicking outside the options menu, close it
        document.addEventListener("click", function (e) {
          if (
            !e.target.classList.contains("option") &&
            !e.target.classList.contains("title") &&
            !e.target.classList.contains("fa-chevron-down") &&
            !e.target.classList.contains("fa-chevron-up")
          ) {
            allBoxes.forEach((ele) => {
              ele.querySelector(".options").classList.remove("active");
              ele.querySelector("i").classList.remove("fa-chevron-up");
              ele.querySelector("i").classList.add("fa-chevron-down");
            });
          }
        });
      </script>






</x-layout>
