@extends('escort.layout')
@section('title', 'Escort Verification')
@section('content')

<div class="container bg-white p-lg-5 p-4 escort-dashboard">
	<h5>Verification</h5>
	<hr>

  <div class="row">
  	<div class="col-sm-12 col-lg-12 col-md-12">
  		<p class="mb-3">Your current verification status:</p>

  		 <div class="row mb-2 ">
  		 	<div class="col-sm-12 col-lg-2 col-md-2">
  		 		<p class="small font-weight-bold">Id Verified</p>
  		 	</div>
  		 	<div class="col-sm-12 col-lg-1 col-md-1">
  		 		<i class="fa-solid fa-xmark text-danger"></i>
  		 		<i class="fa-solid fa-check text-success d-none"></i>
  		 	</div>
  		 	<div class="col-sm-12 col-lg-9 col-md-9"></div>
  		 </div>

  		 <div class="row mb-2">
  		 	<div class="col-sm-12 col-lg-2 col-md-2">
  		 	<p class="small font-weight-bold">Photo Verified</p>
  		 </div>
  		 	<div class="col-sm-12 col-lg-1 col-md-1">
  		 		<i class="fa-solid fa-xmark text-danger"></i>
  		 		<i class="fa-solid fa-check text-success d-none"></i>
  		 	</div>
  		 	<div class="col-sm-12 col-lg-9 col-md-9"></div>
  		 </div>

  		 <div class="row mb-2">
  		 	<div class="col-sm-12 col-lg-2 col-md-2">
  		 	<p class="small font-weight-bold">Email Verified</p>
  		 </div>
  		 	<div class="col-sm-12 col-lg-1 col-md-1">
  		 		<i class="fa-solid fa-xmark text-danger d-none"></i>
  		 		<i class="fa-solid fa-check text-success"></i>
  		 	</div>
  		 	<div class="col-sm-12 col-lg-9 col-md-9">
  		 		<p class="small">vikassingh01.gms+1@gmail.com</p>
  		 	</div>
  		 </div>

  		 <div class="row mb-2">
  		 	<div class="col-sm-12 col-lg-2 col-md-2">
  		 	<p class="small font-weight-bold">Phone Verified</p>
  		 </div>
  		 	<div class="col-sm-12 col-lg-1 col-md-1">
  		 		<i class="fa-solid fa-xmark text-danger"></i>
  		 		<i class="fa-solid fa-check text-success d-none"></i>
  		 	</div>
  		 	<div class="col-sm-12 col-lg-9 col-md-9">
  		 		<p class="small">+61-9999999999</p>
  		 	</div>
  		 </div>

  	</div>
  </div>

 
  <h5 class="mt-5">Phone Verification</h5>
  <p class="small">Please enter your country code and phone number to verify your contact number.</p>
  <hr>

	<form action="" class="">
		<div class="row">
			<div class="col-sm-12 col-lg-4 col-md-4">
				<div class="form-group">
					<label for="" class="form-label">Country Code</label>
					<select class="form-select form-control" aria-label="Default select example">
              <option value="1">+61 Australia</option>
              <option value="2">+91 India</option>
              <option value="3">+34 Spain</option>
          </select>
				</div>
			</div>
			<div class="col-sm-12 col-lg-4 col-md-4">
				<div class="form-group">
					<label for="" class="form-label">Phone Number</label>
					<input type="text" class="form-control" placeholder="Enter Phone Number">
				</div>
			</div>
    </div>

     <div class="row">
     	<div class="col-sm-12 col-lg-4 col-md-4">
     		<button class="btn btn-primary btn-sm px-3">Request Verification</button>
     	</div>
     </div>

	</form>

	<h5 class="mt-5">Verify Your Identity</h5>
  <p class="small">We need to verify your ID before you can become an Escort.</p>
  <hr>

	<form action="" class="">
		<div class="row">
			<div class="col-sm-6 col-lg-2 col-md-2">
				<div class="form-group">
					<label for="" class="form-label">Photo Verification</label>
					<div class="upload-photo">
						 <div class="">
						 	<i class="fa-solid fa-camera upload-photo-icon"></i>
						 	<input type="file" class="form-control d-none upload-photo-input">
						 </div>
					</div>
					<button class="btn btn-primary mt-3 btn-sm px-3 btn-block">Send</button>
				</div>
			</div>
			<div class="col-sm-6 col-lg-2 col-md-2">
				<div class="form-group">
					<label for="" class="form-label">ID Verification</label>
					<div class="upload-id">
						 <div class="">
						 	<i class="fa-solid fa-camera upload-icon"></i>
						 	<input type="file" class="form-control d-none upload-input">
						 </div>
					</div>
					<button class="btn btn-primary mt-3 btn-sm px-3 btn-block">Send</button>
				</div>
			</div>
    </div>


	</form>



</div>

@endsection