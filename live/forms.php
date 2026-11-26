
<?php

require_once __DIR__ . '/app/bootstrap.php';
require_once __DIR__ . '/app/forms.php';

aspi_forms_ensure($pdo);

// প্রকাশিত এবং নির্ধারিত তারিখের মধ্যে থাকা ফরম সংগ্রহ
$sql = "
    SELECT
        id,
        title,
        slug,
        description,
        category,
        original_name,
        file_size,
        download_count,
        created_at
    FROM downloadable_forms
    WHERE status = 'published'
      AND (start_date IS NULL OR start_date <= CURDATE())
      AND (end_date IS NULL OR end_date >= CURDATE())
    ORDER BY show_home DESC, category, title
";

$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// ক্যাটাগরি অনুযায়ী ফরম সাজানো
$cats = [];

foreach ($rows as $row) {
    $cats[$row['category']][] = $row;
}

// HTML Output Escape Function
function form_h($value)
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

?>

<!doctype html>
<html lang="bn">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ফরম ও ডাউনলোড | ASPI</title>

    <meta
        name="description"
        content="আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউটের প্রয়োজনীয় ফরম ও অফিসিয়াল ডকুমেন্ট ডাউনলোড করুন।"
    >

    <link rel="icon" href="../assets/aspi-logo.svg">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <link rel="stylesheet" href="assets/public-shell.css">

    <style>

        @font-face {
            font-family: Hind;
            src: url('../fonts/Hind-Siliguri-Regular.ttf');
        }

        @font-face {
            font-family: Hind;
            src: url('../fonts/Hind-Siliguri-Bold.ttf');
            font-weight: 700;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100%;
            min-height: 100%;
            font-family: Hind, sans-serif !important;
            background: #f5f7fb;
            color: #172033;
        }

        body,
        a,
        button,
        input,
        select,
        textarea {
            font-family: Hind, sans-serif !important;
        }

        a,
        a:visited,
        a:hover,
        a:focus {
            text-decoration: none !important;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .forms-hero {
            padding: clamp(38px, 5vw, 60px) 0;
            background: linear-gradient(
                115deg,
                #10224b,
                #1c3974 65%,
                #087f8c
            );
            color: white;
            width: 100%;
        }

        .wrap {
            width: min(1240px, calc(100% - 48px));
            margin-inline: auto;
        }

        .forms-hero h1 {
            font-size: clamp(30px, 4vw, 44px);
            margin: 0 0 8px;
        }

        .forms-hero p {
            margin: 0;
            color: #dbe8fa;
        }

        .forms-main {
            padding: 40px 0 70px;
        }

        .forms-filter {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .forms-filter button {
            border: 1px solid #dce5ef;
            background: #fff;
            color: #30415f;
            padding: 9px 14px;
            border-radius: 999px;
            cursor: pointer;
        }

        .forms-filter button.active {
            background: #087f8c;
            color: #fff;
            border-color: #087f8c;
        }

        /* Compact Forms Grid */
        .forms-grid {
            display: grid;
            grid-template-columns: repeat(
                auto-fill,
                minmax(min(100%, 320px), 380px)
            );
            justify-content: start;
            gap: 18px;
            align-items: stretch;
        }

        /* Compact Form Card */
        .form-card {
            background: #fff;
            width: 100%;
            min-width: 0;
            border: 1px solid #e1e8f1;
            border-radius: 15px;
            padding: 18px;
            box-shadow: 0 6px 22px #142b4d08;
            display: flex;
            flex-direction: column;
        }

        /* Ensure filtered cards are actually hidden */
        .form-card[hidden] {
            display: none !important;
        }

        .form-icon {
            width: 48px;
            height: 48px;
            border-radius: 13px;
            background: #fff0f0;
            color: #d92525;
            display: grid;
            place-items: center;
            font-size: 22px;
        }

        .form-card h2 {
            font-size: 18px;
            color: #10224b;
            line-height: 1.4;
            margin: 12px 0 6px;
        }

        .form-card p {
            color: #69768b;
            font-size: 13px;
            line-height: 1.7;
            margin: 0 0 12px;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .form-meta {
            font-size: 11px;
            color: #8793a6;
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin: 8px 0 14px;
        }

        .form-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .form-btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            padding: 10px 13px;
            border-radius: 9px;
            background: #087f8c;
            color: #fff;
            font-weight: 700;
            font-size: 13px;
        }

        .form-btn.secondary {
            background: #e7f5f5;
            color: #087f8c;
        }

        .empty {
            padding: 35px;
            background: #fff;
            border: 1px dashed #dce5ef;
            border-radius: 15px;
            text-align: center;
            color: #69768b;
        }

        @media (max-width: 700px) {

            .wrap {
                width: calc(100% - 32px);
            }

            .forms-main {
                padding: 28px 0 48px;
            }

            .forms-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .form-card {
                padding: 18px;
            }

            .form-actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }

            .form-btn {
                min-width: 0;
                padding: 10px 8px;
            }

        }

        @media (max-width: 380px) {

            .wrap {
                width: calc(100% - 24px);
            }

            .form-actions {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

    <?php require __DIR__ . '/app/public-header.php'; ?>

    <!-- Hero Section -->
    <section class="forms-hero">

        <div class="wrap">

            <div style="
                font-size: 12px;
                letter-spacing: 1px;
                color: #a9e7e5;
                font-weight: 700;
            ">
                ASPI DOCUMENT CENTER
            </div>

            <h1>ফরম ও ডাউনলোড সেন্টার</h1>

            <p>
                প্রতিষ্ঠানের প্রয়োজনীয় আবেদন ফরম ও অফিসিয়াল ডকুমেন্ট
                এক জায়গা থেকে সংগ্রহ করুন।
            </p>

        </div>

    </section>

    <!-- Main Content -->
    <main class="forms-main">

        <div class="wrap">

            <!-- Category Filter -->
            <div class="forms-filter">

                <button class="active" data-filter="all">
                    সব ফরম
                </button>

                <?php foreach (array_keys($cats) as $category): ?>

                    <button data-filter="<?= form_h($category) ?>">
                        <?= form_h($category) ?>
                    </button>

                <?php endforeach; ?>

            </div>

            <!-- Forms Grid -->
            <div class="forms-grid">

                <?php foreach ($rows as $row): ?>

                    <article
                        class="form-card"
                        data-category="<?= form_h($row['category']) ?>"
                    >

                        <span class="form-icon">
                            <i class="fa-solid fa-file-pdf"></i>
                        </span>

                        <div style="
                            font-size: 11px;
                            color: #087f8c;
                            font-weight: 700;
                            margin-top: 12px;
                        ">
                            <?= form_h($row['category']) ?>
                        </div>

                        <h2>
                            <?= form_h($row['title']) ?>
                        </h2>

                        <p>
                            <?= nl2br(form_h($row['description'])) ?>
                        </p>

                        <div class="form-meta">

                            <span>
                                <?= strtoupper(
                                    pathinfo(
                                        $row['original_name'],
                                        PATHINFO_EXTENSION
                                    )
                                ) ?>

                                ·

                                <?= number_format(
                                    (int) $row['file_size'] / 1024,
                                    0
                                ) ?> KB
                            </span>

                            <span>
                                <?= number_format(
                                    (int) $row['download_count']
                                ) ?>
                                ডাউনলোড
                            </span>

                        </div>

                        <div class="form-actions">

                            <a
                                class="form-btn"
                                href="form.php?slug=<?= rawurlencode($row['slug']) ?>"
                            >
                                <i class="fa-solid fa-eye"></i>
                                বিস্তারিত
                            </a>

                            <a
                                class="form-btn secondary"
                                href="form-download.php?slug=<?= rawurlencode($row['slug']) ?>"
                            >
                                <i class="fa-solid fa-download"></i>
                                ডাউনলোড
                            </a>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

            <!-- Empty State -->
            <?php if (empty($rows)): ?>

                <div class="empty">
                    বর্তমানে কোনো ফরম প্রকাশিত নেই।
                    নতুন ফরম প্রকাশিত হলে এখানে দেখা যাবে।
                </div>

            <?php endif; ?>

        </div>

    </main>

    <?php require __DIR__ . '/app/public-footer.php'; ?>

    <!-- Mobile Navigation and Category Filter -->
    <script>
        // Mobile Navigation Toggle
        const navToggle = document.getElementById('navToggle');
        const navLinks = document.getElementById('navLinks');

        if (navToggle && navLinks) {
            navToggle.addEventListener('click', () => {
                const isOpen = navLinks.classList.toggle('open');

                navToggle.setAttribute('aria-expanded', String(isOpen));

                navToggle.innerHTML = isOpen
                    ? '<i class="fa-solid fa-xmark"></i>'
                    : '<i class="fa-solid fa-bars"></i>';
            });
        }

        // Category Filter
        const filterButtons = document.querySelectorAll('.forms-filter [data-filter]');
        const formCards = document.querySelectorAll('.form-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                const selectedCategory = button.dataset.filter;

                filterButtons.forEach(item => {
                    item.classList.remove('active');
                });

                button.classList.add('active');

                formCards.forEach(card => {
                    card.hidden =
                        selectedCategory !== 'all' &&
                        card.dataset.category !== selectedCategory;
                });
            });
        });
    </script>

</body>
</html>
