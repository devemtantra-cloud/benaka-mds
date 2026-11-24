<?php
// Reminder dashboard (documents expiring in a date range). Shared by
// index.php (branch users) and admin_index.php (main admin).
// The caller has already done require_login()/require_admin().
include '../database.php';
require_once __DIR__ . '/ui.php';

$self = basename($_SERVER['SCRIPT_NAME']);
$is_all_branches = user_branch() === '';

// Old links saved a row with ?uid=&job_data=&remark=; still honour them once, then clean the URL.
if (isset($_GET['uid']) && (isset($_GET['job_data']) || isset($_GET['remark']))) {
    $ok = save_client_job($con, $_GET['uid'], get_str('job_data'), get_str('remark'));
    flash($ok ? 'success' : 'error', $ok ? 'Saved' : 'Not saved');
    $keep = $_GET;
    unset($keep['uid'], $keep['job_data'], $keep['remark'], $keep['num']);
    $keep['focus'] = (int) $_GET['uid'];
    auth_redirect($self . qs($keep));
}

// ----- Date range: from the form, else the range this branch used last time -----
if ($is_all_branches) {
    $saved = mysqli_fetch_row(mysqli_query($con, "SELECT `fm`, `tod` FROM `datef` WHERE `id` = 1"));
} else {
    $saved = mysqli_fetch_row(db_query($con, "SELECT `fm`, `too` FROM `login` WHERE `branch` = ? LIMIT 1", 's', [user_branch()]));
}
$from = get_str('from_date');
$to = get_str('to_date');
if (isset($_GET['reset'])) {
    $from = date('Y-m-d');
    $to = date('Y-m-d', strtotime('+30 days'));
}
if (valid_date($from) && valid_date($to)) {
    if ($from > $to) { list($from, $to) = [$to, $from]; }
    // Remember the range the user chose.
    if (!$saved || $saved[0] !== $from || $saved[1] !== $to) {
        if ($is_all_branches) {
            db_query($con, "UPDATE `datef` SET `fm` = ?, `tod` = ? WHERE `id` = 1", 'ss', [$from, $to]);
        } else {
            db_query($con, "UPDATE `login` SET `fm` = ?, `too` = ? WHERE `branch` = ?", 'sss', [$from, $to, user_branch()]);
        }
    }
} else {
    $from = $saved && valid_date($saved[0]) ? $saved[0] : date('Y-m-d');
    $to = $saved && valid_date($saved[1]) ? $saved[1] : date('Y-m-d', strtotime('+30 days'));
}

// ----- Filters -----
$branches = branches_map($con);
$q = isset($_GET['reset']) ? '' : get_str('search');
$branch = $is_all_branches ? get_str('branch') : user_branch();
if ($is_all_branches && $branch === '') {
    $branch = branch_from_search($branches, $q);
}
if ($branch !== '' && !isset($branches[$branch])) {
    $branch = '';
}
$filters = ['from_date' => $from, 'to_date' => $to, 'branch' => $is_all_branches ? $branch : '', 'search' => $q];

list($where, $types, $params) = client_where(['branch' => $branch, 'q' => $q, 'date_col' => 'exp_date', 'from' => $from, 'to' => $to]);
$total = (int) db_value($con, "SELECT COUNT(*) FROM `client` c $where", $types, $params);
$pg = paginate($total, 20, isset($_GET['page']) ? (int) $_GET['page'] : 1);
$rows = db_query($con, "SELECT c.* FROM `client` c $where ORDER BY c.`exp_date` ASC, c.`id` ASC LIMIT ? OFFSET ?",
    $types . 'ii', array_merge($params, [$pg['per'], $pg['offset']]));
$tpls = message_templates($con);
$focus = isset($_GET['focus']) ? (int) $_GET['focus'] : (isset($_GET['drs_id']) ? (int) $_GET['drs_id'] : 0);

$job_options = ['contacted', 'Not Responded', 'Rejected', 'Waiting for document', 'Process', 'Complete'];

include "header.php";
?>
<div class="kt-mainpanel">
  <div class="kt-pagetitle"><h5>Renewal reminders</h5></div>
  <div class="kt-pagebody">
    <div class="bk-card table-wrapper">
      <form class="bk-toolbar bk-no-print" method="get" action="<?php echo h($self); ?>">
        <div class="bk-field">
          <label for="from_date">Expiry from</label>
          <input type="date" id="from_date" name="from_date" class="form-control" value="<?php echo h($from); ?>">
        </div>
        <div class="bk-field">
          <label for="to_date">Expiry to</label>
          <input type="date" id="to_date" name="to_date" class="form-control" value="<?php echo h($to); ?>">
        </div>
        <?php if ($is_all_branches) { ?>
          <div class="bk-field">
            <label for="branch">Branch</label>
            <select id="branch" name="branch" class="form-control">
              <option value="">All branches</option>
              <?php foreach ($branches as $id => $b) { ?>
                <option value="<?php echo h($id); ?>"<?php echo (string) $id === $branch ? ' selected' : ''; ?>><?php echo h(trim($b['branch_name'])); ?></option>
              <?php } ?>
            </select>
          </div>
        <?php } ?>
        <div class="bk-field is-grow">
          <label for="search">Search</label>
          <div class="bk-search">
            <i class="icon ion-ios-search"></i>
            <input type="search" id="search" name="search" class="form-control" value="<?php echo h($q); ?>" placeholder="Name, vehicle, phone, document, remark…">
          </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="icon ion-funnel"></i> Apply</button>
        <a class="btn bk-btn-ghost" href="<?php echo h($self); ?>?reset=1" title="Next 30 days, no search">Reset</a>
        <span class="bk-spacer"></span>
        <a class="btn bk-btn-ghost" href="jsonreminder.php" title="Send this branch's reminder list to Google Drive"><i class="icon ion-social-google-outline"></i><span class="bk-btn-label"> Upload to Drive</span></a>
        <button type="button" class="btn bk-btn-ghost" onclick="bkPrint()" title="Print this page"><i class="icon ion-printer"></i><span class="bk-btn-label"> Print</span></button>
      </form>

      <?php if (!$total) { ?>
        <div class="bk-empty"><i class="icon ion-ios-calendar-outline"></i><b>No documents expire in this range</b>Try a wider date range<?php echo $q !== '' ? ' or a different search' : ''; ?>.</div>
      <?php } else { ?>
        <div class="bk-table-wrap">
          <table class="table" id="datatable1">
            <thead>
              <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Vehicle / Contact</th>
                <th>Service</th>
                <th>Job status / Doc no.</th>
                <th>Expiry</th>
                <th>Remark</th>
                <th class="bk-no-print">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php $n = $pg['offset']; while ($c = mysqli_fetch_assoc($rows)) {
                $n++;
                $b = isset($branches[(string) $c['branch_id']]) ? $branches[(string) $c['branch_id']] : null;
                $wa = dashboard_wa($c, $b, $tpls);
                $id = (int) $c['id'];
              ?>
                <tr<?php echo $id === $focus ? ' class="bk-row-focus"' : ''; ?>>
                  <td class="bk-num"><?php echo $n; ?></td>
                  <td><span class="bk-cell-main"><?php echo h($c['name']); ?></span><span class="bk-cell-sub"><?php echo h($b ? trim($b['branch_name']) : ''); ?></span></td>
                  <td><span class="bk-num"><?php echo h($c['v_no']); ?></span><span class="bk-cell-sub bk-num"><?php echo h($c['w_number']); ?></span></td>
                  <td><?php echo h($c['sr_type']); ?></td>
                  <td>
                    <select class="form-control" data-job aria-label="Job status">
                      <?php $opts = $job_options; if ($c['Job_data'] !== '' && !in_array($c['Job_data'], $opts, true)) { array_unshift($opts, $c['Job_data']); } ?>
                      <?php if ($c['Job_data'] === '') { ?><option value="">— Select —</option><?php } ?>
                      <?php foreach ($opts as $o) { ?><option value="<?php echo h($o); ?>"<?php echo $o === $c['Job_data'] ? ' selected' : ''; ?>><?php echo h($o); ?></option><?php } ?>
                    </select>
                    <span class="bk-cell-sub"><?php echo h($c['doc_number']); ?></span>
                  </td>
                  <td class="bk-num" style="white-space:nowrap"><?php echo fmt_date($c['exp_date']); ?><br><?php echo expiry_badge($c['exp_date']); ?></td>
                  <td><input type="text" class="form-control" data-remark value="<?php echo h($c['remark']); ?>" aria-label="Remark" style="min-width:160px"></td>
                  <td class="bk-no-print">
                    <div class="bk-actions">
                      <button type="button" class="btn btn-primary bk-btn-sm" data-save-row="<?php echo $id; ?>" data-mode="dashboard">Save</button>
                      <?php if ($wa) { ?>
                        <a class="bk-wa" data-wa href="<?php echo h($wa); ?>" target="_blank" rel="noopener" title="Send WhatsApp reminder"><i class="fa fa-whatsapp"></i></a>
                      <?php } else { ?>
                        <a class="bk-wa is-disabled" data-wa aria-disabled="true" title="<?php echo $c['wt_status'] === 'no' ? 'Customer has no WhatsApp' : 'No reminder message set up for this service'; ?>"><i class="fa fa-whatsapp"></i></a>
                      <?php } ?>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      <?php } ?>
      <?php echo render_pager($pg, $self, $filters); ?>
    </div>
  </div>
</div>
<script>
  // Bring the row that was just updated into view.
  (function () { var r = document.querySelector('.bk-row-focus'); if (r) r.scrollIntoView({ block: 'center' }); })();
</script>
</body>
</html>
