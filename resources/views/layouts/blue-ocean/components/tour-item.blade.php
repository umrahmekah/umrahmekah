<ul class="pl-0">
    @foreach ($rowData as $row)
        <?php $min_price = min_price($row); ?>
        @if($row->status == 1)
            <div class="tour">
                <a href="#" class="tour__container" style="background-image: url('@if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.$row->view_image) && $row->view_image !='') {{ asset('uploads/images/'.CNF_OWNER.'/'.$row->view_image)}} @else {{ asset('mmb/images/tour-noimage.jpg')}} @endif');">
                    {{-- @if($min_price > 0)
                        <div class="tour__discount">
                            <span>25% Off</span>
                        </div>
                    @endif --}}
                </a>
                <div class="tour__content">
                    <div class="tour__detail">
                        <h3>{{ $row->tour_name }}</h3>
                        <div class="tour__time"><i class="far fa-calendar-alt"></i> {{ Lang::get('core.departuredates') }}
                                <ul>
                                    <?php $num = 0; ?>
                                    @foreach($row->filteredTourdate->where('status', 1) as $tourdate)
                                        <?php if ($num == 5) {break;} ?>
                                        <li>{{ date('d M Y', strtotime($tourdate->start)) }}</li>
                                        <?php $num++; ?>
                                    @endforeach
                                </ul>
                        </div>
                        
                    </div>
                    <div class="packages__pricing">
                        <div class="packages__pricing col-xs-12 col-sm-12">
                            {{-- <p class="packages__pricing-discount text-center">
                                {{ Lang::get('core.from') }}
                            </p>
                            <p class="packages__pricing-price font-weight-bold text-center">
                                @if($min_price > 0)
                                    <span>{{CURRENCY_SYMBOLS}} {{ moneyFormat($min_price) ?? '--' }}</span>
                                @else 
                                    <span>{{CURRENCY_SYMBOLS}} </span>
                                @endif
                            </p>
                            <div class="package__title__rating">
                                <i class="fas fa-star"></i> 
                                <i class="fas fa-star"></i> 
                                <i class="fas fa-star"></i> 
                                <i class="fas fa-star"></i> 
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="tour__rating">(2 Reviews)</span> --}}
                            
                            <?php 
                                $view = 'view=' . $row->tourID; 
                                if(!is_null(app('request')->input('affiliate'))) {
                                    $view .= '&affiliate=' . app('request')->input('affiliate');
                                }
                            ?>

                            <a href="?{{ $view }}" class="btn btn-primary btn-block">{{ Lang::get('core.view_package') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</ul>