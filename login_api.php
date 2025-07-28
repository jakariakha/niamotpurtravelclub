<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Methods: POST');

require 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if the required parameters are set
    if (isset($data['email']) && isset($data['api_key'])){
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $api_key = strip_tags($data['api_key']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM email_and_api WHERE email = ? AND api_key = ?");
        $stmt->bind_param("ss", $email, $api_key);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(array('success' => true, 'message' => 'Login Successful!'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Login Failed!'));
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'Invalid request.'));
    }
} else{
    echo json_encode(['error' => 'Invalid request']);
}

if(isset($stmt)){
    $stmt->close();
}
$conn->close();
?>
