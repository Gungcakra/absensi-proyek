// document.addEventListener("DOMContentLoaded", function () {
//   fetch("daftarBidang.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("daftarBidang").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading daftarBidang:", error));
//   if (document.readyState === "complete") {
//     daftarBidang();
//   }
// });
document.addEventListener("DOMContentLoaded", function (event) {
  daftarBidang();
});

function daftarBidang() {
  $.ajax({
    url: "daftarBidang.php",
    type: "post",
    data: {
      flagBidang: "daftar",
    },
    beforeSend: function () {},
    success: function (data, status) {
      $("#daftarBidang").html(data);
      $("#pagination").html($(data).find("#pagination").html());
    },
  });
}


function deleteBidang(id) {
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
        url: "prosesBidang.php",
        type: "post",
        data: {
          idBidang: id,
          flagBidang: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarBidang();
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
    url: "daftarBidang.php",
    data: {
      flagBidang: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarBidang").html(data);
    },
  });
}

function prosesBidang() {
  const formBidang = $("#formBidangInput")[0];
  const dataForm = new FormData(formBidang);

  $.ajax({
    url: "../prosesBidang.php",
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


function cariDaftarBidang() {
  const searchQuery = $("#searchQuery").val();
  console.log(searchQuery);
  const limit = $("#limit").val();
  if (searchQuery || limit) {
    $.ajax({
      url: "daftarBidang.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        limit: limit,
        flagBidang: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarBidang").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarBidang.php",
      type: "post",
      data: {
        flagBidang: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarBidang").html(data);
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
