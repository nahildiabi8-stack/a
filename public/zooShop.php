<?php
session_start();

require_once __DIR__ . '/../src/Entities/Worker.php';
require_once __DIR__ . '/../src/Entities/Animals.php';
require_once __DIR__ . '/../src/Repositories/WorkerRepositories.php';
require_once __DIR__ . '/../src/Repositories/AnimauxRepositories.php';

$pdo = new PDO('mysql:host=localhost;dbname=animal', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* =========================================================
   POST — ACTIONS AJAX
========================================================= */
if (isset($_POST['action'])) {

  $workerId = (int)($_SESSION['worker_id'] ?? 0);

  /* ── Achat Auto-Feeder ── */
  if ($_POST['action'] === 'buy_auto_feeder') {
    $row = $pdo->query("SELECT cash, has_auto_feeder FROM workers WHERE id = $workerId")->fetch(PDO::FETCH_ASSOC);
    if ($row['has_auto_feeder']) {
      echo json_encode(['status' => 'ERR', 'message' => 'Deja possede']);
      exit;
    }
    if (($row['cash'] ?? 0) < 100) {
      echo json_encode(['status' => 'ERR', 'message' => 'Pas assez de cash']);
      exit;
    }
    $pdo->prepare("UPDATE workers SET cash = ?, has_auto_feeder = 1 WHERE id = ?")
      ->execute([$row['cash'] - 100, $workerId]);
    echo json_encode(['status' => 'OK', 'newCash' => $row['cash'] - 100]);
    exit;
  }

  /* ── Achat Auto-Cleaner ── */
  if ($_POST['action'] === 'buy_auto_cleaner') {
    $row = $pdo->query("SELECT cash, has_auto_cleaner FROM workers WHERE id = $workerId")->fetch(PDO::FETCH_ASSOC);
    if ($row['has_auto_cleaner']) {
      echo json_encode(['status' => 'ERR', 'message' => 'Deja possede']);
      exit;
    }
    if (($row['cash'] ?? 0) < 250) {
      echo json_encode(['status' => 'ERR', 'message' => 'Pas assez de cash']);
      exit;
    }
    $pdo->prepare("UPDATE workers SET cash = ?, has_auto_cleaner = 1 WHERE id = ?")
      ->execute([$row['cash'] - 250, $workerId]);
    echo json_encode(['status' => 'OK', 'newCash' => $row['cash'] - 250]);
    exit;
  }

  /* ── Desactiver Auto-Feeder ── */
  if ($_POST['action'] === 'disable_auto_feeder') {
    $row     = $pdo->query("SELECT cash FROM workers WHERE id = $workerId")->fetch(PDO::FETCH_ASSOC);
    $newCash = ($row['cash'] ?? 0) + 100;
    $pdo->prepare("UPDATE workers SET cash = ?, has_auto_feeder = 0 WHERE id = ?")
      ->execute([$newCash, $workerId]);
    echo json_encode(['status' => 'OK', 'newCash' => $newCash]);
    exit;
  }

  /* ── Desactiver Auto-Cleaner ── */
  if ($_POST['action'] === 'disable_auto_cleaner') {
    $row     = $pdo->query("SELECT cash FROM workers WHERE id = $workerId")->fetch(PDO::FETCH_ASSOC);
    $newCash = ($row['cash'] ?? 0) + 250;
    $pdo->prepare("UPDATE workers SET cash = ?, has_auto_cleaner = 0 WHERE id = ?")
      ->execute([$newCash, $workerId]);
    echo json_encode(['status' => 'OK', 'newCash' => $newCash]);
    exit;
  }

  echo json_encode(['status' => 'ERR', 'message' => 'Action inconnue']);
  exit;
}

/* =========================================================
   CHARGEMENT DES DONNEES WORKER
========================================================= */
$workerId = (int)($_SESSION['worker_id'] ?? 0);
$workerRow = null;
if ($workerId) {
  $workerRow = $pdo->query("SELECT id, name, cash, has_auto_feeder, has_auto_cleaner FROM workers WHERE id = $workerId")->fetch(PDO::FETCH_ASSOC);
}

$cash         = (int)($workerRow['cash']            ?? 0);
$workerName   = $workerRow['name']                  ?? 'Inconnu';
$hasAutoFeed  = (bool)($workerRow['has_auto_feeder'] ?? false);
$hasAutoClean = (bool)($workerRow['has_auto_cleaner'] ?? false);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zoo Game - Shop</title>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    :root {
      --g: #f5c518;
      --gr: #27ae60;
      --re: #c0392b;
      --p: #0d1b2a;
      --p2: #142030;
      --p3: #1a2a3e;
      --bo: #1e3a55;
      --tx: #ccdcec;
      --di: #4a6a8a;
      --f1: clamp(5px, .55vw, 7px);
      --f2: clamp(6px, .7vw, 9px);
    }

    html,
    body {
      user-select: none;
      width: 100%;
      height: 100vh;
      overflow: hidden;
      font-family: 'Press Start 2P', 'Courier New', monospace;
      background: var(--p);
      color: var(--tx);
      display: flex;
      flex-direction: column 
    
    }

    /* HUD */
    #hud {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 16px;
      background: var(--p2);
      border-bottom: 3px solid var(--bo);
      box-shadow: 0 3px 0 #000;
      flex-shrink: 0;
      flex-wrap: wrap
    }

    .chip {
      display: flex;
      align-items: center;
      gap: 5px;
      background: var(--p);
      border: 2px solid var(--bo);
      box-shadow: 2px 2px 0 #000;
      padding: 4px 10px;
      font-size: var(--f1);
      color: var(--g);
      white-space: nowrap
    }

    .chip strong {
      color: #fff
    }

    .sep {
      width: 2px;
      height: 22px;
      background: var(--bo);
      flex-shrink: 0
    }

    #hud-title {
      margin-left: auto;
      font-size: var(--f2);
      color: var(--di);
      letter-spacing: 3px
    }

    #back-btn {
      font-family: inherit;
      font-size: var(--f1);
      background: var(--p);
      color: var(--tx);
      border: 2px solid #3a6aaa;
      box-shadow: 2px 2px 0 #000;
      padding: 4px 10px;
      cursor: pointer
    }

    #back-btn:hover {
      background: #1a3a5a
    }

    #back-btn:active {
      transform: translate(1px, 1px);
      box-shadow: 1px 1px 0 #000
    }

    /* LAYOUT */
    #body {
      display: flex;
      flex: 1;
      overflow: hidden
    }

    /* SIDEBAR */
    #sidebar {
      width: 140px;
      background: var(--p2);
      border-right: 3px solid var(--bo);
      box-shadow: 3px 0 0 #000;
      display: flex;
      flex-direction: column;
      padding: 10px 0;
      gap: 3px;
      flex-shrink: 0;
      overflow-y: auto
    }

    .cat-btn {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 9px 10px;
      font-family: inherit;
      font-size: var(--f1);
      background: transparent;
      color: var(--di);
      border: none;
      border-left: 3px solid transparent;
      cursor: pointer;
      text-align: left;
      white-space: nowrap;
      width: 100%;
      transition: background .1s, color .1s
    }

    .cat-btn:hover {
      background: var(--p3);
      color: var(--tx)
    }

    .cat-btn.active {
      background: var(--p3);
      color: var(--g);
      border-left-color: var(--g)
    }

    .cat-lbl {
      background: var(--p);
      border: 1px solid var(--bo);
      padding: 1px 4px;
      color: var(--g);
      font-size: var(--f1);
      margin-right: 4px;
      flex-shrink: 0
    }

    .cat-count {
      margin-left: auto;
      font-size: var(--f1);
      background: var(--p);
      border: 1px solid var(--bo);
      padding: 1px 4px;
      color: var(--di)
    }

    /* CONTENT */
    #content {
      flex: 1;
      overflow-y: auto;
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 12px
    }

    #section-title {
      font-size: var(--f2);
      color: var(--di);
      border-bottom: 2px solid var(--bo);
      padding-bottom: 6px;
      display: flex;
      align-items: center;
      gap: 8px
    }

    #grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 10px
    }

    /* CARD */
    .card {
      background: var(--p2);
      border: 2px solid var(--bo);
      box-shadow: 3px 3px 0 #000;
      padding: 12px;
      display: flex;
      flex-direction: column;
      gap: 6px;
      position: relative;
      transition: filter .12s, transform .12s;
      cursor: pointer
    }

    .card:hover {
      filter: brightness(1.13);
      transform: translate(-1px, -1px);
      box-shadow: 4px 4px 0 #000
    }

    .card.owned {
      border-color: var(--gr) !important
    }

    .card.poor {
      opacity: .5
    }

    .card.poor:hover {
      filter: none;
      transform: none;
      box-shadow: 3px 3px 0 #000
    }

    .card.selected {
      outline: 2px solid var(--g)
    }

    .card-lbl {
      font-size: var(--f1);
      background: var(--p3);
      border: 2px solid var(--bo);
      box-shadow: 2px 2px 0 #000;
      padding: 4px 7px;
      color: var(--g);
      display: inline-block;
      letter-spacing: 1px;
      align-self: flex-start
    }

    .card-name {
      font-size: var(--f1);
      color: #fff;
      text-shadow: 1px 1px 0 #000;
      line-height: 1.6
    }

    .card-desc {
      font-size: var(--f1);
      color: var(--di);
      line-height: 1.8
    }

    .card-effect {
      font-size: var(--f1);
      color: #4ade80;
      background: #0a2010;
      border: 1px solid #1a5020;
      box-shadow: 1px 1px 0 #000;
      padding: 2px 5px;
      display: inline-block
    }

    .card-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 4px;
      gap: 5px
    }

    .card-price {
      font-size: var(--f2);
      color: var(--g)
    }

    .card-price.free {
      color: #4ade80
    }

    .owned-badge {
      font-size: var(--f1);
      color: #4ade80;
      background: #0a2010;
      border: 2px solid var(--gr);
      box-shadow: 2px 2px 0 #000;
      padding: 2px 7px
    }

    /* TAG */
    .tag {
      position: absolute;
      top: -1px;
      right: -1px;
      font-size: var(--f1);
      padding: 2px 5px;
      border: 2px solid;
      box-shadow: 2px 2px 0 #000;
      background: var(--p)
    }

    .tag-POP {
      border-color: #e08020;
      color: #f0a030
    }

    .tag-PREM {
      border-color: #9040d0;
      color: #c060ff
    }

    .tag-NEW {
      border-color: #2090d0;
      color: #40b8ff
    }

    .tag-AUTO {
      border-color: var(--gr);
      color: #4ade80
    }

    .tag-RARE {
      border-color: var(--re);
      color: #ff6060
    }

    .tag-SPEC {
      border-color: var(--g);
      color: var(--g)
    }

    .tag-EFF {
      border-color: #20a080;
      color: #40d0b0
    }

    .tag-LIM {
      border-color: #d06020;
      color: #f08040
    }

    .tag-BEST {
      border-color: var(--g);
      color: var(--g)
    }

    /* BUTTONS */
    .buy-btn {
      font-family: inherit;
      font-size: var(--f1);
      padding: 4px 9px;
      border: 2px solid;
      box-shadow: 2px 2px 0 #000;
      cursor: pointer;
      white-space: nowrap;
      transition: filter .1s, transform .1s
    }

    .buy-btn:active {
      transform: translate(1px, 1px);
      box-shadow: 1px 1px 0 #000
    }

    .buy-btn.can {
      background: #0a2a10;
      border-color: var(--gr);
      color: #4ade80
    }

    .buy-btn.can:hover {
      filter: brightness(1.3)
    }

    .buy-btn.no {
      background: #1a0808;
      border-color: #5a1010;
      color: #804040;
      cursor: not-allowed
    }

    .buy-btn.got {
      background: #0a1a0a;
      border-color: var(--gr);
      color: #4ade80;
      cursor: default
    }

    /* DETAIL */
    #detail {
      width: 240px;
      background: var(--p2);
      border-left: 3px solid var(--bo);
      box-shadow: -3px 0 0 #000;
      padding: 14px;
      display: flex;
      flex-direction: column;
      gap: 9px;
      flex-shrink: 0;
      overflow-y: auto
    }

    .d-title {
      font-size: var(--f2);
      color: var(--g);
      border-bottom: 2px solid var(--bo);
      padding-bottom: 7px
    }

    .d-lbl {
      font-size: var(--f2);
      text-align: center;
      background: var(--p3);
      border: 3px solid var(--bo);
      box-shadow: 3px 3px 0 #000;
      padding: 12px;
      color: var(--g);
      letter-spacing: 2px
    }

    .d-name {
      font-size: var(--f2);
      color: #fff;
      text-align: center;
      text-shadow: 2px 2px 0 #000;
      line-height: 1.6
    }

    .d-desc {
      font-size: var(--f1);
      color: var(--tx);
      line-height: 2
    }

    .d-row {
      display: flex;
      justify-content: space-between;
      font-size: var(--f1);
      padding: 4px 0;
      border-bottom: 1px solid var(--bo);
      color: var(--di)
    }

    .d-row .v {
      color: var(--tx)
    }

    .d-effect {
      font-size: var(--f1);
      color: #4ade80;
      background: #0a2010;
      border: 2px solid #1a6020;
      box-shadow: 2px 2px 0 #000;
      padding: 6px 8px;
      line-height: 2
    }

    .d-placeholder {
      font-size: var(--f1);
      color: var(--di);
      text-align: center;
      margin: auto;
      line-height: 2.8
    }

    .d-buy {
      font-family: inherit;
      font-size: var(--f2);
      padding: 9px;
      width: 100%;
      border: 2px solid;
      box-shadow: 3px 3px 0 #000;
      cursor: pointer;
      text-align: center;
      margin-top: auto;
      transition: filter .1s, transform .1s
    }

    .d-buy:active {
      transform: translate(1px, 1px);
      box-shadow: 2px 2px 0 #000
    }

    .d-buy.can {
      background: #0a3a18;
      border-color: var(--gr);
      color: #4ade80
    }

    .d-buy.can:hover {
      filter: brightness(1.25)
    }

    .d-buy.no {
      background: #2a0808;
      border-color: #8a1010;
      color: #ff5050;
      cursor: not-allowed
    }

    .d-buy.got {
      background: #0a1a0a;
      border-color: var(--gr);
      color: #4ade80;
      cursor: default
    }

    /* CONFIRM */
    #confirm-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .75);
      z-index: 200;
      align-items: center;
      justify-content: center
    }

    #confirm-overlay.on {
      display: flex
    }

    #confirm-box {
      background: var(--p2);
      border: 3px solid var(--bo);
      box-shadow: 6px 6px 0 #000;
      width: 280px;
      padding: 20px;
      animation: pi .15s ease-out
    }

    @keyframes pi {
      from {
        transform: scale(.82);
        opacity: 0
      }

      to {
        transform: scale(1);
        opacity: 1
      }
    }

    .cf-title {
      font-size: var(--f2);
      color: var(--g);
      margin-bottom: 12px;
      text-align: center
    }

    .cf-lbl {
      font-size: var(--f2);
      text-align: center;
      display: block;
      margin-bottom: 10px;
      background: var(--p3);
      border: 2px solid var(--bo);
      padding: 8px;
      color: var(--g)
    }

    .cf-name {
      font-size: var(--f1);
      color: var(--tx);
      text-align: center;
      margin-bottom: 6px;
      line-height: 1.8
    }

    .cf-price {
      font-size: var(--f2);
      color: var(--g);
      text-align: center;
      margin-bottom: 14px
    }

    .cf-btns {
      display: flex;
      gap: 8px
    }

    .cf-btn {
      flex: 1;
      font-family: inherit;
      font-size: var(--f1);
      padding: 8px;
      border: 2px solid;
      box-shadow: 2px 2px 0 #000;
      cursor: pointer;
      text-align: center;
      transition: filter .1s
    }

    .cf-btn:hover {
      filter: brightness(1.2)
    }

    .cf-btn:active {
      transform: translate(1px, 1px);
      box-shadow: 1px 1px 0 #000
    }

    .cf-ok {
      background: #0a3a18;
      border-color: var(--gr);
      color: #4ade80
    }

    .cf-no {
      background: #3a0808;
      border-color: var(--re);
      color: #ff6060
    }

    /* NOTIF */
    #notif {
      position: fixed;
      top: 54px;
      left: 50%;
      transform: translateX(-50%) translateY(-8px);
      background: var(--p2);
      border: 2px solid var(--gr);
      box-shadow: 3px 3px 0 #000;
      color: #4ade80;
      padding: 5px 16px;
      font-size: var(--f1);
      opacity: 0;
      transition: opacity .22s, transform .22s;
      z-index: 999;
      pointer-events: none;
      white-space: nowrap
    }

    #notif.on {
      opacity: 1;
      transform: translateX(-50%) translateY(0)
    }

    #notif.err {
      border-color: var(--re);
      color: #ff6060
    }

    ::-webkit-scrollbar {
      width: 8px
    }

    ::-webkit-scrollbar-track {
      background: var(--p)
    }

    ::-webkit-scrollbar-thumb {
      background: var(--bo);
      border: 2px solid var(--p)
    }
  </style>
</head>

<body>

  <div id="hud">
    <div class="chip">Cash : <strong id="cash-display"><?php echo $cash; ?></strong></div>
    <div class="sep"></div>
    <div class="chip">Worker : <strong id="worker-display"><?php echo htmlspecialchars($workerName); ?></strong></div>
    <div class="sep"></div>
    <div class="chip">Possedes : <strong id="owned-count">0</strong></div>
    <div class="sep"></div>
    <button id="back-btn" onclick="window.location.href='map.php'">RETOUR</button>
    <div id="hud-title">ZOO GAME - SHOP</div>
  </div>

  <div id="body">
    <aside id="sidebar"></aside>
    <main id="content">
      <div id="section-title"></div>
      <div id="grid"></div>
    </main>
    <aside id="detail">
      <div class="d-placeholder">Clique sur<br>un article<br>pour voir<br>les details</div>
    </aside>
  </div>

  <div id="confirm-overlay">
    <div id="confirm-box">
      <div class="cf-title">CONFIRMER L ACHAT</div>
      <span class="cf-lbl" id="cf-lbl">---</span>
      <div class="cf-name" id="cf-name">Article</div>
      <div class="cf-price" id="cf-price">- 0 $</div>
      <div class="cf-btns">
        <button class="cf-btn cf-ok" id="cf-ok">ACHETER</button>
        <button class="cf-btn cf-no" onclick="closeConfirm()">ANNULER</button>
      </div>
    </div>
  </div>

  <div id="notif"></div>

  <script>
    /* ======================================================
   DONNEES INJECTEES PAR PHP
====================================================== */
    var cash = <?php echo $cash; ?>;
    var workerName = "<?php echo addslashes($workerName); ?>";
    var hasAutoFeed = <?php echo $hasAutoFeed  ? 'true' : 'false'; ?>;
    var hasAutoClean = <?php echo $hasAutoClean ? 'true' : 'false'; ?>;

    /* ======================================================
       CATALOGUE
    ====================================================== */
    var CATS = [{
        id: "tools",
        label: "OUTILS",
        lbl: "TOOL"
      },

    ];

    /* Items — id 7 = Auto-Feeder (action: buy_auto_feeder)
               id 8 = Nettoyeur Auto (action: buy_auto_cleaner)
       owned est initialise depuis PHP selon la BDD           */
    var ITEMS = [{
        id: 7,
        cat: "tools",
        name: "Auto-Feeder",
        desc: "Nourrit automatiquement tous les animaux toutes les 5s.",
        lbl: "BOT",
        price: 100,
        owned: hasAutoFeed,
        max: 1,
        tag: "AUTO",
        tclass: "AUTO",
        effect: "Nourrit auto toutes les 5s",
        action: "buy_auto_feeder"
      },
      {
        id: 8,
        cat: "tools",
        name: "Nettoyeur Auto",
        desc: "Nettoie automatiquement les enclos sales toutes les 10s.",
        lbl: "MOP",
        price: 250,
        owned: hasAutoClean,
        max: 1,
        tag: "AUTO",
        tclass: "AUTO",
        effect: "Nettoie auto toutes les 10s",
        action: "buy_auto_cleaner"
      },
    ];

    /* ======================================================
       ETAT
    ====================================================== */
    var activeCat = "food";
    var selectedId = null;
    var confirmItem = null;
    var notifTimer = null;

    /* ======================================================
       INIT
    ====================================================== */
    function init() {
      document.getElementById("cash-display").textContent = fmt(cash);
      document.getElementById("worker-display").textContent = workerName;
      buildSidebar();
      renderGrid();
      updateOwned();
    }

    /* ======================================================
       SIDEBAR
    ====================================================== */
    function buildSidebar() {
      var sb = document.getElementById("sidebar");
      sb.innerHTML = "";
      CATS.forEach(function(cat) {
        var count = ITEMS.filter(function(i) {
          return i.cat === cat.id;
        }).length;
        var btn = document.createElement("button");
        btn.className = "cat-btn" + (cat.id === activeCat ? " active" : "");
        btn.innerHTML = '<span class="cat-lbl">' + cat.lbl + '</span>' + cat.label + '<span class="cat-count">' + count + '</span>';
        btn.addEventListener("click", function() {
          activeCat = cat.id;
          selectedId = null;
          document.querySelectorAll(".cat-btn").forEach(function(b) {
            b.classList.remove("active");
          });
          btn.classList.add("active");
          renderGrid();
          renderDetail(null);
        });
        sb.appendChild(btn);
      });
    }

    /* ======================================================
       GRILLE
    ====================================================== */
    function renderGrid() {
      var cat = CATS.find(function(c) {
        return c.id === activeCat;
      });
      var items = ITEMS.filter(function(i) {
        return i.cat === activeCat;
      });
      var grid = document.getElementById("grid");
      var title = document.getElementById("section-title");
      title.innerHTML = '<span style="background:var(--p3);border:2px solid var(--bo);padding:2px 6px;color:var(--g)">' + cat.lbl + '</span> ' + cat.label + ' <span style="color:var(--di);font-size:var(--f1)">- ' + items.length + ' articles</span>';
      grid.innerHTML = "";
      items.forEach(function(item) {
        var poor = !item.owned && cash < item.price;
        var card = document.createElement("div");
        card.className = "card" + (item.owned ? " owned" : "") + (poor ? " poor" : "") + (item.id === selectedId ? " selected" : "");
        card.dataset.id = item.id;
        var tagHtml = item.tag ? '<span class="tag tag-' + item.tclass + '">' + item.tag + '</span>' : "";
        var btnHtml = item.owned ?
          '<span class="owned-badge">OK</span>' :
          '<button class="buy-btn ' + (poor ? "no" : "can") + '" onclick="onBuyClick(event,' + item.id + ')">' + (item.price === 0 ? "GRATUIT" : "ACHETER") + '</button>';
        card.innerHTML = tagHtml +
          '<span class="card-lbl">' + item.lbl + '</span>' +
          '<span class="card-name">' + item.name + '</span>' +
          '<span class="card-desc">' + item.desc + '</span>' +
          '<span class="card-effect">' + item.effect + '</span>' +
          '<div class="card-footer"><span class="card-price' + (item.price === 0 ? " free" : "") + '">' + (item.price === 0 ? "GRATUIT" : fmt(item.price) + " $") + '</span>' + btnHtml + '</div>';
        card.addEventListener("click", function() {
          selectedId = item.id;
          document.querySelectorAll(".card").forEach(function(c) {
            c.classList.remove("selected");
          });
          card.classList.add("selected");
          renderDetail(item);
        });
        grid.appendChild(card);
      });
    }

    /* ======================================================
       DETAIL
    ====================================================== */
    function renderDetail(item) {
      var panel = document.getElementById("detail");
      if (!item) {
        panel.innerHTML = '<div class="d-placeholder">Clique sur<br>un article<br>pour voir<br>les details</div>';
        return;
      }
      var cat = CATS.find(function(c) {
        return c.id === item.cat;
      });
      var can = cash >= item.price && !item.owned;
      var btnCls = item.owned ? "got" : can ? "can" : "no";
      var btnLbl = item.owned ? "POSSEDE" : item.price === 0 ? "GRATUIT" : "ACHETER";
      var limitRow = item.max === 1 ? '<div class="d-row"><span>Limite</span><span class="v">1 par zoo</span></div>' : "";
      /* Bouton desactiver — seulement si possede ET item desactivable */
      var disableMap = {
        7: "disable_auto_feeder",
        8: "disable_auto_cleaner"
      };
      var disableBtn = (item.owned && disableMap[item.id]) ?
        '<button style="font-family:inherit;font-size:var(--f1);padding:8px;width:100%;background:#2a0808;border:2px solid #8a1010;color:#ff5050;box-shadow:3px 3px 0 #000;cursor:pointer;margin-top:6px;transition:filter .1s" onmouseover="this.style.filter=\'brightness(1.2)\'" onmouseout="this.style.filter=\'\'" onclick="doDisable(' + item.id + ')">DESACTIVER (remb. ' + fmt(item.price) + '$)</button>' :
        "";

      panel.innerHTML =
        '<div class="d-title">DETAILS</div>' +
        '<div class="d-lbl">' + item.lbl + '</div>' +
        '<div class="d-name">' + item.name + '</div>' +
        '<div class="d-desc">' + item.desc + '</div>' +
        '<div class="d-row"><span>Categorie</span><span class="v">' + cat.label + '</span></div>' +
        '<div class="d-row"><span>Prix</span><span class="v" style="color:var(--g)">' + (item.price === 0 ? "GRATUIT" : fmt(item.price) + " $") + '</span></div>' +
        limitRow +
        '<div class="d-effect">' + item.effect + '</div>' +
        '<button class="d-buy ' + btnCls + '" onclick="onBuyClick(event,' + item.id + ')" ' + (item.owned || !can ? "disabled" : "") + '>' + btnLbl + '</button>' +
        disableBtn;
    }

    /* ======================================================
       ACHAT — CLIC
    ====================================================== */
    function onBuyClick(e, id) {
      e.stopPropagation();
      var item = ITEMS.find(function(i) {
        return i.id === id;
      });
      if (!item || item.owned) return;
      if (cash < item.price) {
        toast("PAS ASSEZ DE CASH !", true);
        return;
      }
      openConfirm(item);
    }

    function openConfirm(item) {
      confirmItem = item;
      document.getElementById("cf-lbl").textContent = item.lbl;
      document.getElementById("cf-name").textContent = item.name;
      document.getElementById("cf-price").textContent = "- " + fmt(item.price) + " $";
      document.getElementById("confirm-overlay").classList.add("on");
      document.getElementById("cf-ok").onclick = doBuy;
    }

    function closeConfirm() {
      document.getElementById("confirm-overlay").classList.remove("on");
      confirmItem = null;
    }

    /* ======================================================
       ACHAT — EXECUTION
    ====================================================== */
    function doBuy() {
      var item = confirmItem;
      closeConfirm();
      if (!item) return;

      /* Items sans action PHP connue : simulation frontend */
      if (!item.action) {
        cash -= item.price;
        document.getElementById("cash-display").textContent = fmt(cash);
        updateOwned();
        toast("ACHETE : " + item.name + " -" + item.price + "$");
        renderGrid();
        renderDetail(ITEMS.find(function(i) {
          return i.id === item.id;
        }));
        return;
      }

      /* Items avec action PHP (Auto-Feeder, Auto-Cleaner...) */
      var fd = new FormData();
      fd.append("action", item.action);

      fetch("zooShop.php", {
          method: "POST",
          body: fd
        })
        .then(function(r) {
          return r.json();
        })
        .then(function(d) {
          if (d.status !== "OK") {
            toast(d.message || "ERREUR", true);
            return;
          }
          /* Mettre a jour le cash depuis la BDD */
          cash = d.newCash;
          document.getElementById("cash-display").textContent = fmt(cash);

          /* Marquer comme possede */
          ITEMS = ITEMS.map(function(i) {
            return i.id === item.id ? Object.assign({}, i, {
              owned: true
            }) : i;
          });

          updateOwned();
          toast("ACHETE : " + item.name);
          renderGrid();
          renderDetail(ITEMS.find(function(i) {
            return i.id === item.id;
          }));
        })
        .catch(function() {
          toast("ERREUR RESEAU", true);
        });
    }

    /* ======================================================
       UTILS
    ====================================================== */
    function toast(msg, err) {
      var n = document.getElementById("notif");
      n.textContent = msg;
      n.className = "on" + (err ? " err" : "");
      clearTimeout(notifTimer);
      notifTimer = setTimeout(function() {
        n.className = "";
      }, 2300);
    }

    function fmt(n) {
      return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "\u00a0");
    }

    function updateOwned() {
      document.getElementById("owned-count").textContent = ITEMS.filter(function(i) {
        return i.owned;
      }).length;
    }


    /* ======================================================
       DESACTIVER UN OUTIL (remboursement)
    ====================================================== */
    var disableActions = {
      7: "disable_auto_feeder",
      8: "disable_auto_cleaner"
    };

    function doDisable(id) {
      var item = ITEMS.find(function(i) {
        return i.id === id;
      });
      var action = disableActions[id];
      if (!item || !action) return;
      var fd = new FormData();
      fd.append("action", action);
      fetch("zooShop.php", {
          method: "POST",
          body: fd
        })
        .then(function(r) {
          return r.json();
        })
        .then(function(d) {
          if (d.status !== "OK") {
            toast(d.message || "ERREUR", true);
            return;
          }
          cash = d.newCash;
          document.getElementById("cash-display").textContent = fmt(cash);
          ITEMS = ITEMS.map(function(i) {
            return i.id === id ? Object.assign({}, i, {
              owned: false
            }) : i;
          });
          updateOwned();
          toast("DESACTIVE : " + item.name + " +" + fmt(item.price) + "$ rembourse");
          renderGrid();
          renderDetail(ITEMS.find(function(i) {
            return i.id === id;
          }));
        })
        .catch(function() {
          toast("ERREUR RESEAU", true);
        });
    }

    document.getElementById("confirm-overlay").addEventListener("click", function(e) {
      if (e.target === this) closeConfirm();
    });

    init();
  </script>
</body>

</html>