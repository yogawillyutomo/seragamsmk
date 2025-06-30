<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Halaman <?= $data['judul']; ?></title>
  <!-- <link rel="stylesheet" href="<?= BASEURL; ?>/css/bootstrap.css"> -->

  <!-- <script src="<?= BASEURL; ?>/js/bootstrap.js" defer></script> -->
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASEURL; ?>/css/style.css">
  <!-- Tambahkan SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Bootstrap Bundle dengan JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    html,
    body {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
    }

    .main-content {
      flex: 1;
      /* Mendorong footer ke bawah */
    }

    footer {
      background: #343a40;
      color: white;
      text-align: center;
      padding: 15px 0;
      position: relative;
      width: 100%;
    }

    .container {
      max-width: 1600px;
    }
  </style>
</head>

<body>
  <?php
  if (isset($_SESSION['user_id'])) {
  ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fs-5">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASEURL; ?>">
          <img src="<?= BASEURL; ?>/img/ico.svg" alt="Logo" height="40">
          <img src="<?= BASEURL; ?>/img/smekon.png" alt="Logo" height="40">
          <span>School Uniform System</span>

        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
          <ul class="navbar-nav mx-auto"> <!-- Tambahkan mx-auto di sini -->
            <li class="nav-item">
              <a class="nav-link" href="<?= BASEURL; ?>/dashboard">Dashboard</a>
            </li>
            <?php if ($_SESSION['role'] === 'admin') : ?>
              <li class="nav-item">
                <a class="nav-link" href="<?= BASEURL; ?>/user">Manajemen User</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dataMasterDropdown" role="button" data-bs-toggle="dropdown">
                  Data Master
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="<?= BASEURL; ?>/siswa">Data Siswa</a></li>
                  <li><a class="dropdown-item" href="<?= BASEURL; ?>/seragam">Katalog Seragam</a></li>
                </ul>
              </li>
            <?php endif; ?>

            <li class="nav-item">
              <a class="nav-link" href="<?= BASEURL; ?>/transaksi">Transaksi</a>
            </li>

            <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'kasir') : ?>
              <li class="nav-item">
                <a class="nav-link" href="<?= BASEURL; ?>/laporan">Laporan Penjualan</a>
              </li>
            <?php endif; ?>

            <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'gudang') : ?>


              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dataMasterDropdown" role="button" data-bs-toggle="dropdown">
                  Laporan Gudang
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="<?= BASEURL; ?>/laporan/reportBySiswa">Per Siswa</a></li>
                  <li><a class="dropdown-item" href="<?= BASEURL; ?>/laporan/reportBySeragam">Per Seragam</a></li>
                </ul>
              </li>
            <?php endif; ?>
          </ul>

          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <span class="nav-link text-white fs-3" id="digitalClock"></span>
            </li>
          </ul>

          <!-- Menu User (Logout dalam Dropdown) -->
          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i> <?= $_SESSION['nama']; ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item disabled"><?= ucfirst($_SESSION['role']); ?></a></li>
                <li><a class="dropdown-item text-danger" href="<?= BASEURL; ?>/auth/logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>


  <?php
  }
  ?>

  <script>
    function updateDigitalClock() {
      const now = new Date();
      const hh = String(now.getHours()).padStart(2, '0');
      const mm = String(now.getMinutes()).padStart(2, '0');
      const ss = String(now.getSeconds()).padStart(2, '0');
      document.getElementById('digitalClock').innerText = `${hh}:${mm}:${ss}`;
    }
    // Jalankan segera & perbarui tiap detik
    updateDigitalClock();
    setInterval(updateDigitalClock, 1000);
  </script>


  <div class="container mt-4">