$(document).ready(function(){
    $('#login').click(function(e) {
        e.preventDefault();
        var memberEmail = $('#memberEmail').val();
        var csrfToken = $('#csrfToken').val();
        var isValid = true;
        if(!memberEmail){
            $('#emailError').show();
            isValid = false;
        }
        $('#memberEmail').on('input', function(){
            $('#emailError').hide();
        })
        if(isValid){
            $.ajax({
                type: "POST",
                url: "main.php",
                data: {
                  'login': true,
                  'member_email': memberEmail,
                  'csrf_token' : csrfToken,
                },
                success: function(response) {
                  if(response === 'emailFound') {
                    $.ajax({
                        type : 'POST',
                        url : 'send_otp.php',
                        data :{
                            'send_otp' : true,
                            'member_email' : memberEmail,
                        },
                        success: function(response){
                            if(response === 'otpSent'){
                                showToast(response);
                                setTimeout(function(){
                                    window.location.replace('verify.php');
                                }, 1000);
                            }
                        }
                    })                                   
                  } else {
                    showToast(response);
                  }
                }
              });
        }
       
      });

      $('#verify').click(function(e){
        e.preventDefault();
        var csrfToken = $('#csrfToken').val();
        var submitOtp = $('#submitOtp').val();
        var isValid = true;
        console.log(csrfToken+submitOtp+isValid);
        if(!submitOtp){
            $('#otpError').show();
            isValid = false;
        }
        $('#submitOtp').on('input', function(){
            $('#otpError').hide();
        })

        if(isValid){
            $.ajax({
                type : 'POST',
                url : 'main.php',
                data : {
                    'otp_verify' : true,
                    'submit_otp' : submitOtp,
                    'csrf_token' : csrfToken,
                },
                success: function(response){
                    if(response === 'loginSuccess'){
                        console.log(response);
                        showToast(response);
                        setTimeout(function(){
                            window.location.replace('index.php');
                        }, 1000);
                    } else if(response == 'wrongOtp'){
                        showToast(response);
                        console.log(response);
                    } else{
                        showToast(response);
                        console.log(response);
                    }
                }
            })
        }
      })
    
})

//For toast
function showToast(status){
    const notyf = new Notyf({
      duration: 2000, 
      position: { x: 'center', y: 'top' },
      dismissble: true,
  });
  if(status === 'otpSent'){
    notyf.success('OTP Sent to your Email!');
  } else if(status === "emailNotValid"){
    notyf.error('Please enter valid email.');
  } else if(status === "emailNotFound"){
    notyf.error('Email Not Found!');
  } else if(status === 'loginSuccess'){
    notyf.success('Login Successful!')
  } else if(status === 'wrongOtp'){
    notyf.error('Wrong OTP!');
  } else if(status === 'otpExpired'){
    notyf.error('OTP Expired!');
  }
  }
  