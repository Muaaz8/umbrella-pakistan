@extends('layouts.dashboard_admin')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
    <style>
        label {
            font-weight: 600;
            color: #333;
            margin-top: 10px;
        }
    </style>
    <style>
        #editorjs {
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            min-height: 300px;
            background-color: #fff;
            font-family: Arial, sans-serif;
        }

        .ce-block__content {
            max-width: 100%;
        }

        .ce-toolbar__plus {
            background-color: #007bff;
            color: #fff;
            border-radius: 50%;
        }

        .ce-toolbar__settings-btn {
            color: #007bff;
        }

        .codex-editor {
            padding-bottom: 30px;
        }
    </style>

@endsection

@section('page_title')
    <title> Admin Dashboard</title>
@endsection

@section('top_import_file')
    <!-- Editor.js Core -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest" defer></script>

    <!-- Tools -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest" defer></script>

    <script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/link@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/simple-image@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/table@latest"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.6.1/tinymce.min.js" referrerpolicy="origin"></script>
@endsection


@section('bottom_import_file')
{{-- <script defer>
    document.addEventListener("DOMContentLoaded", function () {
        console.log(typeof SimpleImage); // should log "function"
        const editor = new EditorJS({
            /**
             * Id of Element that should contain Editor instance
             */
            holder: 'editorjs',
            autofocus: true,
            placeholder: 'Let`s write an awesome blog!',
            inlineToolbar: ['link', 'marker', 'bold', 'italic'],
            tools: {
                header: {
                    class: Header,
                    inlineToolbar: ['marker', 'link'],
                    config: {
                        placeholder: 'Header'
                    },
                    shortcut: 'CMD+SHIFT+H'
                },
                list: {
                    class: EditorjsList,
                    inlineToolbar: true,
                },
                quote: {
                    class: Quote,
                    inlineToolbar: true,
                    config: {
                        quotePlaceholder: 'Enter a quote',
                        captionPlaceholder: 'Quote\'s author',
                    },
                },
                linktool: {
                    class: LinkTool,
                    config: {
                        endpoint: '', // Your backend endpoint for url data
                        checkProtocol: true,
                    },
                },
                image: {
                    class: SimpleImage,
                    inlineToolbar: true,
                    config: {
                        placeholder: 'Enter image URL',
                    },
                    shortcut: 'CMD+SHIFT+Q',
                },
                table: {
                    class: Table,
                    inlineToolbar: true,
                    config: {
                        rows: 2,
                        cols: 3,
                        maxRows: 5,
                        maxCols: 5,
                    },
                    shortcut: 'CMD+SHIFT+T',
                    inlineToolbar: true,
            }
            },
            data: {
                blocks: [
                    {
                        type: "paragraph",
                        data: {
                            text: ""
                        }
                    }
                ]
            },

            onReady: () => {
                console.log('Editor.js is ready to work!')
            },
            onChange: async () => {
                const savedData = await editor.save();
                document.getElementById('editor-content').value = JSON.stringify(savedData);
            }
        });
    });


    // editor.save().then((outputData) => {
    //     console.log('Article data: ', outputData)
    // }).catch((error) => {
    //     console.log('Saving failed: ', error)
    // });
</script> --}}
<script>
    function fill_slug() {
        var title = document.querySelector('input[name="title"]').value;
        var slug = title.toLowerCase().replace(/[^a-z0-9]+/g, '-');
        document.querySelector('input[name="slug"]').value = slug;
    }

    document.addEventListener('DOMContentLoaded', function () {
        tinymce.init({
            selector: '#myEditor',  // match your textarea ID
            // menubar: false,
            plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview | insertfile image media template link anchor codesample | ltr rtl',
            relative_urls: false,
            branding: false,
            convert_urls: true,
            document_base_url: '{{ url('/') }}',
            file_picker_callback: function (callback, value, meta) {
                if (meta.filetype === 'image') {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.onchange = function () {
                        var file = this.files[0];
                        var reader = new FileReader();
                        reader.onload = function () {
                            callback(reader.result, { alt: file.name });
                        };
                        reader.readAsDataURL(file);
                    };
                    input.click();
                }
            },
        });
    });

    $("#add_more").click(function (e) {
        e.preventDefault();
        var meta_tags = `
            <div class="row">
                <div class="col-md-5">
                    <label for="title"> Meta Name </label>
                    <input type="text" class="form-control" name="meta_name[]" placeholder="Meta Name">
                </div>
                <div class="col-md-5">
                    <label for="title"> Meta Content </label>
                    <input type="text" class="form-control" name="meta_content[]" placeholder="Meta Content">
                </div>
                <div class="col-md-2 d-flex justify-content-end m-auto mb-1">
                    <button type="button" class="btn btn-danger remove_meta"> Remove </button>
                </div>
            </div>`;
        $("#meta_tags").append(meta_tags);
    });

    $(document).on('click', '.remove_meta', function (e) {
        e.preventDefault();
        $(this).closest('.row').remove();
    });


</script>
@endsection

@section('content')

<div class="dashboard-content">
    <div class="container-fluid">
        <div class="row m-auto">
          <div class="col-md-12">
            <div class="row m-auto">
                <div>
                    <h4 class="dashboard-title">Create Blog</h4>
                    <p class="dashboard-subtitle">Create a new blog post</p>
                </div>
                <div>
                    <form action="{{ route('admin_blog.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="Blog name"> Blog Name </label>
                            <input type="text" class="form-control" name="title" placeholder="Blog Name" onkeydown="fill_slug()" required>

                            <label for="slug"> Slug </label>
                            <input type="text" class="form-control" name="slug" placeholder="Slug" required readonly>

                            <label for="title"> Title </label>
                            <input type="text" class="form-control" name="meta_title" placeholder="Title" required>

                            <div id="meta_tags">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label for="title"> Meta Name </label>
                                        <input type="text" class="form-control" name="meta_name[]" placeholder="Meta Name">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="title"> Meta Content </label>
                                        <input type="text" class="form-control" name="meta_content[]" placeholder="Meta Content">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn process-pay mt-3" id="add_more"> Add More </button>
                            </div>



                            <label for="slug"> Featured Image </label>
                            <input type="file" class="form-control" name="featured_image" required>

                            <label for="title"> Content </label>
                            <textarea name="content" id="myEditor" class="form-control" placeholder="Blog Content" required>
                                {{ old('content') }}
                            </textarea>

                            {{-- <label for="title"> Title </label>
                            <input type="text" class="form-control" name="category" placeholder="Category" required>

                            <label for="title"> Title </label>
                            <input type="text" class="form-control" name="tags" placeholder="Tags (comma separated)" required> --}}

                        </div>
                        <div class="d-flex form-group gap-2 justify-content-end mb-3">
                            <button type="submit" class="btn btn-primary">Create Blog</button>
                            <a href="{{ route('admin_blog.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
          </div>
        </div>
      </div>
</div>

@endsection
