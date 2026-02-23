(function ($) {
  flatpickr("#date_to_loan", {
    disableMobile: true,
  });
  flatpickr("#date_to_loan_pay_date", {
    disableMobile: true,
  });

  // callTableLoan();
  // callTableLoanPayments();
})(jQuery);
let currentRangeType = null;
$(document).ready(function () {
  flatpickr("#daterange_loan", {
    mode: "range",
    dateFormat: "Y-m-d",
    disableMobile: true,
    onChange: function (selectedDates, dateStr, instance) {
      // ถ้า user เลือกวันที่เอง (ไม่ใช่มาจากปุ่มลัด)
      if (instance.isOpen) {
        resetQuickRangeButtons();
      }

      callTableLoan();
    },
  });
  slowSummarizeLoan();
  callTableLoan();
});

$(document).ready(function () {
  flatpickr("#daterange_loan_payments", {
    mode: "range",
    dateFormat: "Y-m-d",
    disableMobile: true,
    onChange: function () {
      callTableLoanPayments();
    },
  });

  callTableLoanPayments();
});

$(document).on("click", ".js-loan-type", function () {
  $(this).toggleClass("active");
  callTableLoan();
});

function getLoanTypesSelected() {
  const types = $(".js-loan-type.active")
    .map(function () {
      return $(this).data("type");
    })
    .get();

  return types; // [] = all
}

function resetQuickRangeButtons() {
  $(".js-range").removeClass("btn-primary").addClass("btn-outline-primary");
}

function getRangeByKeyLoanOn(key) {
  const now = new Date();

  let start = null,
    end = null;

  if (key === "this_month") {
    start = new Date(now.getFullYear(), now.getMonth(), 1);
    end = new Date(now.getFullYear(), now.getMonth() + 1, 0);
  }

  if (key === "last_month") {
    const d = new Date(now.getFullYear(), now.getMonth() - 1, 1);
    start = new Date(d.getFullYear(), d.getMonth(), 1);
    end = new Date(d.getFullYear(), d.getMonth() + 1, 0);
  }

  if (key === "this_year") {
    start = new Date(now.getFullYear(), 0, 1);
    end = new Date(now.getFullYear(), 11, 31);
  }

  if (key === "last_year") {
    start = new Date(now.getFullYear() - 1, 0, 1);
    end = new Date(now.getFullYear() - 1, 11, 31);
  }

  if (key === "today") {
    start = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    end = new Date(now.getFullYear(), now.getMonth(), now.getDate());
  }

  if (key === "tomorrow") {
    const t = new Date();
    t.setDate(t.getDate() + 1);

    start = new Date(t.getFullYear(), t.getMonth(), t.getDate());
    end = new Date(t.getFullYear(), t.getMonth(), t.getDate());
  }

  return { start, end };
}

$(document).on("click", ".js-range", function () {
  const key = $(this).data("range");
  const isDayBtn = key === "today" || key === "tomorrow";

  const fp = document.querySelector("#daterange_loan")._flatpickr;
  const now = new Date();
  if (isDayBtn) {
    // ล้างปุ่มเดือน/ปี
    $(".js-range")
      .not('[data-range="today"],[data-range="tomorrow"]')
      .removeClass("btn-primary")
      .addClass("btn-outline-primary");

    // toggle ปุ่มที่กด
    $(this).toggleClass("btn-primary btn-outline-primary");
  } else {
    // ล้าง today/tomorrow
    $('.js-range[data-range="today"], .js-range[data-range="tomorrow"]')
      .removeClass("btn-primary")
      .addClass("btn-outline-primary");

    // active ปุ่มเดียว
    $(".js-range")
      .not(this)
      .removeClass("btn-primary")
      .addClass("btn-outline-primary");

    $(this).removeClass("btn-outline-primary").addClass("btn-primary");
  }
  const todayActive = $('.js-range[data-range="today"]').hasClass(
    "btn-primary",
  );
  const tomorrowActive = $('.js-range[data-range="tomorrow"]').hasClass(
    "btn-primary",
  );

  let start = null;
  let end = null;

  if (todayActive && tomorrowActive) {
    currentRangeType = "today_tomorrow";

    const tomorrow = new Date();
    tomorrow.setDate(now.getDate() + 1);

    start = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    end = new Date(
      tomorrow.getFullYear(),
      tomorrow.getMonth(),
      tomorrow.getDate(),
    );
  } else if (todayActive) {
    currentRangeType = "today";

    start = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    end = start;
  } else if (tomorrowActive) {
    currentRangeType = "tomorrow";

    const t = new Date();
    t.setDate(t.getDate() + 1);

    start = new Date(t.getFullYear(), t.getMonth(), t.getDate());
    end = start;
  } else {
    currentRangeType = key;

    if (key === "all") {
      fp.clear();
      callTableLoan();
      return;
    }

    const range = getRangeByKeyLoanOn(key);

    start = range.start;
    end = range.end;
  }

  if (start && end) {
    fp.setDate([start, end], true);
  }
});

var count_loan = 0;

function getPlannedInstallments(
  loan_installment_date,
  total_installments = null,
) {
  const start = new Date(loan_installment_date); // วันเริ่มงวดแรก
  const now = new Date(); // วันนี้

  // ถ้ายังไม่ถึงงวดแรก
  if (now < start) return 0;

  // ส่วนต่างเป็นจำนวนเดือนแบบปฏิทิน
  let monthsDiff =
    (now.getFullYear() - start.getFullYear()) * 12 +
    (now.getMonth() - start.getMonth());

  // ถ้าวันนี้เลย/เท่ากับ "วันครบกำหนด" ของเดือนนี้ ให้รวมงวดเดือนนี้ด้วย
  let planned = monthsDiff + (now.getDate() >= start.getDate() ? 1 : 0);

  // กันพลาด (ต้องไม่ต่ำกว่า 0)
  if (planned < 0) planned = 0;

  // ถ้าระบุจำนวนงวดทั้งหมด ก็ cap ไม่ให้เกิน
  if (total_installments !== null) {
    planned = Math.min(planned, total_installments);
  }

  return planned;
}

function callTableLoan() {
  $("#tableLoanOn").DataTable().clear().destroy();

  const date = $("#daterange_loan").val() || "";
  const loan_types = getLoanTypesSelected();

  $.ajax({
    url: serverUrl + "/loan/tableLoan",
    dataType: "json",
    type: "POST",
    data: {
      date: date,
      loan_types: loan_types,
      range_type: currentRangeType,
    },
    success: function (response) {
      var result = JSON.parse(response.message);
      result.forEach(statusLoan);

      $("#count_car").html(
        '<div class="tx-primary tx-18" id="count_car">รายการสินเชื่อที่ยังไม่ปิด (' +
          count_loan +
          " ราย)</div>",
      );
      count_loan = 0;

      callAutoloenTable(result);
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
              Number(data["loan_summary_no_vat"]).toFixed(2),
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
        render: function (data, type, row, meta) {
          if (data["loan_status"] == "ON_STATE") {
            const date = new Date(data["loan_payment_date_fix"]);
            const newDate = new Date(
              date.setMonth(date.getMonth() + (data["loan_period"] - 1)),
            );

            const daysPassed = Math.floor(
              (Date.now() - newDate) / (1000 * 60 * 60 * 24),
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
              date.setMonth(date.getMonth() + (data["loan_period"] - 1)),
            );

            const daysPassed = Math.floor(
              (Date.now() - newDate) / (1000 * 60 * 60 * 24),
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
              date.setMonth(date.getMonth() + (data["loan_period"] - 1)),
            );

            const daysPassed = Math.floor(
              (Date.now() - newDate) / (1000 * 60 * 60 * 24),
            );
            var installment = data["loan_payment_year_counter"] * 12;
            const remaining_installments =
              installment - data["loan_payment_type"];

            if (daysPassed > 0) {
              const loan_overdue =
                Number(data["loan_overdue"].replace(/[^0-9.-]+/g, "")) + 1;
              const overdue_months = Math.min(
                loan_overdue,
                remaining_installments,
              );
              const loan_overdue_sum =
                overdue_months * data["loan_payment_month"];
              // var loan_overdue_sum =
              //   data["loan_payment_month"] * (loan_overdue);
              return (
                "<font class='tx-danger'>" +
                new Intl.NumberFormat().format(
                  Number(loan_overdue_sum).toFixed(2),
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
        className: "text-center",
        render: function (data, type, row, meta) {
          const loan_payment_month_counter =
            data["loan_payment_year_counter"] * 12;
          const totalProfit =
            data["loan_payment_month"] * loan_payment_month_counter;

          if (!totalProfit || totalProfit === 0) {
            return `<font class='tx-secondary'>-</font>`;
          }
          const ctdRealized =
            (data["loan_payment_sum_installment"] / totalProfit) * 100;
          const plannedInstallments = getPlannedInstallments(
            data["loan_installment_date"],
            loan_payment_month_counter,
          );
          const plannedAmount =
            plannedInstallments * data["loan_payment_month"];
          const ctdPlanned = (plannedAmount / totalProfit) * 100;
          const gap = ctdPlanned - ctdRealized;
          const gapFormatted = new Intl.NumberFormat().format(gap.toFixed(2));

          let color = "";

          if (gap < 0) {
            color = "tx-info"; // จ่ายไว
          } else if (gap === 0) {
            color = "tx-success"; // ตรงแผน
          } else if (gap > 0 && gap <= 10) {
            color = "tx-secondary"; // ช้านิดหน่อย
          } else {
            color = "tx-danger"; // ช้าหนัก
          }
          return `<font class='${color}'>${gapFormatted}%</font>`;
        },
      },
      {
        data: null,
        className: "text-right",
        render: function (data, type, row, meta) {
          return (
            "<font class='tx-success'>" +
            new Intl.NumberFormat().format(
              Number(data["loan_payment_sum_installment"]).toFixed(2),
            ) +
            "</font>"
          );
        },
      },
      {
        data: "Late",
        className: "text-center",
        render: function (data) {
          let late = Math.max(0, Math.floor(Number(data) || 0));
          let color = "";

          if (late === 0) {
            color = "tx-success"; // เขียว = ปกติ
          } else if (late <= 30) {
            color = "tx-secondary"; // เหลือง = เริ่มเสี่ยง
          } else if (late <= 90) {
            color = "tx-orange"; // ส้ม = เสี่ยง
          } else {
            color = "tx-danger"; // แดง = อันตราย
          }

          return `<span class="${color}">${late} วัน</span>`;
        },
      },
      {
        data: null,
        className: "text-center",
        render: function (data, type, row, meta) {
          var roi =
            (data["loan_payment_sum_installment"] /
              data["loan_summary_no_vat"]) *
            100;
          var roiFormatted = new Intl.NumberFormat().format(
            Number(roi.toFixed(2)),
          );

          // กำหนดสีตามเงื่อนไข
          let color = "";

          if (roi > 100) {
            color = "tx-info";
          } else if (roi >= 50) {
            color = "tx-success";
          } else {
            color = "tx-secondary";
          }

          return `<font class='${color}'>${roiFormatted}%</font>`;
        },
      },
      {
        data: null,
        className: "text-right",
        render: function (data, type, row, meta) {
          return (
            "<font>" +
            new Intl.NumberFormat().format(
              Number(data["loan_payment_month"]).toFixed(2),
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
              date.setMonth(date.getMonth() + (data["loan_period"] - 1)),
            );

            const daysPassed = Math.floor(
              (Date.now() - newDate) / (1000 * 60 * 60 * 24),
            );
            var installment = data["loan_payment_year_counter"] * 12;
            const remaining_installments =
              installment - data["loan_payment_type"];

            const loan_overdue =
              Number(data["loan_overdue"].replace(/[^0-9.-]+/g, "")) + 1;
            const overdue_months = Math.min(
              loan_overdue,
              remaining_installments,
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
        .column(17, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b.loan_payment_month); // Handle formatted numbers
        }, 0);

      // Total_sum_remaining_payment = api
      //   .column(14, { page: "current" })
      //   .data()
      //   .reduce(function (a, b) {
      //     return (
      //       intVal(a) +
      //       (intVal(b.loan_sum_interest) -
      //         intVal(b.loan_payment_sum_installment))
      //     );
      //   }, 0);

      Total_summary_no_vat = api
        .column(8, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b.loan_summary_no_vat);
        }, 0);

      Total_loanOverdueSum = api
        .column(12, { page: "current" })
        .data()
        .reduce(function (a, b) {
          const date = new Date(b.loan_payment_date_fix);
          const newDate = new Date(
            date.setMonth(date.getMonth() + (b.loan_period - 1)),
          );

          const daysPassed = Math.floor(
            (Date.now() - newDate) / (1000 * 60 * 60 * 24),
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
        .column(14, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b.loan_payment_sum_installment);
        }, 0);

      // Update footer
      number_payment_month = parseFloat(Total_payment_month).toFixed(2);
      $(api.column(17).footer()).html(
        Number(number_payment_month).toLocaleString(),
      );

      // number_sum_remaining_payment = parseFloat(
      //   Total_sum_remaining_payment
      // ).toFixed(2);
      // $(api.column(14).footer()).html(
      //   Number(number_sum_remaining_payment).toLocaleString()
      // );

      number_payment_sum_installment = parseFloat(
        Total_payment_sum_installment,
      ).toFixed(2);
      $(api.column(14).footer()).html(
        Number(number_payment_sum_installment).toLocaleString(),
      );

      number_loanOverdueSum = parseFloat(Total_loanOverdueSum).toFixed(2);
      $(api.column(12).footer()).html(
        Number(number_loanOverdueSum).toLocaleString(),
      );

      number_summary_no_vat = parseFloat(Total_summary_no_vat).toFixed(2);
      $(api.column(8).footer()).html(
        Number(number_summary_no_vat).toLocaleString(),
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

  let imageFile = $("#imageFile")[0].files[0];
  if (imageFile) {
    formData.append("imageFile", imageFile);
  }

  var loan_list = form.parsley();
  if (loan_list.isValid()) {
    $(".btn-add-loan").text("กำลังบันทึก...").prop("disabled", true); // 🔒 Disable ปุ่ม
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
            },
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
          $("#modalAddLoan").modal("hide");
          callTableLoan();
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

  const date = $("#daterange_loan_payments").val() || "";

  $.ajax({
    url: serverUrl + "/loan/tableLoanPayments",
    dataType: "json",
    type: "get",
    data: { date: date },
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
              Number(data["setting_land_report_money"]).toFixed(2),
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
              Number(data["setting_land_report_account_balance"]).toFixed(2),
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

$(document).ready(function () {
  // ✅ ตรวจสอบว่าเป็น iOS หรือไม่
  function isIos() {
    return /iPhone|iPad|iPod/i.test(navigator.userAgent);
  }

  if (isIos()) {
    $("#btnAiAutoInputCapture").show();
    $("#btnAiAutoInput").show();
  } else {
    $("#btnAiAutoInputCapture").hide();
    $("#btnAiAutoInput").show();
  }

  // ซ่อนฟอร์ม OCR ทุกครั้งที่โหลดหน้า
  $("#detectImageForm").hide();

  // ปุ่มเลือกไฟล์
  $("#btnAiAutoInput").on("click", function () {
    $("#imageFile").click();
  });

  // เก็บไฟล์จากการถ่ายรูป (input แบบ dynamic)
  let _capturedFile = null;

  // ปุ่มถ่ายรูป (iOS)
  $("#btnAiAutoInputCapture").on("click", function () {
    $("<input>")
      .attr({
        type: "file",
        accept: "image/*",
        capture: "camera",
      })
      .on("change", function () {
        if (this.files && this.files[0]) {
          _capturedFile = this.files[0]; // เก็บไว้ส่ง OCR
          $("#detectImageForm").show();
          setImagePreviewFromFile(this.files[0]);
        }
      })
      .click();
  });

  // เมื่อเลือกไฟล์แล้ว แสดงฟอร์ม OCR
  $("#imageFile").on("change", function () {
    _capturedFile = null; // ถ้าเลือกไฟล์ปกติ เคลียร์ไฟล์จากกล้อง
    if (this.files && this.files[0]) {
      $("#detectImageForm").show();
      setImagePreview();
    }
  });

  // ปุ่มยกเลิก
  $("#btnAiAutoInputClear").on("click", function () {
    $("#detectImageForm").hide();
    $("#imageFile").val("");
    $("#imagePreview").attr("src", "");
    _capturedFile = null;
  });

  const imageFile = document.querySelector("#imageFile");
  const imagePreview = document.querySelector("#imagePreview");

  const setImagePreview = async () => {
    const imageBase64String = await getImageBase64String();
    imagePreview.setAttribute("src", imageBase64String);
  };

  const setImagePreviewFromFile = async (file) => {
    const base64 = await toBase64(file);
    imagePreview.setAttribute("src", base64);
  };

  const detectImage = async () => {
    let $form = $("#customerSection");

    $("#btnAiAutoInputClear").addClass("disabled");
    $("#btnAiAutoInputSubmit").addClass("disabled");

    try {
      const imageBase64String = await getImageBase64String();
      const pureBase64 = imageBase64String.replace(/^data:.+;base64,/, "");

      const csrfName = $("meta[name='csrf_name']").attr("content");
      const csrfHash = $("meta[name='csrf_hash']").attr("content");
      const postData = { image_base64: pureBase64 };
      if (csrfName && csrfHash) postData[csrfName] = csrfHash;

      $.ajax({
        url: "/loan/ocrCustomer",
        type: "POST",
        dataType: "json",
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(postData),
        success: function (res) {
          if (res.status !== "success") {
            alert(res.message || "OCR error");
            return;
          }

          let resData = res.text || "";

          const bundleData = {};
          resData.split("\n").forEach((row, index, arr) => {
            let items = row.split(" ");

            // เลขบัตรประชาชน
            const digits = items.join("").replace(/\D/g, "");
            const matchID = digits.match(/\d{13}/);
            if (matchID) bundleData.cardNumber = matchID[0];

            // ชื่อ-นามสกุล
            if (row.includes("ชื่อตัวและชื่อสกุล")) {
              bundleData.prename = items[1];
              bundleData.firstname = items[2];
              bundleData.lastname = items[3];
            }

            // วันเกิด
            if (row.includes("Date of Birth")) {
              bundleData.birthDate = `${items[3]} ${items[4]} ${items[5]}`;
            }

            // ที่อยู่
            if (row.includes("ที่อยู่")) {
              bundleData.address = `${items.join(" ")} ${arr[index + 1] || ""}`;
            }
          });

          if (bundleData.prename) {
            bundleData.gender = ["น.ส.", "นางสาว", "นาง", "เด็กหญิง"].includes(
              bundleData.prename,
            )
              ? "หญิง"
              : "ชาย";
          }

          // ✅ ใส่ค่าลงฟอร์ม + ไฮไลท์ valid
          if (bundleData.firstname || bundleData.lastname) {
            let $inputName = $form.find("input[name=fullname]");
            $inputName
              .val(
                `${bundleData.firstname || ""} ${
                  bundleData.lastname || ""
                }`.trim(),
              )
              .removeClass("is-invalid")
              .addClass("is-valid");
          }

          if (bundleData.cardNumber) {
            let $inputCardID = $form.find("input[name=card_id]");
            $inputCardID
              .val(bundleData.cardNumber)
              .removeClass("is-invalid")
              .addClass("is-valid");
            $(".cardIDMask").mask("9-9999-99999-99-9");
          }

          if (bundleData.birthDate) {
            let d = new Date(bundleData.birthDate);
            let a = moment(d).format("DD/MM/YYYY");
            if (a !== "Invalid date") {
              let $inputBirthday = $form.find("input[name=birthday]");
              $inputBirthday
                .val(a)
                .removeClass("is-invalid")
                .addClass("is-valid");
              $(".dateMask").mask("99/99/9999");
            }
          }

          if (bundleData.gender) {
            let $inputGender = $form.find("select[name=gender]");
            $inputGender
              .val(bundleData.gender)
              .removeClass("is-invalid")
              .addClass("is-valid");
          }

          if (bundleData.address) {
            let $inputAddress = $form.find("textarea[name=address]");
            $inputAddress
              .val(bundleData.address)
              .removeClass("is-invalid")
              .addClass("is-valid");
          }
        },
        error: function (xhr) {
          console.error(xhr.responseText || xhr.statusText);
          alert("ไม่สามารถทำ OCR ได้ กรุณาลองใหม่ภายหลัง");
        },
        complete: function () {
          $("#detectImageForm").hide();
          $("#btnAiAutoInputClear").removeClass("disabled");
          $("#btnAiAutoInputSubmit").removeClass("disabled");
        },
      });
    } catch (err) {
      console.error(err);
      alert("ไม่สามารถทำ OCR ได้ กรุณาลองใหม่ภายหลัง");
      $("#detectImageForm").hide();
      $("#btnAiAutoInputClear").removeClass("disabled");
      $("#btnAiAutoInputSubmit").removeClass("disabled");
    }
  };

  const getImageBase64String = async () => {
    const file = _capturedFile || imageFile.files[0];
    if (!file) throw new Error("no file selected");
    return await toBase64(file);
  };

  const toBase64 = (file) =>
    new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => resolve(reader.result);
      reader.onerror = (error) => reject(error);
    });

  // กดบันทึก
  $("#btnAiAutoInputSubmit").on("click", function () {
    if ($(this).hasClass("disabled")) return;
    detectImage();
  });
});
