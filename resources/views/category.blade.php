@extends('layouts.app')

@section('content')
    <section>
        <div class="container">
            <!-- Category Header -->
            <div class="py-3 border-bottom border-success-subtle">
                <h2 class="text-dark fs-4 fw-bold mb-0 d-flex align-items-center">
                    <span class="me-2"><i class="fa-solid fa-list"></i></span>
                    {{ $category->name }} Escorts
                    <span class="badge bg-warning text-black ms-3 fs-6">
                        ({{ $escorts->total() }} Total)
                    </span>
                </h2>
            </div>

            <!-- Escorts Grid -->
            <div class="row mt-4" id="escorts-container">
                @forelse($escorts as $escort)
                    @php
                        $photo = $escort->usermeta->where('meta_key', 'profile_picture')->first()->meta_value ?? 'default.jpg';
                        $location = $escort->usermeta->where('meta_key', 'country_code')->first()->meta_value ?? 'N/A';
                        $price = $escort->usermeta->where('meta_key', 'subscription_price')->first()->meta_value ?? 'N/A';
                        $package = $escort->usermeta->where('meta_key', 'package')->first()->meta_value ?? 'Basic Package';
                    @endphp
                    <div class="col-md-2 col-6 mb-4 escort-card-item"
                        data-country="{{ $escort->usermeta->where('meta_key', 'country_id')->first()->meta_value ?? '' }}">
                        <div class="card position-relative overflow-hidden escort-card rounded shadow-sm"
                            data-escort-id="{{ $escort->id }}" data-name="{{ $escort->name }}" data-price="${{ $price }}"
                            data-package="{{ $package }}">
                            <div class="py-3 bg-light">
                                <h6 class="text-center text-main">{{ $escort->name }}</h6>
                            </div>
                            <div class="image_container position-relative">
                                <img src="{{ asset('storage/' . $photo) }}" alt="{{ $escort->name }} Photo"
                                    class="img-fluid w-100" style="height: 200px; object-fit: cover;" />
                                <div class="tags position-absolute top-0 start-0 d-flex flex-column p-2 gap-1">
                                    <span class="tag-1 position-relative">
                                        <img src="{{ asset('images/VIP.png') }}" alt="VIP Tag" class="rounded-circle" />
                                    </span>
                                    <span class="tag-2 position-relative">
                                        <img src="{{ asset('images/Independent.png') }}" alt="Independent Tag"
                                            class="rounded-circle" />
                                    </span>
                                    <span class="tag-3 position-relative">
                                        <img src="{{ asset('images/video.png') }}" alt="Video Tag" class="rounded-circle" />
                                    </span>
                                </div>
                            </div>
                            <div class="image_footer py-2 bg-light text-center">
                                <p class="text-muted">Escort</p>
                            </div>
                            <div class="onOverlay position-absolute d-flex flex-column justify-content-center align-items-center w-100 h-100"
                                style="background-color: rgba(0, 0, 0, 0.7); top: 0; left: 0;">
                                <a href="{{ route('listing.profile', ['id' => $escort->id]) }}"
                                    class="overlay-link text-decoration-none w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                    <div
                                        class="text-white fs-6 fw-normal font-family-Inter text-uppercase m-0 px-3 py-2 text-center">
                                        <p class="m-0">{{ strtoupper($escort->name) }}</p>
                                        <h4 class="m-0 mt-1">${{ $price }}</h4>
                                    </div>
                                    <div class="col-10 mx-auto text-center">
                                        <p class="text-white mb-1">
                                            <span class="me-2"><i class="fa-solid fa-location-dot"></i></span>
                                            {{ $location }}
                                        </p>
                                        <p class="text-white">
                                            <span class="me-2"><i class="fa-solid fa-circle text-success"></i></span>
                                            Available
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <h5 class="text-muted">No escorts found in this category</h5>
                            <p class="text-muted">Try checking other categories or adjust your filters.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($escorts->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Escort pagination">
                        <ul class="pagination pagination-sm">
                            @if ($escorts->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link bg-light text-muted border-0 px-3 py-2">Previous</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link bg-light text-dark border-0 px-3 py-2"
                                        href="{{ $escorts->previousPageUrl() }}">Previous</a>
                                </li>
                            @endif
                            @foreach ($escorts->getUrlRange(1, $escorts->lastPage()) as $page => $url)
                                @if ($page == $escorts->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link text-white px-3 py-2"
                                            style="background-color: #eebf01; border-color: #eebf01;">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link bg-light text-dark border-0 px-3 py-2" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                            @if ($escorts->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link bg-light text-dark border-0 px-3 py-2"
                                        href="{{ $escorts->nextPageUrl() }}">Next</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link bg-light text-muted border-0 px-3 py-2">Next</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </section>
@endsection

<style>
    .escort-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .escort-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .onOverlay {
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .escort-card:hover .onOverlay {
        opacity: 1;
    }

    .tags {
        gap: 5px !important;
    }

    .tags img {
        width: 40px;
        /* Reduced size for better fit with 6 cards */
        height: 40px;
        opacity: 0.8;
        transition: opacity 0.3s ease;
        border-radius: 50%;
    }

    .tags img:hover {
        opacity: 1;
    }
</style>