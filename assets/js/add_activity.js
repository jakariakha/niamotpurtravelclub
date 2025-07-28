$(document).ready(function(){
    $('#addNewMember').click(function(e){
        e.preventDefault();
        var csrfToken = $('#csrfToken').val();
        var newMemberName = $('#newMemberName').val();
        var newMemberEmail = $('#newMemberEmail').val();
        var newMemberUid = $('#newMemberUid').val();
        var newMemberDepositAmount = $('#newMemberDepositAmount').val();
        var newMemberSetAmount = $('#newMemberSetAmount').val();
        var newMemberDueMonth = $('#newMemberDueMonth').val();
        var newMemberDueAmount = $('#newMemberDueAmount').val();
        var newMemberJoiningDate = $('#newMemberJoiningDate').val();
        var newMemberPosition = $('#newMemberPosition').val();
        $.ajax({
            type : 'POST',
            url : 'add_activity.php',
            data :{
                'add_new_member' : true,
                'csrf_token' : csrfToken,
                'new_member_name' : newMemberName,
                'new_member_email' : newMemberEmail,
                'new_member_uid' : newMemberUid,
                'new_member_deposit_amount' : newMemberDepositAmount,
                'new_member_set_amount' : newMemberSetAmount,
                'new_member_due_amount' : newMemberDueAmount,
                'new_member_due_month' : newMemberDueMonth,
                'new_member_joining_date' : newMemberJoiningDate,
                'new_member_position' : newMemberPosition,
            },
            success: function(response){
                if(response === 'memberInserted'){
                    // console.log(response);
                    showToast(response);
                    setTimeout(function(){
                        window.location.replace('add_member.php');
                    }, 2000);
                   
                } else{
                    showToast(response);
                    // console.log(response);
                }
            }
        })
    })
})


//For toast
function showToast(status){
    const notyf = new Notyf({
      duration: 2000, 
      position: { x: 'center', y: 'top' },
      dismissble: true,
  });
  if(status == 'memberInserted'){
    notyf.success('Member Added Successfully!');
  } else if(status == 'memberInsertedFailed'){
    notyf.error('Member Added Failed!');
  } else if(status == 'emailExists'){
    notyf.error('Email Already Exists!');
  }
  }
  