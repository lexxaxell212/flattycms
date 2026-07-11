<?php
/**
* icons.php
* SVG Icon Helper Library
* Usage: icon('name') atau icon('name', 'class', 'extra attrs')

<?= icon('search') ?>
<?= icon('search', 'nav-icon') ?>
*/

function icon(string $name, string $class = '', string $attrs = ''): string {
 $icons = _icon_registry();
 if (!isset($icons[$name])) {
  return '';
 }
 $svg = trim($icons[$name]);
 if ($class) {
  $svg = preg_replace('/<svg/', '<svg class="' . $class . '"', $svg, 1);
 }
 if ($attrs) {
  $svg = preg_replace('/<svg/', '<svg ' . $attrs, $svg, 1);
 }
 return $svg;
}
function _icon_registry(): array {
 return [
  'map-pin' => '
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M12 2C8.686 2 6 4.686 6 8c0 4.418 6 12 6 12s6-7.582 6-12c0-3.314-2.686-6-6-6z"/>
    <circle cx="12" cy="8" r="2.5"/>
  </svg>',
  'search' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
	<path d="M21 21l-6 -6" />
</svg>
    ',
  'close' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x-mark">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M12 16l3.644 3.644a1.21 1.21 0 0 0 1.712 0l2.288 -2.288a1.21 1.21 0 0 0 0 -1.712l-3.644 -3.644l3.644 -3.644a1.21 1.21 0 0 0 0 -1.712l-2.288 -2.288a1.21 1.21 0 0 0 -1.712 0l-3.644 3.644l-3.644 -3.644a1.21 1.21 0 0 0 -1.712 0l-2.288 2.288a1.21 1.21 0 0 0 0 1.712l3.644 3.644l-3.644 3.644a1.21 1.21 0 0 0 0 1.712l2.288 2.288a1.21 1.21 0 0 0 1.712 0l3.644 -3.644" />
</svg>
    ',
  'event' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-bolt">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M13.5 21h-7.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v5" />
	<path d="M16 3v4" />
	<path d="M8 3v4" />
	<path d="M4 11h16" />
	<path d="M19 16l-2 3h4l-2 3" />
</svg>
    ',
  'task' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-list-details">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M13 5h8" />
	<path d="M13 9h5" />
	<path d="M13 15h8" />
	<path d="M13 19h5" />
	<path d="M3 5a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1l0 -4" />
	<path d="M3 15a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1l0 -4" />
</svg>
    ',
  'blog' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-blockquote">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M6 15h15" />
	<path d="M21 19h-15" />
	<path d="M15 11h6" />
	<path d="M21 7h-6" />
	<path d="M9 9h1a1 1 0 1 1 -1 1v-2.5a2 2 0 0 1 2 -2" />
	<path d="M3 9h1a1 1 0 1 1 -1 1v-2.5a2 2 0 0 1 2 -2" />
</svg>
    ',
  'trip' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-rocket">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M4 13a8 8 0 0 1 7 7a6 6 0 0 0 3 -5a9 9 0 0 0 6 -8a3 3 0 0 0 -3 -3a9 9 0 0 0 -8 6a6 6 0 0 0 -5 3" />
	<path d="M7 14a6 6 0 0 0 -3 6a6 6 0 0 0 6 -3" />
	<path d="M14 9a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
</svg>
    ',
  'gallery' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-google-photos">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M7.5 7c2.485 0 4.5 1.974 4.5 4.409v.591h-8.397a.61 .61 0 0 1 -.426 -.173a.585 .585 0 0 1 -.177 -.418c0 -2.435 2.015 -4.409 4.5 -4.409" />
	<path d="M16.5 17c-2.485 0 -4.5 -1.974 -4.5 -4.409v-.591h8.397c.333 0 .603 .265 .603 .591c0 2.435 -2.015 4.409 -4.5 4.409" />
	<path d="M7 16.5c0 -2.485 1.972 -4.5 4.405 -4.5h.595v8.392a.61 .61 0 0 1 -.173 .431a.584 .584 0 0 1 -.422 .177c-2.433 0 -4.405 -2.015 -4.405 -4.5" />
	<path d="M17 7.5c0 2.485 -1.972 4.5 -4.405 4.5h-.595v-8.397a.61 .61 0 0 1 .175 -.428a.584 .584 0 0 1 .42 -.175c2.433 0 4.405 2.015 4.405 4.5" />
</svg>
    ',
  'location' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
	<path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0" />
</svg>
    ',
  'profile' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-circle">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
	<path d="M9 10a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
	<path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
</svg>
    ',
  'plus' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-plus">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
	<path d="M9 12h6" />
	<path d="M12 9v6" />
</svg>
    ',
  'google' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-google">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M20.945 11a9 9 0 1 1 -3.284 -5.997l-2.655 2.392a5.5 5.5 0 1 0 2.119 6.605h-4.125v-3h7.945" />
</svg>
    ',
  'arrow-right' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-big-right-line">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M12 9v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-6v-6h6" />
	<path d="M3 9v6" />
</svg>
    ',
  'arrow-left' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-big-left-line">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M12 15v3.586a1 1 0 0 1 -1.707 .707l-6.586 -6.586a1 1 0 0 1 0 -1.414l6.586 -6.586a1 1 0 0 1 1.707 .707v3.586h6v6h-6" />
	<path d="M21 15v-6" />
</svg>
    ',
  'arrow-up' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-big-up-lines">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M9 12h-3.586a1 1 0 0 1 -.707 -1.707l6.586 -6.586a1 1 0 0 1 1.414 0l6.586 6.586a1 1 0 0 1 -.707 1.707h-3.586v3h-6v-3" />
	<path d="M9 21h6" />
	<path d="M9 18h6" />
</svg>
    ',
  'arrow-down' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24
24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline
icon-tabler-arrow-big-down-lines">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M15 12h3.586a1 1 0 0 1 .707 1.707l-6.586 6.586a1 1 0 0 1 -1.414 0l-6.586 -6.586a1 1 0 0 1 .707 -1.707h3.586v-3h6v3" />
	<path d="M15 3h-6" />
	<path d="M15 6h-6" />
</svg>
    ',
  'flash' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bolt">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M13 3l0 7l6 0l-8 11l0 -7l-6 0l8 -11" />
</svg>
    ',
  'calendar' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-time">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4" />
	<path d="M14 18a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
	<path d="M15 3v4" />
	<path d="M7 3v4" />
	<path d="M3 11h16" />
	<path d="M18 16.496v1.504l1 1" />
</svg>
    ',
  'nav-right' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-right">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M13 7h-6l4 5l-4 5h6l4 -5l-4 -5" />
</svg>
    ',
  'nav-left' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-left">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M11 17h6l-4 -5l4 -5h-6l-4 5l4 5" />
</svg>
    ',
  'send' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-send-2">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M4.698 4.034l16.302 7.966l-16.302 7.966a.503 .503 0 0 1 -.546 -.124a.555 .555 0 0 1 -.12 -.568l2.468 -7.274l-2.468 -7.274a.555 .555 0 0 1 .12 -.568a.503 .503 0 0 1 .546 -.124" />
	<path d="M6.5 12h14.5" />
</svg>
    ',
  'instagram' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-instagram">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M4 8a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4l0 -8" />
	<path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
	<path d="M16.5 7.5v.01" />
</svg>
    ',
  'facebook' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-facebook">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
</svg>
    ',
  'youtube' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-youtube">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M2 8a4 4 0 0 1 4 -4h12a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-12a4 4 0 0 1 -4 -4v-8" />
	<path d="M10 9l5 3l-5 3l0 -6" />
</svg>
    ',
  'chat' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-messages">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10" />
	<path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2" />
</svg>
    ',
  'delete' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M4 7l16 0" />
	<path d="M10 11l0 6" />
	<path d="M14 11l0 6" />
	<path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
	<path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
</svg>
    ',
  'culinary' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bowl-chopsticks">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M4 11h16a1 1 0 0 1 1 1v.5c0 1.5 -2.517 5.573 -4 6.5v1a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1 -1v-1c-1.687 -1.054 -4 -5 -4 -6.5v-.5a1 1 0 0 1 1 -1" />
	<path d="M19 7l-14 1" />
	<path d="M19 2l-14 3" />
</svg>
    ',
  'building' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-bank">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M3 21l18 0" />
	<path d="M3 10l18 0" />
	<path d="M5 6l7 -3l7 3" />
	<path d="M4 10l0 11" />
	<path d="M20 10l0 11" />
	<path d="M8 14l0 3" />
	<path d="M12 14l0 3" />
	<path d="M16 14l0 3" />
</svg>
    ',
  'eye' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
	<path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
</svg>
    ',
  'eye-off' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-eye-off">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" />
	<path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87" />
	<path d="M3 3l18 18" />
</svg>
    ',
  'explore' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin-search">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M14.916 11.707a3 3 0 1 0 -2.916 2.293" />
	<path d="M11.991 21.485a1.994 1.994 0 0 1 -1.404 -.585l-4.244 -4.243a8 8 0 1 1 13.651 -5.351" />
	<path d="M15 18a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
	<path d="M20.2 20.2l1.8 1.8" />
</svg>
    ',
  'saved' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
	<path d="M10 14a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
	<path d="M14 4l0 4l-6 0l0 -4" />
</svg>
    ',
  'address' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-pins">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M10.828 9.828a4 4 0 1 0 -5.656 0l2.828 2.829l2.828 -2.829" />
	<path d="M8 7l0 .01" />
	<path d="M18.828 17.828a4 4 0 1 0 -5.656 0l2.828 2.829l2.828 -2.829" />
	<path d="M16 15l0 .01" />
</svg>
    ',
  'info' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-info-square-rounded">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M12 9h.01" />
	<path d="M11 12h1v4h1" />
	<path d="M12 3c7.2 0 9 1.8 9 9c0 7.2 -1.8 9 -9 9c-7.2 0 -9 -1.8 -9 -9c0 -7.2 1.8 -9 9 -9" />
</svg>
    ',
  'location-add' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-current-location">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M9 12a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
	<path d="M4 12a8 8 0 1 0 16 0a8 8 0 1 0 -16 0" />
	<path d="M12 2l0 2" />
	<path d="M12 20l0 2" />
	<path d="M20 12l2 0" />
	<path d="M2 12l2 0" />
</svg>
    ',
  'lock' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-lock">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6" />
	<path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
	<path d="M8 11v-4a4 4 0 1 1 8 0v4" />
</svg>
    ',
  'unlock' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-lock-open">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2l0 -6" />
	<path d="M11 16a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
	<path d="M8 11v-5a4 4 0 0 1 8 0" />
</svg>
    ',
  'rute' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24
24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline
icon-tabler-chart-dots-3">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M3 7a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
	<path d="M14 15a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
	<path d="M15 6a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
	<path d="M3 18a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
	<path d="M9 17l5 -1.5" />
	<path d="M6.5 8.5l7.81 5.37" />
	<path d="M7 7l8 -1" />
</svg>
    ',
  'list' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-list-numbers">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M11 6h9" />
	<path d="M11 12h9" />
	<path d="M12 18h8" />
	<path d="M4 16a2 2 0 1 1 4 0c0 .591 -.5 1 -1 1.5l-3 2.5h4" />
	<path d="M6 10v-6l-2 2" />
</svg>
    ',
  'star' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-carambola">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M17.286 21.09q -1.69 .001 -5.288 -2.615q -3.596 2.617 -5.288 2.616q -2.726 0 -.495 -6.8q -9.389 -6.775 2.135 -6.775h.076q 1.785 -5.516 3.574 -5.516q 1.785 0 3.574 5.516h.076q 11.525 0 2.133 6.774q 2.23 6.802 -.497 6.8" />
</svg>
    ',
  'star-filled' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-carambola">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M17.108 22.085c-1.266 -.068 -2.924 -.859 -5.071 -2.355l-.04 -.027l-.037 .027c-2.147 1.497 -3.804 2.288 -5.072 2.356l-.178 .005c-2.747 0 -3.097 -2.64 -1.718 -7.244l.054 -.178l-.1 -.075c-6.056 -4.638 -5.046 -7.848 2.554 -8.066l.202 -.005l.115 -.326c1.184 -3.33 2.426 -5.085 4.027 -5.192l.156 -.005c1.674 0 2.957 1.76 4.182 5.197l.114 .326l.204 .005c7.6 .218 8.61 3.428 2.553 8.065l-.102 .075l.055 .178c1.35 4.512 1.04 7.137 -1.556 7.24l-.163 .003z" />
</svg>
    ',
  'disconnect' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plug-connected-x">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M20 16l-4 4" />
	<path d="M7 12l5 5l-1.5 1.5a3.536 3.536 0 1 1 -5 -5l1.5 -1.5" />
	<path d="M17 12l-5 -5l1.5 -1.5a3.536 3.536 0 1 1 5 5l-1.5 1.5" />
	<path d="M3 21l2.5 -2.5" />
	<path d="M18.5 5.5l2.5 -2.5" />
	<path d="M10 11l-2 2" />
	<path d="M13 14l-2 2" />
	<path d="M16 16l4 4" />
</svg>
    ',
  'warning' => '
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-alert-triangle">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M12 9v4" />
	<path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0" />
	<path d="M12 16h.01" />
</svg>
    ',


 ];
}