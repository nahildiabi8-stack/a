<?php
session_start();

require_once __DIR__ . '/../Entities/Worker.php';
require_once __DIR__ . '/../Repositories/WorkerRepositories.php';

$pdo = new PDO('mysql:host=localhost;dbname=animal', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$repository = new WorkerRepositories($pdo);

if (isset($_POST['worker_id'])) {

    $workerId = (int) $_POST['worker_id'];

    $worker = $repository->find($workerId);

    if ($worker) {
        $_SESSION['worker_id'] = $worker->getId();
        $_SESSION['gender'] = $worker->getGender();
        $_SESSION['age'] = $worker->getAge();
        $_SESSION['name'] = $worker->getName();
    }

    echo "OK";
    exit;
}