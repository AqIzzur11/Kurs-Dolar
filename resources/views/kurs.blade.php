<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurs Dolar Hari Ini</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="row">

    <div class="col-7  container mt-5 ">
        <h2 class="text-uppercase text-center">Kurs Dolar Hari Ini</h2>
        <div class="row mt-3">
            <div class="col-6 border rounded-start d-flex">
                <h4 class="my-auto">Kurs Dolar BI</h4>
            </div>
            <div class="col-6 border rounded-end d-flex">
                <!-- Loading Indicator -->
        <div id="loading" class="my-auto">
            <!-- <p class="text-muted">Memuat data kurs...</p>
              -->
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <!-- Data Kurs -->
        <div id="kurs-data" style="display: none;" class="my-auto mt-3">
            <h6>Rp <span id="kurs-value">0</span></h6>
            <p class="text-muted" style="font-size:13px">Update terakhir : <span id="kurs-date">-</span> <span id="kurs-clock">-</span></p>
        </div>

        <!-- Jika gagal mengambil data -->
        <div id="kurs-container"></div>
    </div>
            </div>
        </div>
        </div>

    <script>
                $.ajax({
            url: "{{ route('get.kurs') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}" // Tambahkan CSRF token di sini
            },
            success: function (data) {
                if (data && data.kursJual) {
                    $("#kurs-value").text(parseFloat(data.kursJual).toLocaleString());
                    $("#kurs-date").text(data.tanggal);
                    $("#kurs-clock").text(data.jam);
                    

                    $("#loading").hide();
                    $("#kurs-data").show();
                }
            },
            error: function () {
                $("#kurs-container").html("<p class='text-danger'>Gagal mengambil data kurs.</p>");
            }
        });
    </script>
</body>
</html>
