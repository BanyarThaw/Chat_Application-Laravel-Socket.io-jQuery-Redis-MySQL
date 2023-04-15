let user_id = $("#auth_id").val();
let ip_address = '127.0.0.1';
let socket_port = '8005';
let socket = io(ip_address + ':' + socket_port);

socket.on('connect',function() {
    socket.emit('user_connected',user_id);
});

// receive a message
socket.on('updateUserStatus',(data) => {
    let $userStatusIcon = $('.user-status-icon');
    $userStatusIcon.removeClass('text-success');
    $userStatusIcon.attr('title','Away');

    $.each(data, function(key,val) {
        if(val !== null && val !== 0) {
            let $userIcon = $(".user-icon-"+key);
            $userIcon.addClass('text-success');
            $userIcon.attr('title','Online');
        }
    });
});
