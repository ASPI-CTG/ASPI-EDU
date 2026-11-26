/* ASPI Global Content Editor v1.3.5 — one shared editor, without third-party editor framework; saves compatible block JSON. */
(function(){'use strict';
 const esc=s=>String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
 const inline=s=>{
  const t=document.createElement('template');t.innerHTML=String(s??'');
  const allowed=new Set(['B','STRONG','I','EM','U','S','DEL','MARK','CODE','A','BR','SPAN','SUP','SUB']);
  const blockTags=new Set(['P','DIV','H1','H2','H3','H4','H5','H6','LI','SECTION','ARTICLE']);
  const safeStyle=el=>{const rules=[];const st=el.getAttribute('style')||'';for(const part of st.split(';')){const ix=part.indexOf(':');if(ix<0)continue;const k=part.slice(0,ix).trim().toLowerCase(),v=part.slice(ix+1).trim();if(!v||/url\s*\(|expression|javascript|behavior/i.test(v))continue;if(k==='font-size'&&/^(\d{1,2}(\.\d+)?(px|pt|em|rem|%)?)$/i.test(v))rules.push('font-size:'+v);else if((k==='color'||k==='background-color')&&/^(#[0-9a-f]{3,8}|rgba?\([\d\s.,%]+\)|[a-z]{3,20})$/i.test(v))rules.push(k+':'+v);else if(k==='font-family'&&/^[\w\s,'"-]{1,100}$/.test(v))rules.push(k+':'+v);else if(k==='font-weight'&&/^(normal|bold|[1-9]00)$/i.test(v))rules.push(k+':'+v);else if(k==='font-style'&&/^(normal|italic|oblique)$/i.test(v))rules.push(k+':'+v);else if(k==='text-decoration'&&/^(none|underline|line-through|overline)(\s+(underline|line-through|overline))*$/i.test(v))rules.push(k+':'+v);}
   return rules.join(';');};
  const normaliseBlocks=node=>{[...node.childNodes].forEach(child=>{if(child.nodeType!==Node.ELEMENT_NODE)return;const tag=child.tagName.toUpperCase();if(blockTags.has(tag)){const frag=document.createDocumentFragment();if(child.previousSibling&&!(child.previousSibling.nodeType===Node.ELEMENT_NODE&&child.previousSibling.tagName==='BR'))frag.append(document.createElement('br'));while(child.firstChild)frag.append(child.firstChild);if(frag.lastChild&&!(frag.lastChild.nodeType===Node.ELEMENT_NODE&&frag.lastChild.tagName==='BR'))frag.append(document.createElement('br'));child.replaceWith(frag);normaliseBlocks(node);}else normaliseBlocks(child);});};
  normaliseBlocks(t.content);
  const clean=node=>{[...node.childNodes].forEach(child=>{if(child.nodeType===Node.COMMENT_NODE){child.remove();return;}if(child.nodeType!==Node.ELEMENT_NODE)return;const tag=child.tagName.toUpperCase();clean(child);if(!allowed.has(tag)){child.replaceWith(...child.childNodes);return;}const attrs=[...child.attributes];attrs.forEach(a=>{const n=a.name.toLowerCase();if(tag==='SPAN'&&n==='style'){const val=safeStyle(child);if(val)child.setAttribute('style',val);else child.removeAttribute('style');}else if(tag==='A'&&n==='href'&&/^(https?:\/\/|mailto:|tel:|\/|#)/i.test(a.value.trim())){}else child.removeAttribute(a.name);});if(tag==='A'){child.setAttribute('target','_blank');child.setAttribute('rel','noopener noreferrer');}});};
  clean(t.content);return t.innerHTML.replace(/(?:<br>\s*){3,}/gi,'<br><br>').replace(/^\s*(?:<br>)+|(?:<br>)+\s*$/gi,'');
 };
 const blockTypes=[['paragraph','অনুচ্ছেদ'],['header','শিরোনাম'],['list','তালিকা'],['quote','উদ্ধৃতি'],['image','ছবি'],['embed','ভিডিও Embed'],['table','টেবিল'],['checklist','চেকলিস্ট'],['delimiter','বিভাজক রেখা'],['code','কোড'],['warning','গুরুত্বপূর্ণ নোট']];
 function normalise(b){let t=b?.type||'paragraph',d=b?.data||{};if(t==='raw')return {type:'paragraph',data:{text:inline(d.html||'')}};if(t==='list')return {type:t,data:{style:d.style||'unordered',items:(d.items||[]).map(x=>typeof x==='string'?{content:x}:({content:x.content||x.text||''}))}};if(t==='image')return {type:t,data:{file:{url:d.file?.url||d.url||''},caption:d.caption||''}};if(t==='embed')return {type:t,data:{service:d.service||serviceFor(d.source||d.embed||''),source:d.source||d.embed||'',embed:d.embed||d.source||''}};if(t==='table')return {type:t,data:{withHeadings:!!d.withHeadings,content:Array.isArray(d.content)?d.content:[['',''],['','']]}};if(t==='checklist')return {type:t,data:{items:(d.items||[]).map(x=>({text:x.text||x.content||'',checked:!!x.checked}))}};return {type:t,data:d};}
 function serviceFor(url){return /vimeo\.com/i.test(url)?'vimeo':/youtube\.com|youtu\.be/i.test(url)?'youtube':'';}
 function embedSrc(url){let m;if((m=String(url).match(/(?:youtube\.com\/(?:watch\?.*v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{6,})/i)))return 'https://www.youtube-nocookie.com/embed/'+m[1];if((m=String(url).match(/vimeo\.com\/(?:video\/)?([0-9]+)/i)))return 'https://player.vimeo.com/video/'+m[1];return '';}
 function make(tag,cls,html){const n=document.createElement(tag);if(cls)n.className=cls;if(html!==undefined)n.innerHTML=html;return n;}
 class GlobalEditor{
  constructor(opts){this.opts=opts;this.holder=typeof opts.holder==='string'?document.getElementById(opts.holder):opts.holder;this.blocks=[];this.savedRange=null;document.addEventListener('selectionchange',()=>{const sel=window.getSelection();if(sel&&sel.rangeCount&&this.workspace&&this.workspace.contains(sel.anchorNode)){this.savedRange=sel.getRangeAt(0).cloneRange();}});this.holder.innerHTML='';this.holder.classList.add('aspi-editor');this.toolbar=make('div','aspi-editor-toolbar');this.toolbar.addEventListener('mousedown',e=>{if(e.target.closest('button'))e.preventDefault();});this.workspace=make('div','aspi-editor-workspace');this.workspace.addEventListener('paste',e=>this.handlePaste(e));this.holder.append(this.toolbar,this.workspace);this.buildToolbar();this.setData(opts.data||{blocks:[]});}
  buildToolbar(){const addBtn=(label,fn,cls='')=>{const b=make('button',cls,label);b.type='button';b.addEventListener('click',fn);this.toolbar.append(b);return b;};
   addBtn('<b>B</b>',()=>document.execCommand('bold'),'ge-format');addBtn('<i>I</i>',()=>document.execCommand('italic'),'ge-format');addBtn('<u>U</u>',()=>document.execCommand('underline'),'ge-format');addBtn('লিংক',()=>{const u=prompt('লিংকের URL দিন (https://...)');if(u&&/^https?:\/\//i.test(u))document.execCommand('createLink',false,u);},'');
   const toolSelect=(label,items,fn,cls='ge-tool-select')=>{const wrap=make('label','ge-tool-label');wrap.append(make('span','ge-tool-label-text',label));const sel=make('select',cls);items.forEach(([v,l])=>{const o=make('option');o.value=v;o.textContent=l;sel.append(o)});sel.addEventListener('mousedown',e=>e.preventDefault());sel.addEventListener('change',()=>fn(sel.value));wrap.append(sel);this.toolbar.append(wrap);return sel;};
   const runFormat=(cmd,val=null)=>{if(this.savedRange){const sel=window.getSelection();sel.removeAllRanges();sel.addRange(this.savedRange);}document.execCommand('styleWithCSS',false,true);document.execCommand(cmd,false,val);const sel=window.getSelection();if(sel&&sel.rangeCount)this.savedRange=sel.getRangeAt(0).cloneRange();};
   this.fontSizeSelect=toolSelect('আকার',[['','ফন্ট সাইজ'],['12px','12 px'],['14px','14 px'],['16px','16 px'],['18px','18 px'],['20px','20 px'],['24px','24 px'],['28px','28 px'],['32px','32 px'],['36px','36 px'],['48px','48 px']],v=>{if(!v)return;runFormat('fontSize','7');this.workspace.querySelectorAll('font[size="7"]').forEach(el=>{const span=document.createElement('span');span.style.fontSize=v;span.innerHTML=el.innerHTML;el.replaceWith(span);});this.fontSizeSelect.value='';});
   this.fontFamilySelect=toolSelect('ফন্ট',[['','ফন্ট'],['Hind, sans-serif','Hind Siliguri'],['Arial, sans-serif','Arial'],['Georgia, serif','Georgia'],['Tahoma, sans-serif','Tahoma']],v=>{if(v){runFormat('fontName',v);this.workspace.querySelectorAll('font[face]').forEach(el=>{const span=document.createElement('span');span.style.fontFamily=el.getAttribute('face');span.innerHTML=el.innerHTML;el.replaceWith(span);});}this.fontFamilySelect.value='';});
   const colorWrap=make('label','ge-color-tool');colorWrap.append(make('span','ge-tool-label-text','লেখার রং'));this.colorInput=make('input');this.colorInput.type='color';this.colorInput.value='#10224b';this.colorInput.title='লেখার রং';this.colorInput.addEventListener('input',()=>runFormat('foreColor',this.colorInput.value));colorWrap.append(this.colorInput);this.toolbar.append(colorWrap);
   const hiWrap=make('label','ge-color-tool');hiWrap.append(make('span','ge-tool-label-text','হাইলাইট'));this.highlightInput=make('input');this.highlightInput.type='color';this.highlightInput.value='#fff0b3';this.highlightInput.title='লেখার হাইলাইট';this.highlightInput.addEventListener('input',()=>runFormat('hiliteColor',this.highlightInput.value));hiWrap.append(this.highlightInput);this.toolbar.append(hiWrap);
   addBtn('বামে',()=>runFormat('justifyLeft'),'ge-format');addBtn('মাঝে',()=>runFormat('justifyCenter'),'ge-format');addBtn('ডানে',()=>runFormat('justifyRight'),'ge-format');addBtn('Justify',()=>runFormat('justifyFull'),'ge-format');addBtn('ফরম্যাট মুছুন',()=>runFormat('removeFormat'),'ge-format');
   this.toolbar.append(make('span','ge-sep'));
   this.typeSelect=make('select');this.typeSelect.setAttribute('aria-label','নতুন ব্লকের ধরন');blockTypes.forEach(([v,l])=>{const o=make('option');o.value=v;o.textContent=l;this.typeSelect.append(o)});this.toolbar.append(this.typeSelect);
   addBtn('<i class="fa-solid fa-plus"></i> ব্লক যোগ করুন',()=>this.addBlock(this.typeSelect.value),'ge-primary');
   addBtn('<i class="fa-regular fa-image"></i> ছবি আপলোড',()=>this.pickImage());addBtn('<i class="fa-brands fa-youtube"></i> ভিডিও',()=>this.addVideo());addBtn('<i class="fa-solid fa-table"></i> টেবিল',()=>this.addBlock('table'));addBtn('<i class="fa-solid fa-list-check"></i> চেকলিস্ট',()=>this.addBlock('checklist'));addBtn('<i class="fa-brands fa-markdown"></i> Markdown',()=>this.openMarkdownImport(),'ge-markdown-btn');
   const status=make('span','ge-status','Word 365 Paste · Markdown');this.toolbar.append(status);this.status=status;
  }
  setData(data){this.blocks=(Array.isArray(data?.blocks)?data.blocks:[]).map(normalise);if(!this.blocks.length)this.blocks=[{type:'paragraph',data:{text:''}}];this.render();}
  getData(){return {time:Date.now(),blocks:this.blocks.map((b,i)=>({id:b.id||('aspi-'+i),type:b.type,data:this.readBlock(b,i)})),version:'aspi-global-1.2'};}
  readBlock(b,i){const el=this.workspace.querySelector('[data-index="'+i+'"]');if(!el)return b.data||{};const d=b.data||{};switch(b.type){case'header':case'paragraph':return {text:inline(el.querySelector('.ge-editable')?.innerHTML||'') ,level:d.level||2};case'list':return {style:el.querySelector('ol,ul')?.dataset.style||d.style||'unordered',items:[...el.querySelectorAll('.ge-list-item')].map(x=>({content:inline(x.innerHTML)})).filter(x=>x.content.trim())};case'quote':return {text:inline(el.querySelector('.ge-quote-text')?.innerHTML||''),caption:el.querySelector('.ge-caption')?.value||''};case'image':return {file:{url:el.dataset.url||d.file?.url||''},caption:el.querySelector('.ge-caption')?.value||d.caption||''};case'embed':{const url=el.querySelector('.ge-video-url')?.value||d.source||d.embed||'';return {service:serviceFor(url)||d.service||'',source:url,embed:embedSrc(url)||d.embed||''};}case'table':return {content:[...el.querySelectorAll('tr')].map(tr=>[...tr.querySelectorAll('[contenteditable]')].map(c=>inline(c.innerHTML)))};case'checklist':return {items:[...el.querySelectorAll('.ge-check-row')].map(r=>({text:inline(r.querySelector('[contenteditable]').innerHTML),checked:r.querySelector('input').checked}))};case'code':return {code:el.querySelector('textarea')?.value||d.code||''};case'warning':return {title:el.querySelector('.ge-warning-title')?.innerText||'',message:inline(el.querySelector('[contenteditable]')?.innerHTML||'')};default:return d;}}
  render(){
   this.workspace.innerHTML='';
   this.blocks.forEach((b,i)=>{
    this.workspace.append(this.renderInsertPoint(i));
    this.workspace.append(this.renderBlock(b,i));
   });
   this.workspace.append(this.renderInsertPoint(this.blocks.length));
  }
  renderInsertPoint(index){
   const row=make('div','ge-insert-point');
   const line=make('span','ge-insert-line');
   const btn=make('button','ge-insert-plus','+');btn.type='button';btn.title='এখানে নতুন ব্লক যোগ করুন';btn.setAttribute('aria-label','এখানে নতুন ব্লক যোগ করুন');
   btn.addEventListener('click',()=>this.openInsertMenu(btn,index));
   row.append(line,btn);return row;
  }
  openInsertMenu(anchor,index){
   this.closeBlockMenus();
   const menu=make('div','ge-insert-menu');menu.setAttribute('role','menu');
   const search=make('input','ge-insert-search');search.type='search';search.placeholder='ব্লক খুঁজুন…';search.setAttribute('aria-label','ব্লক খুঁজুন');menu.append(search);
   const list=make('div','ge-insert-options');
   blockTypes.forEach(([type,label])=>{const b=make('button','ge-insert-option',`<span class="ge-insert-option-icon">${this.blockIcon(type)}</span><span>${esc(label)}</span>`);b.type='button';b.dataset.label=label.toLowerCase();b.setAttribute('role','menuitem');b.addEventListener('click',()=>{menu.remove();this.addBlock(type,index);});list.append(b);});
   menu.append(list);anchor.parentElement.append(menu);search.addEventListener('input',()=>{const q=search.value.trim().toLowerCase();list.querySelectorAll('button').forEach(b=>b.hidden=!b.dataset.label.includes(q));});
   const outside=e=>{if(!menu.contains(e.target)&&e.target!==anchor){menu.remove();document.removeEventListener('pointerdown',outside,true);}};
   setTimeout(()=>document.addEventListener('pointerdown',outside,true),0);search.focus();
  }
  blockIcon(type){return ({paragraph:'¶',header:'H',list:'☷',quote:'❝',image:'▧',embed:'▶',table:'▦',checklist:'☑',delimiter:'―',code:'</>',warning:'!'}[type]||'+');}
  closeBlockMenus(){
   if(this._blockMenuOutside){document.removeEventListener('pointerdown',this._blockMenuOutside,true);this._blockMenuOutside=null;}
   if(this._blockMenuEscape){document.removeEventListener('keydown',this._blockMenuEscape,true);this._blockMenuEscape=null;}
   document.querySelectorAll('.ge-block-menu').forEach(x=>x.remove());
   this.workspace.querySelectorAll('.ge-insert-menu').forEach(x=>x.remove());
  }
  renderBlock(b,i){const d=b.data||{},wrap=make('section','ge-block');wrap.dataset.index=i;wrap.dataset.type=b.type;
   const tools=make('div','ge-block-tools');
   const drag=make('button','ge-drag-handle','⠿');drag.type='button';drag.title='একবার ট্যাপ করলে মুছুন অপশন; চেপে ধরে টানলে ব্লক সরান';drag.setAttribute('aria-label','ব্লক কন্ট্রোল: ট্যাপ করে মুছুন অপশন, চেপে ধরে সরান');drag.setAttribute('aria-expanded','false');drag.draggable=false;
   const del=make('button','ge-block-delete','×');del.type='button';del.title='ব্লক মুছে ফেলুন';del.setAttribute('aria-label','এই ব্লক মুছে ফেলুন');del.hidden=true;
   del.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();if(confirm('এই ব্লকটি মুছে ফেলবেন?'))this.remove(Number(wrap.dataset.index));});
   let pressTimer=null, longPressed=false, pointerId=null, targetIndex=i, startX=0, startY=0, moved=false;
   const clearPress=()=>{if(pressTimer){clearTimeout(pressTimer);pressTimer=null;}};
   const endInteraction=(e)=>{
    if(pointerId===null || (e.pointerId!==undefined && e.pointerId!==pointerId))return;
    clearPress();
    document.removeEventListener('pointermove',onMove,true);document.removeEventListener('pointerup',onUp,true);document.removeEventListener('pointercancel',onCancel,true);
    if(longPressed){
     this.workspace.querySelectorAll('.ge-block').forEach(x=>x.classList.remove('ge-block-dragging','ge-block-drop-target'));
     const from=i,to=targetIndex;this.dragIndex=null;
     if(to!==from)this.reorder(from,to);
    } else if(!e.type.includes('cancel') && !moved) {
     const show=del.hidden;del.hidden=!show;wrap.classList.toggle('ge-block-delete-open',show);drag.setAttribute('aria-expanded',String(show));
    }
    pointerId=null;longPressed=false;targetIndex=i;moved=false;
   };
   const onMove=e=>{
    if(pointerId===null || e.pointerId!==pointerId)return;
    if(!longPressed && (Math.abs(e.clientX-startX)>10 || Math.abs(e.clientY-startY)>10)){moved=true;clearPress();return;}
    if(!longPressed)return;
    e.preventDefault();
    const hit=document.elementFromPoint(e.clientX,e.clientY)?.closest('.ge-block');
    if(hit&&this.workspace.contains(hit)){
     targetIndex=Number(hit.dataset.index);
     this.workspace.querySelectorAll('.ge-block').forEach(x=>x.classList.toggle('ge-block-drop-target',x===hit&&x!==wrap));
    }
   };
   const onUp=e=>endInteraction(e),onCancel=e=>endInteraction(e);
   drag.addEventListener('pointerdown',e=>{
    if(e.button!==undefined&&e.button!==0)return;
    e.preventDefault();e.stopPropagation();clearPress();pointerId=e.pointerId;startX=e.clientX;startY=e.clientY;targetIndex=i;longPressed=false;moved=false;
    document.addEventListener('pointermove',onMove,{capture:true,passive:false});document.addEventListener('pointerup',onUp,true);document.addEventListener('pointercancel',onCancel,true);
    pressTimer=setTimeout(()=>{if(pointerId===e.pointerId){longPressed=true;del.hidden=true;wrap.classList.remove('ge-block-delete-open');drag.setAttribute('aria-expanded','false');wrap.classList.add('ge-block-dragging');this.dragIndex=i;}},450);
   });
   drag.addEventListener('click',e=>{if(e.detail===0){e.preventDefault();const show=del.hidden;del.hidden=!show;wrap.classList.toggle('ge-block-delete-open',show);drag.setAttribute('aria-expanded',String(show));}});
   drag.addEventListener('keydown',e=>{if(e.key==='Escape'){del.hidden=true;wrap.classList.remove('ge-block-delete-open');drag.setAttribute('aria-expanded','false');}});
   tools.append(drag,del);wrap.append(tools);
   const editable=(html,placeholder,cls='')=>{const n=make('div','ge-editable '+cls,html||'');n.contentEditable='true';n.dataset.placeholder=placeholder;return n;};
   if(b.type==='header'){const el=editable(d.text||'','শিরোনাম লিখুন','ge-header');wrap.append(el);}
   else if(b.type==='paragraph'){wrap.append(editable(d.text||'','এখানে অনুচ্ছেদ লিখুন…'));}
   else if(b.type==='list'){const ol=make((d.style==='ordered'?'ol':'ul'),'');ol.dataset.style=d.style||'unordered';(d.items?.length?d.items:[{content:''}]).forEach(it=>{const li=editable(typeof it==='string'?it:(it.content||''),'তালিকার আইটেম লিখুন','ge-list-item');li.tagName;const item=make('li');item.append(li);ol.append(item)});wrap.append(ol);const sel=make('select','ge-secondary-input');sel.innerHTML='<option value="unordered">বুলেট তালিকা</option><option value="ordered">ক্রমিক তালিকা</option>';sel.value=d.style||'unordered';sel.onchange=()=>{ol.dataset.style=sel.value;const repl=make(sel.value==='ordered'?'ol':'ul');repl.dataset.style=sel.value;while(ol.firstChild)repl.append(ol.firstChild);ol.replaceWith(repl)};wrap.append(sel);const plus=make('button','ge-secondary-input','+ আইটেম যোগ করুন');plus.type='button';plus.onclick=()=>{const target=wrap.querySelector('ol,ul');const li=make('li');li.append(editable('','তালিকার আইটেম লিখুন','ge-list-item'));target.append(li)};wrap.append(plus);}
   else if(b.type==='quote'){const q=editable(d.text||'','উদ্ধৃতি লিখুন','ge-quote-text ge-quote');wrap.append(q);const cap=make('input','ge-caption');cap.placeholder='উৎস / বক্তা (ঐচ্ছিক)';cap.value=d.caption||'';wrap.append(cap);}
   else if(b.type==='image'){const url=d.file?.url||d.url||'';wrap.dataset.url=url;if(url){const fig=make('figure');const img=make('img','ge-image-preview');img.src=url;img.alt=d.caption||'ছবি';fig.append(img);wrap.append(fig);}else wrap.append(make('div','ge-empty-note','ছবি আপলোড করতে উপরের “ছবি আপলোড” বাটন ব্যবহার করুন।'));const cap=make('input','ge-caption');cap.placeholder='ছবির ক্যাপশন / বিকল্প লেখা';cap.value=d.caption||'';wrap.append(cap);}
   else if(b.type==='embed'){const url=d.source||d.embed||'';const info=make('div','ge-video-preview',url?'বর্তমান ভিডিও: '+esc(url):'YouTube বা Vimeo ভিডিওর URL দিন।');wrap.append(info);const inp=make('input','ge-caption ge-video-url');inp.placeholder='https://www.youtube.com/watch?v=…';inp.value=url;wrap.append(inp);}
   else if(b.type==='table'){const table=make('table','ge-table-editor');const content=d.content?.length?d.content:[['',''],['','']];content.forEach(row=>{const tr=make('tr');(row.length?row:['','']).forEach(cell=>{const td=make('td');const ce=editable(cell,'লিখুন');ce.setAttribute('role','textbox');td.append(ce);tr.append(td)});table.append(tr)});wrap.append(table);const controls=make('div','d-flex gap-2 flex-wrap mt-2');const rowBtn=make('button','ge-secondary-input','+ সারি');rowBtn.type='button';rowBtn.onclick=()=>{const cols=table.rows[0]?.cells.length||2;const tr=make('tr');for(let j=0;j<cols;j++){const td=make('td');td.append(editable('','লিখুন'));tr.append(td)}table.append(tr)};const colBtn=make('button','ge-secondary-input','+ কলাম');colBtn.type='button';colBtn.onclick=()=>[...table.rows].forEach(tr=>{const td=make('td');td.append(editable('','লিখুন'));tr.append(td)});controls.append(rowBtn,colBtn);wrap.append(controls);}
   else if(b.type==='checklist'){(d.items?.length?d.items:[{text:'',checked:false}]).forEach(it=>{const row=make('div','ge-check-row');const cb=make('input');cb.type='checkbox';cb.checked=!!it.checked;row.append(cb,editable(it.text||'','চেকলিস্টের আইটেম লিখুন'));wrap.append(row)});const plus=make('button','ge-secondary-input','+ আইটেম যোগ করুন');plus.type='button';plus.onclick=()=>{const row=make('div','ge-check-row');const cb=make('input');cb.type='checkbox';cb.checked=false;row.append(cb,editable('','চেকলিস্টের আইটেম লিখুন'));wrap.insertBefore(row,plus)};wrap.append(plus);}
   else if(b.type==='delimiter'){wrap.append(make('hr'));}
   else if(b.type==='code'){const ta=make('textarea','form-control ge-code');ta.rows=4;ta.value=d.code||'';ta.placeholder='কোড লিখুন…';wrap.append(ta);}
   else if(b.type==='warning'){const title=make('div','ge-warning-title',esc(d.title||'গুরুত্বপূর্ণ নোট'));title.contentEditable='true';wrap.append(title,editable(d.message||'','নোটের বিবরণ লিখুন','ge-warning'));}
   else {wrap.append(editable(d.text||'','এখানে লিখুন…'));}
   return wrap;
  }
  placeFloatingMenu(anchor,menu){
   menu.style.position='fixed';menu.style.left='0px';menu.style.top='0px';menu.style.visibility='hidden';
   menu.style.width=Math.min(270,Math.max(220,window.innerWidth-24))+'px';
   menu.style.maxWidth='calc(100vw - 24px)';menu.style.maxHeight=Math.max(160,window.innerHeight-24)+'px';menu.style.overflowY='auto';
   document.body.append(menu);
   const a=anchor.getBoundingClientRect(),m=menu.getBoundingClientRect(),gap=8,pad=12;
   let left=a.right+gap;
   if(left+m.width>window.innerWidth-pad)left=a.left-m.width-gap;
   left=Math.max(pad,Math.min(left,window.innerWidth-m.width-pad));
   let top=a.top;
   if(top+m.height>window.innerHeight-pad)top=window.innerHeight-m.height-pad;
   top=Math.max(pad,top);
   menu.style.left=Math.round(left)+'px';menu.style.top=Math.round(top)+'px';menu.style.visibility='visible';
  }
  openBlockMenu(anchor,index){
   if(!anchor)return;
   this.closeBlockMenus();
   const menu=make('div','ge-block-menu');menu.setAttribute('role','menu');menu.setAttribute('aria-label','ব্লক পরিচালনা');
   const actions=[
    ['↑','উপরে নিন',()=>this.move(index,-1),index===0],
    ['↓','নিচে নিন',()=>this.move(index,1),index===this.blocks.length-1],
    ['⧉','ব্লক ডুপ্লিকেট করুন',()=>this.duplicate(index),false],
    ['↕','ব্লকের ধরন পরিবর্তন',()=>this.openConvertMenu(menu,index),false],
    ['×','ব্লক মুছে ফেলুন',()=>{if(confirm('এই ব্লকটি মুছে ফেলবেন?'))this.remove(index);},false]
   ];
   actions.forEach(([icon,label,fn,disabled])=>{const b=make('button','ge-block-menu-item',`<span class="ge-block-menu-icon" aria-hidden="true">${icon}</span><span class="ge-block-menu-label">${esc(label)}</span>`);b.type='button';b.disabled=!!disabled;b.setAttribute('role','menuitem');b.addEventListener('click',()=>{if(!b.disabled){this.closeBlockMenus();anchor.setAttribute('aria-expanded','false');fn();}});menu.append(b);});
   this.placeFloatingMenu(anchor,menu);anchor.setAttribute('aria-expanded','true');
   this._blockMenuOutside=e=>{if(!menu.contains(e.target)&&e.target!==anchor){this.closeBlockMenus();anchor.setAttribute('aria-expanded','false');}};
   this._blockMenuEscape=e=>{if(e.key==='Escape'){this.closeBlockMenus();anchor.setAttribute('aria-expanded','false');anchor.focus({preventScroll:true});}};
   setTimeout(()=>{document.addEventListener('pointerdown',this._blockMenuOutside,true);document.addEventListener('keydown',this._blockMenuEscape,true);},0);
   const first=menu.querySelector('button:not(:disabled)');if(first)first.focus({preventScroll:true});
  }
  openConvertMenu(menu,index){
   menu.innerHTML='';
   const back=make('button','ge-block-menu-item',`<span class="ge-block-menu-icon" aria-hidden="true">←</span><span class="ge-block-menu-label">ফিরে যান</span>`);back.type='button';back.onclick=()=>{const anchor=this.workspace.querySelector(`.ge-block[data-index="${index}"] .ge-block-more`);this.openBlockMenu(anchor,index);};menu.append(back);
   blockTypes.filter(([type])=>['paragraph','header','list','quote','delimiter','warning','code'].includes(type)).forEach(([type,label])=>{const b=make('button','ge-block-menu-item',`<span class="ge-block-menu-icon" aria-hidden="true">${this.blockIcon(type)}</span><span class="ge-block-menu-label">${esc(label)}</span>`);b.type='button';b.setAttribute('role','menuitem');b.onclick=()=>{this.closeBlockMenus();this.convertBlock(index,type);};menu.append(b);});
   const anchor=this.workspace.querySelector(`.ge-block[data-index="${index}"] .ge-block-more`);if(anchor)this.placeFloatingMenu(anchor,menu);
  }
  convertBlock(index,type){
   this.blocks=this.getData().blocks.map(normalise);const old=this.blocks[index];if(!old)return;const d=old.data||{};let data;
   if(type==='paragraph'||type==='header')data={text:d.text||d.message||d.code||d.items?.map(x=>x.content||x.text||'').join(' ')||'',level:type==='header'?(d.level||2):undefined};
   else if(type==='list')data={style:'unordered',items:[{content:d.text||d.message||''}]};
   else if(type==='quote')data={text:d.text||d.message||'',caption:''};
   else if(type==='warning')data={title:'গুরুত্বপূর্ণ নোট',message:d.text||d.message||''};
   else if(type==='code')data={code:(d.text||d.message||'').replace(/<[^>]*>/g,'')};else data={};
   this.blocks[index]={type,data};this.render();
  }
  duplicate(index){this.blocks=this.getData().blocks.map(normalise);if(!this.blocks[index])return;const copy=JSON.parse(JSON.stringify(this.blocks[index]));this.blocks.splice(index+1,0,copy);this.render();}
  reorder(from,to){this.blocks=this.getData().blocks.map(normalise);if(from<0||to<0||from>=this.blocks.length||to>=this.blocks.length||from===to)return;const [item]=this.blocks.splice(from,1);this.blocks.splice(to,0,item);this.render();}
  markdownInline(text){
   let out=esc(text);out=out.replace(/`([^`]+)`/g,'<code>$1</code>').replace(/\*\*([^*]+)\*\*/g,'<strong>$1</strong>').replace(/__([^_]+)__/g,'<strong>$1</strong>').replace(/(?<!\*)\*([^*]+)\*(?!\*)/g,'<em>$1</em>').replace(/(?<!_)_([^_]+)_(?!_)/g,'<em>$1</em>').replace(/~~([^~]+)~~/g,'<s>$1</s>').replace(/\[([^\]]+)\]\((https?:\/\/[^\s)]+|mailto:[^\s)]+)\)/g,'<a href="$2">$1</a>');return inline(out);
  }
  parseMarkdown(source){
   const lines=String(source||'').replace(/\r\n?/g,'\n').split('\n'),out=[];let i=0,para=[];
   const flush=()=>{if(para.length){const text=para.join(' ').trim();if(text)out.push({type:'paragraph',data:{text:this.markdownInline(text)}});para=[];}};
   while(i<lines.length){const line=lines[i],trim=line.trim();if(!trim){flush();i++;continue;}
    if(/^```/.test(trim)||/^~~~/.test(trim)){flush();const fence=trim.slice(0,3);i++;const code=[];while(i<lines.length&&!lines[i].trim().startsWith(fence)){code.push(lines[i++]);}if(i<lines.length)i++;out.push({type:'code',data:{code:code.join('\n')}});continue;}
    const hm=trim.match(/^(#{1,6})\s+(.+?)\s*#*$/);if(hm){flush();out.push({type:'header',data:{text:this.markdownInline(hm[2]),level:hm[1].length<=2?2:hm[1].length===3?3:4}});i++;continue;}
    if(/^([-*_])\1\1+\s*$/.test(trim)){flush();out.push({type:'delimiter',data:{}});i++;continue;}
    if(/^>\s?/.test(trim)){flush();const q=[];while(i<lines.length&&/^>\s?/.test(lines[i].trim()))q.push(lines[i++].trim().replace(/^>\s?/,''));out.push({type:'quote',data:{text:this.markdownInline(q.join(' ')),caption:''}});continue;}
    if(/^\|.*\|$/.test(trim)&&i+1<lines.length&&/^\|?\s*:?-{3,}/.test(lines[i+1].trim())){flush();const parseRow=x=>x.trim().replace(/^\||\|$/g,'').split('|').map(c=>this.markdownInline(c.trim()));const head=parseRow(lines[i]);i+=2;const rows=[head];while(i<lines.length&&/^\|.*\|$/.test(lines[i].trim()))rows.push(parseRow(lines[i++]));out.push({type:'table',data:{withHeadings:true,content:rows}});continue;}
    const ul=trim.match(/^[-*+]\s+(.+)$/),ol=trim.match(/^\d+[.)]\s+(.+)$/);if(ul||ol){flush();const ordered=!!ol,items=[];while(i<lines.length){const m=lines[i].trim().match(ordered?/^\d+[.)]\s+(.+)$/:/^[-*+]\s+(.+)$/);if(!m)break;items.push({content:this.markdownInline(m[1])});i++;}out.push({type:'list',data:{style:ordered?'ordered':'unordered',items}});continue;}
    const im=trim.match(/^!\[([^\]]*)\]\((https?:\/\/[^\s)]+)\)$/);if(im){flush();out.push({type:'image',data:{file:{url:im[2]},caption:im[1]}});i++;continue;}
    para.push(trim);i++;
   }
   flush();return out.length?out:[{type:'paragraph',data:{text:''}}];
  }
  blocksFromHTML(html){
   const doc=new DOMParser().parseFromString(String(html||''),'text/html'),out=[];
   const addInline=(node,type='paragraph',level=2)=>{const h=inline(node.innerHTML||'');if(h.trim())out.push(type==='header'?{type:'header',data:{text:h,level}}:{type:'paragraph',data:{text:h}});};
   const visit=node=>{[...node.childNodes].forEach(el=>{if(el.nodeType===Node.TEXT_NODE){if(el.textContent.trim())out.push({type:'paragraph',data:{text:esc(el.textContent.trim())}});return;}if(el.nodeType!==Node.ELEMENT_NODE)return;const tag=el.tagName.toUpperCase();if(tag==='META'||tag==='STYLE'||tag==='SCRIPT'||tag==='XML'||tag==='LINK')return;
    if(/^H[1-6]$/.test(tag)){addInline(el,'header',tag==='H1'||tag==='H2'?2:tag==='H3'?3:4);return;}
    if(tag==='P'){if(el.closest('li'))return;const isWordList=/mso-list/i.test(el.getAttribute('style')||'')||!!el.querySelector('span[style*="mso-list"]');if(isWordList){const txt=(el.innerText||el.textContent||'').replace(/^\s*[•·▪◦‣\-\*]+\s*/,'').replace(/^\s*\d+[.)]\s*/,'').trim();if(txt){const ordered=/^\s*\d+[.)]/.test(el.innerText||'');const last=out[out.length-1];if(last?.type==='list'&&last.data.style===(ordered?'ordered':'unordered'))last.data.items.push({content:inline(esc(txt))});else out.push({type:'list',data:{style:ordered?'ordered':'unordered',items:[{content:inline(esc(txt))}]}});}return;}addInline(el);return;}
    if(tag==='UL'||tag==='OL'){const items=[...el.querySelectorAll(':scope > li')].map(li=>({content:inline(li.innerHTML)})).filter(x=>x.content.trim());if(items.length)out.push({type:'list',data:{style:tag==='OL'?'ordered':'unordered',items}});return;}
    if(tag==='BLOCKQUOTE'){const text=inline(el.innerHTML);if(text)out.push({type:'quote',data:{text,caption:''}});return;}
    if(tag==='HR'){out.push({type:'delimiter',data:{}});return;}
    if(tag==='TABLE'){const rows=[...el.querySelectorAll('tr')].map(tr=>[...tr.querySelectorAll('th,td')].map(c=>inline(c.innerHTML)));if(rows.length)out.push({type:'table',data:{withHeadings:!!el.querySelector('th'),content:rows}});return;}
    if(tag==='IMG'){const src=el.getAttribute('src')||'';if(src&&/^https?:\/\//i.test(src))out.push({type:'image',data:{file:{url:src},caption:el.getAttribute('alt')||''}});return;}
    if(tag==='DIV'||tag==='SECTION'||tag==='ARTICLE'||tag==='MAIN'){const children=[...el.children];if(children.length)visit(el);else addInline(el);return;}
    if(['HTML','BODY'].includes(tag)){visit(el);return;}
    addInline(el);
   });};
   visit(doc.body);return out.length?out:([{type:'paragraph',data:{text:inline(doc.body.innerHTML)}}]);
  }
  handlePaste(e){
   const target=e.target.closest('.ge-editable');if(!target)return;const clip=e.clipboardData;if(!clip)return;const html=clip.getData('text/html')||'',text=clip.getData('text/plain')||'';const markdown=/(^|\n)\s{0,3}(#{1,6}\s|[-*+]\s|\d+[.)]\s|>\s|```|~~~|\|.+\|\s*$)|\*\*[^*]+\*\*|\[[^\]]+\]\(https?:\/\/[^)]+\)/m.test(text);
   const richBlocks=/<(?:p|h[1-6]|ul|ol|table|blockquote|hr)\b/i.test(html)||/class=["'][^"']*WordSection|mso-/i.test(html);const multiLine=!html&&text.includes('\n');if(!markdown&&!richBlocks&&!multiLine)return;
   e.preventDefault();let incoming=[];if(markdown)incoming=this.parseMarkdown(text);else if(html&&richBlocks)incoming=this.blocksFromHTML(html);else if(text.includes('\n'))incoming=text.replace(/\r/g,'').split(/\n/).map(x=>x.trim()).filter(Boolean).map(x=>({type:'paragraph',data:{text:inline(esc(x))}}));else incoming=[{type:'paragraph',data:{text:inline(esc(text))}}];
   this.blocks=this.getData().blocks.map(normalise);const idx=Number(target.closest('.ge-block')?.dataset.index);if(Number.isInteger(idx)&&idx>=0&&idx<this.blocks.length)this.blocks.splice(idx,1,...incoming);else this.blocks.push(...incoming);this.render();this.status.textContent='পেস্ট করা কনটেন্ট রূপান্তরিত হয়েছে';setTimeout(()=>{if(this.status)this.status.textContent='Word 365 Paste · Markdown';},2500);
  }
  openMarkdownImport(){
   const active=document.activeElement;const current=active?.closest?.('.ge-block');this.markdownInsertIndex=current?Number(current.dataset.index):this.blocks.length;
   const overlay=make('div','ge-md-overlay'),dialog=make('div','ge-md-dialog');dialog.setAttribute('role','dialog');dialog.setAttribute('aria-modal','true');dialog.setAttribute('aria-label','Markdown Import');
   const head=make('div','ge-md-head');head.append(make('strong','','Markdown Import'));const close=make('button','ge-md-close','×');close.type='button';close.setAttribute('aria-label','বন্ধ করুন');head.append(close);
   const help=make('p','ge-md-help','ChatGPT বা অন্য জায়গা থেকে Markdown পেস্ট করুন। নিচে Preview দেখে “এডিটরে যুক্ত করুন” চাপুন।');const area=make('textarea','ge-md-source');area.placeholder='# শিরোনাম\n\n**গুরুত্বপূর্ণ লেখা**\n\n- প্রথম আইটেম\n- দ্বিতীয় আইটেম';const preview=make('div','ge-md-preview');preview.innerHTML='<p>Markdown Preview এখানে দেখা যাবে।</p>';
   const actions=make('div','ge-md-actions'),cancel=make('button','ge-secondary-input','বাতিল'),insert=make('button','ge-primary','এডিটরে যুক্ত করুন');cancel.type=insert.type='button';actions.append(cancel,insert);dialog.append(head,help,area,make('div','ge-md-preview-label','Preview'),preview,actions);overlay.append(dialog);document.body.append(overlay);
   const renderPreview=()=>{const bs=this.parseMarkdown(area.value);preview.innerHTML=bs.map(b=>{const d=b.data||{};if(b.type==='header')return '<h'+d.level+'>'+d.text+'</h'+d.level+'>';if(b.type==='paragraph')return '<p>'+d.text+'</p>';if(b.type==='list')return '<'+(d.style==='ordered'?'ol':'ul')+'>'+d.items.map(x=>'<li>'+x.content+'</li>').join('')+'</'+(d.style==='ordered'?'ol':'ul')+'>';if(b.type==='quote')return '<blockquote>'+d.text+'</blockquote>';if(b.type==='delimiter')return '<hr>';if(b.type==='code')return '<pre><code>'+esc(d.code)+'</code></pre>';if(b.type==='table')return '<div class="ge-md-table-wrap"><table>'+d.content.map((r,i)=>'<tr>'+r.map(c=>i===0&&d.withHeadings?'<th>'+c+'</th>':'<td>'+c+'</td>').join('')+'</tr>').join('')+'</table></div>';if(b.type==='image')return '<p>[ছবি] '+esc(d.caption||d.file.url)+'</p>';return '';}).join('')||'<p>Preview করার মতো কনটেন্ট নেই।</p>';};area.addEventListener('input',renderPreview);renderPreview();const done=()=>overlay.remove();close.onclick=cancel.onclick=done;overlay.addEventListener('click',e=>{if(e.target===overlay)done();});insert.onclick=()=>{const blocks=this.parseMarkdown(area.value);this.blocks=this.getData().blocks.map(normalise);const idx=this.markdownInsertIndex;if(Number.isInteger(idx)&&idx>=0&&idx<this.blocks.length)this.blocks.splice(idx,1,...blocks);else this.blocks.push(...blocks);this.render();this.status.textContent='Markdown এডিটরে যুক্ত হয়েছে';done();};area.focus();
  }
  addBlock(type,index=null){this.blocks=this.getData().blocks.map(normalise);let data={};if(type==='header')data={text:'',level:2};else if(type==='list')data={style:'unordered',items:[{content:''}]};else if(type==='quote')data={text:'',caption:''};else if(type==='image')data={file:{url:''},caption:''};else if(type==='embed')data={service:'',source:'',embed:''};else if(type==='table')data={content:[['',''],['','']]};else if(type==='checklist')data={items:[{text:'',checked:false}]};else if(type==='code')data={code:''};else if(type==='warning')data={title:'গুরুত্বপূর্ণ নোট',message:''};else data={text:''};const at=Number.isInteger(index)?Math.max(0,Math.min(index,this.blocks.length)):this.blocks.length;this.blocks.splice(at,0,{type,data});this.render();this.workspace.querySelector('.ge-block[data-index="'+at+'"] .ge-editable')?.focus();this.workspace.querySelector('.ge-block[data-index="'+at+'"]')?.scrollIntoView({behavior:'smooth',block:'nearest'});}
  move(i,dir){const j=i+dir;if(j<0||j>=this.blocks.length)return;const data=this.getData().blocks;const [item]=data.splice(i,1);data.splice(j,0,item);this.blocks=data.map(normalise);this.render();}
  remove(i){this.blocks=this.getData().blocks.map(normalise);if(this.blocks.length===1){this.blocks=[{type:'paragraph',data:{text:''}}];}else this.blocks.splice(i,1);this.render();}
  async pickImage(){const input=make('input');input.type='file';input.accept='image/jpeg,image/png,image/webp,image/gif';input.onchange=async()=>{const file=input.files?.[0];if(!file)return;if(file.size>10*1024*1024){alert('ছবির সর্বোচ্চ সাইজ ১০ MB।');return;}const fd=new FormData();fd.append('image',file);fd.append('csrf',this.opts.csrf||'');this.status.textContent='ছবি আপলোড হচ্ছে…';try{const res=await fetch(this.opts.uploadUrl,{method:'POST',body:fd,credentials:'same-origin'});const j=await res.json();if(!res.ok||!j.success)throw new Error(j.message||'আপলোড ব্যর্থ');this.blocks=this.getData().blocks.map(normalise);this.blocks.push({type:'image',data:{file:{url:j.file.url},caption:''}});this.render();this.status.textContent='ছবি আপলোড সম্পন্ন';}catch(e){alert(e.message||'ছবি আপলোড করা যায়নি।');this.status.textContent='ASPI Global Editor';}};input.click();}
  addVideo(){const url=prompt('YouTube বা Vimeo ভিডিওর URL দিন:');if(!url)return;if(!serviceFor(url)){alert('শুধু YouTube বা Vimeo URL ব্যবহার করুন।');return;}this.blocks=this.getData().blocks.map(normalise);this.blocks.push({type:'embed',data:{service:serviceFor(url),source:url,embed:embedSrc(url)}});this.render();}
 }
 window.ASPIEditor={mount:opts=>new GlobalEditor(opts),version:'1.3.2'};
})();
