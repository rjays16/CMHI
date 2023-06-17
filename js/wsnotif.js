var socket;
var host = "ws://"+window.location.hostname;

$j = jQuery.noConflict();
function wsConnect(){
	try{
		socket = new WebSocket(this.host);

		// //upon connection to WebSocket
		// socket.onopen = function(){
		// 	var login = '{"ac": "lo", "nm": "' + name +
		// 				 '", "dept": "' + department + 
		// 				 '", "sp": "' + specialty + 
		// 				 '", "ar": "' + area + '"}';
		// 	socket.send(login);
		// }
		
		//upon error
		// socket.onerror = function(error){
		// 	$j.gritter.add({
		// 		title: 'Connection Error',
		// 		text: 'An error occurred during connection',
		// 		sticky: false,
		// 		time: 500,
		// 	});
		// }
		//receiving notification from server
		socket.onmessage = function(msg){

			var data = JSON.parse(msg.data);
			var msg = data.message;
			var permission = data.permission;

			try{
				if(data.type!='system' && permission){
					$j.getJSON("../../modules/queuing/check-queuing-permission.php" , {permission:permission}, function( JSON_data ){
						if(JSON_data){
							switch (data.action){
								case "reloadOnQue" :
									refreshPatientOnQue();
									refreshResultOnQue();
								break;
								default:
									if(permission != '_a_1_queing_dashlet_refresh'){
										var type = '';

										if(data.action == 'onqueue'){
											type = 'error';
										}else if(data.action == 'done'){
											type = 'success';
										}else{
											type = 'information';
										}

										var n = noty({text: msg,
										layout: 'bottomRight',
										theme: 'defaultTheme',
										type: type});
									}
								break;
							}//End Switch
						}//End IF
					});//END getJSON
				}//End IF
			}catch(exception){
				//Enter error message
			}//End Switch

		}//End onmessage

		
	} catch(exception){
		//Enter error message
	}
};



function send_custom_notif(permission, message, action){
	try{

	 	var msg = {
		message: message,
		permission: permission,
		action: action 	
		};
		socket.send(JSON.stringify(msg));
		
	} catch(exception){
		alert(exception);
		//Enter error message
	}
};


function refreshDashlet(permission){
	try{
		var msg = {
		action : 'reloadOnQue',
		permission: permission 	
		};
		socket.send(JSON.stringify(msg));
		
	} catch(exception){
		alert(exception);
		//Enter error message
	}
}

$j(document).ready(function(){
	wsConnect();
});