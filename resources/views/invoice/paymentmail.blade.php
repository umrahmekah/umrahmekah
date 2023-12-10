Assamualaikum {{$payment->traveller->fullname}}<br>
Terima kasih atas bayaran {{CURRENCY_SYMBOLS}}{{number_format($payment->amount)}} sebentar tadi bagi tempahan {{$payment->invoice->booking->bookingno}}.<br>
<br>
@if($payment->invoice->balance > 0)
Maklumat pembayaran anda:<br>
Jumlah bayaran yang telah dibuat {{CURRENCY_SYMBOLS}}{{number_format($payment->invoice->totalPaid)}}<br>
Baki tertunggak {{CURRENCY_SYMBOLS}}{{number_format($payment->invoice->balance)}}<br>
@endif
<br>
Terima kasih kerana memilih {{CNF_COMNAME}} bagi mengerjakan ibadah umrah.<br>
<br>
Sekian.<br>
<br>
{{\Auth::user()->fullName}}<br>
{{CNF_COMNAME}}