<!doctype html>
<html lang="de">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>[SHC] Server Übersicht</title>
	<meta name="description" content="Schleswig-Holstein Community – Passion in Gaming. Events, Community, Turniere und gemeinsame Abende." />

	<!-- Bootstrap 5 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Icons (optional, für Buttons/Badges) -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
	<link href="assets/style.css" rel="stylesheet"/>
	<!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Current year
    document.getElementById("year").textContent = new Date().getFullYear();

    // Active nav link on scroll (simple)
    const sections = ["about","events","community","faq","join"].map(id => document.getElementById(id));
    const links = Array.from(document.querySelectorAll(".nav-link")).filter(a => a.getAttribute("href")?.startsWith("#"));
    const setActive = () => {
      const y = window.scrollY + 120;
      let current = "top";
      for (const s of sections) {
        if (s && s.offsetTop <= y) current = s.id;
      }
      links.forEach(a => a.classList.toggle("active", a.getAttribute("href") === "#" + current));
    };
    window.addEventListener("scroll", setActive, { passive:true });
    setActive();
  </script>
</head>

<body>
<?php include('includes/nav.php'); ?>
<section id="sstatus">
	<div class="row g-3" id="serverRow"></div>

<template id="serverCardTpl">
  <div class="col-lg-4">
    <div class="glass p-4 h-100">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="small text-uppercase server-game" style="color: var(--muted); letter-spacing:.12em;"></div>
          <h3 class="h5 fw-bold mb-1 server-name"></h3>
        </div>
        <span class="badge rounded-pill text-bg-light server-players"></span>
      </div>

      <p style="color: var(--muted);" class="mb-3">
        Map: <span class="server-map"></span>
      </p>

      <ul class="list-unstyled mb-0 small" style="color: var(--muted);">
        <li><i class="bi bi-controller me-2"></i><span class="server-game2"></span></li>
      </ul>
    </div>
  </div>
</template>
</section>
<?php include('includes/footer.php'); ?>
 <script>
(async () => {
  const row = document.getElementById("serverRow");
  const tpl = document.getElementById("serverCardTpl");

  try {
    const res = await fetch("config/proxy.php", { cache: "no-store" });
    if (!res.ok) throw new Error(`Proxy HTTP ${res.status}`);

    const xmlText = await res.text();
    const xml = new DOMParser().parseFromString(xmlText, "application/xml");
    if (xml.querySelector("parsererror")) throw new Error("Ungültiges XML erhalten.");

    // Mehrere Einträge: typischerweise <server>...</server>
    const servers = Array.from(xml.getElementsByTagName("server"));
    if (!servers.length) {
      row.innerHTML = `<div class="col-12"><div class="glass p-4">Keine Server-Einträge gefunden.</div></div>`;
      return;
    }

    row.innerHTML = "";

    for (const server of servers) {
      const getValue = (key) => {
        // Attribut?
        if (server.hasAttribute(key)) return (server.getAttribute(key) || "").trim();
        // Element?
        const el = server.getElementsByTagName(key)[0];
        return (el?.textContent || "").trim();
      };

      const name = getValue("name");
      const game = getValue("game");
      const mapName = getValue("mapName");
      const numUsed = getValue("numUsed");
      const capacity = getValue("capacity");

      const card = tpl.content.cloneNode(true);

      card.querySelector(".server-name").textContent = name || "-";
      card.querySelector(".server-game").textContent = game || "-";
      card.querySelector(".server-game2").textContent = game || "-";
      card.querySelector(".server-map").textContent = mapName || "-";
      card.querySelector(".server-players").textContent = `${numUsed || "0"}/${capacity || "0"}`;

      row.appendChild(card);
    }

  } catch (e) {
    row.innerHTML = `<div class="col-12"><div class="glass p-4">Fehler: ${escapeHtml(e.message)}</div></div>`;
  }

  function escapeHtml(s) {
    return String(s)
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");
  }
})();
</script> 

  
</body>
</html>
