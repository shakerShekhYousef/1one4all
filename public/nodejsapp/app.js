// creating express instance
var express = require("express");
var app = express();
const SocketIOFile = require('socket.io-file');
const moment = require('moment');
var request_lib = require('request');
// const hostname = '127.0.0.1';
// port = 3000;

// creating http instance
var http = require("http").createServer(app);

// creating socket io instance
var io = require("socket.io")(http);
//===================================
// create body parser instance
var bodyParser = require("body-parser");

// enable URL encoded for POST requests
app.use(bodyParser.urlencoded({extended: true}));
//creating axios instance
const axios = require('axios');
//app.use(bodyParser.urlencoded({ extended: true }));
//app.use(express.urlencoded({ extended: true }))

//===========================================================
// Create instance of mysql
var mysql = require("mysql");

// make a connection

// var connection = mysql.createConnection({
//     "host": "127.0.0.1",
//     "user": "root",
//     "password": "",
//     "database": "1one4all",
//     "charset": "utf8mb4"
// });


var connection = mysql.createConnection({
    "host": "localhost",
    "user": "one4all_one4all",
    "password": "one4allP@$$word123",
    "database": "one4all_db0"
});

// connect
connection.connect(function (error) {
    if (error) throw error;
    console.log("Connected!");
});
//===================================
// enable headers required for POST request
app.use(function (request, result, next) {
    result.setHeader("Access-Control-Allow-Origin", "*");
    next();
});
//===================================
// create api to return all messages
app.post("/get_messages", function (request, result,) {
    // get all messages from database
    connection.query("SELECT * FROM chats WHERE (sender_id = '" + request.body.sender_id + "' AND idReceiver = '" + request.body.receiver_id + "') OR (idSender = '" + request.body.receiver_id + "' AND idReceiver = '" + request.body.sender_id + "')", function (error, messages) {
        // response will be in JSON
        result.end(JSON.stringify(messages));
    });
    //read message
    // connection.query("UPDATE messages SET `reading`=0 WHERE (idSender = '" + request.body.idReceiver + "' AND idReceiver = '" + request.body.idSender + "')", function (err, result) {
    //     // response will be in JSON
    //     if (err) throw err;
    //     console.log ('all messages are readed');
    //     console.log(result.affectedRows + " record(s) updated");
    // });
});
//get unread messages
app.post("/getUnReadMessages", function (request, result) {
    connection.query("SELECT * ,COUNT(*) FROM messages WHERE (sender_id = '" + request.body.sender_id + "' AND idReceiver = '" + request.body.receiver_id + "' AND reading = 1 )GROUP BY `reading` ", function (error, messages) {
        result.end(JSON.stringify(messages));
        console.log(messages);
    });
});
//===================================


var users = [];

io.on("connection", function (socket) {
//    console.log(socket);

    // attach incoming listener for new user
    socket.on("user_connected", function (data) {
        // save in array
        users[data.sender_id] = socket.id;
        // socket ID will be used to send message to individual person
        // notify all connected clients
        io.emit("user_connected", data);
        console.log('emit data done . ');
    });
    //===================================
    // listen from client inside IO "connection" event
    socket.on("send_message", function (data) {
        // send event to receiver
        var socketId = users[data.receiver_id];
        var time = moment().format('h:mm a');
        console.log(typeof (time));
        data.time = time;
        data.reading = 1;
        io.to(socketId).emit("new_message", data);
        console.log('data to socketId emitting ', data);
        console.log(data.filename);
        var message = connection.query("INSERT INTO chats (message, sender_id, receiver_id,type) VALUES " +
            "('" + data.message + "','" + data.sender_id + "','" + data.receiver_id + "', '" + data.type + "')", function (error, result) {
            console.log(result);
            io.emit('send_message_' + data.receiver_id, data);
            io.emit('send_message_' + data.sender_id, data);
        });
        var sender_name = '';
        connection.query("SELECT name FROM users WHERE (id = '" + data.sender_id + "')", function (error, messages) {
            sender_name = JSON.stringify(messages[0].name);
            console.log(sender_name);
            const response = axios.post("https://api.1one4all.com/api/send_notification", {
                title: 'New Message from ' + sender_name,
                body: data.message,
                receiver_id: data.receiver_id,
                notification_type: 'New message',
                sender_id: data.sender_id,
            }).then(res => {
                console.log(res);
            })
        });

    });
    //===================================
});
// var uploader = new SocketIOFile(socket, {
//     // uploadDir: {			// multiple directories
//     // 	music: 'data/music',
//     // 	document: 'data/document'
//     // },
//     uploadDir: '/home/alifouad91/lac.alifouad91.com/storage/app/public/files/',
//     // uploadDir: '..\\//..\\//storage\\//app\\//public\\//files',		// simple directory
//     // accepts: ['audio/mpeg', 'audio/mp3','image/gif', 'video/mp4','image/jpeg','image/jpg', 'image/png'],// chrome and some of browsers checking mp3 as 'audio/mp3', not 'audio/mpeg'
//     // accepts: ['*'],// chrome and some of browsers checking mp3 as 'audio/mp3', not 'audio/mpeg'
//     maxFileSize: 111114194304, 						// 4 MB. default is undefined(no limit)
//     chunkSize: 10240,							// default is 10240(1KB)
//     transmissionDelay: 0,						// delay of each transmission, higher value saves more cpu resources, lower upload speed. default is 0(no delay)
//     overwrite: true 							// overwrite file if exists, default is true.
// });
// uploader.on('start', (fileInfo) => {
//     console.log('Start uploading');
//     console.log(fileInfo);
// });
// uploader.on('stream', (fileInfo) => {
//     console.log(`${fileInfo.wrote} / ${fileInfo.size} byte(s)`);
// });
// uploader.on('complete', (fileInfo) => {
//     console.log('Upload Complete.');
//     console.log(fileInfo);
// });
// uploader.on('error', (err) => {
//     console.log('Error!', err);
// });
// uploader.on('abort', (fileInfo) => {
//     console.log('Aborted: ', fileInfo);
// });
//===================================
//===================================
// start the server
http.listen(3500, function () {
    console.log("Server started");

});
//===================================
