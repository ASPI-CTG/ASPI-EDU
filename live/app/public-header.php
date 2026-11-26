<?php
/* ASPI shared public header — single source for all public pages. */
if (!function_exists('aspi_global_setting')) {
    function aspi_global_setting(PDO $pdo, string $key, string $fallback = ''): string {
        static $settings = null;
        if ($settings === null) {
            $settings = [];
            try {
                foreach ($pdo->query('SELECT setting_key, setting_value FROM site_settings')->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    $settings[(string)$row['setting_key']] = (string)$row['setting_value'];
                }
            } catch (Throwable $e) { /* Use safe defaults if settings table is unavailable. */ }
        }
        return (string)($settings[$key] ?? $fallback);
    }
}
if (!function_exists('aspi_global_h')) {
    function aspi_global_h($value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
$aspiGlobalName = isset($siteName) && trim((string)$siteName) !== '' ? (string)$siteName : aspi_global_setting($pdo, 'site_name', 'আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট');
$aspiGlobalTagline = isset($tagline) && trim((string)$tagline) !== '' ? (string)$tagline : (isset($siteTagline) && trim((string)$siteTagline) !== '' ? (string)$siteTagline : aspi_global_setting($pdo, 'tagline', 'একটাই লক্ষ্য, হতে হবে দক্ষ।'));
$aspiGlobalAddress = isset($address) && trim((string)$address) !== '' ? (string)$address : aspi_global_setting($pdo, 'address', 'দক্ষিণ হাসিমপুর, দোহাজারী, চন্দনাইশ, চট্টগ্রাম');
$aspiGlobalPhone = isset($phone) && trim((string)$phone) !== '' ? (string)$phone : aspi_global_setting($pdo, 'phone', '+8801847310310');
$aspiGlobalEmail = isset($email) && trim((string)$email) !== '' ? (string)$email : aspi_global_setting($pdo, 'email', 'info@aspi.edu.bd');
$aspiGlobalFacebook = isset($facebook) && trim((string)$facebook) !== '' ? (string)$facebook : aspi_global_setting($pdo, 'facebook_url', 'https://www.facebook.com/ashabsirajpolytechnicinstitute');
$aspiGlobalWhatsapp = isset($wa) && trim((string)$wa) !== '' ? (string)$wa : preg_replace('/\D+/', '', aspi_global_setting($pdo, 'whatsapp', '8801847310310'));
$aspiApplyLabel = aspi_global_setting($pdo, 'nav_apply_label', 'কোর্স আবেদন');

$aspiSocialPlatforms = [
 'facebook'=>['Facebook','fa-brands fa-facebook-f'],
 'x'=>['X','fa-brands fa-x-twitter'],
 'instagram'=>['Instagram','fa-brands fa-instagram'],
 'youtube'=>['YouTube','fa-brands fa-youtube'],
 'linkedin'=>['LinkedIn','fa-brands fa-linkedin-in'],
 'tiktok'=>['TikTok','fa-brands fa-tiktok'],
 'telegram'=>['Telegram','fa-brands fa-telegram'],
 'whatsapp'=>['WhatsApp','fa-brands fa-whatsapp'],
 'pinterest'=>['Pinterest','fa-brands fa-pinterest-p'],
 'threads'=>['Threads','fa-brands fa-threads'],
 'github'=>['GitHub','fa-brands fa-github'],
 'website'=>['Website','fa-solid fa-globe']
];
$aspiHeaderSocials = [];
if (aspi_global_setting($pdo, 'social_show_header', '0') === '1') {
 foreach ($aspiSocialPlatforms as $key=>$meta) {
  $url = trim(aspi_global_setting($pdo, 'social_'.$key.'_url', $key==='facebook' ? aspi_global_setting($pdo, 'facebook_url', aspi_global_setting($pdo, 'facebook', '')) : ''));
  if ($url !== '' && filter_var($url, FILTER_VALIDATE_URL) && in_array(strtolower((string)parse_url($url, PHP_URL_SCHEME)), ['http','https'], true)) {
   $aspiHeaderSocials[] = ['label'=>$meta[0], 'icon'=>$meta[1], 'url'=>$url];
  }
 }
}

?>
<style>
/* Dashboard-managed social links: desktop top-bar only; mobile utility bar stays unchanged. */
.topline-right-group{display:flex;align-items:center;justify-content:flex-end;gap:12px;margin-left:auto;flex:0 0 auto;white-space:nowrap}
.topline-right-group .topline-desktop-contact{display:flex;align-items:center;white-space:nowrap}
.topline-socials{display:none;align-items:center;justify-content:flex-end;gap:9px;flex:0 0 auto}
.topline-socials a{display:inline-flex;align-items:center;justify-content:center;width:25px;height:25px;border-radius:7px;color:#fff!important;background:#ffffff12;font-size:13px;transition:background .18s,transform .18s}
.topline-socials a:hover{background:#087f8c!important;transform:translateY(-1px)}
@media(min-width:901px){.topline-socials{display:flex}}
.topline-social-separator{display:none;width:1px;height:20px;background:rgba(255,255,255,.38);margin:0 5px;flex:0 0 1px}
@media(min-width:901px){.topline-social-separator{display:block}}

/* Desktop-only duplicate contact shortcut fix: keep full phone/email text once.
   Mobile phone and WhatsApp shortcuts remain unchanged below 901px. */
@media (min-width: 901px) {
  .topline .topline-mobile-actions { display: none !important; }
}
</style>
<div class="topline"><div class="container"><span class="topline-location"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> <?=aspi_global_h($aspiGlobalAddress)?></span><div class="topline-right-group"><span class="topline-desktop-contact"><i class="fa-solid fa-phone" aria-hidden="true"></i> <?=aspi_global_h($aspiGlobalPhone)?> &nbsp; · &nbsp; <a href="mailto:<?=aspi_global_h($aspiGlobalEmail)?>"><i class="fa-solid fa-envelope-open-text" aria-hidden="true"></i> <?=aspi_global_h($aspiGlobalEmail)?></a></span><span class="topline-mobile-actions"><a href="tel:<?=aspi_global_h($aspiGlobalPhone)?>" aria-label="ফোনে যোগাযোগ"><i class="fa-solid fa-phone" aria-hidden="true"></i></a><a href="https://wa.me/<?=aspi_global_h($aspiGlobalWhatsapp)?>" target="_blank" rel="noopener" aria-label="WhatsApp-এ যোগাযোগ"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></a></span><?php if($aspiHeaderSocials):?><span class="topline-social-separator" aria-hidden="true"></span><span class="topline-socials" aria-label="সামাজিক যোগাযোগ"><?php foreach($aspiHeaderSocials as $social):?><a href="<?=aspi_global_h($social['url'])?>" target="_blank" rel="noopener noreferrer" aria-label="<?=aspi_global_h($social['label'])?>" title="<?=aspi_global_h($social['label'])?>"><i class="<?=aspi_global_h($social['icon'])?>" aria-hidden="true"></i></a><?php endforeach;?></span><?php endif;?></div></div></div>
<header class="header"><div class="container nav"><a class="brand" href="/"><img src="/assets/aspi-logo.svg" alt="ASPI Logo"><span><strong><?=aspi_global_h($aspiGlobalName)?></strong><small><?=aspi_global_h($aspiGlobalTagline)?></small></span></a><button class="navtoggle" id="navToggle" type="button" aria-label="মেনু খুলুন" aria-expanded="false"><i class="fa-solid fa-bars" aria-hidden="true"></i></button><nav class="navlinks" id="navLinks" aria-label="প্রধান মেনু">
<div class="nav-dropdown"><button class="nav-dropbtn" type="button" aria-expanded="false">আমাদের সম্পর্কে <i class="fa-solid fa-chevron-down" aria-hidden="true"></i></button><div class="nav-dropmenu"><a href="/live/institution.php">প্রতিষ্ঠান পরিচিতি</a><a href="/live/founder.php">প্রতিষ্ঠাতার পরিচিতি</a><a href="/live/foundation.php">আসহাব সিরাজ ফাউন্ডেশন</a></div></div>
<div class="nav-dropdown"><button class="nav-dropbtn" type="button" aria-expanded="false">শিক্ষাক্রম <i class="fa-solid fa-chevron-down" aria-hidden="true"></i></button><div class="nav-dropmenu"><a href="/#diploma">ডিপ্লোমা ইন ইঞ্জিনিয়ারিং</a><a href="/#courses">NSDA স্কিল কোর্স</a></div></div>
<div class="nav-dropdown"><button class="nav-dropbtn" type="button" aria-expanded="false">মিডিয়া ও আপডেট <i class="fa-solid fa-chevron-down" aria-hidden="true"></i></button><div class="nav-dropmenu"><a href="/#gallery">ফটো গ্যালারি</a><a href="/live/notices.php">সর্বশেষ বিজ্ঞপ্তি</a><a href="/live/posts.php">খবর, অনুষ্ঠান ও কর্মশালা</a></div></div><a href="/live/forms.php">ডাউনলোড</a>
<a href="/#contact">যোগাযোগ</a><a class="navcta" href="/#apply"><?=aspi_global_h($aspiApplyLabel)?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></nav></div></header>
