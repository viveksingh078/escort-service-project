@extends('escort.layout')
@section('title', 'Escort Profile Settings')
@section('content')
<div class="container bg-white p-lg-5 p-4 escort-dashboard">
	<h5>General Profile Setting</h5>
	<p class="small">All these details what you add here are going to related to your public
	profile.</p>
	<hr>
	
	<form action="" class="">
		<div class="row">
			<div class="col-sm-12 col-lg-4 col-md-4">
				<div class="form-group">
					<label for="" class="form-label">Profile Picture</label>
					<input type="file" class="form-control">
				</div>
				<div class="form-group">
					<label for="" class="form-label">Display Name</label>
					<input type="text" class="form-control" placeholder="John Wilson">
				</div>
				<div class="form-group">
					<label for="" class="form-label">Profile Link</label>
					<input type="text" class="form-control"
					placeholder="https://www.escortsdirectory.com/john-wilson0">
				</div>
				<div class="form-group">
					<label for="" class="form-label">Age to Display</label>
					<input type="number" class="form-control" placeholder="24">
				</div>
				<div class="form-group">
					<label for="" class="form-label">Location</label>
					<input type="text" class="form-control" placeholder="Virtual">
				</div>
				<label for="" class="form-label">Display EA Links <span
				class="text-danger">(Verification required)</span></label>
				<div class="row">
					<div class="col-sm-12 col-lg-4 col-md-4 mb-2">
						<img class="card-img mx-1" height="100" src="{{ asset('dash/images/dash-img/ireland-logo.svg') }}" class="img-fluid" alt="">
					</div>
					<div class="col-sm-12 col-lg-4 col-md-4 mb-2">
						<img class="card-img mx-1" height="100" src="{{ asset('dash/images/dash-img/ireland-logo.svg') }}" class="img-fluid" alt="">
					</div>
					<div class="col-sm-12 col-lg-4 col-md-4 mb-2">
						<img class="card-img mx-1" height="100" src="{{ asset('dash/images/dash-img/xescorts-logo.svg') }}" class="img-fluid" alt="">
					</div>
				</div>
				<div class="my-4">
					<div class="form-check form-check-flat form-check-custom">
						<label class="form-check-label">
							<input type="checkbox" class="form-check-input">
							Yes, display my escort-ireland profile
						<i class="input-helper"></i></label>
					</div>
					<div class="form-check form-check-flat form-check-custom">
						<label class="form-check-label">
							<input type="checkbox" class="form-check-input">
							Yes, display my escort-scotland profile
						<i class="input-helper"></i></label>
					</div>
					<div class="form-check form-check-flat form-check-custom">
						<label class="form-check-label">
							<input type="checkbox" class="form-check-input">
							Yes, display my xescorts profile
						<i class="input-helper"></i></label>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-lg-8 col-md-8"></div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-lg-12 col-md-12">
				<div class="form-group">
					<label for="" class="form-label">Your Introduction</label>
					<textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
				</div>
				<div class="form-group mb-0">
					<label for="" class="form-label">New Subscriber Welcome Message</label>
					<textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 80px"></textarea>
				</div>
			</div>
		</div>
		<div class="my-4">
			<div class="form-check form-check-flat form-check-custom">
				<label class="form-check-label">
					<input type="checkbox" class="form-check-input">
					Yes, I want my profile to be promoted
				<i class="input-helper"></i></label>
			</div>
			<div class="form-check form-check-flat form-check-custom">
				<label class="form-check-label">
					<input type="checkbox" class="form-check-input">
					Yes, make my titles and descriptions public
				<i class="input-helper"></i></label>
			</div>
			<div class="form-check form-check-flat form-check-custom">
				<label class="form-check-label">
					<input type="checkbox" class="form-check-input">
					I want to receive email notifications
				<i class="input-helper"></i></label>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-lg-4 col-md-4">
			<button class="btn btn-primary">Save Changes</button>
	  	</div>
		</div>
	</form>
</div>
@endsection