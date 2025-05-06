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
                <h1 class="mb-4">Users</h1>
                <div class="mb-2">
                    <a href="{{ route('users.add') }}" class="btn btn-success">Add User</a>
                </div>
                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>User Name</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->name }}</td>
                                <td>
                                    @if($user->status == 'active')
                                    <a href="#" onclick="editStatus('{{ $user->id }}', 'inactive')" class="badge bg-success">Active</a>
                                    @else
                                    <a href="#" onclick="editStatus('{{ $user->id }}', 'active')" class="badge bg-warning">Inactive</a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('users.edit', ['id' => $user->id]) }}" class="btn btn-primary">Edit</a>
                                    <a href="#" class="btn btn-warning" onclick="openChangePasswordModal('{{ $user->id }}')">Change Password</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Ganti Password -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="changePasswordForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ganti Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="changeUserId" name="user_id">
                        <div class="mb-3">
                            <label>Password Lama</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="old_password" required>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="togglePasswordType('old_password')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="new_password" required>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="togglePasswordType('new_password')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="new_password_confirmation" required>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="togglePasswordType('new_password_confirmation')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('partials.js')
    <script>
        function togglePasswordType(text) {
            var input = document.getElementsByName(text)[0];
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }

        function editStatus(id, status) {
            $.ajax({
                url: '/api/users/update-status',
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(response) {
                    if (response.message === 'Status updated') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Status berhasil diperbarui',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal memperbarui status',
                            text: response.message,
                            showConfirmButton: true
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan',
                        text: xhr.responseJSON.message,
                        showConfirmButton: true
                    });
                }
            });
        }

        function openChangePasswordModal(userId) {
            $('#changeUserId').val(userId);
            $('#changePasswordForm')[0].reset();
            $('#changePasswordModal').modal('show');
        }

        $('#changePasswordForm').submit(function(e) {
            e.preventDefault();

            const userId = $('#changeUserId').val();
            const formData = $(this).serialize() + '&id=' + userId;

            $.ajax({
                url: '/api/users/change-password',
                method: 'PUT',
                data: formData,
                success: function(res) {
                    Swal.fire('Berhasil', res.message, 'success');
                    $('#changePasswordModal').modal('hide');
                },
                error: function(xhr) {
                    let errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire('Gagal', errorMsg, 'error');
                }
            });
        });
    </script>
</body>