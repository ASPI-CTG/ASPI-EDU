<footer id="aspi-global-footer" class="footer aspi-shell-footer" role="contentinfo">
  <div class="container aspi-shell-container">
    <div class="footer-top aspi-shell-footer-top">
      <div class="footer-brand aspi-shell-footer-brand">
        <img src="/assets/aspi-logo.svg" alt="ASPI">
        <span><b><?=aspi_shell_h($aspiShellName)?></b><small><?=aspi_shell_h($aspiShellTagline)?></small><small><?=aspi_shell_h($aspiShellAddress)?></small></span>
      </div>
      <div class="footer-links aspi-shell-footer-links">
        <a href="/live/institution.php">প্রতিষ্ঠান পরিচিতি</a>
        <a href="/live/founder.php">প্রতিষ্ঠাতা</a>
        <a href="/live/foundation.php">ফাউন্ডেশন</a>
        <a href="<?=aspi_shell_h($aspiShellAnchor('#courses'))?>">NSDA কোর্স</a>
        <a href="<?=aspi_shell_h($aspiShellAnchor('#gallery'))?>">ফটো গ্যালারি</a>
        <a href="/live/notices.php">বিজ্ঞপ্তি</a>
        <a href="/live/posts.php">খবর ও অনুষ্ঠান</a>
        <a href="<?=aspi_shell_h($aspiShellAnchor('#apply'))?>">আবেদন</a>
        <a href="<?=aspi_shell_h($aspiShellFacebook)?>" target="_blank" rel="noopener">Facebook</a>
        <a href="/live/admin/login.php">অ্যাডমিন লগইন</a>
      </div>
    </div>
    <div class="footer-bottom aspi-shell-footer-bottom"><span>© <?=date('Y')?> <?=aspi_shell_h($aspiShellName)?>. সর্বস্বত্ব সংরক্ষিত।</span><a href="https://saif.info.bd" target="_blank" rel="noopener">Made With Love: Saif Al-Islam</a></div>
  </div>
</footer>
<script>
(()=>{const toggle=document.getElementById('aspiNavToggle'),nav=document.getElementById('aspiNavLinks');if(!toggle||!nav)return;toggle.addEventListener('click',()=>{const open=nav.classList.toggle('is-open');toggle.setAttribute('aria-expanded',open?'true':'false');toggle.setAttribute('aria-label',open?'মেনু বন্ধ করুন':'মেনু খুলুন');toggle.innerHTML=open?'<i class="fa-solid fa-xmark" aria-hidden="true"></i>':'<i class="fa-solid fa-bars" aria-hidden="true"></i>';});document.querySelectorAll('.aspi-shell-dropbtn').forEach(btn=>btn.addEventListener('click',()=>{const parent=btn.closest('.aspi-shell-dropdown');const open=parent.classList.toggle('is-open');btn.setAttribute('aria-expanded',open?'true':'false');document.querySelectorAll('.aspi-shell-dropdown').forEach(other=>{if(other!==parent){other.classList.remove('is-open');other.querySelector('.aspi-shell-dropbtn')?.setAttribute('aria-expanded','false');}});}));nav.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>{nav.classList.remove('is-open');toggle.setAttribute('aria-expanded','false');}));document.addEventListener('click',e=>{if(!e.target.closest('.aspi-shell-nav'))document.querySelectorAll('.aspi-shell-dropdown').forEach(d=>{d.classList.remove('is-open');d.querySelector('.aspi-shell-dropbtn')?.setAttribute('aria-expanded','false');});});})();
</script>
