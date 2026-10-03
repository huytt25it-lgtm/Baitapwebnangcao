<?php
// Yêu cầu 2: Bắt đầu session kéo dài 1 năm
$lifetime = 60 * 60 * 24 * 365;
session_set_cookie_params($lifetime, '/');
session_start();

// Yêu cầu 3: Khởi tạo mảng công việc trong session nếu chưa có
if (!isset($_SESSION['task_list'])) {
    $_SESSION['task_list'] = array();
}

$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action === NULL) {
        $action = 'show_add';
    }
}

// Xử lý các thao tác dựa trên $_SESSION['task_list']
switch ($action) {
    case 'add':
        $new_task = filter_input(INPUT_POST, 'newtask');
        if (!empty($new_task)) {
            $_SESSION['task_list'][] = $new_task;
        }
        break;

    case 'delete':
        $task_id = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
        if ($task_id !== NULL && $task_id !== FALSE) {
            unset($_SESSION['task_list'][$task_id]);
            $_SESSION['task_list'] = array_values($_SESSION['task_list']); // Đánh lại chỉ số mảng
        }
        break;

    case 'modify':
        $task_id = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
        if ($task_id !== NULL && $task_id !== FALSE) {
            $task_to_modify = $_SESSION['task_list'][$task_id];
        }
        break;

    case 'save':
        $task_id = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
        $modified_task = filter_input(INPUT_POST, 'modifiedtask');
        if ($task_id !== NULL && $task_id !== FALSE && !empty($modified_task)) {
            $_SESSION['task_list'][$task_id] = $modified_task;
        }
        break;

    case 'cancel':
        break;

    case 'promote':
        $task_id = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
        if ($task_id !== NULL && $task_id !== FALSE && $task_id > 0) {
            $temp = $_SESSION['task_list'][$task_id];
            $_SESSION['task_list'][$task_id] = $_SESSION['task_list'][$task_id - 1];
            $_SESSION['task_list'][$task_id - 1] = $temp;
        }
        break;

    case 'sort':
        sort($_SESSION['task_list']);
        break;
}

// Lấy danh sách công việc từ session để truyền sang view
$task_list = $_SESSION['task_list'];
include('task_list.php');
?>