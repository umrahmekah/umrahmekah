<meta charset="utf-8">


<h2>Hello {{ $email_data["name"] }} ,</h2>
<p> Thanks for joining us and Welcome to {{ CNF_COMNAME }} </p>
<p> Following is your account Information </p>
<p>
    Username: {{ $email_data["username"] }}  <br>
    Password : {{ $email_data["password"] }} <br>
</p>
<p> Please follow the link to activate your account <br>
    <a href="{{ URL::to('user/activation?code='.$email_data['activate']) }}"> Active my account now</a></p>
<p> If the link does not work, please copy and paste link below </p>
<p> {{ URL::to('user/activation?code='.$email_data['activate']) }}</p><p> Thank You</p>
<h3>{{ CNF_COMNAME }}</h3>