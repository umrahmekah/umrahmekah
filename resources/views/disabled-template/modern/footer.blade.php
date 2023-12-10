
<!-- FOOTER -->       
<footer class="bg-dark type-2">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="footer-block">
              <div class="f_text color-grey-7">
                <h4>{{ CNF_COMNAME }}</h4>
                <h6>{{ CNF_TAGLINE }}</h6>
                {{ CNF_DESCRIPTION }}
              </div>
              <div class="footer-share">
                @if (CNF_FACEBOOK !='')   
                  <a href="{{ CNF_FACEBOOK }}" target="_blank"><span class="fa fa-facebook"></span></a>
                @endif
                @if (CNF_TWITTER !='')   
                  <a href="{{ CNF_TWITTER }}"  target="_blank"><span class="fa fa-twitter"></span></a>
                @endif
                @if (CNF_INSTAGRAM !='')   
                  <a href="{{ CNF_INSTAGRAM }} " target="_blank"><span class="fa fa-instagram"></span></a>
                @endif
                @if (CNF_TRIPADVISOR !='')   
                  <a href="{{ CNF_TRIPADVISOR }}" target="_blank"><span class="fa fa-tripadvisor"></span></a>
                @endif
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6 col-sm-6 no-padding">
           <div class="footer-block">             
           </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
             <div class="footer-block"></div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="footer-block">
              <h6>{{ Lang::get('core.contact_us') }}</h6>
                <div class="contact-info">
                  <div class="contact-line color-grey-3"><i class="fa fa-map-marker"></i><span><a href="https://www.google.com.my/maps/place/{{ urlencode(CNF_ADDRESS) }}" target="_blank">{{ CNF_ADDRESS }}</a></span></div>
                  <div class="contact-line color-grey-3"><i class="fa fa-phone"></i><a href="tel:{{ CNF_TEL }}">{{ CNF_TEL }}</a></div>
                  <div class="contact-line color-grey-3"><i class="fa fa-envelope-o"></i><a href="mailto:{{ CNF_EMAIL }}">{{ CNF_EMAIL }}</a></div>          
                  <div class="contact-line color-grey-3"><i class="fa fa-globe"></i><a href="https://{{ CNF_DOMAIN }}" target="_blank">{{ CNF_DOMAIN }}</a></div>          
                </div>
             </div> 
          </div>
        </div>
      </div>
      <div class="footer-link bg-black">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
                <div class="copyright">
            <span>&copy; {{ date('Y')}} {{ CNF_COMNAME }}.</strong> {{Lang::get('core.allrights')}}.</span>
          </div>
              <ul>

            <li><a class="link-aqua" href="https://oomrah.com/privacy">{{ Lang::get('core.privacy_policy') }}</a></li>
            <li><a class="link-aqua" href="https://oomrah.com/term"> {{ Lang::get('core.tandc') }}</a></li>
          </ul>
            </div>
          </div>
        </div>
      </div>
    </footer>
<script src="{{ URL::asset('assets/theme/modern/js/jquery-2.1.4.min.js')}}"></script>
<script src="{{ URL::asset('assets/theme/modern/js/bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('assets/theme/modern/js/jquery-ui.min.js')}}"></script>
<script src="{{ URL::asset('assets/theme/modern/js/idangerous.swiper.min.js')}}"></script>
<script src="{{ URL::asset('assets/theme/modern/js/jquery.viewportchecker.min.js')}}"></script>
<script src="{{ URL::asset('assets/theme/modern/js/isotope.pkgd.min.js')}}"></script>
<script src="{{ URL::asset('assets/theme/modern/js/jquery.circliful.min.js')}}"></script>
<script src="{{ URL::asset('assets/theme/modern/js/jquery.mousewheel.min.js')}}"></script>
<script src="{{ URL::asset('assets/theme/modern/js/all.js')}}"></script>
<script src="{{ URL::asset('assets/js/lightbox.js')}}"></script>
<script> 
$(document).ready(function() {
  $('.image-link').magnificPopup({
    delegate: 'a',
    type: 'image',
    tLoading: 'Loading image...',
    mainClass: 'mfp-img-mobile',
    gallery: {
      enabled: true,
      navigateByImgClick: true,
      closeBtnInside :true,
      enableEscapeKey: true,
    },
    image: {
      tError: '<a>The image could not be loaded. </a>'
    }
  });
});
</script>
</body>
</html>          