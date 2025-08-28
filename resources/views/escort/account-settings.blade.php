@extends('escort.layout')
@section('title', 'Escort Account Settings')
@section('content')

<div class="container bg-white p-lg-5 p-4 escort-dashboard">
	<h5>Account Setting</h5>
	<hr>
	
	<form action="" class="">
		<div class="row">
			<div class="col-sm-12 col-lg-4 col-md-4">
				<div class="form-group">
					<label for="" class="form-label">Email Address</label>
					<input type="text" class="form-control" placeholder="Enter Email">
				</div>
				<div class="form-group">
					<label for="" class="form-label">New Password</label>
					<input type="text" class="form-control" placeholder="Enter Password">
				</div>
				<div class="form-group">
					<label for="" class="form-label">Confirm Password</label>
					<input type="text" class="form-control" placeholder="Enter Confirm Password">
				</div>
				
			</div>
			<div class="col-sm-12 col-lg-8 col-md-8"></div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-lg-4 col-md-4">
			  <button class="btn btn-primary">Save Changes</button>
		  </div>
		</div>
	</form>
</div>

@endsection