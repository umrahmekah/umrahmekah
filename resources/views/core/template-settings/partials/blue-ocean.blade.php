@push('scripts')
	<script type="text/javascript">
		function submitSectionForm(name) {
			var data = $('#' + name).serialize();
			jQuery.post('{{ route('api.template-settings.blue-ocean.section') }}?section_name=' + name, data, function(data, textStatus, xhr) {
				alert(data.message);

				if(data.status) {
					location.reload();
				}

			});
		}

		function resetSectionForm(name) {
			jQuery.post('{{ route('api.template-settings.blue-ocean.section-reset') }}?section_name=' + name, {}, function(data, textStatus, xhr) {
				alert(data.message);

				if(data.status) {
					location.reload();
				}

			});
		}
	</script>
@endpush

<form class="form-horizontal" 
	action="{{ route('blue-ocean.setting.section') }}?section_name=section-one" 
	method="post" enctype="multipart/form-data">
	<div class="box-header with-border">
		<h3 class="box-title">Section 1: Carousel Photos <small>(Remove or disable banners in order to user this feature)</small></h3>
	</div>

	@foreach(config('templates.blue-ocean.settings.section-one.photos') as $key => $photo)
		<div class="form-group">
		    <label for="upload[{{ $key }}]" class=" control-label col-md-4">Photo {{ $key + 1 }}</label>
			<div class="col-md-6">
				<input name="upload[{{ $key }}]" type="file" id="upload[{{ $key }}]" style="margin: 1rem"> 
				<img src="{{ $photo['url'] }}" style="max-width: 200px;margin: 1rem;">
				<input name="photos[{{ $key }}][url]" value="{{ $photo['url'] }}" type="hidden">
				<input name="photos[{{ $key }}][alt]" type="text" id="photos[{{ $key }}][alt]" class="form-control input-sm" value="{{ $photo['alt'] }}" placeholder="Image Alternate Text">  
				<input name="photos[{{ $key }}][heading-one]" type="text" id="photos[{{ $key }}][heading-one]" class="form-control input-sm" value="{{ $photo['heading-one'] }}" placeholder="First Heading">  
				<input name="photos[{{ $key }}][heading-second]" type="text" id="photos[{{ $key }}][heading-second]" class="form-control input-sm" value="{{ $photo['heading-second'] }}" placeholder="Second Heading">  
				<input name="photos[{{ $key }}][learn-more-link]" type="text" id="photos[{{ $key }}][learn-more-link]" class="form-control input-sm" value="{{ $photo['learn-more-link'] }}"  placeholder="Learn More Link">   
			</div> 
		</div>
	@endforeach
	
	<div class="form-group">
		<label for="ipt" class=" control-label col-md-4">&nbsp;</label>
		<div class="col-md-8">
			<button type="submit" class="btn btn-primary" >{{ Lang::get('core.sb_savechanges') }}</button type="submit">
			<div class="btn btn-secondary" onclick="resetSectionForm('section-one')">{{ Lang::get('core.sb_reset') }}</div>
		 </div> 
	</div>
</form>

<form class="form-horizontal" id="section-two" method="post" enctype="multipart/form-data">
	<div class="box-header with-border">
		<h3 class="box-title">Section 2: Features</h3>
	</div>

	<div class="form-group">
	    <label for="ipt" class=" control-label col-md-4"> Enable <br>  </label>
		<div class="col-md-6">
			<input name="enabled" type="checkbox" id="enabled"  @if(config('templates.blue-ocean.settings.section-two.enabled')) checked="" @endif value="1">
		</div> 
	</div>

	@foreach(config('templates.blue-ocean.settings.section-two.features') as $key => $feature)
		<div class="form-group">
		    <label for="features[{{ $key }}][title]" class=" control-label col-md-4">Feature Item #{{ $key + 1 }}</label>
			<div class="col-md-6">
				<input name="features[{{ $key }}][title]" type="text" id="features[{{ $key }}][title]" class="form-control input-sm" value="{{ $feature['title'] }}" placeholder="Title">  
				<input name="features[{{ $key }}][message]" type="text" id="features[{{ $key }}][message]" class="form-control input-sm" value="{{ $feature['message'] }}" placeholder="Message">  
				<input name="features[{{ $key }}][icon]" type="hidden" id="features[{{ $key }}][icon]" class="form-control input-sm" value="{{ $feature['icon'] }}">  
			</div> 
		</div>
	@endforeach

	<div class="form-group">
		<label for="ipt" class=" control-label col-md-4">&nbsp;</label>
		<div class="col-md-8">
			<div class="btn btn-primary" onclick="submitSectionForm('section-two')">{{ Lang::get('core.sb_savechanges') }}</div>
			<div class="btn btn-secondary" onclick="resetSectionForm('section-two')">{{ Lang::get('core.sb_reset') }}</div>
		 </div> 
	</div>
</form>


<form class="form-horizontal" id="section-three" method="post" enctype="multipart/form-data">
	<div class="box-header with-border">
		<h3 class="box-title">Section 3: Package</h3>
	</div>

	<div class="text-center pt-3 mt-3">
		<p>See <a href="{{ url('core/config/security') }}">Homepage Settings</a> to configure this section.</p>
	</div>
</form>

<form class="form-horizontal" id="section-four" method="post" enctype="multipart/form-data">
	<div class="box-header with-border">
		<h3 class="box-title">Section 4: Testimonials</h3>
	</div>

	<div class="text-center pt-3 mt-3">
		<p>See <a href="{{ url('core/config/security') }}">Homepage Settings</a> to configure this section.</p>
	</div>
</form>

<form class="form-horizontal" id="section-five" method="post" enctype="multipart/form-data">
	<div class="box-header with-border">
		<h3 class="box-title">Section 5: Browse Tour Activiy &amp; Newsletter</h3>
	</div>

	<div class="form-group">
	    <label for="ipt" class=" control-label col-md-4"> Enable <br>  </label>
		<div class="col-md-6">
			<input name="enabled" type="checkbox" id="enabled"  @if(config('templates.blue-ocean.settings.section-five.enabled')) checked="" @endif value="1">
		</div> 
	</div>

	<div class="form-group">
		<label class=" control-label col-md-4">First Column</label>
	</div>
	@foreach(config('templates.blue-ocean.settings.section-five.activities.first') as $key => $activity)
		<div class="form-group">
		    <label for="activities[first][{{ $key }}][url]" class=" control-label col-md-4">Activity {{ $key + 1 }}</label>
			<div class="col-md-6">
				<input name="activities[first][{{ $key }}][label]" type="text" id="activities[first][{{ $key }}][label]" class="form-control input-sm" value="{{ $activity['label'] }}" placeholder="Label">  
				<input name="activities[first][{{ $key }}][url]" type="text" id="activities[first][{{ $key }}][url]" class="form-control input-sm" value="{{ $activity['url'] }}" placeholder="Activity URL">  
			</div> 
		</div>
	@endforeach

	<div class="form-group">
		<label class=" control-label col-md-4">Second Column</label>
	</div>
	@foreach(config('templates.blue-ocean.settings.section-five.activities.second') as $key => $activity)
		<div class="form-group">
		    <label for="activities[second][{{ $key }}][url]" class=" control-label col-md-4">Activity {{ $key + 1 }}</label>
			<div class="col-md-6">
				<input name="activities[second][{{ $key }}][label]" type="text" id="activities[second][{{ $key }}][label]" class="form-control input-sm" value="{{ $activity['label'] }}" placeholder="Label">  
				<input name="activities[second][{{ $key }}][url]" type="text" id="activities[second][{{ $key }}][url]" class="form-control input-sm" value="{{ $activity['url'] }}" placeholder="Activity URL">  
			</div> 
		</div>
	@endforeach

	<div class="form-group">
		<label for="ipt" class=" control-label col-md-4">&nbsp;</label>
		<div class="col-md-8">
			<div class="btn btn-primary" onclick="submitSectionForm('section-five')">{{ Lang::get('core.sb_savechanges') }}</div>
			<div class="btn btn-secondary" onclick="resetSectionForm('section-five')">{{ Lang::get('core.sb_reset') }}</div>
		 </div> 
	</div>
</form>

<form class="form-horizontal" 
	action="{{ route('blue-ocean.setting.section') }}?section_name=section-six" 
	method="post" enctype="multipart/form-data">

	<div class="box-header with-border">
		<h3 class="box-title">Section 6: Discount</h3>
	</div>

	<div class="form-group">
	    <label for="ipt" class=" control-label col-md-4"> Enable <br>  </label>
		<div class="col-md-6">
			<input name="enabled" type="checkbox" id="enabled"  @if(config('templates.blue-ocean.settings.section-six.enabled')) checked="" @endif value="1">
		</div> 
	</div>

	<?php 
		$section_six = config('templates.blue-ocean.settings.section-six');
	?>

	<div class="form-group">
	    <label for="background-image" class=" control-label col-md-4">Background Image</label>
		<div class="col-md-6">
			<input name="upload[]" type="file" id="upload[]" style="margin: 1rem"> 
			<img src="{{ $section_six['background-image'] }}" style="max-width: 200px;margin: 1rem;">
			<input name="background-image" value="{{ $section_six['background-image'] }}" type="hidden">
		</div> 
	</div>

	<div class="form-group">
	    <label for="title" class=" control-label col-md-4">Title</label>
		<div class="col-md-6">
			<input name="title" type="text" id="title" class="form-control input-sm" value="{{ $section_six['title'] }}">  
		</div> 
	</div>

	<div class="form-group">
	    <label for="title-sub" class=" control-label col-md-4">Title Sub</label>
		<div class="col-md-6">
			<input name="title-sub" type="text" id="title-sub" class="form-control input-sm" value="{{ $section_six['title-sub'] }}">  
		</div> 
	</div>

	<div class="form-group">
	    <label for="message" class=" control-label col-md-4">Description</label>
		<div class="col-md-6">
			<input name="message" type="text" id="message" class="form-control input-sm" value="{{ $section_six['message'] }}">  
		</div> 
	</div>

	<div class="form-group">
	    <label for="promotion_tour_label" class=" control-label col-md-4">Promotion Tour Label</label>
		<div class="col-md-6">
			<input name="promotion_tour_label" type="text" id="promotion_tour_label" class="form-control input-sm" value="{{ $section_six['promotion_tour_label'] }}">  
		</div> 
	</div>

	<div class="form-group">
	    <label for="promotion_tour_link" class=" control-label col-md-4">Promotion Tour Link</label>
		<div class="col-md-6">
			<input name="promotion_tour_link" type="text" id="promotion_tour_link" class="form-control input-sm" value="{{ $section_six['promotion_tour_link'] }}">  
		</div> 
	</div>

	<div class="form-group">
		<label for="ipt" class=" control-label col-md-4">&nbsp;</label>
		<div class="col-md-8">
			<button type="submit" class="btn btn-primary">{{ Lang::get('core.sb_savechanges') }}</button type="submit">
			<div class="btn btn-secondary" onclick="resetSectionForm('section-six')">{{ Lang::get('core.sb_reset') }}</div>
		 </div> 
	</div>
</form>

<form class="form-horizontal" id="section-seven" method="post" enctype="multipart/form-data">
	<div class="box-header with-border">
		<h3 class="box-title">Section 7: {{ Lang::get('core.why_us') }}</h3>
	</div>

	<div class="form-group">
	    <label for="ipt" class=" control-label col-md-4"> Enable <br>  </label>
		<div class="col-md-6">
			<input name="enabled" type="checkbox" id="enabled"  @if(config('templates.blue-ocean.settings.section-seven.enabled')) checked="" @endif value="1">
		</div> 
	</div>
	
	@foreach(config('templates.blue-ocean.settings.section-seven.reasons') as $key => $reason)
		<input type="hidden" name="reasons[{{ $key }}][icon]" id="reasons[{{ $key }}][icon]" value="{{ $reason['icon'] }}">
		<div class="form-group">
		    <label for="reasons[{{ $key }}][title]" class=" control-label col-md-4">Reason {{ $key + 1 }}</label>
			<div class="col-md-6">
				<input name="reasons[{{ $key }}][title]" type="text" id="reasons[{{ $key }}][title]" class="form-control input-sm" value="{{ $reason['title'] }}">  
				<input name="reasons[{{ $key }}][message]" type="text" id="reasons[{{ $key }}][message]" class="form-control input-sm" value="{{ $reason['message'] }}">  
			</div> 
		</div>
	@endforeach

	<div class="form-group">
		<label for="ipt" class=" control-label col-md-4">&nbsp;</label>
		<div class="col-md-8">
			<div class="btn btn-primary" onclick="submitSectionForm('section-seven')">{{ Lang::get('core.sb_savechanges') }}</div>
			<div class="btn btn-secondary" onclick="resetSectionForm('section-seven')">{{ Lang::get('core.sb_reset') }}</div>
		 </div> 
	</div>
</form>

<form class="form-horizontal"
	action="{{ route('blue-ocean.setting.section') }}?section_name=footer" 
	method="post" enctype="multipart/form-data">
	<div class="box-header with-border">
		<h3 class="box-title">Footer</h3>
	</div>

	<?php 

		$top_destination = config('templates.blue-ocean.settings.footer.top_destination');

	?>

	<div class="form-group">
	    <label for="ipt" class=" control-label col-md-4"> Enable <br>  </label>
		<div class="col-md-6">
			<input name="enabled" type="checkbox" id="enabled"  @if(true == $top_destination['enabled']) checked="" @endif value="1">
		</div> 
	</div>
	
	<div class="form-group">
	    <label for="background_image" class=" control-label col-md-4">Background Image</label>
		<div class="col-md-6">
			<input name="upload[]" type="file" id="upload[]" style="margin: 1rem"> 
			<img src="{{ $top_destination['background_image'] }}" style="max-width: 200px;margin: 1rem;">
			<input name="background_image" value="{{ $top_destination['background_image'] }}" type="hidden">
		</div> 
	</div>

	<div class="form-group">
		<label for="ipt" class=" control-label col-md-4">&nbsp;</label>
		<div class="col-md-8">
			<button type="submit" class="btn btn-primary">{{ Lang::get('core.sb_savechanges') }}</button type="submit">
			<div class="btn btn-secondary" onclick="resetSectionForm('footer')">{{ Lang::get('core.sb_reset') }}</div>
		 </div> 
	</div>
</form>