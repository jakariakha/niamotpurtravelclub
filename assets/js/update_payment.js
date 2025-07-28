 // For Add money
 $(document).ready(function(){
  $('#paymentSubmit').click(function(e) {
    e.preventDefault();
    var memberEmail = $('#memberEmail').val();
    var csrfToken = $('#csrfToken').val();
    var form = document.getElementById('paymentForm');
    var paymentMethod = $('input[name="payment_method"]:checked').val();
    var trx_id = $('#trx_id').val();
    var depositAmount = $('#depositAmount').val();
    var memberPosition = $('#memberPosition').val();
    var isValid = true;
    //console.log(memberEmail+paymentMethod+trx_id+depositAmount);
    //console.log(memberPosition);
    if (!trx_id) {
      $('#trxidError').show();
      isValid = false;
    } else {
      $('#trxidError').hide();
    }

    if (!depositAmount) {
      $('#amountError').show();
      isValid = false;
    } else {
      $('#amountError').hide();
    }

    if (!paymentMethod) {
      $('#paymentMethodError').show();
      isValid = false;
    } else {
      $('#paymentMethodError').hide();
    }

    $('#trx_id').on('input', function() {
      $('#trxidError').hide();
    });

    $('#depositAmount').on('input', function() {
      $('#amountError').hide();
    });

    $('input[name="payment_method"]').on('input', function() {
      $('#paymentMethodError').hide();
    });
    if (isValid) {
      $.ajax({
        type: "POST",
        url: "check_payment_info.php",
        data: {
          'paymentSubmit': true,
          'csrf_token' : csrfToken,
          'trx_id': trx_id,
          'depositAmount': depositAmount,
          'paymentMethod': paymentMethod,
        },
        success: function(response) {
          if(response === 'trxIdFound') {
            console.log(response);
            $.ajax({
              type : "POST",
              url : "update.php",
              data : {
                'correct_info' : true,
                'csrf_token' : csrfToken,
                'deposit_amount' : depositAmount,
                'member_email': memberEmail,
                'payment_method': paymentMethod,
                'trx_id': trx_id,
              },
              success: function(response){
                if(response === 'success'){
                  //console.log(response);
                  $('#depositForm').hide();
                  //showToast(response);
                  $.ajax({
                    type : 'POST',
                    url : 'loading.php',
                    success: function(response){
                      $('#alert').html(response);
                    }
                  })
                }
              }             
            });
            
          } else{
            //console.log(response);
            showToast(response);
          }     
          $('#paymentForm')[0].reset();
        }
      });
    } else if(memberPosition === 'Cashier'){
        var isValid = true;
        if (!depositAmount) {
          $('#amountError').show();
          isValid = false;
        } else {
          $('#amountError').hide();
        }
        $('#depositAmount').on('input', function() {
          $('#amountError').hide();
        });
        
        if(isValid){
          $.ajax({
            type : 'POST',
            url : 'update.php',
            data : {
              'correct_info' : true,
              'member_email' : memberEmail,
              'member_position' : memberPosition,
              'deposit_amount' : depositAmount,
              'payment_method' : 'Not Found',
              'trx_id' : 'Not Found',
            },
            success: function(response){
              if(response === 'success'){
                //console.log(response);
                $('#depositForm').hide();
                $('#numberCashier').hide();
                // showToast(response);
                $.ajax({
                  type : 'POST',
                  url : 'loading.php',
                  success: function(response){
                    $('#alert').html(response);
                  }
                })
              }else{
                //console.log(response);
                showToast(response);
              }
            }

          })
        }
    }
  });
 })
 
 //For toast
function showToast(status){
  const notyf = new Notyf({
    duration: 2000, 
    position: { x: 'center', y: 'top' },
    dismissble: true,
});
if(status == "success"){
  notyf.success('Deposit Success!');

} else if(status == "trxIdNotFound"){
  notyf.error('Deposit Failed!');

}
}