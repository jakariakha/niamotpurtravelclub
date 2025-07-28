var member_id;

$(document).ready(function () {
  //update member
  $('#update_member').click(function(e){
    e.preventDefault;
    var csrfToken = $('#csrfToken').val();
    var memberId = $('#memberId').val();
    var memberName = $('#memberName').val();
    var memberEmail = $('#memberEmail').val();
    var memberOldEmail = $('#memberOldEmail').val();
    var memberUid = $('#memberUid').val();
    var memberDepositAmount = $('#memberDepositAmount').val();
    var memberSetAmount = $('#memberSetAmount').val();
    var memberDueMonth = $('#memberDueMonth').val();
    var memberDueAmount = $('#memberDueAmount').val();
    var memberJoiningDate = $('#memberJoiningDate').val();
    var memberPosition = $('#memberPosition').val();
    console.log(memberPosition);
    if(memberEmail === memberOldEmail){
        memberUpdate();
    } else{
      $.ajax({
        type : 'POST',
        url : 'update.php',
        data : {
          'find_email': true,
          'in_member_update' : true,
          'csrf_token' : csrfToken,
          'member_email' : memberEmail,
        },
        success: function(response){
          if(response === 'emailFound'){
              showToast(response);
          } else{
            memberUpdate();
          }
        }
      })
    }
    

    function memberUpdate(){
      $.ajax({
        type : 'POST',
        url : 'update.php',
        data : {
          'update_member' : true,
          'csrf_token' : csrfToken,
          'member_id' : memberId,
          'member_name' : memberName,
          'member_email' : memberEmail,
          'member_uid' : memberUid,
          'member_deposit_amount' : memberDepositAmount,
          'member_set_amount' : memberSetAmount,
          'member_due_amount' : memberDueAmount,
          'member_due_month' : memberDueMonth,
          'member_joining_date' : memberJoiningDate,
          'member_position' : memberPosition,
        },
        success: function(response){
          if(response === 'success'){
            //console.log(response);
            showToast(response);
            setTimeout(function(){
              window.location.replace('fetch_members_info.php');
            }, 1500);
          } else{
             //console.log(response);
          }
        }
      })
    }
  })

  
  // For Delete Member
  $('.showDeleteModal').click(function(e) {
    e.preventDefault();
    member_id = $(this).closest('tr').find('.member_id').text();
    $('#deleteModal').modal('show');

    $('#deleteMember').click(function(e) {
      e.preventDefault();
      var csrf_token = $('#csrfToken').val();  
      $.ajax({
        type: "POST",
        url: "update.php",
        data: {
          'csrf_token' : csrf_token,
          'deleteMember': true,
          'member_id': member_id
        },
        success: function(response) {
          if(response === 'deleteSuccess'){
            showToast(response);
            setTimeout(function(){
              window.location.replace('fetch_members_info.php');
            }, 1500);
            $('#deleteModal').modal('hide');
          }
        }
      });
    });
  });

});

//For toast
function showToast(status){
  const notyf = new Notyf({
    duration: 2000, 
    position: { x: 'center', y: 'top' },
    dismissble: true,
});

if(status === "success"){
  notyf.success('Member Update Success!');
} else if(status === "failed"){
  notyf.error('Member Update Failed!');
} else if(status === 'deleteSuccess'){
  notyf.success('Member Delete Success!');
} else if(status === 'emailFound'){
  notyf.error('Member Already Exists!');
}
}
