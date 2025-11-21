var loan_stock_name;
var installments = 0;
var paymentPermonth = 0;
var loan_payment_sum_installment = 0;
var typePayment;
var payNow = 0;

var loan_period = 0;
var loan_installment_date = 0;

(function ($) {
  let searchParams = window.location.pathname;
  var searchParams_ = searchParams.split("/loan/detail/");

  flatpickr("#date_to_loan", {
    disableMobile: true,
  });

  flatpickr("#date_to_loan_pay_date", {
    disableMobile: true,
  });

  flatpickr("#date_to_payment", {
    disableMobile: true,
  });

  $.ajax({
    url: serverUrl + "/loan/fetchOtherPicture/" + searchParams_[1],
    method: "get",
    async: false,
    success: function (response) {
      $("#other_picture").html(response.message);
    },
  });

  loadLoan(searchParams_[1]);
  installmentTab();
  dataTablePaymentDetail();

  new SmartPhoto(".js-img-viewer", {
    resizeStyle: "fit",
  });

  new SmartPhoto(".js-img-viewer-other", {
    resizeStyle: "fit",
  });

  $(".input-other-images").imageUploader();
})(jQuery);

function dateDiff(date_now, date_stock) {
  const diffTime = Math.abs(date_now - date_stock);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  const result = diffDays - 1;

  return '<font style="color: #FEAD00;"> ' + result + " วัน </font>";
}

function loadLoan(loanCode) {
  var loanCode_ = loanCode.split("###");
  $.ajax({
    url: serverUrl + "/loan/detailForm/" + loanCode_[0],
    method: "get",
    async: false,
    success: function (response) {
      //   $("#id_update").val(response.message[0].car_stock_code);
      $("#payment_name").val(response.message.loan_customer);
      $("#customer_name").val(response.message.loan_customer);
      $("#employee_name").val(response.message.loan_employee);
      $("#payment_employee_name").val(response.message.loan_employee);
      $("#loan_address").val(response.message.loan_address);
      $("#loan_number").val(response.message.loan_number);
      $("#loan_area").val(response.message.loan_area);

      $("#fullname").val(response.message.customer_fullname);
      $("#phone").val(response.message.customer_phone);
      $("#card_id").val(response.message.customer_card_id);
      $(".cardIDMask").mask("9-9999-99999-99-9");
      $("#customer_email").val(response.message.customer_email);
      $("#birthday").val(
        moment(response.message.customer_birthday).format("DD/MM/YYYY")
      );
      $(".dateMask").mask("99/99/9999");
      $("#gender").val(response.message.customer_gender);
      $("#address").val(response.message.customer_address);

      $("#date_to_loan").val(response.message.loan_date_promise);
      $("#date_to_loan_pay_date").val(response.message.loan_installment_date);
      $("#loan_without_vat").val(response.message.loan_summary_no_vat);
      $("#money_loan").val(response.message.loan_summary_no_vat);
      $("#payment_year_counter").val(
        response.message.loan_payment_year_counter
      );
      $("#pricePerMonth").val(response.message.loan_payment_month);
      $("#total_loan").val(response.message.loan_summary_all);
      $("#total_loan_interest").val(response.message.loan_sum_interest);
      $("#payment_interest").val(response.message.loan_payment_interest);
      $("#charges_process").val(response.message.loan_payment_process);
      $("#charges_transfer").val(response.message.loan_tranfer);
      $("#charges_etc").val(response.message.loan_payment_other);
      $("#remark").val(response.message.loan_remnark);
      $("#really_pay_loan").val(response.message.loan_really_pay);
      $("#loan_code").val(response.message.loan_code);

      $("#link_map").val(response.message.link_map);

      let loanAmount = Number(response.message.loan_summary_no_vat);
      let loanInterest = (loanAmount * 0.03).toFixed(2);
      let totalLoan = (loanAmount + parseFloat(loanInterest)).toFixed(2);

      $("#loan_amount").val(loanAmount.toFixed(2)); // ✅ เงินต้น ไม่มี comma
      $("#loan_interest_amount").val(loanInterest); // ✅ ดอกเบี้ย ไม่มี comma
      $("#total_loan_amount").val(totalLoan); // ✅ รวมทั้งหมด ไม่มี comma

      loan_stock_name = response.message.loan_stock_name;

      calInstallment(
        response.message.loan_payment_month,
        response.message.loan_payment_year_counter * 12
      );

      loan_period = response.message.loan_period;
      loan_installment_date = response.message.loan_installment_date;

      installments = response.message.loan_payment_year_counter;
      paymentPermonth = response.message.loan_payment_month;
      loan_payment_sum_installment =
        response.message.loan_payment_sum_installment;

      if (response.message.loan_status == "CLOSE_STATE") {
        $("#btn_edit_detail_").hide();
      }

      $("#total_loan_payment").val(response.message.loan_sum_interest);

      $("#pay_sum").val(
        Number(loan_payment_sum_installment.replace(/[^0-9.-]+/g, ""))
      );

      $("#open_loan_payment").val(response.message.loan_summary_no_vat);

      payNow = response.message.loan_payment_month;

      loan_payment_month_counter = response.message.loan_payment_year_counter * 12;

      loan_total_profit = response.message.loan_payment_month * loan_payment_month_counter;

      function getPlannedInstallments(loan_installment_date, total_installments = null) {
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

      let loan_planned =  getPlannedInstallments(loan_installment_date, loan_payment_month_counter);

      loan_planned_payment = response.message.loan_payment_month * loan_planned;

      let loan_ROI = (( loan_payment_sum_installment / loanAmount) * 100).toFixed(2);

      let loan_YTD_Realized = (( loan_payment_sum_installment / loan_total_profit) * 100).toFixed(2);

      let loan_YTD_Planned = (( loan_planned_payment / loan_total_profit) * 100).toFixed(2);

      let loan_duration = 0;
      if(response.message.loan_status !== "CLOSE_STATE"){
        loan_duration = ((loan_payment_month_counter - (loan_period - 1)) / loan_payment_month_counter) * 100;
      }

      $("#loan_roi").val(loan_ROI);
      $("#loan_nim").val(loan_ROI);
      $("#loan_ytd_realized").val(loan_YTD_Realized);
      $("#loan_ytd_planned").val(loan_YTD_Planned);
      $("#loan_ytd_gap").val((loan_YTD_Planned - loan_YTD_Realized).toFixed(2));
      $("#loan_duration").val(loan_duration.toFixed(2));
    },
  });

  $.ajax({
    url: serverUrl + "/loan/callInstallMent/" + loanCode_[1],
    method: "get",
    success: function (response) {
      let installMentCount = parseInt(
        response.message.loan_payment_installment
      );
      $("#installment_count").val(installMentCount);
      $("#payment_id").val(response.message.id);
    },
  });
}

$("#loan_without_vat").keyup(function () {
  $("#money_loan").val(
    new Intl.NumberFormat().format(
      Number($("#loan_without_vat").val()).toFixed(2)
    )
  );
});

$("#edit_loan_detail_btn").click(function () {
  $("#edit_loan_detail_btn").text("กำลังแก้ไข").prop("disabled", true); // 🔒 ปิดการคลิกซ้ำ
  let searchParams = window.location.pathname;
  var searchParams_ = searchParams.split("/loan/detail/");

  let divLoan = $("#detail_loan");
  let formUpDateLoan = divLoan.find("form").attr("id");
  var formData = new FormData(document.getElementById(formUpDateLoan));

  let imageFile = $("#imageFile")[0].files[0];
  if (imageFile) {
    formData.append("imageFile", imageFile);
  }

  $.ajax({
    url: serverUrl + "/loan/updateLoan",
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
          msg: "แก้ไขสินเชื่อไม่สำเร็จ",
          position: "right",
          fade: true,
          time: 300,
        });
        $("#edit_loan_detail_btn").text("บันทึก").prop("disabled", false); // 🔓 เปิดใช้งานอีกครั้ง
      } else {
        notif({
          type: "success",
          msg: "แก้ไขสินเชื่อสำเร็จ!",
          position: "right",
          fade: true,
          time: 300,
        });
        $("#edit_loan_detail_btn").text("บันทึก").prop("disabled", false); // 🔓 เปิดใช้งานอีกครั้ง
        loadLoan(searchParams_[1]);
      }
    },
  });
});

function cancelLoan() {
  let searchParams = window.location.pathname;
  var searchParams_ = searchParams.split("/loan/detail/");

  Swal.fire({
    title: "ยกเลิกรายการ",
    text: "คุณต้องการยกเลิกรายการนี้ !",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "ยกเลิก",
    confirmButtonText: "ตกลง",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: serverUrl + "/loan/cancelLoan/" + searchParams_[1],
        method: "get",
        async: false,
        success: function (response) {
          if (response.error) {
            notif({
              type: "danger",
              msg: "แก้ไขสินเชื่อไม่สำเร็จ",
              position: "right",
              fade: true,
              time: 300,
            });
          } else {
            fetch(
              "https://script.google.com/macros/s/AKfycbycISLS1BJQEBffMcirtCdxwnjLwSmTcmxsGlkx3NEMLrlPO8CJ_KQCloRbwHdifh_cGw/exec",
              {
                method: "POST",
                headers: {
                  "Content-Type": "application/json",
                },
                body: JSON.stringify({
                  loan_code: searchParams_[1], // หมายเลข loan_code ที่ต้องการอัปเดต
                  action: "delete",
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

            notif({
              type: "success",
              msg: "แก้ไขสินเชื่อสำเร็จ!",
              position: "right",
              fade: true,
              time: 1400,
            });

            setTimeout(function () {
              window.location = "/loan/list";
            }, 1 * 1500);
          }
        },
      });
    }
  });
}

function loadPicture() {
  let searchParams = window.location.pathname;
  var searchParams_ = searchParams.split("/loan/detail/");
  // //get picture other by stock code
  $.ajax({
    url: serverUrl + "/loan/fetchOtherPicture/" + searchParams_[1],
    method: "get",
    success: function (response) {
      $("#other_picture").html(response.message);
      new SmartPhoto(
        $(".brick").find($(".brick").find(".js-img-viewer-other")),
        {
          resizeStyle: "fit",
        }
      );
    },
  });
}

// save แก้ไข stock tab2 piture car
$("#AddPicture").submit(function (e) {
  let searchParams = window.location.pathname;
  var searchParams_ = searchParams.split("/loan/detail/");
  e.preventDefault();
  const formData = new FormData(this);
  $("#add_btn_picture").text("กำลังเพิ่มรูปภาพ...").prop("disabled", true); // 🔒 ปิดการคลิกซ้ำ
  $.ajax({
    url: serverUrl + "/loan/insertDetailPiture/" + searchParams_[1],
    method: "post",
    data: formData,
    contentType: false,
    // cache: false,
    processData: false,
    dataType: "json",
    success: function (response) {
      if (response.error) {
      } else {
        $(".input-other-images").html("");
        $(".input-other-images").imageUploader();

        loadPicture();

        notif({
          type: "success",
          msg: "เพิ่มรูปภาพสำเร็จ!",
          position: "right",
          fade: true,
          time: 300,
        });
        $("#add_btn_picture").text("เพิ่มรูปภาพ").prop("disabled", false); // 🔓 เปิดใช้งานอีกครั้ง
      }
    },
  });
});

function deleteOtherPicture(id) {
  $.ajax({
    url: serverUrl + "/loan/delete_other_picture/" + id,
    method: "get",
    success: function (response) {
      if (response.error) {
      } else {
        loadPicture();

        notif({
          type: "success",
          msg: "ลบรูปภาพสำเร็จ!",
          position: "right",
          fade: true,
          time: 300,
        });
      }
    },
  });
}

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

  $pay_count = $pay_count.toFixed(2);

  $sum_all = $dok_total + $loanPrice;

  $("#total_loan_interest").val($dok_total);
  $("#pricePerMonth").val($pay_count);
  $("#total_loan").val($sum_all);

  calInstallment($pay_count, $numYear);
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

function calInstallment(pay_count, numYear) {
  let lableMonth = [];
  let payMonth = [];
  let payMonthBySum = [];

  for (var i = 1; i <= numYear; i++) {
    lableMonth.push("งวด " + i);
  }

  for (var i = 1; i <= numYear; i++) {
    payMonth.push(pay_count);
  }

  for (var i = 1; i <= numYear; i++) {
    payMonthBySum.push(pay_count * i);
  }

  // var chartBarLoan = document.getElementById("chartBarLoan").getContext("2d");
  // new Chart(chartBarLoan, {
  //   type: "bar",
  //   data: {
  //     labels: lableMonth,
  //     datasets: [
  //       {
  //         data: payMonth,
  //         backgroundColor: "#FFA500",
  //       },
  //       {
  //         data: payMonthBySum,
  //         backgroundColor: "#198754",
  //       },
  //     ],
  //   },
  //   options: {
  //     maintainAspectRatio: false,
  //     legend: {
  //       display: false,
  //       labels: {
  //         display: false,
  //       },
  //     },
  //     scales: {
  //       yAxes: [
  //         {
  //           ticks: {
  //             beginAtZero: true,
  //             fontSize: 11,
  //             fontColor: "rgba(171, 167, 167,0.9)",
  //           },
  //           gridLines: {
  //             display: true,
  //             color: "rgba(171, 167, 167,0.2)",
  //             drawBorder: false,
  //           },
  //         },
  //       ],
  //       xAxes: [
  //         {
  //           ticks: {
  //             beginAtZero: true,
  //             fontSize: 11,
  //             max: 80,
  //             fontColor: "rgba(171, 167, 167,0.9)",
  //           },
  //           gridLines: {
  //             display: true,
  //             color: "rgba(171, 167, 167,0.2)",
  //             drawBorder: false,
  //           },
  //         },
  //       ],
  //     },
  //   },
  // });
}

function commaSeparateNumber(val) {
  while (/(\d+)(\d{3})/.test(val.toString())) {
    val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
  }
  return val;
}

$(".modalPaymentLoanClose").click(function () {
  let modalPayLoan = $("#modalPayLoan");
  let form = modalPayLoan.find("form");
  form.parsley().reset();
  form[0].reset();
  $(".btn-add-loan-payment").text("บันทึก");
  $(".PaymentLoanType1").addClass("active");
  $(".PaymentLoanType2").removeClass("active");
  $("#modalPayLoan").modal("hide");
});

$("#tablePayment").on("click", ".paymentBTN", function () {
  let searchParams = window.location.pathname;
  var searchParams_ = searchParams.split("/loan/detail/");

  var id_install = "";

  id_install = $(this).attr("id");

  // console.log(id_install);

  $("#modalPayLoan").modal("show");

  loadLoan(searchParams_[1] + "###" + id_install);
  $("#installment_bar").addClass("show");
  installmentTab();
});

$("#payment_now").keyup(function () {
  let pay_now = $("#payment_now").val();

  pay_now = Number(pay_now.replace(/[^0-9.-]+/g, ""));

  let sum_pay =
    pay_now + Number(loan_payment_sum_installment.replace(/[^0-9.-]+/g, ""));

  $("#pay_sum").val(sum_pay);

  $("#close_loan_payment").val(pay_now);
});

$(document).delegate(".btn-add-loan-payment", "click", function (e) {
  let searchParams = window.location.pathname;
  var searchParams_ = searchParams.split("/loan/detail/");

  $("#codeloan_hidden").val(searchParams_[1]);

  let modalPayLoan = $("#modalPayLoan");
  let formAddLoanPay = modalPayLoan.find("form").attr("id");
  let form = modalPayLoan.find("form");
  var formData = new FormData(document.getElementById(formAddLoanPay));

  const date = new Date(loan_installment_date);
  const newDate = new Date(date.setMonth(date.getMonth() + (loan_period - 1)));

  const overdue_days = Math.floor(
    (Date.now() - newDate) / (1000 * 60 * 60 * 24)
  );

  let overdueColor;
  if (overdue_days === 0) {
    overdueColor = "tx-success"; // สีเขียวสำหรับวันที่เกินกำหนด = 0
  } else if (overdue_days >= 1 && overdue_days <= 90) {
    overdueColor = "tx-secondary"; // สีเหลืองสำหรับวันที่เกินกำหนด 1-90 วัน
  } else {
    overdueColor = "tx-danger"; // สีแดงสำหรับวันที่เกินกำหนดมากกว่า 90 วัน
  }

  let installmentValue = parseInt($("#installment_count").val(), 10);

  var loan_payment = form.parsley();

  if (loan_payment.isValid()) {
    if (
      !isNaN(installmentValue) &&
      installmentValue % 12 === 0 &&
      installmentValue !== 0
    ) {
      // หากเป็นค่า mod 12 เท่ากับ 0
      Swal.fire({
        title: "ชำระสินเชื่อ",
        html: `คุณต้องการผ่อนต่อหรือปิดบัญชี? <span class="${overdueColor}">(เกินกำหนดชำระ ${overdue_days} วัน)</span>`,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "ผ่อนต่อ",
        cancelButtonText: "ปิดบัญชี",
      }).then((result) => {
        if (result.isConfirmed) {
          // ผู้ใช้เลือก "ผ่อนต่อ"
          formData.append("status_payment", "continue");
          proceedLoanPayment(formData, form);
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          // ผู้ใช้กดปุ่ม "ปิดบัญชี" หรือคลิกข้างนอก
          // ส่งข้อมูลและทำการบันทึกเหมือนกัน
          notif({
            type: "warning",
            msg: '<span style="color: black;">คุณเลือกที่จะดำเนินการปิดบัญชีสินเชื่อ</span>',
            position: "right",
            fade: true,
            time: 300,
          });
          form.parsley().reset();
          form[0].reset();
          $("#modalPayLoan").modal("hide");
          // formData.append("status_payment", "close");
          // proceedLoanPayment(formData, form);
        } else {
          // หากผู้ใช้คลิกข้างนอก (result.dismiss === Swal.DismissReason.backdrop)
          notif({
            type: "warning",
            msg: '<span style="color: black;">คุณเลือกที่จะยกเลิกการดำเนินการชำระสินเชื่อ</span>',
            position: "right",
            fade: true,
            time: 300,
          });
        }
      });
    } else {
      // ถ้าไม่เป็น mod 12 เท่ากับ 0 (ผ่อนงวดที่ไม่เป็น mod 12)
      formData.append("status", "default"); // หรือค่าที่เหมาะสม
      proceedLoanPayment(formData, form);
    }
  } else {
    loan_payment.validate();
    $(".btn-add-loan-payment").text("บันทึก");
  }
});
// ฟังก์ชันดำเนินการบันทึก
function proceedLoanPayment(formData, form) {
  $(".btn-add-loan-payment").text("กำลังบันทึก...").prop("disabled", true); // 🔒 ปิดการคลิกซ้ำ
  $.ajax({
    url: serverUrl + "/loan/addPayment",
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
          msg: "จ่ายสินเชื่อไม่สำเร็จ",
          position: "right",
          fade: true,
          time: 300,
        });
        $(".btn-add-loan-payment").text("บันทึก").prop("disabled", false); // 🔓 เปิดใช้งานอีกครั้ง
      } else {
        if (response.payment_type == "CloseLoan") {
          let searchParams = window.location.pathname;
          var searchParams_ = searchParams.split("/loan/detail/");

          fetch(
            "https://script.google.com/macros/s/AKfycbycISLS1BJQEBffMcirtCdxwnjLwSmTcmxsGlkx3NEMLrlPO8CJ_KQCloRbwHdifh_cGw/exec",
            {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
              },
              body: JSON.stringify({
                loan_code: searchParams_[1], // หมายเลข loan_code ที่ต้องการอัปเดต
                action: "delete",
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
        }

        notif({
          type: "success",
          msg: "จ่ายสินเชื่อสำเร็จ!",
          position: "right",
          fade: true,
          time: 300,
        });

        form.parsley().reset();
        form[0].reset();
        $(".btn-add-loan-payment").text("บันทึก").prop("disabled", false); // 🔓 เปิดใช้งานอีกครั้ง
        $(".PaymentLoanType1").addClass("active");
        $(".PaymentLoanType2").removeClass("active");
        $("#modalPayLoan").modal("hide");
        dataTablePaymentDetail();
      }
    },
  });
}

function installmentTab() {
  typePayment = "Installment";
  $("#payment_type").val(typePayment);
  $("#installment_bar").addClass("show");
  $("#pay_sum_loan").addClass("show");
  $("#pay_close_loan_tab").removeClass("show");

  $("#pay_sum").val("");

  let sum_pay_installment =
    Number(payNow) +
    Number(loan_payment_sum_installment.replace(/[^0-9.-]+/g, ""));

  if (loan_payment_sum_installment == 0) {
    $("#pay_sum").val(Number(payNow));
  } else {
    $("#pay_sum").val(sum_pay_installment);
  }
  // $("#payment_now").val(payNow);
  $("#price_month").html("<font>" + payNow + "</font>");
  $("#payment_now").val(payNow);

  $("#payment_now").attr("readonly", false);
}

function closeTab() {
  typePayment = "Close";
  $("#payment_type").val(typePayment);
  $("#installment_bar").removeClass("show");
  $("#pay_sum_loan").addClass("show");
  $("#pay_close_loan_tab").removeClass("show");

  $("#pay_sum").val("");

  let total_loan_payment = $("#total_loan_payment").val();
  let pay_sum = Number(loan_payment_sum_installment.replace(/[^0-9.-]+/g, ""));

  let close_pay = total_loan_payment - pay_sum;
  let close_sum_pay = total_loan_payment + pay_sum;
  $("#payment_now").val(close_pay);
  $("#pay_sum").val(Number(total_loan_payment));
  $("#price_month").html("<font>" + close_pay + "</font>");
}

function closeLoanTab() {
  typePayment = "CloseLoan";
  $("#payment_type").val(typePayment);
  $("#installment_bar").removeClass("show");
  $("#pay_sum_loan").removeClass("show");
  $("#pay_close_loan_tab").addClass("show");

  $("#pay_sum").val("");

  let open_loan_payment = $("#loan_without_vat").val();
  let pay_sum = Number(open_loan_payment.replace(/[^0-9.-]+/g, ""));

  let close_pay = $("#loan_without_vat").val();
  let close_sum_pay = open_loan_payment + pay_sum;
  $("#payment_now").val(close_pay);
  $("#close_loan_payment").val(Number(close_pay));
  $("#price_month").html("<font>" + close_pay + "</font>");

  $("#payment_now").attr("readonly", true);
}

function dataTablePaymentDetail() {
  let searchParams = window.location.pathname;
  var searchParams_ = searchParams.split("/loan/detail/");

  $.ajax({
    url: serverUrl + "/loan/tableListPayment/" + searchParams_[1],
    dataType: "json",
    type: "get",
    async: false,
    success: function (response) {
      var result = JSON.parse(response.message);
      var newDate = new Date();
      for (let i = 0; i < result.length; i++) {
        if (result[i].loan_payment_type == "Close") {
          $("#btn_seting").html(
            "<div id='btn_seting' class='d-flex justify-content-end'></div>"
          );
          break;
        }

        if (result[0].loan_payment_date_fix != null) {
          if (i == 0) {
            result[0].loan_payment_date_fix = new Date(
              result[0].loan_payment_date_fix
            );
            newDate = new Date(result[0].loan_payment_date_fix);
          } else if (i > 0) {
            newDate = new Date(newDate.setMonth(newDate.getMonth() + 1));
            result[i].loan_payment_date_fix = new Date(newDate);
          }
        }
      }
      tableCall(result);
    },
  });
}

function tableCall(data) {
  $("#tablePayment").DataTable().clear().destroy();

  var tablePayment = $("#tablePayment").DataTable({
    // responsive: true,
    language: {
      searchPlaceholder: "Search...",
      sSearch: "",
    },
    // info: true,
    pagingType: "full_numbers",
    lengthMenu: [
      [10, 25, 50, 100, -1],
      [10, 25, 50, 100, "All"],
    ],
    paging: true,
    processing: true,
    data: data,
    columnDefs: [
      {
        targets: 4,
        className: "tx-right",
        data: "loan_payment_amount",
        render: function (data, type, row, meta) {
          return (
            '<span class="tx-success">' +
            new Intl.NumberFormat().format(
              Number(data["loan_payment_amount"]).toFixed(2)
            ) +
            "</span>"
          );
        },
      },
      {
        targets: 5,
        className: "tx-right",
        data: "loan_balance",
        render: function (data, type, row, meta) {
          return (
            '<span class="tx-success">' +
            new Intl.NumberFormat().format(
              Number(data["loan_balance"]).toFixed(2)
            ) +
            "</span>"
          );
        },
      },
      {
        data: "loan_payment_date",
        targets: 9,
        render: function (data, type, row, meta) {
          if (type == "display") {
            if (data["loan_payment_date"] == null) {
              return "รอการชำระ";
            } else {
              const date = new Date(data["loan_payment_date"]);
              const result = date.toLocaleDateString("th-TH", {
                year: "numeric",
                month: "short",
                day: "numeric",
              });
              return result;
            }
          }
          return data["loan_payment_date"];
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
        data: "loan_payment_date_fix",
        targets: 8,
        render: function (data, type, row, meta) {
          if (type == "display") {
            const date = new Date(data["loan_payment_date_fix"]);
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
    ],
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        },
      },
      {
        data: "loan_code",
      },
      {
        data: "loan_payment_customer",
      },
      {
        data: "loan_employee",
      },
      {
        data: null,
      },
      {
        data: null,
      },
      {
        className: "text-center",
        data: "loan_payment_installment",
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
          if (data["loan_payment_date"] === null) {
            return "<font class='tx-primary'>" + "รอการชำระ" + "</font>";
          } else {
            if (
              Date.parse(data["loan_payment_date"]) > Date.now() &&
              data["loan_payment_type"] === null
            ) {
              return "<font>" + "ยังไม่ถึงกำหนด" + "</font>";
            } else if (
              Date.parse(data["loan_payment_date"]) < Date.now() &&
              data["loan_payment_type"] === null
            ) {
              return (
                "<font style='color: #ffc107'>" +
                "รอการจ่าย/เลยกำหนด " +
                dateDiff(Date.now(), Date.parse(data["loan_payment_date"])) +
                "</font>"
              );
            } else {
              return "<font class='tx-success'>" + "จ่ายสำเร็จ" + "</font>";
            }
          }
        },
      },

      {
        data: null,
        orderable: false,
        render: function (data, type, row, meta) {
          if (data["loan_payment_date_fix"] === null) {
            if (meta.row == 0) {
              return (
                "<button type='button' class='modal-effect btn btn-outline-primary mx-2 button-icon paymentBTN' data-toggle='tooltip' data-placement='top' title='ชำระค่างวด' id='" +
                data["id_loan"] +
                "'><i class='ionicon side-menu__icon fas fa-hand-holding-usd'></i></button>"
              );
            } else {
              return "-";
            }
          } else {
            if (
              Date.parse(data["loan_payment_date_fix"]) < Date.now() &&
              data["loan_payment_type"] === null
            ) {
              return (
                "<button type='button' class='modal-effect btn btn-outline-primary mx-2 button-icon paymentBTN' data-toggle='tooltip' data-placement='top' title='ชำระค่างวด' id='" +
                data["id_loan"] +
                "'><i class='ionicon side-menu__icon fas fa-hand-holding-usd'></i></button>"
              );
            } else if (
              Date.parse(data["loan_payment_date_fix"]) > Date.now() &&
              data["loan_payment_type"] === null
            ) {
              return (
                "<button type='button' class='modal-effect btn btn-outline-primary mx-2 button-icon paymentBTN' data-toggle='tooltip' data-placement='top' title='ชำระค่างวด' id='" +
                data["id_loan"] +
                "'><i class='ionicon side-menu__icon fas fa-hand-holding-usd'></i></button>"
              );
            } else {
              return (
                "<button type='button' class='modal-effect btn btn-outline-primary mx-2 button-icon pdf_loan_receipt' data-toggle='tooltip' data-placement='top' title='ปริ้นใบเสร็จ' id='" +
                data["id_loan"] +
                "'><i class='far fa-file-pdf'></i></button>"
              );
            }
          }
        },
      },
    ],
  });
}
$("body").on("click", ".pdf_loan", function () {
  var id = $(this).attr("id");

  let url = `${serverUrl}/pdf_loan/` + id;

  window.open(
    url,
    "Doc",
    "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=992,height=700,top=10,left=10 "
  );
});

$("body").on("click", ".pdf_installment_schedule", function () {
  var id = $(this).attr("id");

  let url = `${serverUrl}/pdf_installment_schedule/` + id;

  window.open(
    url,
    "Doc",
    "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=992,height=700,top=10,left=10 "
  );
});

$("body").on("click", ".pdf_loan_receipt", function () {
  var id = $(this).attr("id");

  let url = `${serverUrl}/pdf_loan_receipt/` + id;

  window.open(
    url,
    "Doc",
    "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=992,height=700,top=10,left=10 "
  );
});

var customer_payment_type = $("#customer_payment_type");
customer_payment_type.on("change", function () {
  var val_pay_type = $("#customer_payment_type").val();
  if (val_pay_type == "เงินสด") {
    $("#bill_credit").removeClass("show");
    $("#file_payment").prop("required", false);
  } else {
    $("#bill_credit").addClass("show");
    $("#file_payment").prop("required", true);
  }
});

var upics = [];

//set for dowload picture
function download(item) {
  var fileName = item.split(/(\\|\/)/g).pop();

  var image = new Image();
  image.crossOrigin = "anonymous";
  image.src = item;
  image.onload = function () {
    // use canvas to load image
    var canvas = document.createElement("canvas");
    canvas.width = this.naturalWidth;
    canvas.height = this.naturalHeight;
    canvas.getContext("2d").drawImage(this, 0, 0);

    // grab the blob url
    var blob;
    if (image.src.indexOf(".jpg") > -1) {
      blob = canvas.toDataURL("image/jpeg");
    } else if (image.src.indexOf(".png") > -1) {
      blob = canvas.toDataURL("image/png");
    } else if (image.src.indexOf(".gif") > -1) {
      blob = canvas.toDataURL("image/gif");
    } else {
      blob = canvas.toDataURL("image/png");
    }

    // create link, set href to blob
    var a = document.createElement("a");
    a.title = fileName;
    a.href = blob;
    a.style.display = "none";
    a.setAttribute("download", fileName);
    a.setAttribute("target", "_blank");
    document.body.appendChild(a);

    // click item
    a.click();
  };
}

function downloadOther(item) {
  let searchParams = window.location.pathname;
  var searchParams_ = searchParams.split("/loan/detail/");

  $.ajax({
    url: serverUrl + "/loan/dowloadPictureOther/" + searchParams_[1],
    method: "get",
    success: function (response) {
      let upics = [];
      $.each(response.message, function (index, item) {
        if (item.src && item.src !== "") {
          upics.push(CDN_IMG + "/uploads/" + item.path + "/" + item.src);
        }
      });

      // โหลดไฟล์ทั้งหมด
      for (var i in upics) {
        download(upics[i]);
      }
    },
  });
}

function parseMapLink(mapLink) {
  let lat, lng;

  // รูปแบบที่ 1: "15.0643520, 104.9294570"
  const decimalPattern = /^([-+]?\d*\.\d+),\s*([-+]?\d*\.\d+)$/;

  // รูปแบบที่ 2: "15°17'10.6"N 104°53'08.2"E"
  const dmsPattern =
    /(\d+)[°\u00B0](\d+)'(\d*\.\d+|\d+)"([NS]),?\s*(\d+)[°\u00B0](\d+)'(\d*\.\d+|\d+)"([EW])/;

  // ตรวจสอบรูปแบบแรก (แบบทศนิยม)
  let match = mapLink.match(decimalPattern);
  if (match) {
    lat = parseFloat(match[1]);
    lng = parseFloat(match[2]);
  } else {
    // ตรวจสอบรูปแบบที่สอง (แบบ DMS)
    match = mapLink.match(dmsPattern);
    if (match) {
      // แปลงค่าจาก DMS ไปเป็น Decimal
      let latDegree = parseInt(match[1]);
      let latMinute = parseInt(match[2]);
      let latSecond = parseFloat(match[3]);
      let latDirection = match[4];

      let lngDegree = parseInt(match[5]);
      let lngMinute = parseInt(match[6]);
      let lngSecond = parseFloat(match[7]);
      let lngDirection = match[8];

      // คำนวณ latitude และ longitude ในรูปแบบทศนิยม
      lat = latDegree + latMinute / 60 + latSecond / 3600;
      if (latDirection === "S") lat = -lat;

      lng = lngDegree + lngMinute / 60 + lngSecond / 3600;
      if (lngDirection === "W") lng = -lng;
    }
  }

  return { latitude: lat, longitude: lng };
}

// When click add link
$("body").on("click", "#btn_edit_link_map", function () {
  let $btn = $(this); // เก็บปุ่มไว้ใช้งาน
  $btn.prop("disabled", true).text("กำลังบันทึก..."); // 🔒 ป้องกันคลิกซ้ำ
  let searchParams = window.location.pathname;
  var searchParams_ = searchParams.split("/loan/detail/");
  var mapLink = $("#link_map").val().trim();
  if (mapLink !== "") {
    $.ajax({
      type: "POST",
      url: "/loan/save_maplink/" + searchParams_[1], // Replace with the actual URL
      data: { mapLink: mapLink },
      success: function (response) {
        if (response.success) {
          let parsedCoords = parseMapLink(mapLink);

          fetch(
            "https://script.google.com/macros/s/AKfycbycISLS1BJQEBffMcirtCdxwnjLwSmTcmxsGlkx3NEMLrlPO8CJ_KQCloRbwHdifh_cGw/exec",
            {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
              },
              body: JSON.stringify({
                loan_code: searchParams_[1], // หมายเลข loan_code ที่ต้องการอัปเดต
                latitude: parsedCoords.latitude, // ค่าใหม่ของ latitude
                longitude: parsedCoords.longitude, // ค่าใหม่ของ longitude
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

          setTimeout(function () {
            location.reload(); // Reload the page
          }, 1500); // Wait for 1.5 seconds before reloading
        } else {
          alert("เกิดข้อผิดพลาด: " + response.message);
          $btn.prop("disabled", false).text("บันทึกลิงก์แผนที่"); // 🔓 เปิดปุ่มอีกครั้ง
        }
      },
      error: function () {
        alert("ไม่สามารถบันทึกลิงก์ Map ได้ในขณะนี้");
        $btn.prop("disabled", false).text("บันทึกลิงก์แผนที่"); // 🔓 เปิดปุ่มอีกครั้ง
      },
    });
  } else {
    alert("กรุณากรอกลิงก์ Map ก่อนทำการบันทึก");
    $btn.prop("disabled", false).text("บันทึกลิงก์แผนที่"); // 🔓 เปิดปุ่มอีกครั้ง
  }
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

  // เก็บไฟล์ที่มาจากการถ่ายรูป (กรณีสร้าง input แบบ dynamic)
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
          _capturedFile = this.files[0]; // เก็บไฟล์ไว้ใช้ตอน OCR
          $("#detectImageForm").show();
          setImagePreviewFromFile(this.files[0]);
        }
      })
      .click();
  });

  // เมื่อเลือกไฟล์แล้ว แสดงฟอร์ม OCR
  $("#imageFile").on("change", function () {
    _capturedFile = null; // ถ้าเลือกไฟล์จาก input ปกติ ให้เคลียร์ไฟล์จากกล้อง
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
              bundleData.prename
            )
              ? "หญิง"
              : "ชาย";
          }

          // ✅ ใส่ค่าลงฟอร์ม + ไฮไลท์ valid (เหมือนเดิม)
          if (bundleData.firstname || bundleData.lastname) {
            let $inputName = $form.find("input[name=fullname]");
            $inputName
              .val(`${bundleData.firstname || ""} ${bundleData.lastname || ""}`.trim())
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
            $inputGender.val(bundleData.gender).removeClass("is-invalid").addClass("is-valid");
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
    // ใช้ไฟล์จาก _capturedFile ถ้ามี ไม่งั้นใช้ไฟล์จาก #imageFile
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
    // กันกดรัว ๆ ระหว่างรอ
    if ($(this).hasClass("disabled")) return;
    detectImage();
  });
});
