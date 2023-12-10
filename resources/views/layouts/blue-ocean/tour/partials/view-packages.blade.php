<div class="package__section">
	<div class="package__single">
		<h2><i class="fas fa-list"></i> {{ Lang::get('core.listpackage') }}</h2>
		<?php $min_price = null; ?>
		<div class="row">
        @foreach($tdate as $td)
			@include('layouts.blue-ocean.tour.partials.view-package-card')
        @endforeach
        </div>
	</div>
</div>