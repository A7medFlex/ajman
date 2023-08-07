<x-layout title="Update Library">
    <div class="special_header">
        <span>
            <i class="fal fa-plus"></i>
            {{ __('layout.update_library') }}
        </span>
    </div>
    <div class="form-data">
        <form action="/library/{{ $library->id }}" method="POST" class="create_form" enctype="multipart/form-data">
            @csrf
            @method("PATCH")
            <div class="profile-image-container form-group img-thumbnail flex-1">
                <div class="profile-image-circle" style="{{ $library->thumbnail ? 'display:none;' : ''}}">
                  <input name="thumbnail" type="file" id="imageUpload" accept="image/*" />
                    <label for="imageUpload">
                        <i class="fal fa-cloud-upload"></i>
                        <span>{{ __('layout.upload_thumbnail') }}</span>
                    </label>
                </div>
                <div id="imagePreview">
                    @if ($library->thumbnail)
                        <img src="{{ asset("storage/". $library->thumbnail) }}" alt="Profile Image" />
                    @endif
                </div>
                @error('thumbnail')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group required">
                <label for="title_ar">{{ __('layout.title_ar') }}</label>
                <input type="text" name="title_ar" id="title_ar" value="{{ old("title_ar", $library->title_ar) }}">
                @error('title_ar')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group optional">
                <label for="title_en">{{ __('layout.title_en') }}</label>
                <input type="text" name="title_en" id="title_en" value="{{ old("title_en", $library->title_en) }}">
                @error('title_en')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group multi-select select optional">
                <label for="tags">{{ __('layout.tags') }}</label>
                @if ($library->tags->count() > 0)
                    <input type="hidden" name="tags" value="{{ collect($library->tags)->map(fn($tag) => $tag->id)->implode(',') }}">
                @else
                    <input type="hidden" name="tags">
                @endif
                <div class="box">
                    <p class="title">
                        @if($library->tags->count() > 0)
                            @foreach ($library->tags as $tag)
                                <span class="tag">{{ $tag->name }}</span>
                            @endforeach
                        @else
                            <span>{{ __('layout.choose_tag') }}</span>
                            <i class="fal fa-chevron-down"></i>
                        @endif
                    </p>

                    <div class="options">
                        @foreach ($tags as $tag)
                            <div class="option {{ $library->tags->contains($tag) ? 'selected' : '' }}" data-value="{{ $tag->id }}">
                                {{ $tag->name }}
                            </div>
                        @endforeach
                    </div>
                </div>
                @error('tags')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group required flex-1">
                <label for="content_ar">{{ __('layout.body_ar') }}</label>
                <textarea name="body_ar" id="content_ar" cols="30" rows="10">{{ old('body_ar', $library->body_ar) }}</textarea>
            </div>
            <div class="form-group optional flex-1">
                <label for="content_en">{{ __('layout.body_en') }}</label>
                <textarea name="body_en" id="content_en" cols="30" rows="10">{{ old('body_en', $library->body_en) }}</textarea>
            </div>


            <div class="form-group optional flex-1">
                <label for="files">{{ __('layout.upload_attachments') }}</label>
                <div class="uploadAttachments">
                    <input type="file" name="attachments[]" id="files" multiple style="display: none;">
                    <div class="files">
                        <i class="fal fa-cloud-upload"></i>
                    </div>
                </div>
            </div>

            <div class="form-group flex-1">
                <button type="submit">{{ __('layout.update_library') }}</button>
            </div>
        </form>
        @if($library->attachments->count() > 0)
                <div class="attachments">
                    <p class="title">{{ __('layout.attachments') }} :</p>
                    <ul>
                        @foreach ($library->attachments as $attachment)
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
                ele.querySelectorAll(".options .option").forEach(o=>{
                    o.classList.remove("selected")
                })
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
