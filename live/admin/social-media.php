<?php
require_once __DIR__.'/../app/bootstrap.php';
require_admin();
$active='social';
$pageTitle='সোশ্যাল মিডিয়া';
$platforms=[
 'facebook'=>['Facebook','fa-brands fa-facebook-f','https://www.facebook.com/'],
 'x'=>['X (Twitter)','fa-brands fa-x-twitter','https://x.com/'],
 'instagram'=>['Instagram','fa-brands fa-instagram','https://www.instagram.com/'],
 'youtube'=>['YouTube','fa-brands fa-youtube','https://www.youtube.com/'],
 'linkedin'=>['LinkedIn','fa-brands fa-linkedin-in','https://www.linkedin.com/'],
 'tiktok'=>['TikTok','fa-brands fa-tiktok','https://www.tiktok.com/'],
 'telegram'=>['Telegram','fa-brands fa-telegram','https://t.me/'],
 'whatsapp'=>['WhatsApp','fa-brands fa-whatsapp','https://wa.me/'],
 'pinterest'=>['Pinterest','fa-brands fa-pinterest-p','https://www.pinterest.com/'],
 'threads'=>['Threads','fa-brands fa-threads','https://www.threads.net/'],
 'github'=>['GitHub','fa-brands fa-github','https://github.com/'],
 'website'=>['অন্য ওয়েবসাইট','fa-solid fa-globe','https://']
];
$values=[];
try { foreach($pdo->query("SELECT setting_key,setting_value FROM site_settings")->fetchAll() as $r) $values[$r['setting_key']]=$r['setting_value']; } catch(Throwable $e) {}
$error=''; $flash=$_SESSION['flash']??''; unset($_SESSION['flash']);
if($_SERVER['REQUEST_METHOD']==='POST'){
 csrf_check();
 $next=[];
 foreach($platforms as $key=>$meta){
  $url=trim((string)($_POST['social_'.$key.'_url']??''));
  if($url!==''){
   if(!filter_var($url,FILTER_VALIDATE_URL) || !in_array(strtolower((string)parse_url($url,PHP_URL_SCHEME)),['http','https'],true)){
    $error=$meta[0].' লিংকটি সঠিক URL নয়। https:// দিয়ে সম্পূর্ণ লিংক দিন।'; break;
   }
  }
  $next['social_'.$key.'_url']=$url;
 }
 $next['social_show_header'] = isset($_POST['social_show_header']) ? '1':'0';
 $next['social_show_footer'] = isset($_POST['social_show_footer']) ? '1':'0';
 if(!$error){
  $st=$pdo->prepare('INSERT INTO site_settings(setting_key,setting_value) VALUES(?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)');
  foreach($next as $k=>$v){$st->execute([$k,$v]);$values[$k]=$v;}
  try{$log=$pdo->prepare("INSERT INTO activity_logs(action,details,created_at) VALUES(?,?,NOW())");$log->execute(['social_media_updated','সোশ্যাল মিডিয়া লিংক ও প্রদর্শন সেটিংস আপডেট']);}catch(Throwable $e){}
  $_SESSION['flash']='সোশ্যাল মিডিয়া লিংক ও প্রদর্শনের সেটিংস সংরক্ষণ করা হয়েছে।';
  header('Location: social-media.php'); exit;
 }
}
require __DIR__.'/_head.php';
?>
<style>
.social-hero{display:flex;align-items:center;justify-content:space-between;gap:20px;padding:24px 27px;border-radius:18px;background:linear-gradient(120deg,#10224b,#1c3974 65%,#087f8c);color:#fff;margin-bottom:20px}.social-hero h2{font-size:26px;font-weight:700;margin:0 0 5px}.social-hero p{margin:0;color:#d9e5f7;font-size:13px;max-width:700px}.social-hero-icon{width:58px;height:58px;border-radius:17px;background:#ffffff18;display:grid;place-items:center;font-size:25px;flex:0 0 auto}.social-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.social-field{display:flex;gap:13px;align-items:flex-start;padding:15px;border:1px solid var(--line);border-radius:14px;background:#fff;min-width:0}.social-icon{width:43px;height:43px;border-radius:13px;background:#e8f5f5;color:var(--teal);display:grid;place-items:center;flex:0 0 43px;font-size:19px}.social-field .form-label{font-weight:700;color:var(--navy);margin-bottom:5px}.social-field .form-control{min-width:0}.social-placements{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.placement-option{display:flex;gap:12px;align-items:flex-start;border:1px solid var(--line);border-radius:13px;padding:15px;background:#fbfcff}.placement-option input{margin-top:5px;accent-color:var(--teal);width:18px;height:18px;flex:0 0 auto}.placement-option b{display:block;color:var(--navy)}.placement-option small{display:block;color:var(--muted);font-size:12px;margin-top:3px}.social-preview{display:flex;gap:9px;flex-wrap:wrap;margin-top:10px}.social-preview span{width:38px;height:38px;border-radius:12px;background:#e8f5f5;color:var(--teal);display:grid;place-items:center}@media(max-width:700px){.social-grid,.social-placements{grid-template-columns:1fr}.social-hero{padding:19px}.social-hero h2{font-size:22px}.social-hero-icon{width:45px;height:45px;font-size:20px}}
</style>
<section class="social-hero"><div><div class="page-kicker" style="color:#d9e5f7">GLOBAL WEBSITE SETTINGS</div><h2>সোশ্যাল মিডিয়া লিংক</h2><p>প্রতিষ্ঠানের অফিসিয়াল সামাজিক যোগাযোগমাধ্যমের লিংক একবার এখানে যোগ করুন। নির্বাচিত অবস্থানে সব পেইজে স্বয়ংক্রিয়ভাবে দেখানো হবে।</p></div><div class="social-hero-icon"><i class="fa-solid fa-share-nodes"></i></div></section>
<?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<?php if($flash):?><div class="alert alert-success"><?=e($flash)?></div><?php endif;?>
<form method="post" id="socialMediaForm">
<input type="hidden" name="csrf" value="<?=e(csrf_token())?>">
<section class="panel mb-4"><div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3"><div><div class="section-label">১. অফিসিয়াল সোশ্যাল মিডিয়া URL</div><div class="hint">প্রতিটি প্ল্যাটফর্মের সম্পূর্ণ পেজ/চ্যানেল লিংক দিন। যেটি ব্যবহার করেন না, সেটি ফাঁকা রাখুন।</div></div><span class="badge text-bg-light">১২টি প্ল্যাটফর্ম</span></div>
<div class="social-grid">
<?php foreach($platforms as $key=>$meta): $v=(string)($values['social_'.$key.'_url']??''); if($key==='facebook' && $v==='') $v=(string)($values['facebook_url']??$values['facebook']??''); ?>
<div class="social-field"><span class="social-icon"><i class="<?=e($meta[1])?>"></i></span><div class="flex-grow-1 min-w-0"><label class="form-label" for="social_<?=e($key)?>"><?=e($meta[0])?></label><input class="form-control" type="url" id="social_<?=e($key)?>" name="social_<?=e($key)?>_url" value="<?=e($v)?>" placeholder="<?=e($meta[2])?>" inputmode="url"><div class="hint mt-1"><?= $v!=='' ? 'লিংক যোগ করা আছে' : 'লিংক না দিলে সাইটে আইকন দেখাবে না' ?></div></div></div>
<?php endforeach;?>
</div></section>
<section class="panel mb-4"><div class="section-label mb-1">২. কোন জায়গায় দেখাবেন?</div><div class="hint mb-3">এই সেটিংস সব পাবলিক পেইজে প্রযোজ্য হবে।</div><div class="social-placements">
<label class="placement-option"><input type="checkbox" name="social_show_header" value="1" <?=($values['social_show_header']??'0')==='1'?'checked':''?>><span><b>Desktop Header / Top Bar</b><small>ডেস্কটপে লোকেশন ও যোগাযোগের তথ্যের পাশে ছোট সোশ্যাল আইকন। মোবাইল হেডারে নতুন আইকন যোগ হবে না।</small></span></label>
<label class="placement-option"><input type="checkbox" name="social_show_footer" value="1" <?=($values['social_show_footer']??'1')!=='0'?'checked':''?>><span><b>Footer — সব ডিভাইস</b><small>ডেস্কটপ, ট্যাবলেট ও মোবাইলের ফুটারে সোশ্যাল আইকন। এটি প্রস্তাবিত ডিফল্ট অবস্থান।</small></span></label>
</div><div class="alert alert-info mt-3 mb-0"><i class="fa-solid fa-circle-info me-1"></i> এগুলো সোশ্যাল মিডিয়া প্রোফাইলের লিংক ও আইকন; লাইভ পোস্ট/ভিডিও ফিড নয়। ফিড এমবেড করতে আলাদা অনুমোদিত embed code/API প্রয়োজন হতে পারে।</div></section>
<div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4"><a class="btn btn-outline-secondary" href="../index.php" target="_blank" rel="noopener"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i> ওয়েবসাইট দেখুন</a><button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> সোশ্যাল মিডিয়া সংরক্ষণ করুন</button></div>
</form>
<?php require __DIR__.'/_foot.php';?>
