<?php
// existing code
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Session timeout 
$timeout_duration = 900;

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: index.php?timeout=1");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

include("connect.php");

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

$email = $_SESSION['email'];
// Get user id
$userQuery = $conn->prepare("SELECT id, firstName, lastName FROM users WHERE email = ?");
$userQuery->bind_param("s", $email);
$userQuery->execute();
$userResult = $userQuery->get_result();
if ($userResult->num_rows === 0) {
    echo "User not found";
    exit;
}
$user = $userResult->fetch_assoc();
$user_id = $user['id'];
$firstName = $user['firstName'];
$lastName = $user['lastName'];

// Get filters and search
$search = $_GET['search'] ?? '';
$filter_status = $_GET['filter_status'] ?? '';
$filter_due_date = $_GET['filter_due_date'] ?? '';

// Build query with filters
$query = "SELECT id, title, description, due_date, is_done FROM todos WHERE user_id = ?";
$params = [$user_id];
$types = "i";

if ($filter_status !== '' && ($filter_status === '0' || $filter_status === '1')) {
    $query .= " AND is_done = ?";
    $params[] = (int)$filter_status;
    $types .= "i";
}

if ($filter_due_date !== '') {
    $query .= " AND due_date = ?";
    $params[] = $filter_due_date;
    $types .= "s";
}

if ($search !== '') {
    $query .= " AND (title LIKE ? OR description LIKE ?)";
    $search_param = '%' . $search . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

$query .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param($types, ...$params);
if (!$stmt->execute()) {
    die('Execute failed: ' . htmlspecialchars($stmt->error));
}
$result = $stmt->get_result();
$todos = [];
while ($row = $result->fetch_assoc()) {
    $todos[] = $row;
}

// Calculate task counts
$total_tasks = count($todos);
$completed_tasks = 0;
$pending_tasks = 0;
$overdue_tasks = 0;
$now = new DateTime();

foreach ($todos as $todo) {
    if ($todo['is_done']) {
        $completed_tasks++;
    } else {
        $due_date = new DateTime($todo['due_date']);
        if ($due_date < $now) {
            $overdue_tasks++;
        } else {
            $pending_tasks++;
        }
    }
}

// Handle todo actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $due_date = $_POST['due_date'] ?? null;
        if ($title !== '') {
            $stmt = $conn->prepare("INSERT INTO todos (user_id, title, description, due_date, is_done, created_at) VALUES (?, ?, ?, ?, 0, NOW())");
            $stmt->bind_param("isss", $user_id, $title, $description, $due_date);
            $stmt->execute();
        }
    } elseif ($action === 'toggle') {
        $id = intval($_POST['id'] ?? 0);
        $is_done = intval($_POST['is_done'] ?? 0);
        $stmt = $conn->prepare("UPDATE todos SET is_done = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("iii", $is_done, $id, $user_id);
        $stmt->execute();
    } elseif ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        $stmt = $conn->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
    } elseif ($action === 'edit') {
        $id = intval($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $due_date = $_POST['due_date'] ?? null;
        if ($title !== '') {
            $stmt = $conn->prepare("UPDATE todos SET title = ?, description = ?, due_date = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("sssii", $title, $description, $due_date, $id, $user_id);
            $stmt->execute();
        }
    }
    header("Location: homepage.php");
    exit;
}
?>
