<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/ui.php';

$is_main_admin = is_admin();
$current = basename($_SERVER['SCRIPT_NAME']);

// Helper pages highlight the menu entry they belong to.
$menu_parent = [
  'index.php' => 'dash', 'admin_index.php' => 'dash',
  'crud.php' => 'cust', 'admin_crud.php' => 'cust', 'add_cust.php' => 'cust', 'Update_data.php' => 'cust',
  'rto.php' => 'rto', 'report.php' => 'report', 'pdf_date.php' => 'report', 'feedback_list.php' => 'feedback',
  'msg.php' => 'msg', 'add_msg.php' => 'msg', 'msg_edit.php' => 'msg',
  'rto_msg.php' => 'rtomsg', 'add_rto_msg.php' => 'rtomsg', 'rto_msg_edit.php' => 'rtomsg',
  'add_slider.php' => 'slider', 'add_service_photo.php' => 'slider',
  'add_details.php' => 'pages', 'update_point.php' => 'pages',
  'add_service.php' => 'services', 'font.php' => 'font',
  'add_branch.php' => 'branches', 'editadd.php' => 'address', 'update_addd.php' => 'address',
  'add_admin.php' => 'users', 'edit_admin.php' => 'users',
];
$titles = [
  'dash' => 'Dashboard', 'cust' => 'Customers', 'rto' => 'RTO Work', 'report' => 'Reports', 'feedback' => 'Customer feedback',
  'msg' => 'Reminder messages', 'rtomsg' => 'RTO messages', 'slider' => 'Home page slider', 'pages' => 'Service page content',
  'services' => 'Services', 'font' => 'Website font', 'branches' => 'Branches', 'address' => 'Branch addresses', 'users' => 'Users & logins',
];
$active = isset($menu_parent[$current]) ? $menu_parent[$current] : '';
$head_title = isset($page_title) ? $page_title : (isset($titles[$active]) ? $titles[$active] : 'Benaka MDS');

$menu = [
  ['Work', [
    ['dash', $is_main_admin ? 'admin_index.php' : 'index.php', 'ion-ios-speedometer-outline', 'Dashboard'],
    ['cust', $is_main_admin ? 'admin_crud.php' : 'crud.php', 'ion-ios-people-outline', 'Customers'],
    ['rto', 'rto.php', 'ion-ios-briefcase-outline', 'RTO Work'],
  ]],
];
if ($is_main_admin) {
  $menu[0][1][] = ['report', 'report.php', 'ion-ios-paper-outline', 'Reports'];
  $menu[0][1][] = ['feedback', 'feedback_list.php', 'ion-ios-chatboxes-outline', 'Feedback'];
  $menu[] = ['WhatsApp messages', [
    ['msg', 'msg.php', 'ion-social-whatsapp-outline', 'Reminder messages'],
    ['rtomsg', 'rto_msg.php', 'ion-ios-chatbubble-outline', 'RTO messages'],
  ]];
  $menu[] = ['Website', [
    ['slider', 'add_slider.php', 'ion-images', 'Home slider'],
    ['pages', 'add_details.php', 'ion-ios-list-outline', 'Service page content'],
    ['services', 'add_service.php', 'ion-ios-pricetags-outline', 'Services'],
    ['font', 'font.php', 'ion-social-tumblr-outline', 'Website font'],
  ]];
  $menu[] = ['Settings', [
    ['branches', 'add_branch.php', 'ion-ios-location-outline', 'Branches'],
    ['address', 'editadd.php', 'ion-ios-home-outline', 'Branch addresses'],
    ['users', 'add_admin.php', 'ion-ios-person-outline', 'Users & logins'],
  ]];
}
$uname = isset($_SESSION['uname']) ? $_SESSION['uname'] : '';
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="color-scheme" content="light dark">
  <meta name="csrf-token" content="<?php echo h(csrf_token()); ?>">
  <title><?php echo h($head_title); ?> · Benaka MDS</title>
  <script>
    // Apply the saved theme (or the device setting) before the page paints.
    (function () {
      var t = null;
      try { t = localStorage.getItem('bk-theme'); } catch (e) {}
      if (t !== 'light' && t !== 'dark') t = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', t);
    })();
  </script>
  <link rel="icon" href="../img/benaka_logo2.jpeg">
  <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet">
  <link href="../lib/Ionicons/css/ionicons.css" rel="stylesheet">
  <link href="../lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/katniss.css">
  <link rel="stylesheet" href="../css/benaka-ui.css?v=2">
<?php include __DIR__ . '/admin_scripts.php'; ?>
</head>

<body>
  <!-- ##### SIDEBAR LOGO ##### -->
  <div class="kt-sideleft-header">
    <div class="kt-logo">
      <a href="<?php echo $is_main_admin ? 'admin_index.php' : 'index.php'; ?>">
        <img src="../img/benaka_logo2.jpeg" alt="">
        <span>Benaka MDS<small><?php echo $is_main_admin ? 'Main admin' : 'Branch panel'; ?></small></span>
      </a>
    </div>
    <div id="ktDate" class="kt-date-today"></div>
  </div>

  <!-- ##### SIDEBAR MENU ##### -->
  <nav class="kt-sideleft" aria-label="Main menu">
    <?php foreach ($menu as $group) { ?>
      <label class="kt-sidebar-label"><?php echo h($group[0]); ?></label>
      <ul class="nav kt-sideleft-menu">
        <?php foreach ($group[1] as $item) { ?>
          <li class="nav-item">
            <a href="<?php echo h($item[1]); ?>" class="nav-link<?php echo $active === $item[0] ? ' active' : ''; ?>"<?php echo $active === $item[0] ? ' aria-current="page"' : ''; ?>>
              <i class="icon <?php echo h($item[2]); ?>"></i>
              <span><?php echo h($item[3]); ?></span>
            </a>
          </li>
        <?php } ?>
      </ul>
    <?php } ?>
  </nav>

  <!-- ##### TOP BAR ##### -->
  <header class="kt-headpanel">
    <div class="kt-headpanel-left">
      <a id="naviconMenu" href="" class="kt-navicon d-none d-lg-flex" aria-label="Toggle menu"><i class="icon ion-navicon-round"></i></a>
      <a id="naviconMenuMobile" href="" class="kt-navicon d-lg-none" aria-label="Open menu"><i class="icon ion-navicon-round"></i></a>
      <div class="bk-head-title"><?php echo h($head_title); ?></div>
    </div>
    <div class="kt-headpanel-right">
      <button type="button" class="bk-icon-btn bk-theme-btn" data-theme-toggle title="Switch light / dark theme" aria-label="Switch light or dark theme">
        <i class="icon ion-ios-moon-outline"></i><i class="icon ion-ios-sunny-outline"></i>
      </button>
      <div class="bk-user" title="<?php echo h($uname); ?>">
        <span class="bk-avatar"><?php echo h(function_exists('mb_substr') ? mb_substr($uname, 0, 2) : substr($uname, 0, 2)); ?></span>
        <span class="bk-user-meta"><b><?php echo h($uname); ?></b><span><?php echo $is_main_admin ? 'Main admin' : 'Branch user'; ?></span></span>
      </div>
      <a class="bk-logout" href="logout.php" title="Log out"><i class="icon ion-log-out"></i><span>Logout</span></a>
    </div>
  </header>

  <div class="bk-toasts" role="status" aria-live="polite"></div>
  <script type="application/json" id="bk-flash"><?php echo json_encode(flash_take(), JSON_HEX_TAG | JSON_HEX_AMP); ?></script>
