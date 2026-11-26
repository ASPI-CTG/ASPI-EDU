<?php
// The public homepage now lives at the domain root. Keep /live/ focused on notices/admin.
header('Location: /live/notices.php', true, 302);
exit;
