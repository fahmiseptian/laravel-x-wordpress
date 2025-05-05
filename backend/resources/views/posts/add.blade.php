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
                <h1 class="my-4">Tambah Postingan Baru</h1>

                <form id="add-post-form">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Postingan</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Konten Postingan</label>
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
                        <label for="lang" class="form-label">Bahasa</label>
                        <select class="form-control" id="lang" name="lang">
                            <option value="id">Indonesia</option>
                            <option value="en">Inggris</option>
                        </select>
                    </div>

                    <!-- Input Gambar Sampul -->
                    <div class="mb-3">
                        <label for="featured_image" class="form-label">Gambar Sampul</label>
                        <input type="file" class="form-control" id="featured_image" name="featured_image"
                            accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Postingan</button>
                    <a href="{{ route('posts') }}" class="btn btn-secondary">Kembali</a>
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

        $('#add-post-form').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append('content', tinymce.get('content').getContent());

            $.ajax({
                url: '/api/post-add',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Post berhasil ditambahkan');
                        window.location.href = '{{ route('posts') }}';
                    } else {
                        alert('Gagal menambahkan post: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        });
    </script>
</body>

</html>
