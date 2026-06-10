<?php
$css = file_get_contents('public/css/premium.css');
$css = str_replace(
    ['#eff6ff', 'background: white !important', 'border-color: #d1d5db !important', '#f8fafc', '#e2e8f0', '#ffffff', '#1e293b'],
    ['rgba(99,102,241,0.1)', 'background: transparent !important', 'border-color: var(--card-border) !important', 'var(--topbar-bg)', 'var(--card-border)', 'var(--topbar-bg)', 'var(--topbar-bg)'],
    $css
);
file_put_contents('public/css/premium.css', $css);
echo "CSS Updated.";
