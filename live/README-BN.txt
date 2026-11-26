ASPI Corporate Website Update — Diploma Cards, Gallery & Photo Uploads

Included updates
- Redesigned Diploma in Engineering cards with large course photos, code badges, session and current-semester details.
- Admin photo upload for every Diploma technology and NSDA short course (JPG, PNG, WEBP; max 5 MB).
- Responsive campus/activity photo gallery on the homepage.
- Admin gallery manager at /live/admin/gallery.php (upload, edit title/alt text, order, publish/hide, delete).
- Profile photo upload for the three homepage leadership/message cards in Website Settings.
- Dynamic homepage navigation includes Photo Gallery and can be edited from Website Settings.
- Responsive styles for desktop, tablet and mobile.

Install safely
1. Back up the current public_html files and the MySQL database.
2. Extract this ZIP and upload/merge its contents into public_html. Do not delete existing uploads/notices PDFs or live/app/config.local.php.
3. In phpMyAdmin, select the existing ASPI database (totaxqrf_aspi) and import live/app/upgrade.sql. It is designed to be re-runnable for the gallery table and diploma image_path column.
4. Confirm live/uploads/site/ is writable by PHP. It contains an .htaccess file that blocks PHP execution in uploaded-image storage.
5. Open /live/admin/diploma.php and upload each technology photo. Open /live/admin/courses.php and upload each NSDA course photo.
6. Open /live/admin/gallery.php to upload campus/lab/activity photos.
7. Open /live/admin/settings.php to upload message profile photos and set each person's name, role and approved message; set navigation labels/URLs there too.
8. Hard refresh the site with Ctrl+F5 and test on mobile.

Notes
- The actual names, roles, messages, academic sessions and semester data must be entered/verified by ASPI staff.
- The ZIP does not include or replace the live database password. Keep live/app/config.local.php unchanged.
- PHP syntax was checked locally. Live hosting/database behavior was not tested in the user's cPanel environment; keep a backup and test after upload.
