<?php
require_once __DIR__.'/../app/bootstrap.php';
require_admin();
$active='pages'; $pageTitle='পেইজ সম্পাদনা';
$pages=[
 'institution'=>['title'=>'প্রতিষ্ঠান পরিচিতি','label'=>'প্রতিষ্ঠান পরিচিতি','key'=>'page_institution_content','legacy'=>'institution_intro','fallback'=>'আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট একটি কারিগরি শিক্ষা প্রতিষ্ঠান। এখানে ডিপ্লোমা ইন ইঞ্জিনিয়ারিং শিক্ষাক্রমের পাশাপাশি দক্ষতাভিত্তিক প্রশিক্ষণ কার্যক্রম পরিচালিত হয়।'],
 'founder'=>['title'=>'প্রতিষ্ঠাতার পরিচিতি','label'=>'প্রতিষ্ঠাতা','key'=>'page_founder_content','legacy'=>'founder_bio','fallback'=>'আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউটের প্রতিষ্ঠাতা সম্পর্কে বিস্তারিত তথ্য শিগগিরই প্রকাশ করা হবে।'],
 'foundation'=>['title'=>'আসহাব সিরাজ ফাউন্ডেশন','label'=>'পরিচালনাকারী ফাউন্ডেশন','key'=>'page_foundation_content','legacy'=>'foundation_about','fallback'=>'আসহাব সিরাজ ফাউন্ডেশন আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট পরিচালনা করে। প্রতিষ্ঠানের শিক্ষা ও প্রশিক্ষণ কার্যক্রমের প্রাতিষ্ঠানিক পরিচালনা ও উন্নয়নে ফাউন্ডেশন ভূমিকা পালন করে।']
];
function aspi_page_upload(array $f): string {
 if(($f['error']??UPLOAD_ERR_NO_FILE)!==UPLOAD_ERR_OK) throw new RuntimeException('ছবি আপলোড করা যায়নি।');
 if(($f['size']??0)>10*1024*1024) throw new RuntimeException('ছবির সর্বোচ্চ সাইজ ১০ MB।');
 $mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
 $ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'][$mime]??'';
 if($ext==='' || !@getimagesize($f['tmp_name'])) throw new RuntimeException('JPG, PNG, WEBP বা GIF ছবি আপলোড করুন।');
 $dir=__DIR__.'/../uploads/site';
 if(!is_dir($dir)&&!mkdir($dir,0755,true)) throw new RuntimeException('আপলোড ফোল্ডার তৈরি করা যায়নি।');
 $name='page-media-'.date('YmdHis').'-'.bin2hex(random_bytes(5)).'.'.$ext;
 if(!move_uploaded_file($f['tmp_name'],$dir.'/'.$name)) throw new RuntimeException('ছবি সার্ভারে সংরক্ষণ করা যায়নি।');
 return 'live/uploads/site/'.$name;
}
if(($_GET['action']??'')==='upload_media' && $_SERVER['REQUEST_METHOD']==='POST'){
 header('Content-Type: application/json; charset=utf-8');
 try { csrf_check(); $f=$_FILES['media']??[]; if(($f['error']??UPLOAD_ERR_NO_FILE)!==UPLOAD_ERR_OK) throw new RuntimeException('মিডিয়া আপলোড করা যায়নি।'); $size=(int)($f['size']??0); $mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']); $imageExt=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif']; $videoExt=['video/mp4'=>'mp4','video/webm'=>'webm','video/ogg'=>'ogv']; if(isset($imageExt[$mime])) { if($size>10*1024*1024 || !@getimagesize($f['tmp_name'])) throw new RuntimeException('ছবি সর্বোচ্চ ১০ MB এবং JPG, PNG, WEBP বা GIF হতে হবে।'); $ext=$imageExt[$mime]; $kind='image'; } elseif(isset($videoExt[$mime])) { if($size>80*1024*1024) throw new RuntimeException('ভিডিওর সর্বোচ্চ সাইজ ৮০ MB।'); $ext=$videoExt[$mime]; $kind='video'; } else throw new RuntimeException('শুধু JPG, PNG, WEBP, GIF অথবা MP4, WebM, OGG ফাইল ব্যবহার করুন।'); $dir=__DIR__.'/../uploads/site'; if(!is_dir($dir)&&!mkdir($dir,0755,true)) throw new RuntimeException('আপলোড ফোল্ডার তৈরি করা যায়নি।'); $name='page-media-'.date('YmdHis').'-'.bin2hex(random_bytes(5)).'.'.$ext; if(!move_uploaded_file($f['tmp_name'],$dir.'/'.$name)) throw new RuntimeException('মিডিয়া সার্ভারে সংরক্ষণ করা যায়নি।'); echo json_encode(['success'=>1,'file'=>['url'=>'/live/uploads/site/'.$name,'type'=>$kind]],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); }
 catch(Throwable $e){ http_response_code(400); echo json_encode(['success'=>0,'message'=>$e instanceof RuntimeException?$e->getMessage():'আপলোড ব্যর্থ হয়েছে।'],JSON_UNESCAPED_UNICODE); } exit;
}
if(($_GET['action']??'')==='upload_image' && $_SERVER['REQUEST_METHOD']==='POST'){
 header('Content-Type: application/json; charset=utf-8');
 try { csrf_check(); $url=aspi_page_upload($_FILES['image']??[]); echo json_encode(['success'=>1,'file'=>['url'=>'/'.$url]],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); }
 catch(Throwable $e){ http_response_code(400); echo json_encode(['success'=>0,'message'=>$e instanceof RuntimeException?$e->getMessage():'আপলোড ব্যর্থ হয়েছে।'],JSON_UNESCAPED_UNICODE); }
 exit;
}
$selected=(string)($_GET['page']??'institution'); if(!isset($pages[$selected])) $selected='institution';
$meta=$pages[$selected]; $error=''; $flash=$_SESSION['flash']??''; unset($_SESSION['flash']);
$all=[]; foreach($pdo->query('SELECT setting_key,setting_value FROM site_settings')->fetchAll() as $r) $all[$r['setting_key']]=$r['setting_value'];
$stored=(string)($all[$meta['key']]??'');
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_page'])){
 csrf_check(); $selected=(string)($_POST['page_key']??'institution'); if(!isset($pages[$selected])) $selected='institution'; $meta=$pages[$selected];
 $title=trim((string)($_POST['page_title']??$meta['title'])); $content=trim((string)($_POST['content_json']??''));
 $decoded=json_decode($content,true);
 if(mb_strlen($title)<2 || mb_strlen($title)>180) $error='পেইজের শিরোনাম ২–১৮০ অক্ষরের মধ্যে দিন।';
 elseif(!is_array($decoded)||!isset($decoded['blocks'])||!is_array($decoded['blocks'])) $error='এডিটরের কনটেন্ট সঠিক নয়। আবার চেষ্টা করুন।';
 elseif(strlen($content)>2000000) $error='কনটেন্ট সর্বোচ্চ ২ MB হতে পারবে।';
 else {
  $st=$pdo->prepare('INSERT INTO site_settings(setting_key,setting_value) VALUES(?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)');
  $st->execute([$meta['key'],json_encode($decoded,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)]);
  // The public page headings are intentionally fixed; title is retained as metadata for the editor UI.
  $st->execute([$meta['key'].'_title',$title]);
  try{$lg=$pdo->prepare('INSERT INTO activity_logs(admin_id,action,details,ip_address) VALUES(?,?,?,?)');$lg->execute([$_SESSION['admin_id'],'page_content_updated',$meta['title'].' Global Editor কনটেন্ট আপডেট',$_SERVER['REMOTE_ADDR']??'']);}catch(Throwable $e){}
  $_SESSION['flash']='“'.$meta['title'].'” পেইজের কনটেন্ট সংরক্ষণ করা হয়েছে।'; header('Location: pages.php?page='.urlencode($selected)); exit;
 }
}
$all=[]; foreach($pdo->query('SELECT setting_key,setting_value FROM site_settings')->fetchAll() as $r) $all[$r['setting_key']]=$r['setting_value'];
$stored=(string)($all[$meta['key']]??'');
if($stored!=='') $editorData=json_decode($stored,true);
else {
 $legacy=(string)($all[$meta['legacy']]??$meta['fallback']);
 if($selected==='founder'){
  $name=trim((string)($all['founder_name']??'')); $role=trim((string)($all['founder_role']??'প্রতিষ্ঠাতা'));
  if($name!=='') $legacy=$name.' — '.$role."\n\n".$legacy;
 }
 $editorData=['blocks'=>[['type'=>'header','data'=>['text'=>$meta['title'],'level'=>2]],['type'=>'paragraph','data'=>['text'=>$legacy]]]];
}
if(!is_array($editorData)||!isset($editorData['blocks'])) $editorData=['blocks'=>[]];
$editTitle=(string)($all[$meta['key'].'_title']??$meta['title']);
require __DIR__.'/_head.php';
?>
<?php if($flash):?><div class="alert alert-success"><?=e($flash)?></div><?php endif;?>
<?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<div class="alert alert-info page-editor-intro"><b>ASPI Global Content Editor:</b> আলাদা পেইজ নির্বাচন করে শিরোনাম ও কনটেন্ট সম্পাদনা করুন। ছবি আপলোড করা যাবে; ভিডিও যোগ করতে ব্লক টুলবারের Embed ব্যবহার করে YouTube বা Vimeo লিংক দিন। পরিবর্তন সংরক্ষণের আগে Preview দেখুন।</div>
<div class="panel page-manager-panel">
 <div class="page-picker"><?php foreach($pages as $key=>$p):?><a class="page-pick <?=($selected===$key?'active':'')?>" href="pages.php?page=<?=e($key)?>"><i class="fa-solid <?=['institution'=>'fa-building-columns','founder'=>'fa-user-tie','foundation'=>'fa-handshake-angle'][$key]?>"></i><span><?=e($p['title'])?></span><small><?=!empty($all[$p['key']])?'সম্পাদিত কনটেন্ট':'প্রাথমিক কনটেন্ট'?></small></a><?php endforeach;?></div>
 <div class="editor-heading"><div><span class="editor-kicker">PAGE CONTENT STUDIO</span><h2><?=e($meta['title'])?></h2><p>একই গ্লোবাল এডিটর দিয়ে লেখা, ছবি, ভিডিও, তালিকা ও টেবিল সম্পাদনা করুন।</p></div><a class="btn btn-outline-primary" href="../<?=e($selected)?>.php" target="_blank" rel="noopener"><i class="fa-solid fa-eye me-1"></i> লাইভ পেইজ দেখুন</a></div>
 <form method="post" id="pageEditorForm"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="save_page" value="1"><input type="hidden" name="page_key" value="<?=e($selected)?>"><input type="hidden" name="content_json" id="content_json">
 <div class="mb-3"><label class="form-label" for="page_title">এডিটর শিরোনাম / অভ্যন্তরীণ নাম</label><input class="form-control" id="page_title" name="page_title" value="<?=e($editTitle)?>" maxlength="180" required></div>
 <div class="editor-shell"><div class="editor-top"><b><i class="fa-solid fa-layer-group me-2"></i>ASPI Global Content Editor</b><span>একটি শেয়ার্ড এডিটর · লেখা, ছবি, ভিডিও ও অন্যান্য ব্লক</span></div><div id="globalContentEditor"></div></div>
 <div id="editorFallbackWrap" hidden><label class="form-label mt-3">বিকল্প কনটেন্ট এডিটর</label><textarea id="editorFallback" class="form-control" rows="14"></textarea><div class="form-text">এডিটর লোড না হলে এখানে লেখা সম্পাদনা করে সংরক্ষণ করতে পারবেন।</div></div>
 <div class="editor-actions"><button class="btn btn-outline-secondary" type="button" id="previewPageBtn"><i class="fa-solid fa-eye me-1"></i> কনটেন্ট প্রিভিউ</button><button class="btn btn-primary" type="submit" id="savePageBtn"><i class="fa-solid fa-floppy-disk me-1"></i> পেইজ সংরক্ষণ করুন</button></div>
 </form>
 <div id="pagePreview" class="page-preview" hidden><div class="preview-head"><b>কনটেন্ট প্রিভিউ</b><button class="btn btn-sm btn-outline-secondary" type="button" id="closePreview">বন্ধ করুন</button></div><div id="pagePreviewBody" class="page-render"></div></div>
</div>
<style>
.page-manager-panel{padding:clamp(14px,2.4vw,28px);border-radius:22px}.page-editor-intro{line-height:1.8;border-radius:14px}.page-picker{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin-bottom:26px}.page-pick{display:grid;grid-template-columns:42px 1fr;align-items:center;column-gap:12px;padding:15px;border:1px solid #dfe7f2;border-radius:15px;background:#fff;color:#10224b;min-width:0}.page-pick>i{grid-row:span 2;width:42px;height:42px;border-radius:12px;display:grid;place-items:center;background:#e6f5f5;color:#087f8c}.page-pick span{font-weight:700;overflow-wrap:anywhere}.page-pick small{font-size:12px;color:#6b7890}.page-pick.active{border-color:#087f8c;background:linear-gradient(130deg,#eaf8f8,#fff);box-shadow:0 8px 24px #087f8c12}.editor-heading{display:flex;align-items:center;justify-content:space-between;gap:18px;margin:12px 0 20px;padding-bottom:18px;border-bottom:1px solid #e5ebf4}.editor-heading h2{font-size:25px;color:#10224b;margin:3px 0}.editor-heading p{color:#6b7890;margin:0;font-size:14px}.editor-kicker{font-size:11px;letter-spacing:1.2px;color:#087f8c;font-weight:700}.editor-toolbar-hint{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:10px}.editor-toolbar-hint span{background:#f2f6fb;border:1px solid #e2e9f3;border-radius:99px;padding:6px 10px;font-size:12px;color:#42516b}.editor-shell{border:1px solid #dce4ef;border-radius:15px;overflow:visible;background:#fff}.editor-top{display:flex;justify-content:space-between;gap:10px;align-items:center;padding:13px 16px;background:#f5f8fc;border-bottom:1px solid #e3eaf3;color:#17305d;border-radius:14px 14px 0 0}.editor-top span{font-size:12px;color:#68748a}.codex-editor{padding:8px 16px 24px;min-height:350px}.ce-block__content,.ce-toolbar__content{max-width:100%}.ce-paragraph{line-height:1.9}.ce-header{font-weight:700;color:#10224b}.ce-toolbar__plus,.ce-toolbar__settings-btn{color:#087f8c}.ce-toolbar__plus{opacity:1!important;visibility:visible!important}.image-tool__image-picture{max-width:100%;border-radius:12px}.image-tool__caption{border:1px solid #e4e9f2;border-radius:8px}.editor-shell .ce-block{margin-bottom:9px}.editor-actions{display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;margin-top:18px}.editor-actions .btn{border-radius:11px;padding:11px 17px}.page-preview{margin-top:20px;border:1px solid #dce4ef;border-radius:15px;padding:18px;background:#fff}.preview-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}.page-render{line-height:1.85;overflow-wrap:anywhere}.page-render img{max-width:100%;height:auto;border-radius:12px}.page-render iframe{width:100%;aspect-ratio:16/9;border:0;border-radius:12px}.page-render table{border-collapse:collapse;width:100%}.page-render td{border:1px solid #dce4ef;padding:8px}.page-render blockquote,.page-render .page-warning{padding:15px 18px;background:#f3f7fc;border-left:4px solid #087f8c;border-radius:10px}.page-render pre{white-space:pre-wrap;background:#f2f5fa;padding:12px;border-radius:10px}
@media(max-width:800px){.page-picker{grid-template-columns:1fr}.page-pick{grid-template-columns:38px 1fr}.page-pick>i{width:38px;height:38px}.editor-heading{align-items:flex-start;flex-direction:column}.editor-heading h2{font-size:22px}.editor-top{align-items:flex-start;flex-direction:column}.codex-editor{padding-inline:7px}.editor-actions{justify-content:stretch}.editor-actions .btn{flex:1}.ce-toolbar__actions{right:0}}
</style>
<script src="../assets/global-editor.js?v=1.3.5"></script>
<script>
(()=> {
 const initial=<?=json_encode($editorData,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT)?>;
 const csrf=<?=json_encode(csrf_token())?>, holder=document.getElementById('globalContentEditor'), form=document.getElementById('pageEditorForm');
 let editor=null, fallback=false;
 const fallbackWrap=document.getElementById('editorFallbackWrap'), fb=document.getElementById('editorFallback');
 function plainFallback(message){fallback=true;holder.innerHTML='<div class="alert alert-warning m-3">'+message+'</div>';fallbackWrap.hidden=false;fb.value=(initial.blocks||[]).map(b=>(b.data&&b.data.text)||'').filter(Boolean).join('\n\n');}
 if(window.ASPIEditor){editor=ASPIEditor.mount({holder, data:initial, csrf, uploadUrl:'pages.php?action=upload_image',uploadMediaUrl:'pages.php?action=upload_media'});} else {plainFallback('Global Editor লোড হয়নি। নিচের বিকল্প ঘরে লেখা সম্পাদনা করুন।');}
 const fallbackData=()=>({blocks:fb.value.split(/\n\s*\n/).filter(Boolean).map(text=>({type:'paragraph',data:{text:text.replace(/</g,'&lt;').replace(/>/g,'&gt;')}}))});
 form.addEventListener('submit',e=>{e.preventDefault();const btn=document.getElementById('savePageBtn');btn.disabled=true;btn.textContent='সংরক্ষণ হচ্ছে…';try{const data=fallback?fallbackData():editor.getData();document.getElementById('content_json').value=JSON.stringify(data);form.submit();}catch(err){console.error(err);alert('কনটেন্ট সংরক্ষণ করা যায়নি। আবার চেষ্টা করুন।');btn.disabled=false;btn.textContent='পেইজ সংরক্ষণ করুন';}});
 document.getElementById('previewPageBtn').addEventListener('click',()=>{try{const data=fallback?fallbackData():editor.getData();const esc=s=>String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));let html='';for(const b of data.blocks||[]){const d=b.data||{};if(b.type==='header'||b.type==='paragraph'){const tag=b.type==='header'?'h'+([2,3,4].includes(+d.level)?+d.level:2):'p';html+='<'+tag+'>'+String(d.text||'')+'</'+tag+'>';}else if(b.type==='image'&&(d.file?.url||d.url))html+='<figure><img style="max-width:100%;height:auto" src="'+esc(d.file?.url||d.url)+'"><figcaption>'+esc(d.caption||'')+'</figcaption></figure>';else if(b.type==='embed'&&/youtube\.com|youtu\.be|vimeo\.com/i.test(d.source||d.embed||''))html+='<p>ভিডিও: '+esc(d.source||d.embed)+'</p>';else if(b.type==='gallery')html+='<div class="page-media-gallery">'+((d.items||[]).map(i=>i.type==='video'?'<video controls src="'+esc(i.url)+'"></video>':i.type==='embed'?'<p>ভিডিও: '+esc(i.url)+'</p>':'<figure><img src="'+esc(i.url)+'"><figcaption>'+esc(i.caption||'')+'</figcaption></figure>').join(''))+'</div>';else if(b.type==='quote')html+='<blockquote>'+String(d.text||'')+'<br><small>'+esc(d.caption||'')+'</small></blockquote>';else if(b.type==='delimiter')html+='<hr>';else if(b.type==='list')html+='<ul>'+((d.items||[]).map(i=>'<li>'+String(typeof i==='string'?i:i.content||'')+'</li>').join(''))+'</ul>';else if(b.type==='table')html+='<table class="table"><tbody>'+((d.content||[]).map(r=>'<tr>'+r.map(c=>'<td>'+String(c||'')+'</td>').join('')+'</tr>').join(''))+'</tbody></table>';else if(b.type==='checklist')html+='<ul>'+((d.items||[]).map(i=>'<li>'+(i.checked?'☑ ':'☐ ')+esc(i.text)+'</li>').join(''))+'</ul>';else if(b.type==='warning')html+='<aside><b>'+esc(d.title)+'</b><p>'+String(d.message||'')+'</p></aside>';else if(b.type==='code')html+='<pre>'+esc(d.code)+'</pre>';}document.getElementById('pagePreviewBody').innerHTML=html;document.getElementById('pagePreview').hidden=false;document.getElementById('pagePreview').scrollIntoView({behavior:'smooth',block:'start'});}catch(err){console.error(err);alert('প্রিভিউ তৈরি করা যায়নি।');}});
 document.getElementById('closePreview').addEventListener('click',()=>document.getElementById('pagePreview').hidden=true);
})();
</script>
<?php require __DIR__.'/_foot.php';?>
