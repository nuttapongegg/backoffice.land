function PreviewImage(input, previewImage) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $("#" + previewImage).attr("src", e.target.result);
    };

    reader.readAsDataURL(input.files[0]);
  }
}

$(document).ready(function () {

  // $(".select2").select2({
  //   dropdownParent: $(".insert_cus"),
  //   placeholder: "โปรดเลือก",
  //   searchInputPlaceholder: "Search",
  // });

  // $(".select2_update").select2({
  //   dropdownParent: $(".update_cus"),
  //   placeholder: "โปรดเลือก",
  //   searchInputPlaceholder: "Search",
  // });

  $.ajax({
    url: serverUrl + "/stock/get_detail_types",
    method: "get",
    async: false,
    success: function (response) {
      // insert
      var types_car = $("#types_interest");
      types_car.html(
        '<option value="" style="color: #000;">เลือกประเภทรถ</option>'
      );
      $.each(response.message, function (index, item) {
        types_car.append(
          $('<option style="color: #000;"></option>')
            .val(item.id)
            .html(item.title)
        );
      });

      // update
      var types_car_update = $("#types_interest_update");
      types_car_update.html(
        '<option value="" style="color: #000;">เลือกประเภทรถ</option>'
      );
      $.each(response.message, function (index, item) {
        types_car_update.append(
          $('<option style="color: #000;"></option>')
            .val(item.id)
            .html(item.title)
        );
      });

    },
  });

  var select_type_car_opject = $("#types_interest");
  select_type_car_opject.on("change", function () {
    var select_type_car = $("#types_interest").val();
    $.ajax({
      url: serverUrl + "/stock/getbrands/" + select_type_car,
      method: "get",
      async: false,
      success: function (response) {
        var brands = $("#brand_interest");
        brands.html('<option value="">โปรดเลือก</option>');
        $.each(response.message, function (index, item) {
          brands.append(
            $('<option style="color: #000;"></option>')
              .val(item.id)
              .html(item.title)
          );
        });
      },
    });
  });
  $('body').on("click", ".btnEditCustomer", function () {

    let $me = $(this);

    let $customerID = $me.data("customer-id");

    $.ajax({
      type: "GET",
      url: `${serverUrl}/customer/edit/${$customerID}`,
      contentType: "application/json; charset=utf-8",
      success: function (res) {
        if (res.success) {
          let $data = res.data;

          CUSTOMER_LIST.clearForm();

          if ($data.customer_type_id == "3") {
            $("#modalEditCustomer").find(".formCustomerTypeGeneral").hide();
            $("#modalEditCustomer").find(".formCustomerTypeCompany").show();

            let $form = $("#modalEditCustomer").find(
              ".formCustomerTypeCompany"
            );

            $("#modalEditCustomer .modal-title").html(
              "แก้ไขลูกค้า" + " " + $data.fullname
            );
            $form.find("input[name=customer_id]").val($data.id);

            $("#modalEditCustomer")
              .find(".type" + $data.customer_type_id)
              .tab("show");

            $form.find("input[name=fullname]").val($data.fullname);
            $form.find("input[name=tax]").val($data.tax);
            $form.find("input[name=phone]").val($data.phone);
            $form.find("input[name=customer_email]").val($data.customer_email);
            $form.find("textarea[name=address]").val($data.address);
          } else {
            $("#modalEditCustomer").find(".formCustomerTypeGeneral").show();
            $("#modalEditCustomer").find(".formCustomerTypeCompany").hide();

            let $form = $("#modalEditCustomer").find(
              ".formCustomerTypeGeneral"
            );

            $("#modalEditCustomer .modal-title").html(
              "แก้ไขลูกค้า" + " " + $data.fullname
            );
            $form.find("input[name=customer_id]").val($data.id);

            $("#modalEditCustomer")
              .find(".type" + $data.customer_type_id)
              .tab("show");

            $form.find("input[name=fullname]").val($data.fullname);
            $form.find("input[name=phone]").val($data.phone);

            $form.find("input[name=card_id]").val($data.card_id);
            $form.find("input[name=customer_email]").val($data.customer_email);
            $form.find(".cardIDMask").mask("9-9999-99999-99-9");
            $form.find("input[name=birthday]").val($data.birthday);
            $form.find(".dateMask").mask("99/99/9999");
            $form.find("select[name=gender]").val($data.gender).change();
            $form.find("select[name=status]").val($data.status).change();
            $form.find("textarea[name=address]").val($data.address);

            $form.find("input[name=occupation]").val($data.occupation);
            $form.find("input[name=salary]").val($data.salary);
            $form
              .find("select[name=customer_source]")
              .val($data.customer_source)
              .change();
            $form.find("textarea[name=work_place]").val($data.work_place);

            $form.find("input[name=note]").val($data.note);

            $form.find("textarea[name=interest]").val($data.interest);

            $form.find("#ePreviewImg").attr("src", `${CDN_IMG}/uploads/img/${$data.img}`);

            // $form.find("select[name=types_interest_update]").val($data.types_interest);
            // $form.find("select[name=brand_interest_update]").val($data.brand_interest);
            // $form.find("select[name=model_interest_update]").val($data.model_interest);
            $form.find("input[name=color_interest]").val($data.color_interest);
            $form.find("input[name=number_interest]").val($data.number_interest);
          }
        } else {
          Swal.fire({
            icon: "error",
            text: `${res.message}`,
            timer: "2000",
            heightAuto: false,
          });

          CUSTOMER_LIST.reloadPage();
        }
      },
      error: function (res) { },
    });
  });
  $('body').on("click", ".btnDeleteCustomer", function () {
    let $me = $(this);

    let $url = $me.data("url");
    console.log($url);

    Swal.fire({
      text: `คุณต้องการลบ`,
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
            Swal.fire({
              icon: "success",
              text: `${res.message}`,
              timer: "2000",
              heightAuto: false,
            });

            CUSTOMER_LIST.reloadPage();
          },
          error: function (res) {
            Swal.fire({
              icon: "error",
              text: `ไม่สามารถอัพเดทได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ`,
              timer: "2000",
              heightAuto: false,
            });
          },
        });
      }
    });
  });
  //update 
  var select_type_car_opject_update = $("#types_interest_update");
  select_type_car_opject_update.on("change", function () {
    var select_type_car_opject_update = $("#types_interest_update").val();
    $.ajax({
      url: serverUrl + "/stock/getbrands/" + select_type_car_opject_update,
      method: "get",
      async: false,
      success: function (response) {
        var brands_update = $("#brand_interest_update");
        brands_update.html('<option value="">โปรดเลือก</option>');
        $.each(response.message, function (index, item) {
          brands_update.append(
            $('<option style="color: #000;"></option>')
              .val(item.id)
              .html(item.title)
          );
        });
      },
    });
  });

  var car_brands_opject = $("#brand_interest");
  car_brands_opject.on("change", function () {
    var brand_data = $("#brand_interest").val();
    var str_split = brand_data;
    var str_split_result = str_split.split("###");

    $.ajax({
      url: serverUrl + "/stock/get_model/" + str_split_result[0],
      method: "get",
      async: false,
      success: function (response) {
        var model = $("#model_interest");
        model.html('<option value="">เลือกรุ่น</option>');
        $.each(response.message, function (index, item) {
          model.append(
            $('<option style="color: #000;"></option>')
              .val(item.title)
              .html(item.title)
          );
        });
      },
    });
  });

  //update
  var car_brands_opject_update = $("#brand_interest_update");
  car_brands_opject_update.on("change", function () {
    var brand_data_update = $("#brand_interest_update").val();
    var str_split = brand_data_update;
    var str_split_result = str_split.split("###");

    $.ajax({
      url: serverUrl + "/stock/get_model/" + str_split_result[0],
      method: "get",
      async: false,
      success: function (response) {
        var model_update = $("#model_interest_update");
        model_update.html('<option value="">เลือกรุ่น</option>');
        $.each(response.message, function (index, item) {
          model_update.append(
            $('<option style="color: #000;"></option>')
              .val(item.title)
              .html(item.title)
          );
        });
      },
    });
  });

  const CUSTOMER_LIST = {
    // รีเฟรชหน้า
    reloadPage() {
      if ($("#hiddenPath").val() == "create") {
        setTimeout(function () {
          window.location.href = `${serverUrl}/customer/list`;
        }, 1 * 1500);
      } else {
        setTimeout(function () {
          location.reload();
        }, 1 * 1500);
      }
    },

    // เสริชหาลูกค้า
    handleLiveSearch() {
      $("#search").on(
        "keyup",
        _.debounce(function (e) {
          let $me = $(this),
            $customerTypeID = $("#tabCustomerType > li")
              .find(".active")
              .data("customer-type-id"),
            $keyword = $me.val();

          if ($customerTypeID != "") {
            $url = `${serverUrl}/customer/ajaxGetCustomerByType/${$customerTypeID}`;

            if ($keyword != "") {
              $url = `${serverUrl}/customer/ajaxGetCustomerByType/${$customerTypeID}/${$keyword}`;
            }

            $.ajax({
              type: "GET",
              url: $url,
              contentType: "application/json; charset=utf-8",
              success: function (res) {
                if (res.success) {
                  let $data = res.data;

                  $("#contactsTab1").find("div").html($data);
                } else {
                }
              },
              error: function (res) { },
            });
          }
        }, 300)
      ); // < try 300 rather than 100
    },

    // ล้างฟอร์ม
    clearForm() {
      $("#modalAddCustomer").find("form").trigger("reset");
      $("#modalEditCustomer").find("form").trigger("reset");
      $("form").find("input").removeClass("is-valid");
      $("form").find("input").removeClass("is-invalid");
      $("form").find("select").removeClass("is-valid");
      $("form").find("select").removeClass("is-invalid");
      $("form").find("textarea").removeClass("is-valid");
      $("form").find("textarea").removeClass("is-invalid");
    },

    // แท็ป
    handleTab() {
      $("#tabCustomerType > li > a").on("click", function () {
        let $me = $(this),
          $customerTypeID = $me.data("customer-type-id"),
          $keyword = $("#search").val();

        if ($customerTypeID != "") {
          $url = `${serverUrl}/customer/ajaxGetCustomerByType/${$customerTypeID}`;

          if ($keyword != "") {
            $url = `${serverUrl}/customer/ajaxGetCustomerByType/${$customerTypeID}/${$keyword}`;
          }

          $.ajax({
            type: "GET",
            url: $url,
            contentType: "application/json; charset=utf-8",
            success: function (res) {
              if (res.success) {
                let $data = res.data;

                $("#contactsTab1").find("div").html($data);
              } else {
              }
            },
            error: function (res) { },
          });
        }
      });

      $(".tabCustomerTypeID").on("click", function () {
        $("form").find("input").removeClass("is-valid");
        $("form").find("input").removeClass("is-invalid");
        $("form").find("select").removeClass("is-valid");
        $("form").find("select").removeClass("is-invalid");
        $("form").find("textarea").removeClass("is-valid");
        $("form").find("textarea").removeClass("is-invalid");

        let $me = $(this),
          $modal = $me.closest(".modal").attr("id"),
          $customerTypeID = $me.find("a").data("customer-type-id");

        if ($customerTypeID == "3") {
          $("#btnAiAutoInput").hide();
          $("#" + $modal)
            .find(".formCustomerTypeGeneral")
            .hide();
          $("#" + $modal)
            .find(".formCustomerTypeCompany")
            .show();
        } else {
          $("#btnAiAutoInput").show();
          $("#" + $modal)
            .find(".formCustomerTypeGeneral")
            .show();
          $("#" + $modal)
            .find(".formCustomerTypeCompany")
            .hide();
        }
      });
    },

    // จัดการฟอร์มเพิ่มลูกค้า
    handleFormAddCustomer() {
      let $modalAddCustomer = $("#modalAddCustomer");
      let $formCustomerTypeGeneralAdd = $modalAddCustomer.find(
        ".formCustomerTypeGeneral"
      );
      let $formCustomerTypeCompanyAdd = $modalAddCustomer.find(
        ".formCustomerTypeCompany"
      );

      $modalAddCustomer.find(".dateMask").mask("99/99/9999");
      $modalAddCustomer.find(".cardIDMask").mask("9-9999-99999-99-9");

      $formCustomerTypeGeneralAdd
        // บันทึกข้อมูล
        .on("click", ".btnSave", function (e) {
          e.preventDefault();

          // เช็คข้อมูล
          if (
            $formCustomerTypeGeneralAdd.find("input[name=fullname]").val() == ""
          ) {
            alert("กรุณาระบุชื่อ");
            return false;
          }

          if (
            $formCustomerTypeGeneralAdd.find("input[name=phone]").val() == ""
          ) {
            alert("กรุณาระบุเบอร์โทร");
            return false;
          }

          // ผ่าน
          if (
            $formCustomerTypeGeneralAdd.find("input[name=fullname]").val() !=
            "" &&
            $formCustomerTypeGeneralAdd.find("input[name=phone]").val() != ""
          ) {
            let $me = $(this);

            $me.attr("disabled", true);

            let formData = new FormData($formCustomerTypeGeneralAdd[0]);

            let $customerTypeID = $("#modalAddCustomer .tabCustomerTypeID")
              .find("a.active")
              .data("customer-type-id");
            formData.append("customer_type_id", $customerTypeID);

            $.ajax({
              type: "POST",
              url: `${serverUrl}/customer/store`,
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
                      CUSTOMER_LIST.reloadPage();
                    }
                  });

                  CUSTOMER_LIST.reloadPage();
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
                      // LANDING_LIST.reloadPage()
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

      $formCustomerTypeCompanyAdd
        // บันทึกข้อมูล
        .on("click", ".btnSave", function (e) {
          e.preventDefault();

          // เช็คข้อมูล
          if (
            $formCustomerTypeCompanyAdd.find("input[name=fullname]").val() == ""
          ) {
            alert("กรุณาระบุชื่อ");
            return false;
          }

          if ($formCustomerTypeCompanyAdd.find("input[name=tax]").val() == "") {
            alert("กรุณาระบุเลขประจำตัวผู้เสียภาษี");
            return false;
          }

          if (
            $formCustomerTypeCompanyAdd.find("input[name=phone]").val() == ""
          ) {
            alert("กรุณาระบุเบอร์โทร");
            return false;
          }

          // ผ่าน
          if (
            $formCustomerTypeCompanyAdd.find("input[name=fullname]").val() !=
            "" &&
            $formCustomerTypeCompanyAdd.find("input[name=tax]").val() != "" &&
            $formCustomerTypeCompanyAdd.find("input[name=phone]").val() != ""
          ) {
            let $me = $(this);

            $me.attr("disabled", true);

            let formData = new FormData($formCustomerTypeCompanyAdd[0]);

            let $customerTypeID = $("#modalAddCustomer .tabCustomerTypeID")
              .find("a.active")
              .data("customer-type-id");
            formData.append("customer_type_id", $customerTypeID);

            $.ajax({
              type: "POST",
              url: `${serverUrl}/customer/store`,
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
                      CUSTOMER_LIST.reloadPage();
                    }
                  });

                  CUSTOMER_LIST.reloadPage();
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
                      // LANDING_LIST.reloadPage()
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

    // ฟังชั่นก์ AI Auto Input
    handleAiAutoInput() {
      $("#btnAiAutoInput").on("click", function () {
        $("#imageFile").click();
      });

      $("#imageFile").on("change", function () {
        $("#detectImageForm").show();
      });

      $("#btnAiAutoInputClear").on("click", function () {
        $("#detectImageForm").hide();
        document.getElementById("imageFile").value = ""; // Fix bug
      });

      function isThaiNationalID(id) {
        if (!/^[0-9]{13}$/g.test(id)) {
          return false;
        }
        let i;
        let sum = 0;
        for (i = 0, sum = 0; i < 12; i++) {
          sum += Number.parseInt(id.charAt(i)) * (13 - i);
        }
        const checkSum = (11 - (sum % 11)) % 10;
        if (checkSum === Number.parseInt(id.charAt(12))) {
          return true;
        }
        return false;
      }

      const detectImageForm = document.querySelector("#detectImageForm");
      const imageFile = detectImageForm.querySelector("#imageFile");
      const imagePreview = document.querySelector("#imagePreview");
      const result = document.querySelector("#result");
      const setImagePreview = async () => {
        const imageBase64String = await getImageBase64String();
        imagePreview.setAttribute("src", imageBase64String);
      };
      const detectImage = async () => {
        let $form = $("#modalAddCustomer").find(".formCustomerTypeGeneral");

        $("#btnAiAutoInputClear").addClass("disabled");
        $("#btnAiAutoInputSubmit").addClass("disabled");
        $form.addClass("disabled");

        const imageBase64String = await getImageBase64String();
        const data = {
          requests: [
            {
              image: {
                content: imageBase64String.replace(/^data:.+;base64,/, ""),
              },
              features: [{ type: "TEXT_DETECTION" }],
            },
          ],
        };
        const url =
          "https://vision.googleapis.com/v1/images:annotate?key=AIzaSyDV-QyKxHdw-FBjZyH7qI09cLjVWgviRJw";
        const response = await fetch(url, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        });

        let resData = "";
        const jsonResponse = await response.json();
        for (const value of jsonResponse.responses) {
          resData = value.fullTextAnnotation.text;
        }

        const bundleData = {};

        // เอาผลลัพธ์ที่ได้มาตัดบรรทัดและ loop ออกมาทีละบรรทัด
        resData.split("\n").forEach((row, index, arr) => {
          // ตัดข้อความในบรรทัดเป็นท่อนๆด้วย space
          let items = row.split(" ");

          // หาตัวเลขบัตรประชาชน
          const thaiid = items.join("");
          if (isThaiNationalID(thaiid)) {
            bundleData.cardNumber = thaiid;
          }

          // หาตำแหน่งของ คำนำหน้าชื่อ, ชื่อ และ นามสกุล
          if (row.includes("ชื่อตัวและชื่อสกุล")) {
            bundleData.prename = items[1];
            bundleData.firstname = items[2];
            bundleData.lastname = items[3];
          }

          // หาวันเดือนปีเกิด
          if (row.includes("Date of Birth")) {
            bundleData.birthDate = `${items[3]} ${items[4]} ${items[5]}`;
          }

          // ที่อยุ่
          if (row.includes("ที่อยู่")) {
            bundleData.address = `${items.join(" ")} ${arr[index + 1]}`;
          }
        });

        // เดาเพศสภาพจากคำนำหน้าชื่อ
        if (bundleData.prename) {
          bundleData.gender = "ชาย";
          if (
            ["น.ส.", "นางสาว", "นาง", "เด็กหญิง"].includes(bundleData.prename)
          ) {
            bundleData.gender = "หญิง";
          }
        }

        // HANDLE FORM
        if (bundleData) {
          // ชื่อ-นามสกุล
          let $inputName = $form.find("input[name=fullname]");
          $inputName.val(bundleData.firstname + " " + bundleData.lastname);
          $inputName.addClass("is-valid");

          // เลขบัตรประชาชน
          let $inputCardID = $form.find("input[name=card_id]");
          $inputCardID.val(bundleData.cardNumber);
          $inputCardID.addClass("is-valid");
          $(".cardIDMask").mask("9-9999-99999-99-9");

          // วัน/เดือน/ปีเกิด
          let $inputBirthday = $form.find("input[name=birthday]");
          let d = new Date(bundleData.birthDate);
          let a = moment(d).format("DDMMYYYY");
          if (a == "Invalid date") {
            // TODO:: HANDLE
          } else {
            $inputBirthday.val(a);
            $inputBirthday.addClass("is-valid");
            $(".dateMask").mask("99/99/9999");
          }

          // เพศ
          let $inputGender = $form.find("select[name=gender]");
          $inputGender.val(bundleData.gender);
          $inputGender.addClass("is-valid");

          // ที่อยู่
          let $inputAddress = $form.find("textarea[name=address]");
          $inputAddress.val(bundleData.address);
          $inputAddress.addClass("is-valid");
        }

        $("#detectImageForm").hide();
        $("#btnAiAutoInputClear").removeClass("disabled");
        $("#btnAiAutoInputSubmit").removeClass("disabled");
        $form.removeClass("disabled");
      };

      const getImageBase64String = async () => {
        return await toBase64(imageFile.files[0]);
      };
      const toBase64 = (file) =>
        new Promise((resolve, reject) => {
          const reader = new FileReader();
          reader.readAsDataURL(file);
          reader.onload = () => resolve(reader.result);
          reader.onerror = (error) => reject(error);
        });
      imageFile.addEventListener("change", (e) => {
        setImagePreview();
      });
      detectImageForm.addEventListener("submit", (e) => {
        e.preventDefault();
        detectImage();
      });
    },

    // จัดการฟอร์มแก้ไขลูกค้า
    handleFormEditCustomer() {
      let $modalEditCustomer = $("#modalEditCustomer");
      let $formCustomerTypeGeneralEdit = $modalEditCustomer.find(
        ".formCustomerTypeGeneral"
      );
      let $formCustomerTypeCompanyEdit = $modalEditCustomer.find(
        ".formCustomerTypeCompany"
      );

      $modalEditCustomer.find(".dateMask").mask("99/99/9999");
      $modalEditCustomer.find(".cardIDMask").mask("9-9999-99999-99-9");

      $formCustomerTypeGeneralEdit
        // บันทึกข้อมูล
        .on("click", ".btnSave", function (e) {
          e.preventDefault();

          // เช็คข้อมูล
          if (
            $formCustomerTypeGeneralEdit.find("input[name=fullname]").val() ==
            ""
          ) {
            alert("กรุณาระบุชื่อ");
            return false;
          }

          if (
            $formCustomerTypeGeneralEdit.find("input[name=phone]").val() == ""
          ) {
            alert("กรุณาระบุเบอร์โทร");
            return false;
          }

          // ผ่าน
          if (
            $formCustomerTypeGeneralEdit.find("input[name=fullname]").val() !=
            "" &&
            $formCustomerTypeGeneralEdit.find("input[name=phone]").val() != ""
          ) {
            let $me = $(this);

            $me.attr("disabled", true);

            let formData = new FormData($formCustomerTypeGeneralEdit[0]);

            let $customerTypeID = $("#modalEditCustomer .tabCustomerTypeID")
              .find("a.active")
              .data("customer-type-id");
            formData.append("customer_type_id", $customerTypeID);

            $.ajax({
              type: "POST",
              url: `${serverUrl}/customer/update`,
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
                      CUSTOMER_LIST.reloadPage();
                    }
                  });

                  CUSTOMER_LIST.reloadPage();
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
                      // LANDING_LIST.reloadPage()
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

      $formCustomerTypeCompanyEdit
        // บันทึกข้อมูล
        .on("click", ".btnSave", function (e) {
          e.preventDefault();

          // เช็คข้อมูล
          if (
            $formCustomerTypeCompanyEdit.find("input[name=fullname]").val() ==
            ""
          ) {
            alert("กรุณาระบุชื่อ");
            return false;
          }

          if (
            $formCustomerTypeCompanyEdit.find("input[name=tax]").val() == ""
          ) {
            alert("กรุณาระบุเลขประจำตัวผู้เสียภาษี");
            return false;
          }

          if (
            $formCustomerTypeCompanyEdit.find("input[name=phone]").val() == ""
          ) {
            alert("กรุณาระบุเบอร์โทร");
            return false;
          }

          // ผ่าน
          if (
            $formCustomerTypeCompanyEdit.find("input[name=fullname]").val() !=
            "" &&
            $formCustomerTypeCompanyEdit.find("input[name=tax]").val() != "" &&
            $formCustomerTypeCompanyEdit.find("input[name=phone]").val() != ""
          ) {
            let $me = $(this);

            $me.attr("disabled", true);

            let formData = new FormData($formCustomerTypeCompanyEdit[0]);

            let $customerTypeID = $("#modalEditCustomer .tabCustomerTypeID")
              .find("a.active")
              .data("customer-type-id");
            formData.append("customer_type_id", $customerTypeID);

            $.ajax({
              type: "POST",
              url: `${serverUrl}/customer/update`,
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
                      CUSTOMER_LIST.reloadPage();
                    }
                  });

                  CUSTOMER_LIST.reloadPage();
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
                      // LANDING_LIST.reloadPage()
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

    // ปุ่มช่วยในการจัดการต่าง ๆ
    handleButtonUtilities() {
      $(".btnAddCustomer").on("click", function () {
        CUSTOMER_LIST.clearForm();
      });

      // CONTACT_23
      $("#contact_23").on("click", ".btnEditCustomer", function () {
        CUSTOMER_LIST.clearForm();

        let $me = $(this);

        let $customerID = $me.data("customer-id");

        $.ajax({
          type: "GET",
          url: `${serverUrl}/customer/edit/${$customerID}`,
          contentType: "application/json; charset=utf-8",
          success: function (res) {
            if (res.success) {
              let $data = res.data;

              CUSTOMER_LIST.clearForm();

              if ($data.customer_type_id == "3") {
                $("#modalEditCustomer").find(".formCustomerTypeGeneral").hide();
                $("#modalEditCustomer").find(".formCustomerTypeCompany").show();

                let $form = $("#modalEditCustomer").find(
                  ".formCustomerTypeCompany"
                );

                $("#modalEditCustomer .modal-title").html(
                  "แก้ไขลูกค้า" + " " + $data.fullname
                );
                $form.find("input[name=customer_id]").val($data.id);

                $("#modalEditCustomer")
                  .find(".type" + $data.customer_type_id)
                  .tab("show");

                $form.find("input[name=fullname]").val($data.fullname);
                $form.find("input[name=tax]").val($data.tax);
                $form.find("input[name=phone]").val($data.phone);
                $form.find("textarea[name=address]").val($data.address);
              } else {
                $("#modalEditCustomer").find(".formCustomerTypeGeneral").show();
                $("#modalEditCustomer").find(".formCustomerTypeCompany").hide();

                let $form = $("#modalEditCustomer").find(
                  ".formCustomerTypeGeneral"
                );

                $("#modalEditCustomer .modal-title").html(
                  "แก้ไขลูกค้า" + " " + $data.fullname
                );
                $form.find("input[name=customer_id]").val($data.id);

                $("#modalEditCustomer")
                  .find(".type" + $data.customer_type_id)
                  .tab("show");

                $form.find("input[name=fullname]").val($data.fullname);
                $form.find("input[name=phone]").val($data.phone);

                $form.find("input[name=card_id]").val($data.card_id);
                $form.find(".cardIDMask").mask("9-9999-99999-99-9");
                $form.find("input[name=birthday]").val($data.birthday);
                $form.find(".dateMask").mask("99/99/9999");
                $form.find("select[name=gender]").val($data.gender).change();
                $form.find("select[name=status]").val($data.status).change();
                $form.find("textarea[name=address]").val($data.address);

                $form.find("input[name=occupation]").val($data.occupation);
                $form.find("input[name=salary]").val($data.salary);
                $form
                  .find("select[name=customer_source]")
                  .val($data.customer_source)
                  .change();
                $form.find("textarea[name=work_place]").val($data.work_place);

                $form.find("input[name=note]").val($data.note);

                $form.find("textarea[name=interest]").val($data.interest);

                $form.find("#ePreviewImg").attr("src", `${CDN_IMG}/uploads/img/${$data.img}`);

                // $form.find("select[name=types_interest_update]").val($data.types_interest);
                // $form.find("select[name=brand_interest_update]").val($data.brand_interest);
                // $form.find("select[name=model_interest_update]").val($data.model_interest);
                $form.find("input[name=color_interest]").val($data.color_interest);
                $form.find("input[name=number_interest]").val($data.number_interest);
              }
            } else {
              Swal.fire({
                icon: "error",
                text: `${res.message}`,
                timer: "2000",
                heightAuto: false,
              });

              CUSTOMER_LIST.reloadPage();
            }
          },
          error: function (res) { },
        });
      });
      $("#contact_23").on("click", ".btnDeleteCustomer", function () {
        let $me = $(this);

        let $url = $me.data("url");

        Swal.fire({
          text: `คุณต้องการลบ`,
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
                Swal.fire({
                  icon: "success",
                  text: `${res.message}`,
                  timer: "2000",
                  heightAuto: false,
                });

                CUSTOMER_LIST.reloadPage();
              },
              error: function (res) {
                Swal.fire({
                  icon: "error",
                  text: `ไม่สามารถอัพเดทได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ`,
                  timer: "2000",
                  heightAuto: false,
                });
              },
            });
          }
        });
      });

      $(".main-content")
        .on("click", ".btnLockup", function () {
          let $me = $(this);
          let $customerID = $me.data("customer-id");

          $("#modalChatCustomer").modal("hide");

          $.ajax({
            type: "GET",
            url: `${serverUrl}/customer/ajaxGetCustomerByID/${$customerID}`,
            contentType: "application/json; charset=utf-8",
            success: function (res) {
              if (res.success) {
                let $data = res.data;
                $("#contact_23").hide().html($data).fadeIn("slow");
              } else {
              }
            },
            error: function (res) { },
          });
        })
        .on("click", ".btnMessage", function () {
          let $me = $(this);

          let $customerID = $me.data("customer-id");

          CUSTOMER_LIST.reloadChat($customerID);
        })
        .on("click", ".btnSendMessage", function () {
          let $me = $(this);

          let $modal = $("#modalChatCustomer"),
            $text = $modal.find("#inputMessage").val();

          if ($text == "") {
            return;
          }

          $me.addClass("disabled");

          let dataObj = {};

          let $customerID = $me.data("customer-id");

          dataObj = {
            text: $text,
            customerID: $customerID,
          };

          $.ajax({
            type: "POST",
            url: `${serverUrl}/customer/chat`,
            data: JSON.stringify(dataObj),
            contentType: "application/json; charset=utf-8",
          })
            .done(function (res) {
              if (res.success) {
                CUSTOMER_LIST.reloadChat($customerID);
                $me.removeClass("disabled");
              }
            })
            .fail(function () {
              $me.removeClass("disabled");
            });
        })
        .on("keypress", "#inputMessage", function (e) {
          if (e.which == 13) {
            let $me = $(this);

            // $me.attr('disabled', true)

            let $modal = $("#modalChatCustomer"),
              $text = $modal.find("#inputMessage").val();

            if ($text == "") {
              return;
            }

            let dataObj = {};

            let $customerID = $me.data("customer-id");

            dataObj = {
              text: $text,
              customerID: $customerID,
            };

            $.ajax({
              type: "POST",
              url: `${serverUrl}/customer/chat`,
              data: JSON.stringify(dataObj),
              contentType: "application/json; charset=utf-8",
            })
              .done(function (res) {
                if (res.success) {
                  CUSTOMER_LIST.reloadChat($customerID);
                }
              })
              .fail(function () { });
          }
        })
        .on("click", ".btnRemoveMessage", function () {
          let $me = $(this);

          let $messageID = $me.data("message-id"),
            $customerID = $me.data("customer-id");

          Swal.fire({
            text: `คุณต้องการลบ`,
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
                url: `${serverUrl}/customer/chatRemove/${$messageID}`,
                success: function (res) {
                  Swal.fire({
                    icon: "success",
                    text: `${res.message}`,
                    timer: "2000",
                    heightAuto: false,
                  });

                  CUSTOMER_LIST.reloadChat($customerID);
                },
                error: function (res) {
                  Swal.fire({
                    icon: "error",
                    text: `ไม่สามารถอัพเดทได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ`,
                    timer: "2000",
                    heightAuto: false,
                  });
                },
              });
            }
          });
        })
        .on("mouseover", ".msg_container_send", function () {
          let $me = $(this);

          $me.find(".btnRemoveMessage").show();
        })
        .on("mouseout", ".msg_container_send", function () {
          let $me = $(this);

          $me.find(".btnRemoveMessage").hide();
        });
    },

    // การพูดคุยล่าสุด
    handleLastChat() {
      $.ajax({
        type: "GET",
        url: `${serverUrl}/customer/ajaxGetLastChat`,
        contentType: "application/json; charset=utf-8",
        success: function (res) {
          if (res.success) {
            let $data = res.data;
            $("#wrapperLastChat").hide().html($data).fadeIn("slow");
          } else {
          }
        },
        error: function (res) { },
      });
    },

    // โหลดแชทใหม่
    reloadChat($customerID) {
      $.ajax({
        type: "GET",
        url: `${serverUrl}/customer/ajaxGetMessageByCustomerID/${$customerID}`,
        contentType: "application/json; charset=utf-8",
        success: function (res) {
          if (res.success) {
            let $data = res.data;

            function mountHTML() {
              $("#modalChatCustomer").find(".modal-body").html($data);
            }

            function setScroll() {
              let ChatBody = new PerfectScrollbar("#ChatBody", {
                useBothWheelAxes: true,
                suppressScrollX: true,
              });

              let testScroll = $("#ChatBody");
              testScroll.scrollTop(testScroll.prop("scrollHeight"));
            }

            async function main() {
              await mountHTML();
              await setScroll();
            }

            main();

            CUSTOMER_LIST.handleLastChat();
          } else {
            Swal.fire({
              icon: "error",
              text: `${res.message}`,
              timer: "2000",
              heightAuto: false,
            });

            CUSTOMER_LIST.reloadPage();
          }
        },
        error: function (res) { },
      });
    },

    // SET UP
    init() {
      let $customerTypeID = $("#tabCustomerType > li")
        .find(".active")
        .data("customer-type-id"),
        $keyword = "";

      if ($customerTypeID != "") {
        $url = `${serverUrl}/customer/ajaxGetCustomerByType/${$customerTypeID}`;

        if ($keyword != "") {
          $url = `${serverUrl}/customer/ajaxGetCustomerByType/${$customerTypeID}/${$keyword}`;
        }

        $.ajax({
          type: "GET",
          url: $url,
          contentType: "application/json; charset=utf-8",
          success: function (res) {
            if (res.success) {
              let $data = res.data;

              $("#contactsTab1").find("div").html($data);
            } else {
            }
          },
          error: function (res) { },
        });
      }

      if ($("#hiddenPath").val() == "create") {
        $("#modalAddCustomer").modal("show");
      }

      if ($("#defaultCustomerID").val() != "") {
        let $customerID = $("#defaultCustomerID").val();

        $("#modalChatCustomer").modal("hide");

        $.ajax({
          type: "GET",
          url: `${serverUrl}/customer/ajaxGetCustomerByID/${$customerID}`,
          contentType: "application/json; charset=utf-8",
          success: function (res) {
            if (res.success) {
              let $data = res.data;
              $("#contact_23").hide().html($data).fadeIn("slow");
            } else {
            }
          },
          error: function (res) { },
        });
      }

      CUSTOMER_LIST.handleLiveSearch();
      CUSTOMER_LIST.handleTab();
      CUSTOMER_LIST.handleFormAddCustomer();
      CUSTOMER_LIST.handleAiAutoInput();
      CUSTOMER_LIST.handleFormEditCustomer();
      CUSTOMER_LIST.handleButtonUtilities();
      CUSTOMER_LIST.handleLastChat();
    },
  };

  CUSTOMER_LIST.init();
});

var customer_source = $("#customer_source");
customer_source.on("change", function () {
  var val_customer_source = $("#customer_source").val();
  // console.log(val_customer_source);
  if (val_customer_source != "อื่นๆ") {
    $("#source_other").removeClass("show");
    $('#customer_source_other').prop('required', false);
  } else {
    $("#source_other").addClass("show");
    $('#customer_source_other').prop('required', true);
  }
});
var customer_source_edit = $("#customer_source_edit");
customer_source_edit.on("change", function () {
  var val_customer_source_edit = $("#customer_source_edit").val();
  // console.log(val_customer_source_edit);
  if (val_customer_source_edit != "อื่นๆ") {
    $("#source_other_edit").removeClass("show");
    $('#customer_source_other_edit').prop('required', false);
  } else {
    $("#source_other_edit").addClass("show");
    $('#customer_source_other_edit').prop('required', true);
  }
});

//datatable
$("#DataTableCustomer").DataTable({
  "oLanguage": {
    "sInfo": "กำลังแสดง _START_ ถึง _END_ จาก _TOTAL_ แถว หน้า _PAGE_ ใน _PAGES_",
    "sSearch": '',
    "sSearchPlaceholder": "ค้นหา...",
    "oPaginate": {
      "sFirst": "เิริ่มต้น",
      "sPrevious": "ก่อนหน้า",
      "sNext": "ถัดไป",
      "sLast": "สุดท้าย"
    },
  },
  "stripeClasses": [],
  "order": [],
  "pageLength": 10,
  "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "ทั้งหมด"]],
  "scrollX": "TRUE",
});