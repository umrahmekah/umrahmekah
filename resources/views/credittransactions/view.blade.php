@extends('layouts.app')

@section('content')
	<section class="content-header">
		<h1> {{ $pageTitle }} </h1>
	</section>

	<div class="content">

		<div class="box box-primary">
			<div class="box-header with-border">
				<div class="box-header-tools pull-left" >
					<a href="{{ url($pageModule.'?return='.$return) }}" class="tips"  title="{{ Lang::get('core.btn_back') }}" ><i class="fa  fa-arrow-left fa-2x"></i></a>
				</div>
			</div>
			<div class="box-body" >

				<table class="table table-striped table-bordered" >
					<tbody>

					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Transaction Number', (isset($fields['transaction_id']['language'])? $fields['transaction_id']['language'] : array())) }}</td>
						<td>{{ $row->transaction_id}} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Amount Paid', (isset($fields['amount_paid']['language'])? $fields['amount_paid']['language'] : array())) }}</td>
						<td>{{ $row->amount_paid}} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Credit Request', (isset($fields['credit_request']['language'])? $fields['credit_request']['language'] : array())) }}</td>
						<td>{{ $row->credit_request}} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Transaction Date', (isset($fields['transaction_date']['language'])? $fields['transaction_date']['language'] : array())) }}</td>
						<td>{{ $row->transaction_date}} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Payment Gateway', (isset($fields['payment_gateway_id']['language'])? $fields['payment_gateway_id']['language'] : array())) }}</td>
						<td>{{ $gateway_name->gateway_name}} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Currency', (isset($fields['currency']['language'])? $fields['currency']['language'] : array())) }}</td>
						<td>{{ $currency_name->currency_name}} </td>

					</tr>

					</tbody>
				</table>



			</div>
		</div>
	</div>

@stop