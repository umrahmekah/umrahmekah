<meta charset="utf-8">


<h2>Assalamualikum {{ $email_data["name"] }} ,</h2>
<p>Terima kasih kerana membuat tempahan bersama kami dan Selamat datang ke {{ CNF_COMNAME }} </p>
<p>Maklumat Tempahan anda adalah seperti berikut.</p>
<p>
	Nombor booking : {{$booking->bookingno}}<br>
	Pakej : {{$booktour->tour->tour_name}} ({{$booktour->tourdate->tour_code}})<br>
	Tarikh : {{\Carbon::parse($booktour->tourdate->start)->format('d/m/Y')}} - {{\Carbon::parse($booktour->tourdate->end)->format('d/m/Y')}}<br>
</p>
<br>
<p>Maklumat akaun anda adalah seperti berikut.</p>
<p>
    Email : {{ $email_data["username"] }}  <br>
    Password : {{ $email_data["password"] }} <br>
</p>

<p>Sila takan pautan di bawah untuk mangaktifkan akaun anda<br>
    <a href="{{ URL::to('user/activation?code='.$email_data['activate']) }}"> Active my account now</a></p>
<p>Jika pautan tidak berfungsi, sila "Copy" dan "Paste" pautan di bawah</p>
<p> {{ URL::to('user/activation?code='.$email_data['activate']) }}</p><p>Terima Kasih</p>
<h3>{{ CNF_COMNAME }}</h3>