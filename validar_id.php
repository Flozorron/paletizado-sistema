<?php
include 'db.php';
$id_palet = $_GET['id_palet'] ?? '';
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM palets WHERE id_palet = ?");
$stmt->bind_param("s", $id_palet);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
echo ($row['total'] > 0) ? 'DUPLICADO' : 'OK';
