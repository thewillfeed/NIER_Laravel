
<html>
	<script> javascript:document.location='#pointToScrollTo' </script>
	<head>
		<title> Главная </title>
		<meta charset="utf-8">
		<link href="mystyle.css" rel="stylesheet" type="text/css">
	</head>
	
	
	<body>
	<div id="main">
		<div id="fone">
		<p id="usermenu">CURRENT USER:  {{$user->name}} <?php  ?> <a href="{{ route('logout') }}" id="quitButton" class="registerbtn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    Logout
</a>  </p>
		 
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
		<div class="container">
			<div id ="chatcontainer">
			
				
					@foreach($messages as $message)

						<div class="box"> 
						<div class="Name">{{$message->username}} {{$message->created_at->diffForHumans()}} </div>
							<div class="message"> {{$message->text}} 
								</div>
								</div>
					@endforeach
						
				<a name="pointToScrollTo"></a>
			
			</div>
			<div id="messagewindow">
			<form name = "f2" action="/create" method="post">
			<div id="sendcontainer"><textarea  name="Text1" cols="40" rows="5" placeholder="Your message here..." id="chatbox"> </textarea>
			<div id="buttoncontainer">
			{{csrf_field()}}
			<input type="submit" name="button" value="Отправить" id="sendbutton">
			</div>
			</div>
			
			
			</form>	
			</div>
			
			</div>
		</div>
	</div>
	</body>	
</html>