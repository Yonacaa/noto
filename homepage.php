<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Session timeout management: 15 minutes inactivity logout
$timeout_duration = 900; // 900 seconds = 15 minutes

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: index.php?timeout=1");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

include 'connect.php';
include 'function.php';
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Todo List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col items-center p-0">

    <!-- Navbar -->
    <nav class="w-full bg-gray-800 shadow-md">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0 text-2xl font-bold text-blue-400">
                    Noto
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="homepage.php" class="text-gray-300 font-semibold hover:text-blue-400">Todo</a>
                    <span class="text-gray-300 font-semibold">Hello, <?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></span>
                    <a href="logout.php" class="text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded-md transition">Logout</a>
                </div>
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="text-gray-300 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-400">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-gray-800">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="homepage.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-blue-400">Todo</a>
                <a href="profile.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-blue-400">Profile</a>
                <span class="block px-3 py-2 rounded-md text-base font-medium text-gray-300">Hello, <?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></span>
                <a href="logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-red-600 hover:bg-red-700 transition">Logout</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow w-full max-w-3xl p-6">
        
        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-8 text-center">
            <div class="bg-gray-800 rounded-lg p-4 shadow flex flex-col items-center space-y-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h3l2 2h5a2 2 0 012 2v10a2 2 0 01-2 2z" />
                </svg>
                <div class="text-2xl font-bold text-gray-100"><?php echo $total_tasks; ?></div>
                <div class="text-gray-400">Total Tasks</div>
            </div>
            <div class="bg-green-700 rounded-lg p-4 shadow flex flex-col items-center space-y-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-300 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <div class="text-2xl font-bold text-gray-100"><?php echo $completed_tasks; ?></div>
                <div class="text-gray-200">Completed</div>
            </div>
            <div class="bg-yellow-600 rounded-lg p-4 shadow flex flex-col items-center space-y-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-300 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 20a8 8 0 100-16 8 8 0 000 16z" />
                </svg>
                <div class="text-2xl font-bold text-gray-100"><?php echo $pending_tasks; ?></div>
                <div class="text-gray-200">Pending</div>
            </div>
            <div class="bg-red-700 rounded-lg p-4 shadow flex flex-col items-center space-y-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-300 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <div class="text-2xl font-bold text-gray-100"><?php echo $overdue_tasks; ?></div>
                <div class="text-gray-200">Overdue</div>
            </div>
        </div>

        <h1 class="text-4xl font-bold mb-8 text-center">Your Todo List</h1>

        <form method="get" action="homepage.php" class="flex space-x-4 mb-6 items-center justify-center">
            <input type="text" name="search" placeholder="Search tasks..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="px-3 py-2 rounded-md bg-gray-800 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" />
            <select name="filter_status" class="px-3 py-2 rounded-md bg-gray-800 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="" <?php if (!isset($_GET['filter_status']) || $_GET['filter_status'] === '') echo 'selected'; ?>>All</option>
                <option value="0" <?php if (isset($_GET['filter_status']) && $_GET['filter_status'] === '0') echo 'selected'; ?>>Not Completed</option>
                <option value="1" <?php if (isset($_GET['filter_status']) && $_GET['filter_status'] === '1') echo 'selected'; ?>>Completed</option>
            </select>
            <input type="date" name="filter_due_date" value="<?php echo isset($_GET['filter_due_date']) ? htmlspecialchars($_GET['filter_due_date']) : ''; ?>" class="px-3 py-2 rounded-md bg-gray-800 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" />
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Filter</button>
            <a href="homepage.php" class="ml-2 text-blue-400 hover:underline">Clear</a>
        </form>

        <button id="openAddTaskModal" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition mb-6">
            Add Task
        </button>

        <!-- Add Task Modal -->
        <div id="addTaskModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-gray-900 p-6 rounded-md w-full max-w-md">
                <h2 class="text-2xl font-semibold mb-4">Add New Task</h2>
                <form method="post" action="homepage.php" class="space-y-4">
                    <input type="hidden" name="action" value="add" />
                    <div>
                        <label for="title" class="block mb-1 font-semibold">Title</label>
                        <input type="text" name="title" id="title" placeholder="Task title" required
                            class="w-full px-4 py-2 border border-gray-700 rounded-md bg-gray-900 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                    <div>
                        <label for="description" class="block mb-1 font-semibold">Description</label>
                        <textarea name="description" id="description" placeholder="Task description"
                            class="w-full px-4 py-2 border border-gray-700 rounded-md bg-gray-900 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                    </div>
                    <div>
                        <label for="due_date" class="block mb-1 font-semibold">Due Date & Time</label>
                        <input type="datetime-local" name="due_date" id="due_date"
                            class="w-full px-4 py-2 border border-gray-700 rounded-md bg-gray-900 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" id="closeAddTaskModal" class="px-4 py-2 rounded-md bg-gray-700 hover:bg-gray-600 transition">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white transition">Add Task</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        function openEditModal(id, title, description, due_date) {
            const modal = document.getElementById('editTaskModal');
            modal.classList.remove('hidden');
            document.getElementById('editTaskId').value = id;
            document.getElementById('editTitle').value = title;
            document.getElementById('editDescription').value = description;
            if (due_date) {
                due_date = due_date.replace(' ', 'T');
            }
            document.getElementById('editDueDate').value = due_date;
        }

        function closeEditModal() {
            const modal = document.getElementById('editTaskModal');
            modal.classList.add('hidden');
        }

        function confirmDelete() {
            alert('Task deleted successfully.');
            return true;
        }
    </script>
        <ul class="bg-gray-800 rounded-md shadow divide-y divide-gray-700 max-h-[calc(100vh-250px)] overflow-y-auto">
            <?php foreach ($todos as $todo): ?>
                <li class="flex flex-col space-y-2 px-4 py-3 <?php echo $todo['is_done'] ? 'bg-green-900' : ''; ?>">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-lg <?php echo $todo['is_done'] ? 'line-through text-gray-500' : ''; ?>">
                            <?php echo htmlspecialchars($todo['title']); ?>
                        </span>
                        <div class="flex space-x-2">
                            <form method="post" action="homepage.php" class="inline">
                                <input type="hidden" name="action" value="toggle" />
                                <input type="hidden" name="id" value="<?php echo $todo['id']; ?>" />
                                <input type="hidden" name="is_done" value="<?php echo $todo['is_done'] ? 0 : 1; ?>" />
                                <button type="submit"
                                    class="text-sm px-3 py-1 rounded-md border border-gray-700 hover:bg-gray-700 transition">
                                    <?php echo $todo['is_done'] ? 'Undo' : 'Done'; ?>
                                </button>
                            </form>
                            <button type="button" class="text-sm px-3 py-1 rounded-md border border-yellow-500 text-yellow-400 hover:bg-yellow-600 transition" onclick="openEditModal(<?php echo $todo['id']; ?>, '<?php echo htmlspecialchars(addslashes($todo['title'])); ?>', '<?php echo htmlspecialchars(addslashes($todo['description'])); ?>', '<?php echo $todo['due_date']; ?>')">
                                Edit
                            </button>
                            <form method="post" action="homepage.php" class="inline" onsubmit="return confirmDelete();">
                                <input type="hidden" name="action" value="delete" />
                                <input type="hidden" name="id" value="<?php echo $todo['id']; ?>" />
                                <button type="submit"
                                    class="text-sm px-3 py-1 rounded-md border border-red-700 text-red-500 hover:bg-red-700 transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <span class="text-gray-400 text-sm <?php echo $todo['is_done'] ? 'line-through' : ''; ?>">
                        <?php echo nl2br(htmlspecialchars($todo['description'])); ?>
                    </span>
                    <span class="text-gray-400 text-xs <?php echo $todo['is_done'] ? 'line-through' : ''; ?>">
                        Due: <?php echo htmlspecialchars(str_replace(' ', ' T', $todo['due_date'])); ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>

    <!-- Edit Task Modal -->
    <div id="editTaskModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-gray-900 p-6 rounded-md w-full max-w-md shadow-lg">
            <h2 class="text-2xl font-semibold mb-4">Edit Task</h2>
            <form method="post" action="homepage.php" class="space-y-4">
                <input type="hidden" name="action" value="edit" />
                <input type="hidden" id="editTaskId" name="id" value="" />
                <div>
                    <label for="editTitle" class="block mb-1 font-semibold">Title</label>
                    <input type="text" id="editTitle" name="title" required
                        class="w-full px-4 py-2 border border-gray-700 rounded-md bg-gray-900 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div>
                    <label for="editDescription" class="block mb-1 font-semibold">Description</label>
                    <textarea id="editDescription" name="description"
                        class="w-full px-4 py-2 border border-gray-700 rounded-md bg-gray-900 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>
                <div>
                    <label for="editDueDate" class="block mb-1 font-semibold">Due Date & Time</label>
                    <input type="datetime-local" id="editDueDate" name="due_date"
                        class="w-full px-4 py-2 border border-gray-700 rounded-md bg-gray-900 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" id="closeEditTaskModal" onclick="closeEditModal()" class="px-4 py-2 rounded-md bg-gray-700 hover:bg-gray-600 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-yellow-500 hover:bg-yellow-600 text-white transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
