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
                <div class="mb-2">
                    <a href="{{ route('posts.add') }}" class="btn btn-success">Add New Post</a>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table text-nowrap align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Cover</th>
                                <th>Title</th>
                                <th>Language</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @foreach ($posts as $index => $post)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="position: relative;">
                                    @if($post['cover'])
                                    <div style="position: relative; display: inline-block;">
                                        <img src="{{ $post['cover'] }}" alt="cover" style="max-width: 200px;">
                                        <button class="edit-cover-btn" data-post-id="{{ $post['id'] }}" style="position: absolute; top: 5px; right: 5px; background: transparent; border: none; cursor: pointer;">
                                            <img src="https://cdn-icons-png.flaticon.com/512/84/84380.png" alt="Edit" style="width: 24px; height: 24px;">
                                        </button>
                                    </div>
                                    @endif
                                    <input type="file" class="cover-input" data-post-id="{{ $post['id'] }}" style="display: none;" accept="image/*">
                                </td>
                                <td>{{ $post['title']['rendered'] }}</td>
                                <td>{{ $post['lang'] == 'en' ? 'English' : 'Indonesia' }}</td>
                                <td>
                                    @if ($post['status'] !== 'publish')
                                    <button class="btn btn-warning btn-sm update-status"
                                        onclick="updatePostStatus('{{ $post['id'] }}', 'publish')"> <i
                                            class="bi bi-arrow-down-circle-fill"></i> Draft</button>
                                    @else
                                    <button class="btn btn-success btn-sm update-status"
                                        onclick="updatePostStatus('{{ $post['id'] }}', 'draft')"><i
                                            class="bi bi-arrow-up-circle-fill"></i> Publish</button>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('posts.edit', ['id' => $post['id']]) }}" class="btn btn-primary btn-sm edit-post"><i
                                            class="bi bi-pencil-square"></i> Edit</a>
                                    <button class="btn btn-danger btn-sm delete-post"
                                        onclick="deletePost('{{ $post['id'] }}')"><i class="bi bi-trash"></i>
                                        Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </main>
    @include('partials.js')

    <script>
        function updatePostStatus(id, status) {
            $.ajax({
                url: '/api/post-update-status',
                type: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire('Success', 'Status postingan berhasil diperbarui.', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', 'Gagal memperbarui status postingan.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        }

        function deletePost(id) {
            if (Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Postingan ini akan dihapus secara permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        return true;
                    }
                    return false;
                })) {
                $.ajax({
                    url: '/api/post-delete/' + id,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Success', 'Postingan berhasil dihapus.', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', 'Gagal menghapus postingan.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error', 'Terjadi kesalahan: ' + error, 'error');
                    }
                });
            }
        }

        $(document).on('click', '.edit-cover-btn', function() {
            var postId = $(this).data('post-id');
            $('.cover-input[data-post-id="' + postId + '"]').click();
        });

        // Ketika file cover diubah
        $(document).on('change', '.cover-input', function() {
            var postId = $(this).data('post-id');
            var file = this.files[0];
            if (file.size > 2000000) {
                Swal.fire('Error', 'Ukuran file terlalu besar. Maksimum 2MB.', 'error');
                return;
            }

            if (file) {
                var formData = new FormData();
                formData.append('cover', file);
                formData.append('post_id', postId);

                $.ajax({
                    url: '/api/posts/update-cover',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Updating cover...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        Swal.fire('Success', 'Cover updated successfully', 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to update cover', 'error');
                    }
                });
            }
        });
    </script>
</body>