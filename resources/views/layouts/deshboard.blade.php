<!DOCTYPE html>
<html lang="en">

<head>
    @include('asset.header')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            @include('asset.nav')
            @include('asset.top')

            <!-- page content -->
            <div class="right_col" role="main">
                @yield('content')
            </div>
            <!-- /page content -->

            <!-- footer content -->
            @include('asset.footer')

            <!-- /footer content -->
        </div>
    </div>
    @include('asset.bottomfooter')
</body>

</html>
