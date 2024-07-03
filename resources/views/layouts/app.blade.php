<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library App</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
        <ul>
            <li><a href="#" id="home-link">Home</a></li>
            <li><a href="#" id="login-link">Login</a></li>
            <li><a href="#" id="register-link">Register</a></li>
            <li><a href="#" id="logout-link">Logout</a></li>
        </ul>
    </nav>
    <div id="search-container">
        <form id="search-form">
            <input type="text" id="search-input" placeholder="Search...">
            <button type="submit">Search</button>
        </form>
    </div>
    <div id="content"></div>
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>