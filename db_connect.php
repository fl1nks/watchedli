<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watched</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            color: #fff;
            background: #000000 url(1234.gif) no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 90px;
            background-color:rgba(30, 30, 30, 0.7);
            z-index: 1000;
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0 20px;
            height: 100%;
        }
        .left-section {
            font-weight: bold;
            padding-top: 20px;
        }
        .nav-block {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            margin-top: 10px;
        }
        .nav-top {
            display: flex;
            align-items: center;
        }
        .nav-top a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            cursor: pointer;
            font-size: 1rem;
            padding: 6px 14px;
        }
        .nav-top a:hover {
            text-decoration: underline;
        }
        .register-buttons {
            display: flex;
            margin-top: 5px;
        }
        .register-btn {
            background: #222;
            color: #fff;
            border: 1px solid #3498db;
            border-radius: 5px;
            padding: 5px 12px;
            font-size: 0.9rem;
            margin-left: 10px;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            text-decoration: none;
        }
        .register-btn:hover {
            background: #3498db;
            color: #fff;
        }
        h3,h1{
            color:  #3498db;
        }
        .welcome-container {
            margin-top: 110px;
            background-color:rgba(30, 30, 30, 0.7);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 80%;
            max-width: 600px;
            box-sizing: border-box;
        }
        .features {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        .feature {
            text-align: center;
        }
        .login-form-container {
            margin-top: 20px;
            background-color: rgba(30, 30, 30, 0.7);
            padding: 30px;
            border-radius: 10px;
            z-index: 1001;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
            width: 80%;
            max-width: 400px;
            box-sizing: border-box;
            text-align: center;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        .login-form {
            text-align: center;
        }
        .login-form h2 {
            margin-bottom: 20px;
        }
        .login-form input {
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 3px;
            border: 1px solid #444;
            background-color: #333;
            color: #fff;
            width: 100%;
            box-sizing: border-box;
        }
        .login-form button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
        }
        .login-form button:hover {
            background-color: #0056b3;
        }
        .hidden {
            display: none;
        }
        .show {
            opacity: 1;
            visibility: visible;
            display: block;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>

<header>
    <div class="header-container">
        <div class="left-section">| Компьютерный клуб - Watched |</div>
        <div class="nav-block">
            <div class="nav-top">
                <a href="#" id="user-login-btn">Вход для пользователей</a>
                <a href="#" id="employee-login-btn">Вход для сотрудников</a>
                <a href="https://t.me/Saportik0">Поддержка</a>
            </div>
            <div class="register-buttons">
                <a href="#" class="register-btn" id="user-register-btn">Регистрация пользователя</a>
                <a href="#" class="register-btn" id="employee-register-btn">Регистрация сотрудника</a>
            </div>
        </div>
    </div>
</header>

<div class="welcome-container">
    <h1>Добро пожаловать в компьютерный клуб Watched!</h1>
    <p>Мы предлагаем вам уникальную возможность погрузиться в мир технологий и гейминга.</p>
    <div class="features">
        <div class="feature">
            <h3>Гейминг</h3>
            <p>Играйте в самые популярные игры на наших мощных компьютерах.</p>
        </div>
        <div class="feature">
            <h3>Программирование</h3>
            <p>Учитесь программировать и разрабатывать свои собственные проекты.</p>
        </div>
        <div class="feature">
            <h3>Обслуживание ПК</h3>
            <p>Узнайте, как собирать и обслуживать компьютеры.</p>
        </div>
    </div>
</div>


<!-- Вход для пользователей -->
<div id="user-login-form" class="login-form-container hidden">
    <div class="login-form">
        <h2>Вход для пользователей</h2>
        <form method="POST" action="authenticate.php">
            <input type="text" name="surname" required placeholder="Фамилия">
            <input type="email" name="email" required placeholder="Email">
            <button type="submit">Войти</button>
        </form>
    </div>
</div>

<!-- Вход для сотрудников -->
<div id="employee-login-form" class="login-form-container hidden">
    <div class="login-form">
        <h2>Вход для сотрудников</h2>
        <form method="POST" action="authenticate2.php">
            <input type="text" name="login" id="login" required placeholder="Логин">
            <input type="password" name="password" id="password" required placeholder="Пароль">
            <button>Войти</button>
        </form>
    </div>
</div>

<!-- Регистрация пользователя -->
<div id="user-register-form" class="login-form-container hidden">
    <div class="login-form">
        <h2>Регистрация пользователя</h2>
        <form method="POST" action="user_register.php">
            <input type="text" name="surname" required placeholder="Фамилия">
            <input type="email" name="email" required placeholder="Email">
            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>
</div>

<!-- Регистрация сотрудника -->
<div id="employee-register-form" class="login-form-container hidden">
    <div class="login-form">
        <h2>Регистрация сотрудника</h2>
        <form method="POST" action="employee_register.php">
            <input type="text" name="login" required placeholder="Логин">
            <input type="password" name="password" required placeholder="Пароль">
            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>
</div>

<script>
    // Функция для показа формы и скрытия других
    function showLoginForm(formId) {
        document.querySelectorAll('.login-form-container').forEach(form => {
            form.classList.remove('show');
        });
        document.getElementById(formId).classList.add('show');
    }

    // Привязка событий к кнопкам
    document.getElementById('user-login-btn').addEventListener('click', function(event) {
        event.preventDefault();
        showLoginForm('user-login-form');
    });
    document.getElementById('employee-login-btn').addEventListener('click', function(event) {
        event.preventDefault();
        showLoginForm('employee-login-form');
    });
    document.getElementById('user-register-btn').addEventListener('click', function(event) {
        event.preventDefault();
        showLoginForm('user-register-form');
    });
    document.getElementById('employee-register-btn').addEventListener('click', function(event) {
        event.preventDefault();
        showLoginForm('employee-register-form');
    });

    // Закрытие формы по клику вне ее
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('login-form-container')) {
            event.target.classList.remove('show');
        }
    });

    // Маскирование электронной почты (для входа пользователя)
    
</script>
</body>
</html>
