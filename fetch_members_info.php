<?php
session_start();
include 'config/config.php';
define('APP_STARTED', true);
include 'navbar.php';
if(!(isset($_SESSION['position']) && $_SESSION['position'] === 'President')){
    header('location: index.php');
}

if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<link rel="stylesheet" href="assets/css/toast.css">
<h2 class="text-center" style="margin-top: 100px;">Manage Members</h2>
<div class="card">
    <div class="card-body border border-info">
      <input type="hidden" id="csrfToken" value="<?php echo $_SESSION['csrf_token'];?>">
          <div class="panel">
              <div class="table-responsive  text-center">
                <table class="table">
                  <tr>  
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">UID</th>
                    <th scope="col">Position</th>
                    <th scope="col">Balance</th>
                    <th scope="col">Set Amount</th>
                    <th scope="col">Due Amount</th>
                    <th scope="col">Due Month</th>
                    <th scope="col">Joining Date</th>     
                    <th scope="col">Action</th>
                  </tr>
                    <tbody class="table-group-divider" id="table-body">
                      <?php
                        $stmt = $conn->prepare("SELECT * FROM members_info");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while($rows = $result->fetch_assoc()){
                            echo '<tr>';  
                            echo '<td class="member_id">'.$rows['id'].'</td>';
                            echo '<td>'.$rows['name'].'</td>';
                            echo '<td>'.$rows['email'].'</td>';
                            echo '<td>'.$rows['member_uid'].'</td>';
                            echo '<td>'.$rows['position'].'</td>';
                            echo '<td>'.$rows['deposit_amount'].'</td>';
                            echo '<td>'.$rows['set_amount'].'</td>';
                            echo '<td>'.$rows['due_amount'].'</td>';
                            echo '<td>'.$rows['due_month'].'</td>';
                            $date = new DateTime($rows['joining_date']);
                            echo '<td>'.$date->format('d-m-Y').'</td>';
                            echo '<td class="d-flex flex-row flex-md-row gap-2"><span class="btn btn-info"><a href="update_member.php?id='.$rows['id'].'" class="text-black text-decoration-none">Update</a></span>
                            <span class="btn btn-danger"><a href="" class="text-center text-white text-decoration-none showdeleteModal">Delete</a></span></td>';
                            echo '</tr>';   
                        }
                        if(isset($stmt)){
                          $stmt->close();
                        }
                        $conn->close();        
                      ?>
                    </tbody>
                  </table>
            </div>
          </div>
    </div>
</div>
<script src="assets/js/update_member.js"></script>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this item?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="deleteMember">Delete</button>
      </div>
    </div>
  </div>
</div>
