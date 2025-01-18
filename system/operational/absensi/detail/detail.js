function daftarDetail() {
  $.ajax({
    url: "daftarDetail.php",
    type: "post",
    data: {
      flagDetail: "daftar",
    },
    beforeSend: function () {},
    success: function (data, status) {
      $("#daftarDetail").html(data);
    },
  });
}
function cariDaftarDetail() {
  const tanggalAbsensi = $("#tanggalAbsensi").val();
  const idAbsensi = $("#idAbsensi").val() || "";
  const idProyek = $("#idProyek").val() || "";
  $("#daftarDetail").empty();
  $.ajax({
    url: "daftarDetail.php",
    type: "post",
    data: {
      tanggalAbsensi: tanggalAbsensi,
      idAbsensi: idAbsensi,
      idProyek: idProyek,
      flagDetail: "cari",
    },
    beforeSend: function () {},
    success: function (data, status) {
      $("#daftarDetail").html(data);
    },
  });
}

function prosesAbsensi(data) {
    const idAbsensi = data.idAbsensi !== undefined ? data.idAbsensi : null;
    const idTukang = data.idTukang;
    const idProyek = data.idProyek;
    const tanggalAbsensi = $("#tanggalAbsensi").val();
  
    $.ajax({
      url: "../prosesAbsensi.php",
      type: "post",
      enctype: "multipart/form-data",
      data: {
        flagAbsensi: "absensi",
        idAbsensi: idAbsensi,
        idTukang: idTukang,
        idProyek: idProyek,
        tanggalAbsensi: tanggalAbsensi,
      },
      dataType: "json",
      success: function (data) {
        const { status, pesan } = data;
        notifikasi(status, pesan);
  
        // Update konten halaman jika diperlukan
        $("#daftarDetail").html(data.updatedHtml);
  
        // Set nilai input tanggalAbsensi kembali
        $("#tanggalAbsensi").val(tanggalAbsensi);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
      },
    });
  }
function updateWaktuMasuk(data, waktuMasuk) {
  const idAbsensi = data.idAbsensi !== undefined ? data.idAbsensi : null;

  $.ajax({
    url: "../prosesAbsensi.php",
    type: "post",
    enctype: "multipart/form-data",
    data: {
      flagAbsensi: "waktuMasuk",
      idAbsensi: idAbsensi,
      waktuMasuk: waktuMasuk,
    },
    dataType: "json",
    success: function (data) {
      const { status, pesan } = data;
      notifikasi(status, pesan);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error:", textStatus, errorThrown);
    },
  });
}

function updateWaktuKeluar(data, waktuKeluar) {
  const idAbsensi = data.idAbsensi !== undefined ? data.idAbsensi : null;

  $.ajax({
    url: "../prosesAbsensi.php",
    type: "post",
    enctype: "multipart/form-data",
    data: {
      flagAbsensi: "waktuKeluar",
      idAbsensi: idAbsensi,
      waktuKeluar: waktuKeluar,
    },
    dataType: "json",
    success: function (data) {
      const { status, pesan } = data;
      notifikasi(status, pesan);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error:", textStatus, errorThrown);
    },
  });
}
