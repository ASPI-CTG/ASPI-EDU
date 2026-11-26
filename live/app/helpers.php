<?php
function save_pdf(array $file, ?string $oldPath=null): array {
 if(($file['error']??UPLOAD_ERR_NO_FILE)===UPLOAD_ERR_NO_FILE) return $oldPath ? [$oldPath, ''] : ['', ''];
 if($file['error']!==UPLOAD_ERR_OK) throw new RuntimeException('PDF আপলোড ব্যর্থ হয়েছে। ফাইলের সাইজ/সার্ভার সেটিংস পরীক্ষা করুন।');
 if(($file['size']??0)>20*1024*1024) throw new RuntimeException('PDF সর্বোচ্চ ২০ MB হতে পারবে।');
 $tmp=$file['tmp_name']; $finfo=new finfo(FILEINFO_MIME_TYPE); $mime=$finfo->file($tmp);
 if($mime!=='application/pdf' || strtolower(pathinfo($file['name'],PATHINFO_EXTENSION))!=='pdf') throw new RuntimeException('শুধু বৈধ PDF ফাইল আপলোড করা যাবে।');
 $dir=dirname(__DIR__).'/uploads/notices'; if(!is_dir($dir) && !mkdir($dir,0755,true)) throw new RuntimeException('আপলোড ফোল্ডার তৈরি করা যায়নি।');
 $name=bin2hex(random_bytes(18)).'.pdf'; if(!move_uploaded_file($tmp,$dir.'/'.$name)) throw new RuntimeException('PDF সংরক্ষণ করা যায়নি।');
 return ['uploads/notices/'.$name, basename($file['name'])];
}
function remove_pdf(?string $path): void { if(!$path || !str_starts_with($path,'uploads/notices/')) return; $full=dirname(__DIR__).'/'.$path; if(is_file($full)) @unlink($full); }

function aspi_safe_notice_html(string $html): string {
 $allowed='<p><br><strong><b><em><i><u><ul><ol><li><a><h2><h3><blockquote><div><span><table><thead><tbody><tr><th><td>';
 $html=strip_tags($html,$allowed);
 // Remove event handlers and unsafe URL schemes from retained markup.
 $html=preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i','',$html)??$html;
 $html=preg_replace_callback('/\s(href|src)\s*=\s*(["\'])(.*?)\2/i',function($m){$v=trim(html_entity_decode($m[3],ENT_QUOTES|ENT_HTML5,'UTF-8'));$scheme=strtolower((string)parse_url($v,PHP_URL_SCHEME));if(preg_match('/^(javascript|data|vbscript):/i',$v)||($scheme!==''&&!in_array($scheme,['http','https','mailto','tel'],true)))return '';return ' '.$m[1].'='.$m[2].htmlspecialchars($v,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').$m[2];},$html)??$html;
 return $html;
}
