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
                <h1 class="my-4">Edit Post</h1>

                <form id="edit-post-form">
                    <div class="mb-3">
                        <label for="title" class="form-label">Post Title</label>
                        <input type="text" class="form-control" id="title" name="title" required value="{{ $post['title']['rendered'] ?? '' }}">
                        <input type="text" class="form-control" id="id" name="id" required value="{{ $post['id'] }}" hidden>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Post Content</label>
                        <textarea class="form-control" id="content" name="content" rows="10">{{ $post['content']['rendered'] ?? '' }}</textarea>
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
            height: 700,
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor colorpicker textpattern',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code'
        });

        $('#edit-post-form').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append('content', tinymce.get('content').getContent());

            $.ajax({
                url: '/api/post-update',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false, 
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Post successfully updated',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '{{ route('posts') }}';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to update post',
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