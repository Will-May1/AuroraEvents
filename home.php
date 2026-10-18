<?php
$events = [
    [
        "id" => "founding",
        "title" => "Founding of Nations",
        "icon" => "bi-stars",
        "type" => "Civilization",
        "date" => "2026-11-07",
        "display_date" => "7 November 2026",
        "players" => "20–40 players",
        "description" => "Join the beginning of a new world. Form a nation, claim land, and help shape the server's first civilizations.",
        "status" => "Open"
    ],
    [
        "id" => "frontier",
        "title" => "The Unknown Frontier",
        "icon" => "bi-moon-stars",
        "type" => "Exploration",
        "date" => "2026-11-14",
        "display_date" => "14 November 2026",
        "players" => "10–25 players",
        "description" => "Venture into uncharted lands, discover resources, and establish settlements beyond the known world.",
        "status" => "Open"
    ],
    [
        "id" => "war",
        "title" => "Empires at War",
        "icon" => "bi-meteor",
        "type" => "PvP",
        "date" => "2026-11-21",
        "display_date" => "21 November 2026",
        "players" => "16–32 players",
        "description" => "Choose your allies, prepare your defenses, and compete for control in a civilization-scale conflict.",
        "status" => "Coming Soon"
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
          content="Discover Minecraft civilization events and apply to join the next adventure.">

    <title>Starfall Civilizations | Events & Applications</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
          rel="stylesheet">

    <link rel="stylesheet" href="sheet.css">
</head>
<body>

<!-- Fixed navigation -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top site-navbar">
    <div class="container">
        <a class="navbar-brand brand" href="home">
            <i class="bi bi-moon-stars-fill brand-icon"></i>
            STARFALL
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
                    <a class="nav-link active" href="#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#events">Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#faq">FAQ</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-celestial mt-2 mt-lg-0"
                       href="apply">
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

<!-- Hero -->
<header class="hero" id="home">
    <div class="hero-stars"></div>

    <div class="container hero-content">
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
                    Join a Minecraft civilization where your choices
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
                <h3>Player Driven</h3>
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
<section class="section-padding" id="events">
    <div class="container">
        <div class="section-heading text-center">
            <span class="eyebrow">CHOOSE YOUR ADVENTURE</span>
            <h2>Upcoming <span class="gradient-text">Events</span></h2>
            <p>Find your place in the story and apply for your next adventure.</p>
        </div>

        <!-- Event filters -->
        <div class="event-filters d-flex flex-wrap justify-content-center gap-2 mb-5">
            <button class="filter-btn active" data-filter="all">All Events</button>
            <button class="filter-btn" data-filter="Civilization">Civilization</button>
            <button class="filter-btn" data-filter="Exploration">Exploration</button>
            <button class="filter-btn" data-filter="PvP">PvP</button>
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
<section class="about-section section-padding" id="about">
    <div class="container">
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
                    This is a civilization experience shaped by its players.
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
<section class="section-padding" id="faq">
    <div class="container faq-container">
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
                        Do I need experience with civilization servers?
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
                        Staff should contact you using the Discord username
                        or other contact details you provide. Check the server's
                        official announcements for application updates.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Application call to action -->
<section class="apply-banner">
    <div class="container text-center">
        <i class="bi bi-stars banner-stars"></i>
        <h2>Your Next Chapter Starts Here.</h2>
        <p>Choose your event. Submit your application. Become part of the story.</p>
        <a href="apply" class="btn btn-gold btn-lg">
            Apply Now <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</section>

<footer class="site-footer">
    <div class="container d-flex flex-column flex-md-row justify-content-between
                align-items-center gap-3">
        <a class="navbar-brand brand" href="home">
            <i class="bi bi-moon-stars-fill brand-icon"></i>
            STARFALL
            <span class="brand-subtitle">CIVILIZATIONS</span>
        </a>
        <p class="mb-0">Build. Explore. Belong.</p>
        <a href="apply" class="footer-link">Applications</a>
    </div>
    <div class="container text-center footer-bottom">
        <small>&copy; <?= date('Y') ?> Starfall Civilizations. Not affiliated with Mojang or Microsoft.</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html> 