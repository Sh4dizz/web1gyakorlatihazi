<?php
require_once 'database.php';

if (!isset($_SESSION['user'])) {
    echo '<div class="alert alert-danger">Az üzenetek megtekintéséhez be kell jelentkeznie!</div>';
    echo '<p>Kérjük, <a href="index.php?page=login" class="btn btn-primary">jelentkezzen be</a> az üzenetek megtekintéséhez.</p>';
} else {
    try {
        $dbh = get_db_connection();
        $stmt = $dbh->prepare("SELECT m.*, u.firstname, u.lastname, u.username 
                              FROM messages m 
                              LEFT JOIN users u ON m.user_id = u.id 
                              ORDER BY m.sent_at DESC");
        $stmt->execute();
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-4">Üzenetek</h1>
            </div>
        </div>

        <?php if (empty($messages)): ?>
            <div class="alert alert-info">Még nincsenek üzenetek az adatbázisban.</div>
        <?php else: ?>
            <div class="card mb-5">
                <div class="card-header">
                    <h2 class="m-0">Beérkezett üzenetek</h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Dátum</th>
                                    <th>Név</th>
                                    <th>E-mail</th>
                                    <th>Tárgy</th>
                                    <th>Üzenet</th>
                                    <th>Küldő</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $message): ?>
                                    <tr>
                                        <td><?= date('Y-m-d H:i', strtotime($message['sent_at'])) ?></td>
                                        <td><?= htmlspecialchars($message['name']) ?></td>
                                        <td><a href="mailto:<?= htmlspecialchars($message['email']) ?>"><?= htmlspecialchars($message['email']) ?></a></td>
                                        <td><?= htmlspecialchars($message['subject']) ?></td>
                                        <td><?= nl2br(htmlspecialchars($message['message'])) ?></td>
                                        <td>
                                            <?php
                                            if ($message['user_id']) {
                                                echo '<span class="badge bg-primary">' . htmlspecialchars($message['lastname'] . ' ' . $message['firstname'] . ' (' . $message['username'] . ')') . '</span>';
                                            } else {
                                                echo '<span class="badge bg-secondary">Vendég</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="m-0">Üzenetek száma</h3>
                        </div>
                        <div class="card-body text-center">
                            <h1 class="display-4"><?= count($messages) ?></h1>
                            <p class="card-text">összes beérkezett üzenet</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="m-0">Regisztrált felhasználók</h3>
                        </div>
                        <div class="card-body text-center">
                            <?php
                            $registered_count = 0;
                            foreach ($messages as $message) {
                                if ($message['user_id']) {
                                    $registered_count++;
                                }
                            }
                            ?>
                            <h1 class="display-4"><?= $registered_count ?></h1>
                            <p class="card-text">regisztrált felhasználók üzenetei</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="m-0">Vendégek</h3>
                        </div>
                        <div class="card-body text-center">
                            <h1 class="display-4"><?= count($messages) - $registered_count ?></h1>
                            <p class="card-text">vendégek által küldött üzenet</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
<?php
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Hiba történt az üzenetek betöltése során.</div>';
    }
}
?>