/* Benaka MDS admin — theme toggle, toasts, confirm dialog, loading bar, inline row saves. */
(function () {
  'use strict';

  var root = document.documentElement;
  var KEY = 'bk-theme';

  // ---------- Theme: device setting unless the user picked one ----------
  function storedTheme() { try { return localStorage.getItem(KEY); } catch (e) { return null; } }
  function deviceTheme() { return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'; }
  function applyTheme(t) { root.setAttribute('data-theme', t); }
  if (window.matchMedia) {
    var mq = window.matchMedia('(prefers-color-scheme: dark)');
    var onChange = function () { if (!storedTheme()) applyTheme(deviceTheme()); };
    if (mq.addEventListener) mq.addEventListener('change', onChange); else if (mq.addListener) mq.addListener(onChange);
  }

  // ---------- Toasts ----------
  function toastBox() {
    var box = document.querySelector('.bk-toasts');
    if (!box) { box = document.createElement('div'); box.className = 'bk-toasts'; box.setAttribute('role', 'status'); box.setAttribute('aria-live', 'polite'); document.body.appendChild(box); }
    return box;
  }
  function toast(msg, type) {
    type = type || 'success';
    var icon = type === 'error' ? 'ion-alert-circled' : (type === 'info' ? 'ion-information-circled' : 'ion-checkmark-circled');
    var el = document.createElement('div');
    el.className = 'bk-toast is-' + type;
    el.innerHTML = '<i class="icon ' + icon + '"></i><div></div>';
    el.lastChild.textContent = msg;
    toastBox().appendChild(el);
    setTimeout(function () { el.classList.add('is-leaving'); setTimeout(function () { el.remove(); }, 220); }, type === 'error' ? 6000 : 3200);
  }
  window.bkToast = toast;

  // ---------- Confirm dialog (replaces window.confirm) ----------
  function confirmBox(title, text, okLabel, danger) {
    return new Promise(function (resolve) {
      var bd = document.createElement('div');
      bd.className = 'bk-modal-backdrop';
      bd.innerHTML = '<div class="bk-modal" role="dialog" aria-modal="true"><h6></h6><p></p><div class="bk-modal-actions">' +
        '<button type="button" class="btn bk-btn-ghost" data-act="no">Cancel</button>' +
        '<button type="button" class="btn ' + (danger ? 'btn-danger' : 'btn-primary') + '" data-act="yes"></button></div></div>';
      bd.querySelector('h6').textContent = title;
      bd.querySelector('p').textContent = text || '';
      bd.querySelector('[data-act=yes]').textContent = okLabel || 'OK';
      function close(v) { document.removeEventListener('keydown', onKey); bd.remove(); resolve(v); }
      function onKey(e) { if (e.key === 'Escape') close(false); if (e.key === 'Enter') close(true); }
      bd.addEventListener('click', function (e) {
        if (e.target === bd) return close(false);
        var act = e.target.getAttribute('data-act');
        if (act) close(act === 'yes');
      });
      document.addEventListener('keydown', onKey);
      document.body.appendChild(bd);
      bd.querySelector('[data-act=yes]').focus();
    });
  }
  window.bkConfirm = confirmBox;

  // ---------- Loading bar for page navigation ----------
  var bar;
  function loading() {
    if (!bar) { bar = document.createElement('div'); bar.className = 'bk-loadbar'; document.body.appendChild(bar); }
    requestAnimationFrame(function () { bar.classList.add('is-on'); });
  }
  window.addEventListener('pageshow', function () { if (bar) bar.classList.remove('is-on'); });

  document.addEventListener('click', function (e) {
    // Theme toggle
    var tbtn = e.target.closest('[data-theme-toggle]');
    if (tbtn) {
      var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      applyTheme(next);
      try { localStorage.setItem(KEY, next); } catch (err) {}
      return;
    }

    // Links/buttons that need confirmation: data-confirm="Delete this client?"
    var c = e.target.closest('[data-confirm]');
    if (c && !c.__confirmed) {
      e.preventDefault();
      confirmBox(c.getAttribute('data-confirm'), c.getAttribute('data-confirm-text') || '', c.getAttribute('data-confirm-ok') || 'Delete', !c.hasAttribute('data-confirm-safe'))
        .then(function (ok) {
          if (!ok) return;
          c.__confirmed = true;
          if (c.tagName === 'A') { loading(); window.location.href = c.href; }
          else if (c.form) { c.form.requestSubmit ? c.form.requestSubmit(c) : c.form.submit(); }
          else c.click();
          c.__confirmed = false;
        });
      return;
    }

    // Show the loading bar for normal same-site navigation
    var a = e.target.closest('a[href]');
    if (a && !e.defaultPrevented && !a.target && !e.ctrlKey && !e.metaKey && !e.shiftKey && a.origin === location.origin &&
        a.getAttribute('href').charAt(0) !== '#' && a.href.indexOf('javascript:') !== 0 && !a.hasAttribute('download')) {
      loading();
    }
  });

  document.addEventListener('submit', function (e) {
    if (e.defaultPrevented) return;
    var btn = e.submitter || (e.target.querySelector && e.target.querySelector('[type=submit]'));
    if (btn && btn.classList) btn.classList.add('is-loading');
    loading();
  });

  // ---------- Inline save of Job status / Remark on list rows ----------
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-save-row]');
    if (!btn) return;
    e.preventDefault();
    var row = btn.closest('tr');
    var id = btn.getAttribute('data-save-row');
    var body = new FormData();
    body.append('id', id);
    body.append('mode', btn.getAttribute('data-mode') || 'dashboard');
    body.append('job', (row.querySelector('[data-job]') || {}).value || '');
    body.append('remark', (row.querySelector('[data-remark]') || {}).value || '');
    body.append('csrf', (document.querySelector('meta[name=csrf-token]') || {}).content || '');
    btn.classList.add('is-loading');
    fetch('api_job.php', { method: 'POST', body: body, credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
      .then(function (r) { return r.json().catch(function () { return { ok: false, msg: 'Session expired — please log in again.' }; }); })
      .then(function (res) {
        btn.classList.remove('is-loading');
        if (!res.ok) { toast(res.msg || 'Could not save', 'error'); return; }
        toast(res.msg || 'Saved');
        row.classList.remove('bk-row-saved'); void row.offsetWidth; row.classList.add('bk-row-saved');
        var wa = row.querySelector('[data-wa]');
        if (wa && 'wa' in res) {
          if (res.wa) { wa.href = res.wa; wa.classList.remove('is-disabled'); wa.removeAttribute('aria-disabled'); wa.title = 'Send WhatsApp message'; }
          else { wa.removeAttribute('href'); wa.classList.add('is-disabled'); wa.setAttribute('aria-disabled', 'true'); wa.title = 'No message template for this job status'; }
        }
      })
      .catch(function () { btn.classList.remove('is-loading'); toast('Network error — not saved', 'error'); });
  });

  // Disabled WhatsApp icon explains itself
  document.addEventListener('click', function (e) {
    var wa = e.target.closest('.bk-wa.is-disabled');
    if (wa) { e.preventDefault(); toast(wa.title || 'No WhatsApp message for this row', 'info'); }
  });

  // ---------- Flash messages from the server ----------
  document.addEventListener('DOMContentLoaded', function () {
    var f = document.getElementById('bk-flash');
    if (!f) return;
    try { JSON.parse(f.textContent).forEach(function (m) { toast(m.msg, m.type === 'error' ? 'error' : (m.type === 'info' ? 'info' : 'success')); }); } catch (err) {}
  });

  // Print the current list
  window.bkPrint = function () { window.print(); };
})();
