<?php
session_start();
date_default_timezone_set('Asia/Dhaka');
include 'config/config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_SESSION['csrf_token']) && ($_POST['csrf_token'] === $_SESSION['csrf_token'])){
        if(isset($_POST['update_member'])){
            $member_id = $_POST['member_id'];
            $member_name = $_POST['member_name'];
            $member_email = $_POST['member_email'];
            $deposit_amount = $_POST['member_deposit_amount'];
            $member_uid = $_POST['member_uid'];
            $set_amount = $_POST['member_set_amount'];
            $position = $_POST['member_position'];
            $due_amount = mysqli_real_escape_string($conn, $_POST['member_due_amount']);
            $due_month = mysqli_real_escape_string($conn, $_POST['member_due_month']);
            $joining_date = $_POST['member_joining_date'];
            $stmt = $conn->prepare("UPDATE members_info SET name = ?, email = ?, member_uid = ?, position = ?, deposit_amount = ?, set_amount = ?, due_amount = ?, due_month = ?, joining_date = ? WHERE id = ? ");
            $stmt->bind_param("ssisiiiisi", $member_name, $member_email, $member_uid, $position, $deposit_amount, $set_amount, $due_amount, $due_month, $joining_date, $member_id);
            if($stmt->execute()){
                echo 'success';
                unset($_SESSION['csrf_token']);
            } else{
                echo 'failed';
            }  
        }
        
         // For Delete Member
        if(isset($_POST['deleteMember'])){
            $member_id = $_POST['member_id'];
            $stmt = $conn->prepare("DELETE FROM members_info WHERE id = ?");
            $stmt->bind_param("i", $member_id);
            if($stmt->execute()){
                echo 'deleteSuccess';
                unset($_SESSION['csrf_token']);    
            }
        }
         
        //when updating profile then check email exists or not
        if(isset($_POST['find_email'])){
            $member_email = $_POST['member_email'];
            //$member_id = $_POST['member_id'];
            $stmt = $conn->prepare("SELECT email from members_info WHERE email = ?");
            $stmt->bind_param("s", $member_email);
            $stmt->execute();
            $result = $stmt->get_result()->num_rows;
            if(isset($_POST['in_member_update'])){
                if($result){          
                    echo 'emailFound';
                } else{
                    echo 'emailNotFound';
                }
            } else if(isset($_POST['in_profile_update'])){
                if($result){          
                    echo 'emailFound';
                    unset($_SESSION['csrf_token']);
                } else{
                    echo 'emailNotFound';
                }
            } else if(isset($_POST['in_add_member'])){
                if($result){          
                    echo 'emailFound';
                    unset($_SESSION['csrf_token']);
                } else{
                    echo 'emailNotFound';
                }
            }
            // if($result){          
            //     echo 'emailFound';
            // } else{
            //     echo 'emailNotFound';
            // }
        }
        
        //profile update
        if(isset($_POST['profile_update'])){
            if(isset($_SESSION['new_email_otp'])){
                $member_id = $_POST['member_id'];
                $member_email = $_POST['member_email'];
                $_SESSION['member_email'] = $member_email;
                $member_name = $_POST['member_name'];
                $submit_otp = htmlspecialchars($_POST['submit_otp']);
                $sent_otp = $_SESSION['new_email_otp'];
                if(($sent_otp == $submit_otp)){
                $stmt = $conn->prepare("UPDATE members_info SET name = ?, email = ? WHERE id = ?");
                $stmt->bind_param("ssi", $member_name, $member_email, $member_id);
                if($stmt->execute()){
                    echo 'updateSuccess';
                    unset($_SESSION['csrf_token']);
                } else{
                    echo 'updateFailed';
                }
                } else{
                    echo 'wrongOtp';
                }
                // $result = [
                //     'id' => $member_id,
                //     'member_name' => $member_name,
                //     'member_email' => $member_email,
                //     'submit_otp' => $submit_otp,
                //     'sent_otp' => $sent_otp
            
                // ];
                // echo json_encode($result);
                //  unset($_SESSION['new_email_otp']);
                
            }else{
                echo 'wrongOtp';
                
            }   
        
        }
          
    } else{
        header('location: 404.php');
        exit();
    }
} else{
    echo json_encode(['error' => 'h']);
    header('location: 404.php');
    exit();
}

if(isset($stmt)){
    $stmt->close();
}
$conn->close();
?>