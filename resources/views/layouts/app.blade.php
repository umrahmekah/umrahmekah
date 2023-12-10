<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@if ( $pageTitle != NULL ) {{ $pageTitle }} | @endif Oomrah </title>
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/favicon/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/favicon//apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/favicon//apple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/favicon//apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/favicon//apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/favicon//apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/favicon//apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/favicon//apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon//apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('assets/favicon//android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon//favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/favicon//favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon//favicon-16x16.png')}}">
    <link rel="manifest" href="{{ asset('assets/favicon//manifest.json')}}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('mmb')}}/css/bootstrap.css">
    <link rel="stylesheet" href="{{ asset('mmb')}}/css/animate.css">
    <link rel="stylesheet" href="{{ asset('mmb')}}/js/summernote/dist/summernote.css">
    <link rel="stylesheet" href="{{ asset('mmb')}}/css/Grace.css">
    <link rel="stylesheet" href="{{ asset('mmb')}}/css/line-awesome-font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('mmb')}}/css/credittotals.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/css/package-form.css') }}" >

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
    <script src="{{ asset('mmb')}}/js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/jquery.cookie.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb/plugins/sparkline/jquery.sparkline.js') }}"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button);
        window.pleaseSelectText = '{{ Lang::get('core.pleaseselect') }}';
        window.datatableLang = {
            'id': {
                "sEmptyTable":   "Tidak ada data yang tersedia pada tabel ini",
                "sProcessing":   "Sedang memproses...",
                "sLengthMenu":   "Tampilkan _MENU_ entri",
                "sZeroRecords":  "Tidak ditemukan data yang sesuai",
                "sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
                "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "sInfoPostFix":  "",
                "sSearch":       "Cari:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "Pertama",
                    "sPrevious": "Sebelumnya",
                    "sNext":     "Selanjutnya",
                    "sLast":     "Terakhir"
                }
            }
        };

        window.__translate = {
            'en': {
                'mmb_loading': '....Loading content , please wait ...',
            },
            'id': {
                'mmb_loading': '....memuat halaman , mohon tunggu ...',
            }
        };
        window.__lang = __translate.{{ config('app.locale') }};
    </script>
    <script type="text/javascript" src="{{ asset('mmb/js/leo.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb')}}/js/bootstrap.js"></script>
    <script type="text/javascript" src="{{ asset('mmb')}}/js/summernote/dist/summernote.js"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/simpleclone.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/datetimepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/parsley.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/form.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/jCombo.js') }}?v=1.1.1"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/grace.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/toastr.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/slimscroll.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/select2.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/icheck.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/app.js')}}"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/fancybox.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/spin.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/datatables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/confirmation.js') }}"></script>
    <script type="text/javascript" src="{{ asset('mmb/js/theia-sticky-sidebar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/Sortable.min.js') }}"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <style>
        @media (orientation:portrait) {
           .credit-top {
              display: none; /* visibility: none; */
           }
        }
        @media (orientation:landscape) and (max-width: 767px) {
           .credit-top {
              display: none; /* visibility: none; */
           }
        }
        .fa-stack[data-count]:after{
          position:absolute;
          right:0%;
          top:1%;
          content: attr(data-count);
          font-size:50%;
          padding:.6em;
          border-radius:999px;
          line-height:.75em;
          color: white;
          background:rgba(255,0,0,.85);
          text-align:center;
          min-width:2em;
          font-weight:bold;
        }
    </style>
</head>
<body class="hold-transition skin-black fixed sidebar-mini" data-color="theme-{{ CNF_TEMPCOLOR }}">
    <div class="wrapper">
        @include('layouts/top') @include('layouts/left')
        <div class="content-wrapper" style="padding-top: 60px">
            <div class="pageLoading"></div>
            @yield('content')
        </div>
        @include('layouts/right')
        <div class="control-sidebar-bg"></div>
        <div class="modal fade" id="mmb-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-default">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body" id="mmb-modal-content">

                    </div>
                </div>
            </div>
        </div>
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b><a href="https://www.oomrah.com" target="_blank">Oomrah</a> {{Lang::get('core.version')}}</b> 1.0.0
            </div>
            <strong> &copy; {{ date('Y')}} {{ CNF_COMNAME }}.</strong> {{Lang::get('core.allrights')}}.
        </footer>
        <div class="control-sidebar-bg"></div>
    </div>
    {{ Sitehelpers::showNotification() }}
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            setInterval(function() {
                var noteurl = $('.notif-value').attr('code');
                $.get('{{ url("notification/load") }}', function(data) {
                    $('.notif-alert').html(data.total);
                    var html = '';
                    $.each(data.note, function(key, val) {
                        html += '<li><a href="' + val.url + '"> <div> <i class="' + val.icon + ' fa-fw"></i> ' + val.title + '  <span class="pull-right text-muted small">' + val.date + '</span></div></li>';
                    });
                    $('.notification-menu').html(html);
                });
            }, 60000);
        });;

    </script>
    @stack('scripts')
</body>
</html>
