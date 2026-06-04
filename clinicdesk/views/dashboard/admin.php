<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المدير - ClinicDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; }
        .navbar-custom { background-color: #212529; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link { color: white !important; font-weight: bold; }
        .stat-card { border: none; border-radius: 10px; transition: transform 0.2s; color: white; }
        .stat-card:hover { transform: translateY(-5px); }
        .card-icon { font-size: 2.5rem; opacity: 0.3; position: absolute; left: 15px; bottom: 10px; }
    </style>
</head>
<body>

<!-- شريط التنقل العلوي للمدير -->
<nav class="navbar navbar-expand-lg navbar-custom mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php?page=dashboard">🏥 <?php echo APP_NAME; ?> - إدارة النظام</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" href="index.php?page=dashboard">الرئيسية</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=appointments&action=index">سجل المواعيد</a>
        </li>
      </ul>
      <div class="d-flex">
        <a href="index.php?page=auth&action=logout" class="btn btn-danger btn-sm font-weight-bold">تسجيل الخروج</a>
      </div>
    </div>
  </div>
</nav>

<div class="container">
    <!-- هيدر الترحيب -->
    <div class="p-4 mb-4 bg-white rounded-3 shadow-sm border">
        <h1 class="display-6 text-dark font-weight-bold">مرحباً بك، <?php echo htmlspecialchars($user['name']); ?> 👋</h1>
        <p class="col-md-8 fs-6 text-muted mb-0">نظام إدارة العيادة الطبية متصل وقيد التشغيل بأمان. يمكنك متابعة حالة العيادة الفورية عبر المؤشرات أدناه.</p>
    </div>

    <!-- شبكة بطاقات الإحصائيات الذكية -->
    <div class="row g-4 mb-4">
        <!-- 1. بطاقة إجمالي المواعيد (زرقاء) -->
        <div class="col-md-3">
            <div class="card stat-card bg-primary p-3 shadow-sm position-relative">
                <div class="card-body">
                    <h6 class="card-title text-uppercase opacity-75">إجمالي المواعيد</h6>
                    <h2 class="display-5 font-weight-bold mb-0"><?php echo $stats['total_appointments']; ?></h2>
                    <div class="card-icon">🗓️</div>
                </div>
            </div>
        </div>

        <!-- 2. بطاقة المواعيد قيد الانتظار (صفراء) -->
        <div class="col-md-3">
            <div class="card stat-card bg-warning text-dark p-3 shadow-sm position-relative">
                <div class="card-body">
                    <h6 class="card-title text-uppercase opacity-75">مواعيد قيد الانتظار</h6>
                    <h2 class="display-5 font-weight-bold mb-0"><?php echo $stats['pending_appts']; ?></h2>
                    <div class="card-icon">⏳</div>
                </div>
            </div>
        </div>

        <!-- 3. بطاقة عدد الأطباء (خضراء) -->
        <div class="col-md-3">
            <div class="card stat-card bg-success p-3 shadow-sm position-relative">
                <div class="card-body">
                    <h6 class="card-title text-uppercase opacity-75">الأطباء المسجلين</h6>
                    <h2 class="display-5 font-weight-bold mb-0"><?php echo $stats['total_doctors']; ?></h2>
                    <div class="card-icon">🩺</div>
                </div>
            </div>
        </div>

        <!-- 4. بطاقة عدد المرضى (حمراء/وردية) -->
        <div class="col-md-3">
            <div class="card stat-card bg-danger p-3 shadow-sm position-relative">
                <div class="card-body">
                    <h6 class="card-title text-uppercase opacity-75">المرضى المسجلين</h6>
                    <h2 class="display-5 font-weight-bold mb-0"><?php echo $stats['total_patients']; ?></h2>
                    <div class="card-icon">👤</div>
                </div>
            </div>
        </div>
    </div>

    <!-- روابط سريعة للتحكم -->
    <div class="card border rounded-3 p-4 bg-white shadow-sm">
        <h4 class="text-secondary border-bottom pb-2 mb-3">⚡ إجراءات سريعة ومباشرة</h4>
        <div class="d-flex gap-2">
            <a href="index.php?page=appointments&action=index" class="btn btn-outline-dark font-weight-bold py-2 px-4">📋 عرض سجل المواعيد والإجراءات</a>
            <a href="index.php?page=appointments&action=book" class="btn btn-primary font-weight-bold py-2 px-4">+ حجز موعد جديد لمريض</a>
        </div>
    </div>
</div>

<footer class="text-center py-4 text-muted mt-5">
    <p class="small">&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?> - نظام الإدارة المتكامل.</p>
</footer>

<script src="https://jsdelivr.net"></script>
</body>
</html>
