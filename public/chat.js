$('body').animate({"opacity":"1"},1000);
function showcontent(part) { $('#'+part).fadeIn().siblings('div').hide(); }
// OPEN VIDEO DOCK WINDOW //
function open_dock_window(vidid) { $('#video_player').css({"display":"block"}).show(); $('#video_dock').css({"opacity":"1"}); $('#video_dock_window').html('<iframe class="video" id="vidframe" width="80%" height="262px" style="border:none;" src="http://www.youtube.com/embed/'+vidid+'?version=3&autoplay=1&autohide=1&modestbranding=1&loop=0&wmode=opaque&iv_load_policy=3&rel=0&showinfo=1&fs=1" allowfullscreen></iframe>'); }
// CLOSE VIDEO DOCK WINDOW //
function close_dock_window() { $('#video_dock').css({"opacity":"0"}); $('#video_player').css({"display":"none"}); $('#video_dock_window').html(''); }
// LOWER VIDEO DOCK WINDOW //
function lower_dock_window() { $('#video_dock_window').slideToggle('fast'); }
function youtube_request() { showcontent('youtube'); $('#youtube_content').load('youtube.php'); $('#youtube_content').html('<div class="loadings"></div>'); }

var __urlRegex = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
var __imgRegex = /\.(?:jpe?g|gif|png)$/i;
function parseURL($string) {
	var exp = __urlRegex;
	return $string.replace(exp,function(match) {
			__imgRegex.lastIndex=0;
			if(__imgRegex.test(match)) {
				return '<div id="noback2-container"><img src="'+match+'"></div>';
			}
			else{
				return '<a href="'+match+'" target="_blank" style="text-decoration:none;">'+match+'</a>';
			}
		}
	);
}
				
function tab_timeout() {                
	var oldTitle = document.title;
	var msg = "New Post";
	var timeoutId = false;
	var blink = function() {
		document.title = document.title == msg ? oldTitle : msg;
		if(document.hasFocus())
		{
			document.title = oldTitle;
			clearInterval(timeoutId);
		}                       
	};
	if (!timeoutId) {
		timeoutId = setInterval(blink, 1500);
	};
}
$(function(){
   	//make connection
	var socket = io.connect('http://localhost:3000')

	//buttons and inputs
	var message = $("#message")
	var username = $("#username")
	var send_message = $("#send_message")
	var send_username = $("#send_username")
	var welcome_error = $("#welcome_error")
	var users = $("#users")
	var chatroom = $("#chatroom")
	var feedback = $("#feedback")
	
	//Emit message
	send_message.click(function(){
		socket.emit('new_message', {message : message.val()})
	})

	//Listen on user counts increase
	socket.on("increase_users", (data) => {
		$('#total_users').html(data.message);
	});
	
	//Listen on user counts decrease
	socket.on("decrease_users", (data) => {
		$('#total_users').html(data.message);
	});

	//Listen on new_message
	socket.on("new_message", (data) => {
		feedback.html('');
		message.val('');
		var newmessage = data.message;
		if(newmessage === '') { return false; }
		var newmessageuser = '';
		if(data.username !== '')
		{
			newmessageuser = data.username+':';
		}
		newmessage=parseURL(newmessage);
		newmessage = newmessage.replace(/\n/g,"<br />").replace(/\r/g,"<br />").replace(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/g, '<div class="preview-container" style="position:relative;background:rgba(0,0,0,0.1); background-image:url(youtube-grunge.png);background-repeat:no-repeat;background-position:center center;" id="videoframe_$1"><div id="videotitle">Watch Video</div><div class="video-gradient"></div><div id="play_icon" onclick="open_dock_window(\'$1\');"></div><img src="http://img.youtube.com/vi/$1/0.jpg" style=\"position:absolute; top:0; right:0; bottom:0; left:0; max-width:100%; width:100%; max-height:100%;\"></div>');
		chatroom.prepend("<div class='message'><span class='blue'>" + newmessageuser + "</span> " + newmessage + "</div>");
		if(!document.hasFocus()) { document.getElementById('audio_alert').play(); tab_timeout(); }
	})

	//Emit a username
	send_username.click(function(){
		if(username.val() === '') { return false; }
		socket.emit('change_username', {username : username.val()});
		socket.emit('new user', username.val(), function(data) {
			if(data) {
				$('#welcomeGuest').hide();
				$('.chat_container').delay('1000').animate({"opacity":"1"},500);
			} else {
				$('#welcome_error').html('<div class="blink_me red">Username already taken.</div>');
			}
		});
		username.val('');
	});
	
	socket.on('usernames', function(data) {
		var html = '<span class="first_title">Online Members</span><br />';
		for(i=0; i < data.length; i++) {
			html += '<span class="member">'+data[i] + '</span><br />';
		}
		$('#users').html(html);
	});

	//Emit typing
	message.bind("keypress", () => {
		socket.emit('typing')
	})

	//Listen on typing
	socket.on('typing', (data) => {
		feedback.html("<p><i>" + data.username + " is typing a message..." + "</i></p>")
	})
});
