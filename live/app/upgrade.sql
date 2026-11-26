-- ASPI Corporate Dynamic Website upgrade. Run once in phpMyAdmin after selecting totaxqrf_aspi.
CREATE TABLE IF NOT EXISTS site_settings (
 setting_key VARCHAR(100) PRIMARY KEY,
 setting_value TEXT NOT NULL,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS courses (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(220) NOT NULL,
 level_label VARCHAR(100) NOT NULL DEFAULT '',
 category VARCHAR(100) NOT NULL DEFAULT 'NSDA Skill Course',
 summary TEXT NOT NULL,
 icon VARCHAR(80) NOT NULL DEFAULT 'fa-graduation-cap',
 image_path VARCHAR(255) NOT NULL DEFAULT 'assets/campus-5.jpg',
 duration_label VARCHAR(120) NOT NULL DEFAULT '',
 status ENUM('active','hidden') NOT NULL DEFAULT 'active',
 sort_order INT NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_courses_status_order(status,sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS applications (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 applicant_name VARCHAR(180) NOT NULL,
 phone VARCHAR(40) NOT NULL,
 course_id INT UNSIGNED NULL,
 education VARCHAR(180) NOT NULL DEFAULT '',
 message TEXT NOT NULL,
 status ENUM('new','contacted','enrolled','closed') NOT NULL DEFAULT 'new',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_applications_status_date(status,created_at),
 CONSTRAINT fk_application_course FOREIGN KEY(course_id) REFERENCES courses(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS activity_logs (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 admin_id INT UNSIGNED NULL,
 action VARCHAR(180) NOT NULL,
 details VARCHAR(500) NOT NULL DEFAULT '',
 ip_address VARCHAR(45) NOT NULL DEFAULT '',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_activity_date(created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT IGNORE INTO site_settings(setting_key,setting_value) VALUES
('site_name','আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট'),
('tagline','একটাই লক্ষ্য, হতে হবে দক্ষ।'),
('hero_kicker','NSDA SKILL DEVELOPMENT'),
('hero_title','দক্ষতা অর্জন করুন, গড়ুন আপনার ভবিষ্যৎ'),
('hero_text','চাহিদাভিত্তিক ব্যবহারিক প্রশিক্ষণের মাধ্যমে কর্মজীবনের জন্য প্রয়োজনীয় দক্ষতা অর্জন করুন।'),
('hero_image','assets/campus-1.webp'),
('phone','01847310310'),
('whatsapp','8801847310310'),
('email','info@aspi.edu.bd'),
('address','দক্ষিণ হাশিমপুর, দোহাজারী, চন্দনাইশ, চট্টগ্রাম'),
('facebook','https://www.facebook.com/ashabsirajpolytechnicinstitute'),
('notice_intro','আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউটের সর্বশেষ অফিসিয়াল বিজ্ঞপ্তি ও আপডেট।'),
('admission_status','ভর্তি চলছে'),
('footer_note','দক্ষতা অর্জনের দীপ্ত প্রতিজ্ঞায় গঠিত একটি আধুনিক কারিগরি শিক্ষা প্রতিষ্ঠান।');
INSERT INTO courses(title,level_label,category,summary,icon,image_path,duration_label,status,sort_order)
SELECT 'কম্পিউটার অপারেশন','লেভেল ২ ও ৩','NSDA Skill Course','কম্পিউটার ব্যবহার, অফিস অ্যাপ্লিকেশন ও ডিজিটাল কর্মদক্ষতা উন্নয়নের প্রশিক্ষণ।','fa-desktop','assets/campus-1.webp','ব্যাচ ও অনুমোদিত কোর্স কাঠামো অনুযায়ী','active',1
WHERE NOT EXISTS(SELECT 1 FROM courses);
INSERT INTO courses(title,level_label,category,summary,icon,image_path,duration_label,status,sort_order)
SELECT 'গ্রাফিক্স ডিজাইন','লেভেল ৩','NSDA Skill Course','ডিজাইন সফটওয়্যার, ভিজ্যুয়াল কমিউনিকেশন ও বাস্তবভিত্তিক ডিজাইন প্র্যাকটিস।','fa-pen-nib','assets/campus-2.webp','ব্যাচ ও অনুমোদিত কোর্স কাঠামো অনুযায়ী','active',2
WHERE (SELECT COUNT(*) FROM courses)=1;
INSERT INTO courses(title,level_label,category,summary,icon,image_path,duration_label,status,sort_order)
SELECT 'গ্রাফিক্স ডিজাইন ফর ফ্রিল্যান্সিং','লেভেল ৪','NSDA Skill Course','ডিজাইন পোর্টফোলিও, ক্লায়েন্ট ব্রিফ ও ফ্রিল্যান্সিং প্রস্তুতিমূলক দক্ষতা।','fa-laptop-code','assets/campus-3.webp','ব্যাচ ও অনুমোদিত কোর্স কাঠামো অনুযায়ী','active',3
WHERE (SELECT COUNT(*) FROM courses)=2;
INSERT INTO courses(title,level_label,category,summary,icon,image_path,duration_label,status,sort_order)
SELECT 'ইলেকট্রিক্যাল ইনস্টলেশন অ্যান্ড মেইনটেন্যান্স','লেভেল ২ ও ৩','NSDA Skill Course','নিরাপদ বৈদ্যুতিক ইনস্টলেশন, টুলস ব্যবহার ও ব্যবহারিক রক্ষণাবেক্ষণ প্রশিক্ষণ।','fa-bolt','assets/campus-4.webp','ব্যাচ ও অনুমোদিত কোর্স কাঠামো অনুযায়ী','active',4
WHERE (SELECT COUNT(*) FROM courses)=3;
INSERT INTO courses(title,level_label,category,summary,icon,image_path,duration_label,status,sort_order)
SELECT 'অটোক্যাড 2D ও 3D','লেভেল ৩','NSDA Skill Course','টেকনিক্যাল ড্রয়িং, 2D ড্রাফটিং ও 3D মডেলিংয়ের প্রাথমিক থেকে প্রয়োগভিত্তিক প্রশিক্ষণ।','fa-drafting-compass','assets/campus-5.jpg','ব্যাচ ও অনুমোদিত কোর্স কাঠামো অনুযায়ী','active',5
WHERE (SELECT COUNT(*) FROM courses)=4;
INSERT INTO courses(title,level_label,category,summary,icon,image_path,duration_label,status,sort_order)
SELECT 'কনজিউমার ইলেকট্রনিক্স','লেভেল ১','NSDA Skill Course','ইলেকট্রনিক্স যন্ত্রাংশ শনাক্তকরণ, মৌলিক পরীক্ষা ও নিরাপদ কাজের অনুশীলন।','fa-microchip','assets/campus-4.webp','ব্যাচ ও অনুমোদিত কোর্স কাঠামো অনুযায়ী','active',6
WHERE (SELECT COUNT(*) FROM courses)=5;


-- Diploma in Engineering program management (safe to run once)
CREATE TABLE IF NOT EXISTS diploma_programs (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 technology_name VARCHAR(220) NOT NULL,
 code VARCHAR(50) NOT NULL DEFAULT '',
 session_label VARCHAR(100) NOT NULL DEFAULT '',
 current_semester VARCHAR(100) NOT NULL DEFAULT '',
 description TEXT NOT NULL,
 status ENUM('active','hidden') NOT NULL DEFAULT 'active',
 sort_order INT NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_diploma_status_order(status,sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO diploma_programs(technology_name,code,session_label,current_semester,description,status,sort_order)
SELECT 'কম্পিউটার সায়েন্স অ্যান্ড টেকনোলজি','CST','','','কম্পিউটার ও তথ্যপ্রযুক্তিভিত্তিক ডিপ্লোমা শিক্ষাক্রম।','active',1
WHERE NOT EXISTS(SELECT 1 FROM diploma_programs);
INSERT INTO diploma_programs(technology_name,code,session_label,current_semester,description,status,sort_order)
SELECT 'ইলেকট্রিক্যাল টেকনোলজি','ET','','','বৈদ্যুতিক প্রযুক্তি ও ব্যবহারিক প্রকৌশলভিত্তিক ডিপ্লোমা শিক্ষাক্রম।','active',2
WHERE (SELECT COUNT(*) FROM diploma_programs)=1;

-- Dynamic homepage navigation and leadership messages; editable from admin website settings.
INSERT IGNORE INTO site_settings(setting_key,setting_value) VALUES
('nav_diploma_label','ডিপ্লোমা'),('nav_diploma_url','#diploma'),
('nav_about_label','প্রতিষ্ঠান পরিচিতি'),('nav_about_url','#about'),
('nav_foundation_label','ফাউন্ডেশন'),('nav_foundation_url','#foundation'),
('nav_courses_label','NSDA কোর্স'),('nav_courses_url','#courses'),
('nav_notices_label','বিজ্ঞপ্তি'),('nav_notices_url','/live/notices.php'),
('nav_contact_label','যোগাযোগ'),('nav_contact_url','#contact'),('nav_apply_label','কোর্স আবেদন'),
('message1_name',''),('message1_role',''),('message1_text',''),
('message2_name',''),('message2_role',''),('message2_text',''),
('message3_name',''),('message3_role',''),('message3_text','');


-- Responsive photo gallery for ASPI campus and activities.
CREATE TABLE IF NOT EXISTS gallery_images (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(220) NOT NULL DEFAULT '',
 image_path VARCHAR(255) NOT NULL,
 alt_text VARCHAR(220) NOT NULL DEFAULT '',
 status ENUM('published','hidden') NOT NULL DEFAULT 'published',
 sort_order INT NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_gallery_status_order(status,sort_order,id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add diploma image_path only when missing (safe to re-run on MySQL/MariaDB).
SET @aspi_has_diploma_image := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='diploma_programs' AND COLUMN_NAME='image_path');
SET @aspi_diploma_image_sql := IF(@aspi_has_diploma_image=0, 'ALTER TABLE diploma_programs ADD COLUMN image_path VARCHAR(255) NOT NULL DEFAULT \'assets/campus-5.jpg\' AFTER description', 'SELECT 1');
PREPARE aspi_diploma_image_stmt FROM @aspi_diploma_image_sql;
EXECUTE aspi_diploma_image_stmt;
DEALLOCATE PREPARE aspi_diploma_image_stmt;

INSERT IGNORE INTO site_settings(setting_key,setting_value) VALUES ('nav_gallery_label','ফটো গ্যালারি'),('nav_gallery_url','#gallery'),('message1_photo',''),('message2_photo',''),('message3_photo','');


-- Legacy homepage gallery assets: register existing images in the admin gallery once.
INSERT INTO gallery_images(title,image_path,alt_text,status,sort_order)
SELECT 'আমাদের ক্যাম্পাস','assets/campus-1.webp','আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট ক্যাম্পাস','published',10
WHERE NOT EXISTS(SELECT 1 FROM gallery_images WHERE image_path='assets/campus-1.webp');
INSERT INTO gallery_images(title,image_path,alt_text,status,sort_order)
SELECT 'প্রশিক্ষণ কার্যক্রম','assets/campus-2.webp','প্রশিক্ষণ কার্যক্রম','published',20
WHERE NOT EXISTS(SELECT 1 FROM gallery_images WHERE image_path='assets/campus-2.webp');
INSERT INTO gallery_images(title,image_path,alt_text,status,sort_order)
SELECT 'ল্যাব ও ব্যবহারিক শিক্ষা','assets/campus-3.webp','ল্যাব ও ব্যবহারিক শিক্ষা','published',30
WHERE NOT EXISTS(SELECT 1 FROM gallery_images WHERE image_path='assets/campus-3.webp');
INSERT INTO gallery_images(title,image_path,alt_text,status,sort_order)
SELECT 'প্রতিষ্ঠানের পরিবেশ','assets/campus-5.jpg','প্রতিষ্ঠানের পরিবেশ','published',40
WHERE NOT EXISTS(SELECT 1 FROM gallery_images WHERE image_path='assets/campus-5.jpg');

-- Block-based news, events, workshops and media posts (Editor.js content JSON).
CREATE TABLE IF NOT EXISTS content_posts (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(240) NOT NULL,
 category ENUM('news','event','workshop','notice') NOT NULL DEFAULT 'news',
 excerpt VARCHAR(600) NOT NULL DEFAULT '',
 cover_image VARCHAR(255) NOT NULL DEFAULT '',
 content_json LONGTEXT NOT NULL,
 event_date DATETIME NULL,
 status ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
 is_featured TINYINT(1) NOT NULL DEFAULT 0,
 published_at DATETIME NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_posts_public(status,published_at,id),
 INDEX idx_posts_category(category,status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO site_settings(setting_key,setting_value) VALUES ('nav_news_label','খবর ও ইভেন্ট'),('nav_news_url','/live/posts.php');
