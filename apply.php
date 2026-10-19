<?php
session_start();
$submitted = false;
$errors = [];

define('DB_HOST', 'sql202.infinityfree.com');
define('DB_USER', 'if0_43187131');
define('DB_PASS', 'hHimykmJ2nY');
define('DB_NAME', 'if0_43187131_AuroraEventsMain');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Structural required array matching the keys sent by your HTML form fields
    $required = [
        'discord_username', 'minecraft_ign', 'age_group',
        'microphone', 'streamingyn', 'about_you', 'event_goals',
        'past_experience', 'character_idea', 'problem_solving',
        'resource_scenario', 'information_scenario', 'friend_scenario',
        'confirm_truth'
    ];

    // Standard fallback string for event naming metrics
    $event_name = "Aurora Civilisations";

    // Validate presence of required data fields
    foreach ($required as $field) {
        if (!isset($_POST[$field]) || trim((string)$_POST[$field]) === '') {
            $errors[] = $field;
        }
    }

    if (empty($errors)) {
        try {

            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

            // Translate your form's text values to your required database integers (0, 1, 2, 3)
            $age_string = (string)$_POST['age_group'];
            $age_integer = 0; // Default fallback for '<12'
            if ($age_string === '12-14') { $age_integer = 1; }
            elseif ($age_string === '15-17') { $age_integer = 2; }
            elseif ($age_string === '18plus') { $age_integer = 3; }

            // Parameterized query blueprint mapped explicitly to your custom fields layout
            $sql = "INSERT INTO tblApps (
                        eventName, discUser, mcIGN, ageGrp, mic, stream, 
                        abtYou, goals, experience, charIdea, problem, 
                        resourceScen, infoScen, friendScen, accepted
                    ) VALUES (
                        :eventName, :discUser, :mcIGN, :ageGrp, :mic, :stream, 
                        :abtYou, :goals, :experience, :charIdea, :problem, 
                        :resourceScen, :infoScen, :friendScen, :accepted
                    )";

            $stmt = $pdo->prepare($sql);

            // Execute input bindings safely away from the underlying MySQL structure
            $stmt->execute([
                ':eventName'    => $event_name,
                ':discUser'     => trim((string)$_POST['discord_username']),
                ':mcIGN'        => trim((string)$_POST['minecraft_ign']),
                ':ageGrp'       => $age_integer,
                ':mic'          => (string)$_POST['microphone'],
                ':stream'       => (string)$_POST['streaming'],
                ':abtYou'       => trim((string)$_POST['about_you']),
                ':goals'        => trim((string)$_POST['event_goals']),
                ':experience'   => trim((string)$_POST['past_experience']),
                ':charIdea'     => trim((string)$_POST['character_idea']),
                ':problem'      => trim((string)$_POST['problem_solving']),
                ':resourceScen' => trim((string)$_POST['resource_scenario']),
                ':infoScen'     => trim((string)$_POST['information_scenario']),
                ':friendScen'   => trim((string)$_POST['friend_scenario']),
                ':accepted'     => 0 // Applications default to pending review (0) upon submission
            ]);

            $submitted = true;

        } catch (PDOException $e) {
            // Logs data parsing complications system side safely
            error_log("Application submission failed database insert inside tblApps: " . $e->getMessage());
            $errors[] = 'database_error';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Event Application | Aurora Civilizations</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    :root {
      --deep-purple: #501C6C;
      --purple: #7D2CA8;
      --bright-purple: #B95CEB;
      --burnt-orange: #9C420D;
      --gold: #D38D19;
      --pale-gold: #FFC96D;
      --ink: #1b1022;
      --panel: rgba(39, 20, 51, .91);
      --space: #10091d;
    --space-light: #1a102b;
    --purple-dark: #501C6C;
    --purple-light: #B95CEB;
    --orange-dark: #9C420D;
    --gold-light: #FFC96D;
    --text: #f6efff;
    --muted: #c4b6d8;
    --border: rgba(185, 92, 235, 0.25);
    }
    * { box-sizing: border-box; }
    
    .brand-subtitle {
        display: block;
        margin-left: 37px;
        margin-top: 5px;
        font: 0.55rem "Inter", sans-serif;
        letter-spacing: 3px;
        color: var(--muted);
}
    .brand-mark { color: var(--pale-gold); }
    .nav-link { color: #e8d9ef !important; }
    .nav-link:hover { color: var(--pale-gold) !important; }
    .hero { padding: 3.4rem 0 2rem; text-align: center; }
    .eyebrow1 {
      display: inline-flex; gap: .5rem; align-items: center;
      color: var(--pale-gold); text-transform: uppercase;
      letter-spacing: .18em; font-size: .75rem; font-weight: 800;
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
      font-weight: 900; letter-spacing: -.04em;
      font-size: clamp(2.2rem, 5vw, 4rem);
      margin: .7rem 0;
    }
    .hero h1 span {
      background: linear-gradient(90deg, var(--bright-purple), var(--pale-gold));
      -webkit-background-clip: text; background-clip: text; color: transparent;
    }
    .hero p { max-width: 680px; margin: 0 auto; color: #cdbfd5; }
    .application-shell {
      max-width: 960px; margin: 0 auto 5rem;
      background: var(--panel); border: 1px solid rgba(185,92,235,.28);
      border-radius: 1.35rem; overflow: hidden;
      box-shadow: 0 24px 80px rgba(0,0,0,.28);
    }
    .progress-area { padding: 1.5rem 1.5rem 1.25rem; background: rgba(13,7,18,.35); border-bottom: 1px solid rgba(255,255,255,.08); }
    .step-track { display: grid; grid-template-columns: repeat(4, 1fr); gap: .65rem; }
    .step-button {
      border: 0; background: transparent; color: #9c8ba8; text-align: left;
      padding: .4rem; border-radius: .8rem; min-width: 0;
    }
    .step-button:disabled { cursor: not-allowed; opacity: .55; }
    .step-button:not(:disabled) { cursor: pointer; }
    .step-button.active { color: #fff; }
    .step-top { display: flex; align-items: center; gap: .55rem; margin-bottom: .5rem; }
    .step-number {
      width: 2rem; height: 2rem; flex: 0 0 2rem; display: grid; place-items: center;
      border: 1px solid #685071; border-radius: 50%; font-size: .8rem; font-weight: 800;
      background: #2b1735;
    }
    .step-button.active .step-number { color: #1b1022; background: var(--pale-gold); border-color: var(--pale-gold); }
    .step-button.complete .step-number { color: white; background: var(--purple); border-color: var(--bright-purple); }
    .step-label { font-size: .77rem; font-weight: 750; line-height: 1.25; }
    .progress { height: 5px; margin-top: 1.15rem; background: #3a2943; }
    .progress-bar { background: linear-gradient(90deg, var(--purple), var(--pale-gold)); transition: width .25s ease; }
    .form-content { padding: clamp(1.25rem, 4vw, 2.6rem); }
    .section-kicker { color: var(--bright-purple); font-size: .75rem; font-weight: 800; letter-spacing: .15em; text-transform: uppercase; }
    .section-title { font-weight: 850; margin: .35rem 0 .5rem; }
    .section-intro { color: #c6b7cf; margin-bottom: 1.8rem; }
    .question-card {
      padding: 1.15rem; margin-bottom: 1rem;
      background: rgba(255,255,255,.035); border: 1px solid rgba(255,255,255,.09);
      border-radius: .9rem;
    }
    .form-label, .question-label { font-weight: 700; margin-bottom: .6rem; }
    .required-star { color: var(--pale-gold); }
    .form-control, .form-select {
      color: #fff; background-color: #21132b; border: 1px solid #594263;
      border-radius: .65rem; padding: .8rem .9rem;
    }
    .form-control:focus, .form-select:focus {
      color: #fff; background-color: #21132b; border-color: var(--bright-purple);
      box-shadow: 0 0 0 .2rem rgba(185,92,235,.18);
    }
    .form-control::placeholder { color: #8e7c99; }
    .form-select option { background: #21132b; color: #fff; }
    .form-text { color: #a998b5; }
    .form-check {
      border: 1px solid #594263; border-radius: .7rem; padding: .8rem .9rem .8rem 2.4rem;
      background: rgba(255,255,255,.025); min-height: 3rem;
    }
    .form-check-input { background-color: #21132b; border-color: #8c7299; }
    .form-check-input:checked { background-color: var(--purple); border-color: var(--bright-purple); }
    .form-check-input:focus { box-shadow: 0 0 0 .2rem rgba(185,92,235,.18); }
    .form-check-label { color: #eee4f2; }
    .btn-primary {
      background: linear-gradient(110deg, var(--purple), var(--deep-purple));
      border: 1px solid var(--bright-purple); font-weight: 750;
    }
    .btn-primary:hover { background: var(--deep-purple); border-color: var(--pale-gold); }
    .btn-outline-light { border-color: #725d7e; color: #e8d9ef; }
    .btn-outline-light:hover { background: #392345; color: #fff; border-color: var(--bright-purple); }
    .btn-gold { background: var(--pale-gold); color: #25142c; border: 0; font-weight: 850; }
    .btn-gold:hover { background: var(--gold); color: #1b1022; }
    .form-actions { display: flex; justify-content: space-between; gap: 1rem; margin-top: 2rem; padding-top: 1.4rem; border-top: 1px solid rgba(255,255,255,.09); }
    .privacy-note { color: #a998b5; font-size: .82rem; }
    .scenario-number { color: var(--pale-gold); font-weight: 900; font-size: .8rem; letter-spacing: .12em; }
    .d-none-step { display: none !important; }
    .alert { border-radius: .8rem; }
    footer { color: #9b8ba6; text-align: center; padding: 0 1rem 2rem; font-size: .85rem; }
    @media (max-width: 575px) {
      .step-track { gap: .2rem; }
      .step-button { padding: .2rem; }
      .step-top { gap: .3rem; }
      .step-number { width: 1.7rem; height: 1.7rem; flex-basis: 1.7rem; }
      .step-label { font-size: .65rem; }
      .progress-area { padding: 1rem .75rem; }
      .form-actions { flex-direction: column-reverse; }
      .form-actions .btn { width: 100%; }
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
    }
  </style>
</head>
<body>
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
                    <a class="nav-link active" href="https://discord.gg/Te8NknMpv8y">Discord</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="home">Home</a>
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
<main class="application-page">
    <div class="container">
        <div class="section-heading text-center">
            <span class="eyebrow">
                <i class="bi bi-stars me-1"></i> YOUR JOURNEY BEGINS
            </span>
            <h1>Event <span class="gradient-text">Application</span></h1>
            <p>Tell us about yourself and the role you want to play.</p>
        </div>
    </div>
  <div class="application-shell">
    <?php if ($submitted): ?>
      <div class="p-4 p-md-5 text-center">
        <div class="display-4 mb-3" style="color:var(--pale-gold)"><i class="bi bi-check-circle-fill"></i></div>
        <h2 class="fw-bold">Application complete!</h2>
        <p class="text-light-emphasis">Once the application deadline ends, you will be contacted via the Aurora Events Discord Server.</p>
        <a class="btn btn-gold rounded-pill px-4 mt-2" href="home">Return home <i class="bi bi-arrow-right ms-1"></i></a>
      </div>
    <?php else: ?>
      <?php if (!empty($errors)): ?>
        <div class="alert alert-warning m-3 mb-0" role="alert">Please complete every required question before submitting.</div>
      <?php endif; ?>
      <div class="progress-area">
        <div class="step-track" aria-label="Application sections">
          <button type="button" class="step-button active" data-step-button="0" aria-current="step">
            <div class="step-top"><span class="step-number">1</span><span class="step-label">Personal</span></div>
          </button>
          <button type="button" class="step-button" data-step-button="1" disabled>
            <div class="step-top"><span class="step-number">2</span><span class="step-label">Technical</span></div>
          </button>
          <button type="button" class="step-button" data-step-button="2" disabled>
            <div class="step-top"><span class="step-number">3</span><span class="step-label">Experience</span></div>
          </button>
          <button type="button" class="step-button" data-step-button="3" disabled>
            <div class="step-top"><span class="step-number">4</span><span class="step-label">Scenarios</span></div>
          </button>
        </div>
        <div class="progress" role="progressbar" aria-label="Application progress" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25">
          <div class="progress-bar" id="progressBar" style="width:25%"></div>
        </div>
      </div>

      <form id="applicationForm" class="form-content" method="post" action="apply" novalidate>
        <section class="form-step" data-step="0">
          <div class="section-kicker">Section 01 / 04</div>
          <h2 class="section-title">Personal questions</h2>
          <p class="section-intro">First, give us the details we need to identify you in Discord and in-game.</p>

          <div class="question-card">
            <label class="form-label" for="discord_username">Discord username <span class="required-star">*</span></label>
            <input class="form-control" id="discord_username" name="discord_username" type="text" maxlength="80" placeholder="e.g. username" required>
            <div class="invalid-feedback">Enter your Discord username.</div>
          </div>
          <div class="question-card">
            <label class="form-label" for="minecraft_ign">Minecraft IGN <span class="required-star">*</span></label>
            <input class="form-control" id="minecraft_ign" name="minecraft_ign" type="text" maxlength="32" placeholder="Your Minecraft username" required>
            <div class="invalid-feedback">Enter your Minecraft username.</div>
          </div>
          <div class="question-card">
            <div class="question-label">Age group <span class="required-star">*</span></div>
            <div class="row g-2">
              <?php foreach (['under12' => '<12', '12-14' => '12-14', '15-17' => '15-17', '18plus' => '18+'] as $value => $label): ?>
                <div class="col-6 col-md-3">
                  <div class="form-check h-100">
                    <input class="form-check-input" type="radio" name="age_group" id="age_<?php echo htmlspecialchars($value, ENT_QUOTES); ?>" value="<?php echo htmlspecialchars($value, ENT_QUOTES); ?>" required>
                    <label class="form-check-label" for="age_<?php echo htmlspecialchars($value, ENT_QUOTES); ?>"><?php echo $label; ?></label>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="form-text mt-2">Choose the age range that applies to you.</div>
            <div class="invalid-feedback d-block" id="ageFeedback" hidden>Select an age group to continue.</div>
          </div>
          <div class="form-actions">
            <span class="privacy-note align-self-center"><i class="bi bi-shield-lock me-1"></i>Your answers should only be visible to the event team.</span>
            <button type="button" class="btn btn-primary rounded-pill px-4 next-btn">Continue <i class="bi bi-arrow-right ms-1"></i></button>
          </div>
        </section>

        <section class="form-step d-none-step" data-step="1" aria-hidden="true">
          <div class="section-kicker">Section 02 / 04</div>
          <h2 class="section-title">Technical questions</h2>
          <p class="section-intro">Let us know what equipment you have and whether you plan to share the event.</p>

          <div class="question-card">
            <div class="question-label">Do you have a working microphone? <span class="required-star">*</span></div>
            <div class="d-flex flex-wrap gap-2">
              <div class="form-check flex-grow-1">
                <input class="form-check-input" type="radio" name="microphone" id="mic_yes" value="yes" required>
                <label class="form-check-label" for="mic_yes">Yes</label>
              </div>
              <div class="form-check flex-grow-1">
                <input class="form-check-input" type="radio" name="microphone" id="mic_no" value="no" required>
                <label class="form-check-label" for="mic_no">No</label>
              </div>
            </div>
            <div class="invalid-feedback d-block" data-radio-feedback="microphone" hidden>Choose yes or no.</div>
          </div>
          <div class="question-card">
            <div class="question-label">Will you be streaming or recording this event? <span class="required-star">*</span></div>
            <div class="d-flex flex-wrap gap-2 mb-3">
              <div class="form-check flex-grow-1">
                <input class="form-check-input" type="radio" name="streamingyn" id="stream_yes" value="yes" required>
                <label class="form-check-label" for="stream_yes">Yes</label>
              </div>
              <div class="form-check flex-grow-1">
                <input class="form-check-input" type="radio" name="streamingyn" id="stream_no" value="no" required>
                <label class="form-check-label" for="stream_no">No</label>
              </div>
            </div>
            <label class="form-label" for="streaming_platform">If yes, where will you stream or upload it?</label>
            <input class="form-control" id="streaming_platform" name="streaming" type="text" maxlength="200" placeholder="e.g. Twitch, YouTube, or a channel link">
            <div class="form-text">If you are not streaming or recording, you can leave this blank.</div>
            <div class="invalid-feedback d-block" data-radio-feedback="streaming" hidden>Choose yes or no.</div>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-outline-light rounded-pill px-4 back-btn"><i class="bi bi-arrow-left me-1"></i> Back</button>
            <button type="button" class="btn btn-primary rounded-pill px-4 next-btn">Continue <i class="bi bi-arrow-right ms-1"></i></button>
          </div>
        </section>

        <section class="form-step d-none-step" data-step="2" aria-hidden="true">
          <div class="section-kicker">Section 03 / 04</div>
          <h2 class="section-title">Player experience</h2>
          <p class="section-intro">There are no perfect answers. We want to understand your approach to roleplay, storytelling, and playing with others.</p>

          <div class="question-card">
            <label class="form-label" for="about_you">Tell us a little about yourself. <span class="required-star">*</span></label>
            <textarea class="form-control" id="about_you" name="about_you" rows="3" minlength="10" maxlength="2000" placeholder="What should the event team know about you?" required></textarea>
            <div class="form-text">A few sentences is a good start.</div><div class="invalid-feedback">Please write a little about yourself.</div>
          </div>
          <div class="question-card">
            <label class="form-label" for="event_goals">What are your goals for this event? <span class="required-star">*</span></label>
            <textarea class="form-control" id="event_goals" name="event_goals" rows="3" minlength="10" maxlength="2000" placeholder="What would make this event memorable for you?" required></textarea>
            <div class="invalid-feedback">Tell us what you hope to get out of the event.</div>
          </div>
          <div class="question-card">
            <label class="form-label" for="past_experience">Describe your past experiences with Minecraft events, roleplay events, and storytelling. Please be specific with your answer. <span class="required-star">*</span></label>
            <textarea class="form-control" id="past_experience" name="past_experience" rows="5" minlength="20" maxlength="4000" placeholder="Mention events you have taken part in, roles you played, stories you helped create, or what you learned. If you are new, explain what interests you." required></textarea>
            <div class="invalid-feedback">Please give a specific answer, or explain if you are new to these events.</div>
          </div>
          <div class="question-card">
            <label class="form-label" for="character_idea">Describe a character you might make for this event. Please be specific with your answer. <span class="required-star">*</span></label>
            <textarea class="form-control" id="character_idea" name="character_idea" rows="5" minlength="20" maxlength="4000" placeholder="Consider their background, personality, motivations, strengths, flaws, and how they might develop." required></textarea>
            <div class="invalid-feedback">Describe your character idea in more detail.</div>
          </div>
          <div class="question-card">
            <label class="form-label" for="problem_solving">Tell us about a time when something went wrong for you. How did you deal with that? <span class="required-star">*</span></label>
            <textarea class="form-control" id="problem_solving" name="problem_solving" rows="4" minlength="15" maxlength="3000" placeholder="What happened, what did you do, and what did you learn?" required></textarea>
            <div class="invalid-feedback">Describe the situation and how you responded.</div>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-outline-light rounded-pill px-4 back-btn"><i class="bi bi-arrow-left me-1"></i> Back</button>
            <button type="button" class="btn btn-primary rounded-pill px-4 next-btn">Continue <i class="bi bi-arrow-right ms-1"></i></button>
          </div>
        </section>

        <section class="form-step d-none-step" data-step="3" aria-hidden="true">
          <div class="section-kicker">Section 04 / 04</div>
          <h2 class="section-title">Scenario questions</h2>
          <p class="section-intro">Think through each situation carefully. We are interested in your reasoning, priorities, and how you would handle difficult choices in a shared story.</p>

          <div class="question-card">
            <div class="scenario-number mb-2">SCENARIO 01</div>
            <label class="form-label" for="resource_scenario">You desperately need a certain resource, but the area you occupy has none of it. Another group has an abundance of it, and they have refused all trade offers. What do you do? <span class="required-star">*</span></label>
            <textarea class="form-control" id="resource_scenario" name="resource_scenario" rows="5" minlength="20" maxlength="4000" placeholder="Explain your options, risks, and why you would choose your approach." required></textarea>
            <div class="invalid-feedback">Explain how you would approach this situation.</div>
          </div>
          <div class="question-card">
            <div class="scenario-number mb-2">SCENARIO 02</div>
            <label class="form-label" for="information_scenario">You come across some information that could ensure safety for your group, but revealing it to anyone would also ensure the safety of your enemies. No one else knows this information. What do you do? What are your priorities? <span class="required-star">*</span></label>
            <textarea class="form-control" id="information_scenario" name="information_scenario" rows="5" minlength="20" maxlength="4000" placeholder="Who would you tell, if anyone? How would you weigh competing risks?" required></textarea>
            <div class="invalid-feedback">Explain your decision and your priorities.</div>
          </div>
          <div class="question-card">
            <div class="scenario-number mb-2">SCENARIO 03</div>
            <label class="form-label" for="friend_scenario">You overhear a dangerous group of players talking about murdering a friend of yours. Later in the session, you get a chance to have a one-on-one with this person. You know that telling them they are in danger could potentially put your own life at risk; however, keeping this information to yourself would put them at the mercy of the group. What do you do? <span class="required-star">*</span></label>
            <textarea class="form-control" id="friend_scenario" name="friend_scenario" rows="5" minlength="20" maxlength="4000" placeholder="Consider trust, safety, consequences, and how you would handle the situation in-character." required></textarea>
            <div class="invalid-feedback">Explain what you would do and why.</div>
          </div>
          <div class="question-card">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="confirm_truth" name="confirm_truth" value="yes" required>
              <label class="form-check-label" for="confirm_truth">I confirm that these answers are my own and that I will follow the event rules. <span class="required-star">*</span></label>
            </div>
            <div class="invalid-feedback d-block" id="confirmFeedback" hidden>Please confirm before submitting.</div>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-outline-light rounded-pill px-4 back-btn"><i class="bi bi-arrow-left me-1"></i> Back</button>
            <button type="submit" class="btn btn-gold rounded-pill px-4">Submit application <i class="bi bi-stars ms-1"></i></button>
          </div>
        </section>
      </form>
    <?php endif; ?>
  </div>
</main>
<footer>
  <div><i class="bi bi-stars me-1"></i> Aurora Civilizations</div>
  <div class="mt-1">Forge alliances. Create stories. Leave your mark among the stars.</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(() => {
  const form = document.getElementById('applicationForm');
  if (!form) return;

  const steps = [...document.querySelectorAll('.form-step')];
  const stepButtons = [...document.querySelectorAll('[data-step-button]')];
  const progressBar = document.getElementById('progressBar');
  const progress = document.querySelector('.progress[role="progressbar"]');
  let currentStep = 0;
  let highestUnlocked = 0;

  function showStep(index) {
    if (index > highestUnlocked || index < 0 || index >= steps.length) return;
    currentStep = index;
    steps.forEach((step, i) => {
      const active = i === index;
      step.classList.toggle('d-none-step', !active);
      step.setAttribute('aria-hidden', String(!active));
    });
    stepButtons.forEach((button, i) => {
      button.classList.toggle('active', i === index);
      button.classList.toggle('complete', i < index);
      button.toggleAttribute('aria-current', i === index);
      if (i === index) button.setAttribute('aria-current', 'step');
      else button.removeAttribute('aria-current');
      button.disabled = i > highestUnlocked;
    });
    const percent = ((index + 1) / steps.length) * 100;
    progressBar.style.width = percent + '%';
    progress.setAttribute('aria-valuenow', String(percent));
    window.scrollTo({ top: document.querySelector('.application-shell').getBoundingClientRect().top + window.scrollY - 90, behavior: 'smooth' });
    const heading = steps[index].querySelector('h2');
    if (heading) heading.setAttribute('tabindex', '-1'), heading.focus({preventScroll: true});
  }

  function validateStep(index) {
    const section = steps[index];
    let valid = true;
    section.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    // Native required, type, minlength, and maxlength validation.
    const controls = [...section.querySelectorAll('input, textarea, select')];
    controls.forEach(control => {
      if (control.type === 'radio' || control.type === 'checkbox') return;
      if (!control.checkValidity()) {
        control.classList.add('is-invalid');
        valid = false;
      }
    });

    // Radio groups need one selected answer.
    const radioNames = [...new Set([...section.querySelectorAll('input[type="radio"][required]')].map(el => el.name))];
    radioNames.forEach(name => {
      const group = [...section.querySelectorAll(`input[type="radio"][name="${name}"]`)];
      const selected = group.some(el => el.checked);
      const feedback = section.querySelector(`[data-radio-feedback="${name}"]`);
      if (!selected) {
        valid = false;
        if (feedback) feedback.hidden = false;
        group.forEach(el => el.classList.add('is-invalid'));
      } else if (feedback) {
        feedback.hidden = true;
        group.forEach(el => el.classList.remove('is-invalid'));
      }
    });

    if (index === 0) {
      const ageSelected = section.querySelector('input[name="age_group"]:checked');
      const ageFeedback = document.getElementById('ageFeedback');
      ageFeedback.hidden = !!ageSelected;
      if (!ageSelected) valid = false;
    }

    if (index === 3) {
      const confirmation = document.getElementById('confirm_truth');
      const confirmFeedback = document.getElementById('confirmFeedback');
      confirmFeedback.hidden = confirmation.checked;
      if (!confirmation.checked) valid = false;
    }

    if (!valid) {
      const firstInvalid = section.querySelector('.is-invalid') || section.querySelector('#ageFeedback:not([hidden]), #confirmFeedback:not([hidden])');
      if (firstInvalid && firstInvalid.focus) firstInvalid.focus();
    }
    return valid;
  }

  document.querySelectorAll('.next-btn').forEach(button => {
    button.addEventListener('click', () => {
      if (!validateStep(currentStep)) return;
      highestUnlocked = Math.max(highestUnlocked, currentStep + 1);
      showStep(currentStep + 1);
    });
  });

  document.querySelectorAll('.back-btn').forEach(button => {
    button.addEventListener('click', () => showStep(currentStep - 1));
  });

  stepButtons.forEach((button, index) => {
    button.addEventListener('click', () => {
      if (index <= highestUnlocked) showStep(index);
    });
  });

  form.addEventListener('submit', event => {
    if (!validateStep(currentStep)) {
      event.preventDefault();
      return;
    }
    // Earlier steps were validated before being unlocked, but validate all again for safety.
    for (let i = 0; i < steps.length; i++) {
      if (!validateStep(i)) {
        event.preventDefault();
        showStep(i);
        return;
      }
    }
  });

  form.querySelectorAll('input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', () => {
      const feedback = form.querySelector(`[data-radio-feedback="${radio.name}"]`);
      if (feedback && form.querySelector(`input[name="${radio.name}"]:checked`)) feedback.hidden = true;
      if (radio.name === 'age_group') document.getElementById('ageFeedback').hidden = true;
    });
  });
  document.getElementById('confirm_truth')?.addEventListener('change', event => {
    document.getElementById('confirmFeedback').hidden = event.target.checked;
  });
})();
</script>
</body>
</html>