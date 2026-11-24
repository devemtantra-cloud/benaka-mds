<?php
// Customer list. Shared by crud.php (branch users) and admin_crud.php (main admin).
// The caller has already done require_login()/require_admin().
include '../database.php';
require_once __DIR__ . '/ui.php';

$self = basename($_SERVER['SCRIPT_NAME']);
$is_all_branches = user_branch() === '';

$branches = branches_map($con);
$q = get_str('search');
$branch = $is_all_branches ? get_str('branch') : user_branch();
if ($is_all_branches && $branch === '') {
    $branch = branch_from_search($branches, $q);
}
if ($branch !== '' && !isset($branches[$branch])) {
    $branch = '';
}
$filters = ['branch' => $is_all_branches ? $branch : '', 'search' => $q];

list($where, $types, $params) = client_where(['branch' => $branch, 'q' => $q]);
$total = (int) db_value($con, "SELECT COUNT(*) FROM `client` c $where", $types, $params);
$pg = paginate($total, 20, isset($_GET['page']) ? (int) $_GET['page'] : 1);
// Newest first, so a customer you just added is at the top.
$rows = db_query($con, "SELECT c.* FROM `client` c $where ORDER BY c.`id` DESC LIMIT ? OFFSET ?",
    $types . 'ii', array_merge($params, [$pg['per'], $pg['offset']]));

include "header.php";
?>
<div class="kt-mainpanel">
  <div class="kt-pagetitle"><h5>Customers</h5></div>
  <div class="kt-pagebody">
    <div class="bk-card table-wrapper">
      <form class="bk-toolbar" method="get" action="<?php echo h($self); ?>">
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
            <input type="search" id="search" name="search" class="form-control" value="<?php echo h($q); ?>" placeholder="Name, vehicle, phone, document…" autofocus>
          </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="icon ion-ios-search-strong"></i> Search</button>
        <?php if ($q !== '' || ($is_all_branches && $branch !== '')) { ?>
          <a class="btn bk-btn-ghost" href="<?php echo h($self); ?>">Clear</a>
        <?php } ?>
        <span class="bk-spacer"></span>
        <a class="btn btn-success" href="add_cust.php"><i class="icon ion-plus"></i> Add customer</a>
      </form>

      <?php if (!$total) { ?>
        <div class="bk-empty"><i class="icon ion-ios-people-outline"></i><b><?php echo $q !== '' ? 'No customers match “' . h($q) . '”' : 'No customers yet'; ?></b><?php echo $q !== '' ? 'Check the spelling or search by vehicle or phone number.' : 'Add your first customer to get started.'; ?></div>
      <?php } else { ?>
        <div class="bk-table-wrap">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Contact</th>
                <th>Service</th>
                <th>Doc no.</th>
                <th>Expiry</th>
                <th>WhatsApp</th>
                <th>Branch</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php $n = $pg['offset']; while ($c = mysqli_fetch_assoc($rows)) {
                $n++;
                $b = isset($branches[(string) $c['branch_id']]) ? $branches[(string) $c['branch_id']] : null;
                $id = (int) $c['id'];
              ?>
                <tr>
                  <td class="bk-num"><?php echo $n; ?></td>
                  <td><span class="bk-cell-main"><?php echo h($c['name']); ?></span><span class="bk-cell-sub bk-num"><?php echo h($c['v_no']); ?></span></td>
                  <td class="bk-num"><?php echo h($c['w_number']); ?></td>
                  <td><?php echo h($c['sr_type']); ?></td>
                  <td class="bk-num"><?php echo h($c['doc_number']); ?></td>
                  <td class="bk-num" style="white-space:nowrap"><?php echo fmt_date($c['exp_date']); ?> <?php echo expiry_badge($c['exp_date']); ?></td>
                  <td><?php echo $c['wt_status'] === 'no' ? '<span class="bk-badge is-muted">No</span>' : '<span class="bk-badge is-success">Yes</span>'; ?></td>
                  <td><?php echo h($b ? trim($b['branch_name']) : ''); ?></td>
                  <td>
                    <div class="bk-actions">
                      <a class="btn bk-btn-ghost bk-btn-sm" href="Update_data.php?id=<?php echo $id; ?>"><i class="icon ion-edit"></i> Edit</a>
                      <a class="btn btn-danger bk-btn-sm" href="del_data.php?id=<?php echo $id; ?>" data-confirm="Delete <?php echo h($c['name']); ?>?" data-confirm-text="This customer and their reminder will be removed permanently." title="Delete"><i class="icon ion-trash-a"></i></a>
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
</body>
</html>
