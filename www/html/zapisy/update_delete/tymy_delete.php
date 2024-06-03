<?php
// Připojení k databázi
$servername = "database";
$username = "admin";
$password = "heslo";
$database = "nba";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Nepodařilo se připojit k databázi: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Mazání týmu podle ID
    $sql = "DELETE FROM Tymy WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Tým byl úspěšně smazán.";
    } else {
        echo "Chyba při mazání týmu: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<a href="../xmlUpravy.php">Zpět</a>