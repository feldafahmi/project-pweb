<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | MARK-UP</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/auth.css'])
</head>
<body>

    <div class="auth-container">
        
        <div class="auth-form-section">
            <div class="auth-content">
                
                <a href="/" class="auth-brand">
                    <img src="{{ asset('img/Markup-Logo.png') }}" alt="Logo">
                    <span>MARK-UP</span>
                </a>

                <h1>Welcome to Mark-Up</h1>
                <p class="auth-subtitle">Please enter your details to create new account</p>

                <div class="auth-toggle">
                    <a href="{{ route('login') }}" class="toggle-btn">Sign In</a>
                    <a href="#" class="toggle-btn active">Sign Up</a>
                </div>

                <form action="{{ route('register.store') }}" method="POST" class="main-form">
                    @csrf
                    
                    <div class="input-field">
                        <label>Full Name</label>
                        <div class="input-group">
                            <i class="far fa-user"></i>
                            <input type="text" name="name" placeholder="Enter your full name" required>
                        </div>
                    </div>

                    <div class="input-field">
                        <label>Email Address</label>
                        <div class="input-group">
                            <i class="far fa-envelope"></i>
                            <input type="email" name="email" placeholder="iailrezamp@gmail.com" required>
                            <i class="fas fa-check-circle success-icon"></i>
                        </div>
                    </div>

                    <div class="input-field">
                        <label>Role</label>
                        <div class="input-group">
                            <i class="fas fa-users"></i>
                            <select name="role" required>
                                <option value="" disabled selected>Select your role</option>
                                <option value="student">Student</option>
                                <option value="mentor">Mentor</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-field">
                        <label>Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="Min. 8 characters" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Create Account</button>
                </form>

                <div class="social-auth">
                    <p>Or Create With</p>
                    <div class="social-icons">
                        <a href="#"><img src="https://cdn1.iconfinder.com/data/icons/google_jfk_icons_by_jkdesign/512/google.png" alt="Google"></a>
                        <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="Apple"></a>
                        <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/0/05/Facebook_Logo_2023.png" alt="Facebook"></a>
                    </div>
                </div>

            </div>
        </div>

        <div class="auth-image-section">
            <img src="{{ asset('img/Register-Bg.jpg') }}" alt="Mark-Up Building">
        </div>

    </div>

</body>
</html>