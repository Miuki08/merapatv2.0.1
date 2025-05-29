<?php 

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login_register.php");
    exit();
}

//Cek apakah role user adalah manager atau officer
if ($_SESSION['role'] != 'manager' && $_SESSION['role'] != 'officer') {
    // Jika bukan manager atau officer, redirect ke dashboard yang sesuai
    if ($_SESSION['role'] == 'customer') {
        header("Location: UI_listpage.php");
    } else {
        // Role tidak dikenali, redirect ke halaman login
        header("Location: user_login_register.php");
    }
    exit();
}

require_once 'meeting_admin.php';
$room = new Meet;

if (isset($_GET['room_id'])) {
    $room->room_id = $_GET['room_id'];
    $data = $room->getByID();

    if ($data && $result = $data->fetch_assoc()) {
        $room_id = $result['room_id'];
        $room_name = $result['room_name'];
        $location = $result['location'];
        $capacity = $result['capacity'];
        $facilities = $result['facilities'];
        $status = $result['status'];
        $total_price = $result['total_price'];
    } else {
        echo "<script>
                alert('Data tidak ditemukan');
                location.href = 'meet_min_view.php';
              </script>";
        exit;
    }
} else {
    echo "<script>
            alert('Room ID tidak valid');
            location.href = 'meet_min_view.php';
          </script>";
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Updated Room | MERAPAT ID</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="d-flex align-items-center">
    <div class="container py-5">
        <div class="row g-5">
            <!-- Hero Section -->
            <div class="col-lg-6">
                <div class="hero-section animate-float">
                    <img src="img/ides.jpg" alt="replika" class="hero-image">
                    <div class="content-text">
                        <h2 class="display-4 fw-bold mb-4">MERAPAT</h2>
                        <p class="lead fs-5">
                            <i class="fas fa-quote-left me-2"></i>
                            Selalu Update kondisi ruangan kita agar pengguna akan selalu percaya dengan pelayanan kita.
                            Jujur dengan pelanggan maka akan selalu mendapatkan kepercayaan dari pelanggan.
                            <i class="fas fa-quote-right ms-2"></i>
                        </p>
                        <div class="mt-4 d-flex gap-2">
                            <span class="badge bg-light text-primary p-3">Profesional</span>
                            <span class="badge bg-light text-primary p-3">Terpercaya</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="col-lg-6">
                <form action="meet_action.php?action=update" method="post" class="glass-panel">
                    <h2 class="text-center mb-4 display-5 fw-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Update Ruangan
                    </h2>
                    
                    <input type="hidden" name="room_id" value="<?= $room_id ?>">

                    <div class="mb-4">
                        <label class="form-label">Nama Ruangan</label>
                        <input type="text" class="form-control" name="room_name" value="<?= $room_name ?>" required>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Kapasitas</label>
                            <input type="number" class="form-control" name="capacity" value="<?= $capacity ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga</label>
                            <input type="number" class="form-control" name="total_price" value="<?= $total_price ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Lokasi</label>
                        <input type="text" class="form-control" name="location" value="<?= $location ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Fasilitas</label>
                        <textarea class="form-control" name="facilities" rows="4" required><?= $facilities ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="Ready" <?= $status == 'Ready' ? 'selected' : ''; ?>>Tersedia</option>
                            <option value="Booking" <?= $status == 'Booking' ? 'selected' : ''; ?>>Dipesan</option>
                            <option value="Repair" <?= $status == 'Repair' ? 'selected' : ''; ?>>Perbaikan</option>
                        </select>
                    </div>

                    <div class="d-grid gap-3 d-md-flex justify-content-end">
                        <button type="button" class="btn btn-light btn-lg" onclick="history.back()">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </button>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enhanced Form Validation
        document.querySelector('form').addEventListener('submit', function(e) {
            let isValid = true;
            
            this.querySelectorAll('.form-control').forEach(input => {
                if (!input.checkValidity()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                this.classList.add('was-validated');
                
                // Smooth scroll to first invalid input
                const firstInvalid = this.querySelector('.is-invalid');
                firstInvalid?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });

        // Real-time validation
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.toggle('is-invalid', !this.checkValidity());
            });
        });
    </script>
</body>
</html>