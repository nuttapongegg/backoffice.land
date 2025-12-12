(function ($) {
  flatpickr("#date_to_loan", {
    disableMobile: true,
  });
  flatpickr("#date_to_loan_pay_date", {
    disableMobile: true,
  });

  // callTableFinx();
})(jQuery);

$(document).ready(function () {
  flatpickr("#daterange_finx", {
    mode: "range",
    dateFormat: "Y-m-d",
    disableMobile: true,
    onChange: function () {
      callTableFinx();
    },
  });
  
  callTableFinx();
});

var count_loan = 0;

function callTableFinx() {
  $("#tableFinxOn").DataTable().clear().destroy();

  const date = $("#daterange_finx").val() || "";

  $.ajax({
    url: serverUrl + "/finx/tableFinxOn",
    dataType: "json",
    type: "get",
    data: { date: date },
    success: function (response) {
      var result = JSON.parse(response.message);
      result.forEach(statusFinx);

      $("#count_car").html(
        '<div class="tx-primary tx-18" id="count_car">รายการสินเชื่อที่ยังไม่ปิด (' +
          count_loan +
          " ราย)</div>"
      );
      count_loan = 0;

      callAutoloenTable(result);
      slowSummarizeFinx();
    },
  });
}

function callAutoloenTable(data) {
  var tableFinx = $("#tableFinxOn").DataTable({
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
            "/finx/detail/" +
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
          return `<input type="checkbox" class="row-check" data-id="${row.loan_code}" ${checked}>`; // ใช้ row.loan_code ในการแทนค่า data-id
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
          // คำนวณ 3% ของ loan_summary_no_vat
          let value = (Number(data["loan_summary_no_vat"]) * 0.03).toFixed(2);

          // แสดงผลแบบมี comma
          return "<font>" + new Intl.NumberFormat().format(value) + "</font>";
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
              (data["loan_payment_sum_installment"] /
                data["loan_sum_interest"]) *
              100;

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
            const total_score =
              day_overdue_score + outstanding_amount_score + payment_score;
            if (total_score >= 12) {
              return "<font class='tx-success'>ความเสี่ยงต่ำ</font>";
            } else if (total_score >= 8 && total_score <= 11) {
              return "<font class='tx-secondary'>ความเสี่ยงปานกลาง</font>";
            } else {
              return "<font class='tx-danger'>ความเสี่ยงสูง</font>";
            }
          }
        },
      },
      {
        data: "loan_type",
        className: "text-center",
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
      var Total_summary_no_vat_finx = api
        .column(12, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + Number(b.loan_summary_no_vat) * 0.03; // Handle formatted numbers
        }, 0);

      Total_summary_no_vat = api
        .column(8, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b.loan_summary_no_vat);
        }, 0);

      // Update footer
      number_summary_no_vat_finx = parseFloat(
        Total_summary_no_vat_finx
      ).toFixed(2);
      $(api.column(12).footer()).html(
        Number(number_summary_no_vat_finx).toLocaleString()
      );

      number_summary_no_vat = parseFloat(Total_summary_no_vat).toFixed(2);
      $(api.column(8).footer()).html(
        Number(number_summary_no_vat).toLocaleString()
      );
    },
    bFilter: true,
  });
}

function statusFinx(item, index, arr) {
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
  let modalAddFinx = $("#modalAddFinx");
  let formAddFinx = modalAddFinx.find("form").attr("id");
  let form = modalAddFinx.find("form");
  var formData = new FormData(document.getElementById(formAddFinx));

  var loan_list = form.parsley();
  if (loan_list.isValid()) {
    $(".btn-add-loan").text("กำลังบันทึก...").prop("disabled", true); // 🔒 Disable ปุ่ม
    $.ajax({
      url: serverUrl + "/loan/addFinx",
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
          $(".btn-add-loan").text("บันทึก").prop("disabled", false); // 🔓 เปิดใช้งานปุ่มอีกครั้ง
        } else {
          notif({
            type: "success",
            msg: "เพิ่มสินเชื่อสำเร็จ!",
            position: "right",
            fade: true,
            time: 300,
          });

          // ส่งข้อมูลไปยัง Google Sheets ผ่าน GAS
          fetch(
            "https://script.google.com/macros/s/AKfycby09PegXsfb_1SF7mZbyyAdY_zygCj6Cq8cuzcPdtPubcUETmEY5EsvZPl-KL5Jj1Lo/exec",
            {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
              },
              body: JSON.stringify({
                loan_code: response.loan_code,
                latitude: " ", //ตอนส่งไปต้องมีค่าไป
                longitude: " ", //ตอนส่งไปต้องมีค่าไป
                customer_name: response.customer_name,
                loan_number: response.loan_number,
                loan_area: response.loan_area,
                loan_without_vat: response.loan_without_vat,
              }),
              mode: "no-cors", // ใช้โหมด no-cors
            }
          )
            .then((response) => {
              // ไม่สามารถเข้าถึงเนื้อหาของคำตอบได้ในโหมดนี้
              // console.log('Request sent');
            })
            .catch((error) => {
              console.error("Error:", error);
            });

          form.parsley().reset();
          form[0].reset();
          $(".btn-add-loan").text("บันทึก").prop("disabled", false); // 🔓 เปิดใช้งานอีกครั้ง
          $("#modalAddFinx").modal("hide");
          callTableFinx();
        }
      },
    });
  } else {
    loan_list.validate();
    $(".btn-add-loan").text("บันทึก").prop("disabled", false); // 🔓 เปิดใช้งานอีกครั้ง
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

$(".modalAddFinxClose").click(function () {
  let modalAddFinx = $("#modalAddFinx");
  let form = modalAddFinx.find("form");
  form.parsley().reset();
  form[0].reset();
  $(".btn-add-loan").text("บันทึก");
  $("#modalAddFinx").modal("hide");
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
function slowSummarizeFinx() {
  $.ajax({
    type: "POST",
    url: `/finx/ajax-summarizeFinx`,
    contentType: "application/json; charset=utf-8",
    success: function (res) {
      if (res.success) {
        let $data_summarizeFinx = res.data_summarizeFinx;
        $("#summarizeFinx").hide().html($data_summarizeFinx).fadeIn("slow");

        let $data_SummarizeFinx = res.data_SummarizeFinx;
        $("#SummarizeFinx").hide().html($data_SummarizeFinx).fadeIn("slow");
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

$(document).ready(function () {
  // เมื่อ checkbox มีการเปลี่ยนค่า ให้ส่งข้อมูลไปอัปเดตฐานข้อมูล
  $("#tableFinxOn tbody").on("change", ".row-check", function () {
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
