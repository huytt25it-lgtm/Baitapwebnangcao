<!DOCTYPE html>
<html>
<head>
    <title>Task List Manager</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <header>
        <h1>Task List Manager</h1>
    </header>
    <main>
        <p><strong>Session ID:</strong> <?php echo session_id(); ?></p>

        <!-- Danh sách công việc -->
        <h2>Tasks</h2>
        <?php if (count($task_list) == 0) : ?>
            <p>There are no tasks in the task list.</p>
        <?php else: ?>
            <ul>
            <?php foreach ($task_list as $id => $task) : ?>
                <li><?php echo $id + 1 . '. ' . htmlspecialchars($task); ?></li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <!-- Form thêm công việc -->
        <h2>Add Task</h2>
        <form action="." method="post">
            <input type="hidden" name="action" value="add">
            <label>Task:</label>
            <input type="text" name="newtask"><br>
            <label>&nbsp;</label>
            <input type="submit" value="Add Task"><br>
        </form>

        <br>

        <?php if (count($task_list) > 0) : ?>
            <!-- Form thao tác chỉnh sửa/xóa/sắp xếp (Không chứa hidden input truyền mảng công việc) -->
            <h2>Select Task</h2>
            <form action="." method="post">
                <label>Task:</label>
                <select name="taskid">
                    <?php foreach ($task_list as $id => $task) : ?>
                        <option value="<?php echo $id; ?>">
                            <?php echo htmlspecialchars($task); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>

                <label>&nbsp;</label>
                <input type="submit" name="action" value="Delete">
                <input type="submit" name="action" value="Modify">
                <input type="submit" name="action" value="Promote"><br>

                <label>&nbsp;</label>
                <input type="submit" name="action" value="Sort">
            </form>

            <?php if (isset($task_to_modify)) : ?>
                <h2>Modify Task</h2>
                <form action="." method="post">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="taskid" value="<?php echo $task_id; ?>">
                    <label>Task:</label>
                    <input type="text" name="modifiedtask" value="<?php echo htmlspecialchars($task_to_modify); ?>"><br>
                    <label>&nbsp;</label>
                    <input type="submit" value="Save Changes">
                    <input type="submit" name="action" value="Cancel">
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</body>
</html>