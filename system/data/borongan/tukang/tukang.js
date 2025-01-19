// document.addEventListener("DOMContentLoaded", function () {
//   fetch("daftarTukang.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("daftarTukang").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading daftarTukang:", error));
//   if (document.readyState === "complete") {
//     daftarTukang();
//   }
// });
document.addEventListener("DOMContentLoaded", function (event) {
  daftarTukang();
});

function daftarTukang() {
  $.ajax({
    url: "daftarTukang.php",
    type: "post",
    data: {
      flagTukang: "daftar",
    },
    beforeSend: function () {},
    success: function (data, status) {
      $("#daftarTukang").html(data);
      $("#pagination").html($(data).find("#pagination").html());
    },
  });
}


function deleteTukang(id) {
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
        url: "prosesTukang.php",
        type: "post",
        data: {
          idTukang: id,
          flagTukang: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarTukang();
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
    url: "daftarTukang.php",
    data: {
      flagTukang: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarTukang").html(data);
    },
  });
}

function prosesTukang() {
  const formTukang = $("#formTukangInput")[0];
  const dataForm = new FormData(formTukang);

  $.ajax({
    url: "../prosesTukang.php",
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


function cariDaftarTukang() {
  const searchQuery = $("#searchQuery").val();
  console.log(searchQuery);
  const limit = $("#limit").val();
  if (searchQuery || limit) {
    $.ajax({
      url: "daftarTukang.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        limit: limit,
        flagTukang: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarTukang").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarTukang.php",
      type: "post",
      data: {
        flagTukang: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarTukang").html(data);
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
