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
$(document).ready(function () {
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

function loadPage(pageNumber) {
  const limit = $("#limit").val();
  $.ajax({
    type: "POST",
    url: "daftarCashbon.php",
    data: {
      flagCashbon: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarCashbon").html(data);
    },
  });
}

function prosesCashbon() {
  const formCashbon = $("#formCashbonInput")[0];
  const dataForm = new FormData(formCashbon);

  $.ajax({
    url: "../prosesCashbon.php",
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
        setTimeout(function () {
          window.location.href = "../";
        }, 500); // Delay the redirect to allow the notification to show
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error:", textStatus, errorThrown);
    },
  });
}

function cariDaftarCashbon() {
  const searchQuery = $("#searchQuery").val();
  console.log(searchQuery);
  const limit = $("#limit").val();
  if (searchQuery || limit) {
    $.ajax({
      url: "daftarCashbon.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        limit: limit,
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

function notifikasi(status, pesan) {
  if (status === true) {
    toastr.success(pesan);
  } else {
    toastr.error(pesan);
  }
}
