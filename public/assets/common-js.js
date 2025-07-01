function getValById(id) {
    var val = $("#" + id).val();
    return $.trim(val);
}



function hideSnackBar() {
    //hide flash message
    if ($(".flash-message").length > 0) {
        setTimeout(() => {
            $(".flash-message").fadeOut("slow");
        }, 7000);
    }
}

function closeFlashMsg(e) {
    if (e.which == 13) {
        $(".flash-message").fadeOut("slow");
    }
}

$(document).on("click", ".flash-msg-close", function () {
    $(".flash-message").fadeOut("slow");
});


function showSnacBar(message, type) {
    
    if ($(".flash-message").length > 0) {
        $(".flash-message").remove();
    }
    var classname = "flash-message alert";
    if (type == "success") {
        // classname += " alert-success";
    } else if (type == "error") {
        // classname += " alert-danger";
    } else if (type == "warning") {
        // classname += " alert-warning";
    }
    var $close = $("<i>", { class: "las la-times flash-msg-close" });
    var $div = $("<div>", { class: classname, role: "alert" });
    var $closeBtn = $(
        '<button class="flash-ok-button swal2-cancel swal2-styled" onkeypress="closeFlashMsg(event)">OK</button>'
    );
    $div.html(message).append($close, $closeBtn);
    $("body").append($div);
    $(".flash-ok-button").focus(); 

    hideSnackBar();
}



$(document).on("click", ".flash-ok-button", function () {
    $(".flash-message").fadeOut("slow");
});

//validate mobile num
function validateMobile(mobilenum) {
    var is_valid = mobilenum.match("[0-9]{10}");
    if (null === is_valid) {
        return false;
    } else {
        return true;
    }
}


function showError(ele, msg) {
    $(".text-danger").html("");
    $("#" + ele).addClass("is-invalid");
    $("#" + ele)
        .parent()
        .append('<small class="form-error text-danger">' + msg + "</small>");

    error = true;
}

//hide error message
function hideError(ele) {
    $("#" + ele).removeClass("is-invalid");
    $("#" + ele)
        .parent()
        .find($(".form-error"))
        .remove();
}


$(document).ready(function () {
    $("#complinant_details").hide();
    $("#document_dtls").hide();
    $(".document_name").attr('disabled','disabled');
    $("#complinant_name").attr('disabled','disabled');
    $("#complinant_father_name").attr('disabled','disabled');
    $("#complinant_address").attr('disabled','disabled');   
    $(document).ajaxStart(function () {
       // Show loading image
       $("#page_loader").show();
    });
 
    $(document).ajaxStop(function () {
       // Hide loading image
       $("#page_loader").hide();
    });
       
 });


 $('#btn-udin-logout').click(function(){
    // alert("12");
    $.ajax({
        url: BASE_URL + "/admin/logout",
        type: "get",
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false,
        success: function (response) {
            if (!response.error) {

                showSnacBar(response.message, 'success');
                setTimeout(function() {
                    window.location.href= BASE_URL + '/admin/login';
                  }, 2000);
            }else{
                showSnacBar(response.message, 'error');
            }
        },
    });
});