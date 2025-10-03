<?php if($_SESSION['role'] == 'admin'){ ?><nav class="navbar navbar-expand-lg bg-body-tertiary position-relative">  <div class="container"><a class="navbar-brand" href="http://localhost/project/index.php" style="position:absolute; left:50%; transform:translateX(-50%);">Project</a>



<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">

  <span class="navbar-toggler-icon"></span>

</button>



<div class="collapse navbar-collapse" id="navbarAdmin">

  <ul class="navbar-nav me-auto mb-2 mb-lg-0">

    <li class="nav-item"><a class="nav-link active" href="/project/admin/dashboard.php">Home</a></li>

    <li class="nav-item"><a class="nav-link" href="/project/admin/users/index.php">Users</a></li>

    <li class="nav-item"><a class="nav-link" href="/project/admin/departments/index.php">Departments</a></li>

  </ul>



  <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

    <li class="nav-item dropdown">

      <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">

        Settings

      </a>

      <ul class="dropdown-menu dropdown-menu-end">

        <li><a class="dropdown-item" href="#"><?= $_SESSION['name'] ?></a></li>

        <li><a class="dropdown-item" href="#"><?= $_SESSION['username'] ?></a></li>

        <li><hr class="dropdown-divider"></li>

        <li><a class="dropdown-item" href=" ../auth/logout.php">Logout</a></li>

      </ul>

    </li>

  </ul>

</div>

  </div></nav><?php } else { ?><nav class="navbar navbar-expand-lg bg-body-tertiary position-relative">  <div class="container"><a class="navbar-brand" href="#" style="position:absolute; left:50%; transform:translateX(-50%);">Mini Project</a>



<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUser" aria-controls="navbarUser" aria-expanded="false" aria-label="Toggle navigation">

  <span class="navbar-toggler-icon"></span>

</button>



<div class="collapse navbar-collapse" id="navbarUser">

  <ul class="navbar-nav me-auto mb-2 mb-lg-0">

    <li class="nav-item"><a class="nav-link active" href="dashboard.php">Home</a></li>

  </ul>



  <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

    <li class="nav-item dropdown">

      <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">

        Settings

      </a>

      <ul class="dropdown-menu dropdown-menu-end">

        <li><a class="dropdown-item" href="#"><?= $_SESSION['name'] ?></a></li>

        <li><a class="dropdown-item" href="#"><?= $_SESSION['username'] ?></a></li>

        <li><hr class="dropdown-divider"></li>

        <li><a class="dropdown-item" href="../auth/logout.php">Logout</a></li>

      </ul>

    </li>

  </ul>

</div>

  </div></nav><?php } ?><nav><a href="index.php">الرئيسية</a>

  <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'){ ?><a href="../admin/dashboard.php">لوحة التحكم</a>

  <?php } elseif(isset($_SESSION['role']) && $_SESSION['role'] == 'user'){ ?><a href="../user/offers.php">العروض</a>

  <?php } ?>  <?php if(isset($_SESSION['role'])){ ?><a href="../auth/logout.php">تسجيل الخروج</a>

  <?php } ?></nav>