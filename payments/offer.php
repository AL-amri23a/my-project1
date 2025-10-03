<?php
session_start();
if(!isset($_SESSION['email'])){
    header("Location: ../public/index.php");
    exit();
}

if (!isset($_SESSION['lang'])) $_SESSION['lang'] = 'ar';
$lang = $_SESSION['lang'];
$dir = $lang === 'ar' ? 'rtl' : 'ltr';

$offers = [
    1 => [
        'ar' => [
            'title'=>'💪 حوّل لياقتك: خصم 20% على العضوية الشهرية!',
            'desc'=>'- مدة العضوية: 1 شهر<br>
- الأنشطة المشمولة: جميع الصفوف الجماعية (يوغا، كارديو، قوة)<br>
- جلسة شخصية مجانية: 1<br>
- خدمات إضافية: استشارة تغذية مجانية + متابعة تقدم أسبوعية<br>
- ملاحظات: العرض ساري لشهر التسجيل فقط، لا يمكن دمج الخصم مع عروض أخرى',
            'icon'=>'fa-dumbbell',
            'bg'=>'linear-gradient(135deg, #f5f7fa, #c3cfe2)',
            'badge'=>'جديد'
        ],
        'en' => [
            'title'=>'💪 Transform Your Fitness: 20% Off This Month!',
            'desc'=>'- Membership duration: 1 month<br>
- Activities included: All group classes (Yoga, Cardio, Strength)<br>
- Free personal session: 1<br>
- Additional services: Free nutrition consultation + weekly progress tracking<br>
- Notes: Offer valid only for the month of registration, cannot combine with other discounts',
            'icon'=>'fa-dumbbell',
            'bg'=>'linear-gradient(135deg, #f5f7fa, #c3cfe2)',
            'badge'=>'New'
        ]
    ],
    2 => [
        'ar' => [
            'title'=>'⚡ باقات اللياقة المميزة: اشترك لمدة 3 أشهر!',
            'desc'=>'- مدة الاشتراك: 3 أشهر<br>
- الأنشطة المشمولة: جميع الصفوف الجماعية وورشة أسبوعية للتغذية<br>
- جلسات شخصية مجانية: 2<br>
- مميزات إضافية: تقييم لياقة أولي + خطة تدريب مخصصة<br>
- ملاحظات: المقاعد محدودة، ينصح بالحجز المبكر',
            'icon'=>'fa-bolt',
            'bg'=>'linear-gradient(135deg, #fff7e6, #ffd580)',
            'badge'=>'لفترة محدودة'
        ],
        'en' => [
            'title'=>'⚡ Boost Your Energy: 3-Month Premium Package!',
            'desc'=>'- Subscription duration: 3 months<br>
- Activities included: All group classes + weekly nutrition workshop<br>
- Free personal sessions: 2<br>
- Additional features: Initial fitness evaluation + personalized training plan<br>
- Notes: Limited seats, early booking recommended',
            'icon'=>'fa-bolt',
            'bg'=>'linear-gradient(135deg, #fff7e6, #ffd580)',
            'badge'=>'Limited Time'
        ]
    ],
    3 => [
        'ar' => [
            'title'=>'❤️ العناية بالصحة: استشارة تغذية حصرية',
            'desc'=>'- مدة العضوية: 12 شهر<br>
- جلسات استشارة: استشارة تغذية مخصصة أسبوعية لمدة شهرين<br>
- خدمات إضافية: خطة وجبات صحية، متابعة إلكترونية لتقدمك<br>
- مميزات: الوصول لجميع الصفوف + متابعة شخص متخصص<br>
- ملاحظات: يجب حجز جلسات الاستشارة مسبقًا',
            'icon'=>'fa-heartbeat',
            'bg'=>'linear-gradient(135deg, #ffe6e6, #ff8080)',
            'badge'=>'حصري'
        ],
        'en' => [
            'title'=>'❤️ Health First: Exclusive Nutrition Consultation',
            'desc'=>'- Membership duration: 12 months<br>
- Consultation sessions: Weekly personalized nutrition consultation for 2 months<br>
- Additional services: Healthy meal plan, online progress tracking<br>
- Features: Access to all classes + professional guidance<br>
- Notes: Consultation sessions must be booked in advance',
            'icon'=>'fa-heartbeat',
            'bg'=>'linear-gradient(135deg, #ffe6e6, #ff8080)',
            'badge'=>'Exclusive'
        ]
    ]
];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if(!isset($offers[$id])){
    header("Location: offers.php");
    exit();
}

$offer = $offers[$id][$lang];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $offer['title'] ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    body { background: #f8f9fa; direction: <?= $dir ?>; padding-top: 40px; }
    .offer-detail-card {
        max-width: 700px;
        margin: auto;
        background: <?= $offer['bg'] ?>;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        text-align: center;
        color: #333;
    }
    .offer-detail-card i { font-size: 4rem; margin-bottom: 20px; color: #333; }
    .offer-detail-card h2 { font-weight: bold; font-size: 1.7rem; margin-bottom: 20px; }
    .offer-detail-card p { font-size: 1rem; line-height: 1.6; text-align: <?= $dir==='rtl'?'right':'left' ?>; margin-bottom: 25px; }
    .badge-custom { font-size: 1rem; padding: 0.5em 0.9em; }
    .btn-back { margin-bottom: 25px; }
    .btn-subscribe { font-size: 1.1rem; padding: 10px 25px; }
    .user-name { font-weight: bold; margin-bottom: 15px; font-size: 1.1rem; text-align: center; }
  </style>
</head>
<body>

<div class="container">
  <div class="user-name">
    <?= $lang==='ar'?'مرحبا':'Hello'; ?> <?= $_SESSION['firstName']; ?>!
  </div>

  <a href="offers.php" class="btn btn-outline-secondary btn-back">
    <?= $lang === 'ar' ? '⬅️ الرجوع إلى العروض' : '⬅️ Back to Offers'; ?>
  </a>

  <div class="offer-detail-card">
    <i class="fas <?= $offer['icon'] ?>"></i>
    <h2><?= $offer['title'] ?></h2>
    <p><?= $offer['desc'] ?></p>
    <span class="badge bg-dark badge-custom mb-3"><?= $offer['badge'] ?></span>
    <br>
    <a href="subscribe.php?id=<?= $id ?>" class="btn btn-success btn-subscribe">
        <?= $lang==='ar'?'اشترك الآن':'Subscribe Now'; ?>
    </a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
