<!DOCTYPE html>
<html lang="en">
<head>

    @include('user.common.header')
</head>
<body>
	<!-- header section start -->
    @include('user.common.topbar')
	<!-- header section end -->

    @yield('content')


  @include('user.common.footer')

</body>
</html>
