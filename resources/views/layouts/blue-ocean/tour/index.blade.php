@extends('layouts.blue-ocean.default')

@section('content')
<div class="packages">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8">

                @include('layouts.blue-ocean.tour.partials.package-sorter')
                
                @include('layouts.blue-ocean.components.tour-item', ['rowData' => $rowData])
                
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 packages__sidebar">
                <h4 class="font-normal font-weight-bold">{{ Lang::get('core.tourcategory') }}</h4>
                <ul>
                    <li class="packages__latest__container-title mt-3 mb-3" style="cursor: pointer;">
                        <a class="" onclick="window.location.href ='{{ url('/package')}}'">All </a>
                    </li>
                    @foreach ($category as $cat)
                        <li class="packages__latest__container-title mt-3 mb-3" style="cursor: pointer;">
                            <a class="" onclick="window.location.href ='{{ url('/package?cat=')}}{{$cat->tourcategoriesID}}'">{{$cat->tourcategoryname}} <span class="fr">({{ $cat->category_count }})</span></a>
                        </li>
                    @endforeach
                </ul>

                <div class="package__card package__card-cta" style="background-image: url(&quot;https://cdn.goodlayers.com/traveltour/wp-content/uploads/2017/01/widget-bg.jpg&quot;);">
                    <h2>{{ Lang::get('core.got_a_question') }}</h2> 

                    <p>{{ Lang::get('core.got_a_question_description') }}</p> 

                    <div class="package__card-row">
                        <i class="fas fa-phone"></i> 
                        <span class="text-yellow font-weight-bold">{{ CNF_TEL }}</span>
                    </div> 
                    <div class="package__card-row">
                        <i class="fas fa-envelope"></i> <span class="text-white">{{ CNF_EMAIL }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection