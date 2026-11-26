<?php
require_once __DIR__.'/../app/bootstrap.php';
require_admin();
$active='health'; $pageTitle='ওয়েবসাইট স্বাস্থ্য পরীক্ষা';
$requiredTables=['admins'=>'অ্যাডমিন অ্যাকাউন্ট','notices'=>'বিজ্ঞপ্তি বোর্ড','site_settings'=>'ওয়েবসাইট সেটিংস','courses'=>'NSDA শর্ট কোর্স','applications'=>'আবেদন ও অনুসন্ধান','activity_logs'=>'কার্যক্রম লগ','diploma_programs'=>'ডিপ্লোমা শিক্ষাক্রম','gallery_images'=>'ফটো গ্যালারি'];
$tableResults=[];
foreach($requiredTables as $table=>$label){
  try{$pdo->query('SELECT 1 FROM `'.$table.'` LIMIT 1');$tableResults[$table]=['ok'=>true,'label'=>$label,'message'=>'টেবিল পাওয়া গেছে'];}
  catch(Throwable $e){$tableResults[$table]=['ok'=>false,'label'=>$label,'message'=>'টেবিল পাওয়া যায়নি—upgrade.sql/schema.sql যাচাই করুন'];}
}
$dirs=[
 'live/uploads/notices'=>__DIR__.'/../uploads/notices',
 'live/uploads/site'=>__DIR__.'/../uploads/site',
];
$dirResults=[];
foreach($dirs as $label=>$path){$exists=is_dir($path);$dirResults[$label]=['exists'=>$exists,'writable'=>$exists&&is_writable($path)];}
$checks=[
 ['PHP Version',PHP_VERSION_ID>=80200,PHP_VERSION],
 ['PDO MySQL Extension',extension_loaded('pdo_mysql'),extension_loaded('pdo_mysql')?'সক্রিয়':'সক্রিয় নয়'],
 ['Fileinfo Extension',extension_loaded('fileinfo'),extension_loaded('fileinfo')?'সক্রিয়':'সক্রিয় নয়'],
 ['Local Config',is_file(__DIR__.'/../app/config.local.php'),is_file(__DIR__.'/../app/config.local.php')?'config.local.php পাওয়া গেছে':'config.local.php নেই; সার্ভার কনফিগারেশন যাচাই করুন'],
];
require __DIR__.'/_head.php';
$okTables=count(array_filter($tableResults,fn($x)=>$x['ok']));
$okChecks=count(array_filter($checks,fn($x)=>$x[1]));
?>
<div class="alert alert-info"><strong>নিরাপদ স্বাস্থ্য পরীক্ষা:</strong> এই পেজ শুধু সিস্টেমের অবস্থা যাচাই করে। এটি কোনো ডেটা, সেটিংস বা ফাইল পরিবর্তন করে না এবং ডেটাবেজ পাসওয়ার্ড প্রদর্শন করে না।</div>
<div class="row g-3 mb-3">
 <div class="col-12 col-sm-6 col-xl-3"><div class="panel metric"><div><div class="muted">ডেটাবেজ টেবিল</div><div class="num"><?=$okTables?>/<?=count($tableResults)?></div><div class="hint">প্রয়োজনীয় টেবিল পাওয়া গেছে</div></div><div class="ico"><i class="fa-solid fa-database"></i></div></div></div>
 <div class="col-12 col-sm-6 col-xl-3"><div class="panel metric"><div><div class="muted">সার্ভার পরীক্ষা</div><div class="num"><?=$okChecks?>/<?=count($checks)?></div><div class="hint">PHP ও প্রয়োজনীয় কনফিগারেশন</div></div><div class="ico"><i class="fa-solid fa-server"></i></div></div></div>
 <div class="col-12 col-sm-6 col-xl-3"><div class="panel metric"><div><div class="muted">আপলোড ফোল্ডার</div><div class="num"><?=count(array_filter($dirResults,fn($x)=>$x['exists']&&$x['writable']))?>/<?=count($dirResults)?></div><div class="hint">অস্তিত্ব ও write permission</div></div><div class="ico"><i class="fa-solid fa-cloud-arrow-up"></i></div></div></div>
 <div class="col-12 col-sm-6 col-xl-3"><div class="panel metric"><div><div class="muted">পরীক্ষার ধরন</div><div class="num" style="font-size:22px">Read-only</div><div class="hint">কোনো পরিবর্তন করা হয় না</div></div><div class="ico"><i class="fa-solid fa-shield-halved"></i></div></div></div>
</div>
<section class="panel mb-3"><div class="section-label mb-3">ডেটাবেজ টেবিল পরীক্ষা</div><div class="table-wrap"><table class="table"><thead><tr><th>অংশ</th><th>অবস্থা</th><th>পরবর্তী পদক্ষেপ</th></tr></thead><tbody><?php foreach($tableResults as $r):?><tr><td class="notice-title"><?=e($r['label'])?> <span class="hint">(<?=e(array_search($r,$tableResults,true)?:'')?>)</span></td><td><span class="badge-status <?=$r['ok']?'bg-success-subtle text-success':'bg-danger-subtle text-danger'?>"><?=$r['ok']?'ঠিক আছে':'সমস্যা'?></span></td><td><?=e($r['message'])?></td></tr><?php endforeach;?></tbody></table></div></section>
<section class="panel mb-3"><div class="section-label mb-3">সার্ভার ও কনফিগারেশন</div><div class="table-wrap"><table class="table"><thead><tr><th>পরীক্ষা</th><th>অবস্থা</th><th>বিস্তারিত</th></tr></thead><tbody><?php foreach($checks as $c):?><tr><td class="notice-title"><?=e($c[0])?></td><td><span class="badge-status <?=$c[1]?'bg-success-subtle text-success':'bg-danger-subtle text-danger'?>"><?=$c[1]?'ঠিক আছে':'যাচাই প্রয়োজন'?></span></td><td><?=e($c[2])?></td></tr><?php endforeach;?></tbody></table></div></section>
<section class="panel"><div class="section-label mb-3">ফাইল আপলোডের অনুমতি</div><div class="table-wrap"><table class="table"><thead><tr><th>ফোল্ডার</th><th>অস্তিত্ব</th><th>লেখার অনুমতি</th></tr></thead><tbody><?php foreach($dirResults as $label=>$r):?><tr><td><code><?=e($label)?></code></td><td><?=$r['exists']?'আছে':'নেই'?></td><td><span class="badge-status <?=$r['writable']?'bg-success-subtle text-success':'bg-warning-subtle text-warning-emphasis'?>"><?=$r['writable']?'লেখা যাবে':'ফোল্ডার/permission ঠিক করুন'?></span></td></tr><?php endforeach;?></tbody></table></div><p class="hint mt-3 mb-0">সমস্যা দেখা দিলে আগে ব্যাকআপ নিন। SQL ফাইল ইমপোর্ট করার আগে সঠিক ASPI ডেটাবেজ নির্বাচন করুন। ডেটাবেজের পাসওয়ার্ড কখনো এই পেজে বা স্ক্রিনশটে শেয়ার করবেন না।</p></section>
<?php require __DIR__.'/_foot.php'; ?>
