(function ($) {
  flatpickr("#date_to_loan", {});
  flatpickr("#date_to_loan_pay_date", {});
  
  callTableLoan();
  callTableLoanPayments();

})(jQuery);
var count_loan = 0;

function callTableLoan() {
  $("#tableLoanOn").DataTable().clear().destroy();
  $.ajax({
    url: serverUrl + "/loan/tableLoan",
    dataType: "json",
    type: "get",
    success: function (response) {
      var result = JSON.parse(response.message);
      result.forEach(statusLoan);

      $("#count_car").html(
        '<div class="tx-primary tx-18" id="count_car">รายการสินเชื่อที่ยังไม่ปิด (' +
        count_loan +
        " ราย)</div>"
      );
      count_loan = 0;

      callAutoloenTable(result);
      slowSummarizeLoan();
    },
  });
}

function callAutoloenTable(data) {
  var tableLoan = $("#tableLoanOn").DataTable({
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
    scrollX: "TRUE",
    paging: true,
    processing: true,
    serverside: true,
    data: data,
    columnDefs: [
      {
        targets: 7,
        className: "text-right",
        data: "loan_summary_no_vat",
        render: function (data, type, row, meta) {
          return (
            '<span class="tx-success">' +
            new Intl.NumberFormat().format(
              Number(data["loan_summary_no_vat"]).toFixed(2)
            ) +
            "</span>"
          );
        },
      },
      {
        data: "loan_date_promise",
        targets: 6,
        render: function (data, type, row, meta) {
          if (type == "display") {
            const date = new Date(data["loan_date_promise"]);
            const result = date.toLocaleDateString("th-TH", {
              year: "numeric",
              month: "short",
              day: "numeric",
            });
            return result;
          }
          return data["loan_date_promise"];
        },
      },
      {
        data: "loan_installment_date",
        targets: 8,
        render: function (data, type, row, meta) {
          if (data["loan_installment_date"] == null) {
            return (
              "<font class='tx-primary'>รอเพิ่มวันจ่าย</font>"
            );
          }else{
            if (type == "display") {
              const date = new Date(data["loan_installment_date"]);
              const newDate = new Date(date.setMonth(date.getMonth() + 1));
              const result = newDate.toLocaleDateString("th-TH", {
                day: "numeric",
              }) + " ของทุกเดือน";
              return result;
            }
          }
          return data["loan_installment_date"];
        },
      }
    ],
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        },
      },
      {
        data: null,
        render: function (data, type, row, meta) {
          return (
            '<a href="' +
            serverUrl +
            "/loan/detail/" +
            data["loan_code"] +
            '" target="_blank"><font>' +
            data["loan_code"] +
            "</font></a>"
          );
        },
      },
      {
        data: "loan_customer",
      },
      {
        data: "loan_address",
      },
      {
        data: "loan_area",
      },
      {
        data: "loan_number",
      },
      {
        data: null,
      },
      {
        data: null,
      },
      {
        data: null,
      },
      {
        data: null,
        render: function (data, type, row, meta) {
            if (data["loan_status"] == "ON_STATE") {

              const date = new Date(data["loan_payment_date_fix"]);
              const newDate = new Date(date.setMonth(date.getMonth() + (data["loan_period"] - 1)));

              const daysPassed = Math.floor((Date.now() - newDate) / (1000 * 60 * 60 * 24));
              if (daysPassed > 0) {
                return (
                  "<font class='tx-secondary'>รอการจ่าย/เลยกำหนด</font>"
                );
              } else {
                return (
                  "<font class='tx-success'>ยังไม่ถึงกำหนด</font>"
                );
              }
            } else if (data["loan_status"] == "CLOSE_STATE") {
              return (
                "<font>สินเชื่อชำระเสร็จสิ้น</font>"
              );
            }
          // }
        },
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, row, meta) {
            if (data["loan_status"] == "ON_STATE") {

              const date = new Date(data["loan_payment_date_fix"]);
              const newDate = new Date(date.setMonth(date.getMonth() + (data["loan_period"] - 1)));

              const daysPassed = Math.floor((Date.now() - newDate) / (1000 * 60 * 60 * 24));

              if (daysPassed > 0) {
                return (
                  "<font class='tx-secondary'>" + daysPassed + " วัน" + "</font>"
                );
              } else {
                return (
                  "<font>-</font>"
                );
              }
            }
        },
      },
      {
        data: null,
        className: "text-right",
        render: function (data, type, row, meta) {
            if (data["loan_status"] == "ON_STATE") {

              const date = new Date(data["loan_payment_date_fix"]);
              const newDate = new Date(date.setMonth(date.getMonth() + (data["loan_period"] - 1)));

              const daysPassed = Math.floor((Date.now() - newDate) / (1000 * 60 * 60 * 24));

              if (daysPassed > 0) {
                const loan_overdue = Number(data["loan_overdue"].replace(/[^0-9.-]+/g, ""));
                var loan_overdue_sum = (data["loan_payment_month"] * (loan_overdue + 1));
                return (
                  "<font class='tx-danger'>" + new Intl.NumberFormat().format(Number(loan_overdue_sum).toFixed(2)) + "</font>"
                );
              } else {
                return (
                  "<font>-</font>"
                );
              }
            }
        },
      },
      {
        data: null,
        className: "text-right",
        render: function (data, type, row, meta) {
          return (
            "<font class='tx-success'>" + new Intl.NumberFormat().format(Number(data["loan_payment_sum_installment"]).toFixed(2)) + "</font>"
          );
        },
      },
      {
        data: null,
        className: "text-right",
        render: function (data, type, row, meta) {
          var summary_all = (data["loan_sum_interest"] - data["loan_payment_sum_installment"])
          return (
            "<font>" + new Intl.NumberFormat().format(Number(summary_all).toFixed(2)) + "</font>"
          );
        },
      },
      {
        data: null,
        className: "text-right",
        render: function (data, type, row, meta) {
          return (
            "<font>" + new Intl.NumberFormat().format(Number(data["loan_payment_month"]).toFixed(2)) + "</font>"
          );
        },
      },
      {
        data: "loan_type",
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, row, meta) {
          return (
            "<font>" + data["loan_payment_year_counter"] + " ปี" + "</font>"
          );
        },
      },
      {
        data: "loan_payment_type",
        className: "text-center",
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, row, meta) {
          var installment = (data["loan_payment_year_counter"] * 12)
          return (
            "<font>" + installment + "</font>"
          );
        },
      },
      {
        data: null,
        className: "text-right",
        render: function (data, type, row, meta) {
          return (
            "<font>" + data["loan_payment_interest"] + " %" + "</font>"
          );
        },
      },
      {
        data: "loan_remnark",
      },
    ],
  });
}

function statusLoan(item, index, arr) {
  if (item.loan_status == "ON_STATE") {
    count_loan++;
  }
}

function dateDiff(date_now, date_stock) {
  const diffTime = Math.abs(date_now - date_stock);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  const result = diffDays - 1;

  return "<font> " + result + " วัน</font>";
}

$(document).delegate(".btn-add-loan", "click", function (e) {
  let modalAddLoan = $("#modalAddLoan");
  let formAddLoan = modalAddLoan.find("form").attr("id");
  let form = modalAddLoan.find("form");
  var formData = new FormData(document.getElementById(formAddLoan));

  var loan_list = form.parsley();
  if (loan_list.isValid()) {
    $(".btn-add-loan").text("กำลังบันทึก...");
    $.ajax({
      url: serverUrl + "/loan/addLoan",
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
            msg: "เพิ่มสินเชื่อไม่สำเร็จ",
            position: "right",
            fade: true,
            time: 300,
          });
          $(".btn-add-loan").text("บันทึก");
        } else {
          notif({
            type: "success",
            msg: "เพิ่มสินเชื่อสำเร็จ!",
            position: "right",
            fade: true,
            time: 300,
          });

          form.parsley().reset();
          form[0].reset();
          $(".btn-add-loan").text("บันทึก");
          $("#modalAddLoan").modal("hide");
          callTableLoan();
        }
      },
    });
  } else {
    loan_list.validate();
    $(".btn-add-loan").text("บันทึก");
  }
});

$("#loan_without_vat").keyup(function () {
  $("#money_loan").val($("#loan_without_vat").val());

  let $transfer = $("#charges_transfer").val(),
  $etc = $("#charges_etc").val(),
  $process = $("#charges_process").val(),
  $loan_without = $("#loan_without_vat").val();

  $transfer = Number($transfer.replace(/[^0-9.-]+/g, ""));
  $etc = Number($etc.replace(/[^0-9.-]+/g, ""));
  $loan_without = Number($loan_without.replace(/[^0-9.-]+/g, ""));
  $process = Number($process.replace(/[^0-9.-]+/g, ""));

  $really_pay = 0;
  $really_pay = $loan_without - ($process + $etc + $transfer);
  
  $("#really_pay_loan").val($really_pay);
});

$(".modalAddLoanClose").click(function () {
  let modalAddLoan = $("#modalAddLoan");
  let form = modalAddLoan.find("form");
  form.parsley().reset();
  form[0].reset();
  $(".btn-add-loan").text("บันทึก");
  $("#modalAddLoan").modal("hide");
});

$("#payment_interest").keyup(function () {
  let $loanPrice = $("#money_loan").val(),
    $numYear = $("#payment_year_counter").val(),
    $interest = $("#payment_interest").val();

  $loanPrice = Number($loanPrice.replace(/[^0-9.-]+/g, ""));
  $numYear = Number($numYear.replace(/[^0-9.-]+/g, ""));

  $dok_total = 0;
  $sum_all = 0;

  $dok_total = (($loanPrice * $interest) / 100) * $numYear;

  $numYear = 12 * $numYear;

  $pay_count = $dok_total / $numYear;

  $sum_all = $dok_total + $loanPrice;
  
  $("#total_loan_interest").val($dok_total);
  $("#pricePerMonth").val($pay_count);
  $("#total_loan").val($sum_all);
});

$("#charges_process").keyup(function () {

  let $transfer = $("#charges_transfer").val(),
  $etc = $("#charges_etc").val(),
  $process = $("#charges_process").val(),
  $loan_without = $("#loan_without_vat").val();

  $transfer = Number($transfer.replace(/[^0-9.-]+/g, ""));
  $etc = Number($etc.replace(/[^0-9.-]+/g, ""));
  $loan_without = Number($loan_without.replace(/[^0-9.-]+/g, ""));
  $process = Number($process.replace(/[^0-9.-]+/g, ""));

  $really_pay = 0;
  $really_pay = $loan_without - ($process + $etc + $transfer);
  
  $("#really_pay_loan").val($really_pay);
});

$("#charges_etc").keyup(function () {

  let $transfer = $("#charges_transfer").val(),
  $etc = $("#charges_etc").val(),
  $process = $("#charges_process").val(),
  $loan_without = $("#loan_without_vat").val();

  $transfer = Number($transfer.replace(/[^0-9.-]+/g, ""));
  $etc = Number($etc.replace(/[^0-9.-]+/g, ""));
  $loan_without = Number($loan_without.replace(/[^0-9.-]+/g, ""));
  $process = Number($process.replace(/[^0-9.-]+/g, ""));

  $really_pay = 0;
  $really_pay = $loan_without - ($process + $etc + $transfer);
  
  $("#really_pay_loan").val($really_pay);
});

$("#charges_transfer").keyup(function () {

  let $transfer = $("#charges_transfer").val(),
  $etc = $("#charges_etc").val(),
  $process = $("#charges_process").val(),
  $loan_without = $("#loan_without_vat").val();

  $transfer = Number($transfer.replace(/[^0-9.-]+/g, ""));
  $etc = Number($etc.replace(/[^0-9.-]+/g, ""));
  $loan_without = Number($loan_without.replace(/[^0-9.-]+/g, ""));
  $process = Number($process.replace(/[^0-9.-]+/g, ""));

  $really_pay = 0;
  $really_pay = $loan_without - ($process + $etc + $transfer);
  
  $("#really_pay_loan").val($really_pay);
});

// ทำการจัดการกับผลลัพธ์ที่ได้จากคำขอ Ajax
function slowSummarizeLoan() {
  $.ajax({
    type: 'POST',
    url: `/loan/ajax-summarizeLoan`,
    contentType: 'application/json; charset=utf-8',
    success: function (res) {
      if (res.success) {
        let $data_summarizeLoan = res.data_summarizeLoan
        $("#summarizeLoan").hide().html($data_summarizeLoan).fadeIn('slow')

        let $data_SummarizeLoan = res.data_SummarizeLoan
        $("#SummarizeLoan").hide().html($data_SummarizeLoan).fadeIn('slow')
      } else {
        // Handle error
      }
    },
    error: function (res) {
      // Handle error
    }
  });
}

$('.tabPaymentType').on('click', function () {
  let $inputType = $("input[name=loan_type]")
  let $me = $(this),
      $docType = $me.find('a').data('type')
      console.log($docType);
      $inputType.val($docType)
})

function callTableLoanPayments() {
  $("#tableLoanPayments").DataTable().clear().destroy();
  $.ajax({
    url: serverUrl + "/loan/tableLoanPayments",
    dataType: "json",
    type: "get",
    success: function (response) {
      var result = JSON.parse(response.message);
      callAutoloenTablePayments(result);
    },
  });
}

function callAutoloenTablePayments(data) {
  var tableLoanPayments = $("#tableLoanPayments").DataTable({
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
    scrollX: "TRUE",
    paging: true,
    processing: true,
    serverside: true,
    data: data,
    columns: [
      {
        className: "text-center",
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        },
      },
      {
        data: "setting_land_report_detail",
      },
      {
        data: null,
        className: "text-right",
        render: function (data, type, row, meta) {
          return (
            "<font>" + new Intl.NumberFormat().format(Number(data["setting_land_report_money"]).toFixed(2)) + "</font>"
          );
        },
      },
      {
        data: "setting_land_report_note",
      },
      {
        className: "text-center",
        data: "employee_name",
      },
      {
        className: "text-center",
        data: "land_account_name",
      },
      {
        data: null,
        className: "text-right",
        render: function (data, type, row, meta) {
          return (
            "<font>" + new Intl.NumberFormat().format(Number(data["setting_land_report_account_balance"]).toFixed(2)) + "</font>"
          );
        },
      },
      {
        data: "created_at",
      },
    ],
  });
}