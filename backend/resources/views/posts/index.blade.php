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
                    <a href="{{ route('posts.add') }}" class="btn btn-success">Tambah Postingan Baru</a>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table text-nowrap align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @foreach ($posts as $index => $post)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $post['title']['rendered'] }}</td>
                                <td>
                                    @if ($post['status'] !== 'publish')
                                    <button class="btn btn-warning btn-sm update-status" onclick="updatePostStatus('{{ $post['id'] }}', 'publish')"> <i class="bi bi-arrow-down-circle-fill"></i> Draft</button>
                                    @else
                                    <button class="btn btn-success btn-sm update-status" onclick="updatePostStatus('{{ $post['id'] }}', 'draft')"><i class="bi bi-arrow-up-circle-fill"></i> Publish</button>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm edit-post" data-id="{{ $post['id'] }}" data-title="{{ $post['title']['rendered'] }}"><i class="bi bi-pencil-square"></i> Edit</button>
                                    <button class="btn btn-danger btn-sm delete-post" onclick="deletePost('{{ $post['id'] }}')"><i class="bi bi-trash"></i> Delete</button>
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
                        alert('Status postingan berhasil diperbarui.');
                        location.reload();
                    } else {
                        alert('Gagal memperbarui status postingan.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        }

        function deletePost(id) {
            if (confirm('Apakah Anda yakin ingin menghapus postingan ini?')) {
                $.ajax({
                    url: '/api/post-delete/' + id,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert('Postingan berhasil dihapus.');
                            location.reload();
                        } else {
                            alert('Gagal menghapus postingan.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan: ' + error);
                    }
                });
            }
        }
    </script>
</body>