// document.addEventListener("DOMContentLoaded", function () {
//   fetch("daftarAbsensi.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("daftarAbsensi").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading daftarAbsensi:", error));
//   if (document.readyState === "complete") {
//     daftarAbsensi();
//   }
// });
document.addEventListener("DOMContentLoaded", function (event) {
  daftarAbsensi();
});

function daftarAbsensi() {
  $.ajax({
    url: "daftarAbsensi.php",
    type: "post",
    data: {
      flagAbsensi: "daftar",
    },
    beforeSend: function () {},
    success: function (data, status) {
      $("#daftarAbsensi").html(data);
      $("#pagination").html($(data).find("#pagination").html());
    },
  });
}


function deleteAbsensi(id) {
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
        url: "prosesAbsensi.php",
        type: "post",
        data: {
          idAbsensi: id,
          flagAbsensi: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarAbsensi();
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

function loadPage(pageNumber) {
  const limit = $("#limit").val();
  $.ajax({
    type: "POST",
    url: "daftarAbsensi.php",
    data: {
      flagAbsensi: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarAbsensi").html(data);
    },
  });
}

function prosesAbsensi() {
  const formAbsensi = $("#formAbsensiInput")[0];
  const dataForm = new FormData(formAbsensi);

  $.ajax({
    url: "../prosesAbsensi.php",
    type: "post",
    enctype: "multipart/form-data",
    processData: false,
    contentType: false,
    data: dataForm,
    dataType: "json",
    success: function (data) {
      const { status, pesan } = data;
      notifikasi(status, pesan);
      if (status) {
        setTimeout(function() {
          window.location.href = "../";
        }, 500); // Delay the redirect to allow the notification to show
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error:", textStatus, errorThrown);
    },
  });
  
}


function cariDaftarAbsensi() {
  const searchQuery = $("#searchQuery").val();
  console.log(searchQuery);
  const limit = $("#limit").val();
  if (searchQuery || limit) {
    $.ajax({
      url: "daftarAbsensi.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        limit: limit,
        flagAbsensi: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarAbsensi").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarAbsensi.php",
      type: "post",
      data: {
        flagAbsensi: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarAbsensi").html(data);
      },
    });
  }
}



function notifikasi(status, pesan) {
  if (status === true) {
    toastr.success(pesan);
  } else {
    toastr.error(pesan);
  }
}
