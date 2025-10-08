@forelse($escorts as $escort)
    @php
        $photo = $escort->usermeta->where('meta_key', 'profile_picture')->first()->meta_value ?? 'default.jpg';
        $cityId = $escort->usermeta->where('meta_key', 'city')->first()->meta_value ?? '';
        $city = \App\Models\City::find($cityId)->name ?? ''; // City ID se name fetch
        $countryId = $escort->usermeta->where('meta_key', 'country')->first()->meta_value ?? '';
        $location = $escort->usermeta->where('meta_key', 'country_code')->first()->meta_value ??
            (\App\Models\Country::find($countryId)->country_code ?? 'N/A'); // Country ID se code fetch
        $price = $escort->usermeta->where('meta_key', 'subscription_price')->first()->meta_value ?? 'N/A';
        $package = $escort->usermeta->where('meta_key', 'package')->first()->meta_value ?? 'Basic Package';
    @endphp
    <div class="col-md-4 col-6 mb-4 escort-card-item"
        data-country="{{ $escort->usermeta->where('meta_key', 'country_id')->first()->meta_value ?? '' }}">
        <div class="card position-relative overflow-hidden escort-card" data-escort-id="{{ $escort->id }}"
            data-name="{{ $escort->name }}" data-price="${{ $price }}" data-package="{{ $package }}">
            <div class="py-3">
                <h6 class="text-center text-main">{{ $escort->name }}</h6>
            </div>
            <div class="image_container position-relative">
                <img src="{{ asset('storage/' . $photo) }}" alt="{{ $escort->name }} Photo" class="img-fluid" />
                <div class="tags">
                    <span class="tag-1 position-relative">
                        <img src="{{ asset('images/VIP.png') }}" alt="VIP Tag" />
                    </span>
                    <span class="tag-2 position-relative">
                        <img src="{{ asset('images/Independent.png') }}" alt="Independent Tag" />
                    </span>
                    <span class="tag-3 position-relative">
                        <img src="{{ asset('images/video.png') }}" alt="Video Tag" />
                    </span>
                </div>
            </div>
            <div class="image_footer py-2">
                <p class="text-center">Escort </p> <!-- Ab name aayega -->
            </div>
            <div class="onOverlay position-absolute d-flex flex-column justify-content-center align-items-center">
                <a href="{{ route('listing.profile', ['id' => $escort->id]) }}" class="overlay-link">
                    <div class="text-white fs-6 fw-normal font-family-Inter text-uppercase m-0 px-3 py-2 ">
                        <p class="m-0 text-center">{{ strtoupper($escort->name) }}</p>
                        <h4 class="m-0 text-center mt-1">${{ $price }}</h4>
                    </div>
                    <div class="col-8 mx-auto">
                        <p class="text-center text-white">
                            <span class="me-2"><i class="fa-solid fa-location-dot"></i></span>
                            {{ $location }} <!-- Ab code aayega -->
                        </p>
                        <p class="text-center text-white mt-2">
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
            <h5 class="text-muted">No escorts found</h5>
            <p class="text-muted">Try adjusting your filters or search criteria.</p>
        </div>
    </div>
@endforelse