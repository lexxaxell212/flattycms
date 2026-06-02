<?php
$_khb_stmt  = $pdo->query("SELECT * FROM admin_items WHERE status = 'active' AND category IN ('trending') ORDER BY id DESC");
$_khb_items = $_khb_stmt->fetchAll(PDO::FETCH_ASSOC);

$_khb_icons = [

'Udara Sejuk Menenangkan' => '
  <g class="khb-icon-g">
    <circle cx="32" cy="22" r="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
    <path d="M18 34 C14 34 8 30 8 24 C8 18 13 14 19 15" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
    <path d="M46 34 C50 34 56 30 56 24 C56 18 51 14 45 15" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
    <path d="M20 42 Q32 36 44 42" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    <path d="M24 50 Q32 45 40 50" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".6"/>
    <path d="M28 57 Q32 54 36 57" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".35"/>
    <circle cx="32" cy="22" r="5" fill="currentColor" opacity=".15"/>
    <path d="M32 10 L32 7M32 37 L32 34M44 22 L47 22M20 22 L17 22M40.5 13.5 L42.6 11.4M23.5 13.5 L21.4 11.4M40.5 30.5 L42.6 32.6M23.5 30.5 L21.4 32.6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".5"/>
  </g>',

'Event Seni Internasional' => '
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1306 1306"><path fill="#552ad4" fill-opacity=".5" d="M463.8 397.7c13.9.2 36.4.2 50 0 13.6-.1 2.2-.2-25.3-.2s-38.6.1-24.7.2M397.5 550c0 63 .1 88.7.2 57.3.2-31.5.2-83.1 0-114.5-.1-31.5-.2-5.8-.2 57.2"/><g fill="#552ad4" stroke-width="0"><path d="M411.5 397.8c-11.1.9-11.4 1-12.5 5.4-1.5 5.3-1.4 263.3.1 276.3 4.9 44.7 20 85.5 44 119.2l5.8 8.2.3 19c.3 17.6.4 19.1 2.3 20.5 2.9 2.2 400 2.4 403.1.2 1.7-1.3 1.9-3.1 2.4-19.8l.5-18.4 7.7-11.4c57.6-86.3 57.7-199.7.3-285.4-4.7-7-8.5-13.7-8.5-15 0-3-2.9-6.3-6.3-7.1-1.5-.4-3.9-2.1-5.3-3.9-42.5-51.3-105.2-80.8-183.9-86.7-13.9-1.1-239.2-2.1-250-1.1M537 495v26h-16v-52h16zm82.8.2.2 25.8h-16v-52.1l7.8.3 7.7.3zm82.2-.2v26h-16v-25.3c0-29.1-.8-26.7 8.7-26.7h7.3zm83 0v26h-16v-52h16zm-276 19.1c0 10.6.3 13.7 1.7 16l1.7 2.9h16.3c21.4 0 20.3.9 20.3-18v-14h43v13.8c0 19.3-1.2 18.2 19.7 18.2 20.6 0 19.2 1.3 19.3-17.8V501l21.8.2 21.7.3.3 13.5c.3 19.2-1 18 19 18 21.5 0 20.2 1.1 20.2-17.7V501h42.9l.3 14.6c.4 18.5-.9 17.4 20.1 17.4 20.5 0 19.6.9 19.7-18.3V501h48v62H461v-62h48zm335.8 191.6-.3 130.8-191.7.3-191.8.2V575h384z"/><path d="M487.6 602.6c-1.6 4.1-.6 205.2 1 206.8 2.4 2.4 326.4 2.4 328.8 0 1.6-1.6 2.6-202.7 1-206.8-.9-2.4-329.9-2.4-330.8 0m320.4 83c0 64.1-.2 73.5-1.5 73.1-2.1-.9-16.5-12.9-16.5-13.8 0-.5-.7-.9-1.5-.9s-1.5-.4-1.5-.8c0-.5-4.7-4.6-10.5-9.2s-10.5-8.7-10.5-9.2c0-.4-.7-.8-1.5-.8s-1.5-.4-1.5-.8c0-.5-3.8-3.9-8.5-7.6-8.6-6.8-9.8-7.8-17-14.1-5-4.4-6.3-4.4-13.1 0-3 1.9-5.9 3.5-6.4 3.5-.6 0-1 .4-1 .9s-1.5 1.6-3.2 2.6c-4.1 2.1-5.9 3.1-13 7.8-3.1 2-6.1 3.7-6.6 3.7s-1.7 1-2.8 2.1c-1 1.2-2.8 2.5-3.9 3-1.9.9-10.6 6.3-19.1 12-6.8 4.4-6.9 4.4-11.8-2.6-2.5-3.5-4.9-6.5-5.3-6.7-.5-.1-2.8-3-5.3-6.3-2.4-3.3-8.1-10.6-12.7-16.3-4.6-5.6-8.3-10.5-8.3-10.8 0-1.8-7.2-8.4-9.3-8.4-1.7 0-4.9 1.5-7.7 3.6-.8.7-3.2 2.1-5.2 3.3-9.1 5.1-25.8 14.8-30.8 18-1.4.8-4.3 2.5-6.5 3.6-2.3 1.1-5.4 2.9-7 4-4.8 3.2-6.8 4.4-9.5 5.7-1.4.7-3.2 1.7-4 2.4-.8.6-4 2.4-7 4-3 1.5-5.7 3.1-6 3.5-.3.3-2.3 1.6-4.5 2.8s-5.3 3-7 4c-2.7 1.6-5 3-12.6 7.3-1.3.7-3.5 2.1-4.8 3-5.4 4-5.1 7.4-5.1-68.7V612h310zm-188.5 20.6c2.7 3.5 7.8 10.1 11.4 14.8 3.5 4.7 6.7 8.7 7.1 9 1.6 1.3 13 16.8 13 17.7 0 .6-.7 1.3-1.5 1.7-1.5.5-7.2 4.2-25.6 16.2-11.6 7.6-12.8 9.5-8.7 13.4 2.9 2.7 5.4 2.4 10.8-1.1 2.5-1.5 6.8-4.2 9.7-5.9 2.8-1.7 5.7-3.8 6.3-4.5.7-.8 1.6-1.5 2.1-1.5s4.9-2.5 9.7-5.6 10.5-6.8 12.7-8.2 5.2-3.3 6.7-4.4c1.5-1 3.2-1.8 3.8-1.8.5 0 1-.4 1-.9s1.5-1.6 3.4-2.5c1.8-.8 3.9-2.2 4.6-3.1.7-.8 1.6-1.5 2.1-1.5.9 0 9-5 9.9-6.1.3-.3 1.6-1.2 3-1.9s6.2-3.7 10.7-6.6c4.6-3 8.7-5.4 9.3-5.4.5 0 1-.4 1-.8 0-2.2 10.3-6.3 11.2-4.5.3.9 6.8 6.4 22.4 19.3 5.6 4.7 19.7 16.4 31.3 26l21.1 17.5-.3 12-.2 12h-309l-.3-16.3c-.2-14.8-.1-16.3 1.5-16.9 1.5-.6 10.1-5.5 15.4-8.8 1.9-1.2 9-5.4 12.8-7.5 1.5-.8 5.8-3.3 9.6-5.4 3.9-2.2 7.2-4.3 7.5-4.6.3-.4 1.9-1.3 3.5-2.2 3.2-1.6 9.1-4.9 14-7.9 1.7-1 4.6-2.8 6.5-3.9s4.2-2.4 5-3c5.3-3.3 15.3-9 16-9 .4 0 1.8-.8 3.1-1.9 1.3-1 4.7-3.1 7.4-4.6 2.8-1.5 6.8-3.8 9-5.1 5.2-3.1 4.4-3.3 10 3.8"/><path d="M679 631.2c-13.4 3-20.1 15-18.1 32.5.3 3.1 8.6 11.6 13.4 13.9 26.6 12.4 48.7-22.5 26.2-41.3-6.4-5.3-13.2-6.9-21.5-5.1m11.8 12.2c11 5.7 8.8 22.9-3.3 25.2-14 2.6-21.5-16.3-9.6-24.3 3.9-2.6 8.9-3 12.9-.9M495.1 854.1c90.3 72.3 224 72.4 316.3.3l6.8-5.4H488.7z"/></g><path fill="#552aaa" d="M819.5 705.5c0 55.5.1 78.4.2 50.8.2-27.6.2-73 0-101-.1-27.9-.2-5.4-.2 50.2"/><path fill="#2a0080" d="M570.8 848.7c45.9.2 120.6.2 166 0 45.5-.1 8-.2-83.3-.2s-128.5.1-82.7.2"/><path fill="#2a0055" d="M691.3 799.7c21.4.2 56 .2 77 0 21-.1 3.6-.2-38.8-.2-42.3 0-59.5.1-38.2.2"/><path fill="#802ad4" d="M571.2 600.7c45 .2 118.6.2 163.5 0 45-.1 8.2-.2-81.7-.2s-126.7.1-81.8.2"/><g fill="#8055d4" stroke-width="0"><path d="M521.5 470.3c-.3.7-.4 12.5-.2 26.2l.2 25 .3-25.7.2-25.7 7.3-.4 7.2-.3-7.3-.2c-5.1-.1-7.4.2-7.7 1.1m255.2-.6 7.3.4.3 25.2.3 25.2-.1-25.5v-25.5l-7.5-.1-7.5-.1zm-315.2 32.6c-.3.7-.4 14.5-.2 30.7l.2 29.5.3-30.2.2-30.3 23.8-.3 23.7-.2-23.8-.3c-18.4-.1-23.9.1-24.2 1.1m108.7-.6 20.7.3.4 13 .3 13-.1-13.2v-13.3h-21l-21-.1zm144.2.6c-.2.7-.3 6.9-.1 13.8l.3 12.4.2-13.2.2-13.3 20.8-.3 20.7-.2-20.8-.3c-16.3-.2-20.9.1-21.3 1.1m106.1-.6 23.5.3.3 30.3.2 30.2v-61h-23.8l-23.7-.1zm-359 203.8v131h379l-189.2-.2L462 836l-.3-130.8-.2-130.7zm383 0c0 72 .1 101.4.2 65.2.2-36.2.2-95.2 0-131-.1-35.9-.2-6.3-.2 65.8"/><path d="M571.7 811.7c44.7.2 117.9.2 162.5 0 44.7-.1 8.2-.2-81.2-.2s-125.9.1-81.3.2"/></g><g fill="#d4aaff" stroke-width="0"><path d="M522 495.5v25.6l7.3-.3 7.2-.3v-50l-7.2-.3-7.3-.3zm83 0V521h14l-.2-25.3-.3-25.2-6.7-.3-6.8-.3zm82.4-24.2c-.2.7-.3 12-.2 25.2l.3 24 6.8.3 6.7.3V470h-6.5c-4.4 0-6.7.4-7.1 1.3m82.6 24.2V521h14v-51h-14z"/><path d="M462 532v30h382v-60h-46v13.5c0 18.9.4 18.5-21 18.5-21.3 0-20.4.8-20.8-17.9l-.4-14.1H715v13.5c0 19 .5 18.5-20.8 18.5-21.6 0-21.2.4-21.2-18.5V502h-40v12.2c0 20 .2 19.8-22 19.8-20.4 0-20 .4-20-18.5V502h-40.8l-.4 14c-.4 18.4.7 17.4-19.8 17.8-22 .5-21 1.3-21-17.4V502h-47zm218 112.3c-10.1 4.8-9.5 17.5 1.1 22.2 7 3.2 15.9-3 15.9-11.1 0-4.3-3.6-10.4-6.2-10.4-.9 0-2-.5-2.3-1-.8-1.3-5.4-1.2-8.5.3m225.7 53.8c-5.9 36.2-21 73.4-41.8 102.7L858 809v36.9l-2.6 2c-2.5 1.9-4 2.1-19.9 2.1h-17.3l-5.4 4.3c-32.8 26.8-80.3 47.1-122.6 52.6-9.4 1.2 5.1 1.3 118.3 1.1 102.3-.1 96.9.1 98.5-4.3.9-2.7 1.6-215.4.7-216.3-.1-.1-1 4.7-2 10.7"/><path d="M612.5 701.9c-1 .9-6.7 4.3-12 7-1.6.8-3.4 1.9-4 2.4-.5.5-3.5 2.3-6.7 4-5.6 3.2-17.7 10.2-23.3 13.6-1.6 1-4.7 2.8-6.7 4-2.1 1.1-5.4 3-7.3 4.1s-5.2 3-7.2 4.1c-2.1 1.2-5.1 3-6.8 4-1.6 1-4.6 2.8-6.5 3.9s-5.3 3.2-7.5 4.5-5.9 3.4-8.2 4.6c-2.4 1.3-4.3 2.7-4.3 3.1s-.9.8-2 .8-2 .4-2 .8c0 .5-1.8 1.7-4 2.7l-4 1.8V798h306v-22.5l-7.9-6.3c-7.5-6-11.4-9.3-17.1-14.4-1.4-1.3-5.3-4.5-8.8-7.2-3.4-2.7-6.2-5.3-6.2-5.7 0-.5-.7-.9-1.5-.9s-1.9-.9-2.5-2-1.7-2-2.5-2-1.5-.4-1.5-.8-3.3-3.4-7.2-6.7c-4-3.2-8-6.5-8.8-7.2-11.4-10.3-11-10.1-14.8-7.6-1.5 1-3.4 2.2-4.3 2.8-.9.5-3.6 2.3-6 4-5.5 3.7-6.7 4.4-11.5 7.3-4.5 2.6-7.3 4.4-8.8 5.5s-6.8 4.5-15.1 9.7c-2.2 1.3-4.4 2.8-5 3.2-1.6 1.3-8.6 5.8-8.9 5.8s-15.6 9.9-18.2 11.7c-1.7 1.3-4.6 3.1-8.8 5.5-2.1 1.3-4.6 2.8-5.5 3.3-7.2 4.6-10 6.6-10.6 7.5-.8 1.3-7.1 1.3-9.6 0-3.9-2.2-2.6-9.3 2.4-12.5 2.3-1.6 5.4-3.6 6.9-4.7 1.5-1 3.2-1.8 3.8-1.8.5 0 1-.4 1-.8 0-.5 1.2-1.5 2.8-2.2 1.5-.8 3.5-2 4.4-2.8 1.7-1.4 6.4-4.2 10.6-6.4 3.5-1.8.9-7.2-8.3-16.9-.8-.9-1.5-2.1-1.5-2.7 0-.7-.4-1.2-.8-1.2-.5 0-2.8-2.7-5.2-6s-4.7-6-5.2-6c-.4 0-.8-.6-.8-1.3s-.8-2-1.7-2.8c-1-.9-2.7-2.9-3.8-4.5-4.1-5.7-6-7.5-7-6.5"/></g><path fill="gray" d="M820.5 705.5c0 55 .1 77.6.2 50.3.2-27.3.2-72.3 0-100-.1-27.7-.2-5.3-.2 49.7"/><path fill="#d4aad4" d="M604.4 495.5c0 14.3.2 20 .3 12.7.2-7.3.2-19 0-26-.1-7-.3-1-.3 13.3m88.9 104.2c22.4.2 59 .2 81.5 0 22.4-.1 4-.2-40.8-.2s-63.2.1-40.7.2m-155 13c21.4.2 56 .2 77 0 21-.1 3.6-.2-38.8-.2-42.3 0-59.5.1-38.2.2"/><path fill="#aaa" d="M485.5 706c0 55.3.1 77.9.2 50.2.2-27.6.2-72.8 0-100.5-.1-27.6-.2-5-.2 50.3m52.8 92.7c21.4.2 56 .2 77 0 21-.1 3.6-.2-38.8-.2-42.3 0-59.5.1-38.2.2"/><path fill="#552a80" d="M486.5 705.5c0 55 .1 77.6.2 50.3.2-27.3.2-72.3 0-100-.1-27.7-.2-5.3-.2 49.7"/><path fill="#aa80d4" d="M557.3 562.7c52.6.2 138.8.2 191.5 0 52.6-.1 9.5-.2-95.8-.2s-148.4.1-95.7.2m0 13c52.6.2 138.8.2 191.5 0 52.6-.1 9.5-.2-95.8-.2s-148.4.1-95.7.2m-58.8 37.5c-.3.7-.4 32.3-.3 70.3l.3 69 .5-70.3c.3-38.6.4-70.2.2-70.2-.1 0-.5.6-.7 1.2m309 72.8c0 40.4.1 57 .2 36.7.2-20.2.2-53.2 0-73.5-.1-20.2-.2-3.6-.2 36.8"/><path fill="#d4d4d4" d="M462 706v130h187.3c102.9 0 188.9-.3 191-.6l3.7-.7V576H462zm355-105.5c4.2 2.2 4.1-1.2 3.8 107.6-.3 96.4-.4 99.8-2.2 101.8l-1.9 2.1H489.3l-1.9-2.1c-2.5-2.7-3-205.7-.6-208.1s325.6-3.7 330.2-1.3"/><path fill="#2a2a80" d="M691.8 612.7c21.7.2 56.8.2 78 0 21.3-.1 3.6-.2-39.3-.2s-60.3.1-38.7.2"/><path fill="#80aad4" d="M499.5 682.5c0 38.5.1 54.1.2 34.7.2-19.5.2-51 0-70-.1-19.1-.2-3.2-.2 35.3"/><path fill="#55aad4" d="M500 682c0 38.2.4 69 .9 69 .4 0 1.9-.8 3.2-1.8s3.8-2.6 5.4-3.4c4.3-2.2 39.4-22.6 43.5-25.3 1.9-1.3 5.8-3.6 8.6-5.2 5.4-3 17.5-10 23.4-13.5 1.9-1.2 4.4-2.6 5.5-3.2s6.3-3.7 11.7-6.9c14.5-8.6 15.9-8.5 23.8 1.8.8 1.1 5.3 6.9 9.9 13 4.7 6 10.4 13.4 12.8 16.3l4.3 5.4V613H500zm306.5 3.5c0 40.1.1 56.4.2 36.2.2-20.3.2-53.1 0-73-.1-19.9-.2-3.4-.2 36.8"/><path fill="#2a80d4" d="M653 670.8c0 54.1.1 57.9 1.8 59.8 1 1 3.1 3.6 4.7 5.8 1.6 2.1 3.1 3.3 3.3 2.7s.8-1.1 1.3-1.1c.8 0 7.9-4.3 10.4-6.3 1.1-.9 9.2-5.9 17.5-10.8 3-1.8 6.3-4.1 7.4-5 1-1 3.1-2.3 4.7-2.9 1.6-.7 2.9-1.6 2.9-2.1s.4-.9 1-.9c.8 0 5.8-3 9.6-5.7 6.1-4.5 13.1-7.5 15.5-6.8 1.9.6 17.8 13.2 20.9 16.5.8.9 6.2 5.4 12 10s11.2 9.1 12 10 6.2 5.4 12 10 10.7 8.7 11 9 1.5 1.4 2.8 2.4l2.2 1.9V613H653zm43.8-38.6c11.1 5.6 17.3 20.6 12.8 31.3-.8 1.8-1.2 3.6-.8 3.9.3.3.1.6-.4.6s-1.8 1.3-2.7 2.9c-7 11.9-28.8 13.6-38.9 3.1-20.3-21.1 3.9-54.8 30-41.8"/><path fill="#aa80aa" d="M691.3 798.7c21.3.2 56.1.2 77.5 0 21.3-.1 3.8-.2-38.8-.2s-60.1.1-38.7.2"/><path fill="#552a55" d="M538.3 799.7c21.4.2 56 .2 77 0 21-.1 3.6-.2-38.8-.2-42.3 0-59.5.1-38.2.2"/><path fill="#d4aaff" fill-opacity=".5" d="M908.5 774c0 53.6.1 75.6.2 48.7.2-26.8.2-70.6 0-97.5-.1-26.8-.2-4.8-.2 48.8M795.8 908.7c15.6.2 40.8.2 56 0 15.2-.1 2.5-.2-28.3-.2s-43.3.1-27.7.2"/></svg>
',

'Ruang Publik Inklusif' => '
  <g class="khb-icon-g">
    <circle cx="22" cy="16" r="6" fill="none" stroke="currentColor" stroke-width="2.2"/>
    <circle cx="42" cy="16" r="6" fill="none" stroke="currentColor" stroke-width="2.2"/>
    <path d="M10 44 C10 34 16 28 22 28 C28 28 30 32 32 32 C34 32 36 28 42 28 C48 28 54 34 54 44" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
    <path d="M22 44 L22 56M42 44 L42 56" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".5"/>
    <path d="M16 56 L28 56M36 56 L48 56" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".5"/>
    <path d="M28 44 Q32 40 36 44" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".4"/>
    <circle cx="32" cy="10" r="3" fill="currentColor" opacity=".2"/>
    <path d="M32 7 L32 4M29.5 8.5 L27 6M34.5 8.5 L37 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".35"/>
  </g>',

'Kultur Ngopi yang Kuat' => '
  <g class="khb-icon-g">
    <path d="M14 26 L42 26 L38 54 Q37 58 32 58 Q27 58 26 54 Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
    <path d="M42 32 L48 32 Q54 32 54 38 Q54 44 48 44 L40 44" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
    <path d="M12 62 L52 62" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".4"/>
    <path d="M22 20 Q22 14 26 10" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".5"/>
    <path d="M28 20 Q28 12 32 8" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".5"/>
    <path d="M34 20 Q34 14 38 10" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".5"/>
    <circle cx="32" cy="42" r="7" fill="currentColor" opacity=".08" stroke="currentColor" stroke-width="1.5" opacity=".3"/>
    <path d="M29 42 Q32 38 35 42 Q32 46 29 42Z" fill="currentColor" opacity=".25"/>
  </g>',

'Arsitektur Bersejarah' => '
  <g class="khb-icon-g">
    <path d="M8 56 L8 32 L32 10 L56 32 L56 56 Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
    <path d="M8 32 L56 32" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".35"/>
    <rect x="24" y="38" width="16" height="18" rx="2" fill="none" stroke="currentColor" stroke-width="2"/>
    <rect x="14" y="36" width="10" height="10" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.8" opacity=".7"/>
    <rect x="40" y="36" width="10" height="10" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.8" opacity=".7"/>
    <path d="M32 10 L32 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    <circle cx="32" cy="5" r="2.5" fill="currentColor" opacity=".4"/>
    <path d="M24 32 L24 56M40 32 L40 56" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" opacity=".2"/>
    <path d="M16 56 L16 44M48 56 L48 44" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".4"/>
  </g>',

'Kiblat Fashion Lokal' => '
  <g class="khb-icon-g">
    <path d="M20 8 L12 24 L8 24 L8 40 L20 40 L20 60 L44 60 L44 40 L56 40 L56 24 L52 24 L44 8 Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
    <path d="M20 8 Q26 16 32 14 Q38 12 44 8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".6"/>
    <path d="M12 24 L20 24M44 24 L52 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".35"/>
    <path d="M20 40 L44 40" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".35"/>
    <circle cx="32" cy="34" r="6" fill="none" stroke="currentColor" stroke-width="1.8" opacity=".5"/>
    <path d="M29 34 L31 36 L35 30" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" opacity=".7"/>
    <path d="M26 52 L38 52" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".4"/>
  </g>',

'Wisata Alam Estetik' => '
  <g class="khb-icon-g">
    <path d="M4 52 L20 20 L36 52 Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
    <path d="M24 52 L42 14 L60 52 Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
    <path d="M4 52 L60 52" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".35"/>
    <circle cx="48" cy="22" r="7" fill="none" stroke="currentColor" stroke-width="2" opacity=".6"/>
    <path d="M48 18 L48 15M48 29 L48 32M44 22 L41 22M52 22 L55 22M45.5 19.5 L43.4 17.4M50.5 24.5 L52.6 26.6M50.5 19.5 L52.6 17.4M45.5 24.5 L43.4 26.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".4"/>
    <path d="M14 40 Q20 36 26 40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".3"/>
    <path d="M32 44 Q42 38 52 44" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".3"/>
  </g>',

'Ikon Kuliner Global' => '
  <g class="khb-icon-g">
    <path d="M10 28 Q10 14 22 14 Q28 14 30 20 Q32 14 38 14 Q50 14 50 28 Q50 40 32 50 Q14 40 10 28 Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
    <path d="M30 20 Q30 28 32 50" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".3"/>
    <path d="M14 26 Q22 24 30 26M30 26 Q38 24 50 26" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".35"/>
    <path d="M16 34 Q24 32 30 34M30 34 Q38 32 48 34" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" opacity=".25"/>
    <path d="M24 52 L28 62M40 52 L36 62" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".5"/>
    <path d="M22 62 L42 62" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity=".4"/>
    <circle cx="32" cy="28" r="5" fill="currentColor" opacity=".1"/>
  </g>',

'Konektivitas Kilat' => '
  <g class="khb-icon-g">
    <path d="M10 8 L56 8 Q58 8 58 10 L58 30 Q58 32 56 32 L38 32 L32 42 L26 32 L8 32 Q6 32 6 30 L6 10 Q6 8 8 8 Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
    <path d="M20 50 Q32 44 44 50M24 56 Q32 52 40 56M28 62 Q32 60 36 62" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".4"/>
    <path d="M34 14 L26 22 L31 22 L28 30 L38 20 L33 20 Z" fill="currentColor" opacity=".7" stroke="currentColor" stroke-width="1" stroke-linejoin="round"/>
    <circle cx="14" cy="20" r="2.5" fill="currentColor" opacity=".3"/>
    <circle cx="50" cy="20" r="2.5" fill="currentColor" opacity=".3"/>
    <path d="M14 14 L14 26M50 14 L50 26" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".2"/>
  </g>',

'Pusat Inovasi Digital' => '
  <g class="khb-icon-g">
    <rect x="18" y="18" width="28" height="28" rx="6" fill="none" stroke="currentColor" stroke-width="2.2"/>
    <circle cx="32" cy="32" r="6" fill="none" stroke="currentColor" stroke-width="2"/>
    <circle cx="32" cy="32" r="2.5" fill="currentColor" opacity=".5"/>
    <path d="M32 10 L32 18M32 46 L32 54M10 32 L18 32M46 32 L54 32" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
    <path d="M14.7 14.7 L20.5 20.5M43.5 43.5 L49.3 49.3M49.3 14.7 L43.5 20.5M20.5 43.5 L14.7 49.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity=".5"/>
    <circle cx="32" cy="10" r="3" fill="currentColor" opacity=".35"/>
    <circle cx="32" cy="54" r="3" fill="currentColor" opacity=".35"/>
    <circle cx="10" cy="32" r="3" fill="currentColor" opacity=".35"/>
    <circle cx="54" cy="32" r="3" fill="currentColor" opacity=".35"/>
    <circle cx="14.7" cy="14.7" r="2.5" fill="currentColor" opacity=".2"/>
    <circle cx="49.3" cy="14.7" r="2.5" fill="currentColor" opacity=".2"/>
    <circle cx="14.7" cy="49.3" r="2.5" fill="currentColor" opacity=".2"/>
    <circle cx="49.3" cy="49.3" r="2.5" fill="currentColor" opacity=".2"/>
  </g>',
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
