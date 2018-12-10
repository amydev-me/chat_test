// var app = require('express')();
// var http = require('http').Server(app);
// var io = require('socket.io')(http);

// app.get('/', function(req, res){
//   res.sendFile(__dirname + '/index.html');
// });

// io.on('connection', function(socket){
//   	socket.on('public-chat-room',function(message){
//   		console.log('here');
//   		 // io.emit('chat.message', message);
//   	});
//   	 socket.on('disconnect', function(){
//     	 io.emit('chat.message', 'User has disconnected');
//   	});
// });

// http.listen(3000);

// var http = require('http').Server();
// var io = require('socket.io')(http);
// var redis=require('redis');
// var redisClient = redis.createClient();

// redisClient.subscribe('public-chat');

// redisClient.on('message',function(channel,message){

// 	console.log('hay here');
// 	// message=JSON.parse(message);

// });


// http.listen(3000);
// var express =   require('express'),
// http =      require('http'),
// server =    http.createServer(app);

// var app = express();

// const redis =   require('ioredis');
// const io =      require('socket.io');
// const client =  redis.createClient();

// server.listen(3000);
// console.log("Listening.....");

// io.listen(server).on('connection', function(client) {
//     const redisClient = redis.createClient();
//     console.log('here')
//     redisClient.subscribe('test-channel');

//     console.log("Redis server running.....");

//     redisClient.on("message", function(channel, message) {
//         console.log(message);
//         client.emit(channel, message);
//     });

//     client.on('disconnect', function() {
//         redisClient.quit();
//     });
// });
// var handler = require('express')();
// var app = require('http').createServer(handler);
// var io = require('socket.io')(app);
// var Redis = require('ioredis');
// var redis = new Redis();

// app.listen(3000, function() {
//     console.log('Server is running!');
// });

// function handler(req, res) {
//     res.writeHead(200);
//     res.end('');
// }

// io.on('connection', function(socket) {});

// redis.psubscribe('test-channel', function(err, count) {});

// redis.on('message', function(subscribed, channel, message) {
// 	console.log('hiihhii')
// 	console.log(channel + ':' + message);
//     io.emit(channel + ':' + message.event, 'hay');
// });

// var server = require('http').Server();

// var io=require('socket.io')(server);

// var Redis = require('ioredis');
// var redis =new Redis();

// redis.subscribe('test-channel');

// redis.on('message',function(channel,message){
// 	console.log(channel,message);
//  	io.emit(channel + ':' + message.event, 'hay');

// })
// server.listen(3000)

var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis();
redis.psubscribe('*', function(err, count) {});

redis.on('pmessage', function(subscribed, channel, message) {
    console.log(channel + ':' + message);
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});
// redis.subscribe('*', function(err, count) {});

// redis.on('message', function(channel, message) {
//     console.log('Message Recieved: ' + message);
//     message = JSON.parse(message);

//     io.on('connection', function(socket) {
//         console.log("made socket connection", socket.id);
//     });

//     io.emit(channel + ':' + message.event, message.data);
// });

http.listen(3000, function() {
    console.log('Listening on Port 3000');
});