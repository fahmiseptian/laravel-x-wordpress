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
                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @forelse ($pages as $page)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $page['title']['rendered'] ?? '-' }}</td>
                                <td>{{ $page['slug'] }}</td>
                                <td>
                                    @if($page['status'] === 'publish')
                                    <span title="Published" style="color: green;" onclick="updatePageStatus('{{ $page['id'] }}', 'draft')">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </span>
                                    @else
                                    <span title="Draft" style="color: gray;" onclick="updatePageStatus('{{ $page['id'] }}', 'publish')">
                                        <i class="bi bi-dash-circle-fill"></i>
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <!-- Edit (lempar ke Elementor) -->
                                    <a href="{{ env('URL_WP') }}/auto-login?token={{ env('TOKEN_WP') }}&post={{ $page['id'] }}" class="btn btn-sm btn-primary" title="Edit Elementor" target="_blank">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <!-- Delete -->
                                    <button class="btn btn-sm btn-danger" title="Delete" onclick="deletePage('{{ $page['id'] }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data halaman.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </main>

    @include('partials.js')

    <script>
        function updatePageStatus(id, status) {
            $.ajax({
                url: '/api/page-update',
                type: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Status halaman berhasil diperbarui.');
                        location.reload();
                    } else {
                        alert('Gagal memperbarui status halaman.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        }

        function deletePage(id) {
            if (confirm('Apakah Anda yakin ingin menghapus halaman ini?')) {
                $.ajax({
                    url: '/api/page-delete/' + id,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert('Halaman berhasil dihapus.');
                            location.reload();
                        } else {
                            alert('Gagal menghapus halaman.');
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