<?php

require_once __DIR__ .'/../inc/bootstrap.php';

$username = request()->get('username');
$password = request()->get('password');

$confirmPassword = request()->get('confirm_password');

if ($password != $confirmPassword) {
  $session->getFlashBag()->add('error', 'Passwords do NOT match');
  redirect('/register.php');
}

$user = getUserByName($username);
if (!empty($user)) {
  $session->getFlashBag()->add('error', "Could not add $username. It Already Exists");
  redirect('./register.php');
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$user = addUser($username, $hashed);
$session->getFlashBag()->add('success', 'User Added');
var_dump($user);

saveUserData($user);
?> 
