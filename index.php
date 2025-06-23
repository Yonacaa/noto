<?php
session_start();
if (isset($_SESSION['flash_error'])) {
    $flash_error = $_SESSION['flash_error'];
    unset($_SESSION['flash_error']);
} else {
    $flash_error = '';
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>NOTO</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-gray-800 rounded-lg shadow-md p-8">
        <h1 class="text-3xl font-bold mb-6 text-center">NOTO</h1>
        <?php if ($flash_error): ?>
            <div class="mb-4 text-red-500 font-semibold text-center">
                <?php echo htmlspecialchars($flash_error); ?>
            </div>
        <?php endif; ?>
        <div id="loginForm">
            <h2 class="text-2xl font-semibold mb-4">Sign In</h2>
            <form method="post" action="register.php" class="space-y-4">
                <div>
                    <label for="emailLogin" class="block mb-1 font-semibold">Email</label>
                    <input type="email" name="email" id="emailLogin" placeholder="Email" required
                        class="w-full px-3 py-2 rounded-md bg-gray-700 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div>
                    <label for="passwordLogin" class="block mb-1 font-semibold">Password</label>
                    <input type="password" name="password" id="passwordLogin" placeholder="Password" required
                        class="w-full px-3 py-2 rounded-md bg-gray-700 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <button type="submit" name="signIn" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md font-semibold transition">
                    Sign In
                </button>
            </form>
            <p class="mt-4 text-center">
                Don't have an account? <button id="showRegister" class="text-blue-400 hover:underline">Register here</button>
            </p>
        </div>
        <div id="registerForm" class="hidden">
            <h2 class="text-2xl font-semibold mb-4">Register</h2>
            <form method="post" action="register.php" class="space-y-4">
                <div>
                    <label for="fName" class="block mb-1 font-semibold">First Name</label>
                    <input type="text" name="fName" id="fName" placeholder="First Name"
                        class="w-full px-3 py-2 rounded-md bg-gray-700 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div>
                    <label for="lName" class="block mb-1 font-semibold">Last Name</label>
                    <input type="text" name="lName" id="lName" placeholder="Last Name"
                        class="w-full px-3 py-2 rounded-md bg-gray-700 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div>
                    <label for="emailRegister" class="block mb-1 font-semibold">Email</label>
                    <input type="email" name="email" id="emailRegister" placeholder="Email" required
                        class="w-full px-3 py-2 rounded-md bg-gray-700 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div>
                    <label for="passwordRegister" class="block mb-1 font-semibold">Password</label>
                    <input type="password" name="password" id="passwordRegister" placeholder="Password" required
                        class="w-full px-3 py-2 rounded-md bg-gray-700 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <button type="submit" name="signUp" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-md font-semibold transition">
                    Register
                </button>
            </form>
            <p class="mt-4 text-center">
                Already have an account? <button id="showLogin" class="text-blue-400 hover:underline">Sign In here</button>
            </p>
        </div>
        <script src="script.js"></script>
</body>
</html>
