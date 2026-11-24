<?php
// The one place admin pages load their JavaScript (included in <head> by header.php).
// Order matters: jQuery first, then Bootstrap and plugins, then katniss.js (sidebar, clock),
// then benaka-ui.js (theme toggle, toasts, confirm dialogs, inline saves).
// Pages must NOT load their own copy of jQuery: a second copy wipes the plugins loaded here.
?>
  <script src="../lib/jquery/jquery.js"></script>
  <script src="../lib/popper.js/popper.js"></script>
  <script src="../lib/bootstrap/bootstrap.js"></script>
  <script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
  <script src="../lib/moment/moment.js"></script>
  <script src="../lib/datatables/jquery.dataTables.js"></script>
  <script src="../lib/datatables-responsive/dataTables.responsive.js"></script>
  <script src="../lib/select2/js/select2.min.js"></script>
  <script src="../js/katniss.js"></script>
  <script src="../js/benaka-ui.js?v=2"></script>
