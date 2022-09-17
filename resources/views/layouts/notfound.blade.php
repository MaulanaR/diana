<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title ? $title.' | ' : '' }} {{ config('app.name', 'Sistem Informasi') }}</title>
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
<body class="sidebar-mini layout-fixed layout-footer-fixed sidebar-collapse">
    <section class="content">
        @yield('content')
    </section>
</body>