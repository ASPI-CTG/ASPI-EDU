<?php
/* ASPI shared public footer — contact cards + original homepage footer. */
$aspiFooterName = $aspiGlobalName ?? (isset($siteName) ? (string)$siteName : 'আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট');
$aspiFooterTagline = $aspiGlobalTagline ?? (isset($tagline) ? (string)$tagline : 'একটাই লক্ষ্য, হতে হবে দক্ষ।');
$aspiFooterAddress = $aspiGlobalAddress ?? (isset($address) ? (string)$address : 'দক্ষিণ হাসিমপুর, দোহাজারী, চন্দনাইশ, চট্টগ্রাম');
$aspiFooterPhone = $aspiGlobalPhone ?? (isset($phone) ? (string)$phone : '+8801847310310');
$aspiFooterEmail = $aspiGlobalEmail ?? (isset($email) ? (string)$email : 'info@aspi.edu.bd');
$aspiFooterFacebook = $aspiGlobalFacebook ?? (isset($facebook) ? (string)$facebook : 'https://www.facebook.com/ashabsirajpolytechnicinstitute');
$aspiFooterWhatsapp = $aspiGlobalWhatsapp ?? (isset($wa) ? (string)$wa : '8801847310310');

$aspiFooterSocials = [];
$aspiFooterSocialPlatforms = [
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
if (!function_exists('aspi_global_setting')) {
  /* Fallback is normally provided by the shared header. */
}
if (function_exists('aspi_global_setting') && aspi_global_setting($pdo, 'social_show_footer', '1') !== '0') {
 foreach($aspiFooterSocialPlatforms as $key=>$meta){
  $url=trim(aspi_global_setting($pdo,'social_'.$key.'_url',$key==='facebook' ? aspi_global_setting($pdo,'facebook_url',aspi_global_setting($pdo,'facebook','')) : ''));
  if($url!=='' && filter_var($url,FILTER_VALIDATE_URL) && in_array(strtolower((string)parse_url($url,PHP_URL_SCHEME)),['http','https'],true)){
   $aspiFooterSocials[]=['label'=>$meta[0],'icon'=>$meta[1],'url'=>$url];
  }
 }
}

?>
<style>
.aspi-footer-socials{display:flex;align-items:center;gap:9px;flex-wrap:wrap;justify-content:flex-end;margin-left:12px}
.aspi-footer-socials a{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:11px;background:#ffffff12;border:1px solid #ffffff1c;color:#e6efff!important;font-size:15px;transition:background .18s,transform .18s}
.aspi-footer-socials a:hover{background:#087f8c!important;transform:translateY(-2px)}
@media(max-width:900px){.aspi-footer-socials{width:100%;justify-content:center;margin:15px auto 0}.aspi-footer-socials a{width:38px;height:38px}}
</style>

<?php if (empty($aspiHideContactStrip)): ?><section class="contactstrip" id="contact"><div class="container contactgrid"><a class="contactitem" href="tel:<?=aspi_global_h($aspiFooterPhone)?>"><i class="fa-solid fa-phone" aria-hidden="true"></i><span><b>ফোনে যোগাযোগ</b><span><?=aspi_global_h($aspiFooterPhone)?></span></span></a><a class="contactitem" href="https://wa.me/<?=aspi_global_h($aspiFooterWhatsapp)?>" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i><span><b>WhatsApp</b><span>সরাসরি বার্তা পাঠান</span></span></a><a class="contactitem" href="mailto:<?=aspi_global_h($aspiFooterEmail)?>"><i class="fa-solid fa-envelope" aria-hidden="true"></i><span><b>ইমেইল</b><span><?=aspi_global_h($aspiFooterEmail)?></span></span></a></div></section><?php endif; ?>
<footer class="footer"><div class="container"><div class="footer-top"><div class="footer-brand"><img src="/assets/aspi-logo.svg" alt="ASPI"><span><b><?=aspi_global_h($aspiFooterName)?></b><small><?=aspi_global_h($aspiFooterTagline)?></small><small><?=aspi_global_h($aspiFooterAddress)?></small></span></div><div class="footer-links"><a href="/live/institution.php">প্রতিষ্ঠান পরিচিতি</a><a href="/live/founder.php">প্রতিষ্ঠাতা</a><a href="/live/foundation.php">ফাউন্ডেশন</a><a href="/#courses">NSDA কোর্স</a><a href="/#gallery">ফটো গ্যালারি</a><a href="/live/notices.php">বিজ্ঞপ্তি</a><a href="/#apply">আবেদন</a><a href="/live/admin/login.php">অ্যাডমিন লগইন</a></div></div><?php if($aspiFooterSocials):?><div class="aspi-footer-socials" aria-label="সোশ্যাল মিডিয়া"><?php foreach($aspiFooterSocials as $social):?><a href="<?=aspi_global_h($social['url'])?>" target="_blank" rel="noopener noreferrer" aria-label="<?=aspi_global_h($social['label'])?>" title="<?=aspi_global_h($social['label'])?>"><i class="<?=aspi_global_h($social['icon'])?>" aria-hidden="true"></i></a><?php endforeach;?></div><?php endif;?><div class="footer-bottom"><span class="footer-copyright">© <?=date('Y')?> <?=aspi_global_h($aspiFooterName)?>. সর্বস্বত্ব সংরক্ষিত।</span><span class="footer-credit">Made With Love: <a href="https://saif.info.bd/" target="_blank" rel="noopener">Saif Al-Islam</a></span></div></div></footer>
<script>
(()=> {
 if(location.pathname!=='/' && !location.pathname.endsWith('/index.php')){
   document.querySelectorAll('.nav-dropbtn').forEach(btn=>{
     if(btn.dataset.aspiDropdownBound)return;
     btn.dataset.aspiDropdownBound='1';
     btn.addEventListener('click',()=>{
       const box=btn.closest('.nav-dropdown'), open=box.classList.toggle('is-open');
       btn.setAttribute('aria-expanded',open?'true':'false');
       document.querySelectorAll('.nav-dropdown').forEach(other=>{if(other!==box){other.classList.remove('is-open');other.querySelector('.nav-dropbtn')?.setAttribute('aria-expanded','false');}});
     });
   });
 }
 document.addEventListener('click',e=>{if(!e.target.closest('.nav-dropdown'))document.querySelectorAll('.nav-dropdown.is-open').forEach(box=>{box.classList.remove('is-open');box.querySelector('.nav-dropbtn')?.setAttribute('aria-expanded','false');});});
 document.addEventListener('keydown',e=>{if(e.key==='Escape'){document.querySelectorAll('.nav-dropdown.is-open').forEach(box=>{box.classList.remove('is-open');box.querySelector('.nav-dropbtn')?.setAttribute('aria-expanded','false');});document.getElementById('navLinks')?.classList.remove('open');document.getElementById('navToggle')?.setAttribute('aria-expanded','false');}});
})();
</script>
