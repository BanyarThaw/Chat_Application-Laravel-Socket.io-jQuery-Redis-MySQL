const express = require("express");
const { createServer } = require("http");
const { Server } = require("socket.io");
var users = [];
var groups = [];

const app = express();
const httpServer = createServer(app);
const io = new Server(httpServer, { 
    cors: {
        origin: "*"
    }
});
var Redis = require('ioredis');
const { group } = require("console");
var redis = new Redis();

redis.subscribe('private-channel',function() {
    console.log('subscribed to private channel');
});

redis.subscribe('group-channel',function() {
    console.log('subscribed to group channel');
});

redis.on('message',function(channel, message) {
    message = JSON.parse(message);
    if(channel == 'private-channel') {
        let data = message.data.data;
        let receiver_id = data.receiver_id;
        let event = message.event;

        io.to(`${users[receiver_id]}`).emit(channel + ':' + message.event, data);
    }

    if(channel == 'group-channel') {
        let data = message.data.data;

        if(data.type == 2) {
            let socket_id = getSocketIdOfUserInGroup(data.sender_id, data.group_id);
            let socket = io.sockets.sockets.get(socket_id);
            socket.broadcast.to('group'+data.group_id).emit('groupMessage', data);
        }
    }
});

io.on("connection", (socket) => {
    socket.on("user_connected",(user_id) => {
        users[user_id] = socket.id;
        io.emit('updateUserStatus',users);
    });

    socket.on('disconnect',() => {
        var i =  users.indexOf(socket.id);
        users.splice(i,1,0);
        io.emit('updateUserStatus', users);
    });

    socket.on('joinGroup', function(data) {
        data['socket_id'] = socket.id;
        if(groups[data.group_id]) {
            var userExist = checkIfUserExistInGroup(data.user_id, data.group_id);

            if(!userExist) {
                groups[data.group_id].push(data);
                socket.join(data.room);
            } else {
                var index = groups[data.group_id].map(function(o) {
                    return o.user_id;
                }).indexOf(data.user_id);

                groups[data.group_id].splice(index,1); // delete old user socket data (especially page refresh)
                groups[data.group_id].push(data);
                socket.join(data.room);
            }
        } else {
            groups[data.group_id] = [data];
            socket.join(data.room);
        }
    });
});

httpServer.listen(8005,function() {
    console.log('Listening to port 8005');
});

function  checkIfUserExistInGroup(user_id, group_id) {
    var group = groups[group_id];
    var exist = false;
    if(groups.length > 0) {
        for(var i = 0;i < group.length; i++) {
            if(group[i]['user_id'] == user_id) {
                exist = true;
                break;
            }
        }
    }

    return exist;
}

function getSocketIdOfUserInGroup(user_id, group_id) {
    var group = groups[group_id];
    if(groups.length > 0) {
        for(var i = 0;i < group.length; i++) {
            if(group[i]['user_id'] == user_id) {
                return group[i]['socket_id'];
            }
        }
    }
}