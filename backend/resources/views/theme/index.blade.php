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
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <a href="{{ env('URL_WP') }}/auto-login?token={{ env('TOKEN_WP') }}&post=668" target="_blank">
                                <img src="{{ asset('assets/images/footer.png') }}" width="200px" height="100px" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">Footer</h5>
                                    <p class="card-text">Konten Footer.</p>
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <a href="{{ env('URL_WP') }}/auto-login?token={{ env('TOKEN_WP') }}&post=860" target="_blank">
                                <img src="{{ asset('assets/images/header.png') }}" width="200px" height="100px" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">Header</h5>
                                    <p class="card-text">Konten Header.</p>
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <a href="{{ env('URL_WP') }}/auto-login?token={{ env('TOKEN_WP') }}&post=832" target="_blank">
                                <img src="{{ asset('assets/images/article.png') }}" width="200px" height="100px" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">Article</h5>
                                    <p class="card-text">Konten Artikel.</p>
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('partials.js')
</body>