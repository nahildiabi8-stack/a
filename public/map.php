<?php
session_start();


require_once __DIR__ . '/../src/Entities/Worker.php';
require_once __DIR__ . '/../src/Entities/Animals.php';

$pdo = new PDO('mysql:host=localhost;dbname=animal', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$Animals = [
    1  => ['name' => 'Lion',         'age' => 1,  'type' => 'Felins',      'weight' => 190,  'height' => 120, 'img' => 'lion.png'],
    2  => ['name' => 'Tigre',        'age' => 1,  'type' => 'Felins',      'weight' => 220,  'height' => 110, 'img' => 'tigre.png'],
    3  => ['name' => 'Elephant',     'age' => 1,  'type' => 'Pachydermes', 'weight' => 5000, 'height' => 300, 'img' => 'elephant.png'],
    4  => ['name' => 'Girafe',       'age' => 1,  'type' => 'Herbivores',  'weight' => 800,  'height' => 550, 'img' => 'girafe.png'],
    5  => ['name' => 'Zebre',        'age' => 1,  'type' => 'Herbivores',  'weight' => 350,  'height' => 140, 'img' => 'zebre.png'],
    6  => ['name' => 'Gorille',      'age' => 1,  'type' => 'Primates',    'weight' => 180,  'height' => 170, 'img' => 'gorille.png'],
    7  => ['name' => 'Chimpanze',    'age' => 1,  'type' => 'Primates',    'weight' => 50,   'height' => 130, 'img' => 'chimpanze.png'],
    8  => ['name' => 'Rhinoceros',   'age' => 1,  'type' => 'Pachydermes', 'weight' => 2000, 'height' => 180, 'img' => 'rhinoceros.png'],
    9  => ['name' => 'Hippopotame',  'age' => 1,  'type' => 'Pachydermes', 'weight' => 3000, 'height' => 150, 'img' => 'hippopotame.png'],
    10 => ['name' => 'Leopard',      'age' => 1,  'type' => 'Felins',      'weight' => 70,   'height' => 80,  'img' => 'leopard.png'],
    11 => ['name' => 'Guepard',      'age' => 1,  'type' => 'Felins',      'weight' => 55,   'height' => 75,  'img' => 'guepard.png'],
    12 => ['name' => 'Crocodile',    'age' => 1,  'type' => 'Reptiles',    'weight' => 500,  'height' => 40,  'img' => 'crocodile.png'],
    13 => ['name' => 'Anaconda',     'age' => 1,  'type' => 'Reptiles',    'weight' => 90,   'height' => 30,  'img' => 'anaconda.png'],
    14 => ['name' => 'Tortue',       'age' => 1,  'type' => 'Reptiles',    'weight' => 200,  'height' => 50,  'img' => 'tortue.png'],
    15 => ['name' => 'Pingouin',     'age' => 1,  'type' => 'Oiseaux',     'weight' => 15,   'height' => 60,  'img' => 'pingouin.png'],
    16 => ['name' => 'Flamant',      'age' => 1,  'type' => 'Oiseaux',     'weight' => 3,    'height' => 120, 'img' => 'flamant.png'],
    17 => ['name' => 'Perroquet',    'age' => 1,  'type' => 'Oiseaux',     'weight' => 1,    'height' => 35,  'img' => 'perroquet.png'],
    18 => ['name' => 'Autruche',     'age' => 1,  'type' => 'Oiseaux',     'weight' => 120,  'height' => 200, 'img' => 'autruche.png'],
    19 => ['name' => 'Panda',        'age' => 1,  'type' => 'Ursides',     'weight' => 100,  'height' => 120, 'img' => 'panda.png'],
    20 => ['name' => 'Ours Polaire', 'age' => 1,  'type' => 'Ursides',     'weight' => 500,  'height' => 160, 'img' => 'ours_polaire.png'],
    21 => ['name' => 'Loup',         'age' => 1,  'type' => 'Canides',     'weight' => 45,   'height' => 80,  'img' => 'loup.png'],
    22 => ['name' => 'Fennec',       'age' => 1,  'type' => 'Canides',     'weight' => 1,    'height' => 20,  'img' => 'fennec.png'],
    23 => ['name' => 'Dauphin',      'age' => 1,  'type' => 'Marins',      'weight' => 150,  'height' => 50,  'img' => 'dauphin.png'],
    24 => ['name' => 'Requin',       'age' => 1,  'type' => 'Marins',      'weight' => 700,  'height' => 60,  'img' => 'requin.png'],
    25 => ['name' => 'Pieuvre',      'age' => 1,  'type' => 'Marins',      'weight' => 10,   'height' => 30,  'img' => 'pieuvre.png'],
    26 => ['name' => 'Meerkats',     'age' => 1,  'type' => 'Mammiferes',  'weight' => 1,    'height' => 25,  'img' => 'meerkat.png'],
    27 => ['name' => 'Hyene',        'age' => 1,  'type' => 'Canides',     'weight' => 60,   'height' => 80,  'img' => 'hyene.png'],
    28 => ['name' => 'Jaguar',       'age' => 1,  'type' => 'Felins',      'weight' => 90,   'height' => 75,  'img' => 'jaguar.png'],
    29 => ['name' => 'Mandrill',     'age' => 1,  'type' => 'Primates',    'weight' => 35,   'height' => 80,  'img' => 'mandrill.png'],
    30 => ['name' => 'Koala',        'age' => 1,  'type' => 'Marsupiaux',  'weight' => 10,   'height' => 70,  'img' => 'koala.png'],
];

foreach ($Animals as $id => $data) {
    if (isset($_SESSION['animal_age'][$id])) {
        $Animals[$id]['age'] = $_SESSION['animal_age'][$id];
    }
}


$AnimalEmojis = [
    1 => '',
    2 => '',
    3 => '',
    4 => '',
    5 => '',
    6 => '',
    7 => '',
    8 => '',
    9 => '',
    10 => '',
    11 => '',
    12 => '',
    13 => '',
    14 => '',
    15 => '',
    16 => '',
    17 => '',
    18 => '',
    19 => '',
    20 => '',
    21 => '',
    22 => '',
    23 => '',
    24 => '',
    25 => '',
    26 => '',
    27 => '',
    28 => '',
    29 => '',
    30 => '',
];




/* =========================================================
   POST AJAX
========================================================= */
if (isset($_POST['action'])) {

    // ── Auto clean all ─────────────────────────────────────
    if ($_POST['action'] === 'auto_clean_all') {
        $workerId = (int)($_SESSION['worker_id'] ?? 0);
        $cashStmt = $pdo->query("SELECT cash FROM workers WHERE id = $workerId");
        $cashData = $cashStmt->fetch(PDO::FETCH_ASSOC);
        $newCash  = $cashData['cash'] ?? 0;
        $cleaned  = false;
        foreach ($Animals as $id => $animalData) {
            if (isset($_SESSION['cleanTime'][$id])) {
                $elapsed = time() - $_SESSION['cleanTime'][$id];
                if ($elapsed >= ($_SESSION['cleanDelay'][$id] ?? 0)) $_SESSION['isClean'][$id] = false;
            }
            if (!($_SESSION['isClean'][$id] ?? false)) {
                $newCash += 10;
                $delais = [5, 10, 20, 30, 60];
                $_SESSION['isClean'][$id]    = true;
                $_SESSION['cleanTime'][$id]  = time();
                $_SESSION['cleanDelay'][$id] = $delais[array_rand($delais)];
                $cleaned = true;
            }
        }
        $pdo->prepare("UPDATE workers SET cash = ? WHERE id = ?")->execute([$newCash, $workerId]);
        die($cleaned ? "OK" : "NOTHING_TO_CLEAN");
    }

    if ($_POST['action'] === 'auto_feed_all') {
        $workerId = (int)($_SESSION['worker_id'] ?? 0);
        $cashStmt = $pdo->query("SELECT cash FROM workers WHERE id = $workerId");
        $cashData = $cashStmt->fetch(PDO::FETCH_ASSOC);
        $newCash  = $cashData['cash'] ?? 0;
        $fed = false;


        foreach ($Animals as $id => $animalData) {
            if (isset($_SESSION['feedTime'][$id])) {
                $elapsed = time() - $_SESSION['feedTime'][$id];
                if ($elapsed >= ($_SESSION['feedDelay'][$id] ?? 0)) $_SESSION['isHungry'][$id] = true;
            }
            if ($_SESSION['isHungry'][$id] ?? true) {
                $_SESSION['feedCount'][$id] = ($_SESSION['feedCount'][$id] ?? 0) + 1;
                if ($_SESSION['feedCount'][$id] >= 10) {
                    $_SESSION['animal_age'][$id] = ($_SESSION['animal_age'][$id] ?? $Animals[$id]['age']) + 1;
                    $_SESSION['feedCount'][$id]  = 0;

                    // XP au worker quand animal vieillit
                    require_once __DIR__ . '/../src/Repositories/WorkerRepositories.php';
                    $repository   = new WorkerRepositories($pdo);
                    $workerEntity = $repository->find($workerId);
                    if ($workerEntity) {
                        $workerEntity->addXp(100);
                        $repository->update($workerId, $workerEntity);
                    }
                }
                $newCash += 10;
                $delais = [5, 10, 20, 30, 60];
                $_SESSION['isHungry'][$id]  = false;
                $_SESSION['feedTime'][$id]  = time();
                $_SESSION['feedDelay'][$id] = $delais[array_rand($delais)];
                $fed = true;
            }
        }
        $pdo->prepare("UPDATE workers SET cash = ? WHERE id = ?")->execute([$newCash, $workerId]);
        die($fed ? "OK" : "NOTHING_TO_FEED");
    }
    // ── Nourrir ────────────────────────────────────────────
    if ($_POST['action'] === 'map_feed') {
        $animalId = (int)($_POST['animal_id'] ?? 0);
        $workerId = (int)($_SESSION['worker_id'] ?? 0);

        // Vérif timer AVANT tout
        if (isset($_SESSION['feedTime'][$animalId])) {
            $elapsed = time() - $_SESSION['feedTime'][$animalId];
            if ($elapsed >= ($_SESSION['feedDelay'][$animalId] ?? 0)) {
                $_SESSION['isHungry'][$animalId] = true;
            }
        }

        // Déjà rassasié ?
        if (isset($_SESSION['isHungry'][$animalId]) && $_SESSION['isHungry'][$animalId] === false) {
            die(json_encode(['status' => 'ALREADY_FED']));
        }
        $workerEntity = null;
        $levelsGained = 0;

        // Compteur repas → grandir
        $_SESSION['feedCount'][$animalId] = ($_SESSION['feedCount'][$animalId] ?? 0) + 1;
        if ($_SESSION['feedCount'][$animalId] >= 10) {
            $_SESSION['animal_age'][$animalId] = ($_SESSION['animal_age'][$animalId] ?? $Animals[$animalId]['age']) + 1;
            $_SESSION['feedCount'][$animalId]  = 0;

            // ← Animal a vieilli, on donne XP au worker
            require_once __DIR__ . '/../src/Repositories/WorkerRepositories.php';
            $repository   = new WorkerRepositories($pdo);
            $workerEntity = $repository->find($workerId);
            if ($workerEntity) {
                $levelsGained = $workerEntity->addXp(100); // 100 XP par âge gagné
                $repository->update($workerId, $workerEntity);
            }
        }

        // XP


        // Cash
        $animalType = $Animals[$animalId]['type'] ?? '';
        $cashGain   = match ($animalType) {
            'Marins' => 50,
            default => 10
        };
        $cashStmt   = $pdo->query("SELECT cash FROM workers WHERE id = $workerId");
        $cashData   = $cashStmt->fetch(PDO::FETCH_ASSOC);
        $newCash    = ($cashData['cash'] ?? 0) + $cashGain;
        $pdo->prepare("UPDATE workers SET cash = ? WHERE id = ?")->execute([$newCash, $workerId]);

        // Reset timer
        $delais = [5, 10, 20, 30, 60];
        $_SESSION['isHungry'][$animalId]  = false;
        $_SESSION['feedTime'][$animalId]  = time();
        $_SESSION['feedDelay'][$animalId] = $delais[array_rand($delais)];

        die(json_encode([
            'status'    => 'OK',
            'newCash'   => $newCash,
            'cashGain'  => $cashGain,
            'delay'     => $_SESSION['feedDelay'][$animalId],
            'feedCount' => $_SESSION['feedCount'][$animalId],
            'levelsGained' => $levelsGained ?? 0,  // ← nouveau
            'workerLV'     => $workerEntity ? $workerEntity->getLV() : null,

        ]));
    }

    // ── Nettoyer ───────────────────────────────────────────
    if ($_POST['action'] === 'map_clean') {
        $animalId = (int)($_POST['animal_id'] ?? 0);
        $workerId = (int)($_SESSION['worker_id'] ?? 0);

        if (isset($_SESSION['isClean'][$animalId]) && $_SESSION['isClean'][$animalId] === true) {
            die(json_encode(['status' => 'ALREADY_CLEAN']));
        }

        $cashStmt = $pdo->query("SELECT cash FROM workers WHERE id = $workerId");
        $cashData = $cashStmt->fetch(PDO::FETCH_ASSOC);
        $newCash  = ($cashData['cash'] ?? 0) + 10;
        $pdo->prepare("UPDATE workers SET cash = ? WHERE id = ?")->execute([$newCash, $workerId]);

        $delais = [5, 10, 20, 30, 60];
        $_SESSION['isClean'][$animalId]    = true;
        $_SESSION['cleanTime'][$animalId]  = time();
        $_SESSION['cleanDelay'][$animalId] = $delais[array_rand($delais)];

        die(json_encode(['status' => 'OK', 'newCash' => $newCash]));
    }

    // ── Changer worker ─────────────────────────────────────
    if ($_POST['action'] === 'map_set_worker') {
        $_SESSION['worker_id'] = (int)($_POST['worker_id'] ?? 0);
        die(json_encode(['status' => 'OK']));
    }
}

/* =========================================================
   VÉRIFICATION TIMERS
========================================================= */
foreach ($Animals as $id => $data) {
    if (isset($_SESSION['feedTime'][$id])) {
        $elapsed = time() - $_SESSION['feedTime'][$id];
        if ($elapsed >= ($_SESSION['feedDelay'][$id] ?? 0)) $_SESSION['isHungry'][$id] = true;
    }
    if (isset($_SESSION['cleanTime'][$id])) {
        $elapsed = time() - $_SESSION['cleanTime'][$id];
        if ($elapsed >= ($_SESSION['cleanDelay'][$id] ?? 0)) $_SESSION['isClean'][$id] = false;
    }
}

/* =========================================================
   WORKERS
========================================================= */
$workersList = $pdo->query("SELECT id, name, age, gender, LV, XP FROM workers ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);

$workerData      = null;
$currentWorkerId = (int)($_SESSION['worker_id'] ?? 0);
if ($currentWorkerId > 0) {
    $stmt = $pdo->prepare("SELECT id, name, age, gender, cash, has_auto_feeder, has_auto_cleaner, LV, XP FROM workers WHERE id = ?");
    $stmt->execute([$currentWorkerId]);
    $workerData = $stmt->fetch(PDO::FETCH_ASSOC);
}

$currentCash = $workerData['cash'] ?? 0;
$workerName  = $workerData['name'] ?? 'Aucun';

// Dans map.php, définis quels enclos se débloquent à quel niveau
$unlockByLevel = [
    1  => [1],
    2  => [2],
    3  => [3],
    4  => [4],
    5  => [5],
    6  => [6],
    7  => [7],
    8  => [8],
    9  => [9],
    10 => [10],
    11 => [11],
    12 => [12],
    13 => [13],
    14 => [14],
    15 => [15],
    16 => [16],
    17 => [17],
    18 => [18],
    19 => [19],
    20 => [20],
    21 => [21],
    22 => [22],
    23 => [23],
    24 => [24],
    25 => [25],
    26 => [26],
    27 => [27],
    28 => [28],
    29 => [29],
    30 => [30],
];

// Calcule les enclos débloqués selon le niveau actuel du worker
$workerLevel    = $workerData['LV'] ?? 1;
$unlockedIds    = [];
foreach ($unlockByLevel as $requiredLevel => $ids) {
    if ($workerLevel >= $requiredLevel) {
        foreach ($ids as $id) $unlockedIds[] = $id;
    }
}

/* =========================================================
   TABLEAU JS
========================================================= */
$jsAnimals = [];
foreach ($Animals as $id => $a) {
    $jsAnimals[] = [
        'id' => $id,
        'n'  => $a['name'],
        't'  => $a['type'],
        'e'  => $AnimalEmojis[$id] ?? '❓',
        'w'  => $a['weight'],
        'h'  => $a['height'],
        'a'  => $a['age'],
        'hg' => $_SESSION['isHungry'][$id] ?? true,
        'cl' => $_SESSION['isClean'][$id]  ?? false,
        'fc' => $_SESSION['feedCount'][$id] ?? 0,
        'un' => in_array($id, $unlockedIds),
    ];
}
$jsAnimalsJson = json_encode($jsAnimals);
$jsCash        = (int)$currentCash;
$jsWorkerId    = $currentWorkerId;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoo Game — Map</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <script defer src="../assets/js/pixel-sounds.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            user-select: none
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        :root {
            --gold: #f5c518;
            --green: #27ae60;
            --panel: #0d1b2a;
            --panel2: #142030;
            --border: #1e3a55;
            --text: #ccdcec;
            --dim: #4a6a8a;
            --f1: clamp(5px, .55vw, 7px);
            --f2: clamp(6px, .7vw, 8px)
        }

        html,
        body {
            width: 100%;
            height: 100vh;
            overflow: hidden;
            font-family: 'Press Start 2P', 'Courier New', monospace;
            font-size: var(--f1);
            background: var(--panel);
            color: var(--text);
            image-rendering: pixelated
        }

        /* HUD */
        #hud {
            position: relative;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: clamp(4px, .7vw, 12px);
            padding: clamp(5px, .7vh, 9px) clamp(8px, 1vw, 18px);
            background: var(--panel2);
            border-bottom: 3px solid var(--border);
            box-shadow: 0 3px 0 #000;
            flex-shrink: 0;
            flex-wrap: wrap
        }

        .px-chip {
            display: flex;
            align-items: center;
            gap: 5px;
            background: var(--panel);
            border: 2px solid var(--border);
            box-shadow: 2px 2px 0 #000;
            padding: 4px 9px;
            font-size: var(--f1);
            color: var(--gold);
            white-space: nowrap
        }

        .px-chip .ico {
            font-size: clamp(9px, 1.1vw, 14px);
            line-height: 1
        }

        .px-chip strong {
            color: #fff
        }

        .sep {
            width: 2px;
            height: 20px;
            background: var(--border);
            flex-shrink: 0
        }

        #worker-sel {
            font-family: inherit;
            font-size: var(--f1);
            background: var(--panel);
            color: var(--text);
            border: 2px solid var(--border);
            box-shadow: 2px 2px 0 #000;
            padding: 3px 6px;
            cursor: pointer
        }

        #hud-title {
            margin-left: auto;
            font-size: var(--f1);
            color: var(--dim);
            letter-spacing: 3px;
            white-space: nowrap
        }

        /* MAP */
        #map-vp {
            flex: 1;
            position: relative;
            overflow: hidden;
            cursor: grab;
            user-select: none
        }

        #map-vp:active {
            cursor: grabbing
        }

        #world {
            position: absolute;
            width: 2000px;
            height: 1500px;
            transform-origin: 0 0
        }

        #world-bg {
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(90deg, rgba(0, 0, 0, .07) 0, rgba(0, 0, 0, .07) 1px, transparent 1px, transparent 16px), repeating-linear-gradient(0deg, rgba(0, 0, 0, .07) 0, rgba(0, 0, 0, .07) 1px, transparent 1px, transparent 16px), linear-gradient(160deg, #2c7a30 0%, #368c3a 30%, #2a7230 60%, #317535 100%)
        }

        .ph,
        .pv {
            position: absolute
        }

        .ph {
            background: repeating-linear-gradient(90deg, #c8a96e 0, #c8a96e 12px, #a8895a 12px, #a8895a 24px);
            border-top: 3px solid #dfc07a;
            border-bottom: 3px solid #7a5020
        }

        .pv {
            background: repeating-linear-gradient(0deg, #c8a96e 0, #c8a96e 12px, #a8895a 12px, #a8895a 24px);
            border-left: 3px solid #dfc07a;
            border-right: 3px solid #7a5020
        }

        /* ENCLOS */
        .enclos {
            position: absolute;
            cursor: pointer;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            border: 3px solid;
            box-shadow: 3px 3px 0 #000;
            transition: filter .12s
        }

        .enclos::before {
            content: '';
            position: absolute;
            inset: 4px;
            border: 1px dashed rgba(255, 255, 255, .12);
            pointer-events: none;
            z-index: 1
        }

        .enclos:hover {
            filter: brightness(1.3);
            z-index: 50
        }

        .enclos.locked {
            filter: grayscale(.8) brightness(.5);
            cursor: not-allowed
        }

        @keyframes hungerPulse {

            0%,
            100% {
                box-shadow: 3px 3px 0 #000
            }

            50% {
                box-shadow: 3px 3px 0 #000, 0 0 0 5px rgba(200, 20, 20, .55)
            }
        }

        .e-ico {
            font-size: clamp(18px, 2.3vw, 30px);
            line-height: 1;
            filter: drop-shadow(2px 2px 0 rgba(0, 0, 0, .7));
            position: relative;
            z-index: 2
        }

        .e-name {
            font-size: var(--f1);
            color: #fff;
            text-shadow: 1px 1px 0 #000, -1px -1px 0 #000;
            text-align: center;
            position: relative;
            z-index: 2;
            max-width: 88%;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis
        }

        .e-tag {
            font-size: var(--f1);
            padding: 1px 4px;
            border: 1px solid rgba(0, 0, 0, .4);
            box-shadow: 1px 1px 0 #000;
            position: relative;
            z-index: 2;
            white-space: nowrap
        }

        .tag-h {
            background: #5a0808;
            color: #ff7070;
            border-color: #901010
        }

        .tag-ok {
            background: #073a15;
            color: #50e070;
            border-color: #0e7030
        }

        .tag-lk {
            background: #141420;
            color: #505070;
            border-color: #282840
        }

        .lock-ov {
            position: absolute;
            inset: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: clamp(16px, 2vw, 26px);
            background: rgba(0, 0, 0, .45)
        }

        /* THEMES */
        .tf {
            background: #4a2808;
            border-color: #b06018 !important
        }

        .th {
            background: #163816;
            border-color: #389030 !important
        }

        .tp {
            background: #202055;
            border-color: #5555b8 !important
        }

        .tpr {
            background: #3a1808;
            border-color: #a85028 !important
        }

        .tr {
            background: #082808;
            border-color: #289020 !important
        }

        .to {
            background: #081840;
            border-color: #2870d0 !important
        }

        .tu {
            background: #181818;
            border-color: #606060 !important
        }

        .tc {
            background: #2a1a04;
            border-color: #b08020 !important
        }

        .tm {
            background: #081838;
            border-color: #1880c8 !important
        }

        .tma {
            background: #200820;
            border-color: #903090 !important
        }

        .tms {
            background: #2a1804;
            border-color: #c08828 !important
        }

        /* POND */
        .pond {
            position: absolute;
            overflow: hidden;
            border: 3px solid #28b8f0;
            box-shadow: 3px 3px 0 #000;
            border-radius: 40% 60% 55% 45%/50% 45% 55% 50%;
            background: #082848;
            animation: pondPulse 4s ease-in-out infinite
        }

        @keyframes pondPulse {

            0%,
            100% {
                background: #082848
            }

            50% {
                background: #0a3860
            }
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(60, 190, 255, .35);
            animation: rippleAnim 3s linear infinite
        }

        @keyframes rippleAnim {
            0% {
                width: 8px;
                height: 8px;
                opacity: .9;
                transform: translate(-50%, -50%)
            }

            100% {
                width: 70px;
                height: 70px;
                opacity: 0;
                transform: translate(-50%, -50%)
            }
        }

        .tree {
            position: absolute;
            font-size: clamp(13px, 1.7vw, 21px);
            pointer-events: none;
            filter: drop-shadow(2px 3px 0 rgba(0, 0, 0, .5))
        }

        /* MODAL */
        #modal-wrap {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 200;
            background: rgba(0, 0, 0, .72);
            align-items: center;
            justify-content: center
        }

        #modal-wrap.on {
            display: flex
        }

        #modal-box {
            background: var(--panel2);
            border: 3px solid var(--border);
            box-shadow: 6px 6px 0 #000;
            width: clamp(250px, 88vw, 320px);
            padding: clamp(12px, 2vw, 18px);
            position: relative;
            animation: popIn .15s ease-out
        }

        @keyframes popIn {
            from {
                transform: scale(.82);
                opacity: 0
            }

            to {
                transform: scale(1);
                opacity: 1
            }
        }

        #m-close {
            position: absolute;
            top: 8px;
            right: 8px;
            font-family: inherit;
            font-size: var(--f1);
            background: #3a0808;
            border: 2px solid #901010;
            box-shadow: 2px 2px 0 #000;
            color: #ff6060;
            padding: 2px 6px;
            cursor: pointer
        }

        #m-close:hover {
            background: #901010;
            color: #fff
        }

        #m-emoji {
            display: block;
            text-align: center;
            font-size: clamp(32px, 5vw, 52px);
            margin-bottom: 8px
        }

        #m-title {
            text-align: center;
            font-size: var(--f2);
            color: var(--gold);
            text-shadow: 2px 2px 0 #000;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--border)
        }

        .m-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: var(--f1);
            padding: 4px 0;
            border-bottom: 1px solid var(--border);
            color: var(--dim)
        }

        .m-row .v {
            color: var(--text)
        }

        .h-wrap {
            margin: 9px 0 3px
        }

        .h-lbl {
            font-size: var(--f1);
            color: var(--dim);
            margin-bottom: 4px
        }

        .h-bg {
            height: 7px;
            background: var(--panel);
            border: 2px solid var(--border);
            box-shadow: 2px 2px 0 #000
        }

        .h-fill {
            height: 100%;
            transition: width .4s
        }

        .m-btns {
            display: flex;
            gap: 5px;
            margin-top: 10px
        }

        .m-btn {
            flex: 1;
            padding: 7px 3px;
            font-family: inherit;
            font-size: var(--f1);
            border: 2px solid;
            box-shadow: 2px 2px 0 #000;
            cursor: pointer;
            text-align: center;
            transition: filter .1s
        }

        .m-btn:hover {
            filter: brightness(1.22)
        }

        .m-btn:active {
            transform: translate(1px, 1px);
            box-shadow: 1px 1px 0 #000
        }

        .m-btn:disabled {
            opacity: .4;
            cursor: not-allowed;
            filter: none
        }

        .m-feed {
            background: #082a10;
            border-color: #27ae60;
            color: #4ade80
        }

        .m-clean {
            background: #081828;
            border-color: #2980b9;
            color: #60b0ff
        }

        .m-go {
            background: #2a1e00;
            border-color: #c8a000;
            color: var(--gold)
        }

        .m-btn.loading {
            opacity: .6;
            pointer-events: none
        }

        /* BBAR */
        #bbar {
            position: relative;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: clamp(4px, .7vw, 10px);
            padding: clamp(4px, .6vh, 7px) clamp(8px, 1vw, 16px);
            background: var(--panel2);
            border-top: 3px solid var(--border);
            box-shadow: 0 -3px 0 #000;
            flex-shrink: 0;
            flex-wrap: wrap
        }

        .bb {
            font-family: inherit;
            font-size: var(--f1);
            padding: 5px 10px;
            background: var(--panel);
            color: var(--dim);
            border: 2px solid var(--border);
            box-shadow: 2px 2px 0 #000;
            cursor: pointer;
            transition: background .1s, color .1s;
            white-space: nowrap
        }

        .bb:hover,
        .bb.on {
            background: #112238;
            color: var(--text);
            border-color: #3a7aaa
        }

        #zoom-info {
            margin-left: auto;
            font-size: var(--f1);
            color: var(--dim);
            white-space: nowrap
        }

        /* MINIMAP */
        #mmap {
            position: fixed;
            bottom: 50px;
            right: 10px;
            z-index: 101;
            width: 130px;
            height: 90px;
            background: #082018;
            border: 2px solid var(--border);
            box-shadow: 3px 3px 0 #000;
            overflow: hidden
        }

        #mmap-in {
            width: 100%;
            height: 100%;
            position: relative;
            background: #287030
        }

        .mm-dot {
            position: absolute;
            background: rgba(200, 200, 60, .55)
        }

        #mmap-vp {
            position: absolute;
            border: 1px solid rgba(255, 255, 255, .75);
            background: rgba(255, 255, 255, .08);
            pointer-events: none
        }

        /* TOOLTIP */
        #tip {
            position: fixed;
            pointer-events: none;
            z-index: 300;
            display: none;
            background: var(--panel2);
            border: 2px solid var(--border);
            box-shadow: 3px 3px 0 #000;
            padding: 5px 8px;
            font-size: var(--f1);
            color: var(--text);
            line-height: 2.2;
            max-width: 170px
        }

        /* NOTIF */
        #notif {
            position: fixed;
            top: 54px;
            left: 50%;
            transform: translateX(-50%) translateY(-6px);
            background: var(--panel2);
            border: 2px solid var(--green);
            box-shadow: 3px 3px 0 #000;
            color: #4ade80;
            padding: 5px 14px;
            font-size: var(--f1);
            opacity: 0;
            transition: opacity .22s, transform .22s;
            z-index: 500;
            pointer-events: none;
            white-space: nowrap
        }

        #notif.on {
            opacity: 1;
            transform: translateX(-50%) translateY(0)
        }

        #notif.err {
            border-color: #e03030;
            color: #ff7070
        }
    </style>
</head>

<body>

    <audio id="bg-music" loop>
        <source src="../assets/sounds/background.mp3" type="audio/mpeg">
    </audio>

    <!-- HUD -->
    <div id="hud">
        <div class="px-chip">
            <span class="ico"></span>
            <strong id="cash-v"><?= number_format($currentCash, 0, ',', ' ') ?></strong>
        </div>
        <div class="sep"></div>
        <div class="px-chip">
            <span class="ico"></span>
            <select id="worker-sel" onchange="changeWorker(this.value)">
                <?php foreach ($workersList as $w): ?>
                    <option value="<?= $w['id'] ?>" <?= $w['id'] == $currentWorkerId ? 'selected' : '' ?>>
                        <?= htmlspecialchars($w['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="sep"></div>
        <div class="px-chip"><span class="ico"></span>Niveau : <strong><?= $workerData['LV'] ?? 1 ?></strong></div>
        <div class="px-chip"><span class="ico"></span>Animaux : <strong>30</strong></div>
        <div id="hud-title">ZOO GAME — MAP</div>
    </div>

    <!-- MAP -->
    <div id="map-vp">
        <div id="world">
            <div id="world-bg"></div>
        </div>
    </div>

    <!-- BBAR -->
    <div id="bbar">
        <button class="bb on">MAIN</button>
        <a href="./zooShop.php"><button class="bb on">SHOP</button></a>
        <span id="zoom-info"><span id="z-pct">72</span>% · Glisser / Molette</span>
    </div>

    <!-- MODAL -->
    <div id="modal-wrap">
        <div id="modal-box">
            <button id="m-close" onclick="closeModal()">✕ FERMER</button>
            <span id="m-emoji"></span>
            <div id="m-title">—</div>
            <div class="m-row"><span>Type</span> <span class="v" id="m-type">—</span></div>
            <div class="m-row"><span>Âge</span> <span class="v" id="m-age">—</span></div>
            <div class="m-row"><span>Poids</span> <span class="v" id="m-weight">—</span></div>
            <div class="m-row"><span>Taille</span> <span class="v" id="m-height">—</span></div>
            <div class="m-row"><span>Repas</span> <span class="v" id="m-meals">— / 10</span></div>
            <div class="m-row"><span>Statut</span> <span class="v" id="m-status">—</span></div>
            <div class="m-row"><span>Propreté</span> <span class="v" id="m-clean">—</span></div>

            <!-- Barre REPAS (jaune) -->

            <!-- Barre FAIM (rouge/vert) -->
            <div class="h-wrap">
                <div class="h-lbl">FAIM</div>
                <div class="h-bg">
                    <div id="h-fill" class="h-fill" style="width:50%;background:#c0392b"></div>
                </div>
            </div>

            <div class="m-btns">
                <button class="m-btn m-feed" id="btn-feed" onclick="feedAnimal()">NOURRIR</button>
                <button class="m-btn m-clean" id="btn-clean" onclick="cleanAnimal()">NETTOYER</button>
                <button class="m-btn m-go" onclick="goToAnimal()">GÉRER</button>
            </div>
        </div>
    </div>

    <!-- MINIMAP -->
    <div id="mmap">
        <div id="mmap-in">
            <div id="mmap-vp"></div>
        </div>
    </div>
    <div id="tip"></div>
    <div id="notif"></div>

    <script>
        const bgMusic = document.getElementById('bg-music');
        document.addEventListener('click', () => {
            if (bgMusic.paused) {
                bgMusic.volume = 0.4;
                bgMusic.play();
            }
        }, {
            once: true
        });

        const ANIMALS = <?= $jsAnimalsJson ?>;
        let cash = <?= $jsCash ?>;
        let workerId = <?= $jsWorkerId ?>;

        const LAYOUT = [
            [1, 65, 65, 140, 120],
            [2, 230, 65, 140, 120],
            [3, 395, 65, 155, 120],
            [4, 575, 65, 145, 120],
            [5, 745, 65, 140, 120],
            [6, 910, 65, 140, 120],
            [7, 65, 218, 128, 116],
            [8, 215, 218, 155, 116],
            [9, 393, 218, 155, 116],
            [10, 572, 218, 136, 116],
            [11, 730, 218, 136, 116],
            [12, 888, 218, 148, 116],
            [15, 65, 367, 130, 116],
            [16, 220, 367, 135, 116],
            [19, 380, 367, 135, 116],
            [20, 540, 367, 145, 116],
            [21, 710, 367, 135, 116],
            [23, 870, 367, 146, 116],
            [13, 65, 516, 135, 116],
            [14, 225, 516, 135, 116],
            [17, 385, 516, 125, 116],
            [18, 535, 516, 140, 116],
            [22, 700, 516, 125, 116],
            [24, 850, 516, 140, 116],
            [25, 65, 665, 130, 116],
            [26, 220, 665, 135, 116],
            [27, 380, 665, 135, 116],
            [28, 540, 665, 135, 116],
            [29, 700, 665, 135, 116],
            [30, 860, 665, 135, 116],
        ];

        const TC = {
            Felins: 'tf',
            Herbivores: 'th',
            Pachydermes: 'tp',
            Primates: 'tpr',
            Reptiles: 'tr',
            Oiseaux: 'to',
            Ursides: 'tu',
            Canides: 'tc',
            Marins: 'tm',
            Mammiferes: 'tma',
            Marsupiaux: 'tms'
        };

        const W = document.getElementById('world');
        let activeA = null;

        function mk(tag, cls) {
            const d = document.createElement(tag);
            if (cls) d.className = cls;
            return d;
        }

        // Chemins
        [
            [65, 190, 985, 24, 'ph'],
            [35, 50, 18, 750, 'pv'],
            [1022, 50, 18, 750, 'pv'],
            [65, 340, 985, 24, 'ph'],
            [65, 489, 985, 24, 'ph'],
            [65, 638, 985, 24, 'ph'],
            [65, 787, 985, 24, 'ph']
        ].forEach(([x, y, w, h, c]) => {
            const d = mk('div', c);
            d.style.cssText = `left:${x}px;top:${y}px;width:${w}px;height:${h}px`;
            W.appendChild(d);
        });

        // Étang
        const pond = mk('div', 'pond');
        pond.style.cssText = 'left:1080px;top:180px;width:175px;height:130px';
        pond.innerHTML = '<div class="ripple" style="top:50%;left:40%"></div><div class="ripple" style="top:30%;left:65%;animation-delay:-1.6s"></div><span style="position:absolute;font-size:16px;top:38%;left:25%">🐟</span><span style="position:absolute;font-size:13px;top:54%;left:55%">🐠</span>';
        W.appendChild(pond);

        // Arbres
        [
            [1070, 60],
            [1115, 60],
            [1170, 60],
            [1230, 60],
            [1290, 60],
            [1350, 60],
            [1070, 360],
            [1170, 360],
            [1280, 360],
            [1070, 540],
            [1200, 540],
            [1330, 540],
            [1070, 700],
            [1200, 700],
            [30, 60],
            [30, 210],
            [30, 365],
            [30, 520],
            [30, 680],
            [30, 820]
        ].forEach(([tx, ty]) => {
            const t = mk('div', 'tree');
            t.style.cssText = `left:${tx}px;top:${ty}px`;
            t.textContent = Math.random() > .4 ? '🌳' : '🌲';
            W.appendChild(t);
        });

        // Enclos
        const mmIn = document.getElementById('mmap-in');
        LAYOUT.forEach(([id, x, y, w, h]) => {
            const a = ANIMALS.find(a => a.id === id);
            if (!a) return;
            const lk = !a.un,
                th = TC[a.t] || 'tf';
            const d = mk('div', `enclos ${th}${lk?' locked':''}`);
            d.style.cssText = `left:${x}px;top:${y}px;width:${w}px;height:${h}px`;
            d.dataset.id = id;
            if (!lk && a.hg) d.style.animation = 'hungerPulse 1.6s ease-in-out infinite';
            const tCls = lk ? 'tag-lk' : a.hg ? 'tag-h' : 'tag-ok';
            const tTxt = lk ? 'LOCK' : a.hg ? 'Faim' : 'OK';
            d.innerHTML = `<span class="e-ico">${lk?'❓':a.e}</span><span class="e-name">${lk?'???':a.n.toUpperCase()}</span><span class="e-tag ${tCls}">${tTxt}</span>${lk?'<div class="lock-ov">🔒</div>':''}`;
            d.addEventListener('click', () => openModal(id));
            d.addEventListener('mouseenter', e => showTip(e, a, lk));
            d.addEventListener('mouseleave', hideTip);
            W.appendChild(d);
            const dot = mk('div', 'mm-dot');
            dot.style.cssText = `left:${(x/2000*130).toFixed(1)}px;top:${(y/1500*90).toFixed(1)}px;width:${(w/2000*130).toFixed(1)}px;height:${(h/1500*90).toFixed(1)}px`;
            mmIn.appendChild(dot);
        });

        /* ══ MODAL ══════════════════════════════════════════════ */
        function openModal(id) {
            const a = ANIMALS.find(a => a.id === id);
            if (!a) return;
            if (!a.un) {
                notify('Enclos verrouillé !');
                return;
            }
            activeA = a;

            $('m-emoji').textContent = a.e;
            $('m-title').textContent = a.n.toUpperCase();
            $('m-type').textContent = a.t;
            $('m-age').textContent = a.a + ' an' + (a.a > 1 ? 's' : '');
            $('m-weight').textContent = a.w + ' kg';
            $('m-height').textContent = a.h + ' cm';
            $('m-meals').textContent = a.fc + ' / 10';



            // Statut faim
            const ms = $('m-status');
            ms.textContent = a.hg ? 'Affamé' : 'Rassasié';
            ms.style.color = a.hg ? '#ff6060' : '#4ade80';

            // Propreté
            $('m-clean').textContent = a.cl ? 'Propre' : 'Sale';
            $('m-clean').style.color = a.cl ? '#4ade80' : '#ff9060';

            // Barre faim rouge/vert
            const pct = a.hg ? 0 : 100;
            $('h-fill').style.width = pct + '%';
            $('h-fill').style.background = a.hg ? '#c0392b' : '#27ae60';

            $('btn-feed').disabled = !a.hg;
            $('btn-clean').disabled = a.cl;
            $('modal-wrap').classList.add('on');
        }

        function closeModal() {
            $('modal-wrap').classList.remove('on');
            activeA = null;
        }
        $('modal-wrap').addEventListener('click', e => {
            if (e.target === $('modal-wrap')) closeModal();
        });

        /* ══ AJAX ═══════════════════════════════════════════════ */
        async function postAction(action, animalId) {
            const form = new FormData();
            form.append('action', action);
            if (animalId !== undefined) form.append('animal_id', animalId);
            const res = await fetch('', {
                method: 'POST',
                body: form
            });
            const text = await res.text();
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Erreur PHP :', text);
                return {
                    status: 'ERROR'
                };
            }
        }

        async function feedAnimal() {
            if (!activeA || !activeA.hg) return;
            const btn = $('btn-feed');
            btn.classList.add('loading');
            btn.textContent = '…';

            const data = await postAction('map_feed', activeA.id);
            btn.classList.remove('loading');
            btn.textContent = 'NOURRIR';

            if (data.status === 'ALREADY_FED') {
                notify('Déjà rassasié !');
                return;
            }
            if (data.status === 'OK') {
                cash = data.newCash;
                refreshCash();

                if (data.levelsGained > 0) {
                    notify(`NIVEAU ${data.workerLV} ! Nouveaux enclos débloqués !`);
                    // Recharge la page pour afficher les nouveaux enclos débloqués
                    setTimeout(() => location.reload(), 2000);
                }

                // Compteur repas correct (pas de % ici, le PHP renvoie la valeur juste)
                activeA.fc = data.feedCount;
                $('m-meals').textContent = activeA.fc + ' / 10';

                activeA.hg = false;
                $('h-fill').style.width = '100%';
                $('h-fill').style.background = '#27ae60';
                notify(`Nourri ! +${data.cashGain} `);
                refreshEnclos(activeA.id);

                // Timer retour de faim côté JS
                const animalId = activeA.id;
                setTimeout(() => {
                    const a = ANIMALS.find(a => a.id === animalId);
                    if (a) {
                        a.hg = true;
                        refreshEnclos(animalId);
                        notify(`${a.n} a faim !`);
                    }
                }, data.delay * 1000);

                closeModal();
            }
        }

        async function cleanAnimal() {
            if (!activeA || activeA.cl) return;
            const btn = $('btn-clean');
            btn.classList.add('loading');
            btn.textContent = '…';

            const data = await postAction('map_clean', activeA.id);
            btn.classList.remove('loading');
            btn.textContent = 'NETTOYER';

            if (data.status === 'ALREADY_CLEAN') {
                notify('Déjà propre !');
                return;
            }
            if (data.status === 'OK') {
                cash = data.newCash;
                refreshCash();
                activeA.cl = true;
                notify('Nettoyé ! +10 cash');
                closeModal();
            }
        }

        function goToAnimal() {
            if (!activeA) return;
            window.location.href = `index.php?goto_animal=${activeA.id}`;
        }

        async function changeWorker(id) {
            workerId = parseInt(id);
            const form = new FormData();
            form.append('action', 'map_set_worker');
            form.append('worker_id', id);
            await fetch('', {
                method: 'POST',
                body: form
            });
            notify('👷 Worker changé !');
        }

        /* ══ REFRESH ════════════════════════════════════════════ */
        function refreshEnclos(id) {
            const a = ANIMALS.find(a => a.id === id);
            const el = document.querySelector(`.enclos[data-id="${id}"]`);
            if (!el || !a) return;
            const tg = el.querySelector('.e-tag');
            if (tg) {
                tg.className = 'e-tag ' + (a.hg ? 'tag-h' : 'tag-ok');
                tg.textContent = a.hg ? 'Faim' : 'OK';
            }
            el.style.animation = a.hg ? 'hungerPulse 1.6s ease-in-out infinite' : 'none';
        }

        function refreshCash() {
            $('cash-v').textContent = cash.toLocaleString('fr-FR');
        }

        function $(id) {
            return document.getElementById(id);
        }

        /* ══ TOOLTIP ════════════════════════════════════════════ */
        const tip = document.getElementById('tip');

        function showTip(e, a, lk) {
            tip.innerHTML = lk ?
                `<strong>VERROUILLÉ</strong><br>Gagnez des niveaux pour débloquer.` :
                `<strong>${a.e} ${a.n.toUpperCase()}</strong><br>${a.t}<br>${a.hg?'Affamé':'Rassasié'} | ${a.cl?'Propre':'Sale'}`;
            tip.style.display = 'block';
            moveTip(e);
        }

        function moveTip(e) {
            tip.style.left = (e.clientX + 13) + 'px';
            tip.style.top = (e.clientY + 13) + 'px';
        }

        function hideTip() {
            tip.style.display = 'none';
        }
        document.addEventListener('mousemove', e => {
            if (tip.style.display === 'block') moveTip(e);
        });

        /* ══ NOTIF ══════════════════════════════════════════════ */
        function notify(m, err = false) {
            const n = $('notif');
            n.textContent = m;
            n.className = 'on' + (err ? ' err' : '');
            clearTimeout(n._t);
            n._t = setTimeout(() => n.className = '', 2200);
        }

        /* ══ PAN & ZOOM ═════════════════════════════════════════ */
        const vp = document.getElementById('map-vp');
        const wl = document.getElementById('world');
        let sc = 0.72,
            ox = 0,
            oy = 0,
            drag = false,
            sx, sy, sox, soy;

        function applyTf() {
            wl.style.transform = `translate(${ox}px,${oy}px) scale(${sc})`;
            $('z-pct').textContent = Math.round(sc * 100);
            const vw = vp.clientWidth,
                vh = vp.clientHeight,
                ww = 2000 * sc,
                wh = 1500 * sc,
                mW = 130,
                mH = 90;
            const dx = Math.max(0, Math.min(mW - 12, (-ox / ww) * mW));
            const dy = Math.max(0, Math.min(mH - 8, (-oy / wh) * mH));
            const dw = Math.min(mW, (vw / ww) * mW),
                dh = Math.min(mH, (vh / wh) * mH);
            $('mmap-vp').style.cssText = `left:${dx}px;top:${dy}px;width:${dw}px;height:${dh}px`;
        }

        vp.addEventListener('mousedown', e => {
            if (e.target.closest('.enclos,.bld,#modal-wrap,#bbar,#hud,#mmap')) return;
            drag = true;
            sx = e.clientX;
            sy = e.clientY;
            sox = ox;
            soy = oy;
        });
        window.addEventListener('mousemove', e => {
            if (!drag) return;
            ox = sox + (e.clientX - sx);
            oy = soy + (e.clientY - sy);
            applyTf();
        });
        window.addEventListener('mouseup', () => drag = false);
        vp.addEventListener('wheel', e => {
            e.preventDefault();
            const d = e.deltaY > 0 ? -.07 : .07,
                ns = Math.max(.3, Math.min(1.9, sc + d));
            const r = vp.getBoundingClientRect(),
                mx = e.clientX - r.left,
                my = e.clientY - r.top;
            ox = mx - (mx - ox) * (ns / sc);
            oy = my - (my - oy) * (ns / sc);
            sc = ns;
            applyTf();
        }, {
            passive: false
        });

        let t1 = null;
        vp.addEventListener('touchstart', e => {
            if (e.touches.length === 1) t1 = {
                x: e.touches[0].clientX,
                y: e.touches[0].clientY,
                ox,
                oy
            };
        });
        vp.addEventListener('touchmove', e => {
            e.preventDefault();
            if (e.touches.length === 1 && t1) {
                ox = t1.ox + (e.touches[0].clientX - t1.x);
                oy = t1.oy + (e.touches[0].clientY - t1.y);
                applyTf();
            }
        }, {
            passive: false
        });

        function initPos() {
            const vpW = vp.offsetWidth || window.innerWidth;
            ox = Math.max(10, (vpW - 2000 * sc) / 2);
            oy = 16;
            applyTf();
        }
        requestAnimationFrame(() => requestAnimationFrame(initPos));

        <?php if ($workerData['has_auto_feeder'] ?? false): ?>
            setInterval(async () => {
                const form = new FormData();
                form.append('action', 'auto_feed_all');
                const text = await (await fetch('', {
                    method: 'POST',
                    body: form
                })).text();
                if (text === 'OK') {
                    notify('Auto-Feeder : animaux nourris !');
                    ANIMALS.forEach(a => {
                        a.hg = false;
                        refreshEnclos(a.id);
                    });
                }
            }, 5000);
        <?php endif; ?>

        <?php if ($workerData['has_auto_cleaner'] ?? false): ?>
            setInterval(async () => {
                const form = new FormData();
                form.append('action', 'auto_clean_all');
                const text = await (await fetch('', {
                    method: 'POST',
                    body: form
                })).text();
                if (text === 'OK') {
                    notify('Auto-Cleaner : enclos nettoyés !');
                    ANIMALS.forEach(a => a.cl = true);
                }
            }, 10000);
        <?php endif; ?>
    </script>
</body>

</html>