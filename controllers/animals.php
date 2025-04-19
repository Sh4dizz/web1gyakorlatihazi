<?php
$animals = [];
$success_message = '';
$error_message = '';

if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add_animal') {
        $name = $_POST['name'] ?? '';
        $type_id = $_POST['type_id'] ?? 0;
        $age = $_POST['age'] ?? 0;
        $gender = $_POST['gender'] ?? '';
        $description = $_POST['description'] ?? '';

        if (empty($name) || empty($type_id) || empty($age) || empty($gender) || empty($description)) {
            $error_message = 'Minden mező kitöltése kötelező!';
        } else {
            try {
                $dbh = get_db_connection();

                $image_path = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = 'assets/uploads/animals';
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }

                    $tmp_name = $_FILES['image']['tmp_name'];
                    $file_name = basename($_FILES['image']['name']);

                    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                    $file_type = mime_content_type($tmp_name);

                    if (in_array($file_type, $allowed_types)) {
                        $new_name = uniqid() . '_' . $file_name;
                        $destination = $upload_dir . '/' . $new_name;

                        if (move_uploaded_file($tmp_name, $destination)) {
                            $image_path = $destination;
                        } else {
                            $error_message = 'Hiba történt a kép feltöltése során.';
                        }
                    } else {
                        $error_message = 'Csak JPG, PNG és GIF formátumú képeket lehet feltölteni.';
                    }
                }

                if (empty($error_message)) {
                    $stmt = $dbh->prepare("INSERT INTO animals (name, type_id, age, gender, description, image_path, arrival_date, adopted) 
                                          VALUES (:name, :type_id, :age, :gender, :description, :image_path, NOW(), FALSE)");

                    $stmt->execute([
                        'name' => $name,
                        'type_id' => $type_id,
                        'age' => $age,
                        'gender' => $gender,
                        'description' => $description,
                        'image_path' => $image_path
                    ]);

                    $success_message = 'Az állat sikeresen hozzáadva az örökbefogadható állatok listájához!';
                }
            } catch (PDOException $e) {
                $error_message = 'Adatbázis hiba történt az állat hozzáadása során.';
            }
        }
    }
}

$animal_types = [];
try {
    $dbh = get_db_connection();
    $stmt = $dbh->prepare("SELECT id, name FROM animal_types ORDER BY name");
    $stmt->execute();
    $animal_types = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
}


try {
    $dbh = get_db_connection();
    $stmt = $dbh->prepare("SELECT a.*, t.name as type_name FROM animals a JOIN animal_types t ON a.type_id = t.id WHERE a.adopted = FALSE ORDER BY a.arrival_date DESC");
    $stmt->execute();
    $animals = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = 'Hiba történt az állatok adatainak lekérése során.';
}
?>

<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">Örökbefogadható állatok</h1>
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
                    <h2>Új örökbefogadható állat hozzáadása</h2>
                </div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" class="row g-3" id="addAnimalForm">
                        <input type="hidden" name="action" value="add_animal">

                        <div class="col-md-6">
                            <label for="name" class="form-label">Állat neve</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="type_id" class="form-label">Faj</label>
                            <select class="form-select" id="type_id" name="type_id" required>
                                <option value="">Válasszon...</option>
                                <?php foreach ($animal_types as $type): ?>
                                    <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="age" class="form-label">Kor (év)</label>
                            <input type="number" class="form-control" id="age" name="age" min="0" max="30" required>
                        </div>

                        <div class="col-md-6">
                            <label for="gender" class="form-label">Nem</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="">Válasszon...</option>
                                <option value="Hím">Hím</option>
                                <option value="Nőstény">Nőstény</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Leírás</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>

                        <div class="col-12">
                            <label for="image" class="form-label">Kép (opcionális)</label>
                            <input class="form-control" type="file" id="image" name="image" accept="image/*">
                            <div class="form-text">Megengedett formátumok: JPG, PNG, GIF. Max méret: 5MB.</div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Hozzáadás</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row mb-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Örökbefogadható állataink</h2>
            </div>
            <div class="card-body">
                <?php if (empty($animals)): ?>
                    <div class="alert alert-info">Jelenleg nincs örökbefogadható állat.</div>
                <?php else: ?>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <?php foreach ($animals as $animal): ?>
                            <div class="col">
                                <div class="card h-100 animal-card">
                                    <?php if (!empty($animal['image_path']) && file_exists($animal['image_path'])): ?>
                                        <img src="<?= htmlspecialchars($animal['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($animal['name']) ?>">
                                    <?php else: ?>
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <span class="text-muted">Nincs kép</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($animal['name']) ?></h5>
                                        <p class="card-text">
                                            <strong>Faj:</strong> <?= htmlspecialchars($animal['type_name']) ?><br>
                                            <strong>Kor:</strong> <?= htmlspecialchars($animal['age']) ?> éves<br>
                                            <strong>Nem:</strong> <?= htmlspecialchars($animal['gender']) ?>
                                        </p>
                                        <p class="card-text"><?= htmlspecialchars(mb_substr($animal['description'], 0, 100)) ?>...</p>
                                        <a href="#" class="btn btn-primary">Részletek</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const animalForm = document.getElementById('addAnimalForm');
        if (animalForm) {
            animalForm.addEventListener('submit', function(event) {
                let isValid = true;

                ['name', 'type_id', 'age', 'gender', 'description'].forEach(function(fieldId) {
                    const field = document.getElementById(fieldId);
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                const fileInput = document.getElementById('image');
                if (fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    const maxSize = 5 * 1024 * 1024; // 5MB

                    if (!allowedTypes.includes(file.type)) {
                        alert('Csak JPG, PNG és GIF formátumú képeket lehet feltölteni.');
                        isValid = false;
                    }

                    if (file.size > maxSize) {
                        alert('A kép mérete nem haladhatja meg az 5MB-ot.');
                        isValid = false;
                    }
                }

                if (!isValid) {
                    event.preventDefault();
                }
            });
        }
    });
</script>