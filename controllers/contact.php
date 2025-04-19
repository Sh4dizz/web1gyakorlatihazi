<?php
require_once 'database.php';

$success = '';
$error = '';
$form_data = [
    'name' => '',
    'email' => '',
    'subject' => '',
    'message' => ''
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_data = [
        'name' => sanitize_input($_POST['name'] ?? ''),
        'email' => sanitize_input($_POST['email'] ?? ''),
        'subject' => sanitize_input($_POST['subject'] ?? ''),
        'message' => sanitize_input($_POST['message'] ?? '')
    ];

    $errors = [];

    if (empty($form_data['name'])) {
        $errors[] = 'A név mező kitöltése kötelező.';
    }

    if (empty($form_data['email'])) {
        $errors[] = 'Az e-mail mező kitöltése kötelező.';
    } elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Érvénytelen e-mail cím.';
    }

    if (empty($form_data['subject'])) {
        $errors[] = 'A tárgy mező kitöltése kötelező.';
    }

    if (empty($form_data['message'])) {
        $errors[] = 'Az üzenet mező kitöltése kötelező.';
    } elseif (strlen($form_data['message']) < 10) {
        $errors[] = 'Az üzenetnek legalább 10 karakterből kell állnia.';
    }

    if (empty($errors)) {
        try {
            $dbh = get_db_connection();
            $stmt = $dbh->prepare("INSERT INTO messages (name, email, subject, message, user_id) 
                                VALUES (:name, :email, :subject, :message, :user_id)");

            $user_id = null;
            if (isset($_SESSION['user']['id'])) {
                $user_id = $_SESSION['user']['id'];
            }

            $stmt->execute([
                'name' => $form_data['name'],
                'email' => $form_data['email'],
                'subject' => $form_data['subject'],
                'message' => $form_data['message'],
                'user_id' => $user_id
            ]);

            $success = 'Köszönjük! Az üzenetet sikeresen elküldtük.';
            $form_data = [
                'name' => '',
                'email' => '',
                'subject' => '',
                'message' => ''
            ];
        } catch (PDOException $e) {
            $error = 'Hiba történt az üzenet mentése során. Kérjük, próbálja újra.';
        }
    } else {
        $error = implode('<br>', $errors);
    }
}
?>

<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">Kapcsolat</h1>
    </div>
</div>

<div class="row mb-5">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h2 class="m-0">Küldjön nekünk üzenetet</h2>
            </div>
            <div class="card-body">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="post" id="contactForm">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="name" class="form-label">Név</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($form_data['name']) ?>" required>
                            <div class="invalid-feedback" id="nameError"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($form_data['email']) ?>" required>
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Tárgy</label>
                        <input type="text" class="form-control" id="subject" name="subject" value="<?= htmlspecialchars($form_data['subject']) ?>" required>
                        <div class="invalid-feedback" id="subjectError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Üzenet</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required><?= htmlspecialchars($form_data['message']) ?></textarea>
                        <div class="invalid-feedback" id="messageError"></div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-lg">Küldés</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="m-0">Elérhetőségek</h2>
            </div>
            <div class="card-body">
                <address>
                    <strong>Menhelyi Nyilvántartó Központ</strong><br>
                    1111 Budapest, Állatvédő utca 1.<br>
                    <i class="bi bi-telephone"></i> Tel: <a href="tel:+3611234567">+36-111111111</a><br>
                    <i class="bi bi-envelope"></i> Email: <a href="mailto:info@menhelyinyilvantarto.hu">info@menhelyinyilvantarto.hu</a>
                </address>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="m-0">Nyitvatartás</h2>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Hétfő - Péntek:</span>
                        <strong>10:00 - 18:00</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Szombat:</span>
                        <strong>10:00 - 14:00</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Vasárnap:</span>
                        <strong>Zárva</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2 class="m-0 text-center">Hol talál meg minket?</h2>
            </div>
            <div class="card-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2696.3696070221704!2d19.050257576908443!3d47.48121317118475!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4741dc13cba00001%3A0x33bb184502e80f31!2sBudapesti%20M%C5%B1szaki%20%C3%A9s%20Gazdas%C3%A1gtudom%C3%A1nyi%20Egyetem!5e0!3m2!1shu!2shu!4v1713632768498!5m2!1shu!2shu" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contactForm');

        contactForm.addEventListener('submit', function(event) {
            let isValid = true;

            const nameInput = document.getElementById('name');
            const nameError = document.getElementById('nameError');
            if (!nameInput.value.trim()) {
                isValid = false;
                nameInput.classList.add('is-invalid');
                nameError.textContent = 'A név mező kitöltése kötelező.';
            } else {
                nameInput.classList.remove('is-invalid');
            }

            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('emailError');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailInput.value.trim()) {
                isValid = false;
                emailInput.classList.add('is-invalid');
                emailError.textContent = 'Az e-mail mező kitöltése kötelező.';
            } else if (!emailPattern.test(emailInput.value.trim())) {
                isValid = false;
                emailInput.classList.add('is-invalid');
                emailError.textContent = 'Érvénytelen e-mail cím.';
            } else {
                emailInput.classList.remove('is-invalid');
            }

            const subjectInput = document.getElementById('subject');
            const subjectError = document.getElementById('subjectError');
            if (!subjectInput.value.trim()) {
                isValid = false;
                subjectInput.classList.add('is-invalid');
                subjectError.textContent = 'A tárgy mező kitöltése kötelező.';
            } else {
                subjectInput.classList.remove('is-invalid');
            }

            const messageInput = document.getElementById('message');
            const messageError = document.getElementById('messageError');
            if (!messageInput.value.trim()) {
                isValid = false;
                messageInput.classList.add('is-invalid');
                messageError.textContent = 'Az üzenet mező kitöltése kötelező.';
            } else if (messageInput.value.trim().length < 10) {
                isValid = false;
                messageInput.classList.add('is-invalid');
                messageError.textContent = 'Az üzenetnek legalább 10 karakterből kell állnia.';
            } else {
                messageInput.classList.remove('is-invalid');
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    });
</script>