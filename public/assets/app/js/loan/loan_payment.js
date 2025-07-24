var installments = 0;
var paymentPermonth = 0;
var loan_payment_sum_installment = 0;
var typePayment;
var payNow = 0;

var loan_period = 0;
var loan_installment_date = 0;

(function ($) {
  let searchParams = window.location.pathname;
  var searchParams_ = searchParams.split("/loanpayment/detail/");

  flatpickr("#date_to_payment", {
    disableMobile: true,
    clickOpens: false
  });

  loadLoan(searchParams_[1]);
  installmentTab();
  dataTablePaymentDetail();
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
    url: serverUrl + "/loanpayment/detailForm/" + loanCode_[0],
    method: "get",
    async: false,
    success: function (response) {
      $("#payment_name").val(response.message.loan_customer);
      $("#payment_employee_name").val(response.message.loan_employee);

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
    url: serverUrl + "/loanpayment/callInstallMent/" + loanCode_[1],
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
}

function commaSeparateNumber(val) {
  while (/(\d+)(\d{3})/.test(val.toString())) {
    val = val.toString().replace(/(\d+)(\d{3})/, "$1" + "," + "$2");
  }
  return val;
}

$(".modalPaymentLoanClose").click(function () {
  let modalPayLoanNoLogin = $("#modalPayLoanNoLogin");
  let form = modalPayLoanNoLogin.find("form");
  form.parsley().reset();
  form[0].reset();
  $(".btn-add-loan-payment").text("บันทึก");
  $(".PaymentLoanType1").addClass("active");
  $(".PaymentLoanType2").removeClass("active");
  $("#modalPayLoanNoLogin").modal("hide");
});

$("#tablePayment").on("click", ".paymentBTN", function () {
  let searchParams = window.location.pathname;
  var searchParams_ = searchParams.split("/loanpayment/detail/");

  var id_install = "";

  id_install = $(this).attr("id");

  // console.log(id_install);

  $("#modalPayLoanNoLogin").modal("show");

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
});

$(document).delegate(".btn-add-loan-payment", "click", function (e) {
  let searchParams = window.location.pathname;
  var searchParams_ = searchParams.split("/loanpayment/detail/");
  $("#codeloan_hidden").val(searchParams_[1]);

  let modalPayLoanNoLogin = $("#modalPayLoanNoLogin");
  let formAddLoanPay = modalPayLoanNoLogin.find("form").attr("id");
  let form = modalPayLoanNoLogin.find("form");
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
          $("#modalPayLoanNoLogin").modal("hide");
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

  let imageFileInvoice = document.querySelector("#imageFileInvoice");

  if (imageFileInvoice.files.length > 0) {
    formData.append("imageFileInvoice", imageFileInvoice.files[0]);
  }

  $(".btn-add-loan-payment").text("กำลังบันทึก...");
  $.ajax({
    url: serverUrl + "/loanpayment/addPaymentNoLogin",
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
        $(".btn-add-loan-payment").text("บันทึก");
      } else {
        notif({
          type: "success",
          msg: "จ่ายสินเชื่อสำเร็จ!",
          position: "right",
          fade: true,
          time: 300,
        });

        form.parsley().reset();
        form[0].reset();
        $(".btn-add-loan-payment").text("บันทึก");
        $(".PaymentLoanType1").addClass("active");
        $(".PaymentLoanType2").removeClass("active");
        $("#modalPayLoanNoLogin").modal("hide");
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

  $("#payment_now").attr("readonly", true);
}

function dataTablePaymentDetail() {
  let searchParams = window.location.pathname;
  var searchParams_ = searchParams.split("/loanpayment/detail/");

  $.ajax({
    url: serverUrl + "/loanpayment/tableListPayment/" + searchParams_[1],
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

      // ค้นหางวดถัดไปที่ต้องชำระ
      let nextInstallment = null;
      for (let i = 0; i < result.length; i++) {
        if (
          result[i].loan_payment_date === null &&
          result[i].loan_payment_type === null
        ) {
          nextInstallment = result[i];
          break; // หยุดลูปเมื่อเจองวดแรกที่ยังไม่ได้ชำระ
        }
      }

      // หากพบงวดถัดไป ให้เปิด Modal อัตโนมัติ
      if (nextInstallment) {
        setTimeout(function () {
          $("#modalPayLoanNoLogin").modal("show");
          loadLoan(searchParams_[1] + "###" + nextInstallment.id_loan); // โหลดข้อมูลงวดถัดไป
          $("#installment_bar").addClass("show");
          installmentTab();
        }, 500); // หน่วงเวลาเล็กน้อยเพื่อให้ DOM พร้อม
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

// var customer_payment_type = $("#customer_payment_type");
// customer_payment_type.on("change", function () {
//   var val_pay_type = $("#customer_payment_type").val();
//   if (val_pay_type == "เงินสด") {
//     $("#bill_credit").removeClass("show");
//     $("#file_payment").prop("required", false);
//   } else {
//     $("#bill_credit").addClass("show");
//     $("#file_payment").prop("required", true);
//   }
// });

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

$(document).ready(function () {
  // ตรวจสอบว่าเป็นอุปกรณ์ iOS หรือไม่ (รวมทั้ง iPhone และ iPad)
  function isIos() {
    return /iPhone|iPad|iPod/i.test(navigator.userAgent);
  }

  if (isIos()) {
    // แสดงปุ่มทั้ง 2 ปุ่ม (ถ่ายรูปและเลือกไฟล์) สำหรับ iOS
    $("#btnAiAutoCapture").show();
    $("#btnAiAutoSelect").show();
  } else {
    // แสดงแค่ปุ่มเลือกไฟล์ที่สามารถถ่ายภาพได้ในอุปกรณ์อื่นๆ
    $("#btnAiAutoCapture").hide();
    $("#btnAiAutoSelect").show();
  }

  // ปุ่มเลือกไฟล์ ใช้ AI Auto Input

  // ฟังก์ชันการทำงานอื่นๆ ตามเดิม
  $("#btnAiAutoCapture").on("click", function () {
    let fileInput = $("#imageFileInvoice");
    fileInput.val(""); // เคลียร์ค่าเก่า เพื่อให้สามารถเลือกไฟล์ใหม่ได้
    fileInput.attr("capture", "environment"); // บังคับให้ใช้กล้องหลัง
    fileInput.click();
  });

  $("#btnAiAutoSelect").on("click", function () {
    let fileInput = $("#imageFileInvoice");
    fileInput.removeAttr("capture"); // เอา capture ออกเพื่อให้เลือกไฟล์ได้
    fileInput.click();
  });

  // เมื่อเลือกไฟล์หรือถ่ายรูป
  $("#imageFileInvoice").on("change", function () {
    const fileInvoice = this.files[0];
    if (!fileInvoice) return;

    const fileType = fileInvoice.type;
    $("#detectImageFormInvoice").show();

    if (fileType === "application/pdf") {
      const fileURL = URL.createObjectURL(fileInvoice);
      $("#pdfPreviewInvoice").attr("src", fileURL).show();
      $("#imagePreviewInvoice").hide();
    } else if (fileType.startsWith("image/")) {
      const readerInvoice = new FileReader();
      readerInvoice.onload = function (e) {
        $("#imagePreviewInvoice").attr("src", e.target.result).show();
      };
      readerInvoice.readAsDataURL(fileInvoice);
      $("#pdfPreviewInvoice").hide();
    }
  });

  // ฟังก์ชันการแปลงวันที่
  function formatDateImg(dateString) {
    if (dateString.includes("-")) {
      let parts = dateString.split("-");
      if (parts.length === 3) {
        let day = parts[0].padStart(2, "0");
        let month = parts[1].padStart(2, "0");
        let year = parts[2];

        // ถ้าปีเป็น 4 หลัก แสดงว่ามันอยู่ในฟอร์แมต dd-mm-yyyy → ต้องแปลง
        if (year.length === 4) {
          return `${year}-${month}-${day}`;
        }
      }
    }

    if (dateString.includes("/")) {
      let parts = dateString.split("/");
      if (parts.length === 3) {
        let day = parts[0].padStart(2, "0");
        let month = parts[1].padStart(2, "0");
        let year = parts[2];

        return `${year}-${month}-${day}`;
      }
    }

    return dateString;
  }

  // เมื่อคลิกปุ่ม 'ยืนยัน'
  $("#btnAiAutoInputInvoiceSubmit").on("click", function () {
    let fileInput = document.querySelector("#imageFileInvoice");

    if (!fileInput.files.length) return; // ถ้ายังไม่ได้เลือกไฟล์ ให้ return ออกไปเลย

    let fileInvoice = fileInput.files[0];
    let formData = new FormData();
    formData.append("image", fileInvoice);

    // แสดง loading
    $("#btnAiAutoInputInvoiceSubmit")
      .prop("disabled", true)
      .html('<i class="fa fa-spinner fa-spin"></i> กำลังประมวลผล');

    $.ajax({
      url: "/loanpayment/ocrInvoice", // URL ของ API
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        $("#btnAiAutoInputInvoiceSubmit")
          .prop("disabled", false)
          .html("ยืนยัน");

        if (response.status === "success") {
          $("#detectImageFormInvoice").hide(); // ซ่อนฟอร์มเมื่ออัปโหลดเสร็จ

          if (response.json_output) {
            let jsonData = response.json_output;
            let amount_thb = 0;

            let amount = jsonData.amount.toString().replace(/,/g, ""); // ลบจุลภาค
            amount = parseFloat(amount); // แปลงเป็นตัวเลข

            if (jsonData.type === "USD") {
              amount_thb = (amount * 34.615).toFixed(2);
            } else if (jsonData.type === "LAK") {
              amount_thb = ((amount / 21745) * 34.615).toFixed(2); // แปลงจาก LAK เป็น USD แล้วค่อยแปลงเป็น THB
            } else {
              amount_thb = amount.toFixed(2); // ถ้าเป็น THB อยู่แล้ว ไม่ต้องแปลง
            }

            amount_thb = Number(amount_thb.replace(/[^0-9.-]+/g, ""));

            let sum_pay =
              amount_thb +
              Number(loan_payment_sum_installment.replace(/[^0-9.-]+/g, ""));

            $("#pay_sum").val(sum_pay);

            // แปลงวันที่ก่อนที่จะนำไปใส่ใน input
            let formattedDateImg = formatDateImg(jsonData.date);
            // เติมข้อมูลลงใน input
            $("input[id=payment_now]").val(amount_thb).addClass("is-valid");

            // วันที่ ที่ได้จาก ai
            // $("input[id=date_to_payment]")
            //   .val(formattedDateImg)
            //   .addClass("is-valid");

            // $("input[id=formDate1]").val(formattedDateImg).addClass("is-valid");
          }
        }
      },
      error: function () {
        $("#btnAiAutoInputInvoiceSubmit")
          .prop("disabled", false)
          .html("ยืนยัน");
      },
    });
  });

  // เมื่อคลิกปุ่ม 'ยกเลิก'
  $("#btnAiAutoInputInvoiceClear").on("click", function () {
    $("#detectImageFormInvoice").hide(); // ซ่อนฟอร์ม
    $("#imageFileInvoice").val(""); // รีเซ็ต input file
    $("#imagePreviewInvoice").attr("src", "").hide(); // ซ่อนภาพ preview
    $("#pdfPreviewInvoice").attr("src", "").hide(); // ซ่อน PDF preview
  });
});
