<?php $this->view('templates/header', $data); ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<style>
  /* Highlight row saat drag */
  .ui-state-highlight {
    height: 2.5em;
    background: #fffae6;
    border: 1px dashed #ccc;
  }

  /* Ubah kursor saat hover di atas baris tabel */
  #seragamTable tr {
    cursor: grab;
    /* kursor “grab” saat idle */
  }

  #seragamTable tr:hover {
    cursor: grabbing;
    /* kursor “grabbing” saat hover */
  }
</style>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <!-- 1) Tombol Simpan Urutan -->
      <button id="saveOrder" class="btn btn-secondary me-2">
        <i class="bi bi-list-nested"></i> Simpan Urutan
      </button>
      <button type="button" id="btnTambahSeragam" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSeragam">
        <i class="bi bi-plus-circle"></i> Tambah Seragam
      </button>
    </div>
  </div>

  <!-- Flash Message -->
  <div id="flash-message"></div>

  <!-- Tabel Data Seragam -->
  <div class="card shadow-sm rounded-3 mb-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped mb-0">
          <thead class="table-light">
            <tr>
              <th class="text-center" style="width: 50px;">No</th>
              <th>Nama Seragam</th>
              <th>Harga Dasar</th>
              <th>Harga Tambahan per 10cm/pcs</th>
              <th>Berhijab</th>
              <th>Harga Berhijab</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="seragamTable">
            <!-- Data seragam akan dimuat dengan AJAX -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah/Edit Seragam -->
<div id="modalSeragam" class="modal fade" tabindex="-1" aria-labelledby="modalSeragamLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow-lg rounded-4">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title" id="modalSeragamLabel">Tambah/Edit Seragam</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formSeragam">
          <input type="hidden" name="id" id="seragamId">

          <div class="mb-3">
            <label for="nama" class="form-label">Nama Seragam</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
          </div>

          <div class="mb-3">
            <label for="harga" class="form-label">Harga Dasar</label>
            <input type="text" class="form-control format-rupiah" id="harga_dasar" name="harga_dasar" placeholder="Rp. 0" required>
          </div>

          <div class="mb-3">
            <label for="harga_tambahan" class="form-label">Harga Tambahan per 10cm/pcs</label>
            <input type="text" class="form-control format-rupiah" id="harga_tambahan" name="harga_tambahan" placeholder="Rp. 0">
          </div>

          <div class="mb-3 form-check">
            <input type="hidden" name="berhijab" value="0">
            <input type="checkbox" class="form-check-input" id="berhijab" name="berhijab" value="1">
            <label class="form-check-label" for="berhijab">Tersedia versi berhijab</label>
          </div>

          <div class="mb-3" id="hargaBerhijabContainer" style="display: none;">
            <label for="harga_berhijab" class="form-label">Harga Berhijab</label>
            <input type="text" class="form-control format-rupiah" id="harga_berhijab" name="harga_berhijab" placeholder="Rp. 0">
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-success">
              <i class="bi bi-save"></i> Simpan
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<script>
  // helper agar kolom tabel tidak “mepet” saat drag
  function fixHelper(e, ui) {
    ui.children().each(function() {
      $(this).width($(this).width());
    });
    return ui;
  }

  // 4a) Inisialisasi sortable pada <tbody id="seragamTable">
  $("#seragamTable").sortable({
    helper: fixHelper,
    placeholder: 'ui-state-highlight',
    cursor: 'move'
  }).disableSelection();

  // 4b) Simpan urutan ke server
  $("#saveOrder").on("click", function() {
    // ambil semua <tr data-id>
    const order = $("#seragamTable tr").map(function() {
      return $(this).data("id");
    }).get();

    $.post("<?= BASEURL ?>/seragam/updateOrder", {
        order: order
      })
      .done(function(res) {
        if (res.status === "success") {
          showFlashMessage("Urutan berhasil disimpan!", "success");
        } else {
          showFlashMessage("Gagal menyimpan urutan.", "danger");
        }
      })
      .fail(function() {
        showFlashMessage("Terjadi kesalahan saat menyimpan.", "danger");
      });
  });

  function loadSeragam() {
    $.ajax({
      url: "seragam/getAllSeragam",
      type: "GET",
      success: function(data) {
        $("#seragamTable").empty();
        if (data.trim() === "") {
          $("#seragamTable").html("<tr><td colspan='7' class='text-center'>Data seragam belum tersedia.</td></tr>");
        } else {
          $("#seragamTable").append(data);
        }
      },
      error: function(xhr) {
        console.error(xhr.responseText);
      }
    });
  }

  $("#btnTambahSeragam").click(function() {
    $("#modalSeragamLabel").text("Tambah Seragam");
    $("#formSeragam")[0].reset();
    $("#seragamId").val("");
    $("#hargaBerhijabContainer").hide();
  });

  $(document).ready(function() {
    loadSeragam();

    $("#formSeragam").submit(function(e) {
      e.preventDefault();

      // Bersihkan format harga
      let hargaDasar = $("#harga_dasar").val().replace(/[^\d]/g, ''); // Menghapus semua selain angka
      let hargaTambahan = $("#harga_tambahan").val().replace(/[^\d]/g, ''); // Menghapus semua selain angka
      let hargaBerhijab = $("#harga_berhijab").val().replace(/[^\d]/g, ''); // Menghapus semua selain angka

      // Update nilai input dengan angka yang sudah dibersihkan
      $("#harga_dasar").val(hargaDasar);
      $("#harga_tambahan").val(hargaTambahan);
      $("#harga_berhijab").val(hargaBerhijab);

      // Serialize form data
      let formData = $(this).serialize();

      // Cek data yang akan dikirim
      // console.log("Data yang dikirim ke server: ", formData);

      $.ajax({
        url: "seragam/saveSeragam", // URL ke server
        type: "POST",
        data: formData,
        dataType: 'json', // Pastikan kita menerima JSON sebagai respons
        success: function(response) {
          // console.log("Respons dari server: ", response); // Cek respons dari server
          if (response.status === "success") {
            $("#modalSeragam").modal("hide");
            loadSeragam(); // Fungsi untuk memuat data seragam
          }
          showFlashMessage(response.flash.message, response.flash.type);
        },
        error: function(xhr, status, error) {
          // Cek status dan error untuk detail kesalahan
          // console.error("Status: ", status);
          // console.error("Error: ", error);
          // console.error("Respons Text: ", xhr.responseText); // Ini akan menampilkan respons mentah dari server

          // Menambahkan alert atau log untuk memudahkan troubleshooting
          showFlashMessage("Terjadi kesalahan saat menyimpan data.", "danger");

          // Jika respons bukan JSON, kita bisa menampilkan informasi lebih lanjut
          try {
            const response = JSON.parse(xhr.responseText);
            // console.error("Respons yang gagal diparse: ", response);
          } catch (e) {
            // console.error("Gagal memparse JSON dari respons server.");
          }
        }
      });
    });



    $(document).on("click", ".btnEdit", function() {
      let id = $(this).data("id");
      $.ajax({
        url: "seragam/getSeragamById/" + id,
        type: "GET",
        success: function(data) {
          let seragam = data; // data sudah berupa objek JavaScript

          $("#modalSeragamLabel").text("Edit Seragam");
          $("#seragamId").val(seragam.id);
          $("#nama").val(seragam.nama);

          // Format harga dasar, harga tambahan, dan harga berhijab ke format Rupiah
          $("#harga_dasar").val(formatRupiah(seragam.harga));
          $("#harga_tambahan").val(formatRupiah(seragam.harga_tambahan));

          if (seragam.berhijab == 1) {
            $("#berhijab").prop("checked", true);
            $("#hargaBerhijabContainer").show();
            $("#harga_berhijab").val(formatRupiah(seragam.harga_berhijab)); // Format harga berhijab
          } else {
            $("#berhijab").prop("checked", false);
            $("#hargaBerhijabContainer").hide();
            $("#harga_berhijab").val("");
          }

          $("#modalSeragam").modal("show");
        },
        error: function(xhr) {
          console.error(xhr.responseText);
        }
      });
    });


    $(document).on("click", ".btnDelete", function() {
      let id = $(this).data("id");
      let nama = $(this).data("nama");

      Swal.fire({
        title: "Konfirmasi Hapus",
        text: `Apakah Anda yakin ingin menghapus seragam "${nama}"?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, Hapus!"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "seragam/deleteSeragam",
            type: "POST",
            data: {
              id: id
            },
            success: function(response) {
              Swal.fire("Berhasil!", "Seragam telah dihapus.", "success").then(() => {
                // Muat ulang data seragam
                loadSeragam();

                // Menampilkan flash message
                showFlashMessage("Seragam berhasil dihapus!", "success");
              });
            },
            error: function() {
              Swal.fire("Error!", "Gagal menghapus seragam.", "error");
            }
          });
        }
      });
    });

  });

  function showFlashMessage(message, type) {
    let flashHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
          ${message}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
    $("#flash-message").html(flashHtml);
    setTimeout(() => $(".alert").fadeOut(), 3000);
  }

  // Format otomatis untuk input harga menjadi format Rupiah
  $('.format-rupiah').on('input', function(e) {
    let value = e.target.value.replace(/[^0-9]/g, ''); // Hapus semua selain angka
    if (value) {
      e.target.value = formatRupiah(value);
    } else {
      e.target.value = '';
    }
  });


  // Fungsi untuk memformat angka menjadi format Rupiah
  function formatRupiah(angka) {
    let number_string = angka.toString();
    let sisa = number_string.length % 3;
    let rupiah = number_string.substr(0, sisa);
    let ribuan = number_string.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
      let separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }
    return 'Rp. ' + rupiah;
  }


  $("#berhijab").change(function() {
    if ($(this).is(":checked")) {
      $("#hargaBerhijabContainer").show();
    } else {
      $("#hargaBerhijabContainer").hide();
      $("#harga_berhijab").val("");
    }
  });
</script>

<?php $this->view('templates/footer'); ?>