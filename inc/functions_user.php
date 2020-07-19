<?php

function getAllUsers() {
    global $db;

    try {
        $query = "SELECT * FROM users";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        throw $e;
    }

}

function getUserById ($id) {
    global $db;

    try {
        $statement = $db->prepare('SELECT * FROM users WHERE id=:id');
        $statement->bindParam('id', $id);
        $statement->execute();
        $user = $statement->fetch();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return $user;
}

function getUserByName($username) {
    global $db;

    try {
        $statement = $db->prepare('SELECT * FROM users WHERE username=:username');
        $statement->bindParam('username', $username);
        $statement->execute();
        $user = $statement->fetch();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return $user;
}

function addUser($username, $password) {
    global $db;

    try {
        $statement = $db->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
        $statement->bindParam('username', $username);
        $statement->bindParam('password', $password);
        $statement->execute(); 
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return getUserByName($username);
}

function updateUserPassword($username, $password) {

    global $db;

    try {
        $statement = $db->prepare('UPDATE users SET password=:password WHERE username=:username');
        $statement->bindParam('password', $password);
        $statement->bindParam('username', $username);
        $statement->execute(); 
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return getUserByName($username);
}
?>