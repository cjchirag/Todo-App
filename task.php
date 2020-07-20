<?php
require_once 'inc/bootstrap.php';
$user = decodeAuthCookie('auth_user_id');

if (empty($user)) {
    global $session;
    $session->getFlashBag()->add('error', 'You must login or register to add an ask');
    redirect('./index.php');
}

if (!empty(request()->get('id'))) {
    $authCheck = isTaskOwner(request()->get('id'));
}
$pageTitle = "Task | Time Tracker";
$page = "task";

if (!empty(request()->get('id'))) {
    list($task_id, $task, $status, $user_id) = getTask(request()->get('id'));
}

include 'inc/header.php';
?>

<div class="section page">
    <div class="col-container page-container">
        <div class="col col-70-md col-60-lg col-center">
            <h1 class="actions-header"><?php
            if (!empty($task_id)) {
                echo "Update";
            } else {
                echo "Add";
            }
            ?> Task</h1>
            <?php
            if (isset($error_message)) {
                echo "<p class='message'>$error_message</p>";
            }
            ?>
            <form class="form-container form-add" method="post" action="inc/actions_tasks.php">
            <input type='hidden' name='user_id' value=<?php echo "$user"; ?> />
                <table>
                    <tr>
                        <th><label for="task">Task<span class="required">*</span></label></th>
                        <td><input type="text" id="task" name="task" value="<?php if (!empty($task)) { echo htmlspecialchars($task); } ?>" /></td>
                    </tr>
                   </table>
                <?php

                if (!empty($task_id)) {
                    echo "<input type='hidden' name='action' value='update' />";
                    echo "<input type='hidden' name='task_id' value='$task_id' />";
                    echo "<input type='hidden' name='status' value='$status' />";
                } else {
                    echo "<input type='hidden' name='status' value='0' />";
                    echo "<input type='hidden' name='action' value='add' />";
                }
                ?>
                <input class="button button--primary button--topic-php" type="submit" value="Submit" />
            </form>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>
