<?php
//get name, email and password from the form
$name = $_POST['form_name'];
$email = $_POST['form_email'];
$password = $_POST['form_password'];
$control_password = $_POST['form_control_password'];
if (empty($name) || empty($email) || empty($password) || empty($control_password)) {
    echo "Please fill all the fields";
    exit();
}
if ($password != $control_password) {
    echo "Passwords do not match";
    exit();
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Please enter a valid email";
    exit();
}
if (strlen($password) < 6) {
    echo "Password must be at least 6 characters";
    exit();
}
//check for sql injection
if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $name)) {
    echo "Please enter a valid name";
    exit();
}
if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $email)) {
    echo "Please enter a valid email";
    exit();
}
//parse password to sha256
$password = hash('sha256', $password);

echo "Success";
var_dump($name, $email, $password);
//connect to the database
