document.addEventListener('DOMContentLoaded', function() {
    const showRegisterBtn = document.getElementById('showRegister');
    const showLoginBtn = document.getElementById('showLogin');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    if (showRegisterBtn && showLoginBtn && loginForm && registerForm) {
        showRegisterBtn.addEventListener('click', function() {
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
        });

        showLoginBtn.addEventListener('click', function() {
            registerForm.classList.add('hidden');
            loginForm.classList.remove('hidden');
        });
    }

    const openAddTaskBtn = document.getElementById('openAddTaskModal');
    const closeAddTaskBtn = document.getElementById('closeAddTaskModal');
    const addTaskModal = document.getElementById('addTaskModal');

    if (openAddTaskBtn && closeAddTaskBtn && addTaskModal) {
        openAddTaskBtn.addEventListener('click', function() {
            addTaskModal.classList.remove('hidden');
        });

        closeAddTaskBtn.addEventListener('click', function() {
            addTaskModal.classList.add('hidden');
        });
    }

    const closeEditTaskBtn = document.getElementById('closeEditTaskModal');
    const editTaskModal = document.getElementById('editTaskModal');

    if (closeEditTaskBtn && editTaskModal) {
        closeEditTaskBtn.addEventListener('click', function() {
            editTaskModal.classList.add('hidden');
        });
    }
});

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
;
    