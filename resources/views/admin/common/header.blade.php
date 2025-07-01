<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('page_title') - {{app_short_name()}} </title>

    <!-- Bootstrap core CSS -->
    <link href="{{app_url()}}/assets/admin/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="{{app_url()}}/assets/admin/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{app_url()}}/assets/admin/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="{{app_url()}}/assets/admin/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="{{app_url()}}/assets/admin/lineicons/style.css">
    <link href="{{app_url()}}/assets/vendor/datatables/datatables.min.css" type="text/css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{app_url()}}/assets/admin/css/style.css" rel="stylesheet">
    <link href="{{app_url()}}/assets/admin/css/style-responsive.css" rel="stylesheet">

    <script src="{{app_url()}}/assets/admin/js/chart-master/Chart.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
