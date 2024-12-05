<?php
session_start();
require '../database_connection.php';

try {
    $stmt = getDbConnection()->prepare("SELECT * FROM Dostawcy");
    $stmt->execute();
    $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];

        $delete_stmt = getDbConnection()->prepare("DELETE FROM Dostawcy WHERE dostawca_id = ?");
        $delete_stmt->execute([$delete_id]);

        $_SESSION['success_message'] = "Dostawca został usunięty.";
        header("Location: suppliersManagement.php");
        exit;
    }
} catch (PDOException $e) {
    echo "Wystąpił błąd podczas pobierania danych dostawców: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie Dostawcami</title>
    <link rel="stylesheet" href="../Style/style_suppliersManagement.css">
</head>
<body>
<div class="index-button-container">
    <a href="stockManagement.php" class="index-button">◄  Do zarządzania magazynem</a>
</div>
<header class="header">
    <h1>Zarządzanie Dostawcami</h1>
</header>

<main class="main-content">

    <?php if (isset($_SESSION['success_message'])): ?>
        <p class="success-message"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
    <?php endif; ?>

    <table class="suppliers-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nazwa Dostawcy</th>
            <th>Osoba Kontaktowa</th>
            <th>Numer Telefonu</th>
            <th>Email</th>
            <th>Adres</th>
            <th>Akcje</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($suppliers): ?>
            <?php foreach ($suppliers as $supplier): ?>
                <tr>
                    <td><?php echo htmlspecialchars($supplier['dostawca_id']); ?></td>
                    <td><?php echo htmlspecialchars($supplier['nazwa_dostawcy']); ?></td>
                    <td><?php echo htmlspecialchars($supplier['osoba_kontaktowa']); ?></td>
                    <td><?php echo htmlspecialchars($supplier['numer_telefonu']); ?></td>
                    <td><?php echo htmlspecialchars($supplier['email']); ?></td>
                    <td><?php echo htmlspecialchars($supplier['adres']); ?></td>
                    <td>
                        <a href="editSupplier.php?id=<?php echo $supplier['dostawca_id']; ?>">Edytuj</a> |
                        <a href="?delete_id=<?php echo $supplier['dostawca_id']; ?>" onclick="return confirm('Czy na pewno chcesz usunąć tego dostawcę?');">Usuń</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Brak dostawców w bazie danych.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="addSupplier.php" class="add-supplier-btn">Dodaj Nowego Dostawcę</a>
</main>

<footer>
    <p>&copy; 2024 Budex Sp z.o.o. Wszelkie prawa zastrzeżone.</p>
</footer>
</body>
</html>
