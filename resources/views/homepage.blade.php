@extends('layouts.app')

@section('content')
  <section>
    <div class="container">
      <div class="row">
        <!-- Header Section -->
        <!-- ================= LEFT COLUMN ================= -->
        <div class="col-md-3">
          <!-- Categories -->
          <div class="container-fluid py-3">
            <div class="row">
              <div class="px-1 py-1 bg-warning col-12 align-items-center">
                <p class="text-black fs-5 fw-semibold font-family-Inter m-0 px-3 py-2">Categories</p>
              </div>
              @foreach($categories as $category)
                <div class="px-1 pb-1 bg-white border border-bottom border-success-subtle col-12 align-items-center">
                  <div class="col-0 d-flex justify-content-between align-items-center">
                    <p class="text-secondary fs-14 fw-normal font-family-Inter m-0 px-3 py-2">
                      {{ $category->name }}
                    </p>
                    <p class="text-secondary fs-14 fw-normal font-family-Inter m-0 px-3 py-2">
                      ({{ (int) ($category->escorts_count ?? 0) }})
                    </p>
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <!-- Popular Countries -->
          <div class="container-fluid py-3">
            <div class="row justify-content-center">
              <div class="px-1 py-1 bg-warning col-12 align-items-center">
                <p class="text-black fs-5 fw-semibold font-family-Inter m-0 px-3 py-2">Popular Countries</p>
              </div>
              <div class="position-relative bg-white border border-bottom border-success-subtle">
                <div class="row justify-content-end align-items-center">
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-8 px-3 py-2">
                    <span class="w-24 d-inline-block">
                      <img class="img-fluid" src="images/turkey.jpg" alt="Turkey Flag" />
                    </span>
                    Turkey
                  </p>
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-4 m-0 px-3 py-2">
                    (757)
                  </p>
                </div>
              </div>
              <div class="position-relative bg-white border border-bottom border-success-subtle">
                <div class="row justify-content-end align-items-center">
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-8 px-3 py-2">
                    <span class="w-24 d-inline-block">
                      <img class="img-fluid" src="images/united-arab-emirates-flag-png-large.jpg" alt="UAE Flag" />
                    </span>
                    United Arab Emirates
                  </p>
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-4 m-0 px-3 py-2">
                    (2890)
                  </p>
                </div>
              </div>
              <div class="position-relative bg-white border border-bottom border-success-subtle">
                <div class="row justify-content-end align-items-center">
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-8 px-3 py-2">
                    <span class="w-24 d-inline-block">
                      <img class="img-fluid" src="images/greece-flag-png-large.jpg" alt="Greece Flag" />
                    </span>
                    Greece
                  </p>
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-4 m-0 px-3 py-2">
                    (2825)
                  </p>
                </div>
              </div>
              <div class="position-relative bg-white border border-bottom border-success-subtle">
                <div class="row justify-content-end align-items-center">
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-8 px-3 py-2">
                    <span class="w-24 d-inline-block">
                      <img class="img-fluid" src="images/russia-flag-png-large.jpg" alt="Russia Flag" />
                    </span>
                    Russia
                  </p>
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-4 m-0 px-3 py-2">
                    (2795)
                  </p>
                </div>
              </div>
              <div class="position-relative bg-white border border-bottom border-success-subtle">
                <div class="row justify-content-end align-items-center">
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-8 px-3 py-2">
                    <span class="w-24 d-inline-block">
                      <img class="img-fluid" src="images/united-kingdom-flag-png-large.jpg" alt="UK Flag" />
                    </span>
                    United Kingdom
                  </p>
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-4 m-0 px-3 py-2">
                    (2845)
                  </p>
                </div>
              </div>
              <div class="position-relative bg-white border border-bottom border-success-subtle">
                <div class="row justify-content-end align-items-center">
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-8 px-3 py-2">
                    <span class="w-24 d-inline-block">
                      <img class="img-fluid" src="images/cyprus-flag-png-large.jpg" alt="Cyprus Flag" />
                    </span>
                    Cyprus
                  </p>
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-4 m-0 px-3 py-2">
                    (2600)
                  </p>
                </div>
              </div>
              <div class="position-relative bg-white border border-bottom border-success-subtle">
                <div class="row justify-content-end align-items-center">
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-8 px-3 py-2">
                    <span class="w-24 d-inline-block">
                      <img class="img-fluid" src="images/united-states-of-america-flag-png-large.jpg" alt="USA Flag" />
                    </span>
                    United States
                  </p>
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-4 m-0 px-3 py-2">
                    (2425)
                  </p>
                </div>
              </div>
              <div class="position-relative bg-white border border-bottom border-success-subtle">
                <div class="row justify-content-end align-items-center">
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-8 px-3 py-2">
                    <span class="w-24 d-inline-block">
                      <img class="img-fluid" src="images/germany-flag-png-large.jpg" alt="Germany Flag" />
                    </span>
                    Germany
                  </p>
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-4 m-0 px-3 py-2">
                    (2325)
                  </p>
                </div>
              </div>
              <div class="position-relative bg-white border border-bottom border-success-subtle">
                <div class="row justify-content-end align-items-center">
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-8 px-3 py-2">
                    <span class="w-24 d-inline-block">
                      <img class="img-fluid" src="images/armenia.jpg" alt="Armenia Flag" />
                    </span>
                    Armenia
                  </p>
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-4 m-0 px-3 py-2">
                    (2215)
                  </p>
                </div>
              </div>
              <div class="position-relative bg-white border border-bottom border-success-subtle">
                <div class="row justify-content-end align-items-center">
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-8 px-3 py-2">
                    <span class="w-24 d-inline-block">
                      <img class="img-fluid" src="images/malaysia-flag-png-large.jpg" alt="Malaysia Flag" />
                    </span>
                    Malaysia
                  </p>
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-4 m-0 px-3 py-2">
                    (2115)
                  </p>
                </div>
              </div>
              <div class="position-relative bg-white border border-bottom border-success-subtle">
                <div class="row justify-content-end align-items-center">
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-8 px-3 py-2">
                    <span class="w-24 d-inline-block">
                      <img class="img-fluid" src="images/italy-flag-png-large.jpg" alt="Italy Flag" />
                    </span>
                    Italy
                  </p>
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-4 m-0 px-3 py-2">
                    (1956)
                  </p>
                </div>
              </div>
              <div class="position-relative bg-white border border-bottom border-success-subtle">
                <div class="row justify-content-end align-items-center">
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-8 px-3 py-2">
                    <span class="w-24 d-inline-block">
                      <img class="img-fluid" src="images/thailand-flag-png-large.jpg" alt="Thailand Flag" />
                    </span>
                    Thailand
                  </p>
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-4 m-0 px-3 py-2">
                    (1857)
                  </p>
                </div>
              </div>
              <div class="position-relative bg-white border border-bottom border-success-subtle">
                <div class="row justify-content-end align-items-center">
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-8 px-3 py-2">
                    <span class="w-24 d-inline-block">
                      <img class="img-fluid" src="images/france-flag-png-large.jpg" alt="France Flag" />
                    </span>
                    France
                  </p>
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-4 m-0 px-3 py-2">
                    (1607)
                  </p>
                </div>
              </div>
              <div class="position-relative bg-white border border-bottom border-success-subtle">
                <div class="row justify-content-end align-items-center">
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-8 px-3 py-2">
                    <span class="w-24 d-inline-block">
                      <img class="img-fluid" src="images/poland-flag-png-large.jpg" alt="Poland Flag" />
                    </span>
                    Poland
                  </p>
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-4 m-0 px-3 py-2">
                    (1107)
                  </p>
                </div>
              </div>
              <div class="position-relative bg-white border border-bottom border-success-subtle">
                <div class="row justify-content-end align-items-center">
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-8 px-3 py-2">
                    <span class="w-24 d-inline-block">
                      <img class="img-fluid" src="images/georgia-flag-jpg-xl.jpg" alt="Georgia Flag" />
                    </span>
                    Georgia
                  </p>
                  <p class="text-secondary fs-14 fw-normal font-family-Inter col-4 m-0 px-3 py-2">
                    (945)
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ================= MIDDLE COLUMN ================= -->
        <div class="col-md-7">
          <div>
            <div class="py-3">
              <div class="pb-1 row">
                <p class="text-dark fs-6 fw-normal font-family-Inter text-uppercase m-0 px-3 py-2">
                  Top Escorts
                </p>
                <div>
                  <a href="#" class="btn text-white bg-main-light border-0 text-dark mb-2 br-25">Travel Girls</a>
                  <a type="button" class="btn text-white bg-main-light border-0 text-dark mb-2 br-25">Escort News</a>
                  <a type="button" class="btn mb-2 br-25 bg-light-red border-0 text-white">Live Sex</a>
                  <a type="button" class="btn mb-2 br-25 bg-light-red border-0 text-white">Live Cams</a>
                  <a type="button" class="btn btn-dark mb-2 br-25 text-white">Meet & Fuck</a>
                  <a type="button" class="btn btn-dark mb-2 br-25 text-white">Live Porn</a>
                  <a type="button" class="btn bg-grey mb-2 br-25 text-white">Local Sex</a>
                  <a type="button" class="btn bg-grey mb-2 br-25 text-white">Escort United States</a>
                </div>
              </div>
            </div>

          {{-- Topbar Ads Slider --}}
          @if(isset($topbarAds) && $topbarAds->count() > 0)
              <div id="topbarAdsCarousel" class="carousel slide mb-3" data-bs-ride="carousel" data-bs-interval="3000">
                  <div class="carousel-inner text-center">
                      @foreach($topbarAds as $key => $ad)
                          <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                              <img 
                                  src="{{ asset($ad->image) }}" 
                                  class="d-block mx-auto"
                                  style="max-height: 150px; object-fit: cover;" 
                                  alt="{{ $ad->title ?? 'Advertisement' }}">
                          </div>
                      @endforeach
                  </div>
              </div>
          @else
              {{-- <div class="text-center mb-3">
                  <img src="{{ asset('images/ads.png') }}" class="img-fluid" style="max-height: 150px;" alt="Advertisement" />
              </div> --}}
          @endif




            <div class="d-flex justify-content-end">
              <button class="hide_filters btn">
                <span class="toggle-text">Hide filter options</span>
                <span><i class="fa-solid fa-angle-down"></i></span>
              </button>
            </div>

            <div class="mt-4 otherFilters">
              <div>
                <div class="clear d-flex flex-wrap" style="padding-top: 0px">
                  <label><input type="checkbox" class="me-2" /> Incall</label>
                  <label><input type="checkbox" class="me-2" /> Outcall</label>
                  <label><input type="checkbox" class="me-2" /> On Tour</label>
                  <label><input type="checkbox" class="me-2" /> Soon On Tour</label>
                  <label><input type="checkbox" class="me-2" /> Available for couples</label>
                  <label><input type="checkbox" class="me-2" /> Anal</label>
                  <label><input type="checkbox" class="me-2" /> Independent</label>
                  <label><input type="checkbox" class="me-2" /> Premium Hookers</label>
                  <label><input type="checkbox" class="me-2" /> VIP Hookers</label>
                  <label><input type="checkbox" class="me-2" /> 100% verified</label>
                  <label><input type="checkbox" class="me-2" /> Only with Video</label>
                  <label><input type="checkbox" class="me-2" /> Only with Reviews</label>
                  <label><input type="checkbox" class="me-2" /> Local</label>
                  <label><input type="checkbox" class="me-2" /> Window Girls</label>
                </div>
              </div>
              <div class="row mt-4">
                <div class="col-md-4">
                  <select class="form-select form-select-lg mb-3">
                    <option value="1" default>Countries</option>
                    <option value="2">United States</option>
                    <option value="3">United Kingdom</option>
                    <option value="4">Canada</option>
                    <option value="5">Australia</option>
                    <option value="6">Germany</option>
                    <option value="7">France</option>
                    <option value="8">Italy</option>
                    <option value="9">Spain</option>
                    <option value="10">Netherlands</option>
                    <option value="11">Sweden</option>
                    <option value="12">Belgium</option>
                    <option value="13">Switzerland</option>
                    <option value="14">Austria</option>
                    <option value="15">Norway</option>
                    <option value="16">Denmark</option>
                    <option value="17">Finland</option>
                    <option value="18">Ireland</option>
                    <option value="19">Portugal</option>
                    <option value="20">Poland</option>
                    <option value="21">Czech Republic</option>
                    <option value="22">Hungary</option>
                    <option value="23">Greece</option>
                    <option value="24">Turkey</option>
                    <option value="25">Russia</option>
                    <option value="26">Ukraine</option>
                    <option value="27">Bulgaria</option>
                    <option value="28">Romania</option>
                    <option value="29">Serbia</option>
                    <option value="30">Croatia</option>
                    <option value="31">Slovakia</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <select class="form-select form-select-lg mb-3">
                    <option value="1" default>City</option>
                    <option value="2">New York</option>
                    <option value="3">Los Angeles</option>
                    <option value="4">Chicago</option>
                    <option value="5">Houston</option>
                    <option value="6">Miami</option>
                    <option value="7">San Francisco</option>
                    <option value="8">Boston</option>
                    <option value="9">Seattle</option>
                    <option value="10">Dallas</option>
                    <option value="11">Atlanta</option>
                    <option value="12">Washington D.C.</option>
                    <option value="13">Philadelphia</option>
                    <option value="14">Phoenix</option>
                    <option value="15">San Diego</option>
                    <option value="16">Las Vegas</option>
                    <option value="17">Orlando</option>
                    <option value="18">Denver</option>
                    <option value="19">Austin</option>
                    <option value="20">Portland</option>
                    <option value="21">Charlotte</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <select class="form-select form-select-lg mb-3">
                    <option value="1" default>Sort Order</option>
                    <option value="2">Last Updated</option>
                    <option value="3">Near By</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row mt-4">
              <h4 class="mb-3">
                <span class="w-24 d-inline-block"><img src="images/VIP.png" class="img-fluid" alt="" /></span>
                VIP Advertisements
              </h4>
              <!-- VIP advertisements content will be added dynamically here if needed -->
              <div class="col-12">
                <!-- Empty for now as per request -->
              </div>
            </div>

            <div id="escorts-container" class="row">
              @forelse($escorts as $escort)
                @php
                  $photo = $escort->usermeta->where('meta_key', 'profile_picture')->first()->meta_value ?? 'default.jpg';
                  $city = $escort->usermeta->where('meta_key', 'city')->first()->meta_value ?? '';
                  $location = $escort->usermeta->where('meta_key', 'country_code')->first()->meta_value ?? 'N/A';
                  $price = $escort->usermeta->where('meta_key', 'subscription_price')->first()->meta_value ?? 'N/A';
                  $package = $escort->usermeta->where('meta_key', 'package')->first()->meta_value ?? 'Basic Package';
                @endphp
                <div class="col-md-4 col-6 mb-4 escort-card-item">
                  <div class="card position-relative overflow-hidden escort-card" data-escort-id="{{ $escort->id }}"
                    data-name="{{ $escort->name }}" data-price="${{ $price }}" data-package="{{ $package }}">
                    <div class="py-3">
                      <h6 class="text-center text-main">{{ $escort->name }}</h6>
                    </div>
                    <div class="image_container position-relative">
                      <img src="{{ asset("storage/{$photo}") }}" alt="{{ $escort->name }} Photo" class="img-fluid" />
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
                      <p class="text-center">Escort {{ $city }}</p>
                    </div>
                    <div class="onOverlay position-absolute d-flex flex-column justify-content-center align-items-center">
                      <div class="text-white fs-6 fw-normal font-family-Inter text-uppercase m-0 px-3 py-2">
                        <p class="m-0 text-center">{{ strtoupper($escort->name) }}</p>
                        <h4 class="m-0 text-center mt-1">${{ $price }}</h4>
                      </div>
                      <div class="col-8 mx-auto">
                        <p class="text-center text-white">
                          <span class="me-2"><i class="fa-solid fa-location-dot"></i></span>
                          {{ $location }}
                        </p>
                        <p class="text-center text-white mt-2">
                          <span class="me-2"><i class="fa-solid fa-circle text-success"></i></span>
                          Available
                        </p>
                      </div>
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
            </div>

            <!-- Load More Button -->
            @if($escorts->hasMorePages())
              <button id="load-more-btn" class="btn bg-main br-25 w-100 text-white py-2 mt-2" data-page="2">
                <span class="load-text">LOAD MORE ESCORTS</span>
                <span class="loading-text d-none">
                  <i class="fas fa-spinner fa-spin me-2"></i>Loading...
                </span>
              </button>
            @endif

            <!-- Pagination -->
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
                          style="background-color: #ffc107; border-color: #ffc107;">{{ $page }}</span>
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

            <div class="my-3 text-center">
              <p class="text-muted small">
                Showing {{ $escorts->firstItem() ?? 0 }} to {{ $escorts->lastItem() ?? 0 }}
                of {{ $escorts->total() }} escorts
              </p>
            </div>
          </div>
        </div>

        <!-- ================= RIGHT COLUMN ================= -->
        <div class="col-md-2">

          <div class="container-fluid py-3">
            @if(isset($sidebarAds) && $sidebarAds->count() > 0)
                <div id="sidebarAdsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                    <div class="carousel-inner">
                        @foreach($sidebarAds as $key => $ad)
                            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                <img 
                                    src="{{ asset($ad->image) }}" 
                                    class="d-block w-100"
                                    style="height: 600px; object-fit: cover;" 
                                    alt="{{ $ad->title ?? 'Advertisement' }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <img src="{{ asset('images/ads.png') }}" class="img-fluid" alt="Advertisement" />
            @endif
        </div>


        </div>
      </div>
    </div>
  </section>

  <!-- Login Modal -->
  <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content p-0" style="background: transparent !important; border: none;">
        <div class="container-fluid login-page p-0 m-0" style="background: transparent !important;">
          <div class="container py-4">
            <div class="row">
              <div class="col-sm-12 col-lg-6 offset-lg-3 col-md-6 offset-md-3">
                <div class="d-flex justify-content-center align-items-center">
                  <div class="glass-card">
                    <div class="text-center mb-4">
                      <h2 class="title mb-2">Welcome Back</h2>
                      <p class="text-muted fw-bold small">Sign in to your account</p>
                      @if(Session::has('success'))
                        <p class="small text-success">{{ Session::get('success') }}</p>
                      @endif
                      @if(Session::has('error'))
                        <p class="small text-danger">{{ Session::get('error') }}</p>
                      @endif
                    </div>
                    <form id="login-form" action="{{ route('authenticate') }}" method="post">
                      @csrf
                      <div class="form-group mb-3">
                        <label for="email" class="fw-bold mb-1 text-muted small">Email</label>
                        <input type="text" name="email" value="{{ old('email') }}"
                          class="form-control input-field @error('email') is-invalid @enderror" id="email"
                          placeholder="Enter your email">
                        @error('email')
                          <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                      </div>
                      <div class="form-group mb-1 show-password">
                        <label for="password" class="fw-bold mb-1 text-muted small">Password</label>
                        <input type="password" name="password"
                          class="form-control input-field @error('password') is-invalid @enderror" id="password" value=""
                          placeholder="Password">
                        <button type="button" class="show-password-btn">
                          <i class="fas fa-eye show-password-icon"></i>
                        </button>
                        @error('password')
                          <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                      </div>
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                        </div>
                        <a href="#" class="text-muted fw-normal text-decoration-none small">Forgot password?</a>
                      </div>
                      <button type="submit" class="btn btn-login w-100">LOGIN</button>
                    </form>
                    <div class="text-center mt-4">
                      <p class="text-muted fw-bolder small">Don't have an account? <a href="{{ route('register') }}"
                          class="font-weight-bold">Sign up</a></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>


  <!-- Billing Modal -->

  <div class="modal fade" id="billingModal" tabindex="-1" aria-labelledby="billingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content billing-card">
        <div class="modal-header border-0">
          <h5 class="modal-title text-white fw-bold">
            Payment for Your Chosen Escort:
            <span id="billing-escort-name" class="text-pink"></span>
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="text-light mb-1">Login User: {{ Auth::guard('fan')->user()->username ?? 'Guest' }}</p>
          <p class="text-light mb-3">Price: <span id="billing-price" class="fw-bold text-pink"></span></p>

          <form id="billing-form" action="{{ route('billing') }}">
            @csrf
            <input type="hidden" id="billing-escort-id" name="escort_id">

            <div class="row g-3">
              <div class="col-md-6">
                <input type="text" class="form-control custom-input" name="first_name" placeholder="First Name" required>
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control custom-input" name="last_name" placeholder="Last Name" required>
              </div>
              <div class="col-12">
                <input type="text" class="form-control custom-input" name="address"
                  placeholder="Address (Main street 1234)" required>
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control custom-input" name="city" placeholder="City" required>
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control custom-input" name="state" placeholder="State" required>
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control custom-input" name="zip_code" placeholder="Zip Code" required>
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control custom-input" name="country" placeholder="Country" required>
              </div>
            </div>

            <button type="submit" class="btn btn-pink w-100 mt-4 py-2 fw-bold rounded-pill">
              Continue to Checkout
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <style>
    /* Card Style */
    .billing-card {
      background: #1c1c1c;
      border-radius: 16px;
      box-shadow: 0 0 25px rgba(0, 0, 0, 0.6);
      padding: 15px;
    }

    /* Inputs */
    .custom-input {
      background: #2b2b2b;
      border: 1px solid #444;
      color: #fff;
      border-radius: 12px;
      padding: 12px 14px;
      transition: all 0.3s ease;
    }

    .custom-input:focus {
      border-color: #ff007f;
      box-shadow: 0 0 10px rgba(255, 0, 127, 0.6);
      outline: none;
      background: #222;
    }

    .custom-input::placeholder {
      color: #aaa;
    }

    /* Button */
    .btn-pink {
      background: linear-gradient(135deg, #ff007f, #ff4081);
      border: none;
      color: #fff;
      transition: 0.3s;
      font-size: 1.1rem;
      letter-spacing: 0.5px;
    }

    .btn-pink:hover {
      background: linear-gradient(135deg, #ff4081, #ff007f);
      box-shadow: 0 0 15px rgba(255, 0, 127, 0.8);
      transform: translateY(-2px);
    }

    /* Text color helper */
    .text-pink {
      color: #ff4081;
    }

    #billing-form input {
      color: #fff;


    }
  </style>

  </div>
  </div>
  </div>

  <link rel="stylesheet" href="{{ asset('css/user-login-style.css') }}">

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      window.history.pushState(null, document.title, window.location.href);
      let isBackNavigation = false;

      // Detect back/forward navigation
      window.addEventListener("pageshow", function (event) {
        if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
          $('#billingModal').modal('hide');
          $('#loginModal').modal('hide');
        }
      });

      // Password toggle
      const toggleBtn = document.querySelector(".show-password-btn");
      const passwordInput = document.getElementById("password");
      const icon = toggleBtn?.querySelector("i");

      if (toggleBtn && passwordInput && icon) {
        toggleBtn.addEventListener("click", function (e) {
          e.preventDefault();
          const type = passwordInput.getAttribute("type");
          if (type === "password") {
            passwordInput.setAttribute("type", "text");
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
          } else {
            passwordInput.setAttribute("type", "password");
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
          }
        });
      }

      // Handle escort card clicks
      let selectedEscort = null;
      const cards = document.querySelectorAll('.escort-card');
      cards.forEach(card => {
        card.addEventListener('click', function () {
          if (isBackNavigation) {
            isBackNavigation = false;
            return;
          }

          selectedEscort = {
            id: this.dataset.escortId,
            name: this.dataset.name,
            price: this.dataset.price,
            package: this.dataset.package
          };

          fetch('{{ route("check-auth") }}', {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            }
          })
            .then(response => response.json())
            .then(data => {
              if (!data.isAuthenticated) {
                $('#loginModal').modal('show');
              } else {
                document.getElementById('billing-escort-name').textContent = selectedEscort.name;
                document.getElementById('billing-price').textContent = selectedEscort.price;
                document.getElementById('billing-escort-id').value = selectedEscort.id;
                $('#billingModal').modal('show');
              }
            })
            .catch(error => console.error('Error checking auth:', error));
        });
      });

      // Handle login form submit with AJAX
      const loginForm = document.getElementById('login-form');
      if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
          e.preventDefault();
          const formData = new FormData(this);

          fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            }
          })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                $('#loginModal').modal('hide');
                window.location.reload();
              } else {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger mt-3';
                errorDiv.textContent = data.error || 'Login failed. Please check your credentials.';
                loginForm.prepend(errorDiv);
                setTimeout(() => errorDiv.remove(), 5000);
              }
            })
            .catch(error => {
              console.error('Login error:', error);
              const errorDiv = document.createElement('div');
              errorDiv.className = 'alert alert-danger mt-3';
              errorDiv.textContent = 'An error occurred. Please try again.';
              loginForm.prepend(errorDiv);
              setTimeout(() => errorDiv.remove(), 5000);
            });
        });
      }

      // Handle billing form submit
      const billingForm = document.getElementById('billing-form');
      if (billingForm) {
        billingForm.addEventListener('submit', function (e) {
          e.preventDefault();
          const formData = new FormData(this);
          fetch('{{ route("billing") }}', {
            method: 'POST',
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            }
          })
            .then(async (response) => {
              const isJson = response.headers.get('content-type')?.includes('application/json');
              const data = isJson ? await response.json() : null;
              if (response.ok && data && data.success && data.url) {
                window.location.href = data.url; // redirect to BTCPay payment page
                return;
              }
              if (response.redirected) {
                window.location.href = response.url;
                return;
              }
              const msg = data?.message || 'Invoice creation failed';
              alert(msg);
            })
            .catch(error => alert('Error: ' + error));
        });
      }

      // Load more escorts
      const loadMoreBtn = document.getElementById('load-more-btn');
      if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function () {
          const currentPage = parseInt(this.getAttribute('data-page'));
          const loadText = this.querySelector('.load-text');
          const loadingText = this.querySelector('.loading-text');

          loadText.classList.add('d-none');
          loadingText.classList.remove('d-none');
          this.disabled = true;

          fetch(`/load-more-escorts?page=${currentPage}`, {
            method: 'GET',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            }
          })
            .then(response => response.json())
            .then(data => {
              if (data.success && data.html) {
                const container = document.getElementById('escorts-container');
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;

                const newCards = tempDiv.querySelectorAll('.escort-card-item');
                newCards.forEach((card, index) => {
                  card.style.opacity = '0';
                  card.style.transform = 'translateY(20px)';
                  container.appendChild(card);
                  setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                  }, index * 100);

                  const newCardElement = card.querySelector('.escort-card');
                  if (newCardElement) {
                    newCardElement.addEventListener('click', function () {
                      if (isBackNavigation) {
                        isBackNavigation = false;
                        return;
                      }

                      selectedEscort = {
                        id: this.dataset.escortId,
                        name: this.dataset.name,
                        price: this.dataset.price,
                        package: this.dataset.package
                      };
                      fetch('{{ route("check-auth") }}', {
                        headers: {
                          'X-Requested-With': 'XMLHttpRequest',
                          'Accept': 'application/json'
                        }
                      })
                        .then(response => response.json())
                        .then(data => {
                          if (!data.isAuthenticated) {
                            $('#loginModal').modal('show');
                          } else {
                            document.getElementById('billing-escort-name').textContent = selectedEscort.name;
                            document.getElementById('billing-price').textContent = selectedEscort.price;
                            document.getElementById('billing-escort-id').value = selectedEscort.id;
                            $('#billingModal').modal('show');
                          }
                        })
                        .catch(error => console.error('Error checking auth:', error));
                    });
                  }
                });

                if (data.hasMorePages) {
                  this.setAttribute('data-page', currentPage + 1);
                  loadText.classList.remove('d-none');
                  loadingText.classList.add('d-none');
                  this.disabled = false;
                } else {
                  this.style.display = 'none';
                  const completionMsg = document.createElement('div');
                  completionMsg.className = 'text-center mt-3';
                  completionMsg.innerHTML = '<p class="text-muted">All escorts loaded</p>';
                  container.parentNode.appendChild(completionMsg);
                }
              } else {
                loadText.textContent = 'Error loading escorts';
                loadText.classList.remove('d-none');
                loadingText.classList.add('d-none');
                this.disabled = false;
              }
            })
            .catch(error => {
              console.error('Error:', error);
              loadText.textContent = 'Error loading escorts';
              loadText.classList.remove('d-none');
              loadingText.classList.add('d-none');
              this.disabled = false;
            });
        });
      }
    });

  </script>
@endsection