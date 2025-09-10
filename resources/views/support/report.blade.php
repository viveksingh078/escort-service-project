@extends('layouts.app')
<!-- huisdhs -->

@section('title', 'Report a User or Listing')

@section('content')
<div class="container py-5">
  <h1>Report a User or Listing</h1>
  <p class="text-muted">If you believe a profile, listing, or message violates our policies, please submit details below. Our team reviews all reports within 24–48 hours.</p>

  <form method="POST" action="{{ route('support.submit') }}">
    @csrf

    <div class="mb-3">
      <label for="reported_item" class="form-label">User ID / Listing ID (if known)</label>
      <input type="text" name="reported_item" id="reported_item" class="form-control" placeholder="e.g. User #1234 or Listing #5678">
    </div>

    <div class="mb-3">
      <label for="reason" class="form-label">Reason for report</label>
      <select name="reason" id="reason" class="form-select" required>
        <option value="">Choose reason</option>
        <option value="fraud">Fraud / Scam</option>
        <option value="fake">Fake profile</option>
        <option value="inappropriate">Inappropriate content</option>
        <option value="harassment">Harassment / Abuse</option>
        <option value="other">Other</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="details" class="form-label">Details</label>
      <textarea name="details" id="details" class="form-control" rows="5" required></textarea>
    </div>

    <button type="submit" class="btn btn-danger">Submit Report</button>
  </form>
</div>
@endsection
