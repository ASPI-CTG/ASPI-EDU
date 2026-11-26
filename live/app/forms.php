<?php
function aspi_forms_ensure(PDO $pdo): void {
 $pdo->exec("CREATE TABLE IF NOT EXISTS downloadable_forms (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(220) NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  category VARCHAR(100) NOT NULL DEFAULT 'সাধারণ ফরম',
  file_path VARCHAR(255) NOT NULL,
  original_name VARCHAR(255) NOT NULL,
  mime_type VARCHAR(120) NOT NULL,
  file_size BIGINT UNSIGNED NOT NULL DEFAULT 0,
  status ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  show_home TINYINT(1) NOT NULL DEFAULT 0,
  start_date DATE NULL,
  end_date DATE NULL,
  download_count BIGINT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_forms_status(status), INDEX idx_forms_category(category)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
 $sample=dirname(__DIR__).'/uploads/forms/stipend-application.pdf';
 if(is_file($sample)) {
  $q=$pdo->prepare("INSERT IGNORE INTO downloadable_forms(title,slug,description,category,file_path,original_name,mime_type,file_size,status,show_home) VALUES(?,?,?,?,?,?,?,?, 'published',1)");
  $q->execute(['উপবৃত্তির আবেদন ফরম','stipend-application','কারিগরি শিক্ষা অধিদপ্তরের স্টাইপেন্ড MIS সফটওয়্যারে উপবৃত্তির জন্য শিক্ষার্থী আবেদন ফরম (SAF)। ফরমটি ডাউনলোড করে প্রয়োজনীয় তথ্য পূরণ করুন এবং প্রতিষ্ঠানের নির্দেশনা অনুযায়ী জমা দিন।','উপবৃত্তি ও আর্থিক সহায়তা','uploads/forms/stipend-application.pdf','উপবৃত্তির আবেদন ফরম.pdf','application/pdf',(int)filesize($sample)]);
 }
}
function aspi_form_slug(string $value): string {
 $value=trim(mb_strtolower($value,'UTF-8'));
 $ascii=@iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$value); if($ascii!==false)$value=$ascii;
 $value=preg_replace('/[^a-z0-9]+/','-',$value)??''; $value=trim($value,'-');
 return $value!==''?substr($value,0,160):'form-'.date('YmdHis');
}
function aspi_form_upload(array $file, ?string $oldPath=null): array {
 if(($file['error']??UPLOAD_ERR_NO_FILE)===UPLOAD_ERR_NO_FILE) return [$oldPath??'', '', '', 0];
 if(($file['error']??UPLOAD_ERR_NO_FILE)!==UPLOAD_ERR_OK) throw new RuntimeException('ফাইল আপলোড করা যায়নি।');
 if(($file['size']??0)>25*1024*1024) throw new RuntimeException('সর্বোচ্চ ফাইল সাইজ ২৫ MB।');
 $mime=(new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
 $allowed=['application/pdf'=>'pdf','application/msword'=>'doc','application/vnd.openxmlformats-officedocument.wordprocessingml.document'=>'docx'];
 $ext=strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
 if(!isset($allowed[$mime]) || $allowed[$mime]!==$ext) throw new RuntimeException('শুধু বৈধ PDF, DOC অথবা DOCX ফাইল আপলোড করুন।');
 $dir=dirname(__DIR__).'/uploads/forms'; if(!is_dir($dir)&&!mkdir($dir,0755,true)) throw new RuntimeException('ফরম আপলোড ফোল্ডার তৈরি করা যায়নি।');
 $name='form-'.date('YmdHis').'-'.bin2hex(random_bytes(5)).'.'.$ext;
 if(!move_uploaded_file($file['tmp_name'],$dir.'/'.$name)) throw new RuntimeException('ফাইল সংরক্ষণ করা যায়নি।');
 return ['uploads/forms/'.$name,basename($file['name']),$mime,(int)$file['size']];
}
function aspi_form_remove(?string $path): void { if(!$path||!str_starts_with($path,'uploads/forms/'))return; $full=dirname(__DIR__).'/'.$path; if(is_file($full))@unlink($full); }
