<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Password Reset</h2>

		<div>
			To reset your password, please complete this form: {{ URL::to('user/reset', array($token)) }}.
		</div>
	</body>
</html>