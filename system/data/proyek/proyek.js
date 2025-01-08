// document.addEventListener("DOMContentLoaded", function () {
//   fetch("daftarProyek.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("daftarProyek").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading daftarProyek:", error));
//   if (document.readyState === "complete") {
//     daftarProyek();
//   }
// });
document.addEventListener("DOMContentLoaded", function (event) {
  daftarProyek();
});

function daftarProyek() {
  $.ajax({
    url: "daftarProyek.php",
    type: "post",
    data: {
      flagProyek: "daftar",
    },
    beforeSend: function () {},
    success: function (data, status) {
      $("#daftarProyek").html(data);
      $("#pagination").html($(data).find("#pagination").html());
    },
  });
}


function deleteProyek(id) {
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
        url: "prosesProyek.php",
        type: "post",
        data: {
          idProyek: id,
          flagProyek: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarProyek();
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
    url: "daftarProyek.php",
    data: {
      flagProyek: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarProyek").html(data);
    },
  });
}

function prosesProyek() {
  const formProyek = $("#formProyekInput")[0];
  const dataForm = new FormData(formProyek);

  $.ajax({
    url: "../prosesProyek.php",
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


function cariDaftarProyek() {
  const searchQuery = $("#searchQuery").val();
  console.log(searchQuery);
  const limit = $("#limit").val();
  if (searchQuery || limit) {
    $.ajax({
      url: "daftarProyek.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        limit: limit,
        flagProyek: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarProyek").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarProyek.php",
      type: "post",
      data: {
        flagProyek: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarProyek").html(data);
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
