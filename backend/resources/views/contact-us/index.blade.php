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
                <h1 class="mb-4">Contact Us Messages</h1>
                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Date</th>
                                <th>Message</th>
                                <!-- <th>Aksi</th> -->
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @forelse ($messages as $msg)
                            <tr>
                                <td>{{ $msg['name'] }}</td>
                                <td>{{ $msg['email'] }}</td>
                                <td>{{ $msg['phone'] }}</td>
                                <td>{{ $msg['created_at'] }}</td>
                                <td>{{ $msg['message'] }}</td>
                                <!-- <td>
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </td> -->
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada pesan.</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    @include('partials.js')
</body>