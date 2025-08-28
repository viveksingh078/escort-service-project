<script src="https://unpkg.com/peerjs@1.4.7/dist/peerjs.min.js"></script>
<script>
  jQuery(document).ready(function($){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function showToast(message, title = 'Success', isError = false) {
      $('#toast-title').text(title);
      $('#toast-body').text(message);

      if (isError) {
        jQuery('#toast').removeClass('bg-success').addClass('bg-danger text-white');
      } else {
        jQuery('#toast').removeClass('bg-danger text-white').addClass('bg-success text-white');
      }

      jQuery('#toast').toast('show');
    }


  let currentFilter = 'all';

    // Filter button click
    $('.filter-btn').on('click', function () {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');

        const filter = $(this).data('filter'); // use data-filter instead of text
        currentFilter = filter;

        loadUsers($('#search').val(), currentFilter);
    });

    // Search input
    $('#search').keyup(function(){
        const search_value = $(this).val();
        loadUsers(search_value, currentFilter);
    });

    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}
    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}
    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}

    {{-- Start code for the load users  --}}

    // Load users with optional filter
    function loadUsers(searchValue = "", filter = "all") {
        $.ajax({
            url: '/escort/get-users',
            method: 'GET',
            data: {
                roles: ['escort', 'fan'],
                search: searchValue,
                filter: filter // send filter to backend
            },
            success: function(users) {
                $('.msg-left-body').empty();

                if (!users || users.length === 0) {
                    $('.msg-left-body').append(`
                        <div class="text-center text-muted py-4">
                            <p>No users found.</p>
                        </div>
                    `);
                    return;
                }

                // Sort users
                users.sort((a, b) => {
                    if (b.is_online !== a.is_online) return b.is_online - a.is_online;
                    return b.message_count - a.message_count;
                });

                let firstUserId = null;

                users.forEach(function(user, index) {
                    if (index === 0) {
                        firstUserId = user.id;
                    }

                    let isActive = index === 0 ? 'active' : '';

                    let onlineDot = user.is_online
                        ? '<span class="online-dot"></span>'
                        : '';

                    let photo = user.photo_id && user.photo_id !== ''
                        ? `/storage/${user.photo_id}`
                        : '/images/dummy-user.jpg';

                    let row = `
                        <div class="user-row-li user_li py-1 ${isActive}" data-id="${user.id}" data-key="${user.username}">
                            <div class="user-row-img-wrapper">
                                <div class="user-img">
                                    <img src="${photo}" class="user-photo" alt="${user.username}">
                                    ${onlineDot}
                                </div>
                                <i class="fa-solid fa-circle online-icon"></i>
                            </div>
                            <div class="user-row-content">
                                <p class="username p-0 m-0">${user.username}</p>
                                <p class="description p-0 m-0">${user.description ?? 'last message'}</p>
                            </div>
                            <div class="user-row-meta d-flex flex-column justify-content-center align-items-center">
                                <p class="p-0 m-0 user-row-meta-text">${user.last_message_time ?? '20min'}</p>
                                <p class="p-0 m-0 user-row-meta-msg">${user.last_message_time ?? '10'}</p>
                            </div>
                        </div>`;

                    $('.msg-left-body').append(row);
                });

                if (firstUserId) {
                    loadUserChatHeader(firstUserId);
                    loadChatData(firstUserId);
                }
            },
            error: function(xhr) {
                console.error('Failed to load users:', xhr.responseText);
            }
        });
    }

    // Initial load
    loadUsers();

    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}
    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}
    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}

    {{-- Start code for the load chat header  --}}

    // Handle click on user
    $(document).on('click','.user_li',function(){
        const userId = $(this).data("id");
        $('.user_li').removeClass('active');
        $(this).addClass('active');
        loadUserChatHeader(userId);
        loadChatData(userId);
    });

    function loadUserChatHeader(userId) {
        if (!userId) {
            console.log('Chat - User ID not found.');
            return;
        }

        $('#chatUserId').val(userId);

        $.ajax({
            url: '/escort/get-user/',
            method: 'GET',
            data: {
                user_id: userId
            },
            success: function(user) {
                $('.msg-right-header').empty();

                if (!user || Object.keys(user).length === 0) {
                    $('.msg-right-header').append(`
                        <div class="text-center text-muted py-4">
                            <p>User not found.</p>
                        </div>
                    `);
                    return;
                }

                const onlineDot = user.is_online
                    ? '<span class="online-dot"></span>'
                    : '';

                const photo = user.photo_id && user.photo_id !== ''
                    ? `/storage/${user.photo_id}`
                    : '/images/dummy-user.jpg';

                const addFriendBtn = !user.is_friend ? `
                    <button type="button" class="chat-header-li-btn add-friend " data-id="${user.id}" id="add_friends">
                        <i class="fa-solid fa-user-plus"></i>
                    </button>` : '';

                const removeFriendBtn = user.is_friend ? `
                    <button type="button" class="chat-header-li-btn add-friend " data-id="${user.id}" id="remove_friends">
                        <i class="fa-solid fa-user-minus"></i>
                    </button>` : '';

                const favBtn = !user.is_favorite ? `
                    <button class="dropdown-item" type="button" data-id="${user.id}" id="add_favourites">Add Favourites</button>` : '';

                const unfavBtn = user.is_favorite ? `
                    <button class="dropdown-item" type="button" data-id="${user.id}" id="remove_favourites">Remove Favourites</button>` : '';

                const row = `
                    <div class="chat-header-li" data-id="${user.id}">
                        <div class="chat-header-li-img-wrapper">
                            <div class="chat-header-li-img">
                                <img src="${photo}" class="user-photo" alt="${user.username}">
                                ${onlineDot}
                            </div>
                        </div>
                        <div class="chat-header-li-content">
                            <p class="username p-0 m-0">${user.username}</p>
                        </div>

                        <button type="button" class="chat-header-li-btn audio-call" id="audio-call">
                            <i class="fa-solid fa-phone"></i>
                        </button>
                        <button type="button" class="chat-header-li-btn video-call" id="video-call">
                            <i class="fa-solid fa-video"></i>
                        </button>

                        ${addFriendBtn}
                        ${removeFriendBtn}

                        <div class="dropdown chat-header-li-dropdown">
                            <button class="chat-header-li-btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu">
                                ${favBtn}
                                ${unfavBtn}
                                <button class="dropdown-item" type="button" data-id="${user.id}" id="block_user">Block User</button>
                                <button class="dropdown-item" type="button" data-id="${user.id}" id="report_user">Report User</button>
                            </div>
                        </div>
                    </div>`;

                $('.msg-right-header').append(row);
                connectChat(user.id,user.username);
            },
            error: function(xhr) {
                console.error('Failed to load user:', xhr.responseText);
            }
        });
    }



    function loadChatData(userId) {
        if (!userId) {
            console.log('Chat - User ID not found.');
            return;
        }

        $.ajax({
            url: '/escort/get-user-chat',
            method: 'GET',
            data: {
                user_id: userId
            },
           success: function(response) {
    $('#chat-body').empty();

    if (response.length === 0) {
        $('#chat-body').html('<div class="no-chat">Chat not found</div>');
        return;
    }

    response.forEach(function(message) {
        const isYou = message.sender_id == currentUserId;
        const time = new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        let html = `<div class="message ${isYou ? 'you' : 'other'}">`;

        if (message.message) {
            html += `<div class="text">${message.message}</div>`;
        } else if (message.attachment) {
            const isImage = message.attachment_type.startsWith('image/');

            if (isImage) {
                html += `
                    <div class="attachment">
                        <img src="${message.attachment}" alt="Image" class="chat-image" />
                        <a href="${message.attachment}" download class="download-btn">
                            <i class="fa-solid fa-circle-down"></i>
                        </a>
                    </div>`;
            } else {
                html += `
                    <div class="attachment file">
                        📎 <span class="file-name">${getFileName(message.attachment)}</span><br>
                        <a href="${message.attachment}" download class="download-btn"><i class="fa-solid fa-circle-down"></i></a>
                    </div>`;
            }
        }

        html += `<div class="time">${time}</div>`;
        html += `</div>`;

        $('#chat-body').append(html);
    });

    scrollChat();
},


        });
    }


    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}
    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}
    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}

    {{-- Start code for the add favorites user  --}}

    $(document).on('click','#add_favourites',function(){
       var user_id = $(this).data("id");
        addFavorites(user_id);
        loadUsers();
    })

    function addFavorites(userId){
        if (!userId) {
            console.log('Chat add favourites - User id not found..');
            return;
        }

      $.ajax({
            url: '/escort/add-favourites/',
            method: 'POST',
            data: {
                user_id: userId
            },
            success: function(response) {

                if(response.status){
                   showToast(response.message, 'Success');
                }else{
                    showToast(response.message, 'Error', true);
                }
                
            },
            error: function(xhr) {
                showToast('Failed to add user to favourites.', 'Error', true);
            }
        });


    }

    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}
    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}
    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}

    $(document).on('click','#remove_favourites',function(){
       var user_id = $(this).data("id");
        removeFavourites(user_id);
        loadUsers();
    })

    function removeFavourites(userId){
        if (!userId) {
            console.log('Chat remove favourites - User id not found..');
            return;
        }

      $.ajax({
            url: '/escort/remove-favourites/'+userId,
            method: 'POST',
            data: {
                user_id: userId
            },
            success: function(response) {

                if(response.status){
                   showToast(response.message, 'Success');
                }else{
                    showToast(response.message, 'Error', true);
                }
                
            },
            error: function(xhr) {
                showToast('Failed to remove favourites', 'Error', true);
            }
        });


    }


    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}
    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}
    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}

    {{-- Start code for the add favorites user  --}}

    $(document).on('click','#add_friends',function(){
       var user_id = $(this).data("id");
        addFriends(user_id);
        loadUsers();
    })

    function addFriends(userId){
        if (!userId) {
            console.log('Chat add friends - User id not found..');
            return;
        }

      $.ajax({
            url: '/escort/add-friends/',
            method: 'POST',
            data: {
                user_id: userId
            },
            success: function(response) {

                if(response.status){
                   showToast(response.message, 'Success');
                }else{
                    showToast(response.message, 'Error', true);
                }
                
            },
            error: function(xhr) {
                showToast('Failed to add friends', 'Error', true);
            }
        });


    }

    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}
    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}
    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}


   $(document).on('click','#remove_friends',function(){
       var user_id = $(this).data("id");
        removeFriends(user_id);
        loadUsers();
    })

    function removeFriends(userId){
        if (!userId) {
            console.log('Chat remove friends - User id not found..');
            return;
        }

      $.ajax({
            url: '/escort/remove-friends/'+userId,
            method: 'POST',
            data: {
                user_id: userId
            },
            success: function(response) {

                if(response.status){
                   showToast(response.message, 'Success');
                }else{
                    showToast(response.message, 'Error', true);
                }
                
            },
            error: function(xhr) {
                showToast('Failed to remove friends', 'Error', true);
            }
        });


    }

    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}
    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}
    {{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}
    
    // Add this at the beginning of chat
        const currentUserId = "{{ Auth::guard('escort')->user()->id }}";
        const username = "{{ Auth::guard('escort')->user()->username }}";
        const csrfToken = "{{ csrf_token() }}";

        const myPeerId = `${username}_${currentUserId}`;
        const peer = new Peer(myPeerId, {
          serialization: 'json', // 
        });

        let conn = null;
        let currentCall = null;

        // On peer open
        peer.on('open', id => {
          $('#my-peer-id').val(id);
          console.log('My peer ID is:', id);
        });

        // Incoming data connection
        peer.on('connection', connection => {
          conn = connection;
          setupConnection(conn);
        });

        // On user click to connect
        $(document).on("click", ".user-row-li", function () {
          const remoteUserID = $(this).data("id");
          const remoteUsername = $(this).data("key");
          connectChat(remoteUserID, remoteUsername);
        });

        function connectChat(remoteUserID, remoteUsername) {
          if (!remoteUserID || !remoteUsername) return alert('Invalid remote peer ID');

          const remoteId = `${remoteUsername}_${remoteUserID}`;
          $('#remote-id').val(remoteUserID);
          console.log("Connecting to:", remoteId);

          // Close previous connection if open
          if (conn && conn.open) {
            console.log("Closing previous connection...");
            conn.close();
          }

          // Open new connection with safe serialization
          conn = peer.connect(remoteId, {
            serialization: 'json'
          });

          conn.on('error', err => {
            console.error("Connection error:", err);
            $('#connection-status').text('Error').css('color', 'red');
          });

          setupConnection(conn);
        }

        function setupConnection(connection) {
          connection.on('open', () => {
            $('#connection-status').text('Connected').css('color', 'green');

            connection.on('data', handleData);
            connection.on('close', () => {
              $('#connection-status').text('Disconnected').css('color', 'red');
            });
          });
        }

        function handleData(data) {
          if (!data || typeof data !== 'object') return;

          let html = `<div class="message other">`;

          if (data.type === 'text') {
            html += `<div class="text">${data.text}</div>`;
          } else if (data.type === 'file' && data.file && data.filename) {
            const isImage = data.file.startsWith('data:image/');
            const fileExt = data.filename.split('.').pop().toLowerCase();

            if (isImage) {
              html += `
                <div class="attachment">
                  <img src="${data.file}" alt="Image" class="chat-image" />
                  <a href="${data.file}" download="${data.filename}" class="download-btn">
                    <i class="fas fa-download"></i> 
                  </a>
                </div>`;
            } else {
              const icon = getFileIcon(fileExt);
              html += `
                <div class="attachment file">
                  <i class="${icon}"></i> <span class="file-name">${data.filename}</span><br>
                  <a href="${data.file}" download="${data.filename}" class="download-btn">
                    <i class="fas fa-download"></i> 
                  </a>
                </div>`;
            }
          }

          const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
          html += `<div class="time">${time}</div></div>`;

          $('#chat-body').append(html);
          scrollChat();
        }

        function sendMessage() {
          const text = $('#chat-input').val().trim();
          const remoteUserID = $('#remote-id').val();

          if (typeof text !== 'string' || !text || !conn?.open) return;

          // Send to peer
          conn.send({ type: 'text', text });

          // Show in sender UI
          $('#chat-body').append(`<div class="message you"><div class="text">${text}</div></div>`);
          $('#chat-input').val('');
          scrollChat();

          // Save to DB
          $.post('/messages/store', {
            _token: csrfToken,
            sender_id: currentUserId,
            receiver_id: remoteUserID,
            message: text,
            attachment: null,
            attachment_type: null
          });
        }

        $('#send-btn').on('click', sendMessage);

        $('#chat-input').on('keydown', function (event) {
          if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendMessage();
          }
        });

        $('#file-btn').click(() => $('#file-input').click());

        $('#file-input').change(e => {
          const file = e.target.files[0];
          if (!file || !conn?.open) return;

          const remoteUserID = parseInt($('#remote-id').val(), 10);
          if (!remoteUserID || isNaN(remoteUserID)) return alert("Invalid remote user");

          const reader = new FileReader();
          reader.onload = () => {
            const base64 = reader.result;
            const fileExt = file.name.split('.').pop().toLowerCase();
            const isImage = file.type.startsWith('image/');

            conn.send({
              type: 'file',
              file: base64,
              filename: file.name
            });

            let html = `<div class="message you">`;

            if (isImage) {
              html += `
                <div class="attachment">
                  <img src="${base64}" alt="Image" class="chat-image" />
                  <a href="${base64}" download="${file.name}" class="download-btn">
                    <i class="fas fa-download"></i>
                  </a>
                </div>`;
            } else {
              const icon = getFileIcon(fileExt);
              html += `
                <div class="attachment file">
                  <i class="${icon}"></i> <span class="file-name">${file.name}</span><br>
                  <a href="${base64}" download="${file.name}" class="download-btn">
                    <i class="fas fa-download"></i>
                  </a>
                </div>`;
            }

            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            html += `<div class="time">${time}</div></div>`;

            $('#chat-body').append(html);
            scrollChat();

            $.post('/messages/store', {
              _token: csrfToken,
              sender_id: currentUserId,
              receiver_id: remoteUserID,
              message: null,
              attachment: base64,
              attachment_type: file.type
            });
          };

          reader.readAsDataURL(file);
        });

        //  Emoji Picker Integration
        $('#emoji-btn').click(() => {
          $('#emoji-picker').toggle();
        });

        document.querySelector('emoji-picker')?.addEventListener('emoji-click', e => {
          const emoji = e.detail?.unicode;
          if (emoji) {
            const current = $('#chat-input').val() || '';
            $('#chat-input').val(current + emoji);
            $('#emoji-picker').hide();
          }
        });

        // Dummy icon function (replace with real logic if needed)
        function getFileIcon(ext) {
          switch (ext) {
            case 'pdf': return 'fas fa-file-pdf';
            case 'doc': case 'docx': return 'fas fa-file-word';
            case 'xls': case 'xlsx': return 'fas fa-file-excel';
            default: return 'fas fa-file';
          }
        }

         // Scroll to bottom
        function scrollChat() {
          $('#chat-body').scrollTop($('#chat-body')[0].scrollHeight);
        } 


{{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}
{{-- START CODE FOR THE VIDEO CALL AND AUDIO CALL --}}
{{-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! --}}

        const ringtone = new Audio("{{ asset('audio/ringtone.mp3') }}");
        ringtone.loop = true;

        let micEnabled = true;
        let camEnabled = true;

        let localStream = null;
        let pendingCall = null;
        let callTimerInterval = null;

        let usingFrontCamera = true; // Track which camera is active

        // ========== INCOMING CALL HANDLER ==========
        peer.on('call', call => {
          pendingCall = call;
          ringtone.play();

           const callType = call.metadata?.type === 'audio' ? 'Audio Call' : 'Video Call';

           let remote_user_id = $('#remote-id').val();

            $.ajax({
                url: '/escort/get-user/',
                method: 'GET',
                data: {
                    user_id: remote_user_id
                },
                success: function(user) {

                const photo = user.photo_id && user.photo_id !== ''
                        ? `/storage/${user.photo_id}`
                        : '/images/dummy-user.jpg';

                  //  console.log(user);

                    callerImage = '<img src="'+photo+'" alt="" height="50px" width="50px">'
                    $('#caller-image').html(callerImage);
                    $('#caller-name').text(user.username);

                },
                error: function(xhr) {
                    console.error('Failed to load user:', xhr.responseText);
                }
            });

          $('#call-type-text').text(callType);
          
         
          jQuery('#incomingCallModal').modal('show');
        });

        // ========== AUDIO CALL BUTTON ==========
        $(document).on("click", "#audio-call", async function () {
          if (jQuery('#videoBackdrop').length) jQuery('#videoBackdrop').modal('show');

          const remoteUserID = $('#remote-id').val();
          const remoteUsername = $(".user-row-li[data-id='" + remoteUserID + "']").data("key");
          if (!remoteUserID || !remoteUsername) return alert("No remote user selected");

          const remotePeerId = `${remoteUsername}_${remoteUserID}`;

          try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            localStream = stream;
            $('#local-video')[0].srcObject = stream;

            const call = peer.call(remotePeerId, stream, { metadata: { type: 'audio' } });

            call.on('stream', remoteStream => {
              $('#remote-video')[0].srcObject = remoteStream;
            });

            currentCall = call;
            startCallTimer();
          } catch (err) {
            console.error("Error accessing microphone:", err);
            alert("Microphone access denied or unavailable.");
          }
        });

        // ========== VIDEO CALL BUTTON ==========
        $(document).on("click", "#video-call", async function () {
          if (jQuery('#videoBackdrop').length) jQuery('#videoBackdrop').modal('show');

          const remoteUserID = $('#remote-id').val();
          const remoteUsername = $(".user-row-li[data-id='" + remoteUserID + "']").data("key");
          if (!remoteUserID || !remoteUsername) return alert("No remote user selected");

          const remotePeerId = `${remoteUsername}_${remoteUserID}`;

          try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
            localStream = stream;
            $('#local-video')[0].srcObject = stream;

            const call = peer.call(remotePeerId, stream, { metadata: { type: 'video' } });

            call.on('stream', remoteStream => {
              $('#remote-video')[0].srcObject = remoteStream;
            });

            currentCall = call;
            startCallTimer();
          } catch (err) {
            console.error("Error accessing camera/microphone:", err);
            alert("Camera/microphone access denied or unavailable.");
          }
        });

        // ========== ACCEPT INCOMING CALL ==========
        $('#accept-call').click(() => {
          if (!pendingCall) return;

          const callType = pendingCall.metadata?.type || 'video';
          const constraints = callType === 'audio' ? { audio: true } : { audio: true, video: true };

          navigator.mediaDevices.getUserMedia(constraints).then(stream => {
            localStream = stream;
            $('#local-video')[0].srcObject = stream;

           jQuery('#videoBackdrop').modal('show');
           jQuery('#incomingCallModal').modal('hide');
            ringtone.pause();
            ringtone.currentTime = 0;

            pendingCall.answer(stream);
            pendingCall.on('stream', remoteStream => {
              $('#remote-video')[0].srcObject = remoteStream;
            });

            currentCall = pendingCall;
            pendingCall = null;

            startCallTimer();
          }).catch(err => {
            console.error("Error getting media:", err);
            alert("Failed to access microphone/camera.");
          });
        });

        // ========== REJECT INCOMING CALL ==========
        $('#reject-call').click(() => {
          if (pendingCall) {
            pendingCall.close();
            pendingCall = null;
          }

          jQuery('#incomingCallModal').modal('hide');
          ringtone.pause();
          ringtone.currentTime = 0;
        });

        // ========== TOGGLE MIC ==========
        $('#toggle-mic').click(() => {
          if (!localStream) return;
          micEnabled = !micEnabled;
          localStream.getAudioTracks().forEach(track => track.enabled = micEnabled);

          $('#toggle-mic i')
            .toggleClass('fa-microphone', micEnabled)
            .toggleClass('fa-microphone-slash', !micEnabled);
        });

        // ========== TOGGLE CAMERA ==========
        $('#toggle-camera').click(() => {
          if (!localStream) return;
          camEnabled = !camEnabled;
          localStream.getVideoTracks().forEach(track => track.enabled = camEnabled);

          $('#toggle-camera i')
            .toggleClass('fa-video', camEnabled)
            .toggleClass('fa-video-slash', !camEnabled);
        });

        // ========== END CALL ==========
        $('#end-call').click(() => {
          if (currentCall) {
            currentCall.close();
            currentCall = null;
          }

          if (localStream) {
            localStream.getTracks().forEach(track => track.stop());
            localStream = null;
            $('#local-video')[0].srcObject = null;
          }

          $('#remote-video')[0].srcObject = null;
          jQuery('#videoBackdrop').modal('hide');

          stopCallTimer();
        });

        // ========== CLEANUP ON PAGE CLOSE ==========
        window.addEventListener("beforeunload", () => {
          if (conn) conn.close();
          if (currentCall) currentCall.close();
          peer.destroy();
        });

        // ========== CALL TIMER ==========
        function startCallTimer() {
          let seconds = 0;
          callTimerInterval = setInterval(() => {
            seconds++;
            const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
            const secs = String(seconds % 60).padStart(2, '0');
            $('#call-timer').text(`Call Time: ${mins}:${secs}`);
          }, 1000);
        }


// ======== SWITCH CAMERA ========
$('#switch-camera').click(async () => {
  if (!localStream) return;

  try {
    localStream.getVideoTracks().forEach(track => track.stop());
    usingFrontCamera = !usingFrontCamera;

    const newStream = await navigator.mediaDevices.getUserMedia({
      video: { facingMode: usingFrontCamera ? 'user' : 'environment' },
      audio: true
    });

    const newVideoTrack = newStream.getVideoTracks()[0];
    const sender = currentCall.peerConnection.getSenders().find(s => s.track.kind === 'video');
    if (sender) sender.replaceTrack(newVideoTrack);

    localStream.removeTrack(localStream.getVideoTracks()[0]);
    localStream.addTrack(newVideoTrack);
    $('#local-video')[0].srcObject = localStream;

  } catch (err) {
    console.error("Error switching camera:", err);
    alert("Unable to switch camera.");
  }
});

// ======== FULLSCREEN ========
$('#fullscreen-video').click(() => {
  const remoteVid = document.getElementById('remote-video');
  if (!document.fullscreenElement) {
    remoteVid.requestFullscreen().then(() => {
      $('#call-controls').show();
    }).catch(err => console.error("Fullscreen error:", err));
  } else {
    document.exitFullscreen();
  }
});

// Hide default video controls
$('#remote-video, #local-video').attr('controls', false);

// ======== KEEP CONTROLS VISIBLE IN FULLSCREEN ========
document.addEventListener('fullscreenchange', () => {
  if (document.fullscreenElement) {
    $('#call-controls').fadeIn();
  } else {
    $('#call-controls').fadeIn(); // Keep visible even after fullscreen exit
  }
});


        function stopCallTimer() {
          clearInterval(callTimerInterval);
          $('#call-timer').text('Call Time: 00:00');
        }






  });
</script>