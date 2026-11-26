<?php
require_once __DIR__.'/../app/bootstrap.php';
require_admin();
$active='posts'; $pageTitle='খবর, অনুষ্ঠান ও কর্মশালা'; $error=''; $flash=$_SESSION['flash']??''; unset($_SESSION['flash']);
function aspi_post_upload_image(array $f): string {
 if(($f['error']??UPLOAD_ERR_NO_FILE)!==UPLOAD_ERR_OK) throw new RuntimeException('ছবি আপলোড করা যায়নি।');
 if(($f['size']??0)>8*1024*1024) throw new RuntimeException('ছবির সর্বোচ্চ সাইজ ৮ MB।');
 $mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
 $ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'][$mime]??'';
 if($ext==='' || !@getimagesize($f['tmp_name'])) throw new RuntimeException('শুধু JPG, PNG, WEBP বা GIF ছবি আপলোড করুন।');
 $dir=__DIR__.'/../uploads/site';
 if(!is_dir($dir)&&!mkdir($dir,0755,true)) throw new RuntimeException('আপলোড ফোল্ডার তৈরি করা যায়নি।');
 $name='post-media-'.date('YmdHis').'-'.bin2hex(random_bytes(5)).'.'.$ext;
 if(!move_uploaded_file($f['tmp_name'],$dir.'/'.$name)) throw new RuntimeException('ছবি সার্ভারে সংরক্ষণ করা যায়নি।');
 return 'live/uploads/site/'.$name;
}
if(($_GET['action']??'')==='upload_media' && $_SERVER['REQUEST_METHOD']==='POST'){
 header('Content-Type: application/json; charset=utf-8');
 try { csrf_check(); $f=$_FILES['media']??[]; if(($f['error']??UPLOAD_ERR_NO_FILE)!==UPLOAD_ERR_OK) throw new RuntimeException('মিডিয়া আপলোড করা যায়নি।'); $size=(int)($f['size']??0); $mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']); $imageExt=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif']; $videoExt=['video/mp4'=>'mp4','video/webm'=>'webm','video/ogg'=>'ogv']; if(isset($imageExt[$mime])) { if($size>10*1024*1024 || !@getimagesize($f['tmp_name'])) throw new RuntimeException('ছবি সর্বোচ্চ ১০ MB এবং JPG, PNG, WEBP বা GIF হতে হবে।'); $ext=$imageExt[$mime]; $kind='image'; } elseif(isset($videoExt[$mime])) { if($size>80*1024*1024) throw new RuntimeException('ভিডিওর সর্বোচ্চ সাইজ ৮০ MB।'); $ext=$videoExt[$mime]; $kind='video'; } else throw new RuntimeException('শুধু JPG, PNG, WEBP, GIF অথবা MP4, WebM, OGG ফাইল ব্যবহার করুন।'); $dir=__DIR__.'/../uploads/site'; if(!is_dir($dir)&&!mkdir($dir,0755,true)) throw new RuntimeException('আপলোড ফোল্ডার তৈরি করা যায়নি।'); $name='post-media-'.date('YmdHis').'-'.bin2hex(random_bytes(5)).'.'.$ext; if(!move_uploaded_file($f['tmp_name'],$dir.'/'.$name)) throw new RuntimeException('মিডিয়া সার্ভারে সংরক্ষণ করা যায়নি।'); echo json_encode(['success'=>1,'file'=>['url'=>'/live/uploads/site/'.$name,'type'=>$kind]],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); }
 catch(Throwable $e){ http_response_code(400); echo json_encode(['success'=>0,'message'=>$e instanceof RuntimeException?$e->getMessage():'আপলোড ব্যর্থ হয়েছে।'],JSON_UNESCAPED_UNICODE); } exit;
}
if(($_GET['action']??'')==='upload_image' && $_SERVER['REQUEST_METHOD']==='POST'){
 header('Content-Type: application/json; charset=utf-8');
 try { csrf_check(); $url=aspi_post_upload_image($_FILES['image']??[]); echo json_encode(['success'=>1,'file'=>['url'=>'/'.$url]],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); }
 catch(Throwable $e){ http_response_code(400); echo json_encode(['success'=>0,'message'=>$e instanceof RuntimeException?$e->getMessage():'আপলোড ব্যর্থ হয়েছে।'],JSON_UNESCAPED_UNICODE); }
 exit;
}
$editing=null;
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_post'])){
 csrf_check();
 $id=(int)($_POST['id']??0); $title=trim((string)($_POST['title']??'')); $category=(string)($_POST['category']??'news');
 $excerpt=trim((string)($_POST['excerpt']??'')); $content=trim((string)($_POST['content_json']??'{"blocks":[]}'));
 $status=in_array(($_POST['status']??'draft'),['draft','published','archived'],true)?$_POST['status']:'draft';
 $featured=isset($_POST['is_featured'])?1:0; $cover=trim((string)($_POST['existing_cover']??'')); $eventRaw=trim((string)($_POST['event_date']??'')); $eventDate=$eventRaw!==''?str_replace('T',' ',$eventRaw).(strlen($eventRaw)===16?':00':''):null;
 try {
  if(mb_strlen($title)<3) throw new RuntimeException('শিরোনাম কমপক্ষে ৩ অক্ষরের হতে হবে।');
  if(!in_array($category,['news','event','workshop','notice'],true)) $category='news';
  if(mb_strlen($excerpt)>600) throw new RuntimeException('সংক্ষিপ্ত বিবরণ সর্বোচ্চ ৬০০ অক্ষর হতে পারবে।');
  $decoded=json_decode($content,true);
  if(!is_array($decoded) || !isset($decoded['blocks']) || !is_array($decoded['blocks'])) throw new RuntimeException('ব্লক এডিটরের কনটেন্ট সঠিকভাবে সংরক্ষিত হয়নি। আবার চেষ্টা করুন।');
  if(!empty($_FILES['cover_file']['name'])) $cover=aspi_post_upload_image($_FILES['cover_file']);
  $json=json_encode($decoded,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
  if($id){
   $st=$pdo->prepare("UPDATE content_posts SET title=?,category=?,excerpt=?,cover_image=?,content_json=?,event_date=?,status=?,is_featured=?,published_at=CASE WHEN ?='published' THEN COALESCE(published_at,NOW()) ELSE NULL END WHERE id=?");
   $st->execute([$title,$category,$excerpt,$cover,$json,$eventDate,$status,$featured,$status,$id]);
   $_SESSION['flash']='পোস্ট আপডেট হয়েছে।';
  } else {
   $st=$pdo->prepare("INSERT INTO content_posts(title,category,excerpt,cover_image,content_json,event_date,status,is_featured,published_at) VALUES(?,?,?,?,?,?,?,?,IF(?='published',NOW(),NULL))");
   $st->execute([$title,$category,$excerpt,$cover,$json,$eventDate,$status,$featured,$status]);
   $_SESSION['flash']='নতুন পোস্ট সংরক্ষণ হয়েছে।';
  }
  try{$lg=$pdo->prepare('INSERT INTO activity_logs(admin_id,action,details,ip_address) VALUES(?,?,?,?)');$lg->execute([$_SESSION['admin_id'],$id?'content_post_updated':'content_post_created',$title,$_SERVER['REMOTE_ADDR']??'']);}catch(Throwable $e){}
  header('Location: posts.php'); exit;
 } catch(Throwable $e){ $error=$e instanceof RuntimeException?$e->getMessage():'পোস্ট সংরক্ষণ করা যায়নি। ডেটাবেজের upgrade.sql চালানো আছে কি না যাচাই করুন.'; $editing=['id'=>$id,'title'=>$title,'category'=>$category,'excerpt'=>$excerpt,'content_json'=>$content,'status'=>$status,'is_featured'=>$featured,'cover_image'=>$cover,'event_date'=>$eventRaw]; }
}
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['delete_post'])){
 csrf_check(); $id=(int)$_POST['id']; $st=$pdo->prepare('DELETE FROM content_posts WHERE id=?');$st->execute([$id]);$_SESSION['flash']='পোস্ট মুছে ফেলা হয়েছে।';header('Location: posts.php');exit;
}
if(!$editing && isset($_GET['edit'])){$st=$pdo->prepare('SELECT * FROM content_posts WHERE id=?');$st->execute([(int)$_GET['edit']]);$editing=$st->fetch()?:null;if($editing && !empty($editing['event_date']))$editing['event_date']=date('Y-m-d\TH:i',strtotime($editing['event_date']));}
$tableReady=true;try{$rows=$pdo->query('SELECT id,title,category,status,is_featured,published_at,updated_at FROM content_posts ORDER BY updated_at DESC,id DESC')->fetchAll();}catch(Throwable $e){$rows=[];$tableReady=false;}
$editorData=['blocks'=>[]];if($editing && !empty($editing['content_json'])){$tmp=json_decode($editing['content_json'],true);if(is_array($tmp)&&isset($tmp['blocks']))$editorData=$tmp;}
require __DIR__.'/_head.php';
?>
<?php if($flash):?><div class="alert alert-success"><?=e($flash)?></div><?php endif;?>
<?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<?php if(!$tableReady):?><div class="alert alert-danger">খবর ও ইভেন্ট টেবিল পাওয়া যায়নি। phpMyAdmin-এ ASPI ডেটাবেজ নির্বাচন করে <code>live/app/upgrade.sql</code> চালান।</div><?php else:?>
<div class="alert alert-info"><b>ASPI Global Content Editor:</b> অনুচ্ছেদ, হেডিং, ছবি, একাধিক ছবি, ক্যাপশন, তালিকা, উদ্ধৃতি, টেবিল ও YouTube/Vimeo এমবেড ব্যবহার করে অনুষ্ঠান, কর্মশালা, নিউজ ও প্রতিবেদন তৈরি করুন। খসড়া হিসেবে সংরক্ষণ বা প্রকাশ করা যাবে।</div>
<div class="panel mb-4" id="postForm">
 <div class="section-label mb-1"><?=$editing?'পোস্ট সম্পাদনা':'নতুন খবর / অনুষ্ঠান / কর্মশালা তৈরি করুন'?></div>
 <div class="hint mb-3">বড় ছবি বা ভিডিও-সমৃদ্ধ পোস্টে কভার ছবি দিন এবং মূল কনটেন্টের মধ্যে প্রয়োজনমতো Image বা Embed ব্লক যোগ করুন।</div>
 <form method="post" enctype="multipart/form-data" id="contentPostForm">
  <input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="save_post" value="1"><input type="hidden" name="id" value="<?=(int)($editing['id']??0)?>"><input type="hidden" name="existing_cover" value="<?=e($editing['cover_image']??'')?>"><input type="hidden" name="content_json" id="content_json" value="">
  <div class="row g-3">
   <div class="col-12 col-lg-8"><label class="form-label">শিরোনাম *</label><input class="form-control" name="title" required maxlength="240" value="<?=e($editing['title']??'')?>" placeholder="যেমন: ASPI-তে কারিগরি কর্মশালা অনুষ্ঠিত"></div>
   <div class="col-12 col-sm-6 col-lg-4"><label class="form-label">কনটেন্টের ধরন</label><select class="form-select" name="category"><option value="news" <?=($editing['category']??'news')==='news'?'selected':''?>>খবর</option><option value="event" <?=($editing['category']??'')==='event'?'selected':''?>>অনুষ্ঠান</option><option value="workshop" <?=($editing['category']??'')==='workshop'?'selected':''?>>কর্মশালা</option><option value="notice" <?=($editing['category']??'')==='notice'?'selected':''?>>অন্যান্য আপডেট</option></select></div>
   <div class="col-12"><label class="form-label">সংক্ষিপ্ত বিবরণ</label><textarea class="form-control" name="excerpt" maxlength="600" rows="2" placeholder="হোমপেজের কার্ডে দেখানো হবে"><?=e($editing['excerpt']??'')?></textarea></div>
   <div class="col-12 col-md-6"><label class="form-label">কভার ছবি</label><input class="form-control" type="file" name="cover_file" accept="image/jpeg,image/png,image/webp,image/gif"><div class="hint mt-1">JPG, PNG, WEBP বা GIF; সর্বোচ্চ ৮ MB। ছবি না দিলে আগের কভার থাকবে।</div><?php if(!empty($editing['cover_image'])):?><img src="../../<?=e(ltrim($editing['cover_image'],'/'))?>" style="max-width:220px;max-height:130px;object-fit:cover;border-radius:10px;margin-top:10px" alt="বর্তমান কভার"><?php endif;?></div>
   <div class="col-12 col-md-3"><label class="form-label">অনুষ্ঠানের তারিখ/সময়</label><input class="form-control" type="datetime-local" name="event_date" value="<?=e($editing['event_date']??'')?>"></div>
   <div class="col-12 col-md-3 d-flex flex-column justify-content-end"><label class="form-label">প্রকাশের অবস্থা</label><select class="form-select" name="status"><option value="draft" <?=($editing['status']??'draft')==='draft'?'selected':''?>>খসড়া</option><option value="published" <?=($editing['status']??'')==='published'?'selected':''?>>প্রকাশিত</option><option value="archived" <?=($editing['status']??'')==='archived'?'selected':''?>>আর্কাইভ</option></select><label class="form-check mt-2"><input class="form-check-input" type="checkbox" name="is_featured" value="1" <?=!empty($editing['is_featured'])?'checked':''?>> হোমপেজে অগ্রাধিকার দিন</label></div>
   <div class="col-12"><div class="editor-shell"><div class="editor-top"><strong><i class="fa-solid fa-layer-group me-1"></i> ASPI Global Content Editor</strong><span>একই গ্লোবাল এডিটর দিয়ে লেখা, ছবি, ভিডিও ও টেবিল সম্পাদনা করুন</span></div><div id="globalContentEditor"></div></div></div>
   <div class="col-12 d-flex gap-2 flex-wrap"><button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk me-1"></i> <?=$editing?'পরিবর্তন সংরক্ষণ':'পোস্ট সংরক্ষণ করুন'?></button><?php if($editing):?><a class="btn btn-outline-secondary" href="posts.php">বাতিল</a><?php endif;?></div>
  </div>
 </form>
</div>
<div class="panel"><div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2"><div><div class="section-label">সকল খবর, অনুষ্ঠান ও কর্মশালা</div><div class="hint">প্রকাশিত পোস্ট হোমপেজ ও খবরের তালিকায় দেখাবে।</div></div><span class="badge-status bg-light text-dark"><?=count($rows)?>টি</span></div><div class="table-wrap"><table class="table"><thead><tr><th>শিরোনাম</th><th>ধরন</th><th>অবস্থা</th><th>আপডেট</th><th>কাজ</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><div class="notice-title"><?=e($r['title'])?></div><?php if($r['is_featured']):?><span class="badge-status bg-warning-subtle text-warning-emphasis">হোমপেজ অগ্রাধিকার</span><?php endif;?></td><td><?=e(['news'=>'খবর','event'=>'অনুষ্ঠান','workshop'=>'কর্মশালা','notice'=>'আপডেট'][$r['category']]??$r['category'])?></td><td><span class="badge-status <?=$r['status']==='published'?'bg-success-subtle text-success':($r['status']==='draft'?'bg-warning-subtle text-warning-emphasis':'bg-secondary-subtle text-secondary')?>"><?=e(['published'=>'প্রকাশিত','draft'=>'খসড়া','archived'=>'আর্কাইভ'][$r['status']]??$r['status'])?></span></td><td><?=e(date('d-m-Y',strtotime($r['updated_at'])))?></td><td><div class="d-flex flex-wrap gap-1"><a class="btn btn-sm btn-outline-primary" href="?edit=<?=(int)$r['id']?>#postForm">সম্পাদনা</a><?php if($r['status']==='published'):?><a class="btn btn-sm btn-outline-secondary" href="../post.php?id=<?=(int)$r['id']?>" target="_blank">দেখুন</a><?php endif;?><form method="post" onsubmit="return confirm('এই পোস্টটি মুছে ফেলবেন?')"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="id" value="<?=(int)$r['id']?>"><button class="btn btn-sm btn-outline-danger" name="delete_post" value="1">মুছুন</button></form></div></td></tr><?php endforeach;?><?php if(!$rows):?><tr><td colspan="5">এখনো কোনো পোস্ট তৈরি করা হয়নি।</td></tr><?php endif;?></tbody></table></div></div>
<style>
.editor-shell{border:1px solid #dce4ef;border-radius:13px;overflow:hidden;background:#fff}.editor-top{display:flex;justify-content:space-between;gap:10px;align-items:center;padding:13px 16px;background:#f5f8fc;border-bottom:1px solid #e3eaf3;color:#17305d}.editor-top span{font-size:12px;color:#68748a}
@media(max-width:600px){.editor-top{align-items:flex-start;flex-direction:column}}
</style>
<script src="../assets/global-editor.js?v=1.3.5"></script>
<script>
(()=> {
 const initial=<?=json_encode($editorData,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT)?>;
 const csrf=<?=json_encode(csrf_token())?>, holder=document.getElementById('globalContentEditor'), form=document.getElementById('contentPostForm');
 if(!window.ASPIEditor){holder.innerHTML='<div class="alert alert-danger m-3">Global Editor লোড হয়নি। পেইজ রিফ্রেশ করুন।</div>';return;}
 const editor=ASPIEditor.mount({holder,data:initial,csrf,uploadUrl:'posts.php?action=upload_image',uploadMediaUrl:'posts.php?action=upload_media'});
 form.addEventListener('submit',e=>{e.preventDefault();const btn=form.querySelector('button[type="submit"]');btn.disabled=true;btn.innerHTML='<i class="fa-solid fa-spinner fa-spin me-1"></i> সংরক্ষণ হচ্ছে…';try{document.getElementById('content_json').value=JSON.stringify(editor.getData());form.submit();}catch(err){console.error(err);alert('কনটেন্ট সংরক্ষণ করা যায়নি। আবার চেষ্টা করুন।');btn.disabled=false;btn.textContent='পোস্ট সংরক্ষণ করুন';}});
})();
</script>
<?php endif; require __DIR__.'/_foot.php';?>
