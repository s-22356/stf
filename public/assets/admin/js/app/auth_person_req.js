$(document).on('click','.send-auth-req',function(){

    var error =  false;
    var auth_phone = getValById('auth_ph_mobile');
    if (auth_phone == "" || auth_phone.length < 10) {
        error = true;
        showError("auth_phone", "Please enter valid phone number");
      }
    var params = { ref: auth_phone };

    $.ajax({
        url: BASE_URL + "/admin/udin/send-auth-request-data",
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
                $("#auth_ph_mobile").val("");
            } else {
                showSnacBar(response.message, 'success');
                setTimeout(function() {
                    location.reload();
                }, 5000);
            }
        }
    });
    
   
});

$(document).on('click','.btn-resend-auth',function(){

    var error =  false;
    var auth_phone = $(this).data('ph');
    
    if (auth_phone == "" || auth_phone.length < 10) {
        error = true;
        showError("auth_phone", "Please enter valid phone number");
      }
    var params = { ref: auth_phone };

    $.ajax({
        url: BASE_URL + "/admin/udin/send-auth-request-data",
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
                showSnacBar(response.message, 'success');
                setTimeout(function() {
                    location.reload();
                }, 5000);
            }
        }
    });
    

});


$(document).on('click','.btn-delete-auth',function(){

    var error =  false;
    var auth_phone = $(this).data('ph');
    
    if (auth_phone == "" || auth_phone.length < 10) {
        error = true;
        showError("auth_phone", "Please enter valid phone number");
      }
    var params = { ref: auth_phone };

    $.ajax({
        url: BASE_URL + "/admin/udin/delete-auth-request-data",
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
                showSnacBar(response.message, 'success');
                setTimeout(function() {
                    location.reload();
                }, 5000);
            }
        }
    });
    

});


$(document).on('click','.verify_req_auth_ph',function(){


    var error =  false;
    var req_phone   =   getValById('ph_no');
    var secret_id   =   getValById('secret_id'); 
    
    hideError('ph_no');
    hideError('secret_id');

    if (req_phone == " " || req_phone.length < 10) {
        error = true;
        showError("ph_no", "Please enter valid phone number");
    }
    
    if(secret_id == " " || secret_id.length < 6){
        error = true;
        showError("secret_id", "Please enter valid secret key");
    }

    if(!error){


        var params = { 
            req_phone: req_phone,
            secret_id:secret_id
        };

        $.ajax({
            url: BASE_URL + "/admin/verify-auth-request-data",
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
                    $('#myModal input').val('');
                    $('#ph_no').val('');
                    $('#secret_id').val('');
                    setTimeout(function() {
                        location.reload();
                    }, 5000);
                }else{
                    showSnacBar(response.message, 'success');
                    $("#adhr_refId").val(response.refId);
                    $("#adhr_asct_ph").val(response.adhr_asct_ph);
                    $('.otp-div').addClass('hide').removeClass('show');
                    $('#otp').attr("disabled", true);
                    var aadhaar_modal = $('#aadhaarModal').modal();
                    aadhaar_modal.show();
                }
            }
        });

    }

});

$(document).on('click','.auth-aadhar-otp',function(){

    var error =  false;
    var aadhar_no    =   getValById('aadhar_no');
    var adhr_refId   =   getValById('adhr_refId');

   
    var params = { 
        aadhar_no:aadhar_no,
        adhr_refId:adhr_refId
    };
    if(!error){
        $.ajax({
            url: BASE_URL + "/admin/generate-auth-adhr-otp",
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
                    $('#myModal input').val('');
                    $('#ph_no').val('');
                    $('#secret_id').val('');
                    setTimeout(function() {
                        location.reload();
                    }, 5000);
                } else {
                    showSnacBar(response.message, 'success');
                    $('<input type="hidden" id="adhr_trans_id" name="adhr_trans_id">').appendTo('#aadhaarModal .modal-body');
                    $('#adhr_trans_id').val(response.adhr_transaction_id);
                    $("#adhr_refId").val(response.authPersonRefId);
                    $('.otp-div').addClass('show').removeClass('hide');
                    $('.auth-verify-aadhar-otp').addClass('show').removeClass('hide');
                    $('#otp').attr("disabled", false);
                    $('.auth-aadhar-otp').addClass('hide').removeClass('show');
                    $('.auth-aadhar-otp').attr("disabled", true);
                    $('.auth-verify-aadhar-otp').addClass('show').removeClass('hide');
                    
                }
            }
        });
    }
});

$(document).on('click','.auth-verify-aadhar-otp',function(){
    
    var error =  false;
    var aadhar_asct_ph  =   getValById('adhr_asct_ph');
    var adhr_trans_id   =   getValById('adhr_trans_id'); 
    var aadhar_otp   =   getValById('otp');
    var adhr_refId  =   getValById('adhr_refId');
   
    var params = { 
        aadhar_asct_ph  : aadhar_asct_ph,
        adhr_trans_id   : adhr_trans_id,
        aadhar_otp      : aadhar_otp,
        adhr_refId      : adhr_refId
    };

    $.ajax({
        url: BASE_URL + "/admin/verify-auth-aadhaar-otp",
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
                $('#myModal input').val('');
                $('#ph_no').val('');
                $('#secret_id').val('');
                setTimeout(function() {
                    location.reload();
                }, 5000);
            } else {
                showSnacBar(response.message, 'success');
                $('#myModal input').val('');
                $('#ph_no').val('');
                $('#secret_id').val('');
                setTimeout(function() {
                    location.reload();
                }, 5000);
            }
        }
    });
});

//refresh the page if modal closed
$("#aadhaarModal").on("hidden.bs.modal", function () {
    $('#aadhaarModal .modal-body').html('');
    $('#ph_no').val('');
    $('#secret_id').val('');
    window.location.reload();
});

$(document).on('click','.auth-actv-dactv-status',function(){

    var error =  false;
    var status   =   $(this).data('id');
    var req_ph   =   $(this).data('phone');
    var params = { 
        status: status,
        req_ph:req_ph
    };

    $.ajax({
        url: BASE_URL + "/admin/udin/auth-actv-dactv-status",
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
                setTimeout(function() {
                    location.reload();
                }, 5000);
            }else{
                showSnacBar(response.message, 'success');
                setTimeout(function() {
                    location.reload();
                }, 5000);
            }
        }
    });
});



$("#authIDP").change(function(){
    var authID = getValById('authIDP');
    $.ajax({
        url: BASE_URL + "/admin/dashboard_individual",
        type: "post",
        data: {authID:authID},
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);
            if (!response.error) {

                var message = response.data;
                const Toast = Swal.mixin({
                    toast: true,
                    position: "center",
                    showConfirmButton: false,
                    background: '#ffffff', // Change background color
                    allowOutsideClick: true,
                    showCloseButton: true
                });
                Toast.fire({
                    html: `<div class="col-md-12">
                                <h3><strong>`+response.auth_name+`</strong></h3>
                                <canvas id="myChart_individual" width="400" height="400"></canvas>
                            </div>`,
                    didOpen: () => {
                        var ctx = document.getElementById("myChart_individual").getContext('2d');
                        new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: ["Notice 35", "Notice 67", "Notice 94", "Notice 179"],
                                datasets: [{
                                    backgroundColor: [
                                        'rgb(88, 5, 23)',
                                        'rgb(6, 160, 34)',
                                        'rgba(217, 159, 12, 0.92)',
                                        'rgb(16, 66, 128)',
                                    ],
                                    data: [
                                        '4','6','8','10'
                                    ],
                                    // [
                                    //     message.udin_for_notice35,
                                    //     message.udin_for_notice67,
                                    //     message.udin_for_notice94,
                                    //     message.udin_for_notice179,
                                    // ],
                                    borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                    ],
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                title: {
                                    display: true,
                                    text: 'Notice Categorized by UDIN Generation'
                                }
                            }
                        });
                    }
                });
                


                
            }

        }
    });
});