@push('scripts')
    <script type="text/javascript">
        function sortPackages()
        {
            window.location = '{{ url('/package?') }}' + $('#sortForm').find('select').serialize();
        }
    </script>
@endpush

<form action="{{ url('/package?')}}" method="GET" id="sortForm">
    <div class="row mt-4">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <select class="form-control" name="sort" id="sortBy" onchange="sortPackages()">
                <option value="1" disabled>Sort By</option> 
                <option value="tour_name">{{ Lang::get('core.tourname') }}</option>
                <option value="tourID">{{ Lang::get('core.tourcode') }}</option>
                <option value="tourcategoriesID">{{ Lang::get('core.tourcategory') }}</option>
                <option value="total_days">{{ Lang::get('core.totaldays') }}</option>
                <option value="views">{{ Lang::get('core.popularity') }}</option>
                <option value="departs">{{ Lang::get('core.departtype') }}</option>
            </select>
        </div> 
        <div class="col-xs-12 col-sm-6 col-md-6">
            <select class="form-control" name="order" id="sortDirection" onchange="sortPackages()">
                <option value="1" disabled>Order</option> 
                <option onchange="window.location.href ='{{ url('/package?sort=')}}{{ $sort }}&order=asc'">{{ Lang::get('core.grid_asc') }}</option> 
                <option onchange="window.location.href ='{{ url('/package?sort=')}}{{ $sort }}&order=asc'">{{ Lang::get('core.grid_desc') }}</option>
            </select>
        </div>
    </div>
</form>