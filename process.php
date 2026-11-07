<?php

$host     = 'localhost';
$port     = '5432';
$db       = 'corporate_db';
$user     = 'postgres';
$password = 'aritra2005';

$dsn = "pgsql:host=$host;port=$port;dbname=$db;";

try {

    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $emp_name   = $_POST['emp_name'];
        $emp_id     = $_POST['emp_id'];
        $phone      = $_POST['phone'];
        $department = $_POST['department'];

        if (!$emp_name || !$phone) {
            throw new Exception("Full Name and Phone Number are required fields.");
        }

        $sql = "INSERT INTO employee_directory (emp_name, emp_id, phone, department) 
                VALUES (:emp_name, :emp_id, :phone, :department)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':emp_name'   => $emp_name,
            ':emp_id'     => $emp_id,
            ':phone'      => $phone,
            ':department' => $department
        ]);

        echo '
        <div class="message-card success">
            <h3>Registration Successful!</h3>
            <p>Employee <strong>' . htmlspecialchars($emp_name) . '</strong> has been added to the directory.</p>
            <a href="index.html" class="back-link">&larr; Register Another Employee</a>
        </div>';
    }
} catch (PDOException $e) {
    echo '
    <div class="message-card error">
        <h3>Database Error</h3>
        <p>' . htmlspecialchars($e->getMessage()) . '</p>
        <a href="index.html" class="back-link">&larr; Go Back to Form</a>
    </div>';
} catch (Exception $e) {
    echo '
    <div class="message-card error">
        <h3>Input Error</h3>
        <p>' . htmlspecialchars($e->getMessage()) . '</p>
        <a href="index.html" class="back-link">&larr; Go Back to Form</a>
    </div>';
}
?>