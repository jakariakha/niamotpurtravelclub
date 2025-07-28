$(document).ready(function() {
    $('#save_profile').click(function(e) {
        e.preventDefault();
        var csrfToken = $('#csrfToken').val();
        var memberId = $('#memberId').val();
        var memberName = $('#memberName').val();
        var memberOldName = $('#memberOldName').val();
        var memberEmail = $('#memberEmail').val();
        var memberOldEmail = $('#memberOldEmail').val();

        var isValid = true;
        if(!memberEmail){
            $('#emailError').show();
            isValid = false;
        }
        if(!memberName){
            $('#nameError').show();
            isValid = false;
        }

        $('#memberEmail').on('input', function(){
            $('#emailError').hide();
        });
        
        $('#memberName').on('input', function(){
            $('#nameError').hide();
        });

        if(isValid){
            if(memberEmail === memberOldEmail){
                if(memberName != memberOldName){
                    emailVerify();
                }                
            } else{
                $.ajax({
                    type: "POST",
                    url: "update.php",
                    data: {
                        'find_email': true,
                        'in_profile_update' : true,
                        'csrf_token' : csrfToken,
                        'member_email' : memberEmail,
                        'member_id' : memberId,
                    },
                    success: function(response){
                        if(response === 'emailFound'){
                            showToast(response);                                         
                        } else{
                            emailVerify();
                        }  
                        //console.log(response); 
                    }    
                }) 
            }
             
        }
    
    function emailVerify(){
        $.ajax({
            type: "POST",
            url : "new_email_verify.php",
            data : {
                'new_email_verify' : true,
            },
            success: function(response){
                $('#editProfile').hide();
                $('#otpInputBox').html(response);
            }
        })
    }
    
    })

    $('#sendOtp').click(function(e){
        e.preventDefault();
        var memberEmail = $('#memberEmail').val();
        var csrfToken = $('#csrfToken').val();
        $.ajax({
            type : "POST",
            url : "new_email_send_otp.php",
            data : {
                'new_email_send_otp' : true,
                'csrf_token' : csrfToken,
                'member_email' : memberEmail,                              
            },
            success: function(response){
                if(response === 'otpSent'){
                    showToast(response);
                    $('#sendOtp').hide();           
                }
            }
        })
    
    })
//verify otp
$('#submitOtp').click(function(e){
    e.preventDefault();
    var csrfToken = $('#csrfToken').val();
    var memberId = $('#memberId').val();
    var memberEmail = $('#memberEmail').val();
    var memberName = $('#memberName').val();
    var submitOtp = $('#inputOtp').val();
    var isValid = true;
    if(!submitOtp){
        $('#otpError').show();
        isValid = false;
    } else if(submitOtp.length != 8){
        $('#wrongOtpError').show();
        isValid = false;
    }
    $('#inputOtp').on('input', function(){
        $('#otpError').hide();
    });
    $('#inputOtp').on('input', function(){
        $('#wrongOtpError').hide();
    });
    if(isValid){
        $.ajax({
            type : "POST",
            url : 'update.php',
            data : {
                'profile_update' : true,
                'csrf_token' : csrfToken,
                'member_id' : memberId,
                'member_email' : memberEmail,
                'member_name' : memberName,
                'submit_otp' : submitOtp
            },
            success: function(response){
                if(response == 'updateSuccess'){
                    
                    //console.log(response);
                    //$('#inputOtp').val('');
                    showToast(response);
                    setTimeout(function(){
                        window.location.replace('profile.php');                  
                    }, 1000);
                    
                } else{
                    showToast(response);
                    // console.log(response);
                }
                
            }
        })
    }

    //console.log(submitOtp+memberEmail+memberId+memberName);


})


})

//For toast
function showToast(status){
    const notyf = new Notyf({
      duration: 2000, 
      position: { x: 'center', y: 'top' },
      dismissble: true,
  });
  if(status == "success"){
    notyf.success('Update Success!');
  } else if(status == "failed"){
    notyf.error('Update Failed!');
  } else if(status == "emailFound"){
    notyf.error('Email Already Exists!');
  } else if(status == "otpSent"){
    notyf.success('OTP Sent!')
  } else if(status == "updateSuccess"){
    notyf.success('Profile Update Success!')
  } else if(status == 'updateFailed'){
    notyf.error('Profile Update Failed!')
  } else if(status == 'wrongOtp'){
    notyf.error('Wrong OTP!')
  }
  }