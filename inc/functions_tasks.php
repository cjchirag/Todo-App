<?php
//task functions

function getTasks($user_id, $status = NULL)
{
    global $db;
    $query = "SELECT * FROM tasks ";
    if (!empty($status)) {
        $query .= "WHERE user_id=:user AND $status";
    } else {
        $query .= "WHERE user_id=:user";
    }
    $query .= " ORDER BY id";
    try {
        $statement = $db->prepare($query);
        $statement->bindParam('user', $user_id);
        $statement->execute();
        $tasks = $statement->fetchAll();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return $tasks;
}
function getIncompleteTasks($user_id)
{
    return getTasks($user_id, 'status=0');
}
function getCompleteTasks($user_id)
{
    return getTasks($user_id, 'status=1');
}
function getTask($task_id)
{
    global $db;

    try {
        $statement = $db->prepare('SELECT * FROM tasks WHERE id=:id');
        $statement->bindParam('id', $task_id);
        $statement->execute();
        $task = $statement->fetch();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return $task;
}
function createTask($data)
{
    global $db;

    try {
        $statement = $db->prepare('INSERT INTO tasks (task, status, user_id) VALUES (:task, :status, :user_id)');
        $statement->bindParam('task', $data['task']);
        $statement->bindParam('status', $data['status']);
        $statement->bindParam('user_id', $data['user_id']);
        $statement->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return getTask($db->lastInsertId());
}
function updateTask($data)
{
    global $db;

    try {
        getTask($data['task_id']);
        $statement = $db->prepare('UPDATE tasks SET task=:task, status=:status WHERE id=:id');
        $statement->bindParam('task', $data['task']);
        $statement->bindParam('status', $data['status']);
        $statement->bindParam('id', $data['task_id']);
        $statement->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return getTask($data['task_id']);
}
function updateStatus($data)
{
    global $db;

    try {
        getTask($data['task_id']);
        $statement = $db->prepare('UPDATE tasks SET status=:status WHERE id=:id');
        $statement->bindParam('status', $data['status']);
        $statement->bindParam('id', $data['task_id']);
        $statement->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return getTask($data['task_id']);
}
function deleteTask($task_id)
{
    global $db;

    try {
        getTask($task_id);
        $statement = $db->prepare('DELETE FROM tasks WHERE id=:id');
        $statement->bindParam('id', $task_id);
        $statement->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return true;
}
