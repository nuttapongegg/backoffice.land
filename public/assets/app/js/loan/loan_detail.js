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
            fetch('https://script.google.com/macros/s/AKfycbycISLS1BJQEBffMcirtCdxwnjLwSmTcmxsGlkx3NEMLrlPO8CJ_KQCloRbwHdifh_cGw/exec', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                loan_code: searchParams_[1],  // หมายเลข loan_code ที่ต้องการอัปเดต
                action: 'delete',
            }),
              mode: 'no-cors'  // ใช้โหมด no-cors
            })
            .then(response => {
              // ไม่สามารถเข้าถึงเนื้อหาของคำตอบได้ในโหมดนี้
              // console.log('Request sent');
            })
            .catch(error => {
              console.error('Error:', error);
            });

            notif({
              type: "success",
              msg: "แก้ไขสินเชื่อสำเร็จ!",
              position: "right",
              fade: true,
              time: 1400,
            });

            setTimeout(function () {
                window.location = '/loan/list'
            }, 1 * 1500)
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
    url: serverUrl + "/loan/insertDetailPiture/"+ searchParams_[1],
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
    pay_now +
    Number(loan_payment_sum_installment.replace(/[^0-9.-]+/g, ""));

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
    overdueColor ="tx-success"; // สีเขียวสำหรับวันที่เกินกำหนด = 0
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
        if(response.payment_type == 'CloseLoan'){
          let searchParams = window.location.pathname;
          var searchParams_ = searchParams.split("/loan/detail/");
          
          fetch('https://script.google.com/macros/s/AKfycbycISLS1BJQEBffMcirtCdxwnjLwSmTcmxsGlkx3NEMLrlPO8CJ_KQCloRbwHdifh_cGw/exec', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              loan_code: searchParams_[1],  // หมายเลข loan_code ที่ต้องการอัปเดต
              action: 'delete',
          }),
            mode: 'no-cors'  // ใช้โหมด no-cors
          })
          .then(response => {
            // ไม่สามารถเข้าถึงเนื้อหาของคำตอบได้ในโหมดนี้
            // console.log('Request sent');
          })
          .catch(error => {
            console.error('Error:', error);
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

function downloadOther(item){
  let searchParams = window.location.pathname;
  var searchParams_ = searchParams.split("/loan/detail/");
  $.ajax({
    url: serverUrl + "/loan/dowloadPictureOther/" + searchParams_[1],
    method: "get",
    success: function (response) {
      upics = [];
      $.each(response.message, function (index, item) {
        if(item.picture_loan_src != null || item.picture_loan_src != "") {
          upics.push(
            CDN_IMG + "/uploads/loan_img_other/" + item.picture_loan_src
          );
        }
      });

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
  const dmsPattern = /(\d+)[°\u00B0](\d+)'(\d*\.\d+|\d+)"([NS]),?\s*(\d+)[°\u00B0](\d+)'(\d*\.\d+|\d+)"([EW])/;

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
      if (latDirection === 'S') lat = -lat;

      lng = lngDegree + lngMinute / 60 + lngSecond / 3600;
      if (lngDirection === 'W') lng = -lng;
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

          fetch('https://script.google.com/macros/s/AKfycbycISLS1BJQEBffMcirtCdxwnjLwSmTcmxsGlkx3NEMLrlPO8CJ_KQCloRbwHdifh_cGw/exec', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              loan_code: searchParams_[1],  // หมายเลข loan_code ที่ต้องการอัปเดต
              latitude: parsedCoords.latitude,  // ค่าใหม่ของ latitude
              longitude: parsedCoords.longitude,  // ค่าใหม่ของ longitude
            }),
            mode: 'no-cors'  // ใช้โหมด no-cors
          })
          .then(response => {
            // ไม่สามารถเข้าถึงเนื้อหาของคำตอบได้ในโหมดนี้
            // console.log('Request sent');
          })
          .catch(error => {
            console.error('Error:', error);
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
