<div class="header pb-2 pt-4 pt-lg-4 d-flex align-items-center">
    <!-- Mask -->
    {{-- <span class="mask opacity-8"></span> --}}
    <!-- Header container -->
    <div class="container-fluid d-flex align-items-center">
    <div class="col-12 pl-0">
        {{-- {{ $class ?? '' }} --}}
            <div class="">
                <h1 class="lw-page-title display-2 mt-md-4">{{ $title }}</h1>
                @if (isset($description) && $description)
                <p class=" mt-0 mb-3">{{ $description }}</p>
                @endif
            </div>
        </div>
    </div>
</div>