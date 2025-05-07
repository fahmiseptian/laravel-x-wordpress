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
                <h1 class="mb-4">Banner Settings</h1>
                <form action="" method="POST" enctype="multipart/form-data">
                    {{-- === Banner Dashboard === --}}
                    <h2>Banner Dashboard</h2>
                    <div class="row" id="banner_dashboard_wrapper">
                        @forelse ($banners['banner_dashboard'] ?? [] as $index => $url)
                        <div class="col-md-3 mb-3 text-center banner-item">
                            <img src="{{ $url }}" class="img-fluid mb-2" style="max-width:150px; height:auto;">
                            <button type="button" class="btn btn-danger btn-sm remove-banner btn-delete-banner" data-url="{{ $url }}">Delete</button>
                            <input type="hidden" name="banner_dashboard[]" value="{{ $url }}">
                        </div>
                        @empty
                        <p class="text-muted">No dashboard banner yet.</p>
                        @endforelse
                    </div>
                    <button type="button" class="btn btn-primary mb-4" id="add-dashboard-banner">Choose Image (Max 4)</button>

                    {{-- === Banner Contact Us === --}}
                    <h2>Banner Contact Us</h2>
                    <div class="mb-3" id="banner_contact_wrapper">
                        @if ($banners['banner_contact'])
                        <div class="banner-item text-center">
                            <img src="{{ $banners['banner_contact'] }}" class="img-fluid mb-2" style="max-width:300px; height:auto;">
                            <button type="button" class="btn btn-danger btn-sm remove-banner btn-delete-banner" data-url="{{ $banners['banner_contact'] }}">Delete</button>
                            <input type="hidden" name="banner_contact" value="{{ $banners['banner_contact'] }}">
                        </div>
                        @else
                        <p class="text-muted">No banner for the Contact Us page yet.</p>
                        @endif
                    </div>
                    <button type="button" class="btn btn-primary mb-4" id="add-contact-banner">Choose Image</button>
                    <input type="file" id="banner-file-dashboard" accept="image/*" style="display: none;" multiple>
                    <input type="file" id="banner-file-contact" accept="image/*" style="display: none;">

                    <br>
                    <button type="button" class="btn btn-success" onclick="saveChanges()">Save Changes</button>
            </div>
        </div>
    </main>

    @include('partials.js')
    <script>
        $('#add-dashboard-banner').click(function() {
            Swal.fire({
                title: 'Pilih Sumber Gambar',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Upload dari Komputer',
                denyButtonText: 'Gunakan URL',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#banner-file-dashboard').click();
                } else if (result.isDenied) {
                    addBannerFromURL('dashboard');
                }
            });
        });
        $('#add-contact-banner').click(function() {
            Swal.fire({
                title: 'Pilih Sumber Gambar',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Upload dari Komputer',
                denyButtonText: 'Gunakan URL',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#banner-file-contact').click();
                } else if (result.isDenied) {
                    addBannerFromURL('contact');
                }
            });
        });

        function addBannerFromURL(type) {
            let imgUrl = prompt("Masukkan URL gambar:");
            if (imgUrl && isValidUrl(imgUrl)) {
                if (type === 'dashboard') {
                    const wrapper = $('#banner_dashboard_wrapper');
                    if (wrapper.children().length >= 4) {
                        alert('Maksimal 4 banner.');
                        return;
                    }
                    const div = $('<div class="col-md-3 mb-3 text-center banner-item"></div>');
                    div.html(`
                <img src="${imgUrl}" class="img-fluid mb-2" style="max-width:150px;">
                <button type="button" class="btn btn-danger btn-sm remove-banner btn-delete-banner" data-url="${imgUrl}">Hapus</button>
                <input type="hidden" name="banner_dashboard_url[]" value="${imgUrl}">
            `);
                    wrapper.append(div);
                } else if (type === 'contact') {
                    const wrapper = $('#banner_contact_wrapper');
                    wrapper.html(`
                <div class="banner-item text-center">
                    <img src="${imgUrl}" class="img-fluid mb-2" style="max-width:300px;">
                    <button type="button" class="btn btn-danger btn-sm remove-banner btn-delete-banner" data-url="${imgUrl}">Hapus</button>
                    <input type="hidden" name="banner_contact_url" value="${imgUrl}">
                </div>
            `);
                }
            } else {
                alert("URL tidak valid.");
            }
        }

        $('#banner-file-dashboard').change(function(e) {
            const files = e.target.files;
            const wrapper = $('#banner_dashboard_wrapper');
            if (wrapper.children().length + files.length > 4) {
                alert('Total maksimal 4 banner.');
                return;
            }
            for (const file of files) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = $('<div class="col-md-3 mb-3 text-center banner-item"></div>');
                    div.html(`
                <img src="${e.target.result}" class="img-fluid mb-2" style="max-width:150px;">
                <button type="button" class="btn btn-danger btn-sm remove-banner btn-delete-banner" data-url="${e.target.result}">Hapus</button>
                <input type="hidden" name="banner_dashboard_file[]" data-filename="${file.name}">
            `);
                    div.data('file', file); // Simpan file ke elemen div
                    wrapper.append(div);
                };
                reader.readAsDataURL(file);
            }
        });

        $('#banner-file-contact').change(function(e) {
            const file = e.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = $('#banner_contact_wrapper');
                const div = $(`
            <div class="banner-item text-center">
                <img src="${e.target.result}" class="img-fluid mb-2" style="max-width:300px;">
                <button type="button" class="btn btn-danger btn-sm remove-banner btn-delete-banner" data-url="${e.target.result}">Hapus</button>
                <input type="hidden" name="banner_contact_file" data-filename="${file.name}">
            </div>
        `);
                div.data('file', file);
                wrapper.html(div);
            };
            reader.readAsDataURL(file);
        });


        // Remove banner
        $(document).on('click', '.remove-banner', function() {
            $(this).closest('.banner-item').remove();
        });

        function isValidUrl(url) {
            try {
                new URL(url);
                return true;
            } catch (_) {
                return false;
            }
        }

        function saveChanges() {
            const formData = new FormData();

            // dashboard dari URL
            $('input[name="banner_dashboard_url[]"]').each(function(index) {
                formData.append(`banner_dashboard_url[${index}]`, $(this).val());
            });

            // dashboard dari file
            $('#banner_dashboard_wrapper .banner-item').each(function(index) {
                const file = $(this).data('file');
                if (file) {
                    formData.append(`banner_dashboard_file[${index}]`, file);
                }
            });

            // contact dari URL
            const contactUrl = $('input[name="banner_contact_url"]').val();
            if (contactUrl) {
                formData.append('banner_contact_url', contactUrl);
            }

            // contact dari file
            const contactFile = $('#banner_contact_wrapper .banner-item').data('file');
            if (contactFile) {
                formData.append('banner_contact_file', contactFile);
            }

            $.ajax({
                url: '/api/banners/update',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    alert('Perubahan berhasil disimpan.');
                    window.location.reload();
                },
                error: function(error) {
                    console.error(error);
                    alert('Terjadi kesalahan saat menyimpan perubahan.');
                }
            });
        }

        $('.btn-delete-banner').on('click', function() {
            const url = $(this).data('url'); // URL gambar banner

            Swal.fire({
                title: 'Yakin hapus banner ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/banners',
                        method: 'DELETE',
                        data: {
                            url: url,
                        },
                        success: function(res) {
                            Swal.fire('Berhasil!', 'Banner dihapus.', 'success');
                            // Hapus elemen dari DOM
                            $('div[data-banner-url="' + url + '"]').remove();
                        },
                        error: function() {
                            Swal.fire('Error', 'Gagal menghapus banner.', 'error');
                        }
                    });
                }
            });
        });
    </script>
</body>