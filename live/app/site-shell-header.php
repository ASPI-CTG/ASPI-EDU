<?php
// Shared ASPI public-site header. Uses the same site settings as the homepage.
if (!function_exists('aspi_shell_setting')) {
    function aspi_shell_setting(PDO $pdo, string $key, string $fallback=''): string {
        static $cache = null;
        if ($cache === null) {
            $cache = [];
            try { foreach ($pdo->query('SELECT setting_key, setting_value FROM site_settings')->fetchAll() as $row) $cache[$row['setting_key']] = $row['setting_value']; }
            catch (Throwable $e) { $cache = []; }
        }
        return (string)($cache[$key] ?? $fallback);
    }
}
if (!function_exists('aspi_shell_h')) { function aspi_shell_h($v): string { return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); } }
$aspiShellName = aspi_shell_setting($pdo, 'site_name', 'আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট');
$aspiShellTagline = aspi_shell_setting($pdo, 'tagline', 'একটাই লক্ষ্য, হতে হবে দক্ষ।');
$aspiShellAddress = aspi_shell_setting($pdo, 'address', 'দক্ষিণ হাশিমপুর, দোহাজারী, চন্দনাইশ, চট্টগ্রাম');
$aspiShellPhone = aspi_shell_setting($pdo, 'phone', '01847310310');
$aspiShellEmail = aspi_shell_setting($pdo, 'email', 'info@aspi.edu.bd');
$aspiShellFacebook = aspi_shell_setting($pdo, 'facebook', 'https://www.facebook.com/ashabsirajpolytechnicinstitute');
$aspiShellHome = (strpos(str_replace('\\','/', (string)($_SERVER['SCRIPT_NAME'] ?? '')), '/live/') === false);
$aspiShellAnchor = static fn(string $anchor): string => $aspiShellHome ? $anchor : '/'.$anchor;
?>
<div class="aspi-shell-topline"><div class="aspi-shell-container"><span><i class="fa-solid fa-location-dot" aria-hidden="true"></i> <?=aspi_shell_h($aspiShellAddress)?></span><span><i class="fa-solid fa-phone" aria-hidden="true"></i> <?=aspi_shell_h($aspiShellPhone)?> &nbsp; · &nbsp; <a href="mailto:<?=aspi_shell_h($aspiShellEmail)?>"><i class="fa-solid fa-envelope-open-text" aria-hidden="true"></i> <?=aspi_shell_h($aspiShellEmail)?></a></span></div></div>
<header class="aspi-shell-header"><div class="aspi-shell-container aspi-shell-nav"><a class="aspi-shell-brand" href="/"><img src="/assets/aspi-logo.svg" alt="ASPI Logo"><span><strong><?=aspi_shell_h($aspiShellName)?></strong><small><?=aspi_shell_h($aspiShellTagline)?></small></span></a><button class="aspi-shell-toggle" id="aspiNavToggle" type="button" aria-label="মেনু খুলুন" aria-expanded="false"><i class="fa-solid fa-bars" aria-hidden="true"></i></button><nav class="aspi-shell-links" id="aspiNavLinks" aria-label="প্রধান মেনু">
<div class="aspi-shell-dropdown"><button class="aspi-shell-dropbtn" type="button" aria-expanded="false">আমাদের সম্পর্কে <i class="fa-solid fa-chevron-down" aria-hidden="true"></i></button><div class="aspi-shell-dropmenu"><a href="/live/institution.php">প্রতিষ্ঠান পরিচিতি</a><a href="/live/founder.php">প্রতিষ্ঠাতার পরিচিতি</a><a href="/live/foundation.php">আসহাব সিরাজ ফাউন্ডেশন</a></div></div>
<div class="aspi-shell-dropdown"><button class="aspi-shell-dropbtn" type="button" aria-expanded="false">শিক্ষাক্রম <i class="fa-solid fa-chevron-down" aria-hidden="true"></i></button><div class="aspi-shell-dropmenu"><a href="<?=aspi_shell_h($aspiShellAnchor('#diploma'))?>">ডিপ্লোমা ইন ইঞ্জিনিয়ারিং</a><a href="<?=aspi_shell_h($aspiShellAnchor('#courses'))?>">NSDA স্কিল কোর্স</a></div></div>
<div class="aspi-shell-dropdown"><button class="aspi-shell-dropbtn" type="button" aria-expanded="false">মিডিয়া ও আপডেট <i class="fa-solid fa-chevron-down" aria-hidden="true"></i></button><div class="aspi-shell-dropmenu"><a href="<?=aspi_shell_h($aspiShellAnchor('#gallery'))?>">ফটো গ্যালারি</a><a href="/live/notices.php">সর্বশেষ বিজ্ঞপ্তি</a><a href="/live/posts.php">খবর, অনুষ্ঠান ও কর্মশালা</a></div></div>
<a href="<?=aspi_shell_h($aspiShellAnchor('#contact'))?>">যোগাযোগ</a><a class="aspi-shell-cta" href="<?=aspi_shell_h($aspiShellAnchor('#apply'))?>">কোর্স আবেদন <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></nav></div></header>
