// document.addEventListener("DOMContentLoaded", function () {
//   fetch("daftarCashbon.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("daftarCashbon").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading daftarCashbon:", error));
//   if (document.readyState === "complete") {
//     daftarCashbon();
//   }
// });
document.addEventListener("DOMContentLoaded", function (event) {
  daftarCashbon();
});

$(document).ready(function() {
  $(".select-tukang").select2();
});


function daftarCashbon() {
  $.ajax({
    url: "daftarCashbon.php",
    type: "post",
    data: {
      flagCashbon: "daftar",
    },
    beforeSend: function () {},
    success: function (data, status) {
      $("#daftarCashbon").html(data);
      $("#pagination").html($(data).find("#pagination").html());
    },
  });
}

function deleteCashbon(id) {
  Swal.fire({
    title: "Are You Sure?",
    text: "Once canceled, the process cannot be undone!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes!",
    cancelButtonText: "Cancel!",
  }).then(function (result) {
    if (result.isConfirmed) {
      $.ajax({
        url: "prosesCashbon.php",
        type: "post",
        data: {
          idCashbon: id,
          flagCashbon: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarCashbon();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("Error:", textStatus, errorThrown);
          Swal.fire("Error", "Something went wrong!", "error");
        },
      });
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire("Canceled", "Proses Canceled!", "error");
    }
  });
}



function prosesCashbon(data) {
  const idCashbon = data.idCashbon !== undefined ? data.idCashbon : null;
  const idTukang = data.idTukang;
  const idProyek = data.idProyek;
  $.ajax({
    url: "../prosesCashbon.php",
    type: "post",
    enctype: "multipart/form-data",
    data: {
      flagCashbon: "absensi",
      idCashbon: idCashbon,
      idTukang: idTukang,
      idProyek: idProyek,

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


function cariDaftarCashbon() {
  const idProyek = $("#idProyek").val();
  const bulanTahun = $("#bulanTahun").val();
  
  if (idProyek || bulanTahun) {
    $.ajax({
      url: "daftarCashbon.php",
      type: "post",
      data: {
        idProyek: idProyek,
        bulanTahun: bulanTahun,
        flagCashbon: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarCashbon").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarCashbon.php",
      type: "post",
      data: {
        flagCashbon: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarCashbon").html(data);
      },
    });
  }
}

function generateLaporan() {
  const idProyek = $("#idProyek").val();
  const bulanTahun = $("#bulanTahun").val();

  const form = $('<form>', {
    action: 'laporan/',
    method: 'post',
    target: '_blank'
  }).append($('<input>', {
    type: 'hidden',
    name: 'idProyek',
    value: idProyek
  })).append($('<input>', {
    type: 'hidden',
    name: 'bulanTahun',
    value: bulanTahun
  })).append($('<input>', {
    type: 'hidden',
    name: 'flagCashbon',
    value: 'cari'
  }));

  $('body').append(form);
  form.submit();
  form.remove();
}



function notifikasi(status, pesan) {
  if (status === true) {
    toastr.success(pesan);
  } else {
    toastr.error(pesan);
  }
}
