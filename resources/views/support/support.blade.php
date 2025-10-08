@extends('layouts.app')
@section('title', 'Support')
@section('content')
  <div class="container py-5">
    @if(session('status'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    <div class="row mb-4">
      <div class="col-md-8">
        <h1 class="display-6">Support Center</h1>
        <p class="lead">Welcome to the Ruby Sirens Support Center. Find FAQs, guides, safety tips, and submit a support
          ticket below.</p>
      </div>
      <div class="col-md-4 text-md-end align-self-center">
<<<<<<< HEAD
        <a href="" class="btn btn-outline-primary mb-2">View Ticket</a>
        <a href="mailto:support@rubysirens.com" class="btn btn-outline-primary mb-2">Email Support</a>
        <a href="{{ route('support.ticket.create') }}" class="btn btn-outline-primary mb-2">Submit Ticket</a>

=======
        <a href="mailto:support@rubysirens.com" class="btn btn-outline-primary mb-2">Email Support</a>
        <a href="{{ route('support.ticket.create') }}" class="btn btn-primary mb-2">Submit Ticket</a>
>>>>>>> 23c30d7 (Escort project)
      </div>
    </div>
    <div class="row gy-4">
      {{-- Left column: FAQs & Accordions --}}
      <div class="col-lg-7">
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title">Quick search FAQs</h5>
            <form id="faq-search" class="mb-3" onsubmit="return false;">
              <div class="input-group">
                <input id="faqQuery" type="search" class="form-control" placeholder="Search help articles or FAQs...">
                <button id="faqSearchBtn" class="btn btn-outline-secondary" type="button">Search</button>
              </div>
            </form>

            <div id="faq-results" class="list-group">
              @foreach($faqs->shuffle()->take(3) as $faq)
                <div class="list-group-item">
                  <strong>{{ $faq->question }}</strong>
                  <div class="small text-muted">{!! $faq->answer !!}</div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
        {{-- Accordion --}}
        <div class="accordion" id="supportAccordion">

          <div class="accordion" id="supportAccordion">
            @foreach($faqs as $index => $faq)
              <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $index }}">
                  <button class="accordion-button shadow-none {{ $index !== 0 ? 'collapsed' : '' }}" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}"
                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                    {{ $faq->question }}
                  </button>
                </h2>
                <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                  aria-labelledby="heading{{ $index }}" data-bs-parent="#supportAccordion">
                  <div class="accordion-body">
                    {!! $faq->answer !!}
                  </div>
                </div>
              </div>
            @endforeach
          </div>

        </div>
      </div>
      {{-- Right column: Contact / Ticket form --}}
      <div class="col-lg-5">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Submit a Support Ticket</h5>
            <p class="text-muted small">Our team typically responds within 24–48 hours.</p>
            <form method="POST" action="{{ route('support.submit') }}" enctype="multipart/form-data" novalidate>
              @csrf
              <div class="mb-3">
                <label for="name" class="form-label">Your name</label>
                <input id="name" name="name" type="text" class="form-control"
                  value="{{ old('name', auth()->user()->name ?? '') }}" required>
                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" class="form-control"
                  value="{{ old('email', auth()->user()->email ?? '') }}" required>
                @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
              </div>
              <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category" class="form-select" required>
                  <option value="">Choose a category</option>
                  <option value="account" {{ old('category') == 'account' ? 'selected' : '' }}>Account</option>
                  <option value="listing" {{ old('category') == 'listing' ? 'selected' : '' }}>Listing</option>
                  <option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>Billing</option>
                  <option value="safety" {{ old('category') == 'safety' ? 'selected' : '' }}>Safety</option>
                  <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                  <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('category') <div class="text-danger small">{{ $message }}</div> @enderror
              </div>
              <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea id="message" name="message" class="form-control" rows="5"
                  required>{{ old('message') }}</textarea>
                @error('message') <div class="text-danger small">{{ $message }}</div> @enderror
              </div>
              <div class="mb-3">
                <label for="attachment" class="form-label">Attachment (optional)</label>
                <input id="attachment" name="attachment" type="file" class="form-control"
                  accept=".png,.jpg,.jpeg,.pdf,.txt">
                <div class="form-text">Allowed: png, jpg, jpeg, pdf, txt — max 8MB</div>
                @error('attachment') <div class="text-danger small">{{ $message }}</div> @enderror
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Send Ticket</button>
                <a href="mailto:support@rubysirens.com" class="btn btn-outline-secondary">Email Support</a>
              </div>
            </form>
            <hr class="my-3">
            <div class="small text-muted">
              <strong>Phone:</strong> +1 (555) 555-5555 (if applicable)<br>
              <strong>Hours:</strong> Mon–Fri, 09:00–18:00 (UTC)<br>
              <strong>Live chat:</strong> Available on site (if enabled)
            </div>
          </div>
        </div>
        <div class="mt-3 text-center">
          <small class="text-muted">If your request is urgent, include <strong>URGENT</strong> in the subject and call our
            hotline.</small>
        </div>
      </div>
    </div>
  </div>
  {{-- Optional small script: basic faq-search mock --}}
  @push('scripts')
    <script>
      document.getElementById('faqSearchBtn').addEventListener('click', function () {
        const q = document.getElementById('faqQuery').value.toLowerCase().trim();
        const results = document.getElementById('faq-results');
        if (!q) {
          // show default
          results.querySelectorAll('.list-group-item').forEach(el => el.style.display = '');
          return;
        }
        results.querySelectorAll('.list-group-item').forEach(el => {
          const text = el.innerText.toLowerCase();
          el.style.display = text.includes(q) ? '' : 'none';
        });
      });
    </script>
  @endpush

  <script>
    jQuery(document).ready(function () {
      function searchFaqs() {
        let q = $('#faqQuery').val().trim();

        $.ajax({
          url: "{{ route('faqs.search') }}",
          type: "GET",
          data: { q: q },
          beforeSend: function () {
            $('#faq-results').html('<div class="list-group-item text-muted">Searching...</div>');
          },
          success: function (data) {
            $('#faq-results').empty();

            if (data.length === 0) {
              $('#faq-results').html('<div class="list-group-item text-muted">No results found</div>');
            } else {
              $.each(data, function (i, faq) {
                $('#faq-results').append(`
<<<<<<< HEAD
                                                  <div class="list-group-item">
                                                      <strong>${faq.question}</strong>
                                                      <div class="small text-muted">${faq.answer}</div>
                                                  </div>
                                              `);
=======
                                            <div class="list-group-item">
                                                <strong>${faq.question}</strong>
                                                <div class="small text-muted">${faq.answer}</div>
                                            </div>
                                        `);
>>>>>>> 23c30d7 (Escort project)
              });
            }
          },
          error: function () {
            $('#faq-results').html('<div class="list-group-item text-danger">Error loading FAQs</div>');
          }
        });
      }

      // Trigger on search button click
      $('#faqSearchBtn').on('click', searchFaqs);

      // Trigger on typing (live search)
      $('#faqQuery').on('keyup', searchFaqs);
    })
  </script>
  @push('scripts')
    <script>
      jQuery(document).ready(function () {

      });
    </script>
  @endpush


@endsection