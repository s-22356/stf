<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title> @yield('page_title') - {{app_short_name()}} </title>
    <link href="{{app_url()}}/assets/vendor/datatables/datatables.min.css" type="text/css" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="{{app_url()}}/assets/admin/css/bootstrap.css" rel="stylesheet">
    <!--external css-->


    <!-- Custom styles for this template -->
    <link href="{{app_url()}}/assets/admin/css/style.css" rel="stylesheet">
   

  </head>

  <body>
    
    <section class="wrapper" style="margin-top:1%;">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4 form_fields">
                    <ol class="breadcrumb bg-light p-3 rounded">
                        <li class="breadcrumb-item"><a href="#">Log</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><b>{{ $page_title }}</b></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card" style="border-radius: 10px;">
                    <div class="card-body table-responsive-lg">
                      
                        @if(sizeof($data))    
                        <div class="card shadow-lg" style="border-radius: 7px;height:100%;padding:1%;">
                            <div class="card-body p-4">
                                <table id="datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Log Id</th>
                                            <th>Type</th>
                                            <th>URL</th>
                                            <th>Method</th>
                                            <th>Post Fields</th>
                                            <th>Header</th>
                                            <th>Hit/Response Time</th>
                                            <th>Request By</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($data as $k => $d)
                                            @php
                                                if($d->message){
                                                    $auth_records = json_decode($d->message, true);
                                                    
                                                }
                                                
                                            @endphp
                                            <tr>
                                                <td>{{ $k + 1 }}</td>
                                                <td>{{ $d->id }}</td>
                                                <td>{{ $d->type }}</td>
                                                <td style="word-wrap: break-word; max-width: 200px; overflow-x: auto;">{{ $d->type  == 'Curl Request' ? $auth_records['url'] : 'Unknown' }}</td>
                                                <td style="word-wrap: break-word; max-width: 200px; overflow-x: auto;">{{ $d->type  == 'Curl Request' ? $auth_records['method'] : 'Unknown' }}</td>
                                                <td style="word-wrap: break-word; max-width: 200px; overflow-x: auto;">{{ $d->type  == 'Curl Request' && isset($auth_records['post_fields']) ? $auth_records['post_fields'] : ($d->type == 'Curl Response' && isset($auth_records['response']) ? $auth_records['response'] : 'Unknown') }}</td>
                                                <td style="word-wrap: break-word; max-width: 200px; overflow-x: auto;">{{ $d->type  == 'Curl Request' && isset($auth_records['headers']) ? json_encode($auth_records['headers'],true) : 'Unknown' }}</td>
                                                <td style="word-wrap: break-word; max-width: 200px; overflow-x: auto;">
                                                    @if($d->type == 'Curl Request')
                                                        {{ isset($auth_records['hit_time']) ? $auth_records['hit_time'] : 'Unknown' }}
                                                    @elseif($d->type == 'Curl Response')
                                                        {{ isset($auth_records['response_time']) ? $auth_records['response_time'] : 'Unknown' }}
                                                    @else
                                                        Unknown
                                                    @endif
                                                </td>
                                                <td style="word-wrap: break-word; max-width: 200px;">{{ $auth_records['reqest_by'] ?? 'Unknown' }}</td>
                                                <td>{{ $d->created_at }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>          
                        @endif
                    </div>
                </div>
            </div>
        </div>
    
    </section>


    <script src="{{app_url()}}/assets/admin/js/jquery.js"></script>
    <script src="{{app_url()}}/assets/admin/js/bootstrap.min.js"></script>
    <script src="{{app_url()}}/assets/vendor/datatables/datatables.min.js"></script>
    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="{{app_url()}}/assets/admin/js/jquery.backstretch.min.js"></script>
    
    <script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
        });
        $('.dataTables_length select').addClass('form-control');
        $('.dataTables_length select').wrap('<div class="input-group"></div>');
        $('.dataTables_length select').before('<span class="input-group-addon"><i class="fa fa-bars"></i></span>');
    });
</script>                                          

    <script>
        $.backstretch("{{app_url()}}/assets/admin/img/stf-full-form.jpg", {pixelRatio: 2});
    </script>


  </body>
</html>

