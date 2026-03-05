<?php
session_start();


ini_set('xdebug.var_display_max_depth',    -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data',     -1);

/* =========================================================
   AUTOLOADS
========================================================= */
require_once __DIR__ . '/../src/Entities/Worker.php';
require_once __DIR__ . '/../src/Entities/Animals.php';
require_once __DIR__ . '/../src/Repositories/WorkerRepositories.php';
require_once __DIR__ . '/../src/Repositories/AnimauxRepositories.php';

/* =========================================================
   CONNEXION BDD
========================================================= */
$pdo = new PDO('mysql:host=localhost;dbname=animal', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* =========================================================
   DONNÉES DES ANIMAUX (dispo partout dès le début)
========================================================= */
$Animals = [
    1  => ['name' => 'Lion',         'age' => 1,  'type' => 'Felins',      'weight' => 190,  'height' => 120, 'img' => 'lion.png'],
    2  => ['name' => 'Tigre',        'age' => 1,  'type' => 'Felins',      'weight' => 220,  'height' => 110, 'img' => 'tigre.png'],
    3  => ['name' => 'Elephant',     'age' => 1, 'type' => 'Pachydermes', 'weight' => 5000, 'height' => 300, 'img' => 'elephant.png'],
    4  => ['name' => 'Girafe',       'age' => 1, 'type' => 'Herbivores',  'weight' => 800,  'height' => 550, 'img' => 'girafe.png'],
    5  => ['name' => 'Zebre',        'age' => 1,  'type' => 'Herbivores',  'weight' => 350,  'height' => 140, 'img' => 'zebre.png'],
    6  => ['name' => 'Gorille',      'age' => 1, 'type' => 'Primates',    'weight' => 180,  'height' => 170, 'img' => 'gorille.png'],
    7  => ['name' => 'Chimpanze',    'age' => 1, 'type' => 'Primates',    'weight' => 50,   'height' => 130, 'img' => 'chimpanze.png'],
    8  => ['name' => 'Rhinoceros',   'age' => 1, 'type' => 'Pachydermes', 'weight' => 2000, 'height' => 180, 'img' => 'rhinoceros.png'],
    9  => ['name' => 'Hippopotame',  'age' => 1, 'type' => 'Pachydermes', 'weight' => 3000, 'height' => 150, 'img' => 'hippopotame.png'],
    10 => ['name' => 'Leopard',      'age' => 1,  'type' => 'Felins',      'weight' => 70,   'height' => 80,  'img' => 'leopard.png'],
    11 => ['name' => 'Guepard',      'age' => 1,  'type' => 'Felins',      'weight' => 55,   'height' => 75,  'img' => 'guepard.png'],
    12 => ['name' => 'Crocodile',    'age' => 1, 'type' => 'Reptiles',    'weight' => 500,  'height' => 40,  'img' => 'crocodile.png'],
    13 => ['name' => 'Anaconda',     'age' => 1, 'type' => 'Reptiles',    'weight' => 90,   'height' => 30,  'img' => 'anaconda.png'],
    14 => ['name' => 'Tortue',       'age' => 1, 'type' => 'Reptiles',    'weight' => 200,  'height' => 50,  'img' => 'tortue.png'],
    15 => ['name' => 'Pingouin',     'age' => 1,  'type' => 'Oiseaux',     'weight' => 15,   'height' => 60,  'img' => 'pingouin.png'],
    16 => ['name' => 'Flamant',      'age' => 1,  'type' => 'Oiseaux',     'weight' => 3,    'height' => 120, 'img' => 'flamant.png'],
    17 => ['name' => 'Perroquet',    'age' => 1, 'type' => 'Oiseaux',     'weight' => 1,    'height' => 35,  'img' => 'perroquet.png'],
    18 => ['name' => 'Autruche',     'age' => 1, 'type' => 'Oiseaux',     'weight' => 120,  'height' => 200, 'img' => 'autruche.png'],
    19 => ['name' => 'Panda',        'age' => 1,  'type' => 'Ursides',     'weight' => 100,  'height' => 120, 'img' => 'panda.png'],
    20 => ['name' => 'Ours Polaire', 'age' => 1, 'type' => 'Ursides',     'weight' => 500,  'height' => 160, 'img' => 'ours_polaire.png'],
    21 => ['name' => 'Loup',         'age' => 1,  'type' => 'Canides',     'weight' => 45,   'height' => 80,  'img' => 'loup.png'],
    22 => ['name' => 'Fennec',       'age' => 1,  'type' => 'Canides',     'weight' => 1,    'height' => 20,  'img' => 'fennec.png'],
    23 => ['name' => 'Dauphin',      'age' => 1, 'type' => 'Marins',      'weight' => 150,  'height' => 50,  'img' => 'dauphin.png'],
    24 => ['name' => 'Requin',       'age' => 1, 'type' => 'Marins',      'weight' => 700,  'height' => 60,  'img' => 'requin.png'],
    25 => ['name' => 'Pieuvre',      'age' => 1,  'type' => 'Marins',      'weight' => 10,   'height' => 30,  'img' => 'pieuvre.png'],
    26 => ['name' => 'Meerkats',     'age' => 1,  'type' => 'Mammiferes',  'weight' => 1,    'height' => 25,  'img' => 'meerkat.png'],
    27 => ['name' => 'Hyene',        'age' => 1,  'type' => 'Canides',     'weight' => 60,   'height' => 80,  'img' => 'hyene.png'],
    28 => ['name' => 'Jaguar',       'age' => 1,  'type' => 'Felins',      'weight' => 90,   'height' => 75,  'img' => 'jaguar.png'],
    29 => ['name' => 'Mandrill',     'age' => 1,  'type' => 'Primates',    'weight' => 35,   'height' => 80,  'img' => 'mandrill.png'],
    30 => ['name' => 'Koala',        'age' => 1,  'type' => 'Marsupiaux',  'weight' => 10,   'height' => 70,  'img' => 'koala.png'],
];

// Appliquer les ages sauvegardés en session
foreach ($Animals as $id => $data) {
    if (isset($_SESSION['animal_age'][$id])) {
        $Animals[$id]['age'] = $_SESSION['animal_age'][$id];
    }
}

/* =========================================================
   POST — Sélection du worker
========================================================= */
if (isset($_POST['worker_id'])) {
    $_SESSION['worker_id'] = (int) $_POST['worker_id'];
    $_SESSION['gender']    = $_POST['gender']    ?? null;
    $_SESSION['age']       = $_POST['age']       ?? null;
    $_SESSION['name']      = $_POST['name']      ?? null;
    $_SESSION['animal_id'] = $_POST['animal_id'] ?? null;
    $_SESSION['isHungry']  = $_POST['isHungry']  ?? null;
    die("OK");
}

/* =========================================================
   POST — Actions (next_animal / feed_animal)
========================================================= */
if (isset($_POST['action'])) {

    // --- Changer d'animal ---
    if ($_POST['action'] === 'next_animal') {
        $current = (int)($_SESSION['animal_id'] ?? 1);
        $next    = ($current >= 30) ? 1 : $current + 1;
        $_SESSION['animal_id'] = $next;
        die("NEXT_OK");
    }

    if ($_POST['action'] === 'clean_animal') {
        $current  = (int)($_SESSION['animal_id'] ?? 1);
        $workerId = (int)($_SESSION['worker_id'] ?? 0);

        // Bloquer si déjà propre
        if (isset($_SESSION['isClean'][$current]) && $_SESSION['isClean'][$current] === true) {
            die("ANIMAL_ALREADY_CLEAN");
        }

        // Gain de cash
        $cashStmt = $pdo->query("SELECT cash FROM workers WHERE id = $workerId");
        $cashData = $cashStmt->fetch(PDO::FETCH_ASSOC);
        $newCash  = ($cashData['cash'] ?? 0) + 10;
        $pdo->prepare("UPDATE workers SET cash = ? WHERE id = ?")->execute([$newCash, $workerId]);

        // Reset timer — redevient sale après un délai
        $delais = [15, 30, 60, 90, 120, 150, 180, 210, 600];
        $_SESSION['isClean'][$current]      = true;
        $_SESSION['cleanTime'][$current]    = time();
        $_SESSION['cleanDelay'][$current]   = $delais[array_rand($delais)];

        die("OK");
    }




    if ($_POST['action'] === 'auto_feed_all') {
        $workerId = (int)($_SESSION['worker_id'] ?? 0);

        // ← UNE SEULE fois avant la boucle
        $cashStmt = $pdo->query("SELECT cash FROM workers WHERE id = $workerId");
        $cashData = $cashStmt->fetch(PDO::FETCH_ASSOC);
        $newCash  = $cashData['cash'] ?? 0;
        $fed = false;

        foreach ($Animals as $id => $animalData) {
            if (isset($_SESSION['feedTime'][$id])) {
                $elapsed = time() - $_SESSION['feedTime'][$id];
                if ($elapsed >= $_SESSION['feedDelay'][$id]) {
                    $_SESSION['isHungry'][$id] = true;
                }
            }

            if ($_SESSION['isHungry'][$id] ?? true) {
                $_SESSION['feedCount'][$id] = ($_SESSION['feedCount'][$id] ?? 0) + 1;
                if ($_SESSION['feedCount'][$id] >= 10) {
                    $_SESSION['animal_age'][$id] = ($_SESSION['animal_age'][$id] ?? $Animals[$id]['age']) + 1;
                    $_SESSION['feedCount'][$id]  = 0;
                }

                $newCash += 10; // ← on accumule dans la variable

                $delais = [15, 30, 60, 90, 120, 150, 180, 210, 600];
                $_SESSION['isHungry'][$id]  = false;
                $_SESSION['feedTime'][$id]  = time();
                $_SESSION['feedDelay'][$id] = $delais[array_rand($delais)];
                $fed = true;
            }
        }

        // ← UNE SEULE fois après la boucle
        $pdo->prepare("UPDATE workers SET cash = ? WHERE id = ?")
            ->execute([$newCash, $workerId]);

        die($fed ? "OK" : "NOTHING_TO_FEED");
    }

    if ($_POST['action'] === 'disable_auto_feeder') {
        $workerId = (int)($_SESSION['worker_id'] ?? 0);
        $cashStmt = $pdo->query("SELECT cash FROM workers WHERE id = $workerId");
        $cashData = $cashStmt->fetch(PDO::FETCH_ASSOC);
        $newCash  = ($cashData['cash'] ?? 0) + 100; // remboursement
        $pdo->prepare("UPDATE workers SET cash = ?, has_auto_feeder = 0 WHERE id = ?")
            ->execute([$newCash, $workerId]);
        die("OK");
    }
    // --- Nourrir l'animal ---
    if ($_POST['action'] === 'feed_animal') {
        $current  = (int)($_SESSION['animal_id'] ?? 1);
        $workerId = (int)($_SESSION['worker_id'] ?? 0);
        if (isset($_SESSION['isHungry'][$current]) && $_SESSION['isHungry'][$current] === false) {
            die("ANIMAL_NOT_HUNGRY");
        }


        // Vérif timer : l'animal est-il de nouveau affamé ?
        if (isset($_SESSION['feedTime'][$current])) {
            $elapsed = time() - $_SESSION['feedTime'][$current];
            if ($elapsed >= $_SESSION['feedDelay'][$current]) {
                $_SESSION['isHungry'][$current] = true;
            }
        }


        // Compteur de repas → grandir tous les 10 repas
        $_SESSION['feedCount'][$current] = ($_SESSION['feedCount'][$current] ?? 0) + 1;
        if ($_SESSION['feedCount'][$current] >= 10) {
            $_SESSION['animal_age'][$current] = ($_SESSION['animal_age'][$current] ?? $Animals[$current]['age']) + 1;
            $_SESSION['feedCount'][$current]  = 0;
        }

        // Gain de cash
        $animalType = $Animals[$current]['type'] ?? '';
        $cashGain   = match ($animalType) {
            'Marins'  => 50,
            'Consonnes' => 25,
            default     => 10
        };
        $cashStmt = $pdo->query("SELECT cash FROM workers WHERE id = $workerId");
        $cashData = $cashStmt->fetch(PDO::FETCH_ASSOC);
        $newCash  = ($cashData['cash'] ?? 0) + $cashGain;
        $pdo->prepare("UPDATE workers SET cash = ? WHERE id = ?")->execute([$newCash, $workerId]);

        // Reset timer
        $delais = [15, 30, 60, 90, 120, 150, 180, 210, 600];
        $_SESSION['isHungry'][$current]  = false;
        $_SESSION['feedTime'][$current]  = time();
        $_SESSION['feedDelay'][$current] = $delais[array_rand($delais)];

        die("OK");
    }


    if ($_POST['action'] === 'buy_auto_feeder') {
        $workerId = (int)($_SESSION['worker_id'] ?? 0);
        $cashStmt = $pdo->query("SELECT cash, has_auto_feeder FROM workers WHERE id = $workerId");
        $cashData = $cashStmt->fetch(PDO::FETCH_ASSOC);

        // Déjà acheté ?
        if ($cashData['has_auto_feeder']) {
            die("ALREADY_OWNED");
        }

        if (($cashData['cash'] ?? 0) >= 100) {
            $newCash = $cashData['cash'] - 100;
            $pdo->prepare("UPDATE workers SET cash = ?, has_auto_feeder = 1 WHERE id = ?")
                ->execute([$newCash, $workerId]);
            die("OK");
        }

        die("NOT_ENOUGH_CASH");
    }

    if ($_POST['action'] === 'buy_cleaner') {
        if ($c) {
            # code...
        }
    }

    if ($_POST['action'] === 'buy_enclos') {
        if ($c) {
            # code...
        }
    }
}

/* =========================================================
   CHARGEMENT DE L'ANIMAL COURANT
========================================================= */
$Animalnum = (int)($_SESSION['animal_id'] ?? 1);
if (!isset($Animals[$Animalnum])) $Animalnum = 1;
$Animal = $Animals[$Animalnum];

/* =========================================================
   VÉRIFICATION TIMER (au chargement de la page)
========================================================= */
if (isset($_SESSION['feedTime'][$Animalnum])) {
    $elapsed = time() - $_SESSION['feedTime'][$Animalnum];
    if ($elapsed >= $_SESSION['feedDelay'][$Animalnum]) {
        $_SESSION['isHungry'][$Animalnum] = true;
    }
}

/* =========================================================
   RÉCUPÉRATION DES WORKERS
========================================================= */
$workersList = $pdo->query("SELECT id, name, age, gender FROM workers ORDER BY id")
    ->fetchAll(PDO::FETCH_ASSOC);

$cash = $pdo->query("SELECT cash FROM workers WHERE id = " . (int)($_SESSION['worker_id'] ?? 0))
    ->fetch(PDO::FETCH_ASSOC);

/* =========================================================
   CRÉATION DES OBJETS
========================================================= */
$animalEntity = new Animal(
    $Animalnum,
    $Animal['name'],
    $Animal['age'],
    $Animal['type'],
    $Animal['weight'],
    $Animal['height'],
    $_SESSION['isHungry'][$Animalnum] ?? true
);

$workerEntity = null;
if (isset($_SESSION['worker_id'])) {
    $stmt = $pdo->prepare("SELECT id, name, age, gender, has_auto_feeder FROM workers WHERE id = ?");
    $stmt->execute([$_SESSION['worker_id']]);
    $workerData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($workerData) {
        $workerEntity = new Worker(
            $workerData['name'],
            $workerData['age'],
            $workerData['id'],
            $workerData['gender']
        );
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoo Game</title>
    <link href="../assets/tailwind/output.css" rel="stylesheet">
    <style>
        .pixel-font {
            font-family: 'Press Start 2P', monospace;
        }
    </style>
</head>

<body class="pixel-font flex flex-col justify-center items-center h-screen bg-[#0e1722] text-white">

    <!-- Animal -->
    <div>
        <img src="../assets/img/<?php echo $Animal['img']; ?>" alt="animal" class="w-lg h-128 object-contain">
    </div>

    <!-- Contrôles -->
    <div class="flex flex-col items-center gap-4 mt-4">

        <select id="worker-select" class="bg-[#0e1722] text-white p-2">
            <?php foreach ($workersList as $w): ?>
                <option
                    value="<?php echo $w['id']; ?>"
                    <?php echo (isset($_SESSION['worker_id']) && (int)$_SESSION['worker_id'] === (int)$w['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($w['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <h1 class="text-2xl font-bold"><?php echo $Animal['name']; ?></h1>
        <div class="flex flex-row  gap-4">
            <p id="hunger-status">
                <?php echo $animalEntity->isHungry() ? 'Affamé' : 'Rassasié'; ?>
            </p>
            <p>Clean? <?php echo $_SESSION['isClean'][$Animalnum] ?? false ? 'Propre' : 'Sale'; ?></p>
            <p>Repas : <?php echo $_SESSION['feedCount'][$Animalnum] ?? 0; ?> / 10</p>
            <p>Âge : <?php echo $animalEntity->getAge(); ?></p>
            <p>Cash : <?php echo $cash['cash'] ?? 0; ?></p>
        </div>


        <div class="flex gap-2">
            <button id="choose-worker-btn" class="bg-[#0e1722] text-white p-2">CHANGE WORKER</button>
            <button id="reset-btn" class="bg-[#0e1722] text-white p-2">AUTRE ANIMAL</button>
            <button id="feed-btn" class="bg-[#0e1722] text-white p-2">FEED ANIMAL</button>
            <button id="clean-btn" class="bg-[#0e1722] text-white p-2">CLEAN</button>
        </div>

    </div>

    <section class="pt-10">
        <div class="flex gap-2">
            <button id="buy-auto-feeder-btn" class="bg-[#0e1722] text-white p-2">BUY AUTO FEEDER</button>
            <button id="disable-auto-feeder-btn" class="bg-[#0e1722] text-white p-2">DISABLE AUTO FEEDER</button>
        </div>

    </section>

    <script>
        // Changer de worker
        document.getElementById('choose-worker-btn').addEventListener('click', () => {
            const workerId = document.getElementById('worker-select').value;
            fetch('../src/Controllers/WorkerController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `worker_id=${workerId}`
            }).then(() => location.reload());
        });

        // Animal suivant
        document.getElementById('reset-btn').addEventListener('click', async () => {
            const form = new FormData();
            form.append('action', 'next_animal');
            await fetch('', {
                method: 'POST',
                body: form
            });
            location.reload();
        });

        document.getElementById('disable-auto-feeder-btn').addEventListener('click', async () => {
            const form = new FormData();
            form.append('action', 'disable_auto_feeder');
            await fetch('', {
                method: 'POST',
                body: form
            });
            location.reload();
        });

        document.getElementById('clean-btn').addEventListener('click', async () => {
            const form = new FormData();
            form.append('action', 'clean_animal');
            await fetch('', {
                method: 'POST',
                body: form
            });
            location.reload();
        });

        document.getElementById('buy-auto-feeder-btn').addEventListener('click', async () => {
            const form = new FormData();
            form.append('action', 'buy_auto_feeder');
            await fetch('', {
                method: 'POST',
                body: form
            });
            location.reload();
        });



        // Nourrir l'animal
        document.getElementById('feed-btn').addEventListener('click', async () => {
            const form = new FormData();
            form.append('action', 'feed_animal');
            form.append('worker_id_feed', document.getElementById('worker-select').value);

            const response = await fetch('', {
                method: 'POST',
                body: form
            }); // ← stocker dans response
            const text = await response.text(); // ← lire la réponse

            if (text === 'ANIMAL_NOT_HUNGRY') {
                return;
            }

            location.reload();
        });

        // Auto feeder — tourne toutes les secondes
        <?php if ($workerData['has_auto_feeder'] ?? false): ?>

            setInterval(async () => {
                const form = new FormData();
                form.append('action', 'auto_feed_all'); // ← ici
                form.append('worker_id_feed', document.getElementById('worker-select').value);

                const response = await fetch('', {
                    method: 'POST',
                    body: form
                });
                const text = await response.text();

                if (text === 'OK') {
                    location.reload();
                }
            }, 1000);
        <?php endif; ?>
    </script>

</body>

</html>