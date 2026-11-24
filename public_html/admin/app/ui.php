<?php
// Small shared helpers for the admin/branch panel: escaping, flash messages,
// pagination, dates and WhatsApp links. Requires auth.php (session).

require_once __DIR__ . '/auth.php';

function h($s)
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

// ---------- Flash messages (shown as a toast on the next page) ----------

function flash($type, $msg)
{
    $_SESSION['flash'][] = ['type' => $type, 'msg' => $msg];
}

function flash_take()
{
    $f = isset($_SESSION['flash']) ? $_SESSION['flash'] : [];
    unset($_SESSION['flash']);
    return $f;
}

// Finish a save/delete: remember the message and go back.
function done($msg, $back, $type = 'success')
{
    flash($type, $msg);
    auth_redirect($back);
}

// The list page the user came from (keeps their search/page), or a default.
function back_to($default, array $allowed)
{
    $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    $p = parse_url($ref);
    $ref_host = isset($p['host']) ? $p['host'] . (isset($p['port']) ? ':' . $p['port'] : '') : null;
    if ($p && ($ref_host === null || strcasecmp($ref_host, $_SERVER['HTTP_HOST']) === 0)) {
        $file = basename(isset($p['path']) ? $p['path'] : '');
        if (in_array($file, $allowed, true)) {
            return $file . (isset($p['query']) ? '?' . $p['query'] : '');
        }
    }
    return $default;
}

// List pages a form may send the user back to.
const LIST_PAGES = ['crud.php', 'admin_crud.php', 'index.php', 'admin_index.php', 'rto.php'];

// The customer list for this user (branch or main admin).
function customers_page()
{
    return is_admin() ? 'admin_crud.php' : 'crud.php';
}

// A "return to" value posted by a form: only our own list pages are accepted.
function safe_return($ret, $default)
{
    $p = parse_url((string) $ret);
    if (!$p || isset($p['scheme']) || isset($p['host']) || !isset($p['path'])) {
        return $default;
    }
    $file = basename($p['path']);
    return in_array($file, LIST_PAGES, true) ? $file . (isset($p['query']) ? '?' . $p['query'] : '') : $default;
}

// ---------- CSRF token for background (fetch) saves ----------

function csrf_token()
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_ok($token)
{
    return !empty($_SESSION['csrf']) && is_string($token) && hash_equals($_SESSION['csrf'], $token);
}

// ---------- Database helpers (prepared statements) ----------

function db_query($con, $sql, $types = '', array $params = [])
{
    $stmt = mysqli_prepare($con, $sql);
    if ($types !== '') {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function db_value($con, $sql, $types = '', array $params = [])
{
    $row = mysqli_fetch_row(db_query($con, $sql, $types, $params));
    return $row ? $row[0] : null;
}

// LIKE pattern for a user search term (so % and _ are matched literally).
function like($q)
{
    return '%' . addcslashes($q, '%_\\') . '%';
}

// ---------- Request helpers ----------

function get_str($key, $default = '')
{
    return isset($_GET[$key]) ? trim((string) $_GET[$key]) : $default;
}

function valid_date($d)
{
    return is_string($d) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $d) && strtotime($d) !== false;
}

function fmt_date($d)
{
    if (!$d || $d === '0000-00-00') {
        return '';
    }
    $t = strtotime($d);
    return $t ? date('d-m-Y', $t) : h($d);
}

// Query string for links: current filters + overrides, empty values dropped.
function qs(array $params, array $over = [])
{
    $all = array_merge($params, $over);
    $all = array_filter($all, function ($v) { return $v !== '' && $v !== null; });
    return $all ? '?' . http_build_query($all) : '';
}

// ---------- Pagination ----------

function paginate($total, $per, $page)
{
    $pages = max(1, (int) ceil($total / $per));
    $page = min(max(1, (int) $page), $pages);
    $offset = ($page - 1) * $per;
    return [
        'total' => (int) $total, 'per' => $per, 'page' => $page, 'pages' => $pages, 'offset' => $offset,
        'from' => $total ? $offset + 1 : 0, 'to' => min($offset + $per, $total),
    ];
}

// "Showing 21–40 of 3,848" + « ‹ 1 … 4 5 [6] 7 8 … 193 › »
function render_pager(array $p, $base, array $params)
{
    $out = '<div class="bk-pager">';
    $out .= '<div class="bk-pager-info">' . ($p['total']
        ? 'Showing <b>' . number_format($p['from']) . '–' . number_format($p['to']) . '</b> of <b>' . number_format($p['total']) . '</b>'
        : 'No records') . '</div>';
    if ($p['pages'] > 1) {
        $link = function ($n, $label, $cls = '', $aria = '') use ($base, $params) {
            return '<a class="bk-page ' . $cls . '" href="' . h($base . qs($params, ['page' => $n > 1 ? $n : ''])) . '"' . ($aria ? ' aria-label="' . $aria . '"' : '') . '>' . $label . '</a>';
        };
        $cur = $p['page'];
        $out .= '<nav class="bk-pages" aria-label="Pages">';
        $out .= $cur > 1 ? $link($cur - 1, '&lsaquo;', 'bk-page-nav', 'Previous page') : '<span class="bk-page bk-page-nav is-disabled">&lsaquo;</span>';
        $win = 2;
        $start = max(1, $cur - $win);
        $end = min($p['pages'], $cur + $win);
        if ($start > 1) {
            $out .= $link(1, '1');
            if ($start > 2) {
                $out .= '<span class="bk-page-gap">…</span>';
            }
        }
        for ($i = $start; $i <= $end; $i++) {
            $out .= $i === $cur ? '<span class="bk-page is-current" aria-current="page">' . $i . '</span>' : $link($i, (string) $i);
        }
        if ($end < $p['pages']) {
            if ($end < $p['pages'] - 1) {
                $out .= '<span class="bk-page-gap">…</span>';
            }
            $out .= $link($p['pages'], number_format($p['pages']));
        }
        $out .= $cur < $p['pages'] ? $link($cur + 1, '&rsaquo;', 'bk-page-nav', 'Next page') : '<span class="bk-page bk-page-nav is-disabled">&rsaquo;</span>';
        $out .= '</nav>';
    }
    return $out . '</div>';
}

// ---------- Client list filters (dashboard, customers, RTO work) ----------

// Builds "WHERE ..." for the client table.
//   $f = ['branch' => '6', 'q' => 'text', 'date_col' => 'exp_date'|'d_inward', 'from' => 'Y-m-d', 'to' => 'Y-m-d']
// Branch users are always limited to their own branch.
function client_where(array $f)
{
    $w = [];
    $types = '';
    $params = [];
    $branch = user_branch() !== '' ? user_branch() : (isset($f['branch']) ? (string) $f['branch'] : '');
    if ($branch !== '') {
        $w[] = 'c.`branch_id` = ?';
        $types .= 's';
        $params[] = $branch;
    }
    if (!empty($f['date_col'])) {
        $col = $f['date_col'] === 'd_inward' ? 'd_inward' : 'exp_date';
        if ($col === 'd_inward') {
            // Inward date is stored as text; "< next day" also covers values that carry a time.
            $w[] = "c.`d_inward` <> '' AND c.`d_inward` >= ? AND c.`d_inward` < ?";
            $types .= 'ss';
            $params[] = $f['from'];
            $params[] = date('Y-m-d', strtotime($f['to'] . ' +1 day'));
        } else {
            $w[] = 'c.`exp_date` >= ? AND c.`exp_date` <= ?';
            $types .= 'ss';
            $params[] = $f['from'];
            $params[] = $f['to'];
        }
    }
    if (isset($f['q']) && $f['q'] !== '') {
        $cols = ['name', 'v_no', 'w_number', 'Job_data', 'sr_type', 'doc_number', 'remark'];
        $w[] = '(' . implode(' OR ', array_map(function ($c) { return "c.`$c` LIKE ?"; }, $cols)) . ')';
        $types .= str_repeat('s', count($cols));
        foreach ($cols as $c) {
            $params[] = like($f['q']);
        }
    }
    return [$w ? 'WHERE ' . implode(' AND ', $w) : '', $types, $params];
}

// Admin typed a branch name into the search box: treat it as the branch filter.
function branch_from_search($branches, &$q)
{
    foreach ($branches as $id => $b) {
        if ($q !== '' && (strcasecmp(trim($b['name']), $q) === 0 || strcasecmp(trim($b['branch_name']), $q) === 0)) {
            $q = '';
            return (string) $id;
        }
    }
    return '';
}

// ---------- Lookups used by the list pages ----------

function branches_map($con)
{
    $map = [];
    $res = mysqli_query($con, "SELECT `branch_id`, `branch_name`, `name`, `address`, `phone`, `whatsapp_no` FROM `branch` ORDER BY `branch_name`");
    while ($r = mysqli_fetch_assoc($res)) {
        $map[(string) $r['branch_id']] = $r;
    }
    return $map;
}

// WhatsApp templates, keyed "Name|srtype".
function message_templates($con)
{
    $map = [];
    $res = mysqli_query($con, "SELECT `Name`, `msg`, `srtype`, `type` FROM `msg`");
    while ($r = mysqli_fetch_assoc($res)) {
        $map[(string) $r['Name'] . '|' . (string) $r['srtype']] = $r;
        if (!isset($map[(string) $r['Name'] . '|*'])) {
            $map[(string) $r['Name'] . '|*'] = $r;
        }
    }
    return $map;
}

// Fill a message template with client + branch details (same placeholders as before).
function fill_template($tpl, array $c, $branch)
{
    $b = $branch ?: ['branch_name' => '', 'address' => '', 'phone' => '', 'whatsapp_no' => ''];
    $vars = [
        '#name' => $c['name'], '#vn' => $c['v_no'], '#newDate' => fmt_date($c['exp_date']),
        '#bname' => $b['branch_name'], '#add' => $b['address'], '#ph' => $b['phone'], '#wtapp' => $b['whatsapp_no'],
        '#doc' => $c['doc_number'], '#jobdata' => $c['Job_data'], '#sr' => $c['sr_type'],
        '#rem' => $c['remark'], '#nn' => $c['w_number'],
    ];
    // Templates and branch addresses are stored URL-style (%0a = new line). The old links put this
    // text into the URL as-is, so decode once here; wa_url() then encodes it exactly once.
    return rawurldecode(strtr((string) $tpl, $vars));
}

function wa_url($phone, $text)
{
    $phone = preg_replace('/\D+/', '', (string) $phone);
    return 'https://api.whatsapp.com/send?phone=' . $phone . '&text=' . rawurlencode($text);
}

// WhatsApp link for a dashboard row (template chosen by service type), or null.
function dashboard_wa($c, $branch, $tpls)
{
    $key = (string) $c['sr_type'] . '|*';
    if ($c['wt_status'] === 'no' || !isset($tpls[$key])) {
        return null;
    }
    return wa_url($c['w_number'], fill_template($tpls[$key]['msg'], $c, $branch));
}

// WhatsApp link for an RTO Work row (template chosen by job status + service), or null.
function rto_wa($c, $branch, $tpls)
{
    $key = (string) $c['Job_data'] . '|' . (string) $c['sr_type'];
    if ($c['wt_status'] === 'no' || $c['Job_data'] === '' || !isset($tpls[$key])) {
        return null;
    }
    return wa_url($c['w_number'], fill_template($tpls[$key]['msg'], $c, $branch));
}

// Remaining days until a date (negative = past).
function days_left($d)
{
    if (!$d || $d === '0000-00-00') {
        return null;
    }
    return (int) floor((strtotime($d) - strtotime(date('Y-m-d'))) / 86400);
}

function expiry_badge($d)
{
    $n = days_left($d);
    if ($n === null) {
        return '';
    }
    if ($n < 0) {
        return '<span class="bk-badge is-danger">Expired</span>';
    }
    if ($n <= 7) {
        return '<span class="bk-badge is-warn">' . ($n === 0 ? 'Today' : $n . ' day' . ($n === 1 ? '' : 's')) . '</span>';
    }
    return '';
}
