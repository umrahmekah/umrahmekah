<div class="navbar__bg-white" id="navbar_id">
    {{-- <div class="d-flex navbar__top align-center container">
        <div class="mr-auto">
            <ul class="d-flex ul-none mb-0 pl-0">
                <li>
                    <a href="#">
                        <i class="fas fa-phone"></i> {{ CNF_TEL }}
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="far fa-envelope"></i> {{ CNF_EMAIL }}
                    </a>
                </li>
            </ul>
        </div>
        <div class="ml-auto">
            @include('layouts.blue-ocean.components.social-media-link')
        </div>
    </div> --}}
    <nav class="navbar navbar-expand-lg" style="padding: 0px 10%">
        <div class="container" style="padding: 0px;">
            <a href="/" class="navbar-brand" style="margin: 0px;">
                @include('layouts.blue-ocean.components.icon')
            </a>
            <button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler xy-center">
                <i class="fas fa-bars text-white"></i>
            </button>
            <div id="navbarSupportedContent" class="collapse navbar-collapse">
                @include('layouts.blue-ocean.components.menus')
            </div>
        </div>
    </nav>
</div>