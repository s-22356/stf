 <!--footer start-->
 <footer class="site-footer">
    <div class="text-right">
        <a target="_blank" title="SNLTR, External Link that opens in a new window" href="https://www.nltr.org/"><b>Developed by <br>Department of Information Technology & Electronics <br>Government of West Bengal.</b>&nbsp;&nbsp;&nbsp;&nbsp;<span target="_blank" class="go-top"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM199.4 312.6c31.2 31.2 81.9 31.2 113.1 0c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9c-50 50-131 50-181 0s-50-131 0-181s131-50 181 0c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0c-31.2-31.2-81.9-31.2-113.1 0s-31.2 81.9 0 113.1z"/></svg></span></a>
        
    </div>
</footer>
<!--footer end-->
<script>
   var BASE_URL = "{{ env('APP_URL') }}";
 </script>
 <!-- js placed at the end of the document so the pages load faster -->
 <script src="{{app_url()}}/assets/admin/js/jquery.js"></script>
 <script src="{{app_url()}}/assets/admin/js/jquery-1.8.3.min.js"></script>
 <script src="{{app_url()}}/assets/admin/js/bootstrap.min.js"></script>
 <script class="include" type="text/javascript" src="{{app_url()}}/assets/admin/js/jquery.dcjqaccordion.2.7.js"></script>
 <script src="{{app_url()}}/assets/admin/js/jquery.scrollTo.min.js"></script>
 <!-- <script src="{{app_url()}}/assets/admin/js/jquery.nicescroll.js" type="text/javascript"></script> -->
 <script src="{{app_url()}}/assets/admin/js/jquery.sparkline.js"></script>
 <script src="{{app_url()}}/assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
 <script src="{{app_url()}}/assets/vendor/datatables/datatables.min.js"></script>
 <!--common script for all pages-->
 <script src="{{app_url()}}/assets/admin/js/common-scripts.js"></script>

 <script type="text/javascript" src="{{app_url()}}/assets/admin/js/gritter/js/jquery.gritter.js"></script>
 <script type="text/javascript" src="{{app_url()}}/assets/admin/js/gritter-conf.js"></script>

 <!--script for this page-->
 <script src="{{app_url()}}/assets/admin/js/sparkline-chart.js"></script>
 <script src="{{app_url()}}/assets/admin/js/zabuto_calendar.js"> </script>
 <script src="{{app_url()}}/assets/common-js.js"> </script>

 <!-- scripts for chart -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
@php
    $module_arr =   array('upload','udin');
@endphp


@if (in_array($module,$module_arr))
    <script src="{{app_url()}}/assets/admin/js/app/upload.js"></script>
@else
    <script src="{{app_url()}}/assets/admin/js/app/auth_person_req.js"></script>
@endif
{{--
 <script type="text/javascript">
     $(document).ready(function() {
         var unique_id = $.gritter.add({
             // (string | mandatory) the heading of the notification
             title: 'Welcome to Dashgum!',
             // (string | mandatory) the text inside the notification
             text: 'Hover me to enable the Close Button. You can hide the left sidebar clicking on the button next to the logo. Free version for <a href="http://blacktie.co" target="_blank" style="color:#ffd777">BlackTie.co</a>.',
             // (string | optional) the image to display on the left
             image: '/assets/admin/img/ui-sam.jpg',
             // (bool | optional) if you want it to fade out on its own or just sit there
             sticky: true,
             // (int | optional) the time you want it to be alive for before fading out
             time: '',
             // (string | optional) the class name you want to apply to that specific message
             class_name: 'my-sticky-class'
         });

         return false;
     });
 </script> --}}

 <script type="application/javascript">
     $(document).ready(function () {
         $("#date-popover").popover({html: true, trigger: "manual"});
         $("#date-popover").hide();
         $("#date-popover").click(function (e) {
             $(this).hide();
         });

         $("#my-calendar").zabuto_calendar({
             action: function () {
                 return myDateFunction(this.id, false);
             },
             action_nav: function () {
                 return myNavFunction(this.id);
             },
             ajax: {
                 url: "show_data.php?action=1",
                 modal: true
             },
             legend: [
                 {type: "text", label: "Special event", badge: "00"},
                 {type: "block", label: "Regular event", }
             ]
         });
     });


     function myNavFunction(id) {
         $("#date-popover").hide();
         var nav = $("#" + id).data("navigation");
         var to = $("#" + id).data("to");
         console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
     }


 </script>
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