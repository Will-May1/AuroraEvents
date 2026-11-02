<?php
declare(strict_types=1);
session_start();

$dbHost = 'sql202.infinityfree.com';
$dbName = 'if0_43187131_AuroraEventsMain';
$dbUser = 'if0_43187131';
$dbPass = 'hHimykmJ2nY';

$scoreQuestions = [
    'abtYou'       => 'Tell us a little about yourself',
    'goals'        => 'What are your goals for this event?',
    'experience'   => 'Describe your past Minecraft, roleplay and storytelling experience',
    'charIdea'     => 'What character might you make for this event?',
    'problem'      => 'Describe a time something went wrong and how you dealt with it',
    'resourceScen' => 'Resource scenario',
    'infoScen'     => 'Information and safety scenario',
    'friendScen'   => 'Friend in danger scenario',
];

$ageGroups = ['Under 12', '12–14', '15–17', '18+'];
$error = '';
$notice = '';
$app = null;
$pendingApps = [];

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    // Do not rely on a session username alone: confirm the account is still an admin.
    if (empty($_SESSION['account_ID'])) {
        header('Location: login');
        exit;
    }
    $adminCheck = $pdo->prepare('SELECT `isAdmin` FROM `tblAccounts` WHERE `accountID` = :id LIMIT 1');
    $adminCheck->execute(['id' => $_SESSION['account_ID']]);
    if ((int)$adminCheck->fetchColumn() !== 1) {
        $_SESSION = [];
        session_destroy();
        header('Location: login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $applicationID = filter_input(INPUT_POST, 'applicationID', FILTER_VALIDATE_INT);
        $decision = $_POST['decision'] ?? '';

        if (!$applicationID || !in_array($decision, ['accept', 'deny'], true)) {
            $error = 'Invalid submission. Please choose an application and a valid decision.';
        } else {
            // Re-check that the application is still pending, and lock it during review.
            $pdo->beginTransaction();
            $lock = $pdo->prepare('SELECT `applicationID`, `accepted` FROM `tblApps` WHERE `applicationID` = :id FOR UPDATE');
            $lock->execute(['id' => $applicationID]);
            $lockedApp = $lock->fetch();

            if (!$lockedApp || (int)$lockedApp['accepted'] !== 0) {
                $pdo->rollBack();
                $error = 'That application has already been reviewed or no longer exists.';
            } elseif ($decision === 'accept') {
                $scores = [];
                foreach ($scoreQuestions as $field => $label) {
                    $rawScore = $_POST['scores'][$field] ?? '';
                    if (filter_var($rawScore, FILTER_VALIDATE_INT) === false || (int)$rawScore < 1 || (int)$rawScore > 10) {
                        $error = 'Please give every written answer a score from 1 to 10 before accepting.';
                        break;
                    }
                    $scores[$field . 'Score'] = (int)$rawScore;
                }

                if ($error !== '') {
                    $pdo->rollBack();
                } else {
                    // Create tblAppScores with these column names (SQL is below the PHP page).
                    $columns = array_merge(['applicationID'], array_keys($scores));
                    $quotedColumns = array_map(static fn($column) => '`' . $column . '`', $columns);
                    $placeholders = array_map(static fn($column) => ':' . $column, $columns);
                    $insert = $pdo->prepare(
                        'INSERT INTO `tblAppScores` (' . implode(', ', $quotedColumns) . ') VALUES (' . implode(', ', $placeholders) . ')'
                    );
                    $insertValues = ['applicationID' => $applicationID] + $scores;
                    $insert->execute($insertValues);

                    $update = $pdo->prepare('UPDATE `tblApps` SET `accepted` = 1 WHERE `applicationID` = :id AND `accepted` = 0');
                    $update->execute(['id' => $applicationID]);
                    if ($update->rowCount() !== 1) {
                        throw new RuntimeException('The application status changed before it could be accepted.');
                    }
                    $pdo->commit();
                    $notice = 'Application accepted and scores saved.';
                }
            } else {
                $update = $pdo->prepare('UPDATE `tblApps` SET `accepted` = 2 WHERE `applicationID` = :id AND `accepted` = 0');
                $update->execute(['id' => $applicationID]);
                if ($update->rowCount() !== 1) {
                    throw new RuntimeException('The application status changed before it could be denied.');
                }
                $pdo->commit();
                $notice = 'Application denied.';
            }
        }
    }

    $pendingQuery = $pdo->query(
        'SELECT `applicationID`, `eventName`, `discUser`, `mcIGN` FROM `tblApps` WHERE `accepted` = 0 ORDER BY `applicationID` ASC'
    );
    $pendingApps = $pendingQuery->fetchAll();

    $requestedID = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['applicationID'])) {
        $requestedID = filter_var($_POST['applicationID'], FILTER_VALIDATE_INT) ?: null;
    }

    if ($requestedID) {
        $getApp = $pdo->prepare('SELECT * FROM `tblApps` WHERE `applicationID` = :id AND `accepted` = 0 LIMIT 1');
        $getApp->execute(['id' => $requestedID]);
        $app = $getApp->fetch() ?: null;
    }
    if (!$app && count($pendingApps) > 0) {
        $getApp = $pdo->prepare('SELECT * FROM `tblApps` WHERE `applicationID` = :id AND `accepted` = 0 LIMIT 1');
        $getApp->execute(['id' => $pendingApps[0]['applicationID']]);
        $app = $getApp->fetch() ?: null;
    }
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // Log technical details on the server; do not expose database errors to visitors.
    error_log((string)$e);
    $error = 'Something went wrong while processing the application. Check the server error log and database settings.';
}

function h(mixed $value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function answerText(mixed $value): string {
    $text = trim((string)($value ?? ''));
    return $text === '' ? 'No answer provided.' : $text;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Review | Event Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
          rel="stylesheet">
    <style>
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
        :root { --night:#21142d; --purple:#501C6C; --violet:#7D2CA8; --bright:#B95CEB; --rust:#9C420D; --gold:#D38D19; --pale:#FFC96D; }
        body { min-height:100vh; color:#f7f1fb; background:radial-gradient(ellipse at top right, #45205d 0, var(--night) 52%, #160e1e 100%); }
        .navbar { background:rgba(24,14,32,.94); border-bottom:1px solid rgba(255,201,109,.25); }
        .brand-mark { color:var(--pale); letter-spacing:.04em; font-weight:800; }
        .panel { background:rgba(42,27,55,.94); border:1px solid rgba(185,92,235,.25); border-radius:1rem; box-shadow:0 1rem 2.5rem #10081755; }
        .muted { color:#cbbbd8; }
        .eyebrow { color:var(--pale); text-transform:uppercase; letter-spacing:.12em; font-size:.75rem; font-weight:700; }
        .answer-card { height:100%; padding:1rem; border-radius:.8rem; background:#21162c; border:1px solid #634176; }
        .answer-text { white-space:pre-wrap; overflow-wrap:anywhere; color:#f1eaf7; }
        .form-control, .form-select { color:#fff; background:#170f20; border-color:#68447a; }
        .form-control:focus, .form-select:focus { color:#fff; background:#170f20; border-color:var(--bright); box-shadow:0 0 0 .2rem #B95CEB30; }
        .form-select option { color:#fff; background:#21142d; }
        .btn-gold { background:var(--gold); color:#201326; font-weight:800; border:0; }
        .btn-gold:hover { background:var(--pale); color:#201326; }
        .btn-outline-light { border-color:#9b83aa; }
        .pending-link { display:block; padding:.85rem 1rem; border-radius:.65rem; color:#f7f1fb; text-decoration:none; border:1px solid transparent; }
        .pending-link:hover, .pending-link.active { background:#45205d; border-color:var(--bright); color:#fff; }
        .badge-pending { background:#5b3b70; color:#ffe0a4; }
        label { color:#f7f1fb; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid px-lg-4">
        <a class="navbar-brand brand" href="home">
            <i class="bi bi-moon-stars-fill brand-icon"></i>
            AURORA
            <span class="brand-subtitle">CIVILIZATIONS</span>
        </a>
        <a class="navbar-brand brand-mark" href="appDash">✦ EVENT ADMIN</a>
        <div class="d-flex align-items-center gap-3">
            <span class="small muted d-none d-sm-inline">Signed in as <?= h($_SESSION['loggedinuser'] ?? 'Admin') ?></span>
            <a class="btn btn-sm btn-outline-light" href="appDash">Dashboard</a>
        </div>
    </div>
</nav>

<main class="container-fluid px-lg-4 py-4 py-lg-5">
    <div class="row g-4">
        <aside class="col-12 col-lg-3 col-xl-2">
    <div class="panel p-3">
        <div class="eyebrow mb-1">Review queue</div>
        <h2 class="h5 mb-3">Pending applications <span class="badge badge-pending rounded-pill"><?= count($pendingApps) ?></span></h2>
        <?php if (!$pendingApps): ?>
            <p class="muted small mb-0">No pending applications right now.</p>
        <?php else: ?>
            <div class="d-grid gap-1">
                <?php foreach ($pendingApps as $pending): ?>
                    <a class="pending-link <?= ($app && (int)$app['applicationID'] === (int)$pending['applicationID']) ? 'active' : '' ?>" href="?id=<?= (int)$pending['applicationID'] ?>">
                        <!-- Added custom wrapper helper styles for secure text wrapping boundary parsing -->
                        <div class="text-break-container" style="word-break: break-word; overflow-wrap: break-word; white-space: normal;">
                            <div class="fw-semibold"><?= h($pending['discUser']) ?></div>
                            <div class="small muted"><?= h($pending['eventName']) ?> · <?= h($pending['mcIGN']) ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</aside>


        <section class="col-12 col-lg-9 col-xl-10">
            <?php if ($notice !== ''): ?><div class="alert alert-success" role="status"><?= h($notice) ?></div><?php endif; ?>
            <?php if ($error !== ''): ?><div class="alert alert-danger" role="alert"><?= h($error) ?></div><?php endif; ?>

            <?php if (!$app): ?>
                <div class="panel p-4 p-lg-5 text-center">
                    <div class="display-5 mb-3" aria-hidden="true">✦</div>
                    <h1 class="h3">You're all caught up</h1>
                    <p class="muted mb-0">There are no applications with a pending status (accepted = 0).</p>
                </div>
            <?php else: ?>
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <div class="eyebrow">Application #<?= (int)$app['applicationID'] ?></div>
                        <h1 class="display-6 fw-bold mb-1"><?= h($app['eventName']) ?></h1>
                        <p class="muted mb-0">Review the answers and score each written response from 1 to 10.</p>
                    </div>
                    <span class="badge badge-pending fs-6 rounded-pill px-3 py-2">Pending review</span>
                </div>

                <form method="post" action="appDash" id="reviewForm">
                    <input type="hidden" name="applicationID" value="<?= (int)$app['applicationID'] ?>">
                    <div class="panel p-3 p-lg-4 mb-4">
                        <h2 class="h5 mb-3">Applicant details</h2>
                        <div class="row g-3">
                            <div class="col-12 col-md-4"><div class="eyebrow">Discord username</div><div class="fs-5"><?= h($app['discUser']) ?></div></div>
                            <div class="col-12 col-md-4"><div class="eyebrow">Minecraft IGN</div><div class="fs-5"><?= h($app['mcIGN']) ?></div></div>
                            <div class="col-12 col-md-4"><div class="eyebrow">Age group</div><div class="fs-5"><?= h($ageGroups[(int)($app['ageGrp'] ?? -1)] ?? 'Not specified') ?></div></div>
                            <div class="col-12 col-md-4"><div class="eyebrow">Working microphone</div><div><?= h($app['mic']) ?></div></div>
                            <div class="col-12 col-md-8"><div class="eyebrow">Streaming / recording channel</div><div><?= h(answerText($app['stream'] ?? '')) ?></div></div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <?php foreach ($scoreQuestions as $field => $question): ?>
                            <div class="col-12 col-xl-6">
                                <div class="answer-card">
                                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                        <h2 class="h6 mb-0"><?= h($question) ?></h2>
                                        <span class="badge text-bg-dark">Score / 10</span>
                                    </div>
                                    <p class="answer-text mb-3"><?= h(answerText($app[$field] ?? '')) ?></p>
                                    <label class="form-label" for="score-<?= h($field) ?>">Your score</label>
                                    <select class="form-select" id="score-<?= h($field) ?>" name="scores[<?= h($field) ?>]" required>
                                        <option value="">Choose a score…</option>
                                        <?php for ($score = 1; $score <= 10; $score++): ?>
                                            <option value="<?= $score ?>" <?= (isset($_POST['scores'][$field]) && (int)$_POST['scores'][$field] === $score) ? 'selected' : '' ?>><?= $score ?> / 10</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="panel p-3 p-lg-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                        <div><h2 class="h5 mb-1">Make a decision</h2><p class="muted small mb-0">Accept saves all eight scores. Deny changes the status without saving scores.</p></div>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-outline-danger px-4" type="submit" name="decision" value="deny" formnovalidate onclick="return confirm('Deny this application?');">Deny application</button>
                            <button class="btn btn-gold px-4" type="submit" name="decision" value="accept">✦ Accept application</button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </section>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>