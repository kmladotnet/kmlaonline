<html>

<head>
	<style>
		body {
			width: 600px;
			font-family: calibri;
		}

		.error {
			color: #FF0000;
		}

		.chat-connection-ack {
			color: #26af26;
		}

		.chat-message {
			border-bottom-left-radius: 4px;
			border-bottom-right-radius: 4px;
		}

		#btnSend {
			background: #26af26;
			border: #26af26 1px solid;
			border-radius: 4px;
			color: #FFF;
			display: block;
			margin: 15px 0px;
			padding: 10px 50px;
			cursor: pointer;
		}

		#chat-box {
			background: #fff8f8;
			border: 1px solid #ffdddd;
			border-radius: 4px;
			border-bottom-left-radius: 0px;
			border-bottom-right-radius: 0px;
			min-height: 300px;
			padding: 10px;
			overflow: auto;
		}

		.chat-box-html {
			color: #09F;
			margin: 10px 0px;
			font-size: 0.8em;
		}

		.chat-box-message {
			color: #09F;
			padding: 5px 10px;
			background-color: #fff;
			border: 1px solid #ffdddd;
			border-radius: 4px;
			display: inline-block;
		}

		.chat-input {
			border: 1px solid #ffdddd;
			border-top: 0px;
			width: 100%;
			box-sizing: border-box;
			padding: 10px 8px;
			color: #191919;
		}
	</style>
	<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
	<script>
		function showMessage(messageHTML) {
			$('#chat-box').append(messageHTML);
		}

		$(document).ready(function() {
			var websocket = new WebSocket("ws://106.187.34.53:8090/unused/php-socket.php");
			websocket.onopen = function(event) {
				showMessage("<div class='chat-connection-ack'>Connection is established!</div>");
			}
			websocket.onmessage = function(event) {
				var Data = JSON.parse(event.data);
				showMessage("<div class='" + Data.message_type + "'>" + Data.message + "</div>");
				$('#chat-message').val('');
			};

			websocket.onerror = function(event) {
				showMessage("<div class='error'>Problem due to some Error</div>");
			};
			websocket.onclose = function(event) {
				showMessage("<div class='chat-connection-ack'>Connection Closed</div>");
			};

			$('#frmChat').on("submit", function(event) {
				event.preventDefault();
				$('#chat-user').attr("type", "hidden");
				var messageJSON = {
					chat_user: $('#chat-user').val(),
					chat_message: $('#chat-message').val()
				};
				websocket.send(JSON.stringify(messageJSON));
			});
		});
	</script>
</head>

<body>
	<form name="frmChat" id="frmChat">
		<div id="chat-box"></div>
		<input type="text" name="chat-user" id="chat-user" placeholder="Name" class="chat-input" required />
		<input type="text" name="chat-message" id="chat-message" placeholder="Message" class="chat-input chat-message" required />
		<input type="submit" id="btnSend" name="send-chat-message" value="Send">
	</form>
</body>

</html>