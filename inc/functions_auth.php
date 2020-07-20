<?php

function isAuthenticated() {
  return decodeAuthCookie();
}

function requireAuth() {
  if (!isAuthenticated()) {
    global $session;
    $session->getFlashBag()->add('error', 'The user is not authorized for this action');
    redirect('./login.php');
  }
}

function isTaskOwner($task_id) {
  global $session;
  if (!isAuthenticated()) {
    return false;
  }

  $task = getTask($task_id);

  if ($task['user_id'] == decodeAuthCookie('auth_user_id')) {
    return true;
  } else {
    $session->getFlashBag()->add('error', 'The user is not authorized to access this task');
    redirect('./task_list.php');
  }
}

function getAuthenticatedUser() {
  return getUserById(decodeAuthCookie('auth_user_id'));
}


function saveUserData($user) {

  global $session;
  $session->getFlashBag()->add('success', 'Successfully Logged In');

  $expTime = time() + 3600;
  $jwt = Firebase\JWT\JWT::encode(
    [
      'iss' => request()->getBaseUrl(),
      'sub' => (int) $user['id'],
      'exp' => $expTime,
      'iat' => time(),
      'nbf' => time()
    ],
    getenv("SECRET_KEY"),
    'HS256'
  );
  $cookie = setAuthCookie($jwt, $expTime);
  redirect('../index.php', ['cookies' => [$cookie]]);

}

function setAuthCookie ($data, $expTime) {
    $cookie = new Symfony\Component\HttpFoundation\Cookie(
        'auth',
        $data,
        $expTime,
        '/',
        'localhost',
        false,
        true
      );
      return $cookie;
}

function decodeAuthCookie($prop = null)
{
  try {
    
    Firebase\JWT\JWT::$leeway=1;
    $cookie = Firebase\JWT\JWT::decode(
      request()->cookies->get('auth'),
      getenv("SECRET_KEY"),
      ['HS256']
    );
  } catch (Exception $e) {
    return false;
  }
  if ($prop === null) {
    return $cookie;
  }
  if ($prop == 'auth_user_id') {
    $prop = 'sub';
  }
  if (!isset($cookie->$prop)) {
    return false;
  }
  return $cookie->$prop;
}

?>