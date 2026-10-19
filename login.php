<?php
declare(strict_types=1);
session_start();
$dbHost = 'sql202.infinityfree.com';
$dbName = 'if0_43187131_AuroraEventsMain';
$dbUser = 'if0_43187131';
$dbPass = 'hHimykmJ2nY';

$errorMessage = '';
$username = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $errorMessage = 'Enter both your username and password.';
    } else {
        try {
            $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            // Change these field names if your table schema differs.
            $stmt = $pdo->prepare(
                'SELECT accountID, username, `passHash`, `isAdmin`
                 FROM tblAccounts WHERE username = :username LIMIT 1'
            );
            $stmt->execute(['username' => $username]);
            $account = $stmt->fetch();

            if ($account &&
                password_verify($password, (string)$account['passHash']) &&
                (int)$account['isAdmin'] === 1) {
                session_regenerate_id(true);
                $_SESSION['account_ID'] = (int)$account['accountID'];
                $_SESSION['account_username'] = (string)$account['username'];
                $_SESSION['isAdmin'] = true;

                header('Location: appDash');
                exit;
            }

            $errorMessage = 'The username or password is incorrect, or this account does not have admin access.';
        } catch (PDOException $e) {
            error_log('Admin login database error: ' . $e->getMessage());
            $errorMessage = 'Login is temporarily unavailable. Please try again later.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login | Aurora Civilizations</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    :root{--deep-purple:#501C6C;--purple:#7D2CA8;--bright-purple:#B95CEB;--burnt-orange:#9C420D;--gold:#D38D19;--pale-gold:#FFC96D}
    *{box-sizing:border-box}
    body{min-height:100vh;display:flex;flex-direction:column;color:#fff8ff;background:radial-gradient(ellipse at 15% 10%,rgba(125,44,168,.34),transparent 38%),radial-gradient(ellipse at 90% 85%,rgba(156,66,13,.20),transparent 32%),linear-gradient(145deg,#140b1b,#24112f 55%,#160e1c);font-family:system-ui,-apple-system,"Segoe UI",sans-serif}
    .navbar{background:rgba(20,10,28,.92);border-bottom:1px solid rgba(255,201,109,.25);backdrop-filter:blur(12px)}
    .navbar-brand{color:#fff!important;font-weight:850;letter-spacing:.04em}.brand-mark{color:var(--pale-gold)}.nav-link{color:#e8d9ef!important}
    main{flex:1;display:grid;place-items:center;padding:3rem 1rem}
    .login-card{width:100%;max-width:470px;padding:clamp(1.5rem,5vw,2.5rem);border-radius:1.35rem;background:rgba(39,20,51,.93);border:1px solid rgba(185,92,235,.32);box-shadow:0 24px 80px rgba(0,0,0,.34)}
    .admin-icon{width:4.4rem;height:4.4rem;display:grid;place-items:center;margin:0 auto 1.2rem;border-radius:1.25rem;color:var(--pale-gold);font-size:1.9rem;background:linear-gradient(145deg,rgba(125,44,168,.42),rgba(211,141,25,.12));border:1px solid rgba(255,201,109,.3)}
    .eyebrow{color:var(--pale-gold);text-transform:uppercase;letter-spacing:.17em;font-size:.72rem;font-weight:850}h1{font-weight:900;letter-spacing:-.035em}.intro{color:#cdbfd5}
    .form-label{color:#f4eaf8;font-weight:700}.form-control{color:#fff;background:#21132b;border:1px solid #594263;border-radius:.7rem;padding:.85rem .95rem}.form-control::placeholder{color:#8e7c99}.form-control:focus{color:#fff;background:#21132b;border-color:var(--bright-purple);box-shadow:0 0 0 .2rem rgba(185,92,235,.18)}
    .input-group .btn{color:#d9c7e3;background:#21132b;border:1px solid #594263;border-left:0}
    .btn-login{color:#25142c;background:var(--pale-gold);border:0;border-radius:.7rem;padding:.85rem 1rem;font-weight:850}.btn-login:hover{color:#1b1022;background:var(--gold)}
    .security-note{color:#ad9bb8;font-size:.82rem;border-top:1px solid rgba(255,255,255,.09);padding-top:1.1rem;margin-top:1.4rem}.alert{border-radius:.75rem}
    footer{color:#9b8ba6;text-align:center;padding:0 1rem 1.5rem;font-size:.83rem}
    h1, h2, h3, .brand {font-family: "Cinzel", serif;}
.brand {color: var(--gold-light) !important;letter-spacing: 3px;font-size: 1.1rem;line-height: 1.1;}
.brand-icon {color: var(--purple-light); margin-right: 8px; font-size: 1.5rem;}
.brand-subtitle {display: block;margin-left: 37px;margin-top: 5px;font: 0.55rem "Inter", sans-serif;letter-spacing: 3px;color: var(--muted);}
.btn-outline-celestial {color: var(--text);border: 1px solid var(--purple-light);background: rgba(80, 28, 108, 0.2);}
.btn-outline-celestial:hover {color: white;background: var(--purple-dark);border-color: var(--purple-light);}
.btn-gold {color: #211128;background: linear-gradient(110deg, var(--gold-light), var(--gold));border: 1px solid var(--gold-light);}
.btn-gold:hover,.btn-gold:focus {color: #211128;background: var(--gold-light);border-color: var(--gold-light);box-shadow: 0 5px 25px rgba(211, 141, 25, 0.25);}
.site-navbar {min-height: 76px;background: rgba(16, 9, 29, 0.96);border-bottom: 1px solid var(--border);backdrop-filter: blur(16px);-webkit-backdrop-filter: blur(16px);}
@media (max-width: 575.98px) {
    .site-navbar {
        min-height: 70px;
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
        <a class="nav-link ms-auto" href="home"><i class="bi bi-arrow-left me-1"></i> Back to site</a>
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<main>
  <section class="login-card" aria-labelledby="loginTitle">
    <div class="admin-icon"><i class="bi bi-shield-lock-fill"></i></div>
    <div class="text-center mb-4">
      <div class="eyebrow">Restricted access</div>
      <h1 id="loginTitle" class="h2 mt-2 mb-2">Admin login</h1>
      <p class="intro mb-0">Sign in with an authorised administrator account to manage the event.</p>
    </div>
    <?php if ($errorMessage !== ''): ?>
      <div class="alert alert-danger" role="alert"><i class="bi bi-exclamation-circle-fill me-2"></i><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <form method="post" action="login">
      <div class="mb-3">
        <label class="form-label" for="username">Username</label>
        <input class="form-control" type="text" id="username" name="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="username" maxlength="100" required autofocus>
      </div>
      <div class="mb-4">
        <label class="form-label" for="password">Password</label>
        <div class="input-group">
          <input class="form-control" type="password" id="password" name="password" autocomplete="current-password" required>
          <button class="btn" type="button" id="togglePassword" aria-label="Show password" aria-pressed="false"><i class="bi bi-eye" id="passwordIcon"></i></button>
        </div>
      </div>
      <button class="btn btn-login w-100" type="submit">Sign in securely <i class="bi bi-arrow-right ms-1"></i></button>
    </form>
    <div class="security-note"><i class="bi bi-shield-check me-1" style="color:var(--pale-gold)"></i>Administrator access only. Credentials are checked on the server.</div>
  </section>
</main>
<footer><i class="bi bi-stars me-1"></i> Aurora Civilizations</footer>
<script>
const passwordInput=document.getElementById('password');
const togglePassword=document.getElementById('togglePassword');
const passwordIcon=document.getElementById('passwordIcon');
togglePassword.addEventListener('click',()=>{
  const show=passwordInput.type==='password';
  passwordInput.type=show?'text':'password';
  passwordIcon.className=show?'bi bi-eye-slash':'bi bi-eye';
  togglePassword.setAttribute('aria-label',show?'Hide password':'Show password');
  togglePassword.setAttribute('aria-pressed',String(show));
});
</script>
</body>
</html>