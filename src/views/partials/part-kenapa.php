<?php
$_khb_stmt  = $pdo->query("SELECT * FROM admin_items WHERE status = 'active' AND category IN ('trending') ORDER BY id DESC");
$_khb_items = $_khb_stmt->fetchAll(PDO::FETCH_ASSOC);

$_khb_icons = [

'Udara Sejuk Menenangkan' => '
<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
  <!-- Awan utama -->
  <path d="M16 40 Q10 40 10 34 Q10 28 16 27 Q17 20 24 19 Q28 14 34 17 Q40 14 44 19 Q50 19 52 25 Q56 26 56 32 Q56 38 50 40 Z"
        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="color-mix(in srgb, currentColor 8%, transparent)">
    <animateTransform attributeName="transform" type="translate" values="0,0;2,0;0,0" dur="4s" repeatCount="indefinite" calcMode="spline" keySplines="0.45 0 0.55 1;0.45 0 0.55 1"/>
  </path>
  <!-- Garis angin 1 -->
  <path d="M10 48 Q18 48 26 48" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
    <animate attributeName="stroke-dasharray" values="0,30;30,0;0,30" dur="2.5s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
    <animate attributeName="opacity" values="0.3;0.8;0.3" dur="2.5s" repeatCount="indefinite"/>
  </path>
  <!-- Garis angin 2 -->
  <path d="M8 54 Q20 54 32 54" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
    <animate attributeName="stroke-dasharray" values="0,40;40,0;0,40" dur="2.5s" begin="0.4s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
    <animate attributeName="opacity" values="0.2;0.6;0.2" dur="2.5s" begin="0.4s" repeatCount="indefinite"/>
  </path>
  <!-- Garis angin 3 -->
  <path d="M14 58 Q22 58 28 58" stroke="currentColor" stroke-width="2" stroke-linecap="round">
    <animate attributeName="stroke-dasharray" values="0,24;24,0;0,24" dur="2.5s" begin="0.8s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
    <animate attributeName="opacity" values="0.15;0.5;0.15" dur="2.5s" begin="0.8s" repeatCount="indefinite"/>
  </path>
  <!-- Bintik embun -->
  <circle cx="36" cy="50" r="2" fill="currentColor" opacity="0.3">
    <animate attributeName="cy" values="50;56;50" dur="3s" begin="0.2s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
    <animate attributeName="opacity" values="0.3;0;0.3" dur="3s" begin="0.2s" repeatCount="indefinite"/>
  </circle>
  <circle cx="44" cy="52" r="1.5" fill="currentColor" opacity="0.3">
    <animate attributeName="cy" values="52;58;52" dur="3s" begin="0.7s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
    <animate attributeName="opacity" values="0.3;0;0.3" dur="3s" begin="0.7s" repeatCount="indefinite"/>
  </circle>
  <circle cx="52" cy="48" r="1.5" fill="currentColor" opacity="0.3">
    <animate attributeName="cy" values="48;54;48" dur="3s" begin="1.1s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
    <animate attributeName="opacity" values="0.3;0;0.3" dur="3s" begin="1.1s" repeatCount="indefinite"/>
  </circle>
</svg>',

'Event Seni Internasional' => '
<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
  <!-- Bingkai kalender -->
  <rect x="8" y="14" width="48" height="42" rx="8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="color-mix(in srgb, currentColor 6%, transparent)"/>
  <!-- Garis header -->
  <path d="M8 26 Q8 22 12 22 L52 22 Q56 22 56 26" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".4"/>
  <!-- Pin kiri -->
  <path d="M20 8 Q20 6 22 6 Q24 6 24 8 L24 16 Q24 18 22 18 Q20 18 20 16 Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <animateTransform attributeName="transform" type="rotate" values="0 22 12;-8 22 12;0 22 12" dur="3s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </path>
  <!-- Pin kanan -->
  <path d="M40 8 Q40 6 42 6 Q44 6 44 8 L44 16 Q44 18 42 18 Q40 18 40 16 Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <animateTransform attributeName="transform" type="rotate" values="0 42 12;8 42 12;0 42 12" dur="3s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </path>
  <!-- Bintang event -->
  <path d="M32 30 L33.8 35.5 L39.5 35.5 L35 38.8 L36.8 44.5 L32 41.2 L27.2 44.5 L29 38.8 L24.5 35.5 L30.2 35.5 Z"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="color-mix(in srgb, currentColor 12%, transparent)">
    <animateTransform attributeName="transform" type="scale" values="1 1;1.08 1.08;1 1" dur="2s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1" additive="sum" origin="32 37"/>
    <animate attributeName="opacity" values="0.8;1;0.8" dur="2s" repeatCount="indefinite"/>
  </path>
  <!-- Kilap -->
  <circle cx="48" cy="32" r="2" fill="currentColor" opacity="0">
    <animate attributeName="opacity" values="0;0.5;0" dur="2.5s" begin="0.5s" repeatCount="indefinite"/>
    <animate attributeName="r" values="1;3;1" dur="2.5s" begin="0.5s" repeatCount="indefinite"/>
  </circle>
</svg>',

'Ruang Publik Inklusif' => '
<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
  <!-- Orang kiri -->
  <circle cx="18" cy="14" r="5" stroke="currentColor" stroke-width="2.5">
    <animateTransform attributeName="transform" type="translate" values="0,0;0,-2;0,0" dur="2.8s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </circle>
  <path d="M10 36 Q10 26 18 26 Q26 26 26 36 L26 44 Q26 46 24 46 Q22 46 22 44 L22 38 L20 38 L20 56 Q20 58 18 58 Q16 58 16 56 L16 38 L14 38 L14 44 Q14 46 12 46 Q10 46 10 44 Z"
        stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
    <animateTransform attributeName="transform" type="translate" values="0,0;0,-2;0,0" dur="2.8s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </path>
  <!-- Orang kanan -->
  <circle cx="46" cy="14" r="5" stroke="currentColor" stroke-width="2.5">
    <animateTransform attributeName="transform" type="translate" values="0,0;0,-2;0,0" dur="2.8s" begin="0.4s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </circle>
  <path d="M38 36 Q38 26 46 26 Q54 26 54 36 L54 44 Q54 46 52 46 Q50 46 50 44 L50 38 L48 38 L48 56 Q48 58 46 58 Q44 58 44 56 L44 38 L42 38 L42 44 Q42 46 40 46 Q38 46 38 44 Z"
        stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
    <animateTransform attributeName="transform" type="translate" values="0,0;0,-2;0,0" dur="2.8s" begin="0.4s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </path>
  <!-- Tangan saling sambut -->
  <path d="M26 34 Q32 30 38 34" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
    <animate attributeName="d" values="M26 34 Q32 30 38 34;M26 34 Q32 28 38 34;M26 34 Q32 30 38 34" dur="2.8s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </path>
  <!-- Hati kecil tengah -->
  <path d="M32 22 Q32 18 29 18 Q26 18 26 21 Q26 24 32 28 Q38 24 38 21 Q38 18 35 18 Q32 18 32 22 Z"
        fill="currentColor" opacity="0" stroke="none">
    <animate attributeName="opacity" values="0;0.35;0" dur="2.8s" begin="0.6s" repeatCount="indefinite"/>
    <animateTransform attributeName="transform" type="scale" values="0.5 0.5;1 1;0.5 0.5" dur="2.8s" begin="0.6s" repeatCount="indefinite" additive="sum" origin="32 23"/>
  </path>
</svg>',

'Kultur Ngopi yang Kuat' => '
<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
  <!-- Cangkir -->
  <path d="M14 28 Q13 28 13 29 L17 52 Q17 55 20 55 L44 55 Q47 55 47 52 L51 29 Q51 28 50 28 Z"
        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="color-mix(in srgb, currentColor 7%, transparent)"/>
  <!-- Handle -->
  <path d="M51 34 Q60 34 60 41 Q60 48 51 48" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
  <!-- Alas -->
  <path d="M10 56 Q10 58 12 58 L52 58 Q54 58 54 56 L54 55 Q54 54 52 54 L12 54 Q10 54 10 55 Z"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="color-mix(in srgb, currentColor 10%, transparent)"/>
  <!-- Uap 1 -->
  <path d="M24 22 Q22 18 24 14 Q26 10 24 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" fill="none">
    <animate attributeName="opacity" values="0;0.7;0" dur="2s" repeatCount="indefinite"/>
    <animateTransform attributeName="transform" type="translate" values="0,0;0,-4;0,-8" dur="2s" repeatCount="indefinite"/>
  </path>
  <!-- Uap 2 -->
  <path d="M32 24 Q30 20 32 16 Q34 12 32 8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" fill="none">
    <animate attributeName="opacity" values="0;0.7;0" dur="2s" begin="0.5s" repeatCount="indefinite"/>
    <animateTransform attributeName="transform" type="translate" values="0,0;0,-4;0,-8" dur="2s" begin="0.5s" repeatCount="indefinite"/>
  </path>
  <!-- Uap 3 -->
  <path d="M40 22 Q38 18 40 14 Q42 10 40 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" fill="none">
    <animate attributeName="opacity" values="0;0.7;0" dur="2s" begin="1s" repeatCount="indefinite"/>
    <animateTransform attributeName="transform" type="translate" values="0,0;0,-4;0,-8" dur="2s" begin="1s" repeatCount="indefinite"/>
  </path>
</svg>',

'Arsitektur Bersejarah' => '
<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
  <!-- Atap -->
  <path d="M8 30 Q8 28 10 27 L32 8 L54 27 Q56 28 56 30 L56 32 Q56 34 54 34 L10 34 Q8 34 8 32 Z"
        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="color-mix(in srgb, currentColor 8%, transparent)"/>
  <!-- Body gedung -->
  <path d="M12 34 L12 56 L52 56 L52 34" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="color-mix(in srgb, currentColor 5%, transparent)"/>
  <!-- Pintu -->
  <path d="M26 56 L26 44 Q26 40 32 40 Q38 40 38 44 L38 56" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
  <!-- Jendela kiri -->
  <rect x="14" y="38" width="8" height="8" rx="2" stroke="currentColor" stroke-width="2" opacity=".7">
    <animate attributeName="opacity" values="0.4;1;0.4" dur="3s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </rect>
  <!-- Jendela kanan -->
  <rect x="42" y="38" width="8" height="8" rx="2" stroke="currentColor" stroke-width="2" opacity=".7">
    <animate attributeName="opacity" values="0.4;1;0.4" dur="3s" begin="0.8s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </rect>
  <!-- Tiang kiri -->
  <path d="M18 34 L18 56" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".3"/>
  <!-- Tiang kanan -->
  <path d="M46 34 L46 56" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".3"/>
  <!-- Puncak menara -->
  <path d="M32 4 L32 8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
  <circle cx="32" cy="4" r="2.5" fill="currentColor" opacity=".4">
    <animate attributeName="opacity" values="0.2;0.8;0.2" dur="2s" repeatCount="indefinite"/>
  </circle>
  <!-- Alas -->
  <path d="M6 56 Q6 58 8 58 L56 58 Q58 58 58 56" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
</svg>',

'Kiblat Fashion Lokal' => '
<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
  <!-- Baju/dress shape -->
  <path d="M22 8 Q16 12 10 20 L18 24 L18 56 Q18 58 20 58 L44 58 Q46 58 46 56 L46 24 L54 20 Q48 12 42 8 Q38 14 32 14 Q26 14 22 8 Z"
        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="color-mix(in srgb, currentColor 7%, transparent)">
    <animateTransform attributeName="transform" type="translate" values="0,0;0,-1;0,0" dur="3s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </path>
  <!-- Garis leher -->
  <path d="M22 8 Q26 14 32 14 Q38 14 42 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".5">
    <animateTransform attributeName="transform" type="translate" values="0,0;0,-1;0,0" dur="3s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </path>
  <!-- Bintang fashion -->
  <path d="M32 28 L33.2 32 L37 32 L34.2 34.4 L35.2 38 L32 36 L28.8 38 L29.8 34.4 L27 32 L30.8 32 Z"
        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="color-mix(in srgb, currentColor 15%, transparent)">
    <animateTransform attributeName="transform" type="rotate" values="0 32 33;360 32 33" dur="8s" repeatCount="indefinite"/>
  </path>
  <!-- Garis detail baju -->
  <path d="M24 44 Q32 46 40 44" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".4">
    <animateTransform attributeName="transform" type="translate" values="0,0;0,-1;0,0" dur="3s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </path>
  <path d="M22 50 Q32 52 42 50" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".3">
    <animateTransform attributeName="transform" type="translate" values="0,0;0,-1;0,0" dur="3s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </path>
  <!-- Kilap -->
  <circle cx="48" cy="14" r="2" fill="currentColor" opacity="0">
    <animate attributeName="opacity" values="0;0.6;0" dur="3s" begin="1s" repeatCount="indefinite"/>
    <animate attributeName="r" values="1;3;1" dur="3s" begin="1s" repeatCount="indefinite"/>
  </circle>
</svg>',

'Wisata Alam Estetik' => '
<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
  <!-- Gunung kiri -->
  <path d="M4 54 Q4 56 6 56 L28 56 L18 28 Q16 24 14 28 Z"
        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="color-mix(in srgb, currentColor 6%, transparent)"/>
  <!-- Gunung kanan besar -->
  <path d="M20 56 L32 14 L44 56 Z"
        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="color-mix(in srgb, currentColor 10%, transparent)"/>
  <!-- Salju puncak -->
  <path d="M32 14 Q34 20 38 24 Q35 23 32 26 Q29 23 26 24 Q30 20 32 14 Z"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="color-mix(in srgb, currentColor 20%, transparent)"/>
  <!-- Matahari -->
  <circle cx="50" cy="18" r="7" stroke="currentColor" stroke-width="2.2" fill="color-mix(in srgb, currentColor 10%, transparent)">
    <animate attributeName="r" values="7;8;7" dur="3s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </circle>
  <path d="M50 8 L50 6M50 30 L50 32M60 18 L62 18M38 18 L36 18M57 11 L58.5 9.5M42 25 L40.5 26.5M57 25 L58.5 26.5M42 11 L40.5 9.5"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".5">
    <animateTransform attributeName="transform" type="rotate" values="0 50 18;360 50 18" dur="12s" repeatCount="indefinite"/>
  </path>
  <!-- Alas bukit -->
  <path d="M4 56 Q20 48 32 52 Q44 48 60 56" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" fill="none">
    <animate attributeName="d" values="M4 56 Q20 48 32 52 Q44 48 60 56;M4 56 Q20 46 32 50 Q44 46 60 56;M4 56 Q20 48 32 52 Q44 48 60 56" dur="4s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </path>
</svg>',

'Ikon Kuliner Global' => '
<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
  <!-- Piring -->
  <ellipse cx="32" cy="46" rx="24" ry="8" stroke="currentColor" stroke-width="2.5" fill="color-mix(in srgb, currentColor 5%, transparent)"/>
  <!-- Kubah makanan -->
  <path d="M8 46 Q8 24 32 24 Q56 24 56 46" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="color-mix(in srgb, currentColor 8%, transparent)"/>
  <!-- Handle kubah -->
  <path d="M32 16 Q32 20 32 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
  <circle cx="32" cy="14" r="4" stroke="currentColor" stroke-width="2.2" fill="color-mix(in srgb, currentColor 12%, transparent)">
    <animateTransform attributeName="transform" type="translate" values="0,0;0,-2;0,0" dur="2s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </circle>
  <!-- Uap kiri -->
  <path d="M20 22 Q18 18 20 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".5">
    <animate attributeName="opacity" values="0;0.6;0" dur="2s" repeatCount="indefinite"/>
    <animateTransform attributeName="transform" type="translate" values="0,0;0,-4" dur="2s" repeatCount="indefinite"/>
  </path>
  <!-- Uap kanan -->
  <path d="M44 22 Q42 18 44 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".5">
    <animate attributeName="opacity" values="0;0.6;0" dur="2s" begin="0.6s" repeatCount="indefinite"/>
    <animateTransform attributeName="transform" type="translate" values="0,0;0,-4" dur="2s" begin="0.6s" repeatCount="indefinite"/>
  </path>
  <!-- Garpu -->
  <path d="M6 38 L6 56M6 38 L4 32M6 38 L8 32M10 32 L10 42 Q10 44 8 44" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" opacity=".6"/>
  <!-- Sendok -->
  <path d="M58 32 Q60 36 59 40 Q58 44 56 44 L55 56M58 32 Q54 28 52 32 Q50 36 52 40 Q54 44 56 44" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" opacity=".6"/>
</svg>',

'Konektivitas Kilat' => '
<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
  <!-- Gelombang sinyal luar -->
  <path d="M8 32 Q8 14 32 14 Q56 14 56 32" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" fill="none" opacity=".3">
    <animate attributeName="opacity" values="0.1;0.5;0.1" dur="2s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </path>
  <!-- Gelombang sinyal tengah -->
  <path d="M14 38 Q14 22 32 22 Q50 22 50 38" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" fill="none" opacity=".5">
    <animate attributeName="opacity" values="0.2;0.7;0.2" dur="2s" begin="0.3s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </path>
  <!-- Gelombang sinyal dalam -->
  <path d="M20 44 Q20 32 32 32 Q44 32 44 44" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" fill="none" opacity=".7">
    <animate attributeName="opacity" values="0.4;1;0.4" dur="2s" begin="0.6s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </path>
  <!-- Titik tengah -->
  <circle cx="32" cy="50" r="4" stroke="currentColor" stroke-width="2.5" fill="color-mix(in srgb, currentColor 20%, transparent)">
    <animate attributeName="r" values="4;5;4" dur="2s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
  </circle>
  <!-- Kilat kecil -->
  <path d="M36 42 L31 48 L34 48 L30 56 L38 47 L34 47 Z" fill="currentColor" opacity="0" stroke="none">
    <animate attributeName="opacity" values="0;0.7;0" dur="2s" begin="1s" repeatCount="indefinite"/>
  </path>
</svg>',

'Pusat Inovasi Digital' => '
<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
  <!-- Hexagon -->
  <path d="M32 6 L54 19 L54 45 L32 58 L10 45 L10 19 Z"
        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="color-mix(in srgb, currentColor 6%, transparent)"/>
  <!-- Circuit lines -->
  <path d="M32 6 L32 18M32 46 L32 58M10 19 L20 25M44 39 L54 45M54 19 L44 25M20 39 L10 45"
        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".35"/>
  <!-- Inner ring -->
  <circle cx="32" cy="32" r="12" stroke="currentColor" stroke-width="2" fill="none" opacity=".5">
    <animateTransform attributeName="transform" type="rotate" values="0 32 32;360 32 32" dur="10s" repeatCount="indefinite"/>
  </circle>
  <!-- Core -->
  <circle cx="32" cy="32" r="5" stroke="currentColor" stroke-width="2.2" fill="color-mix(in srgb, currentColor 18%, transparent)">
    <animate attributeName="r" values="5;6.5;5" dur="2s" repeatCount="indefinite" calcMode="spline" keySplines="0.4 0 0.6 1;0.4 0 0.6 1"/>
    <animate attributeName="opacity" values="0.8;1;0.8" dur="2s" repeatCount="indefinite"/>
  </circle>
  <!-- Node dots -->
  <circle cx="32" cy="20" r="2.5" fill="currentColor" opacity=".5">
    <animate attributeName="opacity" values="0.3;0.9;0.3" dur="2s" begin="0s" repeatCount="indefinite"/>
  </circle>
  <circle cx="32" cy="44" r="2.5" fill="currentColor" opacity=".5">
    <animate attributeName="opacity" values="0.3;0.9;0.3" dur="2s" begin="0.4s" repeatCount="indefinite"/>
  </circle>
  <circle cx="22" cy="26" r="2.5" fill="currentColor" opacity=".5">
    <animate attributeName="opacity" values="0.3;0.9;0.3" dur="2s" begin="0.8s" repeatCount="indefinite"/>
  </circle>
  <circle cx="42" cy="26" r="2.5" fill="currentColor" opacity=".5">
    <animate attributeName="opacity" values="0.3;0.9;0.3" dur="2s" begin="1.2s" repeatCount="indefinite"/>
  </circle>
  <circle cx="22" cy="38" r="2.5" fill="currentColor" opacity=".5">
    <animate attributeName="opacity" values="0.3;0.9;0.3" dur="2s" begin="1.6s" repeatCount="indefinite"/>
  </circle>
  <circle cx="42" cy="38" r="2.5" fill="currentColor" opacity=".5">
    <animate attributeName="opacity" values="0.3;0.9;0.3" dur="2s" begin="2s" repeatCount="indefinite"/>
  </circle>
</svg>',

];

?>

<style>
.khb-section {
  position: relative;
  overflow: hidden;
  padding: 5rem 0 6rem;
}

.khb-section::before {
  content: "";
  position: absolute;
  top: -200px;
  right: -200px;
  width: 600px;
  height: 600px;
  background: radial-gradient(circle, rgba(167,139,250,.08) 0%, transparent 70%);
  pointer-events: none;
}

.khb-header {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-end;
  justify-content: space-between;
  gap: 1rem;
  margin-bottom: 3.5rem;
}

.khb-header__left {
  display: flex;
  flex-direction: column;
  gap: 0.3rem;
}

/* Grid */
.khb-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 0;
}

@media (max-width: 640px) {
  .khb-grid {
    grid-template-columns: 1fr;
  }
}

/* Item */
.khb-item {
  padding: 2.25rem 2rem 2.5rem;
  border-bottom: 1px solid color-mix(in srgb, currentColor 8%, transparent);
  position: relative;
  transition: background 0.3s ease;
}

.khb-item:nth-child(odd) {
  border-right: 1px solid color-mix(in srgb, currentColor 8%, transparent);
}

@media (max-width: 640px) {
  .khb-item:nth-child(odd) {
    border-right: none;
  }
  .khb-item {
    padding: 2rem 0.25rem;
  }
}

/* Last row — no bottom border */
.khb-item:nth-last-child(-n+2):not(.khb-item:nth-child(odd):last-child) {
  border-bottom: none;
}
.khb-item:last-child {
  border-bottom: none;
}
@media (max-width: 640px) {
  .khb-item:nth-last-child(-n+2) {
    border-bottom: 1px solid color-mix(in srgb, currentColor 8%, transparent);
  }
  .khb-item:last-child {
    border-bottom: none;
  }
}

/* Hover bg */
.khb-item::after {
  content: "";
  position: absolute;
  inset: 0;
  background: color-mix(in srgb, var(--color-primary, #7c3aed) 4%, transparent);
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
}
.khb-item:hover::after {
  opacity: 1;
}

/* Icon */
.khb-icon-wrap {
  width: 64px;
  height: 64px;
  margin-bottom: 1.5rem;
  color: var(--color-primary, #7c3aed);
  position: relative;
  transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.khb-item:hover .khb-icon-wrap {
  transform: translateY(-4px);
}

.khb-icon-wrap svg {
  width: 64px;
  height: 64px;
  overflow: visible;
}

/* Animated stroke on hover */
.khb-item .khb-icon-g {
  transition: opacity 0.3s ease;
}

.khb-item:hover .khb-icon-g {
  filter: drop-shadow(0 0 8px color-mix(in srgb, var(--color-primary, #7c3aed) 50%, transparent));
}

/* Title */
.khb-item__title {
  font-size: 1rem;
  font-weight: 700;
  color: var(--text-heading);
  margin: 0 0 0.6rem;
  line-height: 1.3;
  transition: color 0.2s;
}

.khb-item:hover .khb-item__title {
  color: var(--color-primary, #7c3aed);
}

/* Excerpt */
.khb-item__excerpt {
  font-size: 0.84rem;
  color: var(--text-muted);
  line-height: 1.7;
  margin: 0;
}
</style>

<?php if (!empty($_khb_items)): ?>
<section class="khb-section">
  <div class="container">

    <div class="khb-header">
      <div class="khb-header__left">
        <span class="text-eyebrow">Discover Bandung</span>
        <h2 class="text-sub-hero">Kenapa Harus Bandung?</h2>
      </div>
      <div>
        <p class="khb-lead">Bandung 2026: Perpaduan sempurna inovasi digital,<br>kesejukan alam, dan kreativitas kuliner terbaik.</p>
      </div>
    </div>

    <div class="khb-grid">
      <?php foreach ($_khb_items as $i => $_khb_item):
        $title   = htmlspecialchars($_khb_item['title'] ?? 'Untitled');
        $excerpt = htmlspecialchars($_khb_item['excerpt'] ?? '');
        $icon    = $_khb_icons[$title] ?? '
          <g class="khb-icon-g">
            <circle cx="32" cy="32" r="22" fill="none" stroke="currentColor" stroke-width="2.2"/>
            <circle cx="32" cy="32" r="8" fill="currentColor" opacity=".2"/>
          </g>';
      ?>
      <div class="khb-item">
        <div class="khb-icon-wrap">
          <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
            <?= $icon ?>
          </svg>
        </div>
        <h3 class="khb-item__title"><?= $title ?></h3>
        <?php if ($excerpt): ?>
          <p class="khb-item__excerpt"><?= $excerpt ?></p>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>
