/**
 * pixel-sounds.js
 * ─────────────────────────────────────────────────────────────
 * Système de sons universel pour Zoo Game.
 * Modifie uniquement la section SOUND CONFIG ci-dessous
 * pour changer n'importe quel son du site.
 * ─────────────────────────────────────────────────────────────
 */

(function () {

  /* ═══════════════════════════════════════════════════════════
     🔊 SOUND CONFIG
     ─────────────────────────────────────────────────────────
     3 valeurs possibles pour chaque son :

       '../assets/sounds/click.mp3'  →  joue ce fichier audio
       false                         →  son pixel généré (défaut)
       null                          →  silence total
  ═══════════════════════════════════════════════════════════ */
  const SOUNDS = {

    // ── Boutons ──────────────────────────────────────────────
    button_click:    'false',    // clic bouton normal
    button_disabled: false,    // clic sur bouton désactivé (grisé)
    button_hover:    null,     // survol bouton

    // ── Select / dropdown ────────────────────────────────────
    select_change:   false,    // changement de valeur dans un <select>

    // ── Notifications ────────────────────────────────────────
    notif_success:   false,    // notif verte (nourri, nettoyé, acheté…)
    notif_error:     false,    // notif rouge (déjà fait, pas assez de cash…)
    notif_info:      false,    // notif neutre

    // ── Actions jeu ──────────────────────────────────────────
    feed_animal:     false,    // nourrir un animal
    clean_animal:    false,    // nettoyer un animal
    buy_item:        false,    // achat en boutique
    unlock_enclos:   false,    // enclos débloqué

    // ── Map ───────────────────────────────────────────────────
    map_open_modal:  false,    // ouverture modal enclos
    map_close_modal: false,    // fermeture modal enclos

    // ── Navigation ───────────────────────────────────────────
    page_navigate:   false,    // clic sur un lien / changement de page

  };

  /*
   * ─── EXEMPLE avec tes propres fichiers MP3 ───────────────
   *
   *   button_click:    '../assets/sounds/click.mp3',
   *   button_disabled: '../assets/sounds/denied.mp3',
   *   notif_success:   '../assets/sounds/success.mp3',
   *   notif_error:     '../assets/sounds/error.mp3',
   *   feed_animal:     '../assets/sounds/feed.mp3',
   *   clean_animal:    '../assets/sounds/clean.mp3',
   *   buy_item:        '../assets/sounds/coin.mp3',
   *   unlock_enclos:   '../assets/sounds/unlock.mp3',
   *   map_open_modal:  '../assets/sounds/open.mp3',
   *   map_close_modal: '../assets/sounds/close.mp3',
   *   page_navigate:   '../assets/sounds/swoosh.mp3',
   *
   * ──────────────────────────────────────────────────────── */

  /* ═══════════════════════════════════════════════════════════
     🔈 VOLUME GLOBAL  (0.0 → 1.0)
  ═══════════════════════════════════════════════════════════ */
  const MASTER_VOLUME = 0.9;

  /* ═══════════════════════════════════════════════════════════
     Sons pixel de fallback (Web Audio — aucun fichier requis)
     Tu peux tweaker freq/dur/vol si tu veux un rendu différent
  ═══════════════════════════════════════════════════════════ */
  const PIXEL = {
    button_click:    { w:'square', f:[440,300],       d:0.08, v:0.18 },
    button_disabled: { w:'square', f:[200,100],       d:0.15, v:0.20 },
    button_hover:    { w:'sine',   f:[600],            d:0.04, v:0.04 },
    select_change:   { w:'square', f:[380,500],       d:0.07, v:0.14 },
    notif_success:   { w:'square', f:[440,660],       d:0.18, v:0.15 },
    notif_error:     { w:'square', f:[250,120],       d:0.20, v:0.22 },
    notif_info:      { w:'sine',   f:[500],            d:0.10, v:0.12 },
    feed_animal:     { w:'square', f:[300,500],       d:0.18, v:0.16 },
    clean_animal:    { w:'sine',   f:[600,800],       d:0.20, v:0.14 },
    buy_item:        { w:'square', f:[440,660,880],   d:0.25, v:0.18 },
    unlock_enclos:   { w:'square', f:[300,500,700,900],d:0.35,v:0.20 },
    map_open_modal:  { w:'sine',   f:[400,600],       d:0.12, v:0.14 },
    map_close_modal: { w:'sine',   f:[600,400],       d:0.10, v:0.10 },
    page_navigate:   { w:'square', f:[440,330],       d:0.12, v:0.16 },
  };

  /* ═══════════════════════════════════════════════════════════
     MOTEUR AUDIO INTERNE
  ═══════════════════════════════════════════════════════════ */
  let ctx = null;
  const cache = {};

  function getCtx() {
    if (!ctx) ctx = new (window.AudioContext || window.webkitAudioContext)();
    return ctx;
  }

  // Charge un fichier audio et le met en cache
  async function loadFile(src) {
    if (cache[src]) return cache[src];
    try {
      const res  = await fetch(src);
      const buf  = await res.arrayBuffer();
      const dec  = await getCtx().decodeAudioData(buf);
      cache[src] = dec;
      return dec;
    } catch (e) {
      console.warn('[ZooSound] Fichier introuvable :', src);
      return null;
    }
  }

  // Joue un AudioBuffer
  function playBuf(buf, vol) {
    try {
      const ac  = getCtx();
      const src = ac.createBufferSource();
      const g   = ac.createGain();
      src.buffer = buf;
      src.connect(g);
      g.connect(ac.destination);
      g.gain.setValueAtTime((vol || 1) * MASTER_VOLUME, ac.currentTime);
      src.start();
    } catch (e) {}
  }

  // Joue un son pixel généré
  function playPixel(key) {
    try {
      const cfg = PIXEL[key];
      if (!cfg) return;
      const ac   = getCtx();
      const now  = ac.currentTime;
      const notes = cfg.f;
      const step  = cfg.d / notes.length;

      notes.forEach((freq, i) => {
        const o = ac.createOscillator();
        const g = ac.createGain();
        o.connect(g);
        g.connect(ac.destination);
        o.type = cfg.w;
        const t = now + i * step;
        o.frequency.setValueAtTime(freq, t);
        // Ramp vers la note suivante si elle existe
        if (notes[i + 1]) o.frequency.exponentialRampToValueAtTime(notes[i + 1], t + step);
        g.gain.setValueAtTime(cfg.v * MASTER_VOLUME, t);
        g.gain.exponentialRampToValueAtTime(0.001, t + step);
        o.start(t);
        o.stop(t + step + 0.01);
      });
    } catch (e) {}
  }

  /* ═══════════════════════════════════════════════════════════
     API PUBLIQUE : window.playSound('key')
  ═══════════════════════════════════════════════════════════ */
  async function playSound(key) {
    const cfg = SOUNDS[key];
    if (cfg === null || cfg === undefined) return;   // silence
    if (cfg === false) { playPixel(key); return; }   // son pixel
    // Fichier audio
    const buf = await loadFile(cfg);
    if (buf) playBuf(buf);
    else     playPixel(key);                          // fallback pixel si fichier KO
  }

  window.playSound = playSound;

  /* ═══════════════════════════════════════════════════════════
     AUTO-ATTACH sur tous les éléments interactifs
  ═══════════════════════════════════════════════════════════ */
  function attach() {
    // Boutons
    document.querySelectorAll('button, input[type="button"], input[type="submit"]').forEach(el => {
      if (el.dataset.snd) return;
      el.dataset.snd = '1';
      el.addEventListener('mouseenter', () => playSound('button_hover'));
      el.addEventListener('click', () =>
        playSound(el.disabled ? 'button_disabled' : 'button_click')
      );
    });

    // Selects
    document.querySelectorAll('select').forEach(el => {
      if (el.dataset.snd) return;
      el.dataset.snd = '1';
      el.addEventListener('change', () => playSound('select_change'));
    });

    // Liens
    document.querySelectorAll('a[href]').forEach(el => {
      if (el.dataset.snd) return;
      el.dataset.snd = '1';
      el.addEventListener('click', () => playSound('page_navigate'));
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', attach);
  } else {
    attach();
  }

  // Re-attache sur éléments créés dynamiquement (enclos JS, modals…)
  new MutationObserver(attach).observe(document.body, { childList: true, subtree: true });

  /* ═══════════════════════════════════════════════════════════
     PATCH AUTOMATIQUE de notify()
     Compatible avec la fonction notify() de map.php / index.php
     Joue notif_success ou notif_error automatiquement
  ═══════════════════════════════════════════════════════════ */
  function patchNotify() {
    if (!window.notify || window.notify._patched) return;
    const orig = window.notify;
    window.notify = function (msg, err = false) {
      playSound(err ? 'notif_error' : 'notif_success');
      return orig.apply(this, arguments);
    };
    window.notify._patched = true;
  }

  // Tente immédiatement, puis après le chargement complet
  patchNotify();
  window.addEventListener('load', patchNotify);

})();
