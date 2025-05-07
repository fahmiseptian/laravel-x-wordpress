<!doctype html>
<html lang="en">

@include('partials.css')

<body>

    <main class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        @include('partials.sidebar')

        <div class="body-wrapper">
            @include('partials.navbar')

            <div class="container-fluid">
                <h1 class="my-4">Add New Post</h1>

                <form id="add-post-form">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Post Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="featured_image" class="form-label">Featured Image</label>
                        <input type="file" class="form-control" id="featured_image" name="featured_image" accept="image/*">
                    </div>
                    <div id="preview-container" style="margin-top: 10px;">
                        <img id="preview-image" src="" alt="Preview Image" style="max-width: 200px; display: none;">
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Post Content</label>
                        <textarea class="form-control" id="content" name="content" rows="10"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="draft">Draft</option>
                            <option value="publish">Publish</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="lang" class="form-label">Language</label>
                        <select class="form-control" id="lang" name="lang">
                            <option value="id">Indonesian</option>
                            <option value="en">English</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Post</button>
                    <a href="{{ route('posts') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </main>

    @include('partials.js')

    <script>
        // Initialize TinyMCE editor
        tinymce.init({
            selector: '#content',
            height: 300,
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor colorpicker textpattern',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code'
        });

        $('#featured_image').on('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-image').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(file);
            } else {
                $('#preview-image').hide();
            }
        });

        $('#add-post-form').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append('content', tinymce.get('content').getContent());

            var file = formData.get('featured_image');
            if (file.size > 2000000) {
                Swal.fire('Error', 'Ukuran file terlalu besar. Maksimum 2MB.', 'error');
                return;
            }


            $.ajax({
                url: '/api/post-add',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Post successfully added',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '{{ route('posts') }}';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to add post',
                            text: response.message,
                            showConfirmButton: true
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'An error occurred',
                        text: error,
                        showConfirmButton: true
                    });
                }
            });
        });
    </script>
</body>

</html>