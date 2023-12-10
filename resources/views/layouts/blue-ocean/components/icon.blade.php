<a href="{{ url() }}" class="logo">
    @if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.CNF_LOGO) && CNF_LOGO !='')
        <img src="{{ URL::asset('/uploads/images/'.CNF_OWNER.'/'.CNF_LOGO)}}" style="max-height:80px; max-width:200px"/>
    @else
        <h3><span class="color-blue">{{ CNF_COMNAME }}</span></h3>
    @endif
</a>