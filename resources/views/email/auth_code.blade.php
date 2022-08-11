<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<h3>Your IP authorization code is <b>{{ $data['auth_code'] }}</b></h3>
<p>
	Pharmacy account has been accessed by <b>{{ $data['ip'] }}</b> IP. <br>
</p>
</body>
</html>