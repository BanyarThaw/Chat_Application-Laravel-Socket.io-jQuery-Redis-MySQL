function send() {
    sendMessage($('.chat-input').val());
    $(".chat-input").val("");
}

$('.send-button').click(function(e) {
    send();
});

$(".chat-input").keypress(function(e) {
    if(e.which === 13 && !e.shiftKey) {
        send();
        return false;
    }
}); 

function sendMessage(message) {
    let route = '/send-message';let type = 1;
    let formData = new FormData();
    formData.append('message',message);
    formData.append('_token',$("meta[name='csrf-token']").attr('content'));
    if($("#conversation_type").val() == 'message_group_id') {
        formData.append('message_group_id',$("#partner_id").val());
        route = '/send-group-message';type = 2;
    } else {
        formData.append('receiver_id',$("#partner_id").val());
    }

    html_design('',$("#sender_name").val(),getCurrentDateTime(),getCurrentTime(),message,type,$("#partner_id").val());

    $.ajax({
        url: route,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'JSON',
        success: function (response) {
            if(response.success) {
                console.log(response.data);
            }
        },
        error: function ( error ) {
            console.log('the page was NOT loaded', error); // show error message
        },
    });
}

function html_design(image,name,date,time,content,type,id) {
    let userInfo = '<div class="col-md-12 user-info">\n' +
        '<div class="chat-image">\n' + image +
        '</div>\n' +
        '\n' +
        '<div class="chat-name font-weight-bold">\n' +
        name +
        '<span class="small time text-gray-500" title="'+date+'">\n' +
        time+'</span>\n' +
        '</div>\n' +
        '</div>\n';

    let messageContent = '<div class="col-md-12 message-content">\n' +
        '<div class="message-text">\n' + content +
        '</div>\n' +
        '</div>';

    let newMessage = '<div class="row message align-items-center mb-2">'
        +userInfo + messageContent +
        '</div>';

    if(type == 2) {
        id = 'gp-'+id;
    }

    $("#messageWrapper-"+id).append(newMessage);
    $(".message-listing").scrollTop($(".message-listing")[0].scrollHeight);
}

$("#selectMember").select2({
    dropdownParent: $('#addGroupModal')
});

$(".message-listing").scrollTop($(".message-listing")[0].scrollHeight);