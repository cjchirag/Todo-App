<?php
require 'inc/bootstrap.php';

$pageTitle = "Task List | Time Tracker";
$page = "tasks";

$user = decodeAuthCookie('auth_user_id');

if (empty($user)) {
    $session->getFlashBag()->add('error', 'You must login or register an account to use a task list');
}

$filter = request()->get('filter');
$tasks = [];
if ($filter=='all') {
    $tasks = getTasks($user);
} elseif ($filter=='complete') {
    $tasks = getCompleteTasks($user);
} elseif ($filter == 'incomplete') {
    $tasks = getIncompleteTasks($user);
} else {
    $tasks = getTasks($user);
}
include 'inc/header.php';
?>

    <div class="col-container page-container">
        <div class="col col-70-md col-60-lg col-center">

            <h1 class="actions-header">Task List</h1>
            <div class="actions-item">
                <a class="actions-link" href="task.php">
                    <span class="actions-icon">
                        <svg viewbox="0 0 64 64"><use xlink:href="#task_icon"></use></svg>
                    </span>
                Add Task</a>
            </div>

            <div class="form-container">
                <ul class="action_filter">
                    <li class="on"><a href="task_list.php?filter=incomplete">Incomplete</a></li>
                    <li class="on"><a href="task_list.php?filter=complete">Complete</a></li>
                    <li class="on"><a href="task_list.php?filter=all">All</a></li>
                </ul>
                  <table class="items">
                      <tr><th>Status</th><th>Title</th><th>Action</th></tr>
                        <?php
                        foreach ($tasks as $item) {
                        // An additional layer of security
                         if (isTaskOwner($item['id'])) {
                            echo "<tr><td>";
                            echo "<input type='checkbox' onChange='javascript:location=\"inc/actions_tasks.php?action=status&task_id=".$item['id'];
                            if (!empty($filter)) {
                                echo "&filter=$filter";
                            }
                            if ($item['status']) {
                                echo "&status=0\"' checked";
                            } else {
                                echo "&status=1\"' ";
                            }
                            echo " />";
                            echo "</td><td width='100%'>";

                            echo "<a href='task.php?id=" . $item['id'] . "'>" . $item['task'] . "</a>";
                            echo "</td><td>";

                            echo "<a href='inc/actions_tasks.php?action=delete&task_id=".$item['id'];
                            echo "' onclick=\"return confirm('Are you sure you want tot delete this task?');\"";
                            echo "'>Delete</a>";
                            echo "</td></tr>\n";
                        }
                    }
                        ?>
                  </table>
            </div>

        </div>
    </div>

<?php include("inc/footer.php"); ?>
