<?php
// Session başlat
session_start();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SeeAuto — Araç Bakım ve Takip Sistemi</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap');

  * { margin: 0; padding: 0; box-sizing: border-box; }

  :root {
    --c-bg: #0a0a0f;
    --c-surface: #111118;
    --c-card: #16161f;
    --c-border: rgba(255,255,255,0.07);
    --c-border2: rgba(255,255,255,0.12);
    --c-gold: #e8b84b;
    --c-gold2: #f5d07a;
    --c-red: #e05252;
    --c-green: #4ecb8a;
    --c-blue: #5b9cf6;
    --c-text: #f0eee8;
    --c-muted: rgba(240,238,232,0.45);
    --c-muted2: rgba(240,238,232,0.25);
  }

  body { font-family: 'DM Sans', sans-serif; background: var(--c-bg); color: var(--c-text); min-height: 100vh; }

  .screen { display: none; }
  .screen.active { display: block; }

  /* ===== ROL SEÇİM ===== */
  #role-screen {
    min-height: 100vh; display: none; flex-direction: column;
    align-items: center; justify-content: center;
    background: var(--c-bg); position: relative; overflow: hidden;
  }
  #role-screen.active { display: flex; }
  #role-screen::before {
    content:''; position:absolute; width:500px; height:500px; border-radius:50%;
    background:radial-gradient(circle,rgba(232,184,75,0.07) 0%,transparent 70%);
    top:-150px; left:-100px; pointer-events:none;
  }
  #role-screen::after {
    content:''; position:absolute; width:400px; height:400px; border-radius:50%;
    background:radial-gradient(circle,rgba(91,156,246,0.06) 0%,transparent 70%);
    bottom:-100px; right:-100px; pointer-events:none;
  }
  .role-screen-inner { position:relative; z-index:1; display:flex; flex-direction:column; align-items:center; width:100%; }
  .role-brand { display:flex; align-items:center; gap:12px; margin-bottom:48px; }
  .role-brand-icon { width:52px; height:52px; background:var(--c-gold); border-radius:14px; display:flex; align-items:center; justify-content:center; }
  .role-brand-icon svg { width:26px; height:26px; }
  .role-brand-name { font-family:'Syne',sans-serif; font-size:26px; font-weight:800; letter-spacing:-0.5px; }
  .role-brand-sub { font-size:12px; color:var(--c-muted); letter-spacing:1.5px; text-transform:uppercase; margin-top:2px; }
  .role-headline { font-family:'Syne',sans-serif; font-size:32px; font-weight:800; letter-spacing:-1px; text-align:center; margin-bottom:10px; line-height:1.1; }
  .role-headline span { color:var(--c-gold); }
  .role-sub-text { font-size:14px; color:var(--c-muted); text-align:center; margin-bottom:48px; }
  .role-cards { display:flex; gap:20px; justify-content:center; flex-wrap:wrap; }
  .role-card { width:220px; background:var(--c-card); border:1px solid var(--c-border); border-radius:20px; padding:32px 24px; cursor:pointer; transition:all .25s; display:flex; flex-direction:column; align-items:center; text-align:center; position:relative; overflow:hidden; }
  .role-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; opacity:0; transition:opacity .25s; }
  .role-card.admin-card::before { background:linear-gradient(90deg,var(--c-gold),var(--c-gold2)); }
  .role-card.musteri-card::before { background:linear-gradient(90deg,var(--c-blue),#8bb8ff); }
  .role-card:hover { border-color:var(--c-border2); transform:translateY(-4px); box-shadow:0 20px 40px rgba(0,0,0,0.3); }
  .role-card:hover::before { opacity:1; }
  .role-card.admin-card:hover { border-color:rgba(232,184,75,0.3); }
  .role-card.musteri-card:hover { border-color:rgba(91,156,246,0.3); }
  .role-icon-wrap { width:64px; height:64px; border-radius:18px; display:flex; align-items:center; justify-content:center; margin-bottom:20px; }
  .admin-card .role-icon-wrap { background:rgba(232,184,75,0.12); border:1px solid rgba(232,184,75,0.2); }
  .musteri-card .role-icon-wrap { background:rgba(91,156,246,0.12); border:1px solid rgba(91,156,246,0.2); }
  .role-icon-wrap svg { width:30px; height:30px; }
  .admin-card .role-icon-wrap svg { stroke:var(--c-gold); }
  .musteri-card .role-icon-wrap svg { stroke:var(--c-blue); }
  .role-card-title { font-family:'Syne',sans-serif; font-size:18px; font-weight:700; margin-bottom:8px; }
  .admin-card .role-card-title { color:var(--c-gold); }
  .musteri-card .role-card-title { color:var(--c-blue); }
  .role-card-desc { font-size:12px; color:var(--c-muted); line-height:1.6; }
  .role-card-btn { margin-top:24px; padding:10px 24px; border-radius:10px; border:none; font-family:'Syne',sans-serif; font-size:13px; font-weight:700; cursor:pointer; transition:all .2s; pointer-events:none; }
  .admin-card .role-card-btn { background:var(--c-gold); color:#0a0a0f; }
  .musteri-card .role-card-btn { background:var(--c-blue); color:#0a0a0f; }

  /* ===== LOGIN ===== */
  #login-screen { min-height:100vh; display:none; align-items:stretch; position:relative; overflow:hidden; }
  #login-screen.active { display:flex; }
  .login-visual { flex:1; background:linear-gradient(135deg,#0a0a0f 0%,#1a1025 50%,#0f1a25 100%); position:relative; display:flex; flex-direction:column; justify-content:center; padding:48px; overflow:hidden; }
  .login-visual::before { content:''; position:absolute; width:400px; height:400px; border-radius:50%; background:radial-gradient(circle,rgba(232,184,75,0.12) 0%,transparent 70%); top:-80px; left:-80px; }
  .login-visual::after { content:''; position:absolute; width:300px; height:300px; border-radius:50%; background:radial-gradient(circle,rgba(91,156,246,0.08) 0%,transparent 70%); bottom:-60px; right:-60px; }
  .brand-logo { display:flex; align-items:center; gap:12px; margin-bottom:64px; position:relative; z-index:1; }
  .brand-icon { width:44px; height:44px; background:var(--c-gold); border-radius:10px; display:flex; align-items:center; justify-content:center; }
  .brand-icon svg { width:22px; height:22px; }
  .brand-name { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; letter-spacing:-0.5px; }
  .brand-sub { font-size:11px; color:var(--c-muted); letter-spacing:1.5px; text-transform:uppercase; margin-top:2px; }
  .login-headline { font-family:'Syne',sans-serif; font-size:42px; font-weight:800; letter-spacing:-2px; line-height:1; position:relative; z-index:1; margin-bottom:16px; }
  .login-headline span { color:var(--c-gold); }
  .login-desc { font-size:14px; color:var(--c-muted); line-height:1.7; position:relative; z-index:1; max-width:340px; }
  .login-stats { display:flex; gap:32px; margin-top:48px; position:relative; z-index:1; }
  .login-stat-val { font-family:'Syne',sans-serif; font-size:28px; font-weight:800; color:var(--c-gold); }
  .login-stat-lbl { font-size:11px; color:var(--c-muted); margin-top:2px; letter-spacing:0.5px; }
  .login-form-wrap { width:420px; background:var(--c-surface); padding:48px; display:flex; flex-direction:column; justify-content:center; border-left:1px solid var(--c-border); }
  .login-form-title { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; margin-bottom:8px; }
  .login-form-sub { font-size:13px; color:var(--c-muted); margin-bottom:32px; }
  .login-rol-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:20px; font-size:12px; font-weight:600; margin-bottom:32px; }
  .login-rol-badge.admin { background:rgba(232,184,75,0.12); border:1px solid rgba(232,184,75,0.25); color:var(--c-gold); }
  .login-rol-badge.musteri { background:rgba(91,156,246,0.12); border:1px solid rgba(91,156,246,0.25); color:var(--c-blue); }
  .form-group { margin-bottom:20px; }
  .form-label { font-size:12px; font-weight:500; color:var(--c-muted); letter-spacing:0.5px; text-transform:uppercase; margin-bottom:8px; display:block; }
  .form-input { width:100%; background:var(--c-card); border:1px solid var(--c-border); border-radius:12px; padding:14px 16px; color:var(--c-text); font-family:'DM Sans',sans-serif; font-size:14px; outline:none; transition:border-color .2s; }
  .form-input:focus { border-color:var(--c-border2); }
  .form-input::placeholder { color:var(--c-muted2); }
  .login-btn { width:100%; padding:16px; background:var(--c-gold); color:#0a0a0f; border:none; border-radius:12px; font-family:'Syne',sans-serif; font-size:15px; font-weight:700; cursor:pointer; transition:all .2s; margin-top:8px; }
  .login-btn:hover { background:var(--c-gold2); transform:translateY(-1px); }
  .login-btn.musteri-btn { background:var(--c-blue); color:#fff; }
  .login-btn.musteri-btn:hover { background:#7aaeff; }
  .login-back { margin-top:20px; text-align:center; font-size:13px; color:var(--c-muted); cursor:pointer; transition:color .2s; }
  .login-back:hover { color:var(--c-text); }
  .login-hint { margin-top:24px; padding:14px 16px; background:rgba(255,255,255,0.03); border:1px solid var(--c-border); border-radius:10px; font-size:12px; color:var(--c-muted); line-height:1.6; }
  .login-hint strong { color:var(--c-text); }
  .login-error { margin-top:12px; padding:12px 14px; background:rgba(224,82,82,0.12); border:1px solid rgba(224,82,82,0.25); border-radius:10px; font-size:13px; color:var(--c-red); display:none; }

  /* ===== ADMIN PANEL ===== */
  #admin-screen { min-height:100vh; display:none; }
  #admin-screen.active { display:flex; }
  .admin-sidebar { width:240px; background:var(--c-surface); border-right:1px solid var(--c-border); display:flex; flex-direction:column; position:fixed; top:0; left:0; bottom:0; z-index:10; }
  .sidebar-logo { display:flex; align-items:center; gap:10px; padding:24px 20px; border-bottom:1px solid var(--c-border); }
  .sidebar-logo-icon { width:34px; height:34px; background:var(--c-gold); border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
  .sidebar-logo-icon svg { width:18px; height:18px; }
  .sidebar-logo-name { font-family:'Syne',sans-serif; font-size:16px; font-weight:800; }
  .sidebar-nav { flex:1; padding:16px 12px; overflow-y:auto; }
  .nav-section { font-size:10px; color:var(--c-muted2); letter-spacing:1.5px; text-transform:uppercase; padding:0 8px; margin:16px 0 8px; }
  .nav-item { display:flex; align-items:center; gap:10px; padding:10px 10px; border-radius:10px; cursor:pointer; font-size:13px; font-weight:500; color:var(--c-muted); transition:all .2s; margin-bottom:2px; }
  .nav-item:hover { background:rgba(255,255,255,0.05); color:var(--c-text); }
  .nav-item.active { background:rgba(232,184,75,0.1); color:var(--c-gold); }
  .nav-item svg { width:16px; height:16px; flex-shrink:0; }
  .sidebar-footer { padding:16px 12px; border-top:1px solid var(--c-border); }
  .sidebar-user { display:flex; align-items:center; gap:10px; padding:10px; }
  .sidebar-avatar { width:32px; height:32px; background:var(--c-gold); border-radius:8px; display:flex; align-items:center; justify-content:center; font-family:'Syne',sans-serif; font-size:13px; font-weight:700; color:#0a0a0f; }
  .sidebar-user-name { font-size:13px; font-weight:500; }
  .sidebar-user-role { font-size:11px; color:var(--c-muted); }
  .logout-btn { display:flex; align-items:center; gap:8px; padding:10px; border-radius:10px; cursor:pointer; font-size:12px; color:var(--c-muted); transition:all .2s; margin-top:4px; }
  .logout-btn:hover { color:var(--c-red); background:rgba(224,82,82,0.08); }
  .logout-btn svg { width:14px; height:14px; }
  .admin-main { margin-left:240px; flex:1; padding:32px; min-height:100vh; }
  .page-header { margin-bottom:32px; }
  .page-title { font-family:'Syne',sans-serif; font-size:26px; font-weight:800; letter-spacing:-0.5px; }
  .page-sub { font-size:14px; color:var(--c-muted); margin-top:4px; }
  .admin-page { display:none; }
  .admin-page.active { display:block; }

  /* STAT KARTLARI */
  .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px; }
  .stat-card { background:var(--c-card); border:1px solid var(--c-border); border-radius:16px; padding:20px; }
  .stat-label { font-size:12px; color:var(--c-muted); letter-spacing:0.5px; margin-bottom:8px; }
  .stat-value { font-family:'Syne',sans-serif; font-size:28px; font-weight:800; }
  .stat-value.gold { color:var(--c-gold); }
  .stat-value.green { color:var(--c-green); }
  .stat-value.blue { color:var(--c-blue); }

  /* TABLO */
  .table-card { background:var(--c-card); border:1px solid var(--c-border); border-radius:16px; overflow:hidden; margin-bottom:20px; }
  .table-header { display:flex; align-items:center; justify-content:space-between; padding:18px 20px; border-bottom:1px solid var(--c-border); }
  .table-title { font-family:'Syne',sans-serif; font-size:15px; font-weight:700; }
  .table-count { font-size:12px; color:var(--c-muted); }
  .add-btn { display:flex; align-items:center; gap:6px; padding:8px 16px; background:var(--c-gold); color:#0a0a0f; border:none; border-radius:8px; font-family:'Syne',sans-serif; font-size:12px; font-weight:700; cursor:pointer; transition:all .2s; }
  .add-btn:hover { background:var(--c-gold2); }
  .add-btn svg { width:14px; height:14px; }
  table { width:100%; border-collapse:collapse; }
  th { font-size:11px; color:var(--c-muted); letter-spacing:0.5px; text-transform:uppercase; padding:12px 16px; text-align:left; border-bottom:1px solid var(--c-border); font-weight:500; }
  td { padding:14px 16px; font-size:13px; border-bottom:1px solid rgba(255,255,255,0.03); }
  tr:last-child td { border-bottom:none; }
  tr:hover td { background:rgba(255,255,255,0.02); }
  .del-btn { padding:5px 12px; background:rgba(224,82,82,0.1); color:var(--c-red); border:1px solid rgba(224,82,82,0.2); border-radius:6px; font-size:11px; cursor:pointer; transition:all .2s; }
  .del-btn:hover { background:rgba(224,82,82,0.2); }
  .plaka-badge { display:inline-block; background:rgba(232,184,75,0.1); border:1px solid rgba(232,184,75,0.2); color:var(--c-gold); padding:3px 10px; border-radius:6px; font-family:'Syne',sans-serif; font-size:12px; font-weight:700; letter-spacing:1px; }
  .ucret-badge { color:var(--c-green); font-weight:600; }
  .empty-row { text-align:center; padding:32px; font-size:13px; color:var(--c-muted); }

  /* MODAL FORM */
  .modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:100; display:none; align-items:center; justify-content:center; }
  .modal-overlay.open { display:flex; }
  .modal-box { background:var(--c-surface); border:1px solid var(--c-border2); border-radius:20px; padding:32px; width:480px; max-width:95vw; }
  .modal-title { font-family:'Syne',sans-serif; font-size:18px; font-weight:800; margin-bottom:20px; }
  .modal-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
  .modal-grid .full { grid-column:1/-1; }
  .modal-actions { display:flex; gap:10px; justify-content:flex-end; margin-top:20px; }
  .btn-cancel { padding:10px 20px; background:transparent; border:1px solid var(--c-border); color:var(--c-muted); border-radius:10px; cursor:pointer; font-size:13px; transition:all .2s; }
  .btn-cancel:hover { border-color:var(--c-border2); color:var(--c-text); }
  .btn-save { padding:10px 24px; background:var(--c-gold); color:#0a0a0f; border:none; border-radius:10px; font-family:'Syne',sans-serif; font-size:13px; font-weight:700; cursor:pointer; transition:all .2s; }
  .btn-save:hover { background:var(--c-gold2); }
  .btn-save.blue { background:var(--c-blue); color:#fff; }
  textarea.form-input { resize:vertical; min-height:80px; }
  select.form-input { appearance:none; }

  /* MÜŞTERİ PANEL */
  #musteri-screen { min-height:100vh; display:none; }
  #musteri-screen.active { display:flex; flex-direction:column; }
  .musteri-header { background:var(--c-surface); border-bottom:1px solid var(--c-border); padding:0 24px; display:flex; align-items:center; justify-content:space-between; height:64px; position:sticky; top:0; z-index:10; }
  .musteri-brand { display:flex; align-items:center; gap:10px; }
  .musteri-brand-icon { width:32px; height:32px; background:var(--c-gold); border-radius:8px; display:flex; align-items:center; justify-content:center; }
  .musteri-brand-icon svg { width:16px; height:16px; }
  .musteri-brand-name { font-family:'Syne',sans-serif; font-size:16px; font-weight:800; }
  .musteri-header-right { display:flex; align-items:center; gap:16px; }
  .musteri-user-info { text-align:right; }
  .musteri-user-name { font-size:13px; font-weight:600; }
  .musteri-user-role { font-size:11px; color:var(--c-blue); }
  .musteri-logout { padding:6px 14px; background:transparent; border:1px solid var(--c-border); border-radius:8px; color:var(--c-muted); font-size:12px; cursor:pointer; transition:all .2s; }
  .musteri-logout:hover { color:var(--c-red); border-color:rgba(224,82,82,0.3); }
  .musteri-nav { background:var(--c-surface); border-bottom:1px solid var(--c-border); display:flex; padding:0 24px; }
  .musteri-nav .nav-item { padding:16px 14px; font-size:13px; border-bottom:2px solid transparent; color:var(--c-muted); border-radius:0; margin:0 2px; }
  .musteri-nav .nav-item.active { color:var(--c-blue); border-bottom-color:var(--c-blue); background:transparent; }
  .musteri-nav .nav-item:hover { color:var(--c-text); background:transparent; }
  .musteri-content { flex:1; padding:28px 24px; }

  /* MÜŞTERİ ARAÇ GRID */
  .arac-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:16px; margin-top:16px; }
  .my-arac-card { background:var(--c-card); border:1px solid var(--c-border); border-radius:16px; padding:20px; cursor:pointer; transition:all .2s; }
  .my-arac-card:hover { border-color:var(--c-border2); transform:translateY(-2px); }
  .my-arac-plaka { font-family:'Syne',sans-serif; font-size:16px; font-weight:800; color:var(--c-gold); letter-spacing:1px; margin-bottom:6px; }
  .my-arac-model { font-size:14px; font-weight:600; margin-bottom:4px; }
  .my-arac-yil { font-size:12px; color:var(--c-muted); margin-bottom:12px; }
  .my-arac-status { display:flex; align-items:center; gap:6px; }
  .status-dot { width:6px; height:6px; border-radius:50%; }
  .status-lbl { font-size:11px; font-weight:500; }
  .s-active .status-dot { background:var(--c-green); }
  .s-active .status-lbl { color:var(--c-green); }
  .s-pending .status-dot { background:var(--c-gold); }
  .s-pending .status-lbl { color:var(--c-gold); }

  /* BAKIM MÜŞTERİ LİST */
  .bakim-musteri-row { display:flex; align-items:center; gap:14px; padding:14px 16px; border-bottom:1px solid rgba(255,255,255,0.04); transition:background .2s; }
  .bakim-musteri-row:last-child { border-bottom:none; }
  .bakim-musteri-row:hover { background:rgba(255,255,255,0.02); }
  .bakim-icon { width:40px; height:40px; background:rgba(232,184,75,0.1); border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
  .bakim-icon svg { width:18px; height:18px; stroke:var(--c-gold); }
  .bakim-musteri-info { flex:1; }
  .bakim-musteri-hizmet { font-size:13px; font-weight:600; }
  .bakim-musteri-detail { font-size:12px; color:var(--c-muted); margin-top:2px; }

  /* RANDEVU FORM */
  .rdv-form { background:var(--c-card); border:1px solid var(--c-border); border-radius:16px; padding:24px; margin-bottom:20px; }
  .rdv-form-title { font-family:'Syne',sans-serif; font-size:16px; font-weight:700; margin-bottom:20px; }
  .rdv-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
  .rdv-grid .full { grid-column:1/-1; }
  .rdv-submit { padding:12px 28px; background:var(--c-blue); color:#fff; border:none; border-radius:10px; font-family:'Syne',sans-serif; font-size:13px; font-weight:700; cursor:pointer; transition:all .2s; margin-top:4px; }
  .rdv-submit:hover { background:#7aaeff; }

  /* TOAST */
  #toast { position:fixed; bottom:24px; right:24px; padding:12px 20px; border-radius:12px; font-size:13px; font-weight:600; z-index:999; opacity:0; transform:translateY(8px); transition:all .3s; pointer-events:none; }
  #toast.show { opacity:1; transform:translateY(0); }

  /* LOADING */
  .loading { text-align:center; padding:32px; color:var(--c-muted); font-size:13px; }

  @media (max-width: 768px) {
    .admin-sidebar { display:none; }
    .admin-main { margin-left:0; padding:16px; }
    .stats-grid { grid-template-columns:1fr 1fr; }
    .login-visual { display:none; }
    .login-form-wrap { width:100%; }
    .modal-grid { grid-template-columns:1fr; }
    .rdv-grid { grid-template-columns:1fr; }
  }
</style>
</head>
<body>

<!-- ===== ROL SEÇİM ===== -->
<div id="role-screen" class="active">
  <div class="role-screen-inner">
    <div class="role-brand">
      <div class="role-brand-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="#0a0a0f" stroke-width="2" stroke-linecap="round">
          <path d="M14 16H9m10 0h3v-3.15a1 1 0 00-.84-.99L16 11l-2.7-3.6a1 1 0 00-.8-.4H5.24a2 2 0 00-1.8 1.1L2 11v5h2"/>
          <circle cx="6.5" cy="16.5" r="2.5"/><circle cx="16.5" cy="16.5" r="2.5"/>
        </svg>
      </div>
      <div>
        <div class="role-brand-name">SeeAuto</div>
        <div class="role-brand-sub">Bakım & Takip</div>
      </div>
    </div>
    <div class="role-headline">Hoş Geldiniz<br><span>Rolünüzü Seçin</span></div>
    <div class="role-sub-text">Sisteme giriş yapmak için rolünüzü seçin</div>
    <div class="role-cards">
      <div class="role-card admin-card" onclick="selectRole('admin')">
        <div class="role-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round">
            <path d="M12 2L3 7l9 5 9-5-9-5z"/><path d="M3 12l9 5 9-5"/><path d="M3 17l9 5 9-5"/>
          </svg>
        </div>
        <div class="role-card-title">Admin</div>
        <div class="role-card-desc">Araç, müşteri, personel ve bakım yönetimi</div>
        <div class="role-card-btn">Giriş Yap →</div>
      </div>
      <div class="role-card musteri-card" onclick="selectRole('musteri')">
        <div class="role-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round">
            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
          </svg>
        </div>
        <div class="role-card-title">Müşteri</div>
        <div class="role-card-desc">Araçlarınızı ve bakım geçmişinizi görüntüleyin</div>
        <div class="role-card-btn">Giriş Yap →</div>
      </div>
    </div>
  </div>
</div>

<!-- ===== LOGIN ===== -->
<div id="login-screen">
  <div class="login-visual">
    <div class="brand-logo">
      <div class="brand-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="#0a0a0f" stroke-width="2" stroke-linecap="round">
          <path d="M14 16H9m10 0h3v-3.15a1 1 0 00-.84-.99L16 11l-2.7-3.6a1 1 0 00-.8-.4H5.24a2 2 0 00-1.8 1.1L2 11v5h2"/>
          <circle cx="6.5" cy="16.5" r="2.5"/><circle cx="16.5" cy="16.5" r="2.5"/>
        </svg>
      </div>
      <div>
        <div class="brand-name">SeeAuto</div>
        <div class="brand-sub">Bakım & Takip</div>
      </div>
    </div>
    <div class="login-headline">Araç<br>Bakım<br><span>Sistemi</span></div>
    <div class="login-desc">Profesyonel araç bakım ve takip yönetimi. Tüm işlemlerinizi tek panelden kolayca yönetin.</div>
    <div class="login-stats">
      <div><div class="login-stat-val" id="stat-arac">—</div><div class="login-stat-lbl">Araç</div></div>
      <div><div class="login-stat-val" id="stat-musteri">—</div><div class="login-stat-lbl">Müşteri</div></div>
      <div><div class="login-stat-val" id="stat-bakim">—</div><div class="login-stat-lbl">Bakım</div></div>
    </div>
  </div>
  <div class="login-form-wrap">
    <div class="login-rol-badge admin" id="login-rol-badge" style="display:none">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M12 2L3 7l9 5 9-5-9-5z"/></svg>
      Admin Girişi
    </div>
    <div class="login-form-title" id="login-form-title">Giriş Yap</div>
    <div class="login-form-sub" id="login-form-sub">Bilgilerinizi girin</div>

    <div class="form-group">
      <label class="form-label" id="label-giris">Kullanıcı Adı</label>
      <input type="text" class="form-input" id="inp-giris" placeholder="admin">
    </div>
    <div class="form-group">
      <label class="form-label" id="label-sifre">Şifre</label>
      <input type="password" class="form-input" id="inp-sifre" placeholder="••••••••" onkeydown="if(event.key==='Enter') doLogin()">
    </div>
    <div class="login-error" id="login-error"></div>
    <button class="login-btn" id="login-btn" onclick="doLogin()">Giriş Yap</button>
    <!-- Bu kodu 354. satırın altına yapıştır -->
<div id="register-link-area" style="margin-top: 15px; text-align: center; font-size: 13px; display:none;">
    <span style="color: var(--c-muted);">Hesabınız yok mu?</span> 
    <a href="javascript:void(0)" onclick="showRegister()" style="color: var(--c-gold); text-decoration: none; font-weight: 600;"> Üye Ol</a>
</div>
    <div class="login-hint" id="login-hint" style="display:none">
      <strong>Admin:</strong> Kullanıcı adı: <code>admin</code> / Şifre: <code>admin123</code>
    </div>
    <div class="login-back" onclick="goBack()">← Rol seçimine dön</div>
  </div>
</div>

<!-- ===== ADMİN PANEL ===== -->
<div id="admin-screen">
  <div class="admin-sidebar">
    <div class="sidebar-logo">
      <div class="sidebar-logo-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="#0a0a0f" stroke-width="2" stroke-linecap="round">
          <path d="M14 16H9m10 0h3v-3.15a1 1 0 00-.84-.99L16 11l-2.7-3.6a1 1 0 00-.8-.4H5.24a2 2 0 00-1.8 1.1L2 11v5h2"/>
          <circle cx="6.5" cy="16.5" r="2.5"/><circle cx="16.5" cy="16.5" r="2.5"/>
        </svg>
      </div>
      <div class="sidebar-logo-name">SeeAuto</div>
    </div>
    <div class="sidebar-nav">
      <div class="nav-section">Yönetim</div>
      <div class="nav-item active" onclick="adminPage('dashboard',this)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Dashboard
      </div>
      <div class="nav-item" onclick="adminPage('musteriler',this)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        Müşteriler
      </div>
      <div class="nav-item" onclick="adminPage('araclar',this)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M14 16H9m10 0h3v-3.15a1 1 0 00-.84-.99L16 11l-2.7-3.6a1 1 0 00-.8-.4H5.24a2 2 0 00-1.8 1.1L2 11v5h2"/><circle cx="6.5" cy="16.5" r="2.5"/><circle cx="16.5" cy="16.5" r="2.5"/></svg>
        Araçlar
      </div>
      <div class="nav-item" onclick="adminPage('bakimlar',this)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
        Bakım Kayıtları
      </div>
      <div class="nav-item" onclick="adminPage('personel',this)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Personel
      </div>
      <div class="nav-item" onclick="adminPage('hizmetler',this)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M5.34 18.66l-1.41 1.41M12 2v2M12 20v2M4.93 4.93l1.41 1.41M18.66 18.66l1.41 1.41M2 12h2M20 12h2"/></svg>
        Hizmetler
      </div>
    </div>
    <div class="sidebar-footer">
      <div class="sidebar-user">
        <div class="sidebar-avatar">AD</div>
        <div>
          <div class="sidebar-user-name">Admin</div>
          <div class="sidebar-user-role">Yönetici</div>
        </div>
      </div>
      <div class="logout-btn" onclick="doLogout()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Çıkış Yap
      </div>
    </div>
  </div>

  <div class="admin-main">
    <!-- DASHBOARD -->
    <div class="admin-page active" id="page-dashboard">
      <div class="page-header">
        <div class="page-title">Dashboard</div>
        <div class="page-sub">Sistem özeti ve son işlemler</div>
      </div>
      <div class="stats-grid">
        <div class="stat-card"><div class="stat-label">TOPLAM ARAÇ</div><div class="stat-value gold" id="d-arac">—</div></div>
        <div class="stat-card"><div class="stat-label">MÜŞTERİ SAYISI</div><div class="stat-value blue" id="d-musteri">—</div></div>
        <div class="stat-card"><div class="stat-label">BAKIM KAYDI</div><div class="stat-value" id="d-bakim">—</div></div>
        <div class="stat-card"><div class="stat-label">TOPLAM GELİR</div><div class="stat-value green" id="d-gelir">—</div></div>
      </div>
      <div class="table-card">
        <div class="table-header">
          <div><div class="table-title">Son Bakımlar</div></div>
        </div>
        <table>
          <thead><tr>
            <th>Plaka</th><th>Araç</th><th>Hizmet</th><th>Personel</th><th>Tarih</th><th>Ücret</th>
          </tr></thead>
          <tbody id="dash-bakim-tbody"><tr><td colspan="6" class="loading">Yükleniyor...</td></tr></tbody>
        </table>
      </div>
    </div>

    <!-- MÜŞTERİLER -->
    <div class="admin-page" id="page-musteriler">
      <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between">
        <div><div class="page-title">Müşteriler</div><div class="page-sub">Tüm müşteri kayıtları</div></div>
        <button class="add-btn" onclick="openModal('musteri')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Müşteri Ekle
        </button>
      </div>
      <div class="table-card">
        <div class="table-header"><div class="table-title">Müşteri Listesi</div><div class="table-count" id="musteri-count"></div></div>
        <table>
          <thead><tr><th>Ad Soyad</th><th>Telefon</th><th>E-posta</th><th>Araç Sayısı</th><th>İşlem</th></tr></thead>
          <tbody id="musteri-tbody"><tr><td colspan="5" class="loading">Yükleniyor...</td></tr></tbody>
        </table>
      </div>
    </div>

    <!-- ARAÇLAR -->
    <div class="admin-page" id="page-araclar">
      <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between">
        <div><div class="page-title">Araçlar</div><div class="page-sub">Kayıtlı tüm araçlar</div></div>
        <button class="add-btn" onclick="openModal('arac')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Araç Ekle
        </button>
      </div>
      <div class="table-card">
        <div class="table-header"><div class="table-title">Araç Listesi</div><div class="table-count" id="arac-count"></div></div>
        <table>
          <thead><tr><th>Plaka</th><th>Marka/Model</th><th>Yıl</th><th>Şasi No</th><th>Müşteri</th><th>İşlem</th></tr></thead>
          <tbody id="arac-tbody"><tr><td colspan="6" class="loading">Yükleniyor...</td></tr></tbody>
        </table>
      </div>
    </div>

    <!-- BAKIM KAYITLARI -->
    <div class="admin-page" id="page-bakimlar">
      <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between">
        <div><div class="page-title">Bakım Kayıtları</div><div class="page-sub">Tüm bakım ve servis işlemleri</div></div>
        <button class="add-btn" onclick="openModal('bakim')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Bakım Ekle
        </button>
      </div>
      <div class="table-card">
        <div class="table-header"><div class="table-title">Bakım Listesi</div><div class="table-count" id="bakim-count"></div></div>
        <table>
          <thead><tr><th>Plaka</th><th>Araç</th><th>Müşteri</th><th>Hizmet</th><th>Personel</th><th>Giriş</th><th>Çıkış</th><th>Ücret</th><th>İşlem</th></tr></thead>
          <tbody id="bakim-tbody"><tr><td colspan="9" class="loading">Yükleniyor...</td></tr></tbody>
        </table>
      </div>
    </div>

    <!-- PERSONEL -->
    <div class="admin-page" id="page-personel">
      <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between">
        <div><div class="page-title">Personel</div><div class="page-sub">Servis çalışanları</div></div>
        <button class="add-btn" onclick="openModal('personel')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Personel Ekle
        </button>
      </div>
      <div class="table-card">
        <div class="table-header"><div class="table-title">Personel Listesi</div></div>
        <table>
          <thead><tr><th>ID</th><th>Ad Soyad</th><th>Uzmanlık Alanı</th><th>İşlem</th></tr></thead>
          <tbody id="personel-tbody"><tr><td colspan="4" class="loading">Yükleniyor...</td></tr></tbody>
        </table>
      </div>
    </div>

    <!-- HİZMETLER -->
    <div class="admin-page" id="page-hizmetler">
      <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between">
        <div><div class="page-title">Hizmetler</div><div class="page-sub">Sunulan servis hizmetleri ve fiyatlar</div></div>
        <button class="add-btn" onclick="openModal('hizmet')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Hizmet Ekle
        </button>
      </div>
      <div class="table-card">
        <div class="table-header"><div class="table-title">Hizmet Listesi</div></div>
        <table>
          <thead><tr><th>ID</th><th>Hizmet Adı</th><th>Standart Ücret</th><th>İşlem</th></tr></thead>
          <tbody id="hizmet-tbody"><tr><td colspan="4" class="loading">Yükleniyor...</td></tr></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- ===== MÜŞTERİ PANEL ===== -->
<div id="musteri-screen">
  <div class="musteri-header">
    <div class="musteri-brand">
      <div class="musteri-brand-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="#0a0a0f" stroke-width="2" stroke-linecap="round"><path d="M14 16H9m10 0h3v-3.15a1 1 0 00-.84-.99L16 11l-2.7-3.6a1 1 0 00-.8-.4H5.24a2 2 0 00-1.8 1.1L2 11v5h2"/><circle cx="6.5" cy="16.5" r="2.5"/><circle cx="16.5" cy="16.5" r="2.5"/></svg>
      </div>
      <div class="musteri-brand-name">SeeAuto</div>
    </div>
    <div class="musteri-header-right">
      <div class="musteri-user-info">
        <div class="musteri-user-name" id="m-username">—</div>
        <div class="musteri-user-role">Müşteri</div>
      </div>
      <button class="musteri-logout" onclick="doLogout()">Çıkış</button>
    </div>
  </div>
  <div class="musteri-nav">
    <div class="nav-item active" onclick="musteriPage('m-araclarim',this)">Araçlarım</div>
    <div class="nav-item" onclick="musteriPage('m-bakimlarim',this)">Bakım Geçmişim</div>
    <div class="nav-item" onclick="musteriPage('m-randevu',this)">Randevu Al</div>
  </div>
  <div class="musteri-content">
    <!-- ARAÇLARIM -->
    <div id="m-araclarim">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
        <div class="page-title" style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800">Araçlarım</div>
        <button class="add-btn" onclick="openModal('arac-m')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Araç Ekle
        </button>
      </div>
      <div class="arac-grid" id="m-arac-grid"><div class="loading">Yükleniyor...</div></div>
    </div>

    <!-- BAKIMLARIM -->
    <div id="m-bakimlarim" style="display:none">
      <div class="page-title" style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;margin-bottom:16px">Bakım Geçmişim</div>
      <div class="table-card">
        <div class="table-header"><div class="table-title">Bakımlarım</div></div>
        <div id="m-bakim-list"><div class="loading">Yükleniyor...</div></div>
      </div>
    </div>

    <!-- RANDEVU -->
    <div id="m-randevu" style="display:none">
      <div class="page-title" style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;margin-bottom:16px">Randevu Al</div>
      <div class="rdv-form">
        <div class="rdv-form-title">Yeni Randevu Talebi</div>
        <div class="rdv-grid">
          <div>
            <label class="form-label">Araç Seçin</label>
            <select class="form-input" id="rdv-arac"><option value="">Araç yükleniyor...</option></select>
          </div>
          <div>
            <label class="form-label">Hizmet Seçin</label>
            <select class="form-input" id="rdv-hizmet"><option value="">Hizmet seçin</option></select>
          </div>
          <div>
            <label class="form-label">Tercih Ettiğiniz Tarih</label>
            <input type="date" class="form-input" id="rdv-tarih">
          </div>
          <div>
            <label class="form-label">Saat</label>
            <select class="form-input" id="rdv-saat">
              <option>09:00</option><option>10:00</option><option>11:00</option>
              <option>13:00</option><option>14:00</option><option>15:00</option><option>16:00</option>
            </select>
          </div>
          <div class="full">
            <label class="form-label">Notunuz (opsiyonel)</label>
            <textarea class="form-input" id="rdv-not" rows="3" placeholder="Araçla ilgili ek bilgi verebilirsiniz..."></textarea>
          </div>
        </div>
        <button class="rdv-submit" onclick="randevuGonder()">Randevu Gönder</button>
      </div>

      <div class="table-card">
        <div class="table-header"><div class="table-title">Randevu Taleplerim</div></div>
        <div id="m-rdv-list"><div class="loading">Burada randevularınız görünecek (bu versiyon DB randevu tablosu gerektirmez).</div></div>
      </div>
    </div>
  </div>
</div>

<!-- ===== MODALLER ===== -->

<!-- Müşteri Ekle -->
<div class="modal-overlay" id="modal-musteri">
  <div class="modal-box">
    <div class="modal-title">Müşteri Ekle</div>
    <div class="modal-grid">
      <div><label class="form-label">Ad</label><input class="form-input" id="m-ad" placeholder="Ahmet"></div>
      <div><label class="form-label">Soyad</label><input class="form-input" id="m-soyad" placeholder="Yılmaz"></div>
      <div><label class="form-label">Telefon</label><input class="form-input" id="m-tel" placeholder="0532..."></div>
      <div><label class="form-label">E-posta</label><input class="form-input" id="m-mail" placeholder="ornek@mail.com"></div>
      <div class="full"><label class="form-label">Adres</label><textarea class="form-input" id="m-adres" placeholder="Mahalle, sokak, no..."></textarea></div>
    </div>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeModal('modal-musteri')">İptal</button>
      <button class="btn-save" onclick="musteriEkle()">Kaydet</button>
    </div>
  </div>
</div>

<!-- Araç Ekle (Admin) -->
<div class="modal-overlay" id="modal-arac">
  <div class="modal-box">
    <div class="modal-title">Araç Ekle</div>
    <div class="modal-grid">
      <div><label class="form-label">Plaka</label><input class="form-input" id="a-plaka" placeholder="34 AB 1234"></div>
      <div><label class="form-label">Marka</label><input class="form-input" id="a-marka" placeholder="Toyota"></div>
      <div><label class="form-label">Model</label><input class="form-input" id="a-model" placeholder="Corolla"></div>
      <div><label class="form-label">Yıl</label><input class="form-input" id="a-yil" type="number" placeholder="2024" min="1980" max="2030"></div>
      <div><label class="form-label">Şasi No</label><input class="form-input" id="a-sasi" placeholder="WBA..."></div>
      <div>
        <label class="form-label">Müşteri</label>
        <select class="form-input" id="a-musteri"><option value="">Seçin...</option></select>
      </div>
    </div>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeModal('modal-arac')">İptal</button>
      <button class="btn-save" onclick="aracEkleAdmin()">Kaydet</button>
    </div>
  </div>
</div>

<!-- Araç Ekle (Müşteri) -->
<div class="modal-overlay" id="modal-arac-m">
  <div class="modal-box">
    <div class="modal-title">Araç Ekle</div>
    <div class="modal-grid">
      <div><label class="form-label">Plaka</label><input class="form-input" id="am-plaka" placeholder="34 AB 1234"></div>
      <div><label class="form-label">Marka</label><input class="form-input" id="am-marka" placeholder="Toyota"></div>
      <div><label class="form-label">Model</label><input class="form-input" id="am-model" placeholder="Corolla"></div>
      <div><label class="form-label">Yıl</label><input class="form-input" id="am-yil" type="number" placeholder="2024"></div>
      <div class="full"><label class="form-label">Şasi No</label><input class="form-input" id="am-sasi" placeholder="WBA..."></div>
    </div>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeModal('modal-arac-m')">İptal</button>
      <button class="btn-save blue" onclick="aracEkleMusteri()">Kaydet</button>
    </div>
  </div>
</div>

<!-- Bakım Ekle -->
<div class="modal-overlay" id="modal-bakim">
  <div class="modal-box">
    <div class="modal-title">Bakım Kaydı Ekle</div>
    <div class="modal-grid">
      <div>
        <label class="form-label">Araç</label>
        <select class="form-input" id="b-arac"><option value="">Seçin...</option></select>
      </div>
      <div>
        <label class="form-label">Hizmet</label>
        <select class="form-input" id="b-hizmet"><option value="">Seçin...</option></select>
      </div>
      <div>
        <label class="form-label">Personel</label>
        <select class="form-input" id="b-personel"><option value="">Seçin...</option></select>
      </div>
      <div><label class="form-label">Toplam Ücret (₺)</label><input class="form-input" id="b-ucret" type="number" placeholder="0.00"></div>
      <div><label class="form-label">Giriş Tarihi</label><input class="form-input" id="b-gelis" type="datetime-local"></div>
      <div><label class="form-label">Çıkış Tarihi</label><input class="form-input" id="b-cikis" type="datetime-local"></div>
      <div class="full"><label class="form-label">Yapılan Detaylar</label><textarea class="form-input" id="b-detay" rows="3" placeholder="Yapılan işlemler..."></textarea></div>
    </div>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeModal('modal-bakim')">İptal</button>
      <button class="btn-save" onclick="bakimEkle()">Kaydet</button>
    </div>
  </div>
</div>

<!-- Personel Ekle -->
<div class="modal-overlay" id="modal-personel">
  <div class="modal-box">
    <div class="modal-title">Personel Ekle</div>
    <div class="modal-grid">
      <div class="full"><label class="form-label">Ad Soyad</label><input class="form-input" id="p-adsoyad" placeholder="Ali Veli"></div>
      <div class="full"><label class="form-label">Uzmanlık Alanı</label><input class="form-input" id="p-uzmanlik" placeholder="Motor Mekanik"></div>
    </div>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeModal('modal-personel')">İptal</button>
      <button class="btn-save" onclick="personelEkle()">Kaydet</button>
    </div>
  </div>
</div>

<!-- Hizmet Ekle -->
<div class="modal-overlay" id="modal-hizmet">
  <div class="modal-box">
    <div class="modal-title">Hizmet Ekle</div>
    <div class="modal-grid">
      <div class="full"><label class="form-label">Hizmet Adı</label><input class="form-input" id="h-adi" placeholder="Periyodik Bakım"></div>
      <div class="full"><label class="form-label">Standart Ücret (₺)</label><input class="form-input" id="h-ucret" type="number" placeholder="6000"></div>
    </div>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeModal('modal-hizmet')">İptal</button>
      <button class="btn-save" onclick="hizmetEkle()">Kaydet</button>
    </div>
  </div>
</div>

<div id="toast"></div>

<script>
// ===== GLOBAL =====
let currentRol = '';
let currentUID = 0;
let currentName = '';

// ===== EKRAN YÖNETİMİ =====
function showScreen(id) {
  ['role-screen','login-screen','admin-screen','musteri-screen'].forEach(s => {
    const el = document.getElementById(s);
    el.classList.remove('active');
  });
  document.getElementById(id).classList.add('active');
}

function selectRole(rol) {
  currentRol = rol;
  document.getElementById('login-screen').classList.add('active');
  document.getElementById('role-screen').classList.remove('active');

  const badge = document.getElementById('login-rol-badge');
  badge.style.display = 'inline-flex';
  const hint = document.getElementById('login-hint');

  if (rol === 'admin') {
    badge.className = 'login-rol-badge admin';
    badge.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M12 2L3 7l9 5 9-5-9-5z"/></svg> Admin Girişi';
    document.getElementById('label-giris').textContent = 'Kullanıcı Adı';
    document.getElementById('inp-giris').placeholder = 'admin';
    document.getElementById('label-sifre').textContent = 'Şifre';
    document.getElementById('login-form-sub').textContent = 'Admin bilgilerinizi girin';
    document.getElementById('login-btn').className = 'login-btn';
    hint.style.display = 'block';
    hint.innerHTML = '<strong>Test:</strong> Kullanıcı: <code>admin</code> / Şifre: <code>admin123</code>';
  } else {
    badge.className = 'login-rol-badge musteri';
    badge.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Müşteri Girişi';
    document.getElementById('label-giris').textContent = 'E-posta Adresi';
    document.getElementById('inp-giris').placeholder = 'ornek@mail.com';
    document.getElementById('label-sifre').textContent = 'Şifre';
    document.getElementById('login-form-sub').textContent = 'E-posta ve şifrenizle giriş yapın';
    document.getElementById('login-btn').className = 'login-btn musteri-btn';
    hint.style.display = 'none';
  }
  document.getElementById('inp-giris').value = '';
  document.getElementById('inp-sifre').value = '';
  document.getElementById('login-error').style.display = 'none';
  loadLoginStats();
  // Bu kodu 792. satırdaki loadLoginStats(); altına yapıştır
document.getElementById('register-link-area').style.display = (rol === 'musteri' ? 'block' : 'none');
}

function goBack() {
  document.getElementById('login-screen').classList.remove('active');
  document.getElementById('role-screen').classList.add('active');
}

// ===== STATS LOGIN SAYFASI =====
async function loadLoginStats() {
  try {
    // Veritabanından sayıları getiren isteği gönderiyoruz
    const data = await api({ action: 'dashboard_stats' });
    
    if (data) {
      // Çizgileri veritabanından gelen gerçek sayılarla güncelliyoruz
      document.getElementById('stat-arac').innerText = data.arac_sayisi || 0;
      document.getElementById('stat-musteri').innerText = data.musteri_sayisi || 0;
      document.getElementById('stat-bakim').innerText = data.bakim_sayisi || 0;
    }
  } catch (error) {
    console.error("İstatistikler yüklenirken bir hata oluştu:", error);
  }
}

// ===== API YARDIMCI =====
async function api(params) {
  const fd = new FormData();
  Object.keys(params).forEach(k => fd.append(k, params[k]));
  const res = await fetch('api.php', { method: 'POST', body: fd });
  return res.json();
}

async function auth(params) {
  const fd = new FormData();
  Object.keys(params).forEach(k => fd.append(k, params[k]));
  const res = await fetch('auth.php', { method: 'POST', body: fd });
  return res.json();
}

// ===== GİRİŞ =====
async function doLogin() {
  const giris = document.getElementById('inp-giris').value.trim();
  const sifre = document.getElementById('inp-sifre').value.trim();
  const errEl = document.getElementById('login-error');
  errEl.style.display = 'none';

  if (!giris || !sifre) { showError(errEl, 'Lütfen tüm alanları doldurun.'); return; }

  const data = await auth({ action: 'login', rol: currentRol, giris, sifre });
  if (data.success) {
    currentUID = data.id || 0;
    currentName = data.name;
    if (data.rol === 'admin') {
      showScreen('admin-screen');
      loadDashboard();
    } else {
      document.getElementById('m-username').textContent = currentName;
      showScreen('musteri-screen');
      loadMusteriAraclar();
      loadMusteriBakimlar();
      loadRdvForm();
    }
  } else {
    showError(errEl, data.message || 'Giriş başarısız.');
  }
}

function showError(el, msg) {
  el.textContent = msg;
  el.style.display = 'block';
}

async function doLogout() {
  await auth({ action: 'logout' });
  location.reload();
}

// ===== ADMIN SAYFALAR =====
function adminPage(page, el) {
  document.querySelectorAll('.admin-page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.admin-sidebar .nav-item').forEach(n => n.classList.remove('active'));
  document.getElementById('page-' + page).classList.add('active');
  if (el) el.classList.add('active');

  if (page === 'dashboard') loadDashboard();
  else if (page === 'musteriler') loadMusteriler();
  else if (page === 'araclar') loadAraclar();
  else if (page === 'bakimlar') loadBakimlar();
  else if (page === 'personel') loadPersonel();
  else if (page === 'hizmetler') loadHizmetler();
}

// ===== DASHBOARD =====
async function loadDashboard() {
  const data = await api({ action: 'dashboard_stats' });
  document.getElementById('d-arac').textContent = data.arac_sayisi || 0;
  document.getElementById('d-musteri').textContent = data.musteri_sayisi || 0;
  document.getElementById('d-bakim').textContent = data.bakim_sayisi || 0;
  document.getElementById('d-gelir').textContent = formatUcret(data.gelir || 0);

  const tbody = document.getElementById('dash-bakim-tbody');
  if (!data.son_bakimlar || data.son_bakimlar.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" class="empty-row">Bakım kaydı yok.</td></tr>';
    return;
  }
  tbody.innerHTML = data.son_bakimlar.map(b => `
    <tr>
      <td><span class="plaka-badge">${b.Plaka}</span></td>
      <td>${b.AracAdi}</td>
      <td>${b.HizmetAdi}</td>
      <td>${b.Personel}</td>
      <td>${b.GelisTarihi}</td>
      <td class="ucret-badge">${formatUcret(b.ToplamUcret)}</td>
    </tr>
  `).join('');
}

// ===== MÜŞTERİLER =====
async function loadMusteriler() {
  const data = await api({ action: 'musteriler_list' });
  const tbody = document.getElementById('musteri-tbody');
  document.getElementById('musteri-count').textContent = data.length + ' kayıt';
  if (!data.length) { tbody.innerHTML = '<tr><td colspan="5" class="empty-row">Müşteri bulunamadı.</td></tr>'; return; }
  tbody.innerHTML = data.map(m => `
    <tr>
      <td>${m.Ad} ${m.Soyad}</td>
      <td>${m.Telefon}</td>
      <td>${m.Mail}</td>
      <td>${m.AracSayisi}</td>
      <td><button class="del-btn" onclick="musteriSil(${m.MusteriID})">Sil</button></td>
    </tr>
  `).join('');
}

async function musteriEkle() {
  const data = await api({
    action: 'musteri_ekle',
    ad: document.getElementById('m-ad').value,
    soyad: document.getElementById('m-soyad').value,
    telefon: document.getElementById('m-tel').value,
    mail: document.getElementById('m-mail').value,
    adres: document.getElementById('m-adres').value
  });
  if (data.success) { closeModal('modal-musteri'); toast('✓ Müşteri eklendi!'); loadMusteriler(); }
  else toast('Hata oluştu!', true);
}

async function musteriSil(id) {
  if (!confirm('Bu müşteriyi silmek istiyor musunuz?')) return;
  const data = await api({ action: 'musteri_sil', id });
  if (data.success) { toast('✓ Müşteri silindi.'); loadMusteriler(); }
  else toast('Silinemedi! Araç kaydı olabilir.', true);
}

// ===== ARAÇLAR =====
let cachedMusteriler = [];
let cachedAraclar = [];

async function loadAraclar() {
  const [aracData, musteriData] = await Promise.all([
    api({ action: 'araclar_list' }),
    api({ action: 'musteriler_list' })
  ]);
  cachedAraclar = aracData;
  cachedMusteriler = musteriData;
  const tbody = document.getElementById('arac-tbody');
  document.getElementById('arac-count').textContent = aracData.length + ' araç';
  if (!aracData.length) { tbody.innerHTML = '<tr><td colspan="6" class="empty-row">Araç bulunamadı.</td></tr>'; return; }
  tbody.innerHTML = aracData.map(a => `
    <tr>
      <td><span class="plaka-badge">${a.Plaka}</span></td>
      <td>${a.Marka} ${a.Model}</td>
      <td>${a.Yil}</td>
      <td style="font-size:11px;color:var(--c-muted)">${a.SasiNo}</td>
      <td>${a.MusteriAdi}</td>
      <td><button class="del-btn" onclick="aracSil(${a.AracID})">Sil</button></td>
    </tr>
  `).join('');
}

async function aracEkleAdmin() {
  const data = await api({
    action: 'arac_ekle',
    plaka: document.getElementById('a-plaka').value,
    marka: document.getElementById('a-marka').value,
    model: document.getElementById('a-model').value,
    yil: document.getElementById('a-yil').value,
    sasi_no: document.getElementById('a-sasi').value,
    musteri_id: document.getElementById('a-musteri').value
  });
  if (data.success) { closeModal('modal-arac'); toast('✓ Araç eklendi!'); loadAraclar(); }
  else toast('Hata oluştu!', true);
}

async function aracSil(id) {
  if (!confirm('Bu aracı silmek istiyor musunuz?')) return;
  const data = await api({ action: 'arac_sil', id });
  if (data.success) { toast('✓ Araç silindi.'); loadAraclar(); }
  else toast('Silinemedi!', true);
}

// ===== BAKIM KAYITLARI =====
let cachedHizmetler = [];
let cachedPersonel = [];

async function loadBakimlar() {
  const data = await api({ action: 'bakimlar_list' });
  const tbody = document.getElementById('bakim-tbody');
  document.getElementById('bakim-count').textContent = data.length + ' kayıt';
  if (!data.length) { tbody.innerHTML = '<tr><td colspan="9" class="empty-row">Bakım kaydı yok.</td></tr>'; return; }
  tbody.innerHTML = data.map(b => `
    <tr>
      <td><span class="plaka-badge">${b.Plaka}</span></td>
      <td>${b.AracAdi}</td>
      <td>${b.MusteriAdi}</td>
      <td>${b.HizmetAdi}</td>
      <td>${b.Personel}</td>
      <td style="font-size:12px">${b.GelisTarihi}</td>
      <td style="font-size:12px">${b.CikisTarihi}</td>
      <td class="ucret-badge">${formatUcret(b.ToplamUcret)}</td>
      <td><button class="del-btn" onclick="bakimSil(${b.KayitID})">Sil</button></td>
    </tr>
  `).join('');
}

async function bakimEkle() {
  const data = await api({
    action: 'bakim_ekle',
    arac_id: document.getElementById('b-arac').value,
    hizmet_id: document.getElementById('b-hizmet').value,
    personel_id: document.getElementById('b-personel').value,
    ucret: document.getElementById('b-ucret').value,
    gelis_tarihi: document.getElementById('b-gelis').value,
    cikis_tarihi: document.getElementById('b-cikis').value,
    detay: document.getElementById('b-detay').value
  });
  if (data.success) { closeModal('modal-bakim'); toast('✓ Bakım kaydı eklendi!'); loadBakimlar(); }
  else toast('Hata oluştu!', true);
}

async function bakimSil(id) {
  if (!confirm('Bu bakım kaydını silmek istiyor musunuz?')) return;
  const data = await api({ action: 'bakim_sil', id });
  if (data.success) { toast('✓ Kayıt silindi.'); loadBakimlar(); }
  else toast('Silinemedi!', true);
}

// ===== PERSONEL =====
async function loadPersonel() {
  const data = await api({ action: 'personel_list' });
  cachedPersonel = data;
  const tbody = document.getElementById('personel-tbody');
  if (!data.length) { tbody.innerHTML = '<tr><td colspan="4" class="empty-row">Personel bulunamadı.</td></tr>'; return; }
  tbody.innerHTML = data.map(p => `
    <tr>
      <td style="color:var(--c-muted)">#${p.PersonelID}</td>
      <td>${p.AdSoyad}</td>
      <td>${p.UzmanlikAlani}</td>
      <td><button class="del-btn" onclick="personelSil(${p.PersonelID})">Sil</button></td>
    </tr>
  `).join('');
}

async function personelEkle() {
  const data = await api({
    action: 'personel_ekle',
    adsoyad: document.getElementById('p-adsoyad').value,
    uzmanlik: document.getElementById('p-uzmanlik').value
  });
  if (data.success) { closeModal('modal-personel'); toast('✓ Personel eklendi!'); loadPersonel(); }
  else toast('Hata oluştu!', true);
}

async function personelSil(id) {
  if (!confirm('Bu personeli silmek istiyor musunuz?')) return;
  const data = await api({ action: 'personel_sil', id });
  if (data.success) { toast('✓ Personel silindi.'); loadPersonel(); }
  else toast('Silinemedi! Bakım kaydı olabilir.', true);
}

// ===== HİZMETLER =====
async function loadHizmetler() {
  const data = await api({ action: 'hizmetler_list' });
  cachedHizmetler = data;
  const tbody = document.getElementById('hizmet-tbody');
  if (!data.length) { tbody.innerHTML = '<tr><td colspan="4" class="empty-row">Hizmet bulunamadı.</td></tr>'; return; }
  tbody.innerHTML = data.map(h => `
    <tr>
      <td style="color:var(--c-muted)">#${h.HizmetID}</td>
      <td>${h.HizmetAdi}</td>
      <td class="ucret-badge">${formatUcret(h.StandartUcret)}</td>
      <td><button class="del-btn" onclick="hizmetSil(${h.HizmetID})">Sil</button></td>
    </tr>
  `).join('');
}

async function hizmetEkle() {
  const data = await api({
    action: 'hizmet_ekle',
    adi: document.getElementById('h-adi').value,
    ucret: document.getElementById('h-ucret').value
  });
  if (data.success) { closeModal('modal-hizmet'); toast('✓ Hizmet eklendi!'); loadHizmetler(); }
  else toast('Hata oluştu!', true);
}

async function hizmetSil(id) {
  if (!confirm('Bu hizmeti silmek istiyor musunuz?')) return;
  const data = await api({ action: 'hizmet_sil', id });
  if (data.success) { toast('✓ Hizmet silindi.'); loadHizmetler(); }
  else toast('Silinemedi!', true);
}

// ===== MÜŞTERİ PANELİ =====
function musteriPage(page, el) {
  ['m-araclarim','m-bakimlarim','m-randevu'].forEach(p => {
    document.getElementById(p).style.display = p === page ? 'block' : 'none';
  });
  document.querySelectorAll('.musteri-nav .nav-item').forEach(n => n.classList.remove('active'));
  if (el) el.classList.add('active');
}

async function loadMusteriAraclar() {
  const data = await api({ action: 'araclar_list' });
  const grid = document.getElementById('m-arac-grid');
  if (!data.length) { grid.innerHTML = '<div style="color:var(--c-muted);font-size:13px;padding:16px">Henüz araç eklemediniz.</div>'; return; }
  grid.innerHTML = data.map(a => `
    <div class="my-arac-card">
      <div class="my-arac-plaka">${a.Plaka}</div>
      <div class="my-arac-model">${a.Marka} ${a.Model}</div>
      <div class="my-arac-yil">${a.Yil} · ${a.SasiNo}</div>
      <div class="my-arac-status s-active"><div class="status-dot"></div><div class="status-lbl">Aktif</div></div>
    </div>
  `).join('');
}

async function loadMusteriBakimlar() {
  const data = await api({ action: 'bakimlar_list' });
  const list = document.getElementById('m-bakim-list');
  if (!data.length) { list.innerHTML = '<div class="loading">Henüz bakım kaydınız yok.</div>'; return; }
  list.innerHTML = data.map(b => `
    <div class="bakim-musteri-row">
      <div class="bakim-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke="var(--c-gold)">
          <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
        </svg>
      </div>
      <div class="bakim-musteri-info">
        <div class="bakim-musteri-hizmet">${b.HizmetAdi}</div>
        <div class="bakim-musteri-detail">${b.Plaka} · ${b.AracAdi} · ${b.GelisTarihi}</div>
      </div>
      <div style="font-size:13px;font-weight:600;color:var(--c-gold);margin-right:16px">${formatUcret(b.ToplamUcret)}</div>
      <div class="my-arac-status s-active"><div class="status-dot"></div><div class="status-lbl">Tamamlandı</div></div>
    </div>
  `).join('');
}

async function loadRdvForm() {
  const [aracData, hizmetData] = await Promise.all([
    api({ action: 'araclar_list' }),
    api({ action: 'hizmetler_list' })
  ]);
  const aracSel = document.getElementById('rdv-arac');
  aracSel.innerHTML = '<option value="">— Araç seçin —</option>' +
    aracData.map(a => `<option value="${a.AracID}">${a.Plaka} — ${a.Marka} ${a.Model}</option>`).join('');
  const hizSel = document.getElementById('rdv-hizmet');
  hizSel.innerHTML = '<option value="">— Hizmet seçin —</option>' +
    hizmetData.map(h => `<option value="${h.HizmetID}">${h.HizmetAdi} (${formatUcret(h.StandartUcret)})</option>`).join('');
  document.getElementById('rdv-tarih').value = new Date().toISOString().split('T')[0];
}

async function aracEkleMusteri() {
  const data = await api({
    action: 'arac_ekle',
    plaka: document.getElementById('am-plaka').value,
    marka: document.getElementById('am-marka').value,
    model: document.getElementById('am-model').value,
    yil: document.getElementById('am-yil').value,
    sasi_no: document.getElementById('am-sasi').value
  });
  if (data.success) { closeModal('modal-arac-m'); toast('✓ Araç eklendi!'); loadMusteriAraclar(); loadRdvForm(); }
  else toast('Hata oluştu!', true);
}

function randevuGonder() {
  const arac = document.getElementById('rdv-arac').value;
  const hizmet = document.getElementById('rdv-hizmet').value;
  const tarih = document.getElementById('rdv-tarih').value;
  if (!arac || !hizmet || !tarih) { toast('Lütfen araç, hizmet ve tarih seçin.', true); return; }
  toast('✓ Randevu talebiniz gönderildi! Admin onaylayacak.');
  document.getElementById('m-rdv-list').innerHTML = '<div style="padding:16px;font-size:13px;color:var(--c-muted)">Randevu talebiniz admin onayına gönderildi.</div>';
}

// ===== MODAL =====
async function openModal(type) {
  if (type === 'arac') {
    // Müşteri listesini doldur
    if (!cachedMusteriler.length) {
      const data = await api({ action: 'musteriler_list' });
      cachedMusteriler = data;
    }
    const sel = document.getElementById('a-musteri');
    sel.innerHTML = '<option value="">— Müşteri seçin —</option>' +
      cachedMusteriler.map(m => `<option value="${m.MusteriID}">${m.Ad} ${m.Soyad}</option>`).join('');
  }
  if (type === 'bakim') {
    const [aracData, hizmetData, personelData] = await Promise.all([
      api({ action: 'araclar_list' }),
      api({ action: 'hizmetler_list' }),
      api({ action: 'personel_list' })
    ]);
    document.getElementById('b-arac').innerHTML = '<option value="">Seçin...</option>' +
      aracData.map(a => `<option value="${a.AracID}">${a.Plaka} — ${a.Marka} ${a.Model}</option>`).join('');
    document.getElementById('b-hizmet').innerHTML = '<option value="">Seçin...</option>' +
      hizmetData.map(h => `<option value="${h.HizmetID}">${h.HizmetAdi}</option>`).join('');
    document.getElementById('b-personel').innerHTML = '<option value="">Seçin...</option>' +
      personelData.map(p => `<option value="${p.PersonelID}">${p.AdSoyad}</option>`).join('');
    const now = new Date().toISOString().slice(0,16);
    document.getElementById('b-gelis').value = now;
    document.getElementById('b-cikis').value = now;
  }
  document.getElementById('modal-' + type).classList.add('open');
}

function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}

// Overlay dışına tıklama ile kapat
document.querySelectorAll('.modal-overlay').forEach(el => {
  el.addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('open');
  });
});

// ===== YARDIMCI =====
function formatUcret(val) {
  if (val === null || val === undefined) return '—';
  return parseFloat(val).toLocaleString('tr-TR', { style: 'currency', currency: 'TRY' });
}

function toast(msg, isError = false) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.background = isError ? 'var(--c-red)' : 'var(--c-green)';
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3000);
}
// Üye Ol yazısına tıklanınca modalı açar
function showRegister() {
    if (typeof openModal === 'function') {
        openModal('musteri'); 
        const modalTitle = document.querySelector('#modal-musteri .modal-title');
        if (modalTitle) modalTitle.innerText = "Üye Kayıt Formu";
        
        const saveBtn = document.querySelector('#modal-musteri .btn-save');
        if (saveBtn) saveBtn.setAttribute('onclick', 'doRegister()');
    }
}

// Kayıt verilerini auth.php'ye gönderir
async function doRegister() {
    try {
        const data = await auth({
            action: 'register',
            ad: document.getElementById('m-ad').value,
            soyad: document.getElementById('m-soyad').value,
            telefon: document.getElementById('m-tel').value,
            mail: document.getElementById('m-mail').value,
            adres: document.getElementById('m-adres').value
        });

        if (data.success) {
            closeModal('modal-musteri');
            toast('✓ Kayıt başarılı! Giriş yapabilirsiniz.');
        } else {
            toast('Hata: ' + (data.message || 'Kayıt yapılamadı'), true);
        }
    } catch (e) {
        toast('Sistem hatası oluştu!', true);
    }
}

</script>
</body>
</html>
