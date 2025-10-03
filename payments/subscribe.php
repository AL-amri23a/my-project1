<?php
session_start();
include("../config/connect.php"); // الاتصال بقاعدة البيانات

// التحقق من تسجيل الدخول
if(!isset($_SESSION['email'])){
    header("Location: ../public/index.php");
    exit();
}

// اللغة واتجاه النص
$lang = $_SESSION['lang'] ?? 'ar';
$dir = $lang === 'ar' ? 'rtl' : 'ltr';

// ===== مصفوفة العروض =====
$offers = [
    1 => [
        'ar' => [
            'title'=>'💪 حوّل لياقتك: خصم 20% على العضوية الشهرية!',
            'desc'=>'- مدة العضوية: 1 شهر<br>- الأنشطة المشمولة: جميع الصفوف الجماعية (يوغا، كارديو، قوة)<br>- جلسة شخصية مجانية: 1<br>- خدمات إضافية: استشارة تغذية مجانية + متابعة تقدم أسبوعية<br>- ملاحظات: العرض ساري لشهر التسجيل فقط، لا يمكن دمج الخصم مع عروض أخرى',
            'price'=>'10 ر.ع / شهر'
        ],
        'en' => [
            'title'=>'💪 Transform Your Fitness: 20% Off This Month!',
            'desc'=>'- Membership duration: 1 month<br>- Activities included: All group classes (Yoga, Cardio, Strength)<br>- Free personal session: 1<br>- Additional services: Free nutrition consultation + weekly progress tracking<br>- Notes: Offer valid only for the month of registration, cannot combine with other discounts',
            'price'=>'$25 / month'
        ]
    ],
    2 => [
        'ar' => [
            'title'=>'⚡ باقات اللياقة المميزة: اشترك لمدة 3 أشهر!',
            'desc'=>'- مدة الاشتراك: 3 أشهر<br>- الأنشطة المشمولة: جميع الصفوف الجماعية وورشة أسبوعية للتغذية<br>- جلسات شخصية مجانية: 2<br>- مميزات إضافية: تقييم لياقة أولي + خطة تدريب مخصصة<br>- ملاحظات: المقاعد محدودة، ينصح بالحجز المبكر',
            'price'=>'27 ر.ع / 3 أشهر'
        ],
        'en' => [
            'title'=>'⚡ Boost Your Energy: 3-Month Premium Package!',
            'desc'=>'- Subscription duration: 3 months<br>- Activities included: All group classes + weekly nutrition workshop<br>- Free personal sessions: 2<br>- Additional features: Initial fitness evaluation + personalized training plan<br>- Notes: Limited seats, early booking recommended',
            'price'=>'$65 / 3 months'
        ]
    ],
    3 => [
        'ar' => [
            'title'=>'❤️ العناية بالصحة: استشارة تغذية حصرية',
            'desc'=>'- مدة العضوية: 12 شهر<br>- جلسات استشارة: استشارة تغذية مخصصة أسبوعية لمدة شهرين<br>- خدمات إضافية: خطة وجبات صحية، متابعة إلكترونية لتقدمك<br>- مميزات: الوصول لجميع الصفوف + متابعة شخص متخصص<br>- ملاحظات: يجب حجز جلسات الاستشارة مسبقًا',
            'price'=>'100 ر.ع / سنة'
        ],
        'en' => [
            'title'=>'❤️ Health First: Exclusive Nutrition Consultation',
            'desc'=>'- Membership duration: 12 months<br>- Consultation sessions: Weekly personalized nutrition consultation for 2 months<br>- Additional services: Healthy meal plan, online progress tracking<br>- Features: Access to all classes + professional guidance<br>- Notes: Consultation sessions must be booked in advance',
            'price'=>'$250 / year'
        ]
    ]
];

// ===== التحقق من id صالح =====
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if(!isset($offers[$id])){
    header("Location: offers.php");
    exit();
}
$offer = $offers[$id][$lang];

// ===== معالجة الاشتراك =====
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subscribe'])) {
    // جلب id المستخدم
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $_SESSION['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $user_id = $user['id'];

    // إدخال الاشتراك في قاعدة login.subscriptions
    $stmt = $conn->prepare("INSERT INTO subscriptions (user_id, offer_id, offer_title, price) VALUES (?,?,?,?)");
    $stmt->bind_param("iiss", $user_id, $id, $offer['title'], $offer['price']);
    if($stmt->execute()){
        $success = $lang==='ar' ? 'تم الاشتراك بنجاح! 🎉' : 'Subscription successful! 🎉';
    } else {
        $success = $lang==='ar' ? 'حدث خطأ أثناء الاشتراك.' : 'Error during subscription.';
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $offer['title'] ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; direction: <?= $dir ?>; padding-top: 40px; }
    .subscribe-card { max-width: 700px; margin: auto; background: #fff; padding: 35px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); text-align: center; color: #333; }
    .subscribe-card h2 { font-size: 1.7rem; margin-bottom: 20px; }
    .subscribe-card p { font-size: 1rem; text-align: <?= $dir==='rtl'?'right':'left' ?>; line-height: 1.6; margin-bottom: 20px; }
    .btn-back, .btn-confirm { margin-bottom: 20px; }
    .user-name { font-weight: bold; margin-bottom: 15px; font-size: 1.1rem; text-align: center; }
    .price-tag { font-weight: bold; font-size: 1.2rem; margin-bottom: 20px; }
    .success-msg { font-weight: bold; color: green; margin-bottom: 15px; font-size: 1.1rem; }
  </style>
</head>
<body>
<div class="container">
  <div class="user-name">
    <?= $lang==='ar'?'مرحبا':'Hello'; ?> <?= $_SESSION['firstName']; ?>!
  </div>

  <a href="offer.php?id=<?= $id ?>" class="btn btn-outline-secondary btn-back">
    <?= $lang==='ar'?'⬅️ الرجوع إلى تفاصيل العرض':'⬅️ Back to Offer'; ?>
  </a>

  <div class="subscribe-card">
    <h2><?= $offer['title'] ?></h2>
    <p><?= $offer['desc'] ?></p>
    <div class="price-tag"><?= $lang==='ar'?'السعر:':'Price:' ?> <?= $offer['price'] ?></div>

    <?php if($success): ?>
      <div class="success-msg"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
      <button type="submit" name="subscribe" class="btn btn-success btn-lg">
        <?= $lang==='ar'?'تأكيد الاشتراك':'Confirm Subscription'; ?>
      </button>
    </form>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
