<?php
$servername="sql202.infinityfree.com";
$username="if0_43187131";
$password="hHimykmJ2nY";
$dbname="if0_43187131_AuroraEventsMain";
try{
    $conn= new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($_SESSION['showconnect'] != 0) {
        echo("Connected successfully<br>");
    }   
}
catch(PDOException $e){
    echo("Connection failed: " . $e->getMessage());
}
try {
    $query = "SELECT id, title, icon, type, date, display_date, players, description, status
                    FROM tblEvents 
                    WHERE ended = 0";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $events = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Query Execution Failure: " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
          content="Discover Minecraft civilization events and apply to join the next adventure.">

    <title>Aurora Events | Homepage</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
          rel="stylesheet">

</head>
<body>

<!-- Fixed navigation -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top site-navbar">
    <div class="container">
        <a class="navbar-brand brand" href="home">
            <i class="bi bi-moon-stars-fill brand-icon"></i>
            AURORA
            <span class="brand-subtitle">CIVILIZATIONS</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link active" href="home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://discord.gg/Te8NknMpv8y">Discord</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="home#events">Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="home#about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="home#faq">FAQ</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-celestial mt-2 mt-lg-0"
                       href="login">
                        <i class="bi bi-person-circle me-1"></i> Account
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-gold mt-2 mt-lg-0"
                       href="apply">
                        <i class="bi bi-stars me-1"></i> Apply Now
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<style>
h1, h2, h3, .brand {
    font-family: "Cinzel", serif;
}

.brand {
    color: var(--gold-light) !important;
    letter-spacing: 3px;
    font-size: 1.1rem;
    line-height: 1.1;
}

.brand-icon {
    color: var(--purple-light);
    margin-right: 8px;
    font-size: 1.5rem;
}

.brand-subtitle {
    display: block;
    margin-left: 37px;
    margin-top: 5px;
    font: 0.55rem "Inter", sans-serif;
    letter-spacing: 3px;
    color: var(--muted);
}

.btn-outline-celestial {
    color: var(--text);
    border: 1px solid var(--purple-light);
    background: rgba(80, 28, 108, 0.2);
}

.btn-outline-celestial:hover {
    color: white;
    background: var(--purple-dark);
    border-color: var(--purple-light);
}

.btn-gold {
    color: #211128;
    background: linear-gradient(110deg, var(--gold-light), var(--gold));
    border: 1px solid var(--gold-light);
}

.btn-gold:hover,
.btn-gold:focus {
    color: #211128;
    background: var(--gold-light);
    border-color: var(--gold-light);
    box-shadow: 0 5px 25px rgba(211, 141, 25, 0.25);
}

.site-navbar {
    min-height: 76px;
    background: rgba(16, 9, 29, 0.96);
    border-bottom: 1px solid var(--border);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
}

@media (max-width: 575.98px) {
    .site-navbar {
        min-height: 70px;
    }
}
:root {
    --space: #10091d;
    --space-light: #1a102b;
    --purple-dark: #501C6C;
    --purple: #7D2CA8;
    --purple-light: #B95CEB;
    --orange-dark: #9C420D;
    --gold: #D38D19;
    --gold-light: #FFC96D;
    --text: #f6efff;
    --muted: #c4b6d8;
    --border: rgba(185, 92, 235, 0.25);
}

* {box-sizing: border-box;}
html {scroll-behavior: smooth;scroll-padding-top: 95px;}
body {margin: 0;padding-top: 76px;background: var(--space);color: var(--text);font-family: "Inter", sans-serif;line-height: 1.7;}
a {text-decoration: none;}
h2 {font-size: clamp(2rem, 4vw, 3rem);margin-bottom: 1rem;}
p {color: var(--muted);}
h1, h2, h3, .brand {font-family: "Cinzel", serif;}
.brand {color: var(--gold-light) !important;letter-spacing: 3px;font-size: 1.1rem;line-height: 1.1;}
.brand-icon {color: var(--purple-light);margin-right: 8px;font-size: 1.5rem;}
.brand-subtitle {display: block;margin-left: 37px;margin-top: 5px;font: 0.55rem "Inter", sans-serif;letter-spacing: 3px;color: var(--muted);}
.btn {border-radius: 9px;font-weight: 600;padding: 11px 20px;transition: transform 0.2s ease, box-shadow 0.2s ease;}
.btn:hover {transform: translateY(-2px);}
.btn-gold {color: #211128;background: linear-gradient(110deg, var(--gold-light), var(--gold));border: 1px solid var(--gold-light);}
.btn-gold:hover,.btn-gold:focus {color: #211128;background: var(--gold-light);border-color: var(--gold-light);box-shadow: 0 5px 25px rgba(211, 141, 25, 0.25);}
.btn-outline-celestial {color: var(--text);border: 1px solid var(--purple-light);background: rgba(80, 28, 108, 0.2);}
.btn-outline-celestial:hover {color: white;background: var(--purple-dark);border-color: var(--purple-light);}
.btn-disabled {color: #a99ab8;background: #261b32;border: 1px solid #44334f;}
.hero {position: relative;isolation: isolate;overflow: hidden;min-height: 570px;display: flex;align-items: center;background:radial-gradient(ellipse at 80% 40%, rgba(125, 44, 168, 0.4), transparent 45%),radial-gradient(ellipse at 10% 90%, rgba(156, 66, 13, 0.22), transparent 45%),linear-gradient(120deg, #10091d, #21102f 65%, #120b22);}
.hero::before,.hero::after {position: absolute;content: "";pointer-events: none;z-index: -1;}
.hero::before {width: 500px;height: 500px;right: -100px;top: -120px;border: 1px solid rgba(185, 92, 235, 0.14);border-radius: 50%;box-shadow:0 0 0 50px rgba(185, 92, 235, 0.025),0 0 0 100px rgba(185, 92, 235, 0.025);}
.hero::after {width: 100%;height: 1px;bottom: 0;background: linear-gradient(90deg, transparent, var(--purple), var(--gold), transparent);}
.hero-content {
    padding-top: 65px;
    padding-bottom: 75px;
}

.eyebrow {
    display: inline-block;
    margin-bottom: 18px;
    color: var(--gold-light);
    font-size: 0.76rem;
    font-weight: 700;
    letter-spacing: 3px;
}

.hero h1 {
    font-size: clamp(2.7rem, 5.6vw, 5rem);
    line-height: 1.12;
    letter-spacing: -1px;
    font-weight: 700;
}

.gradient-text {
    background: linear-gradient(100deg, var(--gold-light), var(--purple-light));
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-description {
    max-width: 540px;
    font-size: 1.05rem;
    margin-top: 22px;
}

.hero-note {
    color: #bca9d0;
    font-size: 0.85rem;
}

.cosmic-art {
    height: 380px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.planet {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 190px;
    height: 190px;
    border-radius: 50%;
    color: var(--gold-light);
    font-size: 5rem;
    background:
        radial-gradient(circle at 30% 25%, #b95ceb, #501c6c 45%, #1a102b 75%);
    border: 1px solid rgba(255, 201, 109, 0.7);
    box-shadow:
        0 0 45px rgba(185, 92, 235, 0.45),
        0 0 100px rgba(125, 44, 168, 0.2);
}

.orbit {
    position: absolute;
    width: 300px;
    height: 300px;
    border: 1px solid rgba(255, 201, 109, 0.4);
    border-radius: 50%;
    transform: rotate(-35deg) scaleY(0.42);
}

.orbit-two {
    width: 390px;
    height: 390px;
    transform: rotate(40deg) scaleY(0.45);
    border-color: rgba(185, 92, 235, 0.4);
}

.cosmic-moon {
    position: absolute;
    top: 20px;
    right: 10px;
    font-size: 3rem;
    color: var(--purple-light);
    filter: drop-shadow(0 0 12px rgba(185, 92, 235, 0.6));
}

.floating-star {
    position: absolute;
    color: var(--gold-light);
}

.star-one {
    top: 55px;
    left: 65px;
    font-size: 1.4rem;
}

.star-two {
    bottom: 65px;
    right: 40px;
    font-size: 1.8rem;
}

.star-three {
    bottom: 95px;
    left: 20px;
    font-size: 0.9rem;
}

.cosmic-caption {
    position: absolute;
    bottom: 0;
    font-size: 0.7rem;
    letter-spacing: 5px;
    color: var(--muted);
}
.stats-section {
    padding: 38px 0;
    background: #160e24;
    border-bottom: 1px solid var(--border);
}

.stats-section h3 {
    margin: 12px 0 2px;
    font-size: 1.05rem;
}

.stats-section p {
    margin: 0;
    font-size: 0.85rem;
}

.stat-icon {
    font-size: 2rem;
    color: var(--purple-light);
}
.section-padding {
    padding: 100px 0;
}

.section-heading {
    margin-bottom: 45px;
}

.section-heading h2 {
    margin-bottom: 12px;
}

.section-heading p {
    max-width: 600px;
    margin: 0 auto;
}
.event-filters .filter-btn {
    border: 1px solid var(--border);
    background: var(--space-light);
    color: var(--muted);
    border-radius: 50px;
    padding: 8px 19px;
    transition: 0.2s ease;
}

.event-filters .filter-btn:hover,
.event-filters .filter-btn.active {
    color: var(--gold-light);
    border-color: var(--purple-light);
    background: var(--purple-dark);
}
.event-card {
    padding: 28px;
    border-radius: 16px;
    background:
        linear-gradient(145deg, rgba(38, 23, 55, 0.95), rgba(20, 12, 33, 0.98));
    border: 1px solid var(--border);
    transition: transform 0.25s ease, border-color 0.25s ease,
                box-shadow 0.25s ease;
}

.event-card:hover {
    transform: translateY(-6px);
    border-color: rgba(185, 92, 235, 0.7);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
}

.event-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.event-icon {
    width: 58px;
    height: 58px;
    display: grid;
    place-items: center;
    color: var(--gold-light);
    font-size: 1.8rem;
    border-radius: 14px;
    background: rgba(125, 44, 168, 0.23);
    border: 1px solid var(--border);
}

.event-status {
    padding: 5px 11px;
    border-radius: 50px;
    font-size: 0.73rem;
    font-weight: 700;
}

.status-open {
    color: #c5f7d3;
    background: rgba(52, 143, 85, 0.18);
    border: 1px solid rgba(98, 208, 137, 0.3);
}

.status-soon {
    color: var(--gold-light);
    background: rgba(211, 141, 25, 0.13);
    border: 1px solid rgba(211, 141, 25, 0.3);
}

.event-category {
    color: var(--purple-light);
    text-transform: uppercase;
    letter-spacing: 2px;
    font-size: 0.7rem;
    font-weight: 700;
}

.event-card h3 {
    font-size: 1.35rem;
    margin: 10px 0;
}

.event-description {
    font-size: 0.9rem;
    min-height: 100px;
}

.event-details {
    border-top: 1px solid var(--border);
    padding-top: 16px;
    margin-top: 20px;
    display: grid;
    gap: 9px;
    color: #d1c2e2;
    font-size: 0.82rem;
}

.event-details i {
    color: var(--gold-light);
    margin-right: 8px;
}
.about-section {
    background:
        radial-gradient(ellipse at 5% 50%, rgba(80, 28, 108, 0.3), transparent 45%),
        #160e24;
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}

.about-art {
    min-height: 340px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-radius: 20px;
    background:
        radial-gradient(circle at 50% 45%, rgba(185, 92, 235, 0.25), transparent 45%),
        linear-gradient(135deg, #241139, #10091d);
    border: 1px solid var(--border);
}

.about-orbit {
    position: absolute;
    width: 250px;
    height: 250px;
    border: 1px solid rgba(255, 201, 109, 0.5);
    border-radius: 50%;
    box-shadow: 0 0 50px rgba(185, 92, 235, 0.1);
}

.about-star {
    position: absolute;
    color: var(--gold-light);
    font-size: 6rem;
    filter: drop-shadow(0 0 25px rgba(255, 201, 109, 0.35));
}

.about-moon {
    position: absolute;
    color: var(--purple-light);
    font-size: 3rem;
    top: 35px;
    right: 45px;
}

.about-message {
    position: absolute;
    bottom: 35px;
    display: flex;
    gap: 20px;
    color: var(--gold-light);
    font-size: 0.7rem;
    letter-spacing: 3px;
}
.faq-container {
    max-width: 850px;
}

.accordion-celestial .accordion-item {
    color: var(--text);
    background: var(--space-light);
    border: 1px solid var(--border);
    margin-bottom: 12px;
    border-radius: 10px;
    overflow: hidden;
}

.accordion-celestial .accordion-button {
    color: var(--text);
    background: var(--space-light);
    font-weight: 600;
    box-shadow: none;
}

.accordion-celestial .accordion-button::after {
    filter: invert(1);
}

.accordion-celestial .accordion-button:not(.collapsed) {
    color: var(--gold-light);
    background: #29183b;
}

.accordion-celestial .accordion-body {
    color: var(--muted);
}



.banner-stars {
    color: var(--gold-light);
    font-size: 2rem;
}
.application-page {
    min-height: 75vh;
    padding: 70px 0 100px;
    background:
        radial-gradient(ellipse at 50% 0%, rgba(125, 44, 168, 0.18), transparent 55%),
        var(--space);
}

.application-page .section-heading {
    margin-bottom: 35px;
}

.application-panel {
    max-width: 760px;
    padding: clamp(22px, 5vw, 48px);
    background: linear-gradient(145deg, #21142f, #160e24);
    border: 1px solid var(--border);
    border-radius: 18px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
}

.form-section-title {
    color: var(--gold-light);
    font-weight: 700;
    font-size: 1.05rem;
    margin-bottom: 25px;
}

.form-section-title i {
    margin-right: 8px;
    color: var(--purple-light);
}

.form-label {
    color: var(--text);
    font-weight: 600;
    font-size: 0.9rem;
}

.form-control,
.form-select {
    color: var(--text);
    background-color: #120c20;
    border: 1px solid #503660;
    border-radius: 9px;
    padding: 12px 14px;
}

.form-control::placeholder {
    color: #91819f;
}

.form-control:focus,
.form-select:focus {
    color: white;
    background-color: #120c20;
    border-color: var(--purple-light);
    box-shadow: 0 0 0 0.2rem rgba(185, 92, 235, 0.15);
}

.form-select option {
    color: white;
    background: var(--space-light);
}

.form-text {
    color: #b3a2c4;
    font-size: 0.78rem;
}


.form-check-input {
    background-color: #120c20;
    border-color: var(--purple-light);
}

.form-check-input:checked {
    background-color: var(--purple);
    border-color: var(--purple-light);
}

.form-check-label {
    color: var(--muted);
    font-size: 0.85rem;
}

.alert-danger {
    color: #ffe2e2;
    background: #401c2a;
    border-color: #793349;
}
.site-footer {
    padding: 35px 0 18px;
    background: #0c0715;
}

.site-footer p {
    font-size: 0.9rem;
}

.footer-link {
    color: var(--gold-light);
}

.footer-link:hover {
    color: white;
}

.footer-bottom {
    margin-top: 25px;
    padding-top: 18px;
    border-top: 1px solid var(--border);
    color: #9381a5;
}
@media (max-width: 991.98px) {

    .hero {
        min-height: auto;
    }

    .hero-content {
        padding-top: 65px;
        padding-bottom: 65px;
    }

    .section-padding {
        padding: 70px 0;
    }
}

@media (max-width: 575.98px) {
    body {
        padding-top: 70px;
    }


    .hero h1 {
        font-size: 2.55rem;
    }

    .hero-description {
        font-size: 0.95rem;
    }

    .btn-lg {
        font-size: 0.9rem;
    }

    .event-card {
        padding: 22px;
    }

    .event-description {
        min-height: 0;
    }

    .about-art {
        min-height: 280px;
    }

    .about-message {
        gap: 12px;
        letter-spacing: 2px;
    }
}

@media (prefers-reduced-motion: reduce) {
    html {
        scroll-behavior: auto;
    }

    *,
    *::before,
    *::after {
        transition-duration: 0.01ms !important;
        animation-duration: 0.01ms !important;
    }
}
</style>
<!-- Hero -->
<header class="hero">
    <div class="hero-stars"></div>

    <div class="container hero-content" id="home">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="eyebrow">
                    <i class="bi bi-stars"></i>
                    YOUR STORY STARTS HERE
                </span>

                <h1>
                    A WORLD<br>
                    <span class="gradient-text">WAITING TO BE</span><br>
                    BUILT.
                </h1>

                <p class="hero-description">
                    Build a nation. Forge alliances. Explore the unknown.
                    Join a Minecraft civilisation where your choices
                    help shape the world.
                </p>

                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="#events" class="btn btn-gold btn-lg">
                        Explore Events <i class="bi bi-arrow-down-right ms-2"></i>
                    </a>
                    <a href="apply" class="btn btn-outline-celestial btn-lg">
                        Apply to Join
                    </a>
                </div>

                <div class="hero-note mt-4">
                    <i class="bi bi-moon-stars me-2"></i>
                    A community built around strategy, creativity and discovery.
                </div>
            </div>

            <div class="col-lg-5 d-none d-lg-block">
                <div class="cosmic-art">
                    <div class="orbit orbit-one"></div>
                    <div class="orbit orbit-two"></div>
                    <div class="planet">
                        <i class="bi bi-stars"></i>
                    </div>
                    <i class="bi bi-moon-stars cosmic-moon"></i>
                    <i class="bi bi-stars floating-star star-one"></i>
                    <i class="bi bi-stars floating-star star-two"></i>
                    <i class="bi bi-stars floating-star star-three"></i>
                    <div class="cosmic-caption">A NEW WORLD AWAITS</div>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-bottom"></div>
</header>

<!-- Quick stats -->
<section class="stats-section">
    <div class="container">
        <div class="row text-center g-3">
            <div class="col-6 col-md-3">
                <i class="bi bi-globe2 stat-icon"></i>
                <h3>One World</h3>
                <p>Endless possibilities</p>
            </div>
            <div class="col-6 col-md-3">
                <i class="bi bi-people stat-icon"></i>
                <h3>One Community</h3>
                <p>Build together</p>
            </div>
            <div class="col-6 col-md-3">
                <i class="bi bi-shield-shaded stat-icon"></i>
                <h3>Lore Driven</h3>
                <p>Your choices matter</p>
            </div>
            <div class="col-6 col-md-3">
                <i class="bi bi-stars stat-icon"></i>
                <h3>New Events</h3>
                <p>New stories unfold</p>
            </div>
        </div>
    </div>
</section>

<!-- Events -->
<section class="section-padding">
    <div class="container" id="events">
        <div class="section-heading text-center">
            <span class="eyebrow">CHOOSE YOUR ADVENTURE</span>
            <h2>Upcoming <span class="gradient-text">Events</span></h2>
            <p>Find your place in the story and apply for your next adventure.</p>
        </div>

        <!-- Event filters -->
        <div class="event-filters d-flex flex-wrap justify-content-center gap-2 mb-5">
            <button class="filter-btn active" data-filter="all">All Events</button>
            <button class="filter-btn" data-filter="Civilisation">Civilisation</button>
            <button class="filter-btn" data-filter="Exploration">Exploration</button>
            <button class="filter-btn" data-filter="Combat">Combat</button>
        </div>

        <div class="row g-4" id="eventGrid">
            <?php foreach ($events as $event): ?>
                <div class="col-md-6 col-xl-4 event-item"
                     data-type="<?= htmlspecialchars($event['type']) ?>">
                    <article class="event-card h-100">
                        <div class="event-card-top">
                            <div class="event-icon">
                                <i class="bi <?= htmlspecialchars($event['icon']) ?>"></i>
                            </div>

                            <span class="event-status <?= $event['status'] === 'Open' ? 'status-open' : 'status-soon' ?>">
                                <?= htmlspecialchars($event['status']) ?>
                            </span>
                        </div>

                        <span class="event-category">
                            <?= htmlspecialchars($event['type']) ?>
                        </span>

                        <h3><?= htmlspecialchars($event['title']) ?></h3>

                        <p class="event-description">
                            <?= htmlspecialchars($event['description']) ?>
                        </p>

                        <div class="event-details">
                            <div>
                                <i class="bi bi-calendar3"></i>
                                <?= htmlspecialchars($event['display_date']) ?>
                            </div>
                            <div>
                                <i class="bi bi-people"></i>
                                <?= htmlspecialchars($event['players']) ?>
                            </div>
                        </div>

                        <?php if ($event['status'] === 'Open'): ?>
                            <a class="btn btn-gold w-100 mt-4"
                               href="apply?event=<?= urlencode($event['id']) ?>">
                                Apply for Event <i class="bi bi-arrow-up-right ms-2"></i>
                            </a>
                        <?php else: ?>
                            <button class="btn btn-disabled w-100 mt-4" disabled>
                                Applications Not Yet Open
                            </button>
                        <?php endif; ?>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>

        <p id="noEvents" class="text-center mt-4 d-none">
            No events found in this category.
        </p>
    </div>
</section>

<!-- About -->
<section class="about-section section-padding" >
    <div class="container" id="about">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="about-art">
                    <i class="bi bi-stars about-star"></i>
                    <i class="bi bi-moon about-moon"></i>
                    <div class="about-orbit"></div>
                    <div class="about-message">
                        <span>EXPLORE</span>
                        <span>BUILD</span>
                        <span>BELONG</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <span class="eyebrow">MORE THAN JUST MINECRAFT</span>
                <h2>Make Your Mark<br>on the <span class="gradient-text">World.</span></h2>
                <p>
                    This is a civilisation experience shaped by its players.
                    Create settlements, build economies, form alliances,
                    explore distant lands and take part in server-wide events.
                </p>
                <p>
                    Every event is a chance to meet new people, test your
                    strategy and contribute to a story that the community
                    creates together.
                </p>
                <a href="apply" class="btn btn-gold mt-2">
                    Start Your Application <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="section-padding">
    <div class="container faq-container" id="faq">
        <div class="section-heading text-center">
            <span class="eyebrow">GOOD TO KNOW</span>
            <h2>Frequently Asked <span class="gradient-text">Questions</span></h2>
        </div>

        <div class="accordion accordion-celestial" id="faqAccordion">
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq1"
                            aria-expanded="true">
                        How do I join an event?
                    </button>
                </h3>
                <div id="faq1" class="accordion-collapse collapse show"
                     data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Choose an event, complete its application form and
                        wait for the server staff to review your submission.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq2">
                        Do I need experience with civilisation servers?
                    </button>
                </h3>
                <div id="faq2" class="accordion-collapse collapse"
                     data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Not necessarily. Tell us about yourself and what you
                        would like to contribute. Check each event's requirements.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq3">
                        How will I know if my application is accepted?
                    </button>
                </h3>
                <div id="faq3" class="accordion-collapse collapse"
                     data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        You will be given the Heartbound Accepted role in the Aurora Events 
                        discord server. Watch out for announcements in the future.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<footer class="site-footer">
    <div class="container d-flex flex-column flex-md-row justify-content-between
                align-items-center gap-3">
        <a class="navbar-brand brand" href="home">
            <i class="bi bi-moon-stars-fill brand-icon"></i>
            Aurora
            <span class="brand-subtitle">CIVILISATIONS</span>
        </a>
        <p class="mb-0">Build. Explore. Belong.</p>
        <a href="apply" class="footer-link">Applications</a>
    </div>
    <div class="container text-center footer-bottom">
        <small>&copy; <?= date('Y') ?> Aurora Civilisations. Not affiliated with Mojang or Microsoft.</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
    // Event category filters
    const filterButtons = document.querySelectorAll(".filter-btn");
    const eventCards = document.querySelectorAll(".event-item");
    const noEventsMessage = document.getElementById("noEvents");

    filterButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const filter = button.dataset.filter;
            let visibleCount = 0;

            filterButtons.forEach((item) => {
                item.classList.remove("active");
                item.setAttribute("aria-pressed", "false");
            });

            button.classList.add("active");
            button.setAttribute("aria-pressed", "true");

            eventCards.forEach((card) => {
                const matches =
                    filter === "all" || card.dataset.type === filter;

                card.classList.toggle("d-none", !matches);

                if (matches) {
                    visibleCount++;
                }
            });

            if (noEventsMessage) {
                noEventsMessage.classList.toggle(
                    "d-none",
                    visibleCount !== 0
                );
            }
        });

        button.setAttribute(
            "aria-pressed",
            button.classList.contains("active") ? "true" : "false"
        );
    });

    // Application statement character counter
    const reasonField = document.getElementById("reason");
    const reasonCounter = document.getElementById("reasonCounter");

    function updateCharacterCount() {
        if (!reasonField || !reasonCounter) return;

        const length = reasonField.value.length;
        reasonCounter.textContent = `${length} / 3000`;

        reasonCounter.style.color =
            length < 30 ? "#FFC96D" : "#c5f7d3";
    }

    if (reasonField) {
        reasonField.addEventListener("input", updateCharacterCount);
        updateCharacterCount();
    }

    // Basic client-side form feedback
    const form = document.getElementById("applicationForm");

    if (form) {
        form.addEventListener("submit", (event) => {
            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
            }
        });
    }
});
</script>
</body>
</html> 