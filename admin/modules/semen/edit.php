<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config/db.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/auth.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/report_helpers.php";

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM semen_reports WHERE id=$id");
$row = $result->fetch_assoc();
if(!$row) die("Not found");

if($_SERVER['REQUEST_METHOD']=="POST"){

$interpretation = classify_who6($_POST);

$stmt = $conn->prepare("
UPDATE semen_reports SET
volume=?, concentration=?, progressive=?, morphology=?, total_motility=?, vitality=?, wbc=?, interpretation=?
WHERE id=?
");

$stmt->bind_param("ddddddssi",
$_POST['volume'],
$_POST['concentration'],
$_POST['progressive'],
$_POST['morphology'],
$_POST['total_motility'],
$_POST['vitality'],
$_POST['wbc'],
$interpretation,
$id
);

$stmt->execute();

header("Location: view.php?id=".$id);
exit;
}

include "../../includes/header.php";
?>

<h2>Edit Semen Report</h2>

<form method="POST">

Volume <input name="volume" value="<?= $row['volume'] ?>"><br>
Concentration <input name="concentration" value="<?= $row['concentration'] ?>"><br>
Progressive <input name="progressive" value="<?= $row['progressive'] ?>"><br>
Morphology <input name="morphology" value="<?= $row['morphology'] ?>"><br>
Vitality <input name="vitality" value="<?= $row['vitality'] ?>"><br>
WBC <input name="wbc" value="<?= $row['wbc'] ?>"><br>

<button>Update Report</button>
</form>