<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>LetSense</title>
    </head>
    <body>
        <div class="container min-vh-100 d-flex flex-column align-items-center justify-content-center bg-white">
                <div class="text-center mb-4">
                        <h1 class="display-1 fw-bold text-primary">LetSense</h1>
                        <p class="fs-4 text-secondary mt-2">Sistem Absensi Pegawai</p>
                </div>
                <button onclick="getLocation()" class="btn btn-primary btn-lg mt-4">Absen Sekarang</button>
                <div id="location" class="mt-4 fs-5 text-secondary">Lokasi Anda akan tampil di sini.</div>
        </div>
        <div id="success-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-white bg-opacity-75 d-none">
                <div class="bg-white p-4 rounded shadow text-center">
                        <div class="text-success display-1 fw-bold animate__animated animate__bounce">
                                <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="text-success fs-3 fw-bold mt-2">Absensi Berhasil!</div>
                </div>
        </div>
        <div id="failure-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-white bg-opacity-75 d-none">
                <div class="bg-white p-4 rounded shadow text-center">
                        <div class="text-danger display-1 fw-bold animate__animated animate__bounce">
                                <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="text-danger fs-3 fw-bold mt-2">Absen Gagal!</div>
                </div>
        </div>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
                function showOverlay(success) {
                        const successOverlay = document.getElementById("success-overlay");
                        const failureOverlay = document.getElementById("failure-overlay");
                        if (success) {
                                successOverlay.classList.remove("d-none");
                                setTimeout(() => successOverlay.classList.add("d-none"), 3000);
                        } else {
                                failureOverlay.classList.remove("d-none");
                                setTimeout(() => failureOverlay.classList.add("d-none"), 3000);
                        }
                }

                function getLocation() {
                        if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(showPosition, showError);
                        } else {
                                document.getElementById("location").innerHTML =
                                        "Geolokasi tidak didukung oleh browser Anda.";
                        }
                }

                function showPosition(position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        document.getElementById(
                                "location"
                        ).innerHTML = `Latitude: ${latitude} <br> Longitude: ${longitude}`;

                        const targetLat = -8.6793832;
                        const targetLng = 115.2000503;
                        const maxDistance = 1;

                        const distance = calculateDistance(
                                latitude,
                                longitude,
                                targetLat,
                                targetLng
                        );

                        if (distance <= maxDistance) {
                                showOverlay(true);
                        } else {
                                showOverlay(false);
                        }
                }

                function showError(error) {
                        let message = "";
                        switch (error.code) {
                                case error.PERMISSION_DENIED:
                                        message = "Pengguna menolak permintaan geolokasi.";
                                        break;
                                case error.POSITION_UNAVAILABLE:
                                        message = "Informasi lokasi tidak tersedia.";
                                        break;
                                case error.TIMEOUT:
                                        message = "Permintaan lokasi melebihi batas waktu.";
                                        break;
                                case error.UNKNOWN_ERROR:
                                        message = "Terjadi kesalahan yang tidak diketahui.";
                                        break;
                        }
                        document.getElementById("location").innerHTML = message;
                }

                function calculateDistance(lat1, lon1, lat2, lon2) {
                        const R = 6371e3;
                        const φ1 = (lat1 * Math.PI) / 180;
                        const φ2 = (lat2 * Math.PI) / 180;
                        const Δφ = ((lat2 - lat1) * Math.PI) / 180;
                        const Δλ = ((lon2 - lon1) * Math.PI) / 180;

                        const a =
                                Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                                Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
                        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                        const distance = R * c;
                        return distance;
                }
        </script>
    </body>
</html>
