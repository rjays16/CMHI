var socket;
var host = "ws://192.168.15.149:9000";

function wsConnect(name, department, specialty, area){
	try{
		socket = new WebSocket(this.host);

		//upon connection to WebSocket
		socket.onopen = function(){
			var login = '{"ac": "lo", "nm": "' + name +
						 '", "dept": "' + department + 
						 '", "sp": "' + specialty + 
						 '", "ar": "' + area + '"}';
			socket.send(login);
		}
		
		//upon error
		socket.onerror = function(error){
			$j.gritter.add({
				title: 'Connection Error',
				text: 'An error occurred during connection',
				sticky: false,
				time: 500,
			});
		}
		
		//receiving notification from server
		socket.onmessage = function(msg){
			var data = JSON.parse(msg.data);
			var l = window.location;
			var baseUrl= l.protocol + "//" + l.host + "/" + l.pathname.split('/')[1]+'/useralertLog/getLog';
			console.log(baseUrl);
			$j.ajax({
				url: baseUrl,
				dataType: 'JSON',
				type: 'POST',
				data: {id:data.lg},
				success: function(pass){
					$j.gritter.add({
						title: 'From: ' + pass.fr,
						text: pass.msg,
						log_id: pass.lg,
						sticky: true,
						time: '',
					});
				}
			});
		}
		
		//upon closing the connection
		socket.onclose = function(e){
			$j.gritter.add({
				title: 'Connection Closed',
				text: 'The connection is closed',
				sticky: false,
				time: 500,
			});
		}

	} catch(exception){
		//Enter error message
	}
};

// function send_notification(alert_id){
// 	try{
// 		var notif = '{"ac": "al", "id": "'+alert_id+'"}';
// 		socket.send(notif);
// 	} catch(exception){
// 		//Enter error message
// 	}
// };

function send_notification(type, sendto, alert_id){
	try{
		var notif = '{"ac": "ald", "ty": '+type+', "to": "'+sendto+'", "id": "'+alert_id+'"}';
		socket.send(notif);
	} catch(exception){
		//Enter error message
	}
};


function send_custom_notif(type, sendto, message){
	try{
		var notif = '{"ac": "cu", "ty": '+type+', "to": "'+sendto+'", "msg": "'+message+'"}';
		socket.send(notif);
		
	} catch(exception){
		//Enter error message
	}
};

$j(document).ready(function(){

	wsConnect('pass.pid', 'pass.dept', 'pass.sp', 'pass.area');

});