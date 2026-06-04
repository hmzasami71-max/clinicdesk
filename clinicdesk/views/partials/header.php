<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . " - " . APP_NAME : APP_NAME; ?></title>
    
    <!-- 🟢 استدعاء Bootstrap 5.3 المخصص للغة العربية RTL لضمان التناسق التام -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        .navbar-custom { background-color: #0d6efd; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link { color: white !important; font-weight: bold; }
        .main-card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<!-- شريط الملاحة المتوافق مع Bootstrap 5 -->
<nav class="navbar navbar-expand-lg navbar-custom mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php?page=dashboard">🏥 <?php echo APP_NAME; ?></a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0"> <!-- تم تعديل mr-auto إلى me-auto لتتوافق مع إصدار 5 -->
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=dashboard">لوحة التحكم</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=appointments&action=index">إدارة المواعيد</a>
        </li>
      </ul>
      <div class="d-flex">
        <a href="index.php?page=auth&action=logout" class="btn btn-danger btn-sm text-white font-weight-bold">تسجيل الخروج</a>
      </div>
    </div>
  </div>
</nav>

<div class="container">
