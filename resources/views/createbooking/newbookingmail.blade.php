<meta charset="utf-8">


<h2>Assalamualikum {{ $booking->traveller->fullname }},</h2>
<p>Terima kasih kerana membuat tempahan bersama kami.</p>
<p>Maklumat Tempahan anda adalah seperti berikut.</p>
<p>
	Nombor booking : {{$booking->bookingno}}<br>
	Pakej : {{$booktour->tourdate->tour->tour_name}} ({{$booktour->tourdate->tour_code}})<br>
	Tarikh : {{\Carbon::parse($booktour->tourdate->start)->format('d/m/Y')}} - {{\Carbon::parse($booktour->tourdate->end)->format('d/m/Y')}}<br>
</p>
<br>
<p>Terima Kasih</p>
<h3>{{ CNF_COMNAME }}</h3>