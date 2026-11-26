<?php
function aspi_safe_inline(string $html): string {
 $html=strip_tags($html,'<b><strong><i><em><u><s><mark><code><a><br>');
 $html=preg_replace_callback('/<a\b([^>]*)>/i',function($m){
  $href=''; if(preg_match('/href\s*=\s*["\']([^"\']+)["\']/i',$m[1],$x))$href=trim($x[1]);
  if(!preg_match('#^(https?://|mailto:)#i',$href))return '<a>';
  return '<a href="'.htmlspecialchars($href,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'" target="_blank" rel="noopener noreferrer">';
 },$html);
 return $html;
}
function aspi_public_path(string $path): string {
 $path=trim($path);
 if($path==='') return '';
 if(preg_match('#^https://#i',$path)) return $path;
 return '/'.ltrim($path,'/');
}
function aspi_render_blocks(string $json): string {
 $data=json_decode($json,true); if(!is_array($data)||empty($data['blocks'])||!is_array($data['blocks']))return '';
 $out='';
 foreach($data['blocks'] as $b){
  $type=$b['type']??''; $d=$b['data']??[];
  switch($type){
   case 'paragraph': $out.='<p>'.aspi_safe_inline((string)($d['text']??'')).'</p>'; break;
   case 'header': $level=max(2,min(4,(int)($d['level']??2)));$out.='<h'.$level.'>'.aspi_safe_inline((string)($d['text']??'')).'</h'.$level.'>';break;
   case 'list':
    $style=$d['style']??'unordered';$tag=$style==='ordered'?'ol':'ul';$out.='<'.$tag.'>';
    foreach(($d['items']??[]) as $item){$text=is_array($item)?(string)($item['content']??''):(string)$item;$out.='<li>'.aspi_safe_inline($text).'</li>';}
    $out.='</'.$tag.'>';break;
   case 'checklist': $out.='<ul class="post-checklist">';foreach(($d['items']??[]) as $it){$out.='<li>'.(!empty($it['checked'])?'✓ ':'□ ').aspi_safe_inline((string)($it['text']??'')).'</li>';}$out.='</ul>';break;
   case 'quote': $out.='<blockquote><div>'.aspi_safe_inline((string)($d['text']??'')).'</div>'.(!empty($d['caption'])?'<cite>'.htmlspecialchars((string)$d['caption'],ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'</cite>':'').'</blockquote>';break;
   case 'delimiter': $out.='<hr class="post-divider">';break;
   case 'image':
    $url=aspi_public_path((string)($d['file']['url']??'')); if($url!=='')$out.='<figure><img loading="lazy" src="'.htmlspecialchars($url,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'" alt="'.htmlspecialchars(strip_tags((string)($d['caption']??'')),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'">'.(!empty($d['caption'])?'<figcaption>'.htmlspecialchars(strip_tags((string)$d['caption']),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'</figcaption>':'').'</figure>';break;
   case 'embed':
    $src=(string)($d['embed']??'');$service=(string)($d['service']??'');$url=(string)($d['source']??'');
    if($service==='youtube' && preg_match('~(?:youtube\.com/embed/|youtu\.be/)([A-Za-z0-9_-]{6,})~',$src.' '.$url,$m))$src='https://www.youtube-nocookie.com/embed/'.$m[1];
    elseif($service==='vimeo' && preg_match('~vimeo\.com/(?:video/)?([0-9]+)~',$src.' '.$url,$m))$src='https://player.vimeo.com/video/'.$m[1];
    else $src='';
    if($src!=='')$out.='<div class="post-embed"><iframe loading="lazy" src="'.htmlspecialchars($src,ENT_QUOTES,'UTF-8').'" title="ভিডিও" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>';break;
   case 'gallery':
    $items=$d['items']??[]; if(is_array($items)&&$items){$out.='<div class="aspi-media-gallery">';foreach($items as $it){$kind=(string)($it['type']??'image');$url=aspi_public_path((string)($it['url']??''));$cap=htmlspecialchars(strip_tags((string)($it['caption']??'')),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');if($url==='')continue;if($kind==='video'){$out.='<figure class="aspi-gallery-item"><video controls preload="metadata" playsinline src="'.htmlspecialchars($url,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'"></video>'.($cap!==''?'<figcaption>'.$cap.'</figcaption>':'').'</figure>';}elseif($kind==='embed'){$service=(string)($it['service']??'');$src='';if($service==='youtube'&&preg_match('~(?:youtube\.com/(?:watch\?.*v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{6,})~i',$url,$m))$src='https://www.youtube-nocookie.com/embed/'.$m[1];elseif($service==='vimeo'&&preg_match('~vimeo\.com/(?:video/)?([0-9]+)~i',$url,$m))$src='https://player.vimeo.com/video/'.$m[1];if($src!=='')$out.='<figure class="aspi-gallery-item"><div class="aspi-gallery-embed"><iframe loading="lazy" src="'.htmlspecialchars($src,ENT_QUOTES,'UTF-8').'" title="ভিডিও" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>'.($cap!==''?'<figcaption>'.$cap.'</figcaption>':'').'</figure>';}else{$out.='<figure class="aspi-gallery-item"><img loading="lazy" src="'.htmlspecialchars($url,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'" alt="'.$cap.'">'.($cap!==''?'<figcaption>'.$cap.'</figcaption>':'').'</figure>';}}$out.='</div>'; }break;
   case 'table':
    $out.='<div class="post-table-wrap"><table><tbody>';foreach(($d['content']??[]) as $row){$out.='<tr>';foreach((is_array($row)?$row:[]) as $cell)$out.='<td>'.aspi_safe_inline((string)$cell).'</td>';$out.='</tr>';}$out.='</tbody></table></div>';break;
   case 'code': $out.='<pre class="post-code"><code>'.htmlspecialchars((string)($d['code']??''),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'</code></pre>'; break;
   case 'warning': $out.='<aside class="post-warning"><strong>'.htmlspecialchars((string)($d['title']??'গুরুত্বপূর্ণ নোট'),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'</strong><div>'.aspi_safe_inline((string)($d['message']??'')).'</div></aside>'; break;
  }
 }
 return $out;
}
function aspi_post_category(string $cat): string { return ['news'=>'খবর','event'=>'অনুষ্ঠান','workshop'=>'কর্মশালা','notice'=>'অন্যান্য আপডেট'][$cat]??'আপডেট';}
