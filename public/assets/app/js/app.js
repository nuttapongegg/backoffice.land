function select2Banks(data) {
  if (!data.id) {
    return data.text;
  }

  if (data.id == "เลือกธนาคาร") {
    return data.text;
  }

  var $data = $(
    '<span class="d-flex align-items-center"><img src="../assets/img/banks/ic-' +
      data.element.dataset.icon +
      '.png" class="rounded-circle avatar-xs me-1" /> ' +
      data.text +
      "</span>"
  );
  return $data;
}

$(document).ready(function () {

  let timeleft = 29;
  let downloadTimer = setInterval(function () {
    if (timeleft <= 0) {
      clearInterval(downloadTimer);
      $("#switchbtn-light-theme").click();
      $(".profiles-gate-container").fadeOut(1000);
    } else {
      document.getElementById("countdown").innerHTML = "(" + timeleft + ")";
    }
    timeleft -= 1;
  }, 3000);

  const APP = {
    // ลิสใบสำคัญ
    docTable() {
      $("#tableDoc").DataTable().clear().destroy();
      let $table = $("#tableDoc").DataTable({
        oLanguage: {
          sInfo: "กำลังแสดง หน้า _PAGE_ ใน _PAGES_",
          sSearch: "",
          sSearchPlaceholder: "ค้นหา...",
        },
        stripeClasses: [],
        pageLength: 5,
        lengthMenu: [
          [5, 25, 50, -1],
          [5, 25, 50, "ทั้งหมด"],
        ],

        // Processing indicator
        processing: true,
        // DataTables server-side processing mode
        serverSide: true,
        // Initial no order.
        order: [],
        // Load data from an Ajax source
        ajax: {
          url: `${serverUrl}/document/getLists`,
          type: "POST",
          data: function (d) {
            // d.data = {
            //     docType: $docType
            // }
            const $date = $("#daterange_doc").val();
            if ($date !== "") {
              d.date = $date;
            }
            let $tab = $(".tabDocType a.active");

            d.docType = $tab.data("doc-type");

            return d;
          },
        },
        //Set column definition initialisation properties
        columnDefs: [
          {
            className: "text-center",
            targets: [0],
            width: "10%",
          },
          {
            className: "text-center",
            targets: [1],
            width: "10%",
          },
          {
            className: "text-left",
            targets: [2],
            width: "20%",
          },
          {
            className: "text-left",
            targets: [3],
            width: "25%",
          },
          {
            className: "text-center",
            targets: [4],
            width: "10%",
          },
          {
            className: "text-center",
            targets: [5],
            width: "10%",
          },
          {
            className: "text-center",
            targets: [5],
            width: "10%",
          },
          {
            className: "text-center",
            targets: [6],
            orderable: false,
            width: "5%",
          },
        ],
      });
    },

    // ล้าง DocForm
    docFormClear() {
      $("form[name=formDoc]").trigger("reset");
    },

    // ตั้งค่าฟอร์มใบสำคัญเริ่มต้น
    docSetEnvForm() {
      $("#formDate").flatpickr({
        disableMobile: true,
      });

      easyNumberSeparator({
        selector: ".price",
        separator: ",",
      });
    },
    

    // จัดการฟอร์มใบสำคัญเมื่อเปิด
    docOpenForm($docType) {
      $("#wrapperDocPaymentTypeEtc").hide();
      $("#wrapperACForm").hide();

      let $tab = $(".tabDocType"),
        $modal = $("#docModal"),
        $inputType = $("input[name=doc_type]");
      var date = new Date();

      if (date.getMonth() <= 8) {
        var date_doc = date.getFullYear() + "0" + (date.getMonth() + 1);
      } else {
        var date_doc = date.getFullYear() + "" + (date.getMonth() + 1);
      }

      switch ($docType) {
        case "ใบสำคัญรับ":
          $title = "ใบสำคัญรับ";
          // $inputDocType = 'RV-' + date_doc
          $tabAhrefClass = ".tabDocType1";
          break;

        case "ใบสำคัญจ่าย":
          $title = "ใบสำคัญจ่าย";
          // $inputDocType = 'PV-' + date_doc
          $tabAhrefClass = ".tabDocType2";
          break;
      }

      // จัดการ Modal
      $modal.find(".modal-title").html($title);
      $modal.find(".table-title").html("ประวัติ" + $title);

      // จัดการ Tab
      $tab.find("a").removeClass("active");
      $tab.find($tabAhrefClass).addClass("active");

      // จัดการ Form
      $inputType.val($docType);

      var $tabDocType = $(".tabDocType a.active"),
        documentType = $tabDocType.data("doc-type");

      $.ajax({
        url: `${serverUrl}/document/docNumber/` + documentType,
        type: "GET",
        dataType: "json",
        success: function (res) {
          switch (documentType) {
            case "ใบสำคัญรับ":
              $inputDocType = "RV-" + date_doc + res;
              break;
            case "ใบสำคัญจ่าย":
              $inputDocType = "PV-" + date_doc + res;
              break;
          }
          $("input[name=doc_number]").val($inputDocType);
          // console.log(res)
        },
        error: function (data) {},
      });

      // $('input[name=doc_number]').val($inputDocType)
      $(".wrapperFile").find(".dropify-preview").css("display", "none");
      $(".wrapperFile").find(".dropify-render").html("");
      $(".docDropify").dropify();

      $('input[type="file"]').val("");

      // LOAD ใบสำคัญ
      APP.docTable();
    },

    // จัดการฟอร์มใบสำคัญ
    handleDocForm() {
      let $modalDoc = $("#docModal");
      let $form = $modalDoc.find("form");

      let $FORM_KEY = $modalDoc.find("form").data("form-key");

      /*************************************************************
       * EVENT
       */

      // เมนู NAV
      $(".selectDoc").on("click", function () {
        let $me = $(this),
          $docType = $me.data("doc-type");

        APP.docFormClear();
        APP.docSetEnvForm();
        APP.docOpenForm($docType);
      });

      // เมนู TAB
      $(".tabDocType").on("click", function () {
        let $me = $(this),
          $docType = $me.find("a").data("doc-type");

        APP.docFormClear();
        APP.docSetEnvForm();
        APP.docOpenForm($docType);
      });

      $modalDoc
        // เรียกข้อมูลมาแก้ไข
        .on("click", ".btnEdit", function () {
          let $me = $(this),
            $docID = $me.data("doc-id");

          $.ajax({
            type: "GET",
            url: `${serverUrl}/document/edit/${$docID}`,
            contentType: "application/json; charset=utf-8",
          }).done(function (res) {
            if (res.success == 1) {
              APP.docFormClear();
              // APP.docOpenForm();
              $("#docModal").animate({ scrollTop: 100 }, "slow");
              let $data = res.data;

              APP.docOpenForm($data.doc_type);

              let $docPaymentType = $data.doc_payment_type,
                $docPrice = $data.price,
                $land_account_name = $data.cash_flow_name;

              $form.find("input[name=doc_id]").val($data.id);
              $form.find("input[name=doc_type]").val($data.doc_type);
              $form.find("input[name=doc_number]").val($data.doc_number);
              $("#formDate").flatpickr();
              $form.find("input[name=doc_date]").val($data.doc_date);
              $form.find("input[name=title]").val($data.title);
              $form.find("input[name=price]").val($docPrice);
              easyNumberSeparator({
                selector: ".price",
                separator: ",",
              });
              $form
                .find("select[name=land_account_name]")
                .val($land_account_name);
              $form.find("input[name=note]").val($data.note);
              if ($data.filePath != "") {
                $form.find(".dropify-preview").css("display", "block");
                $form
                  .find(".dropify-render")
                  .html(
                    '<img src="' +
                      `${CDN_IMG}/uploads/file_loan/${$data.filePath}` +
                      '" alt="">'
                  );
              }
              $form.find(".btnSave").html("ยืนยันแก้ไขข้อมูล");
            }
          });
        })
        // ลบใบสำคัญ
        .on("click", ".btnDelete", function () {
          let $me = $(this);

          let $url = $me.data("url"),
            $name = $me.data("document-number");

          $me.prop("disabled", true);

          Swal.fire({
            text: `คุณต้องการลบ ${$name}`,
            icon: "warning",
            buttonsStyling: false,
            confirmButtonText: "ตกลง",
            showCloseButton: true,
            customClass: {
              confirmButton: "btn btn-primary",
            },
          }).then(function (result) {
            if (result.isConfirmed) {
              $.ajax({
                type: "POST",
                url: $url,
                success: function (res) {
                  $me.prop("disabled", false);

                  Swal.fire({
                    icon: "success",
                    text: `${res.message}`,
                    timer: "2000",
                    heightAuto: false,
                  });

                  APP.docTable();
                },
                error: function (res) {
                  Swal.fire({
                    icon: "error",
                    text: `ไม่สามารถลบได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ`,
                    timer: "2000",
                    heightAuto: false,
                  });
                },
              });
            }
          });
        })

        // ดึงรายการ
        .on("click", "#openTitleList", function () {
          let x = screen.width / 2 - 800 / 2;
          let y = screen.height / 2 - 600 / 2;

          let $tab = $(".tabDocType a.active");
          let $docType = $tab.data("doc-type");

          let configParam = $.param({
            actionBy: "MAIN",
            formKey: $FORM_KEY,
            docType: $docType,
          });

          let url = `${serverUrl}/document/listTitle?${configParam}`;
          window.open(
            url,
            "DF",
            "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no height=600,width=800,left=" +
              x +
              ",top=" +
              y
          );
        })

        .on("click", ".btnPrint", function () {
          let $me = $(this),
            $id = $me.data("doc-id");
          $type = $me.data("doc-type-");
          if ($type == "ใบสำคัญรับ") {
            let url = `${serverUrl}/pdf_document/` + $id;
            window.open(
              url,
              "Doc",
              "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=992,height=700,top=10,left=10 "
            );
          } else if ($type == "ใบสำคัญจ่าย") {
            let url = `${serverUrl}/pdf_doc_pay/` + $id;
            window.open(
              url,
              "Doc",
              "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=992,height=700,top=10,left=10 "
            );
          }
        });

      $form
        // บันทึกข้อมูล
        .on("click", ".btnSave", function (e) {
          e.preventDefault();

          if ($form.find("input[name=doc_date]").val() == "") {
            alert("กรุณาเลือกวันที่");
            return false;
          }

          if ($form.find("input[name=title]").val() == "") {
            alert("กรุณาเลือกรายการ");
            return false;
          }

          if ($form.find("input[name=price]").val() == "") {
            alert("กรุณาเลือกระบุจำนวนเงิน");
            return false;
          }

          let $docType = $("input[name=doc_type]").val();

          if ($form.find("select[name=land_account_name]").val() == null) {
            alert("กรุณาเลือกบัญชีบริษัท");
            return false;
          }

          // ผ่าน
          if (
            $form.find("input[name=doc_date]").val() != "" &&
            $form.find("input[name=title]").val() != "" &&
            $form.find("input[name=price]").val() != ""
          ) {
            let $me = $(this);

            $me.attr("disabled", true);

            let formData = new FormData($form[0]);
            let imageFileInvoiceDoc = document.querySelector(
              "#imageFileInvoiceDoc"
            );

            if (imageFileInvoiceDoc.files.length > 0) {
              formData.append(
                "imageFileInvoiceDoc",
                imageFileInvoiceDoc.files[0]
              );
            }
            let $url = "";
            if ($form.find("input[name=doc_id]").val() != "") {
              $url = `${serverUrl}/document/update`;
            } else {
              $url = `${serverUrl}/document/store`;
            }

            $.ajax({
              type: "POST",
              url: $url,
              data: formData,
              processData: false,
              contentType: false,
            })
              .done(function (res) {
                if (res.success) {
                  Swal.fire({
                    text: res.message,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "ตกลง",
                    customClass: {
                      confirmButton: "btn btn-primary",
                    },
                  }).then(function (result) {
                    if (result.isConfirmed) {
                      APP.docTable();
                    }
                  });

                  // console.log($form.find("input[name=doc_date]").val());

                  $me.attr("disabled", false);
                  APP.docFormClear();
                  APP.docOpenForm($docType);
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
                      // LANDING_KNOWLEDGE.reloadPage()
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
          }
        });
    },
    tableDocFilter() {
      flatpickr("#daterange_doc", {
        mode: "range",
        dateFormat: "Y-m-d",
        onChange: function (selectedDates) {
          APP.docTable();
        },
      });
    },

    // เลือกธีม
    selectTheme() {
      $(".theme").on("click", function () {
        clearInterval(downloadTimer);

        let $me = $(this),
          $themeName = $me.data("theme-name");

        $("#" + $themeName).click();
        $(".profiles-gate-container").fadeOut(1000);
      });

      $(".btn--snakeBorder").on("click", function () {
        clearInterval(downloadTimer);
        $("#switchbtn-light-theme").click();
        $(".profiles-gate-container").fadeOut(1000);
      });
    },

    // SET UP
    init() {
      APP.selectTheme();
      APP.handleDocForm();
      APP.tableDocFilter();

      if (!localStorage.getItem("zemthemecolors")) {
        $(".profiles-gate-container").show();
      } else {
        clearInterval(downloadTimer);
      }
    },
  };

  APP.init();

  $("#note").keyup(function () {
    let searchText = $(this).val();
    let $tab = $(".tabDocType a.active");
    let $docType = $tab.data("doc-type");
    if (searchText != "") {
      $.ajax({
        url: `/document/search`,
        method: "POST",
        data: {
          query: searchText,
          type: $docType,
        },
        success: function (response) {
          $("#show-list").html(response);
        },
      });
    } else {
      $("#show-list").html("");
    }
    $(document).on("click", "a", function () {
      $("#note").val($(this).text());
      $("#show-list").html("");
    });
  });

  // ตรวจสอบว่าเป็นอุปกรณ์ iOS หรือไม่ (รวมทั้ง iPhone และ iPad)
  function isIos() {
    return /iPhone|iPad|iPod/i.test(navigator.userAgent);
  }

  if (isIos()) {
    // แสดงปุ่มทั้ง 2 ปุ่ม (ถ่ายรูปและเลือกไฟล์) สำหรับ iOS
    $("#btnAiAutoCaptureDoc").show();
    $("#btnAiAutoSelectDoc").show();
  } else {
    // แสดงแค่ปุ่มเลือกไฟล์ที่สามารถถ่ายภาพได้ในอุปกรณ์อื่นๆ
    $("#btnAiAutoCaptureDoc").hide();
    $("#btnAiAutoSelectDoc").show();
  }

  // ปุ่มเลือกไฟล์ ใช้ AI Auto Input
  // ฟังก์ชันการทำงานอื่นๆ ตามเดิม
  $("#btnAiAutoCaptureDoc").on("click", function () {
    let fileInput = $("#imageFileInvoiceDoc");
    fileInput.val(""); // เคลียร์ค่าเก่า เพื่อให้สามารถเลือกไฟล์ใหม่ได้
    fileInput.attr("capture", "environment"); // บังคับให้ใช้กล้องหลัง
    fileInput.click();
  });

  $("#btnAiAutoSelectDoc").on("click", function () {
    let fileInput = $("#imageFileInvoiceDoc");
    fileInput.removeAttr("capture"); // เอา capture ออกเพื่อให้เลือกไฟล์ได้
    fileInput.click();
  });

  // เมื่อเลือกไฟล์หรือถ่ายรูป
  $("#imageFileInvoiceDoc").on("change", function () {
    const fileInvoice = this.files[0];
    if (!fileInvoice) return;

    const fileType = fileInvoice.type;
    $("#detectImageFormInvoiceDoc").show();

    if (fileType === "application/pdf") {
      const fileURL = URL.createObjectURL(fileInvoice);
      $("#pdfPreviewInvoiceDoc").attr("src", fileURL).show();
      $("#imagePreviewInvoiceDoc").hide();
    } else if (fileType.startsWith("image/")) {
      const readerInvoice = new FileReader();
      readerInvoice.onload = function (e) {
        $("#imagePreviewInvoiceDoc").attr("src", e.target.result).show();
      };
      readerInvoice.readAsDataURL(fileInvoice);
      $("#pdfPreviewInvoiceDoc").hide();
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
  $("#btnAiAutoInputInvoiceDocSubmit").on("click", function () {
    let fileInput = document.querySelector("#imageFileInvoiceDoc");

    if (!fileInput.files.length) return; // ถ้ายังไม่ได้เลือกไฟล์ ให้ return ออกไปเลย

    let fileInvoice = fileInput.files[0];
    let formData = new FormData();
    formData.append("image", fileInvoice);

    // แสดง loading
    $("#btnAiAutoInputInvoiceDocSubmit")
      .prop("disabled", true)
      .html('<i class="fa fa-spinner fa-spin"></i> กำลังประมวลผล');

    $.ajax({
      url: "/document/ocrInvoice", // URL ของ API
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        $("#btnAiAutoInputInvoiceDocSubmit")
          .prop("disabled", false)
          .html("ยืนยัน");

        if (response.status === "duplicate") {
          Swal.fire({
            icon: "warning",
            title: "ข้อมูลซ้ำ",
            text: response.message,
            confirmButtonText: "ตกลง",
          });
          return;
        } else if (response.status === "success") {
          $("#detectImageFormInvoiceDoc").hide(); // ซ่อนฟอร์มเมื่ออัปโหลดเสร็จ

          if (response.json_output) {
            let jsonData = response.json_output;

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

            // แปลงวันที่ก่อนที่จะนำไปใส่ใน input
            let formattedDateImg = formatDateImg(jsonData.date);
            // เติมข้อมูลลงใน input
            $("input[name=price]").val(amount_thb).addClass("is-valid");
            $("input[id=formDate]").val(formattedDateImg).addClass("is-valid");
            // $("input[id=formDate1]").val(formattedDateImg).addClass("is-valid");
            $("input[name=doc_file_date]").val(jsonData.date).addClass("is-valid");
            $("input[name=doc_file_time]").val(jsonData.time).addClass("is-valid");
            $("input[name=doc_file_price]").val(jsonData.amount).addClass("is-valid");

            easyNumberSeparator({
              selector: ".price",
              separator: ",",
            });
          }
        }
      },
      error: function () {
        $("#btnAiAutoInputInvoiceDocSubmit")
          .prop("disabled", false)
          .html("ยืนยัน");
      },
    });
  });

  // เมื่อคลิกปุ่ม 'ยกเลิก'
  $("#btnAiAutoInputInvoiceDocClear").on("click", function () {
    $("#detectImageFormInvoiceDoc").hide(); // ซ่อนฟอร์ม
    $("#imageFileInvoiceDoc").val(""); // รีเซ็ต input file
    $("#imagePreviewInvoiceDoc").attr("src", "").hide(); // ซ่อนภาพ preview
    $("#pdfPreviewInvoiceDoc").attr("src", "").hide(); // ซ่อน PDF preview
  });
});

$("#checkbox_wht").on("change", function () {
  $("#open_wht").toggleClass("show");
});
