<?php
$upload_dir = 'assets/uploads';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$success_message = '';
$error_message = '';

if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $name = basename($_FILES['image']['name']);

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($tmp_name);

        if (in_array($file_type, $allowed_types)) {
            $new_name = uniqid() . '_' . $name;
            $destination = $upload_dir . '/' . $new_name;

            if (move_uploaded_file($tmp_name, $destination)) {
                $success_message = 'A kép feltöltése sikeres!';
            } else {
                $error_message = 'Hiba történt a kép feltöltése során.';
            }
        } else {
            $error_message = 'Csak JPG, PNG és GIF formátumú képeket lehet feltölteni.';
        }
    }
}

$uploaded_images = [];
if (file_exists($upload_dir) && is_dir($upload_dir)) {
    $files = scandir($upload_dir);

    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $file_path = $upload_dir . '/' . $file;
            $file_type = mime_content_type($file_path);

            if (strpos($file_type, 'image/') === 0) {
                $uploaded_images[] = $file;
            }
        }
    }
}
?>

<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">Képgaléria</h1>
    </div>
</div>

<?php if ($success_message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
<?php endif; ?>

<?php if ($error_message): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['user'])): ?>
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Kép feltöltése</h2>
                </div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" id="uploadForm" class="row g-3">
                        <div class="col-md-8">
                            <label for="image" class="form-label">Válasszon egy képet:</label>
                            <input class="form-control" type="file" id="image" name="image" accept="image/*" required>
                            <div class="form-text">Megengedett formátumok: JPG, PNG, GIF. Max méret: 5MB.</div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Feltöltés</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info mb-5">
        A képek feltöltéséhez kérjük, <a href="index.php?page=login">jelentkezzen be</a>.
    </div>
<?php endif; ?>

<?php if (!empty($uploaded_images)): ?>
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Feltöltött képek</h2>
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-4 g-4">
                        <?php foreach ($uploaded_images as $image): ?>
                            <div class="col">
                                <div class="card h-100 gallery-card">
                                    <img src="<?= htmlspecialchars($upload_dir . '/' . $image) ?>" class="card-img-top" alt="Galéria kép">
                                    <div class="card-footer text-center">
                                        <small class="text-muted"><?= htmlspecialchars(substr($image, strpos($image, '_') + 1)) ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const uploadForm = document.getElementById('uploadForm');
        if (uploadForm) {
            uploadForm.addEventListener('submit', function(event) {
                const fileInput = document.getElementById('image');
                const file = fileInput.files[0];

                if (!file) {
                    event.preventDefault();
                    alert('Kérjük, válasszon ki egy képet!');
                    return;
                }

                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    event.preventDefault();
                    alert('Csak JPG, PNG és GIF formátumú képeket lehet feltölteni.');
                }

                const maxFileSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxFileSize) {
                    event.preventDefault();
                    alert('A kép mérete nem haladhatja meg az 5MB-ot.');
                }
            });
        }
    });
</script>