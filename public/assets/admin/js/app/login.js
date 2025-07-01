$(document).on('click','.btn-send-otp', function(){
	/*alert('1');*/
    var udin_phone = getValById("udin_phone");
    var params = { udin_phone: udin_phone };
	
    $.ajax({
        url: BASE_URL + "/admin/generate-login-phone-otp",
        type: "post",
        data: params,
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false,
        success: function (response) {

            if (!response.error) {
                $('.phone-div').removeClass('show').addClass('hide');
                $('.otp-div').removeClass('hide').addClass('show');
                showSnacBar(response.message, 'success');
            }else{
              //  $('.otp-div').removeClass('show').addClass('hide');
              //  $('.phone-div').removeClass('hide').addClass('show');
                showSnacBar(response.message, 'error');
            }
        },
    });
})


$(document).on('click','.btn-verify-otp', function(){

    var udin_phone = getValById("udin_phone");
    var udin_otp = getValById("udin_otp");


    var params = { udin_phone: udin_phone, udin_otp: udin_otp };
    $.ajax({
        url: BASE_URL + "/admin/verify-login-phone-otp",
        type: "post",
        data: params,
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false,
        success: function (response) {

            if (!response.error) {
                showSnacBar(response.message, 'success');
                window.location.href= BASE_URL + '/admin/dashboard';
            }else{
                showSnacBar(response.message, 'error');
            }
        },
    });
})


