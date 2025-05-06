<!doctype html>
<html lang="en">

@include('partials.css')


<body>

    <main class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <div
            class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="{{ asset('assets/images/logos/logo.png') }}" alt="">
                                </a>
                                <hr>
                                <form id="loginForm">
                                    <div class="mb-3">
                                        <label for="identifier" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="identifier" placeholder="Username or Email">
                                    </div>
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" placeholder="Password">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Sign In</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    @include('partials.js')
    <script>
        $('#loginForm').submit(function(e) {
            e.preventDefault();

            const identifier = $('#identifier').val();
            const password = $('#password').val();

            if (!identifier || !password) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Harap isi semua kolom',
                    showConfirmButton: true
                });
                return;
            }

            login(identifier, password);
        });


        function login(identifier, password) {
            $.ajax({
                url: '/api/login',
                type: 'POST',
                data: {
                    identifier: identifier,
                    password: password
                },
                success: function(response) {
                    if (response.message == 'Login successful') {
                        window.location.href = '/';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        });
                    }
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.responseJSON.message,
                    });
                }
            });
        }
    </script>
</body>