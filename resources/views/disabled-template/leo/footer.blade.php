      <div id="contact" class="bg-primary text-white pos-relative">
  
        <div class="container pt-5 pb-2">                                                                          
            
    <h2 class="text-white text-uppercase text-letter-spacing-xs- font-weight-bold my-0 mt-2-neg display-4- pos-relative z-index-2">
      {{ CNF_COMNAME }}
    </h2>
    <p class="mt-3">{{ Lang::get('core.contact_us') }}</p>
    <p class="lead">
      <i class="ion-ios-telephone icon-1x icon-sq"></i> {{ CNF_TEL }}
      <br />
      <i class="ion-ios-email icon-1x icon-sq"></i> {{ CNF_EMAIL }}
    </p>
    <p class="lead">
      @if (CNF_FACEBOOK !='')   <a href="{{ CNF_FACEBOOK }}" target="_blank"    class="text-white"> <i class="ion-social-facebook icon-1x icon-sq"></i></a>@endif
      @if (CNF_TWITTER !='')   <a href="{{ CNF_TWITTER }}"  target="_blank"    class="text-white"> <i class="ion-social-twitter icon-1x icon-sq"></i></a>@endif
      @if (CNF_INSTAGRAM !='')   <a href="{{ CNF_INSTAGRAM }} " target="_blank"  class="text-white"> <i class="ion-social-instagram icon-1x icon-sq"></i></a>@endif
      @if (CNF_TRIPADVISOR !='')   <a href="{{ CNF_TRIPADVISOR }}" target="_blank" class="text-white"> <i class="fa fa-tripadvisor icon-1x icon-sq"></i></a>@endif
    </p>
    <p class="text-lg-right text-xs op-6 mt-4">
<strong>&copy; {{ date('Y')}} {{ CNF_COMNAME }}.</strong> {{Lang::get('core.allrights')}}.    </p>
  </div>
        </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('mmb/js/parsley.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.9.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
    <script src="{{ URL::asset('assets/js/custom-script.js')}}"></script>
    <script src="{{ URL::asset('assets/js/script.min.js')}}"></script>

  </body>
</html>