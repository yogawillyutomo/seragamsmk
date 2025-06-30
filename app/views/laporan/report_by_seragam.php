<?php $this->view('templates/header', $data); ?>

<h3><?= $judul ?></h3>

<div class="d-flex mb-3">
  <button id="btnExportPdfSeragam" class="btn btn-danger me-2">
    <i class="bi bi-file-earmark-pdf"></i> Export PDF
  </button>
  <button id="btnExportExcelSeragam" class="btn btn-success">
    <i class="bi bi-file-earmark-excel"></i> Export Excel
  </button>
</div>

<table class="table table-bordered" id="tblSeragam">
  <thead>
    <tr>
      <th>No</th>
      <th>Seragam</th>
      <th>Total Beli</th>
      <th>Total Diambil</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1;
    foreach ($reports as $r): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($r['seragam']) ?></td>
        <td><?= $r['total_beli'] ?></td>
        <td><?= $r['total_diambil'] ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- jsPDF & AutoTable (jika belum ada di template) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<!-- SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
  // === EXPORT PDF PER SERAGAM ===
  document.getElementById('btnExportPdfSeragam').addEventListener('click', () => {
    const {
      jsPDF
    } = window.jspdf;
    const doc = new jsPDF({
      orientation: 'landscape'
    });

    // Judul
    doc.setFontSize(14);
    doc.text('Laporan Per Seragam', 14, 16);

    // Ambil data tabel dan hitung ringkasan
    const rows = Array.from(document.querySelectorAll('#tblSeragam tbody tr'));
    const data = [];
    let sumBeli = 0,
      sumDiambil = 0;

    rows.forEach(tr => {
      const no = tr.cells[0].innerText.trim();
      const nama = tr.cells[1].innerText.trim();
      const beli = parseInt(tr.cells[2].innerText.trim(), 10);
      const ambil = parseInt(tr.cells[3].innerText.trim(), 10);

      data.push([no, nama, beli, ambil]);
      sumBeli += beli;
      sumDiambil += ambil;
    });

    // Render tabel
    doc.autoTable({
      head: [
        ['No', 'Seragam', 'Total Beli', 'Total Diambil']
      ],
      body: data,
      startY: 20,
      styles: {
        fontSize: 10
      },
      headStyles: {
        fillColor: [220, 220, 220]
      }
    });

    // Tulis rekap di bawah tabel
    const finalY = doc.lastAutoTable.finalY || 20;
    doc.setFontSize(12);
    const x = 14;
    doc.text(`Total Beli       : ${sumBeli}`, x, finalY + 10);
    doc.text(`Total Diambil    : ${sumDiambil}`, x, finalY + 17);
    doc.text(`Total Belum Ambil: ${sumBeli - sumDiambil}`, x, finalY + 24);

    // Simpan PDF
    const date = new Date().toISOString().slice(0, 10);
    doc.save(`Laporan_Seragam_${date}.pdf`);
  });


  // === EXPORT EXCEL PER SERAGAM ===
  document.getElementById('btnExportExcelSeragam').addEventListener('click', () => {
    // Buat workbook dari tabel
    const wb = XLSX.utils.table_to_book(
      document.getElementById('tblSeragam'), {
        sheet: 'LaporanSeragam'
      }
    );

    // Ambil sheet & range
    const ws = wb.Sheets['LaporanSeragam'];
    const range = XLSX.utils.decode_range(ws['!ref']);
    const lastRow = range.e.r + 1; // 1-based index dari baris terakhir data

    // Hitung sums (atau bisa juga via formula)
    // Tambahkan baris ringkasan di bawah
    const summaryAoA = [
      ['', 'Total Beli', {
        t: 'n',
        f: `SUM(C2:C${lastRow})`
      }, ],
      ['', 'Total Diambil', {
        t: 'n',
        f: `SUM(D2:D${lastRow})`
      }, ]
    ];
    // origin: {r: lastRow (0-based), c:0} → menulis tepat di bawah data
    XLSX.utils.sheet_add_aoa(ws, summaryAoA, {
      origin: {
        r: lastRow,
        c: 0
      }
    });

    // Format kolom jumlah (C & D) sebagai number
    // dan label di kolom B, formulas di kolom C/D
    // (SheetJS akan otomatis men‐expand '!ref')
    // Unduh file
    const date = new Date().toISOString().slice(0, 10);
    XLSX.writeFile(
      wb,
      `Laporan_Seragam_${date}.xlsx`
    );
  });
</script>


<?php $this->view('templates/footer'); ?>