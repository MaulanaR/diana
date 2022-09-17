<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ isset($title) ? $title.' | ' : '' }} {{ config('app.name', 'Sistem Informasi') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
	<link rel="shortcut icon" href="{{asset('logo/logo.png')}}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('sweetalert2/dist/sweetalert2.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('bootstrap-select/css/bootstrap-select.min.css') }}" />
    <style>
        #loadingx {
          display: none; /* Hidden by default */
          position: fixed; /* Fixed/sticky position */
          bottom: 20px; /* Place the button at the bottom of the page */
          right: 30px; /* Place the button 30px from the right */
          z-index: 99; /* Make sure it does not overlap */
          border: 1px solid grey; /* Remove borders */
          outline: none; /* Remove outline */
          background-color: white; /* Set a background color */
          color: white; /* Text color */
          cursor: pointer; /* Add a mouse pointer on hover */
          padding: 10px; /* Some padding */
          border-radius: 10px; /* Rounded corners */
          font-size: 15px; /* Increase font size */
          color:#494E54;
          box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }

        ul.nav-treeview > li.nav-item{
            padding-left: 1em !important;
        }
    </style>
    @yield('css')
</head>

<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <div id="loadingx" title="Go to top"><span class="spinner-border text-primary spinner-border-sm"></span> Loading..</div> 
        <nav class="main-header navbar navbar-expand navbar-dark navbar-purple">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <span class="nav-link">
                        @if (Session::has('current_roles'))
                                @foreach (Session::get('current_roles') as $item_sessi)
                                    <i class="fas fa-info-circle"></i> Role Aktif: {{ucwords($item_sessi->name)}}
                                @endforeach
                            @endif
                    </span>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                {{-- <li class="nav-item">
                    <button class="btn btn-md btn-outline-warning mr-1" role="button" onclick="change_role()">
                        <i class="fas fa-door-open"></i> Ubah Role
                    </button>
                </li> --}}
                <li class="nav-item">
                    <a class="btn btn-md btn-danger" href="#" role="button"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Log Out
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-purple elevation-4">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="brand-link  navbar-light">
                <img src="{{ asset('logo/logo_notr.png') }}" alt="{{ config('app.name', 'Laravel') }}"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ config('app.name', 'Sistem Informasi') }}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        @if(file_exists(Auth::user()->picture)) 
                        <img src="{{Auth::user()->picture}}" class="img-circle elevation-2" alt="User Image">
                        @else
                        <img src="{{asset('avatar/user.png')}}" class="img-circle elevation-2" alt="User Image">
                        @endif
                    </div>
                    <div class="info" >
                        <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                        
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item active">
                            @if (Request::segment(1) == 'backend' && Request::segment(2) == null)
                                <a href="{{ route('homelogin') }}" class="nav-link active">
                                @else
                                    <a href="{{ route('homelogin') }}" class="nav-link">
                            @endif
                            <i class="far fa-circle nav-icon"></i>
                            <p>Dashboard</p>
                            </a>
                        </li>
                        @if (Session::has('menu'))
                            {!! Session::get('menu') !!}
                        @endif
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header pt-2 pb-2">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <h3>{{ isset($title) ? $title : '' }}</h3>
                        </div>
                        
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                @yield('content')
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer text-sm">
            <div class="float-right d-none d-sm-block">
                Version 0.0.0
            </div>
            <strong>Copyright &copy; @php echo date('Y') @endphp <a href="#">---.</a></strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    @php
        $group_grole = DB::table('alus_ug')
                ->where('user_id', Auth::user()->id)
                ->join('alus_g', 'alus_ug.group_id', '=', 'alus_g.id')
                ->get();
        $opt_rol = null;
        foreach ($group_grole as $key_group_grole => $val_group_grole) {
            $opt_rol .= '<option value="'.$val_group_grole->group_id.'">'.$val_group_grole->name.'</option>';
        }
    @endphp
    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('sweetalert2/dist/sweetalert2.min.js') }}"></script>

    <script>
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function(xhr, settings) {
          $("#loadingx").show();
        },
        complete: function(data)
        {
            $("#loadingx").hide();
        },
    });

  function popup(judul = null, msg = null, tipe = 'success', red = false, timec = 3000) {
      let timerInterval
      Swal.fire({
          title: judul,
          html: msg + '',
          type: tipe,
          timer: timec,
          timerProgressBar: true,
          onBeforeOpen: () => {
            timerInterval = setInterval(() => {
                  const content = Swal.getContent()
                  if (content) {
                      const b = content.querySelector('span')
                      if (b) {
                          b.textContent = Swal.getTimerLeft()
                      }
                  }
              }, 100)
          },
          onClose: () => {
              clearInterval(timerInterval);
              if (red) {
                  window.location.href = "{{ url('/') }}/" + red;
              }
          }
      }).then((result) => {
          if (result.dismiss === Swal.DismissReason.timer) {
              if (red) {
                  window.location.href = "{{ url('/') }}/" + red;
              }
          }
      })
  }

  function popup_reload(judul = null, msg = null, tipe = 'success', timex = 3000) {
      let timerInterval
      Swal.fire({
          title: judul,
          html: msg + '',
          type: tipe,
          allowOutsideClick: false,
          allowEnterKey: false,
          allowEscapeKey: false,
          timer: timex,
          timerProgressBar: true,
          onBeforeOpen: () => {
              timerInterval = setInterval(() => {
                  const content = Swal.getContent()
                  if (content) {
                    //   const b = content.querySelector('span')
                    //   if (b) {
                    //       b.textContent = Swal.getTimerLeft()
                    //   }
                  }
              }, 100)
          },
          onClose: () => {
              clearInterval(timerInterval);
              location.reload();
          }
      }).then((result) => {
          if (result.dismiss === Swal.DismissReason.timer) {
              location.reload();
          }
      })
  }

  function change_role()
  {
    Swal.fire({
    title: 'Pilih Role : ',
    html : '<select class="form-control" id="sel_rol">{!!$opt_rol!!}</select>',
    showCancelButton: true,
    confirmButtonText: 'Proses !',
    showLoaderOnConfirm: true,
    allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
    if (result.value) {
        $.ajax({
            type: "GET",
            url: "{{route('change_role')}}",
            data: {'id_group' : $("#sel_rol").val()},
            dataType: "json",
            success: function (response) {
                if(response.status){
                    window.location.replace("{{url('/backend')}}");
                }else{
                    popup('gagal',`${xhr.responseText}`,'error');
                }
            },
            error: function(xhr, status, error){
                popup('gagal',`${xhr.responseText}`,'error');
            }
        })
    }
    })
  }

  $(function() {
    $("#prnih").parents().addClass("menu-open");
        $('.sel').selectpicker({
          'liveSearch': true
        });
    });

</script>

    <!-- external js -->
    @yield('js')

</body>

</html>
