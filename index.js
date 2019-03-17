const express = require('express')
const app = express()
var connectCounter = 0;
//set the template engine ejs
app.set('view engine', 'ejs')

//middlewares
app.use(express.static('public'))


//routes
app.get('/', (req, res) => {
	res.render('index');
})

//Listen on port 3000
//server = app.listen(3000)

//socket.io instantiation
const io = require("socket.io")(server)
nicknames = [];

//listen on every connection
io.on('connection', (socket) => {
	console.log('New user connected')
	io.sockets.emit('increase_users', { message : '<div style="text-align:center; color:yellow;">' + nicknames.length + ' users online.</div>' });
	socket.on('new user', function(data, callback) {
		if(nicknames.indexOf(data) != -1) {
			io.sockets.emit('increase_users', { message : '<div style="text-align:center; color:yellow;">' + nicknames.length + ' users online.</div>' });
			callback(false);
		} else {
			callback(true);
			socket.nicknames = data;
			nicknames.push(socket.nicknames);
			updateNicknames();
			console.log(socket.username + ' joined chat');
			io.sockets.emit('increase_users', { message : '<div style="text-align:center; color:yellow;">' + nicknames.length + ' users online.</div>' });
			io.sockets.emit('new_message', {message : '<div class="site_message">'+socket.username+' has joined.</div>', username : ''});
		}
	});
	
	function updateNicknames() { io.sockets.emit('usernames', nicknames); }
	//default username
	socket.username = "Anonymous"

    //listen on change_username
    socket.on('change_username', (data) => {
        socket.username = data.username;
		connectCounter++;
		if(connectCounter == 1)
		{
			io.sockets.emit('increase_users', { message : '<div style="text-align:center; color:yellow;">' + connectCounter + ' user online.</div>' });
		} else {
			io.sockets.emit('increase_users', { message : '<div style="text-align:center; color:yellow;">' + connectCounter + ' users online.</div>' });
		}
    })

    //listen on new_message
    socket.on('new_message', (data) => {
        //broadcast the new message
        io.sockets.emit('new_message', {message : data.message, username : socket.username});
    })

    //listen on typing
    socket.on('typing', (data) => {
    	socket.broadcast.emit('typing', {username : socket.username})
    })
	
	socket.on('disconnect', function(data) {
		if(!socket.nicknames) return;
		connectCounter--;
		nicknames.splice(nicknames.indexOf(socket.nicknames), 1);
		updateNicknames();
		console.log(socket.username +' disconnected');
		io.sockets.emit('new_message', {message : '<div class="site_message">'+socket.username+' has left the room.</div>', username : ''});
		if(connectCounter == 1)
		{
			io.sockets.emit('increase_users', { message : '<div style="text-align:center; color:yellow;">' + connectCounter + ' user online.</div>' });
		} else {
			io.sockets.emit('decrease_users', { message : '<div style="text-align:center; color:yellow;">' + connectCounter + ' users online.</div>' });
		}
	});
})
