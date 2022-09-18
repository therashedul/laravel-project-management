   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <!-- CSRF Token -->
   <meta name="csrf-token" content="{{ csrf_token() }}">

   <title>{{ config('app.name', 'Url') }}</title>

   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="icon" href="images/favicon.ico" type="image/ico" />

   <!-- Bootstrap -->
   {{-- <link href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet"> --}}
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
       integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
   <link href="{{ asset('vendors/bootstrap/dist/css/b4vtabs.css') }}" rel="stylesheet">
   <!-- Font Awesome -->
   {{-- <link href="{{ asset('vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet"> --}}
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
       integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
       crossorigin="anonymous" referrerpolicy="no-referrer" />
   <!-- NProgress -->
   <link href="{{ asset('vendors/nprogress/nprogress.css') }}" rel="stylesheet">
   <!-- Dropzone.js -->
   {{-- <link href="{{ asset('vendors/dropzone/dist/min/dropzone.min.css') }}" rel="stylesheet"> --}}
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" />

   <!-- NProgress -->
   <script src="{{ asset('css/file-manager-1.2.css') }}"></script>
   <script src="{{ asset('css/custom-4.1.css') }}"></script>

   <!-- Custom Theme Style -->
   <link href="{{ asset('build/css/custom.css') }}" rel="stylesheet">
