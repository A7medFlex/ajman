<x-layout title="Update Tag">
    <div class="special_header">
        <span>
            <i class="fal fa-plus"></i>
            {{ __('layout.update_tag') }}
        </span>
    </div>

    <div class="form-data">
        <form action="/admin/tags/{{ $tag->id }}" method="POST">
            @csrf
            @method("PATCH")

            <div class="form-group required">
                <label for="name">{{ __('layout.name') }}</label>
                <input type="text" name="name" id="name" value="{{ old("name", $tag->name) }}">
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group select required">
                <label for="owner_country">{{ __('layout.tag_for') }}</label>
                <input type="hidden" name="model">
                <div class="box">
                    <p class="title">
                        <span>{{ __('layout.choose_option') }}</span>
                        <i class="fal fa-chevron-down"></i>
                    </p>

                    <div class="options">
                        <div class="option {{ $tag->model === 'App\Models\Blog' ? 'selected' : '' }}" data-value="blog">
                            {{ __('layout.blog') }}
                        </div>
                        <div class="option {{ $tag->model === 'App\Models\User' ? 'selected' : '' }}" data-value="library">
                            {{ __('layout.library') }}
                        </div>
                    </div>
                </div>
                @error('model')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>


            <div class="form-group flex-1">
                <button type="submit">{{ __('layout.update_tag') }}</button>
            </div>
        </form>
    </div>


    <script>
        // options menu
        let allBoxes = document.querySelectorAll(".form-group.select")
        allBoxes.forEach(ele=>{
            ele.querySelector(".title").addEventListener("click", function(e){
                e.target.parentElement.querySelector("i").classList.toggle("fa-chevron-up")
                e.target.parentElement.querySelector("i").classList.toggle("fa-chevron-down")
                e.target.parentElement.querySelector(".options").classList.toggle("active")
            })

            ele.querySelectorAll(".options .option").forEach(option=>{
                if(option.classList.contains("selected")){
                    option.parentElement.parentElement.querySelector(".title span").textContent = option.textContent
                    ele.querySelector("input").value = option.getAttribute("data-value")
                }
                option.addEventListener("click", function(e){
                    ele.querySelectorAll(".options .option").forEach(option=>{
                        option.classList.remove("selected")
                    })
                    e.target.classList.add("selected")
                    e.target.parentElement.parentElement.querySelector(".title span").textContent = e.target.textContent
                    e.target.parentElement.parentElement.querySelector("i").classList.remove("fa-chevron-up")
                    e.target.parentElement.parentElement.querySelector("i").classList.add("fa-chevron-down")
                    e.target.parentElement.classList.toggle("active")

                    ele.querySelector("input").value = e.target.getAttribute("data-value")

                    let parent = e.target.parentElement.parentElement.parentElement
                    if(parent.classList.contains("error")) parent.classList.remove("error")
                })
            })

        })

        // when click outside the options menu close it
        document.addEventListener("click", function(e){
            if(!e.target.classList.contains("option") && !e.target.classList.contains("title") && !e.target.classList.contains("fa-chevron-down") && !e.target.classList.contains("fa-chevron-up")){
                allBoxes.forEach(ele=>{
                    ele.querySelector(".options").classList.remove("active")
                    ele.querySelector("i").classList.remove("fa-chevron-up")
                    ele.querySelector("i").classList.add("fa-chevron-down")
                })
            }
        })

    </script>
</x-layout>
