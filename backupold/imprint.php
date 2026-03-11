<!doctype html>
<html lang="de">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php echo $title; ?></title>
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
<?php include('includes/imprint.php'); ?>
<?php include('includes/footer.php'); ?>
  

  
</body>
</html>
