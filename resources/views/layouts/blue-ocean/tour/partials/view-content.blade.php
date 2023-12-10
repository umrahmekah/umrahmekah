<html prefix="og: http://ogp.me/ns#">
<head>
<style>

div.fb      {
  margin-top: 4px;  
  margin-right: 3px;
  margin-left: 12px;
}
    
div.twitter {
  margin-top: 8px;
}
    
</style>
    
  <meta property="og:url"           content="{{ url() }}/package?view={{$package->tourID}}"/>
  <meta property="og:type"          content="website" />
  <meta property="og:title"         content="Package Umrah" />
  <meta property="og:description"   content="Package umrah" />
  <meta property="og:image"         content="url(&quot;{!! $package_image  !!}&quot;)" />
    
</head>

<body>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v4.0"></script>


<div style="padding: 0px 10%">
	<div class="container">
		<div class="row layout__main">
			<div class="col-sm-12 col-md-12 col-lg-12" style="padding: 0;">
                <div class="row"> 
                    
                    <div class="fb"> <div class="fb-share-button" data-href="{{ url() }}/package?view={{$package->tourID}}" data-layout="button_count" data-size="small"><a target="_blank" href="{{ url() }}/package?view={{$package->tourID}}" class="fb-xfbml-parse-ignore">Share</a></div> </div>
                    
                   <div class="twitter">  <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-show-count="true">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script> </div>
                    
                </div>
				<div class="package__full" style="padding: 20px 0px;">
					<div class="container" style="padding: 0px;">
						<div class="package__section">
							<div class="package__single">
								<h2>
									<i class="fas fa-star"></i> {{ Lang::get('core.details') }}
	                                
								</h2>
								{!! $package->tour_description !!}
							</div>
						</div>

						@include('layouts.blue-ocean.tour.partials.view-packages')

						<div class="package__section">
							<div class="package__single">
								<h2><i class="fas fa-file"></i> {{ Lang::get('core.included') }}</h2>
								<ul class="list-group list-group-striped">{!! SiteHelpers::showInclusions($row->inclusions) !!}</ul>
							</div>
						</div>

						<div class="package__section">
							<div class="package__single">
								<h2><i class="fas fa-file"></i> {{ Lang::get('core.tandc') }}</h2>
								{!! Lang::get('core.read_tac', [
									'linkopen' => '<a href="/tnc?id='. $row->policyandterms .'&package='. $row->tour_name .'">',
									'linkclose' => '</a>'
									]) !!}
							</div>
						</div>

						@if(!empty($package->gallery))
							@include('layouts.blue-ocean.tour.partials.view-gallery')
						@endif
					</div>
				</div>
			</div>
			@include('layouts.blue-ocean.tour.partials.view-booking')
		</div>
	</div>
</div>

@include('layouts.blue-ocean.tour.partials.view-scripts')

</body>
</html>