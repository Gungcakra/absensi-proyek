// document.addEventListener("DOMContentLoaded", function () {
//   fetch("daftarUser.php")
//     .then((response) => response.text())
//     .then((data) => {
//       document.getElementById("daftarUser").innerHTML = data;
//     })
//     .catch((error) => console.error("Error loading daftarUser:", error));
//   if (document.readyState === "complete") {
//     daftarUser();
//   }
// });
document.addEventListener("DOMContentLoaded", function (event) {
  daftarUser();
});

function daftarUser() {
  $.ajax({
    url: "daftarUser.php",
    type: "post",
    data: {
      flagUser: "daftar",
    },
    beforeSend: function () {},
    success: function (data, status) {
      $("#daftarUser").html(data);
      $("#pagination").html($(data).find("#pagination").html());
    },
  });
}


function deleteUser(id) {
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
        url: "prosesUser.php",
        type: "post",
        data: {
          userId: id,
          flagUser: "delete",
        },
        dataType: "json",

        success: function (data) {
          const { status, pesan } = data;
          notifikasi(status, pesan);
          daftarUser();
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
    url: "daftarUser.php",
    data: {
      flagUser: "cari",
      page: pageNumber,
      searchQuery: $("#searchQuery").val(),
      limit: limit,
    },
    success: function (data) {
      $("#daftarUser").html(data);
    },
  });
}

function prosesUser() {
  const formUser = $("#formUserInput")[0];
  const dataForm = new FormData(formUser);

  $.ajax({
    url: "../prosesUser.php",
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


function cariDaftarUser() {
  const searchQuery = $("#searchQuery").val();
  console.log(searchQuery);
  const limit = $("#limit").val();
  if (searchQuery || limit) {
    $.ajax({
      url: "daftarUser.php",
      type: "post",
      data: {
        searchQuery: searchQuery,
        limit: limit,
        flagUser: "cari",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarUser").html(data);
      },
    });
  } else {
    $.ajax({
      url: "daftarUser.php",
      type: "post",
      data: {
        flagUser: "daftar",
      },
      beforeSend: function () {},
      success: function (data, status) {
        $("#daftarUser").html(data);
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
