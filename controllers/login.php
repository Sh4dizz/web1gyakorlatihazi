<?php
?>
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">Felhasználói fiók</h1>
    </div>
</div>

<?php if (isset($error) && !empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (isset($success) && !empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="row mb-5">
    <div class="col-md-6 mb-4 mb-md-0">
        <div class="card h-100">
            <div class="card-header">
                <h2 class="m-0">Belépés</h2>
            </div>
            <div class="card-body">
                <form method="post" action="index.php?page=login" id="loginForm">
                    <input type="hidden" name="action" value="login">
                    <div class="mb-3">
                        <label for="login_username" class="form-label">Felhasználónév</label>
                        <input type="text" class="form-control" id="login_username" name="username" required>
                        <div class="invalid-feedback" id="login_username_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="login_password" class="form-label">Jelszó</label>
                        <input type="password" class="form-control" id="login_password" name="password" required>
                        <div class="invalid-feedback" id="login_password_error"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Belépés</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h2 class="m-0">Regisztráció</h2>
            </div>
            <div class="card-body">
                <form method="post" action="index.php?page=login" id="registerForm">
                    <input type="hidden" name="action" value="register">
                    <div class="mb-3">
                        <label for="reg_lastname" class="form-label">Családi név</label>
                        <input type="text" class="form-control" id="reg_lastname" name="lastname" required>
                        <div class="invalid-feedback" id="reg_lastname_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="reg_firstname" class="form-label">Utónév</label>
                        <input type="text" class="form-control" id="reg_firstname" name="firstname" required>
                        <div class="invalid-feedback" id="reg_firstname_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="reg_username" class="form-label">Felhasználónév</label>
                        <input type="text" class="form-control" id="reg_username" name="username" required>
                        <div class="invalid-feedback" id="reg_username_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="reg_password" class="form-label">Jelszó</label>
                        <input type="password" class="form-control" id="reg_password" name="password" required>
                        <div class="invalid-feedback" id="reg_password_error"></div>
                        <div class="form-text">A jelszónak legalább 6 karakterből kell állnia.</div>
                    </div>
                    <button type="submit" class="btn btn-success">Regisztráció</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            let isValid = true;
            const username = document.getElementById('login_username');
            const password = document.getElementById('login_password');
            const usernameError = document.getElementById('login_username_error');
            const passwordError = document.getElementById('login_password_error');

            if (!username.value.trim()) {
                isValid = false;
                username.classList.add('is-invalid');
                usernameError.textContent = 'A felhasználónév megadása kötelező.';
            } else {
                username.classList.remove('is-invalid');
            }

            if (!password.value.trim()) {
                isValid = false;
                password.classList.add('is-invalid');
                passwordError.textContent = 'A jelszó megadása kötelező.';
            } else {
                password.classList.remove('is-invalid');
            }

            if (!isValid) {
                event.preventDefault();
            }
        });

        document.getElementById('registerForm').addEventListener('submit', function(event) {
            let isValid = true;

            const lastname = document.getElementById('reg_lastname');
            const lastnameError = document.getElementById('reg_lastname_error');
            if (!lastname.value.trim()) {
                isValid = false;
                lastname.classList.add('is-invalid');
                lastnameError.textContent = 'A családi név megadása kötelező.';
            } else {
                lastname.classList.remove('is-invalid');
            }

            const firstname = document.getElementById('reg_firstname');
            const firstnameError = document.getElementById('reg_firstname_error');
            if (!firstname.value.trim()) {
                isValid = false;
                firstname.classList.add('is-invalid');
                firstnameError.textContent = 'Az utónév megadása kötelező.';
            } else {
                firstname.classList.remove('is-invalid');
            }

            const username = document.getElementById('reg_username');
            const usernameError = document.getElementById('reg_username_error');
            if (!username.value.trim()) {
                isValid = false;
                username.classList.add('is-invalid');
                usernameError.textContent = 'A felhasználónév megadása kötelező.';
            } else if (username.value.trim().length < 4) {
                isValid = false;
                username.classList.add('is-invalid');
                usernameError.textContent = 'A felhasználónévnek legalább 4 karakterből kell állnia.';
            } else {
                username.classList.remove('is-invalid');
            }


            const password = document.getElementById('reg_password');
            const passwordError = document.getElementById('reg_password_error');
            if (!password.value.trim()) {
                isValid = false;
                password.classList.add('is-invalid');
                passwordError.textContent = 'A jelszó megadása kötelező.';
            } else if (password.value.trim().length < 6) {
                isValid = false;
                password.classList.add('is-invalid');
                passwordError.textContent = 'A jelszónak legalább 6 karakterből kell állnia.';
            } else {
                password.classList.remove('is-invalid');
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    });
</script>