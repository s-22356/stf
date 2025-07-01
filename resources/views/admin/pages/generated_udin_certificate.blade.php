@extends('admin.layouts.public')
@section('page_title', $page_tile)
@section('content')
<section id="main-content">
    <section class="wrapper">

        <div class="row">
            <div class="col-lg-9 main-chart">

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="#">UDIN</a></li>
                      <li class="breadcrumb-item active" aria-current="page">{{$page_tile}}</li>
                    </ol>
                  </nav>



                  <div class="card shadow-lg" style="border-radius: 7px;height:10%;padding:1%;">
                        <div class="card-body p-2">
                            <div class="row mt">
                                <div class="col-lg-12">
                                   
                                    <h5><b class="form_fields">Download Multiple Certificates: </b>@if(sizeof($data))<button type="button" class="btn btn-lg px-5 py-2" id="selectBtn" style="background: #584090;color:white">Select All</button>
                                        <button type="button" class="btn btn-lg px-5 py-2" id="downloadBtn" style="background: #584090;color:white">Download</button>@endif
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(sizeof($data))
                    <div class="card shadow-lg" style="border-radius: 7px;height:100%;padding:3%;">
                        <div class="card-body p-4">
                            <table id="datatable" class="table table-bordered">
                                <thead>
                                    <tr>
                                    <th>Select</th>
                                    <th>Sl</th>
                                    <th>Case Fir Id</th>
                                    <th class="numeric">Diary Number</th>
                                    <th class="numeric">UDIN NO</th>
                                    <th class="numeric">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $k => $d)
                                    <tr>
                                        <td><input class="form-check-input multi-download-checkbox" type="checkbox" data-udin="{{$d->udin}}" id="multi-download"></td>
                                        <td>{{$k+1}}</td>
                                        <td>{{ $d->stf_case_fir_id}}</td>
                                        <td class="numeric">{{$d->stf_diary_no}}</td>
                                        <td class="numeric"> {{$d->udin}} <i data-udin=" {{$d->udin}}" class="fa fa-download btn-udin-download" aria-hidden="true"></i> </td>
                                        @if($d->udin==null && $d->uploaded==1 && $d->quotation_id!=null)
                                        <td><button type="button" class="btn btn-lg px-5 py-2 btn-get-udin" data-id="{{$d->id}}" style="background: #584090;color:white">Get UDIN</button></td>
                                        @else
                                            <td></td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
    

                    @else
                        <div class="card shadow-lg" style="border-radius: 7px;height:100%;padding:3%;">
                            <div class="card-body p-4">
                                <table id="datatable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Case Fir Id</th>
                                            <th class="numeric">Diary Number</th>
                                            <th class="numeric">Created At</th>
                                            <th class="numeric">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                                            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
               


            </div><!-- /col-lg-9 END SECTION MIDDLE -->



            <!-- **********************************************************************************************************************************************************
            RIGHT SIDEBAR CONTENT
            *********************************************************************************************************************************************************** -->

            
        </div>
        <!--/row -->
    </section>
</section>


@stop
