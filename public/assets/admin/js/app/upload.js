$(document).on('click','.btn-aadhar-generate-otp', function(){
    var udin_aadhaar = getValById("udin_aadhaar");
    var params = { udin_aadhaar: udin_aadhaar };
    $.ajax({
        url: BASE_URL + "/admin/generate-aadhar-otp",
        type: "post",
        data: params,
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false,
        success: function (response) {
            console.log(response);
            if (!response.error) {
                $('.addhar_div').removeClass('show').addClass('hide');
                $('.addhar_otp_validate_div').removeClass('hide').addClass('show');
                $('#transaction_id').val(response.transaction);
                $('#addhar_no').val(response.aadhar_no);
                showSnacBar(response.message, 'success');
            }else{
             //   $('.otp-div').removeClass('show').addClass('hide');
             //   $('.phone-div').removeClass('hide').addClass('show');
                showSnacBar(response.message, 'error');
            }
        },
    });
});



$(document).on('click','.btn-aadhar-verify-otp', function(){
    var transaction_id = getValById("transaction_id");
    var addhar_otp = getValById("addhar_otp");
    var addhar_no = getValById("addhar_no");

    var params = { transaction_id: transaction_id , addhar_otp:addhar_otp, addhar_no:addhar_no };
    $.ajax({
        url: BASE_URL + "/admin/verify-aadhar-otp",
        type: "post",
        data: params,
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false,
        success: function (response) {
            
            if (!response.error) {
                Swal.fire({
                    title: "Choose anyone of them?",
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: "Manual",
                    denyButtonText: "Automatic"
                  }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        sessionStorage.setItem("ProcessItem", "2");
                        window.location.reload();
                    } else if (result.isDenied) {
                        sessionStorage.setItem("ProcessItem", "1");
                        window.location.reload();
                    }
                  });
                
            }else{
             //   $('.otp-div').removeClass('show').addClass('hide');
             //   $('.phone-div').removeClass('hide').addClass('show');
                showSnacBar(response.message, 'error');
            }
        },
    });
});


$(document).on('click','.btn-generate-udin',function(){

    var id   =   $(this).data('id');
    $('#id').val(id);
    var params = { id: id };
    // $('#page_loader').show();
    $.ajax({
        url: BASE_URL + "/admin/udin/generate-certificate",
        type: "post",
        data: params,
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        beforeSend: function () {
             $(".btn-generate-udin").attr("disabled", true);
        },
        success: function (response) {
            console.log(response);
            if (response.error) {
                $(".btn-generate-udin").attr("disabled", false);
                showSnacBar(response.message, 'error');
                if(response.e_code  ==  'MRT_00001' && response.message ==  'Invalid random parameter'){
                    
                    $('.btn-generate-udin').click();
                }
               /* setTimeout("redirectToList()", 3000);*/
            } else {
                $(".btn-generate-udin").attr("disabled", false);
                showSnacBar(response.message, 'success');
                window.location.reload();
            }
        },
        complete: function() {
            // Hide loader after AJAX request is complete
            // $('#page_loader').hide();
        }
    });
 });
 
$(document).ready(function(){
    /*alert(sessionStorage.getItem("ProcessItem") );*/
    if(sessionStorage.getItem("ProcessItem")  ==  "1"){
        /*alert('1');*/
        clickButtonsSequentially();
    }
});

// Simulate clicking each button with class 'btn-generate-udin' after AJAX success
function clickButtonsSequentially() {
    var buttons = $('.btn-generate-udin');
    var index = 0;

    function clickNextButton() {
        /*alert('1');*/
        if (index < buttons.length) {
            var button = buttons.eq(index);
            button.click();
            index++;
            // Delay before clicking the next button (adjust as needed)
            setTimeout(clickNextButton,  15000); // 1000 milliseconds = 1 second
        }
    }
    clickNextButton(); // Start the process
}


$(document).on('click','.btn-udin-download',function(){
   /*alert(1);*/
    var ref = $(this).data("udin");
    /*alert(ref);*/
    var params = { ref: ref };

    $.ajax({
        url: BASE_URL + "/admin/udin/download-udin-doc",
        type: "post",
        data: params,
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        beforeSend: function () {
              $(".btn-udin-download").attr("disabled", true);
        },
        success: function (response) {
            //console.log(response);
            //alert(1);
            if (response.error) {
                $(".download-udin").attr("disabled", false);
                if(response.status=="UC"){
                    showSnacBar(response.status_text, "error");
                }else if(response.status=="UE"){
                    showSnacBar(response.status_text, "error");
                }else{
                    showSnacBar(response.status_text, "error");
                }
                
               /* setTimeout("redirectToList()", 3000);*/
            } else {

                
                var link = document.createElement("a");
                document.body.appendChild(link);
                link.setAttribute("type", "hidden");
                link.href = response.data_original;
                link.download = response.filename; //response.filename;
                link.click();
                document.body.removeChild(link);
                

            }
        },
    });
    
});


$(document).on('click','.btn-get-udin',function(){
    var id  =   $(this).data('id');
    var params = { ref: id };

    $.ajax({
        url: BASE_URL + "/admin/udin/get-udin-no",
        type: "post",
        data: params,
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            if (response.error) {
                showSnacBar(response.message, 'error');

            } else {
                window.location.reload();
                showSnacBar(response.message, 'success');
            }
        }
    });
});

// $('#select-generate-cert').click(function() {
//     // Selecting first 10 checkboxes
//     $('.multi-generate-checkbox:lt(5)').prop('checked', true);
// });

// $('#generate-cert-data').click(function(){
//     var generateIdArray = [];
//     $('.multi-generate-checkbox:checked').each(function() {
//         generateIdArray.push($(this).data('id'));
//     });
//     /*console.log(generateIdArray);*/

//     if(generateIdArray.length!=0){
//         var params = { ref: generateIdArray };
//         $.ajax({
//             url: "/admin/udin/generate-multiple-data",
//             type: "post",
//             data: params,
//             dataType: "json",
//             headers: {
//                 "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//             },
//             cache: false,
//             success: function (response) {
//                 if(response.error){
//                     console.log(response);
//                     if(response.e_code  ==  'MRT_00001' && response.message ==  'Invalid random parameter'){
                         
//                         $('#generate-cert-data').click();
//                     }else{
//                         showSnacBar(response.message, 'error');
//                         window.location.reload();
//                     }
//                 }else{
//                     window.location.reload();
//                     showSnacBar(response.message, 'success');
//                 }
//             }
//         });
//     }
// });

$('#selectBtn').click(function() {
    // Selecting first 10 checkboxes
    $('.multi-download-checkbox:lt(10)').prop('checked', true);
});

$('#downloadBtn').click(function() {
    var udinArray = [];
    // Getting UDIN numbers of selected checkboxes
    $('.multi-download-checkbox:checked').each(function() {
        udinArray.push($(this).data('udin'));
    });
    
    if(udinArray.length!=0){
        var params = { ref: udinArray };
        $.ajax({
            url: BASE_URL + "/admin/udin/download-multiple-doc",
            type: "post",
            data: params,
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            cache: false,
            success: function (response) {
                if(response.error){
                    showSnacBar(response.message, "error");
                }else{
                    /*var base64length    =   response.data_original.length;*/
                    var b = response.data_original;
                    for(var i=0;i<b.length;i++){
                        var link = document.createElement("a");
                        document.body.appendChild(link);
                        link.setAttribute("type", "hidden");
                        link.href = b[i].doc_base64;
                        link.download = b[i].udin; //response.filename;
                        link.click();
                        document.body.removeChild(link);
                    }
                    window.location.reload();
                }
            }
        });
        /*console.log(JSON.stringify(params));*/
    }else{
        alert('please select checkbox');
    }
    
    // Here you can perform further actions with the UDIN numbers, such as downloading
});


$("#nd_notice_id").change(function(){
    var notice_id = $(this).val();
    $('#notice_id').val(notice_id);

    // Hide all sections by default
    $("#complinant_details").hide();
    $("#document_dtls").show();
    $("#accused_appearence_dtls").hide();

    // Disable all fields by default
    $("#complinant_name, #complinant_father_name, #complinant_address, #accused_appearing_date, #appearing_time, #accused_appearing_place").prop('disabled', true);

    // Show/hide sections and enable/disable fields based on notice_id
    switch (notice_id) {
        case "2":
            $("#accused_appearence_dtls").hide();
            $("#document_dtls").show();
            break;
        case "3":
            $("#complinant_details").show();
            $("#accused_appearence_dtls").show();
            $("#document_dtls").show();
            $(".document_name, #complinant_name, #complinant_father_name, #complinant_address,#accused_appearing_date, #appearing_time, #accused_appearing_place").prop('disabled', false);
            break;
        case "4":
            $("#complinant_details").show();
            $("#accused_appearence_dtls").show();
            $("#document_dtls").show();
            $(".document_name,#complinant_name, #complinant_father_name, #complinant_address,#accused_appearing_date, #appearing_time, #accused_appearing_place").prop('disabled', false);
            break;
        default:
            $("#accused_appearence_dtls").show();
            $("#document_dtls").show();
            $(".document_name,#accused_appearing_date, #appearing_time, #accused_appearing_place").prop('disabled', false);
    }
});

let documentCount = 1;
$('#add-more-button').click(function() {
    documentCount++;
    const newField = `
        <div class="row mb-3 document-row">
            <div class="col-md-6">
                <label for="document_details" class="form-label"><b class="form_fields">Enter Document Name ${documentCount}:</b></label>
                <textarea name="document_name[]" class="form-control document_name"></textarea>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-button">Remove</button>
            </div>
        </div>
    `;
    $('#additional-document-names').append(newField);
});

$(document).on('click', '.remove-button', function() {
    $(this).closest('.document-row').remove();
});

$(document).on('click', '#aadhaar_concent', function() {
    if ($(this).is(':checked')) {
        $('.btn-aadhar-generate-otp').prop('disabled', false);
    } else {
        $('.btn-aadhar-generate-otp').prop('disabled', true);
    }
});
