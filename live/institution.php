<?php
require_once __DIR__.'/app/bootstrap.php';
require_once __DIR__.'/app/page_content.php';
function aspi_page_setting(PDO $pdo,string $key,string $fallback=''):string{static $cache=null;if($cache===null){$cache=[];try{foreach($pdo->query('SELECT setting_key,setting_value FROM site_settings')->fetchAll() as $r)$cache[$r['setting_key']]=$r['setting_value'];}catch(Throwable $e){}}return (string)($cache[$key]??$fallback);}
function aspi_page_h($v):string{return htmlspecialchars((string)$v,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
$siteName=aspi_page_setting($pdo,'site_name','আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট');
$pageTitle='প্রতিষ্ঠান পরিচিতি';
$pageText=aspi_page_setting($pdo,'institution_intro','আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট একটি কারিগরি শিক্ষা প্রতিষ্ঠান। এখানে ডিপ্লোমা ইন ইঞ্জিনিয়ারিং শিক্ষাক্রমের পাশাপাশি দক্ষতাভিত্তিক প্রশিক্ষণ কার্যক্রম পরিচালিত হয়।');
$siteTagline=aspi_page_setting($pdo,'tagline','একটাই লক্ষ্য, হতে হবে দক্ষ।');
$pageEditorJson=aspi_page_setting($pdo,'page_institution_content','');
$pageEditorHtml=$pageEditorJson!==''?aspi_page_render_editor_json($pageEditorJson):'';
// Hide only the duplicated section heading; preserve all other saved editor content.
if ($pageEditorHtml !== '') {
    $pageEditorHtml = preg_replace_callback('~<h([1-6])\\b[^>]*>.*?</h\\1\\s*>~isu', static function ($m) {
        $heading = trim(preg_replace('/\\s+/u', ' ', html_entity_decode(strip_tags($m[0]), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
        return $heading === 'আমাদের সম্পর্কে' ? '' : $m[0];
    }, $pageEditorHtml);
}
?><!doctype html><html lang="bn"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="theme-color" content="#10224b"><title><?=aspi_page_h($pageTitle)?> | <?=aspi_page_h($siteName)?></title><link rel="icon" href="../assets/aspi-logo.svg"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"><style>
@font-face{font-family:Hind;src:url('../fonts/Hind-Siliguri-Regular.ttf') format('truetype');font-display:swap}@font-face{font-family:Hind;src:url('../fonts/Hind-Siliguri-Bold.ttf') format('truetype');font-weight:700;font-display:swap}*{box-sizing:border-box}a,a:hover,a:focus{text-decoration:none!important;border:0!important}body{margin:0;font-family:Hind,Arial,sans-serif;background:#f5f7fb;color:#172033;line-height:1.85}.about-wrap{width:min(1120px,92%);margin:auto}.about-hero{position:relative;overflow:hidden;background:linear-gradient(110deg,#081630f2,#15386ce8 60%,#087f8cd9),url('../assets/campus-1.webp') center/cover;color:#fff;padding:clamp(55px,8vw,95px) 0;border-radius:0 0 7% 7% / 0 0 5% 5%}.eyebrow{color:#f4bd42;font-size:13px;font-weight:700;letter-spacing:1px}.about-hero h1{font-size:clamp(32px,5vw,54px);line-height:1.25;margin:12px 0;max-width:850px}.about-hero p{font-size:17px;color:#e1eafa;max-width:800px;margin:0}.about-main{padding:42px 0 70px}.about-content{background:#fff;border:1px solid #e4e9f2;border-radius:24px;padding:clamp(22px,4vw,44px);box-shadow:0 20px 55px #10224b0b}.about-content h2{color:#10224b;font-size:25px;margin-top:0}.about-content p{white-space:pre-line;color:#53627a;font-size:17px;margin:0}@media(max-width:650px){.about-hero{padding:45px 0}.about-hero p{font-size:15px}.about-main{padding:24px 0 45px}.about-content{border-radius:17px}.about-content p{font-size:15px}}

.page-editor-content{color:#53627a;font-size:17px;line-height:1.9;overflow-wrap:anywhere}
.page-editor-content h2,.page-editor-content h3,.page-editor-content h4{color:#10224b;line-height:1.4;margin:1.35em 0 .55em}
.page-editor-content p{white-space:normal;color:inherit;font-size:inherit;margin:0 0 1em}
.page-editor-content ul,.page-editor-content ol{padding-left:1.5em;margin:0 0 1em}
.page-editor-content li{margin:.3em 0}
.page-editor-content img{display:block;max-width:100%;height:auto;border-radius:14px;margin:12px auto}
.page-editor-content figure{margin:1.3em 0;text-align:center}
.page-editor-content figcaption,.page-editor-content cite{display:block;font-size:.88em;color:#718096;margin-top:7px}
.page-editor-content blockquote{margin:1.2em 0;padding:16px 20px;background:#f3f7fc;border-left:4px solid #087f8c;border-radius:10px}
.page-editor-content blockquote p{margin:0}
.page-editor-content hr{border:0;border-top:2px solid #e2eaf4;margin:1.7em 0}
.page-editor-content .page-video{position:relative;width:100%;aspect-ratio:16/9;margin:1.4em 0;border-radius:14px;overflow:hidden;background:#0d1b3b}
.page-editor-content .page-video iframe{width:100%;height:100%;border:0}
.page-editor-content .page-table-wrap{width:100%;overflow-x:auto;margin:1.2em 0}
.page-editor-content table{width:100%;border-collapse:collapse;min-width:420px}
.page-editor-content td,.page-editor-content th{border:1px solid #dce4ef;padding:9px 12px}
.page-editor-content .page-checklist{list-style:none;padding-left:0}
.page-editor-content .page-warning{padding:16px 18px;background:#fff8e7;border-left:4px solid #f4bd42;border-radius:10px;margin:1em 0}
.page-editor-content pre{white-space:pre-wrap;overflow-wrap:anywhere;background:#f2f5fa;padding:14px;border-radius:10px;overflow-x:auto}
@media(max-width:650px){.page-editor-content{font-size:15px}.page-editor-content table{min-width:380px}}
.page-editor-content .page-media-gallery{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;margin:1.4em 0}.page-editor-content .page-gallery-item{margin:0;min-width:0;background:#f4f7fb;border:1px solid #e2e9f2;border-radius:13px;overflow:hidden}.page-editor-content .page-gallery-item img,.page-editor-content .page-gallery-item video{display:block;width:100%;height:100%;max-height:420px;aspect-ratio:4/3;object-fit:contain;background:#edf2f7}.page-editor-content .page-gallery-item figcaption{padding:8px 12px;color:#68748a;font-size:13px}.page-editor-content .page-gallery-embed{position:relative;aspect-ratio:16/9}.page-editor-content .page-gallery-embed iframe{position:absolute;inset:0;width:100%;height:100%;border:0}@media(max-width:650px){.page-editor-content .page-media-gallery{grid-template-columns:1fr;gap:10px}}
</style><link rel="stylesheet" href="/live/assets/public-shell.css?v=1.1.0">
</head><body><?php require __DIR__.'/app/public-header.php'; ?><section class="about-hero"><div class="about-wrap"><h1><?=aspi_page_h($pageTitle)?></h1><p><?=aspi_page_h($pageText)?></p></div></section><main class="about-main"><div class="about-wrap"><article class="about-content"><?php if($pageEditorHtml!==''):?><div class="page-editor-content"><?=$pageEditorHtml?></div><?php else:?><p><?=nl2br(aspi_page_h($pageText))?></p><?php endif;?></article></div></main><?php $aspiHideContactStrip = true; require __DIR__.'/app/public-footer.php'; ?><script>const mt=document.getElementById('navToggle'),mn=document.getElementById('navLinks');if(mt&&mn)mt.addEventListener('click',()=>{const open=mn.classList.toggle('open');mt.setAttribute('aria-expanded',String(open));mt.setAttribute('aria-label',open?'মেনু বন্ধ করুন':'মেনু খুলুন');mt.innerHTML=open?'<i class="fa-solid fa-xmark"></i>':'<i class="fa-solid fa-bars"></i>'});</script></body></html>