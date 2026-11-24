<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/ui.php';
// Already logged in: go straight to the right dashboard.
if (is_logged_in()) {
    auth_redirect(is_admin() ? 'admin_index.php' : 'index.php');
}
$error = isset($_GET['e']);
$out = isset($_GET['out']);
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="light dark">
  <title>Sign in · Benaka MDS</title>
  <script>
    (function () {
      var t = null;
      try { t = localStorage.getItem('bk-theme'); } catch (e) {}
      if (t !== 'light' && t !== 'dark') t = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', t);
    })();
  </script>
  <link rel="icon" href="../img/benaka_logo2.jpeg">
  <link href="../lib/Ionicons/css/ionicons.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/benaka-ui.css?v=2">
  <style>
    body { min-height: 100vh; margin: 0; display: grid; place-items: center; padding: 24px 16px;
      background: radial-gradient(1200px 600px at 10% -10%, var(--bk-primary-soft), transparent 60%), var(--bk-bg); }
    .bk-login { width: min(400px, 100%); background: var(--bk-surface); border: 1px solid var(--bk-border); border-radius: 16px; box-shadow: var(--bk-shadow-lg); padding: 32px 28px 26px; }
    .bk-login-brand { display: flex; flex-direction: column; align-items: center; gap: 10px; margin-bottom: 22px; text-align: center; }
    .bk-login-brand img { width: 72px; height: 72px; border-radius: 50%; background: #fff; object-fit: cover; box-shadow: 0 0 0 4px var(--bk-surface-3); }
    .bk-login-brand h1 { font-size: 20px; font-weight: 700; margin: 0; color: var(--bk-text); }
    .bk-login-brand p { margin: 0; color: var(--bk-muted); font-size: 13.5px; }
    .bk-login label { display: block; font-size: 12.5px; font-weight: 600; color: var(--bk-text-2); margin: 14px 0 6px; }
    .bk-login input { width: 100%; height: 44px; padding: 0 14px; box-sizing: border-box; font-size: 15px; }
    .bk-pass { position: relative; }
    .bk-pass button { position: absolute; right: 6px; top: 50%; transform: translateY(-50%); background: none; border: 0; color: var(--bk-muted); font-size: 20px; width: 34px; height: 34px; cursor: pointer; border-radius: 6px; }
    .bk-pass button:hover { color: var(--bk-text); background: var(--bk-surface-3); }
    .bk-login .btn { width: 100%; height: 46px; margin-top: 22px; font-size: 15px; border: 0; cursor: pointer; }
    .bk-alert { display: flex; gap: 8px; align-items: center; padding: 10px 12px; border-radius: 8px; font-size: 13.5px; font-weight: 500; margin-bottom: 4px; }
    .bk-alert.is-error { background: var(--bk-danger-soft); color: var(--bk-danger); }
    .bk-alert.is-info { background: var(--bk-primary-soft); color: var(--bk-primary); }
    .bk-login-foot { display: flex; justify-content: space-between; align-items: center; margin-top: 18px; color: var(--bk-muted); font-size: 12.5px; }
    .bk-login-foot a { color: var(--bk-muted); }
  </style>
</head>
<body>
  <form class="bk-login" action="main.php" method="post">
    <div class="bk-login-brand">
      <img src="../img/benaka_logo2.jpeg" alt="Benaka Motor Driving School">
      <h1>Benaka MDS</h1>
      <p>Sign in to the admin &amp; branch panel</p>
    </div>
    <?php if ($error) { ?><div class="bk-alert is-error" role="alert"><i class="icon ion-alert-circled"></i> Wrong username or password. Please try again.</div><?php } ?>
    <?php if ($out) { ?><div class="bk-alert is-info" role="status"><i class="icon ion-checkmark-circled"></i> You have been logged out.</div><?php } ?>

    <label for="uname">Username</label>
    <input type="text" id="uname" name="uname" required autocomplete="username" autofocus value="<?php echo h(isset($_GET['u']) ? $_GET['u'] : ''); ?>">

    <label for="psw">Password</label>
    <div class="bk-pass">
      <input type="password" id="psw" name="psw" required autocomplete="current-password">
      <button type="button" aria-label="Show password" onclick="var p=document.getElementById('psw');p.type=p.type==='password'?'text':'password';this.firstElementChild.className='icon '+(p.type==='password'?'ion-eye':'ion-eye-disabled');"><i class="icon ion-eye"></i></button>
    </div>

    <button type="submit" name="submit" value="1" class="btn btn-primary">Sign in</button>

    <div class="bk-login-foot">
      <a href="/">&larr; benakamds.in</a>
      <button type="button" class="bk-icon-btn bk-theme-btn" title="Switch light / dark theme" aria-label="Switch light or dark theme"
        onclick="var r=document.documentElement,n=r.getAttribute('data-theme')==='dark'?'light':'dark';r.setAttribute('data-theme',n);try{localStorage.setItem('bk-theme',n)}catch(e){}">
        <i class="icon ion-ios-moon-outline"></i><i class="icon ion-ios-sunny-outline"></i>
      </button>
    </div>
  </form>
  <script>
    // Show a spinner while signing in.
    document.querySelector('.bk-login').addEventListener('submit', function () { this.querySelector('[type=submit]').classList.add('is-loading'); });
  </script>
</body>
</html>
