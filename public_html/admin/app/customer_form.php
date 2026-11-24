<?php
// Shared customer form for add_cust.php (new) and Update_data.php (edit).
// Expects: $con, $mode ('add'|'edit'), $c (customer row or defaults), $return (list page to go back to).
$services = [];
$res = mysqli_query($con, "SELECT `service_name` FROM `service` ORDER BY `service_name`");
while ($r = mysqli_fetch_row($res)) {
    $services[] = $r[0];
}
if ($c['sr_type'] !== '' && !in_array($c['sr_type'], $services, true)) {
    array_unshift($services, $c['sr_type']);
}
$branches = branches_map($con);
$phone_local = $mode === 'edit' ? $c['w_number'] : preg_replace('/^91(?=\d{10}$)/', '', $c['w_number']);
?>
<style>
  .bk-form { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px 20px; }
  .bk-form .bk-field { min-width: 0; }
  .bk-form .is-full { grid-column: 1 / -1; }
  .bk-form .bk-hint { font-size: 12px; color: var(--bk-muted); }
  .bk-field label.bk-check { display: inline-flex; align-items: center; gap: 8px; margin-top: 8px; color: var(--bk-text-2); font-size: 13px; font-weight: 500; text-transform: none; letter-spacing: 0; cursor: pointer; }
  .bk-check input { width: 16px; height: 16px; min-height: 0; accent-color: var(--bk-primary); }
  .bk-form-actions { display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid var(--bk-border); margin-top: 22px; padding-top: 18px; }
  .bk-req { color: var(--bk-danger); }
  @media (max-width: 767px) { .bk-form { grid-template-columns: 1fr; } .bk-form-actions .btn { flex: 1; } }
</style>
<div class="kt-mainpanel">
  <div class="kt-pagetitle"><h5><?php echo $mode === 'edit' ? 'Edit customer' : 'Add customer'; ?></h5></div>
  <div class="kt-pagebody">
    <form class="bk-card table-wrapper" style="max-width:980px" action="<?php echo $mode === 'edit' ? 'update_client.php' : 'ins_client.php'; ?>" method="post" autocomplete="off">
      <?php if ($mode === 'edit') { ?>
        <input type="hidden" name="id" value="<?php echo (int) $c['id']; ?>">
        <input type="hidden" name="eid" value="<?php echo h($c['branch_id']); ?>">
      <?php } ?>
      <input type="hidden" name="return" value="<?php echo h($return); ?>">

      <div class="bk-form">
        <div class="bk-field">
          <label for="name">Customer name <span class="bk-req">*</span></label>
          <input type="text" id="name" name="name" class="form-control" value="<?php echo h($c['name']); ?>" required maxlength="100" placeholder="Full name">
        </div>
        <div class="bk-field">
          <label for="v_no">Vehicle number</label>
          <input type="text" id="v_no" name="v_no" class="form-control" value="<?php echo h($c['v_no']); ?>" maxlength="30" placeholder="e.g. KA12AB1234" style="text-transform:uppercase">
        </div>

        <div class="bk-field">
          <label for="w_number">WhatsApp number <span class="bk-req">*</span></label>
          <?php if ($mode === 'edit') { ?>
            <input type="tel" id="w_number" name="w_number" class="form-control" value="<?php echo h($phone_local); ?>" required maxlength="15" placeholder="91XXXXXXXXXX">
            <span class="bk-hint">Include the 91 country code.</span>
          <?php } else { ?>
            <input type="tel" id="w_number" name="w_number" class="form-control" value="<?php echo h($phone_local); ?>" required pattern="[0-9]{10}" maxlength="10" inputmode="numeric" placeholder="10-digit mobile number" title="10-digit mobile number">
            <span class="bk-hint">10 digits — 91 is added automatically.</span>
          <?php } ?>
          <label class="bk-check"><input type="checkbox" name="no_whatsapp" value="no"<?php echo $c['wt_status'] === 'no' ? ' checked' : ''; ?>> Customer has no WhatsApp</label>
          <?php if ($mode === 'add') { ?><a id="welcome-link" class="bk-hint" target="_blank" rel="noopener" style="display:none"><i class="fa fa-whatsapp"></i> Send welcome message</a><?php } ?>
        </div>
        <div class="bk-field">
          <label for="sr_type">Service <span class="bk-req">*</span></label>
          <select id="sr_type" name="sr_type" class="form-control" required>
            <?php if ($c['sr_type'] === '') { ?><option value="">— Select service —</option><?php } ?>
            <?php foreach ($services as $s) { ?>
              <option value="<?php echo h($s); ?>"<?php echo $s === $c['sr_type'] ? ' selected' : ''; ?>><?php echo h($s); ?></option>
            <?php } ?>
          </select>
        </div>

        <?php if (user_branch() === '') { ?>
          <div class="bk-field">
            <label for="branch_id">Branch <span class="bk-req">*</span></label>
            <select id="branch_id" name="branch_id" class="form-control" required>
              <?php if ($c['branch_id'] === '') { ?><option value="">— Select branch —</option><?php } ?>
              <?php foreach ($branches as $id => $b) { ?>
                <option value="<?php echo h($id); ?>"<?php echo (string) $id === (string) $c['branch_id'] ? ' selected' : ''; ?>><?php echo h(trim($b['branch_name'])); ?></option>
              <?php } ?>
            </select>
          </div>
        <?php } ?>
        <div class="bk-field">
          <label for="doc_number">Document number <span class="bk-req">*</span></label>
          <input type="text" id="doc_number" name="doc_number" class="form-control" value="<?php echo h($c['doc_number']); ?>" required maxlength="60" placeholder="DL / RC / policy number">
        </div>

        <div class="bk-field">
          <label for="exp_date">Expiry date</label>
          <input type="date" id="exp_date" name="exp_date" class="form-control" value="<?php echo h($c['exp_date'] === '0000-00-00' ? '' : $c['exp_date']); ?>">
          <span class="bk-hint">Reminders are based on this date.</span>
        </div>
        <div class="bk-field">
          <label for="d_inward">Inward date</label>
          <input type="date" id="d_inward" name="d_inward" class="form-control" value="<?php echo h(substr((string) $c['d_inward'], 0, 10)); ?>">
          <span class="bk-hint">Set this to show the job in RTO Work.</span>
        </div>
        <div class="bk-field">
          <label for="case_wk">Case worker</label>
          <input type="text" id="case_wk" name="case_wk" class="form-control" value="<?php echo h($c['case_worker']); ?>" maxlength="60" placeholder="Staff handling this job">
        </div>
      </div>

      <div class="bk-form-actions">
        <a class="btn bk-btn-ghost" href="<?php echo h($return); ?>">Cancel</a>
        <button type="submit" name="add" value="1" class="btn btn-primary"><i class="icon ion-checkmark"></i> <?php echo $mode === 'edit' ? 'Save changes' : 'Add customer'; ?></button>
      </div>
    </form>
  </div>
</div>
<?php if ($mode === 'add') { ?>
<script>
  // Offer the welcome WhatsApp message once a valid number is typed (as before).
  (function () {
    var input = document.getElementById('w_number'), link = document.getElementById('welcome-link');
    var msg = 'Welcome to Benaka Motor Driving School,\n\nThank you for choosing us, to know more about us logon to-https://benakamds.in/';
    input.addEventListener('input', function () {
      var ok = /^\d{10}$/.test(input.value);
      link.style.display = ok ? 'inline-flex' : 'none';
      if (ok) link.href = 'https://api.whatsapp.com/send?phone=91' + input.value + '&text=' + encodeURIComponent(msg);
    });
  })();
</script>
<?php } ?>
</body>
</html>
