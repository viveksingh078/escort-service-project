@extends('layouts.app')

@section('content')

  <link rel="stylesheet" href="{{ asset('css/user-register-style.css') }}">

    <div class="container-fluid p-0 m-0 register-page">
      <div class="container py-5 register-page-inner">
        <div class="row">
          <div class="col-sm-12 col-lg-6 col-md-6 mx-auto">
            <div class="shadow-sm p-4 register-page-inner-div bg-white">
              <div class="text-center">
                <h2>Register</h2>
                <p class="text-muted">Choose what kind of account you would like to create</p>
              </div>
              <div class="row mt-4">

                <div class="col-sm-12 col-lg-6 col-md-6 mb-4">

                  <div class="card border-0 regiter-card">
                    <div class="card-body text-center bg-white">
                      <p class="small text-muted m-0">Click here to create an escort account.</p>
                      <div class="regiter-card-img-box my-2">
                        <a href="{{ route('escort.register') }}">
                          <img class="regiter-card-img" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQALSSDXfcjHdNrLjtsBICi79DCmXbEqzoKtg&s" alt="" >
                        </a>
                      </div>
                      <a href="{{ route('escort.register') }}"><h5 class=" text-dark fw-bold">I'm an ESCORT</h5></a>
                    </div>
                  </div>

                </div>
                <div class="col-sm-12 col-lg-6 col-md-6 mb-4">

                  <div class="card border-0 regiter-card">
                    <div class="card-body text-center bg-white">
                      <p class="small text-muted m-0">Click here to create an account to see escort content.</p>
                      <div class="regiter-card-img-box my-2">
                        <a href="{{ route('fan.register') }}">
                          <img class="regiter-card-img" src="https://images.unsplash.com/photo-1584999734482-0361aecad844?q=80&w=580&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="" >
                        </a>
                      </div>
                      <a href="{{ route('fan.register') }}"><h5 class=" text-dark fw-bold">I'm a FAN</h5></a>
                    </div>
                  </div>
                  
                </div>
              </div>
              <div class="row">
                <div class="text-center">
                  <h5 class="fw-bold pb-3  bottom-card-title m-0">What other Escorts say about EscortFans</h5>
                </div>
                <div class="row">
                  <div class="col-3">
                    <div class="bottom-img-box d-flex justify-content-end">
                      <img class="bottom-img" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQALSSDXfcjHdNrLjtsBICi79DCmXbEqzoKtg&s" alt="" >
                    </div>
                    
                  </div>
                  <div class="col-9">
                    <h5 class="bottom-card-subtitle">Davina Divine UK</h5>
                    <em>"EscortFans is a great platform to interact
                    with your
                    fans. It is superior in the fact that you wouldn’t be banned for
                    being an
                    Escort. So much freedom than other sites!"</em>
                  </div>
                </div>
                
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>

@endsection