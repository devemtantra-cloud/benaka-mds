<?php require_once __DIR__ . '/auth.php'; require_login();
// RTO Work: jobs by inward date, with job status, remark and WhatsApp update per row.
include '../database.php';
require_once __DIR__ . '/ui.php';

$self = 'rto.php';
$is_all_branches = user_branch() === '';

// Inward-date range: from the form, else the last one used in this session, else the last 30 days.
$from = get_str('from_date');
$to = get_str('to_date');
if (isset($_GET['reset']) || !valid_date($from) || !valid_date($to)) {
    if (!isset($_GET['reset']) && isset($_SESSION['rto_range'])) {
        list($from, $to) = $_SESSION['rto_range'];
    } else {
        $from = date('Y-m-d', strtotime('-30 days'));
        $to = date('Y-m-d');
    }
}
if ($from > $to) { list($from, $to) = [$to, $from]; }
$_SESSION['rto_range'] = [$from, $to];

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

list($where, $types, $params) = client_where(['branch' => $branch, 'q' => $q, 'date_col' => 'd_inward', 'from' => $from, 'to' => $to]);
$total = (int) db_value($con, "SELECT COUNT(*) FROM `client` c $where", $types, $params);
$pg = paginate($total, 20, isset($_GET['page']) ? (int) $_GET['page'] : 1);
$rows = db_query($con, "SELECT c.* FROM `client` c $where ORDER BY c.`d_inward` DESC, c.`id` DESC LIMIT ? OFFSET ?",
    $types . 'ii', array_merge($params, [$pg['per'], $pg['offset']]));
$tpls = message_templates($con);
$focus = isset($_GET['focus']) ? (int) $_GET['focus'] : (isset($_GET['drs_id']) ? (int) $_GET['drs_id'] : 0);

$job_options = ['Data Entry', 'Fees', 'Inward', 'Process', 'Pending', 'Complete', 'Waiting for Customer', 'Work done'];

include "header.php";
?>
<div class="kt-mainpanel">
  <div class="kt-pagetitle"><h5>RTO work</h5></div>
  <div class="kt-pagebody">
    <div class="bk-card table-wrapper">
      <form class="bk-toolbar bk-no-print" method="get" action="<?php echo $self; ?>">
        <div class="bk-field">
          <label for="from_date">Inward from</label>
          <input type="date" id="from_date" name="from_date" class="form-control" value="<?php echo h($from); ?>">
        </div>
        <div class="bk-field">
          <label for="to_date">Inward to</label>
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
            <input type="search" id="search" name="search" class="form-control" value="<?php echo h($q); ?>" placeholder="Name, vehicle, phone, document, status…">
          </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="icon ion-funnel"></i> Apply</button>
        <a class="btn bk-btn-ghost" href="<?php echo $self; ?>?reset=1" title="Last 30 days, no search">Reset</a>
        <span class="bk-spacer"></span>
        <?php if (!$is_all_branches) { ?>
          <a class="btn bk-btn-ghost" href="jsonreminder.php"><i class="icon ion-social-google-outline"></i><span class="bk-btn-label"> Upload to Drive</span></a>
        <?php } ?>
        <button type="button" class="btn bk-btn-ghost" onclick="bkPrint()" title="Print this page"><i class="icon ion-printer"></i><span class="bk-btn-label"> Print</span></button>
      </form>

      <?php if (!$total) { ?>
        <div class="bk-empty"><i class="icon ion-ios-briefcase-outline"></i><b>No RTO work with an inward date in this range</b>Try a wider date range<?php echo $q !== '' ? ' or a different search' : ''; ?>.</div>
      <?php } else { ?>
        <div class="bk-table-wrap">
          <table class="table" id="datatable1">
            <thead>
              <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Vehicle / Doc no.</th>
                <th>Service</th>
                <th>Job status</th>
                <th>Case worker</th>
                <th>Inward</th>
                <th>Remark</th>
                <th class="bk-no-print">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php $n = $pg['offset']; while ($c = mysqli_fetch_assoc($rows)) {
                $n++;
                $b = isset($branches[(string) $c['branch_id']]) ? $branches[(string) $c['branch_id']] : null;
                $wa = rto_wa($c, $b, $tpls);
                $id = (int) $c['id'];
              ?>
                <tr<?php echo $id === $focus ? ' class="bk-row-focus"' : ''; ?>>
                  <td class="bk-num"><?php echo $n; ?></td>
                  <td>
                    <span class="bk-cell-main"><?php echo h($c['name']); ?></span>
                    <span class="bk-cell-sub bk-num"><?php echo h($c['w_number']); ?><?php echo $c['wt_status'] === 'no' ? ' · no WhatsApp' : ''; ?></span>
                    <span class="bk-cell-sub"><?php echo h($b ? trim($b['branch_name']) : ''); ?></span>
                  </td>
                  <td><span class="bk-num"><?php echo h($c['v_no']); ?></span><span class="bk-cell-sub bk-num"><?php echo h($c['doc_number']); ?></span></td>
                  <td><?php echo h($c['sr_type']); ?></td>
                  <td>
                    <select class="form-control" data-job aria-label="Job status" style="min-width:132px">
                      <?php $opts = $job_options; if ($c['Job_data'] !== '' && !in_array($c['Job_data'], $opts, true)) { array_unshift($opts, $c['Job_data']); } ?>
                      <?php if ($c['Job_data'] === '') { ?><option value="">— Select —</option><?php } ?>
                      <?php foreach ($opts as $o) { ?><option value="<?php echo h($o); ?>"<?php echo $o === $c['Job_data'] ? ' selected' : ''; ?>><?php echo h($o); ?></option><?php } ?>
                    </select>
                  </td>
                  <td><?php echo h($c['case_worker']); ?></td>
                  <td class="bk-num" style="white-space:nowrap"><?php echo fmt_date(substr((string) $c['d_inward'], 0, 10)); ?></td>
                  <td><input type="text" class="form-control" data-remark value="<?php echo h($c['remark']); ?>" aria-label="Remark" style="min-width:120px"></td>
                  <td class="bk-no-print">
                    <div class="bk-actions">
                      <button type="button" class="btn btn-primary bk-btn-sm" data-save-row="<?php echo $id; ?>" data-mode="rto">Save</button>
                      <?php if ($wa) { ?>
                        <a class="bk-wa" data-wa href="<?php echo h($wa); ?>" target="_blank" rel="noopener" title="Send WhatsApp update"><i class="fa fa-whatsapp"></i></a>
                      <?php } else { ?>
                        <a class="bk-wa is-disabled" data-wa aria-disabled="true" title="<?php echo $c['wt_status'] === 'no' ? 'Customer has no WhatsApp' : 'Pick a job status that has an RTO message, then Save'; ?>"><i class="fa fa-whatsapp"></i></a>
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
  (function () { var r = document.querySelector('.bk-row-focus'); if (r) r.scrollIntoView({ block: 'center' }); })();
</script>
</body>
</html>
