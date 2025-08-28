@extends('escort.layout')
@section('title', 'Escort Payouts')
@section('content')


<link rel="stylesheet" href="{{ asset('css/chat-style.css') }}">


<div class="container-fluid p-0 m-0 py-4">
  <div class="container py-3 px-3 bg-white">
    <div class="row">
      <div class="col-sm-12 col-lg-3 col-md-3 px-0 ">

        <input type="hidden" id="chatUserId" >
        <input type="hidden" id="my-peer-id" >
        <input type="hidden" id="remote-id" >

        {{-- Start message left sidebar --}}

        <div class="msg-left-sec">

          <div class="msg-left-header px-2" >
           <div class="d-flex justify-content-between align-items-center">
              <input type="search"id="search" name="search" class="form-control form-control-sm" placeholder="Search or start a new chat">
            <div class="dropdown">
                <button class="btn bg-white px-2 m-0" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu">
                  <button class="dropdown-item" type="button">Add Friends</button>
                  <button class="dropdown-item" type="button">Block User</button>
                  <button class="dropdown-item" type="button">Report User</button>
                </div>
            </div>
           </div>
           <div class="msg-left-header-filters">
              <button type="button" class="filter-btn active" data-filter="all">All</button>
              <button type="button" class="filter-btn" data-filter="unread">Unread</button>
              <button type="button" class="filter-btn" data-filter="favourites">Favourites</button>
              <button type="button" class="filter-btn" data-filter="friends">Friends</button>
            </div>


          </div>
          <div class="msg-left-body">
            
          </div>
          <div class="msg-left-footer"></div>
          
        </div>
        
      </div>
      <div class="col-sm-12 col-lg-9 col-md-9">

        {{-- Start message right section --}}

        <div class="msg-right-sec">


            <div id="connection-status">Disconnected</div>

          <div class="msg-right-header">
            
          </div>
          <div class="msg-right-body">
              <div class="chat-body" id="chat-body" style="background-image:url({{asset('images/chat-background.png')}});">
                  
              </div>
          </div>
          <div class="msg-right-footer mt-2">

            <emoji-picker style="position:absolute; bottom:60px; left:100px; display:none;" id="emoji-picker"></emoji-picker>



              <div class="chat-footer d-flex align-items-center">
                  <button class="chat-footer-btn chat-footer-btn-emoji" id="emoji-btn">
                    <i class="chat-footer-btn-emoji-icon fa-solid fa-face-smile"></i>
                  </button>
                  <input type="file" id="file-input" style="display:none;">
                  <button class="chat-footer-btn chat-footer-btn-attach" id="file-btn">
                    <i class="chat-footer-btn-attach-icon fa-solid fa-paperclip"></i>
                  </button>
                  <input type="text" id="chat-input" class="form-control w-100 chat-footer-input mr-3" placeholder="Enter Message...">
                  <button class="chat-footer-btn chat-footer-btn-send" id="send-btn">
                    <i class="chat-footer-btn-send-icon fa-solid fa-paper-plane"></i>
                  </button>
              </div>
          </div>
          
        </div>
        
      </div>
    </div>

  </div>
</div>


<!-- Toast Container -->
<div aria-live="polite" aria-atomic="true" style="position: fixed; top: 1rem; right: 1rem; z-index: 9999;">
  <div id="toast" class="toast" data-delay="6000">
    <div class="toast-header">
      <strong class="mr-auto" id="toast-title">Notification</strong>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body" id="toast-body">
      Message goes here.
    </div>
  </div>
</div>


<!-- Fullscreen Video Call Modal -->
<div class="modal fade" id="videoBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content" style="background: black; border:none;">
      <div class="modal-body p-0 position-relative" style="width:100vw; height:100vh; overflow:hidden;">

        <!-- Remote Video (Fullscreen) -->
        <video id="remote-video" autoplay playsinline muted
          style="width:100%; height:100%; object-fit:cover; background:black;"></video>

        <!-- Local Video (Small Overlay) -->
        <video id="local-video" autoplay muted playsinline
          style="position:absolute; bottom:15px; right:15px; width:150px; height:110px; border-radius:8px; object-fit:cover; box-shadow:0 0 8px rgba(0,0,0,0.5); z-index:10;"></video>

        <!-- Call Controls -->
        <div id="call-controls" class="d-flex justify-content-center gap-3 position-absolute w-100" style="bottom:20px; z-index:15;">
          <button id="toggle-mic" class="btn btn-dark rounded-circle shadow" title="Toggle Microphone">
            <i class="fas fa-microphone"></i>
          </button>
          <button id="toggle-camera" class="btn btn-dark rounded-circle shadow" title="Toggle Camera">
            <i class="fas fa-video"></i>
          </button>
          <button id="switch-camera" class="btn btn-dark rounded-circle shadow" title="Switch Camera">
            <i class="fas fa-sync-alt"></i>
          </button>
          <button id="fullscreen-video" class="btn btn-dark rounded-circle shadow" title="Fullscreen">
            <i class="fas fa-expand"></i>
          </button>
          <button id="end-call" class="btn btn-danger rounded-circle shadow" title="End Call">
            <i class="fas fa-phone-slash"></i>
          </button>
        </div>

      </div>
    </div>
  </div>
</div>



<!-- Modal -->
{{-- <div class="modal fade" id="videoBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="videoBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-sm-12 col-lg-6">
            <video id="local-video" autoplay muted playsinline style="width:100%; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.3);"></video>
          </div>
          <div class="col-sm-12 col-lg-6">
            <video id="remote-video" autoplay playsinline style="width:100%; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.3);"></video>
          </div>
        </div>

        <div class="d-flex justify-content-center gap-3 mb-4">
          <button id="toggle-mic" class="btn btn-dark rounded-circle shadow" title="Toggle Microphone">
            <i class="fas fa-microphone"></i>
          </button>
          <button id="toggle-camera" class="btn btn-dark rounded-circle shadow" title="Toggle Camera">
            <i class="fas fa-video"></i>
          </button>
          <button id="switch-camera" class="btn btn-dark rounded-circle shadow" title="Switch Camera">
            <i class="fas fa-sync-alt"></i>
          </button>
          <button id="fullscreen-video" class="btn btn-dark rounded-circle shadow" title="Fullscreen">
            <i class="fas fa-expand"></i>
          </button>
          <button id="end-call" class="btn btn-danger rounded-circle shadow" title="End Call">
            <i class="fas fa-phone-slash"></i>
          </button>
        </div>


      </div>

    </div>
  </div>
</div> --}}


<!-- Incoming Call Popup -->
<div class="modal fade" id="incomingCallModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-none border-0 px-5" style="background: transparent;">

      <div class="incoming-call-box">
        <div class="incoming-call-box-one incoming-box">
          <div id="caller-image"></div>
        </div>
        <div class="incoming-call-box-two incoming-box">
          <div class="text-left">
            <h5 id="caller-name" class="text-lowercase mt-2">Incoming Call...</h5>
            <p id="call-type-text" class="mt-1"></p>
          </div>
        </div>
        <div class="incoming-call-box-three incoming-box">
          <div class="d-flex justify-content-center">
          <button id="accept-call" class="btn accept-call incomming-btn">
            <i class="fas fa-phone"></i>
          </button>
          <button id="reject-call" class="btn reject-call incomming-btn">
            <i class="fas fa-phone-slash"></i>
          </button>
        </div>
        </div>
      </div>

    </div>
  </div>
</div>



@include('escort/message-script');
@endsection