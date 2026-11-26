<?php
declare(strict_types=1);
/** Render Editor.js JSON using a strict allow-list of block types and HTML tags. */
function aspi_page_safe_inline(string $text): string {
    $text = strip_tags($text, '<b><strong><i><em><u><mark><code><a><br><sup><sub>');
    // Remove dangerous attributes and unsafe links from Editor.js inline HTML.
    $text = preg_replace_callback('/<a\b([^>]*)>/i', function($m) {
        $attrs = $m[1]; $href = '';
        if (preg_match('/\bhref\s*=\s*["\']([^"\']*)["\']/i', $attrs, $hm)) $href = $hm[1];
        $href = trim(html_entity_decode($href, ENT_QUOTES|ENT_HTML5, 'UTF-8'));
        if (!preg_match('~^(https?://|mailto:|tel:|/|#)~i', $href)) return '<a>';
        return '<a href="'.htmlspecialchars($href, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8').'" target="_blank" rel="noopener noreferrer">';
    }, $text);
    // Keep only safe tags, removing event handlers/styles from any retained markup.
    $text = preg_replace('/<(b|strong|i|em|u|mark|code|br|sup|sub)\b[^>]*>/i', '<$1>', $text);
    $text = preg_replace('/<\/(b|strong|i|em|u|mark|code|sup|sub)>/i', '</$1>', $text);
    return $text;
}
function aspi_page_render_editor_json(string $json): string {
    $data = json_decode($json, true);
    if (!is_array($data) || !isset($data['blocks']) || !is_array($data['blocks'])) return '';
    $out = '';
    foreach ($data['blocks'] as $block) {
        if (!is_array($block)) continue;
        $type = (string)($block['type'] ?? '');
        $d = is_array($block['data'] ?? null) ? $block['data'] : [];
        $text = (string)($d['text'] ?? '');
        switch ($type) {
            case 'paragraph': $out .= '<p>'.aspi_page_safe_inline($text).'</p>'; break;
            case 'header':
                $level = (int)($d['level'] ?? 2); if (!in_array($level,[2,3,4],true)) $level=2;
                $out .= '<h'.$level.'>'.aspi_page_safe_inline($text).'</h'.$level.'>'; break;
            case 'list':
                $style = (string)($d['style'] ?? 'unordered'); $tag = $style === 'ordered' ? 'ol' : 'ul';
                $items = is_array($d['items'] ?? null) ? $d['items'] : [];
                $out .= '<'.$tag.'>'; foreach ($items as $item) {
                    if (is_array($item)) $item = (string)($item['content'] ?? '');
                    $out .= '<li>'.aspi_page_safe_inline((string)$item).'</li>';
                } $out .= '</'.$tag.'>'; break;
            case 'quote':
                $out .= '<blockquote><p>'.aspi_page_safe_inline((string)($d['text'] ?? '')).'</p>';
                if (!empty($d['caption'])) $out .= '<cite>'.aspi_page_safe_inline((string)$d['caption']).'</cite>';
                $out .= '</blockquote>'; break;
            case 'delimiter': $out .= '<hr>'; break;
            case 'image':
                $url = (string)($d['file']['url'] ?? $d['url'] ?? '');
                if (preg_match('#^(https://|/|live/)#i', $url)) {
                    $out .= '<figure><img loading="lazy" src="'.htmlspecialchars($url,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'" alt="'.htmlspecialchars((string)($d['alt']??''),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'">';
                    if (!empty($d['caption'])) $out .= '<figcaption>'.aspi_page_safe_inline((string)$d['caption']).'</figcaption>';
                    $out .= '</figure>';
                } break;
            case 'embed':
                $embed = (string)($d['embed'] ?? '');
                $host = strtolower((string)(parse_url($embed, PHP_URL_HOST) ?? ''));
                $allowedHosts = ['youtube.com','www.youtube.com','youtu.be','www.youtube-nocookie.com','youtube-nocookie.com','vimeo.com','www.vimeo.com','player.vimeo.com'];
                if (in_array($host, $allowedHosts, true) && in_array(strtolower((string)parse_url($embed, PHP_URL_SCHEME)), ['https','http'], true)) {
                    $out .= '<div class="page-video"><iframe loading="lazy" src="'.htmlspecialchars($embed,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'" title="Embedded video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe></div>';
                } break;
            case 'gallery':
                $items = is_array($d['items'] ?? null) ? $d['items'] : [];
                if ($items) {
                    $out .= '<div class="page-media-gallery">';
                    foreach ($items as $item) {
                        if (!is_array($item)) continue;
                        $kind = (string)($item['type'] ?? 'image');
                        $url = trim((string)($item['url'] ?? ''));
                        $caption = htmlspecialchars(strip_tags((string)($item['caption'] ?? '')), ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8');
                        if ($url === '') continue;
                        if ($kind === 'video' && preg_match('#^(https://|/|live/)#i', $url)) {
                            $out .= '<figure class="page-gallery-item"><video controls preload="metadata" playsinline src="'.htmlspecialchars($url,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'"></video>'.($caption !== '' ? '<figcaption>'.$caption.'</figcaption>' : '').'</figure>';
                        } elseif ($kind === 'embed') {
                            $service = (string)($item['service'] ?? ''); $src = '';
                            if ($service === 'youtube' && preg_match('~(?:youtube\.com/(?:watch\?.*v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{6,})~i', $url, $m)) $src = 'https://www.youtube-nocookie.com/embed/'.$m[1];
                            elseif ($service === 'vimeo' && preg_match('~vimeo\.com/(?:video/)?([0-9]+)~i', $url, $m)) $src = 'https://player.vimeo.com/video/'.$m[1];
                            if ($src !== '') $out .= '<figure class="page-gallery-item"><div class="page-gallery-embed"><iframe loading="lazy" src="'.htmlspecialchars($src,ENT_QUOTES,'UTF-8').'" title="ভিডিও" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>'.($caption !== '' ? '<figcaption>'.$caption.'</figcaption>' : '').'</figure>';
                        } elseif (preg_match('#^(https://|/|live/)#i', $url)) {
                            $out .= '<figure class="page-gallery-item"><img loading="lazy" src="'.htmlspecialchars($url,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'" alt="'.$caption.'">'.($caption !== '' ? '<figcaption>'.$caption.'</figcaption>' : '').'</figure>';
                        }
                    }
                    $out .= '</div>';
                }
                break;
            case 'table':
                $content = is_array($d['content'] ?? null) ? $d['content'] : [];
                $out .= '<div class="page-table-wrap"><table><tbody>';
                foreach ($content as $row) { if (!is_array($row)) continue; $out .= '<tr>'; foreach ($row as $cell) $out .= '<td>'.aspi_page_safe_inline((string)$cell).'</td>'; $out .= '</tr>'; }
                $out .= '</tbody></table></div>'; break;
            case 'checklist':
                $items = is_array($d['items'] ?? null) ? $d['items'] : []; $out .= '<ul class="page-checklist">';
                foreach ($items as $item) if (is_array($item)) $out .= '<li>'.(!empty($item['checked'])?'☑ ':'☐ ').aspi_page_safe_inline((string)($item['text']??'')).'</li>';
                $out .= '</ul>'; break;
            case 'warning':
                $out .= '<aside class="page-warning"><strong>'.aspi_page_safe_inline((string)($d['title']??'')).'</strong><div>'.aspi_page_safe_inline((string)($d['message']??'')).'</div></aside>'; break;
            case 'code': $out .= '<pre><code>'.htmlspecialchars((string)($d['code']??''),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'</code></pre>'; break;
            case 'raw': $out .= '<pre class="page-raw">'.htmlspecialchars((string)($d['html']??''),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'</pre>'; break;
        }
    }
    return $out;
}
