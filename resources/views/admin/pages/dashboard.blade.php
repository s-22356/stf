@extends('admin.layouts.public')
@section('page_title', 'Dashboard') @section('content')
<section id="main-content">
    <section class="wrapper">

    <div class="main-panel" style="margin-top:2%">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">UDIN</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><b>{{ $page_tile }}</b></li>
                    </ol>
                </nav>
                
              </div>
            </div>
          </div>
          @if(Session::get('auth_type') != "auth_user")
            <div class="row"  style="margin-left:30%;">
              <div class="col-md-6 grid-margin stretch-card">
                <div class="card shadow-lg" style="border-radius: 7px;height:100%;padding:3%;text-align:center;">
                  <div class="card-body p-4">
                    <label for="authIDP"><b>Select Authorised Person</b></label>
                    <select class="form-control" id="authIDP">
					  <option>Select</option>
                      <option value="ALL">ALL</option>
                      @foreach($authId as $authPersonId)
                        <option value="{{$authPersonId->stf_auth_id}}">{{$authPersonId->stf_auth_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
          @endif
          <div class="row">
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card shadow-lg" style="border-radius: 7px;height:100%;padding:3%;">
                <div class="card-body p-4">
                  <p class="card-title text-md-center text-xl-left"><b>Total Notice U/S 35 B.N.S.S Generated</b></p>
                  <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"  id="total_notice35_generate">{{$data->total_notice35_generate ? $data->total_notice35_generate : 0}}</h3>
                    <i class="ti-calendar icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                  </div>  
                  <!-- <p class="mb-0 mt-2 text-danger">0.12% <span class="text-black ms-1"><small>(30 days)</small></span></p> -->
                </div>
              </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
            <div class="card shadow-lg" style="border-radius: 7px;height:100%;padding:3%;">
                <div class="card-body p-4">
                  <p class="card-title text-md-center text-xl-left"><b>Total Notice U/S 94 B.N.S.S Generated</b></p>
                  <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" id="total_notice94_generate">{{$data->total_notice94_generate ? $data->total_notice94_generate : 0}}</h3>
                    <i class="ti-user icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                  </div>  
                  <!-- <p class="mb-0 mt-2 text-danger">0.47% <span class="text-black ms-1"><small>(30 days)</small></span></p> -->
                </div>
              </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card shadow-lg" style="border-radius: 7px;height:100%;padding:3%;">
                <div class="card-body p-4">
                  <p class="card-title text-md-center text-xl-left"><b>Total Notice U/S 179 B.N.S.S Generated</b></p>
                  <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" id="total_notice179_generate">{{$data->total_notice179_generate ? $data->total_notice179_generate : 0}}</h3>
                    <i class="ti-agenda icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                  </div>  
                  <!-- <p class="mb-0 mt-2 text-success">64.00%<span class="text-black ms-1"><small>(30 days)</small></span></p> -->
                </div>
              </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
             <div class="card shadow-lg" style="border-radius: 7px;height:100%;padding:3%;">
                <div class="card-body p-4">
                  <p class="card-title text-md-center text-xl-left"><b>Total Notice U/S 67 B.N.S.S Generated</b></p>
                  <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" id="total_notice67_generate">{{$data->total_notice67_generate ? $data->total_notice67_generate : 0}}</h3>
                    <i class="ti-layers-alt icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                  </div>  
                  <!-- <p class="mb-0 mt-2 text-success">23.00%<span class="text-black ms-1"><small>(30 days)</small></span></p> -->
                </div>
              </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
             <div class="card shadow-lg" style="border-radius: 7px;height:100%;padding:3%;">
                <div class="card-body p-4">
                  <p class="card-title text-md-center text-xl-left"><b>Total Udin Generated</b></p>
                  <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" id="total_udin">{{$data->total_udin ? $data->total_udin : 0}}</h3>
                    <i class="ti-layers-alt icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                  </div>  
                  <!-- <p class="mb-0 mt-2 text-success">23.00%<span class="text-black ms-1"><small>(30 days)</small></span></p> -->
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
             <div class="card shadow-lg" style="border-radius: 7px;height:100%;padding:3%;">
                <div class="card-body p-4">
                <p class="card-title text-md-center text-xl-left"><b></b></p>
                  <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"><canvas id="myChart" style="width:100%;max-width:700px;"></canvas></h3>
                    <i class="ti-layers-alt icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                  </div>  
                </div>
              </div>
            </div>
            
          </div>
       
    </section>
</section><br><br><br><br><br><br><br>

@stop


@section('scripts')
    <script>
        $(document).ready(function () {
            var ctx = document.getElementById("myChart").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ["Notice 35", "Notice 67", "Notice 94", "Notice 179"],
                    datasets: [{
                        backgroundColor: [
                            'rgb(88, 5, 23)',
                            'rgb(6, 160, 34)',
                            'rgba(217, 159, 12, 0.92)',
                            'rgb(16, 66, 128)',
                        ],
                        data: 
                        [
                            {{$data->total_notice35_generate ? $data->total_notice35_generate : 0}},
                            {{$data->total_notice67_generate ? $data->total_notice67_generate : 0}},
                            {{$data->total_notice94_generate ? $data->total_notice94_generate : 0}},
                            {{$data->total_notice179_generate ? $data->total_notice179_generate : 0}},
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Notice Categorized by UDIN Generation'
                    }
                }
            });
        });
    </script>
@stop