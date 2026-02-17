(function ($) {
  flatpickr("#owner_loan_date", {
    disableMobile: true,
  });

  // callTableOwnerLoan();
})(jQuery);

$(document).ready(function () {
  easyNumberSeparator({
    selector: ".price_amount",
    separator: ",",
  });
});

$(document).ready(function () {
  flatpickr("#daterange_owner_loan", {
    mode: "range",
    dateFormat: "Y-m-d",
    disableMobile: true,
    onChange: function () {
      callTableOwnerLoan();
    },
  });

  callTableOwnerLoan();
  slowSummarizeOwnerLoan();
});

var count_owner_loan = 0;

// function callTableOwnerLoan() {
//   $("#tableOwnerLoanOn").DataTable().clear().destroy();

//   const date = $("#daterange_owner_loan").val() || "";

//   $.ajax({
//     url: serverUrl + "/ownerloan/tableOwnerLoanOn",
//     dataType: "json",
//     type: "get",
//     data: { date: date },
//     success: function (response) {
//       var result = JSON.parse(response.message);
//       result.forEach(statusOwnerLoan);

//       $("#count_owner_loan").html(
//         '<div class="tx-primary tx-18" id="count_owner_loan">รายการยืม (' +
//           count_owner_loan +
//           " รายการ)</div>",
//       );
//       count_owner_loan = 0;

//       callAutoloenTable(result);
//     },
//   });
// }

// function callAutoloenTable(data) {
//   var tableOwnerLoan = $("#tableOwnerLoanOn").DataTable({
//     responsive: false,
//     language: {
//       searchPlaceholder: "Search...",
//       sSearch: "",
//     },
//     info: true,
//     pagingType: "full_numbers",
//     lengthMenu: [
//       [10, 25, 50, 100, -1],
//       [10, 25, 50, 100, "All"],
//     ],
//     scrollX: "TRUE",
//     paging: true,
//     processing: true,
//     serverside: true,
//     data: data,
//     columns: [
//       {
//         data: null,
//         className: "text-center",
//         render: function (data, type, row, meta) {
//           return meta.row + meta.settings._iDisplayStart + 1;
//         },
//       },
//       {
//         data: null,
//         className: "text-center",
//         render: function (data, type, row, meta) {
//           return (
//             '<a href="' +
//             serverUrl +
//             "/ownerloan/detail/" +
//             data["owner_code"] +
//             '" target="_blank"><font>' +
//             data["owner_code"] +
//             "</font></a>"
//           );
//         },
//       },
//       {
//         data: "owner_loan_date",
//         className: "text-center",
//       },
//       {
//         data: null,
//         className: "text-right",
//         render: function (data, type, row, meta) {
//           return (
//             "<font>" +
//             new Intl.NumberFormat().format(Number(data["amount"]).toFixed(2)) +
//             "</font>"
//           );
//         },
//       },
//       {
//         data: "land_account_name",
//         className: "text-center",
//       },
//       {
//         data: "username",
//         className: "text-center",
//       },
//       {
//         data: "status",
//         className: "text-center",
//       },
//       {
//         data: "note",
//       },
//     ],
//     createdRow: function (row, data, dataIndex) {
//       $(row)
//         .find(".text-ellipsis")
//         .each(function () {
//           var content = $(this).text();
//           $(this).attr("title", content); // ตั้งค่า title ให้แสดงข้อความเต็มเมื่อ hover
//         });
//     },
//     footerCallback: function (row, data, start, end, display) {
//       var api = this.api();

//       var intVal = function (i) {
//         return typeof i === "string"
//           ? i.replace(/[\$,]/g, "") * 1
//           : typeof i === "number"
//             ? i
//             : 0;
//       };

//       // Total over this page
//       var Total_amount_owner_loan = api
//         .column(3, { page: "current" })
//         .data()
//         .reduce(function (a, b) {
//           return intVal(a) + Number(b.amount); // Handle formatted numbers
//         }, 0);

//       // Update footer
//       number_amount_owner_loan = parseFloat(Total_amount_owner_loan).toFixed(2);
//       $(api.column(3).footer()).html(
//         Number(number_amount_owner_loan).toLocaleString(),
//       );
//     },
//     bFilter: true,
//   });
// }

function callTableOwnerLoan() {
  $("#tableOwnerLoanOn").DataTable().clear().destroy();

  const date = $("#daterange_owner_loan").val() || "";

  $.ajax({
    url: serverUrl + "/ownerloan/tableOwnerLoanOn",
    dataType: "json",
    type: "get",
    data: { date: date },
    success: function (response) {
      var result = JSON.parse(response.message || "[]");
      result.forEach(statusOwnerLoan);

      $("#count_owner_loan").html(
        '<div class="tx-primary tx-18" id="count_owner_loan">รายการยืม (' +
          count_owner_loan +
          " รายการ)</div>",
      );
      count_owner_loan = 0;

      callOwnerLoanTable(result);
    },
    error: function (xhr) {
      console.log("tableOwnerLoanOn error:", xhr.status, xhr.responseText);
    },
  });
}

function callOwnerLoanTable(data) {
  $("#tableOwnerLoanOn").DataTable({
    responsive: false,
    language: {
      searchPlaceholder: "Search...",
      sSearch: "",
    },
    info: true,
    pagingType: "full_numbers",
    lengthMenu: [
      [10, 25, 50, 100, -1],
      [10, 25, 50, 100, "All"],
    ],
    scrollX: true,
    paging: true,
    processing: true,
    serverside: false,
    data: data,

    columns: [
      {
        data: null,
        className: "text-center",
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        },
      },
      {
        data: null,
        className: "text-center",
        render: function (data) {
          return (
            '<a href="' +
            serverUrl +
            "/ownerloan/detail/" +
            data["owner_code"] +
            '" target="_blank"><font>' +
            data["owner_code"] +
            "</font></a>"
          );
        },
      },

      { data: "owner_loan_date", className: "text-center" },

      // amount
      {
        data: "amount",
        className: "text-right",
        render: (v) =>
          Number(v || 0).toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          }),
      },

      // paid_total
      {
        data: "paid_total",
        className: "text-right",
        render: (v) =>
          Number(v || 0).toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          }),
      },

      // paid_principal_total
      {
        data: "paid_principal_total",
        className: "text-right",
        render: (v) =>
          Number(v || 0).toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          }),
      },

      // paid_interest_total
      {
        data: "paid_interest_total",
        className: "text-right",
        render: (v) =>
          Number(v || 0).toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          }),
      },

      // outstanding
      {
        data: "outstanding",
        className: "text-right",
        render: (v) =>
          Number(v || 0).toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          }),
      },

      // interest_due_today
      {
        data: "interest_due_today",
        className: "text-right",
        render: (v) =>
          Number(v || 0).toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          }),
      },

      // interest_rate_used
      {
        data: "interest_rate_used",
        className: "text-center",
        render: (v) => (v ? Number(v).toFixed(2) + "%" : "-"),
      },

      // days_since_last_pay
      {
        data: "days_since_last_pay",
        className: "text-center",
        render: (v) => (v != null ? v : "-"),
      },

      // interest_per_day
      {
        data: "interest_per_day",
        className: "text-right",
        render: (v) =>
          Number(v || 0).toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          }),
      },

      // total_due_today
      {
        data: "total_due_today",
        className: "text-right",
        render: (v) =>
          Number(v || 0).toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          }),
      },

      // days_from_loan
      { data: "days_from_loan", className: "text-center" },

      // last_pay_date
      {
        data: "last_pay_date",
        className: "text-center",
        render: (v) => v || "-",
      },

      // principal_progress_pct
      {
        data: "principal_progress_pct",
        className: "text-center",
        render: (v) => (v == null ? "-" : Number(v).toFixed(2) + "%"),
      },

      { data: "land_account_name", className: "text-center" },
      { data: "username", className: "text-center" },
      // { data: "status", className: "text-center" },
      { data: "note" },
    ],

    footerCallback: function (row, data, start, end, display) {
      const api = this.api();
      const sumCol = (idx) =>
        api
          .column(idx, { page: "current" })
          .data()
          .reduce((a, b) => a + Number(b || 0), 0);

      const fmt = (n) =>
        Number(n || 0).toLocaleString(undefined, {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
        });

      // index ตาม columns ด้านบน:
      // 3 amount
      // 4 paid_total
      // 5 paid_principal_total
      // 6 paid_interest_total
      // 7 outstanding
      // 8 interest_due_today
      // 9 total_due_today
      $(api.column(3).footer()).html(fmt(sumCol(3)));
      $(api.column(4).footer()).html(fmt(sumCol(4)));
      $(api.column(5).footer()).html(fmt(sumCol(5)));
      $(api.column(6).footer()).html(fmt(sumCol(6)));
      $(api.column(7).footer()).html(fmt(sumCol(7)));
      $(api.column(8).footer()).html(fmt(sumCol(8)));
      $(api.column(11).footer()).html(fmt(sumCol(11)));
      $(api.column(12).footer()).html(fmt(sumCol(12)));
    },

    bFilter: true,
  });
}

function statusOwnerLoan(item, index, arr) {
  if (item.status == "OPEN") {
    count_owner_loan++;
  }
}

$(document).on("click", ".owner_Loan_open", function () {
  // reset form
  let modal = $("#modalAddOwnerLoan");
  let form = modal.find("form")[0];
  form.reset();

  // reset dropify
  let drEvent = $(".ownerLoanFile").dropify();
  drEvent = drEvent.data("dropify");
  drEvent.resetPreview();
  drEvent.clearElement();
});

$(document).delegate(".btn-add-owner-loan", "click", function (e) {
  let $modal = $("#modalAddOwnerLoan");
  let formId = $modal.find("form").attr("id");
  let $form = $modal.find("form");
  let formData = new FormData(document.getElementById(formId));

  // parsley validate
  let v = $form.parsley();
  if (v.isValid()) {
    $(".btn-add-owner-loan").text("กำลังบันทึก...").prop("disabled", true);

    $.ajax({
      url: serverUrl + "/ownerloan/addOwnerLoan", // <-- เปลี่ยนตาม route จริงของคุณ
      method: "post",
      data: formData,
      contentType: false,
      cache: false,
      processData: false,
      dataType: "json",
      success: function (response) {
        if (response.error) {
          notif({
            type: "danger",
            msg: response.message || "เพิ่มรายการยืมไม่สำเร็จ",
            position: "right",
            fade: true,
            time: 300,
          });
          $(".btn-add-owner-loan").text("บันทึก").prop("disabled", false);
          return;
        }

        notif({
          type: "success",
          msg: "เพิ่มรายการยืมสำเร็จ!",
          position: "right",
          fade: true,
          time: 300,
        });

        $form.parsley().reset();
        $form[0].reset();
        $(".btn-add-owner-loan").text("บันทึก").prop("disabled", false);
        $("#modalAddOwnerLoan").modal("hide");

        // reload ตาราง owner loan
        if (typeof callTableOwnerLoan === "function") {
          callTableOwnerLoan();
        }
        slowSummarizeOwnerLoan();
      },
      error: function (xhr) {
        console.error("STATUS:", xhr.status);
        console.error("URL:", this.url);
        console.error("RESPONSE:", xhr.responseText);

        notif({
          type: "danger",
          msg: "ระบบขัดข้อง กรุณาลองใหม่",
          position: "right",
          fade: true,
          time: 300,
        });
      },
    });
  } else {
    v.validate();
    $(".btn-add-owner-loan").text("บันทึก").prop("disabled", false);
  }
});

// ทำการจัดการกับผลลัพธ์ที่ได้จากคำขอ Ajax
function slowSummarizeOwnerLoan() {
  $.ajax({
    type: "POST",
    url: `/ownerloan/ajax-summarizeOwnerLoan`,
    contentType: "application/json; charset=utf-8",
    success: function (res) {
      if (res.success) {
        let $data_summarizeOwnerLoan = res.data_summarizeOwnerLoan;
        $("#summarizeOwnerLoan")
          .hide()
          .html($data_summarizeOwnerLoan)
          .fadeIn("slow");
      } else {
        // Handle error
      }
    },
    error: function (res) {
      // Handle error
    },
  });
}

$(document).ready(function () {
  //When click edit modal_Interest_Rate
  $("body").on("click", ".modal_Interest_Rate", function () {
    $.ajax({
      url: "/ownerloan/edit-interest-rate",
      type: "GET",
      dataType: "json",
      success: function (res) {
        // let $data = res.data
        $("#modal_Interest_Rate").modal("show");
        $("#form_Setting_Interest_Rate #OwnerSettingId").val(res.data.id);
        $("#form_Setting_Interest_Rate #interest_Rate").val(
          res.data.default_interest_rate,
        );
      },
      error: function (data) {
        $("#modal_Interest_Rate").modal("show");
      },
    });
  });

  //modalEditTargeted
  let $modalInterestRate = $("#modal_Interest_Rate");
  let $formEditlInterestRate = $modalInterestRate.find("form");

  $formEditlInterestRate
    // บันทึกข้อมูล
    .on("click", ".btnEditSettingInterestRate", function (e) {
      e.preventDefault();

      let $me = $(this);

      $me.attr("disabled", true);

      let formData = new FormData($formEditlInterestRate[0]);

      formData.append(
        "content",
        $formEditlInterestRate.find(".ql-editor").html(),
      );

      $.ajax({
        type: "POST",
        url: "/ownerloan/update-interest-rate",
        data: formData,
        processData: false,
        contentType: false,
      })
        .done(function (res) {
          if (res.success == 1) {
            Swal.fire({
              text: "แก้ไข อัตราดอกเบี้ย สำเร็จ",
              icon: "success",
              buttonsStyling: false,
              confirmButtonText: "ตกลง",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            }).then(function (result) {
              $modalInterestRate.modal("hide");
              $me.attr("disabled", false);
            });
          }

          // กรณี: Server มีการตอบกลับ แต่ไม่สำเร็จ
          else {
            // Show error message.
            Swal.fire({
              text: res.message,
              icon: "error",
              buttonsStyling: false,
              confirmButtonText: "ตกลง",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            }).then(function (result) {
              if (result.isConfirmed) {
                // LANDING_PROMOTION.reloadPage()
              }
            });

            $me.attr("disabled", false);
          }
        })
        .fail(function (context) {
          let messages =
            context.responseJSON?.messages ||
            "ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ";
          // Show error message.
          Swal.fire({
            text: messages,
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "ตกลง",
            customClass: {
              confirmButton: "btn btn-primary",
            },
          });

          $me.attr("disabled", false);
        });
    });
});
