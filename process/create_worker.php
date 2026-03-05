<?php


declare(strict_types=1);

require '../src/Entities/Worker.php';
require '../src/Repositories/WorkerRepositories.php';

$pdo = new PDO('mysql:host=localhost;dbname=animal', 'root', '');
$repository = new WorkerRepositories($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'] ?? '';
    $age = isset($_POST['age']) ? (int) $_POST['age'] : 0;
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $gender = $_POST['gender'] ?? '';

    $worker = new Worker($name, $age, $id,  $gender);

    $repository->save($worker);

    header("Location: ../public/map.php");
    exit;
} else {
    echo "Invalid request method.";
}
