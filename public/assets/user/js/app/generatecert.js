$(".generate-otp").click(function () {
    
    var error = false;
    var username = getValById("username");
    /*alert(username);*/
    if (username == "" || !validateMobile(username)) {
        error = true;
        showError("username_container", "Please enter valid mobile number");
    }
    if (!error) {
        var params = "username=" + username;
        $.ajax({
            url: BASE_URL + "/certificate-download",
            type: "post",
            data: params,
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $("#username").attr("readonly", true);
                $(".generate-otp").html("Checking...").attr("disabled", true);
            },
            success: function (response) {
                /*console.log(response);*/
                if (response.error) {
                    $("#username").attr("readonly", false);
                    $(".generate-otp").html("Verify").attr("disabled", false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    /*showSnacBar(response.message, "error");*/
                } else {
                    $("#username").attr("readonly", true);
                    $(".generate-otp-area").hide();
                    $(".validate-otp-area").show();
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    /*showSnacBar(response.message, "success");*/
                }
            },
        });
    }

});


$(document).on("click", ".validate-otp", function () {
    var error = false;
    var mobile_no = getValById("username");
    var otp_num = getValById("otp");
    /*alert(mobile_no);*/

    if (otp_num == "" || otp_num.length < 6) {
        error = true;
        showError("otp_num", "Please enter valid OTP");
    }

    if (!error) {
        var params = {
            mobile_no: mobile_no,
            otp_num: otp_num
        };
        $.ajax({
            url: BASE_URL + "/validate-otp",
            type: "post",
            data: params,
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            cache: false,
            beforeSend: function () {
                $(".validate-otp").attr("disabled", true);
            },
            success: function (response) {
               
                if (response.error) {

                    /*$(".generate-otp").removeClass("hide").addClass("show");
                    $(".otp_response").removeClass("show").addClass("hide");*/
                    $(".validate-otp").attr("disabled", false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    /*console.log(response.data);*/
                    $(".generate-otp-wrap").hide();
                    $(".validate-otp-area").hide();
                    $(".table-certificate").show();
                    $.each(response.data, function (key, value) {
                        /*console.log(value);*/
                             $('#certificateid').append('<tr>' +
                                '<td colspan="5">' + value.college_name + '</td>' +
                                '<td colspan="10">' + value.batch + '</td>' +
                                '<td colspan="10">' + value.udin   + '</td>'+
                                '<td colspan="10">'+ value.registration_no + '</td>'+
                                `<td colspan="10"><a data-udin="${value.udin}" class=" btn btn-primary  btn-utin-download" aria-hidden="true">DOWNLOAD</a></td>` +
                                '</tr>');
                    });
                    Swal.fire({
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            },
        });
    }
});


$(document).on("click", ".btn-utin-download", function () {
    /*alert(1);*/
    var ref = $(this).data("udin");
    /*alert(ref);*/
    var params = { ref: ref };

    $.ajax({
        url: BASE_URL + "/download-utin-doc",
        type: "post",
        data: params,
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        beforeSend: function () {
              $(".btn-utin-download").attr("disabled", true);
        },
        success: function (response) {
            //console.log(response);
            //alert(1);
            if (response.error) {
                $(".download-udin").attr("disabled", false);
                if(response.status=="UC"){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.status_text,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }else if(response.status=="UE"){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.status_text,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.status_text,
                        showConfirmButton: false,
                        timer: 2000
                    });
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Downlaoded Successfully.',
                        showConfirmButton: false,
                        timer: 2000
                    });

            }
        },
    });
});