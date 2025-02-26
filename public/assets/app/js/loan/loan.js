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
        targets: 3, // กำหนดคอลัมน์ที่ต้องการให้ตัดข้อความ
        render: function (data, type, row) {
          return (
            '<div class="text-ellipsis wd-180" title="' +
            data["loan_address"] +
            '">' +
            data["loan_address"] +
            "</div>"
          );
        },
      },
      {
        targets: 5, // กำหนดคอลัมน์ที่ต้องการให้ตัดข้อความ
        render: function (data, type, row) {
          return (
            '<div class="text-ellipsis wd-100" title="' +
            data["loan_number"] +
            '">' +
            data["loan_number"] +
            "</div>"
          );
        },
      },
      {
        targets: 8,
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
        targets: 7,
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
        targets: 9,
        render: function (data, type, row, meta) {
          if (data["loan_installment_date"] == null) {
            return "<font class='tx-primary'>รอเพิ่มวันจ่าย</font>";
          } else {
            if (type == "display") {
              const date = new Date(data["loan_installment_date"]);
              const newDate = new Date(date.setMonth(date.getMonth() + 1));
              const result =
                newDate.toLocaleDateString("th-TH", {
                  day: "numeric",
                }) + " ของทุกเดือน";
              return result;
            }
          }
          return data["loan_installment_date"];
        },
      },
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
        data: null,
      },
      {
        data: "loan_area",
      },
      {
        data: null,
      },
      {
        data: "land_deed_status", // ค่า 0 หรือ 1 ที่จะติ๊ก
        className: "text-center",
        orderable: false,
        render: function (data, type, row) {
            let checked = data == 1 ? "checked" : "";
            return `<input type="checkbox" class="row-check" data-id="${row.loan_code}" ${checked}>`;  // ใช้ row.loan_code ในการแทนค่า data-id
        },
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
            const newDate = new Date(
              date.setMonth(date.getMonth() + (data["loan_period"] - 1))
            );

            const daysPassed = Math.floor(
              (Date.now() - newDate) / (1000 * 60 * 60 * 24)
            );
            if (daysPassed > 0) {
              return "<font class='tx-secondary'>รอการจ่าย/เลยกำหนด</font>";
            } else {
              return "<font class='tx-success'>ยังไม่ถึงกำหนด</font>";
            }
          } else if (data["loan_status"] == "CLOSE_STATE") {
            return "<font>สินเชื่อชำระเสร็จสิ้น</font>";
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
            const newDate = new Date(
              date.setMonth(date.getMonth() + (data["loan_period"] - 1))
            );

            const daysPassed = Math.floor(
              (Date.now() - newDate) / (1000 * 60 * 60 * 24)
            );

            if (daysPassed > 0) {
              return (
                "<font class='tx-secondary'>" + daysPassed + " วัน" + "</font>"
              );
            } else {
              return "<font>-</font>";
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
            const newDate = new Date(
              date.setMonth(date.getMonth() + (data["loan_period"] - 1))
            );

            const daysPassed = Math.floor(
              (Date.now() - newDate) / (1000 * 60 * 60 * 24)
            );
            var installment = data["loan_payment_year_counter"] * 12;
            const remaining_installments =
              installment - data["loan_payment_type"];

            if (daysPassed > 0) {
              const loan_overdue =
                Number(data["loan_overdue"].replace(/[^0-9.-]+/g, "")) + 1;
              const overdue_months = Math.min(
                loan_overdue,
                remaining_installments
              );
              const loan_overdue_sum =
                overdue_months * data["loan_payment_month"];
              // var loan_overdue_sum =
              //   data["loan_payment_month"] * (loan_overdue);
              return (
                "<font class='tx-danger'>" +
                new Intl.NumberFormat().format(
                  Number(loan_overdue_sum).toFixed(2)
                ) +
                "</font>"
              );
            } else {
              return "<font>-</font>";
            }
          }
        },
      },
      {
        data: null,
        className: "text-right",
        render: function (data, type, row, meta) {
          return (
            "<font class='tx-success'>" +
            new Intl.NumberFormat().format(
              Number(data["loan_payment_sum_installment"]).toFixed(2)
            ) +
            "</font>"
          );
        },
      },
      {
        data: null,
        className: "text-right",
        render: function (data, type, row, meta) {
          var summary_all =
            data["loan_sum_interest"] - data["loan_payment_sum_installment"];
          return (
            "<font>" +
            new Intl.NumberFormat().format(Number(summary_all).toFixed(2)) +
            "</font>"
          );
        },
      },
      {
        data: null,
        className: "text-right",
        render: function (data, type, row, meta) {
          return (
            "<font>" +
            new Intl.NumberFormat().format(
              Number(data["loan_payment_month"]).toFixed(2)
            ) +
            "</font>"
          );
        },
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, row, meta) {
          if (data["loan_status"] == "ON_STATE") {
            const date = new Date(data["loan_payment_date_fix"]);
            const newDate = new Date(
              date.setMonth(date.getMonth() + (data["loan_period"] - 1))
            );

            const daysPassed = Math.floor(
              (Date.now() - newDate) / (1000 * 60 * 60 * 24)
            );
            var installment = data["loan_payment_year_counter"] * 12;
            const remaining_installments =
              installment - data["loan_payment_type"];

            const loan_overdue =
              Number(data["loan_overdue"].replace(/[^0-9.-]+/g, "")) + 1;
            const overdue_months = Math.min(
              loan_overdue,
              remaining_installments
            );
            const loan_overdue_sum =
              overdue_months * data["loan_payment_month"];

            let day_overdue_score = 0;
            if (daysPassed <= 30) {
              day_overdue_score = 5;
            } else if (daysPassed <= 90) {
              day_overdue_score = 3;
            } else {
              day_overdue_score = 1;
            }

            const overdue_percentage =
              (loan_overdue_sum / data["loan_sum_interest"]) * 100;
            let outstanding_amount_score = 0;
            if (overdue_percentage < 10) {
              outstanding_amount_score = 5; // น้อยกว่า 10% ได้ 5 คะแนน
            } else if (overdue_percentage >= 10 && overdue_percentage <= 30) {
              outstanding_amount_score = 3; // อยู่ระหว่าง 10%-30% ได้ 3 คะแนน
            } else if (overdue_percentage > 30) {
              outstanding_amount_score = 1; // มากกว่า 30% ได้ 1 คะแนน
            }

            // คำนวณเปอร์เซ็นต์ของเงินที่ชำระแล้วเทียบกับยอดทั้งหมด
            const paid_percentage =
              (data["loan_payment_sum_installment"] / data["loan_sum_interest"]) *100;

            let payment_score = 0; // ค่าเริ่มต้นของคะแนน
            // ตรวจสอบเงื่อนไขเพื่อกำหนดคะแนน
            if (paid_percentage < 20) {
              payment_score = 1; // ชำระน้อยกว่า 30% ได้ 1 คะแนน
            } else if (paid_percentage >= 20 && paid_percentage <= 60) {
              payment_score = 3; // ชำระระหว่าง 30%-60% ได้ 3 คะแนน
            } else {
              payment_score = 5; // ชำระมากกว่า 60% ได้ 5 คะแนน
            }

            // รวมคะแนนทั้งหมด
            const total_score = day_overdue_score + outstanding_amount_score + payment_score;
            if (total_score >= 12) {
              return (
                "<font class='tx-success'>ความเสี่ยงต่ำ</font>"
              );
            } else if (total_score >= 8 && total_score <= 11) {
              return (
                "<font class='tx-secondary'>ความเสี่ยงปานกลาง</font>"
              );
            } else {
              return (
                "<font class='tx-danger'>ความเสี่ยงสูง</font>"
              );
            }
          }
        },
      },
      {
        data: "loan_type",
        className: "text-center",
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
          var installment = data["loan_payment_year_counter"] * 12;
          return "<font>" + installment + "</font>";
        },
      },
      {
        data: null,
        className: "text-right",
        render: function (data, type, row, meta) {
          return "<font>" + data["loan_payment_interest"] + " %" + "</font>";
        },
      },
      {
        data: "loan_remnark",
      },
    ],
    createdRow: function (row, data, dataIndex) {
      $(row)
        .find(".text-ellipsis")
        .each(function () {
          var content = $(this).text();
          $(this).attr("title", content); // ตั้งค่า title ให้แสดงข้อความเต็มเมื่อ hover
        });
    },
    footerCallback: function (row, data, start, end, display) {
      var api = this.api();

      var intVal = function (i) {
        return typeof i === "string"
          ? i.replace(/[\$,]/g, "") * 1
          : typeof i === "number"
          ? i
          : 0;
      };

      // Total over this page
      var Total_payment_month = api
        .column(14, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b.loan_payment_month); // Handle formatted numbers
        }, 0);

      Total_sum_remaining_payment = api
        .column(13, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return (
            intVal(a) +
            (intVal(b.loan_sum_interest) -
              intVal(b.loan_payment_sum_installment))
          );
        }, 0);

      Total_summary_no_vat = api
        .column(7, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b.loan_summary_no_vat);
        }, 0);

      Total_loanOverdueSum = api
        .column(11, { page: "current" })
        .data()
        .reduce(function (a, b) {
          const date = new Date(b.loan_payment_date_fix);
          const newDate = new Date(
            date.setMonth(date.getMonth() + (b.loan_period - 1))
          );

          const daysPassed = Math.floor(
            (Date.now() - newDate) / (1000 * 60 * 60 * 24)
          );

          if (daysPassed > 0) {
            const loanPaymentMonth = intVal(b.loan_payment_month);
            const loanOverdue = intVal(b.loan_overdue);

            // คำนวณยอดรวม
            const loanOverdueSum = loanPaymentMonth * (loanOverdue + 1);

            // เพิ่มผลรวมลงใน total
            return a + loanOverdueSum;
          } else {
            // หากไม่เกินกำหนดคืน total เดิม
            return a;
          }
        }, 0);

      Total_payment_sum_installment = api
        .column(12, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b.loan_payment_sum_installment);
        }, 0);

      // Update footer
      number_payment_month = parseFloat(Total_payment_month).toFixed(2);
      $(api.column(14).footer()).html(
        Number(number_payment_month).toLocaleString()
      );

      number_sum_remaining_payment = parseFloat(
        Total_sum_remaining_payment
      ).toFixed(2);
      $(api.column(13).footer()).html(
        Number(number_sum_remaining_payment).toLocaleString()
      );

      number_payment_sum_installment = parseFloat(
        Total_payment_sum_installment
      ).toFixed(2);
      $(api.column(12).footer()).html(
        Number(number_payment_sum_installment).toLocaleString()
      );

      number_loanOverdueSum = parseFloat(Total_loanOverdueSum).toFixed(2);
      $(api.column(11).footer()).html(
        Number(number_loanOverdueSum).toLocaleString()
      );

      number_summary_no_vat = parseFloat(Total_summary_no_vat).toFixed(2);
      $(api.column(7).footer()).html(
        Number(number_summary_no_vat).toLocaleString()
      );

    },
    bFilter: true,
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
    type: "POST",
    url: `/loan/ajax-summarizeLoan`,
    contentType: "application/json; charset=utf-8",
    success: function (res) {
      if (res.success) {
        let $data_summarizeLoan = res.data_summarizeLoan;
        $("#summarizeLoan").hide().html($data_summarizeLoan).fadeIn("slow");

        let $data_SummarizeLoan = res.data_SummarizeLoan;
        $("#SummarizeLoan").hide().html($data_SummarizeLoan).fadeIn("slow");
      } else {
        // Handle error
      }
    },
    error: function (res) {
      // Handle error
    },
  });
}

$(".tabPaymentType").on("click", function () {
  let $inputType = $("input[name=loan_type]");
  let $me = $(this),
    $docType = $me.find("a").data("type");
  console.log($docType);
  $inputType.val($docType);
});

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
            "<font>" +
            new Intl.NumberFormat().format(
              Number(data["setting_land_report_money"]).toFixed(2)
            ) +
            "</font>"
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
            "<font>" +
            new Intl.NumberFormat().format(
              Number(data["setting_land_report_account_balance"]).toFixed(2)
            ) +
            "</font>"
          );
        },
      },
      {
        data: "created_at",
      },
    ],
  });
}
$(document).ready(function () {
  // เมื่อ checkbox มีการเปลี่ยนค่า ให้ส่งข้อมูลไปอัปเดตฐานข้อมูล
  $("#tableLoanOn tbody").on("change", ".row-check", function () {
    let loan_code = $(this).data("id");
    let status = this.checked ? 1 : 0; // ถ้าติ๊กให้เป็น 1, ไม่ติ๊กเป็น 0

    $.ajax({
      url: `/loan/update_deed_status`,
      type: "POST",
      data: { loan_code: loan_code, status: status },
      success: function (response) {
        console.log("บันทึกข้อมูลโฉนดสำเร็จ");
      },
      error: function (xhr) {
        alert("เกิดข้อผิดพลาดในการบันทึก");
      },
    });
  });
});
