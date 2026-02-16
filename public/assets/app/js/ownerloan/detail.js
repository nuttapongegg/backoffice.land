(function () {
  // -----------------------------
  // Helpers
  // -----------------------------
  function money(n) {
    const x = Number(n || 0);
    return x.toLocaleString(undefined, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
  }
  $(document).ready(function () {
    easyNumberSeparator({
      selector: ".price_pay_amount",
      separator: ",",
    });
  });

  function badge(status) {
    if (status === "ACTIVE")
      return `<span class="badge bg-success">ACTIVE</span>`;
    if (status === "CANCEL")
      return `<span class="badge bg-danger">CANCEL</span>`;
    return `<span class="badge bg-secondary">${status || "-"}</span>`;
  }

  function calcPayoffTodayAndFill() {
    $.ajax({
      url: `${serverUrl}/ownerloan/ajax-calc-payoff-today/${encodeURIComponent(OWNER_CODE)}`,
      type: "GET",
      dataType: "json",
      success: function (res) {
        if (!res || res.ok !== true) return;

        const totalDue = Number(res.data?.total_due || 0);
        const payDate = res.data?.pay_date || ""; // "Y-m-d"

        $("#modalRemain").text(money(totalDue));
        $("#pay_amount").val(totalDue > 0 ? totalDue : "");
        $("#pay_paid_date").val(payDate);

        easyNumberSeparator({
          selector: ".price_pay_amount",
          separator: ",",
        });

        // ✅ สำคัญ: init หลัง set ค่าแล้ว และล็อกวันนั้น
        initPayDatePicker(payDate);
      },
    });
  }

  // ✅ ไฟล์ชำระ: uploads/owner_loan_pay/xxx
  // (อย่าใช้ uploads/file_owner_loan เพราะนั้นคือไฟล์ตอน "เปิดรายการยืม")
  function proofLink(file) {
    if (!file) return "-";

    // เผื่อ backend ส่งมาเป็น full url อยู่แล้ว
    if (
      String(file).startsWith("http://") ||
      String(file).startsWith("https://")
    ) {
      return `<a class="btn btn-outline-primary btn-sm" target="_blank" href="${file}">ดูไฟล์</a>`;
    }

    const cdn = (window.CDN_IMG || "").replace(/\/$/, "");
    // backend ของคุณ ddoo_upload_file ส่ง "ชื่อไฟล์" หรือ "path"?
    // - ถ้าเป็นแค่ชื่อไฟล์: abc.pdf -> ต่อเป็น /uploads/owner_loan_pay/abc.pdf
    // - ถ้าเป็น path อยู่แล้ว: uploads/owner_loan_pay/abc.pdf -> ต่อกับ CDN ตรงๆ
    const clean = String(file).replace(/^\//, "");
    const url = clean.startsWith("uploads/")
      ? `${cdn}/${clean}`
      : `${cdn}/uploads/owner_loan_pay/${clean}`;

    return `<a class="btn btn-outline-primary btn-sm" target="_blank" href="${url}">ดูไฟล์</a>`;
  }

  // -----------------------------
  // หา OWNER_CODE จาก URL
  // /ownerloan/detail/OWN000001
  // -----------------------------
  let path = window.location.pathname;
  let parts = path.split("/detail/");
  let OWNER_CODE = parts[1] || "";
  if (OWNER_CODE) OWNER_CODE = OWNER_CODE.split("?")[0];

  // base url
  let serverUrl = window.location.origin; // http://localhost:8080

  // DOM
  const $tbody = $("#installmentBody");

  // -----------------------------
  // Summary handling
  // -----------------------------
  let CURRENT_REMAIN = 0;

  function setSummary(summary) {
    const loanAmount = Number(summary?.loan_amount || 0);
    const paidTotal = Number(summary?.paid_total || 0);
    const paidPrincipal = Number(summary?.paid_principal_total || 0);
    const paidInterest = Number(summary?.paid_interest_total || 0);

    const remainTotal = Number(
      summary?.remain_total || Math.max(0, loanAmount - paidPrincipal),
    );
    CURRENT_REMAIN = Math.max(0, remainTotal);

    $("#loanAmount").text(money(loanAmount));

    // ✅ เลือกเอาว่าจะให้ "ชำระแล้ว" หมายถึงอะไร:
    // ถ้าต้องการ "ชำระแล้ว = ตัดเงินต้นแล้ว"
    $("#sumPaid").text(money(paidPrincipal));

    // ถ้าคุณอยากโชว์ดอกด้วย (แนะนำ)
    // เช่นเพิ่ม span ใหม่ id="sumInterest"
    // $("#sumInterest").text(money(paidInterest));

    $("#sumRemain").text(money(CURRENT_REMAIN));
    $("#lastPayDate").text(summary?.last_pay_date || "-");
    $("#modalRemain").text(money(CURRENT_REMAIN));
  }

  function isClosedStatus(status) {
    return ["CLOSED", "PAID", "CANCELLED", "CANCEL"].includes(
      String(status || "").toUpperCase(),
    );
  }

  function updateLoanStatusUI(status) {
    const st = String(status || "-").toUpperCase();

    // badge (ต้องมี <span id="loanStatusBadge"> ในหน้า html)
    const $b = $("#loanStatusBadge");
    if ($b.length) {
      $b.text(st);

      $b.removeClass("bg-success bg-danger bg-secondary bg-warning bg-info");
      if (st === "OPEN") $b.addClass("bg-success");
      else if (["CANCELLED", "CANCEL"].includes(st)) $b.addClass("bg-danger");
      else if (["CLOSED", "PAID"].includes(st)) $b.addClass("bg-secondary");
      else $b.addClass("bg-secondary");
    }

    // ปุ่มเพิ่มการชำระ + ข้อความ (ต้องมี #payDisabledHint ในหน้า html)
    const closed = isClosedStatus(st);
    $("#btnOpenPayModal").prop("disabled", closed);
    $("#payDisabledHint").toggle(closed);
  }

  // -----------------------------
  // flatpickr
  // -----------------------------
  function initPayDatePicker(lockDateStr) {
    if (typeof flatpickr === "undefined") return;

    const el = document.getElementById("pay_paid_date");
    if (!el) return;

    if (el._flatpickr) el._flatpickr.destroy();

    const lockDate = lockDateStr || $("#pay_paid_date").val() || new Date();

    flatpickr(el, {
      disableMobile: true,
      dateFormat: "Y-m-d",
      defaultDate: lockDate,
      clickOpens: false, // ✅ กดแล้วไม่เปิดปฏิทิน
      allowInput: false,
    });
  }

  // -----------------------------
  // Modal open/close
  // -----------------------------
  function openPayModal() {
    if ($("#btnOpenPayModal").prop("disabled")) return;
    // reset
    $("#pay_note").val("");
    $("#pay_file").val("");
    $("#pay_file_preview").html("");
    $("#pay_land_account_id").val("");

    reloadLandAccounts();

    // ใส่ค่าเริ่มต้นเป็น "คงเหลือ"
    $("#pay_amount").val(CURRENT_REMAIN > 0 ? CURRENT_REMAIN : "");

    // เปิด modal
    const modalEl = document.getElementById("modalPayInstallment");
    if (!modalEl) return console.error("ไม่พบ #modalPayInstallment");
    if (typeof bootstrap === "undefined" || !bootstrap.Modal) {
      return console.error(
        "bootstrap.Modal ไม่พร้อมใช้งาน (เช็ค bootstrap.bundle.js)",
      );
    }

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
    setTimeout(calcPayoffTodayAndFill, 50);
    // init date picker ตอนเปิด (กันบางธีม render ช้า)
  }

  $(document).on("click", "#btnOpenPayModal", function (e) {
    e.preventDefault();
    openPayModal();
  });

  // preview file
  $(document).on("change", "#pay_file", function () {
    const f = this.files && this.files[0];
    if (!f) {
      $("#pay_file_preview").html("");
      return;
    }

    if (f.type && f.type.startsWith("image/")) {
      const url = URL.createObjectURL(f);
      $("#pay_file_preview").html(
        `<div class="text-center">
          <img src="${url}" class="img-fluid rounded" style="max-height:260px;">
        </div>`,
      );
      return;
    }

    if (f.type === "application/pdf") {
      $("#pay_file_preview").html(
        `<div class="text-muted">ไฟล์ PDF: ${f.name}</div>`,
      );
      return;
    }

    $("#pay_file_preview").html(
      `<div class="text-muted">ไฟล์: ${f.name}</div>`,
    );
  });

  // -----------------------------
  // Load Payments
  // -----------------------------
  function reloadPayments() {
    if (!OWNER_CODE) {
      $tbody.html(
        `<tr><td colspan="8" class="text-center text-danger">ไม่พบ OWNER_CODE</td></tr>`,
      );
      return;
    }

    $tbody.html(
      `<tr><td colspan="8" class="text-center text-muted">กำลังโหลดข้อมูล...</td></tr>`,
    );

    $.ajax({
      url: `${serverUrl}/ownerloan/ajax-payments/${encodeURIComponent(OWNER_CODE)}`,
      type: "GET",
      dataType: "json",
      success: function (res) {
        if (!res || res.ok !== true) {
          $tbody.html(
            `<tr><td colspan="8" class="text-center text-danger">${(res && res.message) || "โหลดไม่สำเร็จ"}</td></tr>`,
          );
          return;
        }

        // ✅ summary
        setSummary(res.summary || {});
        updateLoanStatusUI(res.meta?.loan_status);

        // ✅✅ เพิ่มตรงนี้: id ของรายการ ACTIVE ล่าสุด
        const lastActiveId = Number(res.meta?.last_active_payment_id || 0);

        const rows = res.rows || [];
        if (!rows.length) {
          $tbody.html(
            `<tr><td colspan="8" class="text-center text-muted">ยังไม่มีประวัติการชำระ</td></tr>`,
          );
          return;
        }

        let html = "";
        rows.forEach((r, idx) => {
          const isActive = r.status === "ACTIVE";
          const isLast = isActive && Number(r.id) === lastActiveId;

          let actionHtml = "";
          if (r.status === "CANCEL") {
            actionHtml = `<button class="btn btn-sm btn-outline-secondary" disabled>ยกเลิกแล้ว</button>`;
          } else if (isLast) {
            actionHtml = `<button class="btn btn-sm btn-outline-danger btnCancelPay" data-id="${r.id}">ยกเลิก</button>`;
          } else if (isActive) {
            // ✅ ACTIVE แต่ไม่ใช่รายการล่าสุด
            actionHtml = `<button class="btn btn-sm btn-outline-dark" disabled title="ต้องยกเลิกรายการล่าสุดก่อน">
            ยกเลิก
          </button>`;
          } else {
            actionHtml = `<button class="btn btn-sm btn-outline-secondary" disabled>-</button>`;
          }

          html += `
          <tr>
            <td class="text-center">${idx + 1}</td>
            <td class="text-center">${r.pay_date || "-"}</td>
            <td class="text-end">
              <div>${money(r.pay_amount)}</div>
              <div class="small text-muted">ต้น ${money(r.principal_amount)} | ดอก ${money(r.interest_amount)}</div>
            </td>
            <td>${r.note || "-"}</td>
            <td class="text-center">${r.username || "-"}</td>
            <td class="text-center">${proofLink(r.owner_loan_pay_file)}</td>
                <td class="text-center">${badge(r.status)}</td>
                <td class="text-center">${actionHtml}</td>
              </tr>
            `;
        });

        $tbody.html(html);
      },
      error: function (xhr) {
        console.log("ajax error:", xhr.status, xhr.responseText);
        $tbody.html(
          `<tr><td colspan="8" class="text-center text-danger">โหลดข้อมูลผิดพลาด (${xhr.status})</td></tr>`,
        );
      },
    });
  }

  // -----------------------------
  // Pay (POST)
  // -----------------------------
  $(document).on("click", "#btnSubmitPay", function () {
    const ownerLoanId = $("#pay_owner_loan_id").val();
    const payDate = $("#pay_paid_date").val(); // flatpickr string Y-m-d
    const payAmount = $("#pay_amount").val();
    const note = $("#pay_note").val();
    const landAccountId = $("#pay_land_account_id").val();
    const file = $("#pay_file")[0].files && $("#pay_file")[0].files[0];

    // ✅ แจ้งเตือนแบบสวย (แทน alert)
    if (!ownerLoanId) {
      notif({
        type: "danger",
        msg: "ไม่พบ owner_loan_id",
        position: "right",
        fade: true,
        time: 1800,
      });
      return;
    }
    if (!payAmount) {
      notif({
        type: "danger",
        msg: "กรุณากรอกยอดชำระ",
        position: "right",
        fade: true,
        time: 1800,
      });
      return;
    }
    if (!landAccountId) {
      notif({
        type: "danger",
        msg: "กรุณาเลือกบัญชีโอนออก",
        position: "right",
        fade: true,
        time: 1800,
      });
      return;
    }

    const fd = new FormData();
    fd.append("owner_loan_id", ownerLoanId);
    fd.append("pay_date", payDate);
    fd.append("pay_amount", payAmount);
    fd.append("note", note);
    fd.append("land_account_id", landAccountId);
    if (file) fd.append("pay_file", file);

    // ✅ ป้องกันกดซ้ำ
    const $btn = $("#btnSubmitPay");
    $btn.prop("disabled", true).addClass("disabled");

    $.ajax({
      url: `${serverUrl}/ownerloan/ajax-payoff-today`,
      type: "POST",
      data: fd,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (res) {
        // ❌ บันทึกไม่สำเร็จ (เปลี่ยน alert -> notif/Swal)
        if (!res || res.ok !== true) {
          const msg = (res && res.message) || "บันทึกไม่สำเร็จ";

          // เคสสำคัญ: เงินในบัญชีไม่พอ ให้เด้งชัดๆ
          if (msg.includes("ยอดเงินในบัญชีไม่พอ")) {
            Swal.fire({
              icon: "error",
              title: "ยอดเงินไม่พอ",
              text: msg,
              confirmButtonText: "รับทราบ",
            });
          } else {
            notif({
              type: "danger",
              msg: msg,
              position: "right",
              fade: true,
              time: 2000,
            });
          }
          return;
        }

        // ✅ สำเร็จ
        notif({
          type: "success",
          msg: "บันทึกสำเร็จ",
          position: "right",
          fade: true,
          time: 1200,
        });

        // ปิด modal
        const el = document.getElementById("modalPayInstallment");
        const modal = bootstrap.Modal.getInstance(el);
        if (modal) modal.hide();

        reloadPayments();
        reloadLandAccounts();
      },
      error: function (xhr) {
        console.log("pay error:", xhr.status, xhr.responseText);

        Swal.fire({
          icon: "error",
          title: "ระบบขัดข้อง",
          text: `กรุณาลองใหม่ (HTTP ${xhr.status})`,
          confirmButtonText: "รับทราบ",
        });
      },
      complete: function () {
        // ✅ เปิดปุ่มกลับเสมอ ไม่ว่าผ่าน/ไม่ผ่าน
        $btn.prop("disabled", false).removeClass("disabled");
      },
    });
  });

  function reloadLandAccounts(selectedId = "") {
    $.ajax({
      url: `${serverUrl}/ownerloan/ajax-land-accounts`,
      type: "GET",
      dataType: "json",
      success: function (res) {
        if (!res || res.ok !== true) return;

        let html = `<option value="">-- เลือกบัญชี --</option>`;
        (res.rows || []).forEach((a) => {
          const selected =
            String(a.id) === String(selectedId) ? "selected" : "";
          html += `
          <option value="${a.id}" ${selected}>
            ${a.land_account_name} (คงเหลือ ${money(a.land_account_cash)})
          </option>
        `;
        });

        $("#pay_land_account_id").html(html);
      },
    });
  }

  // -----------------------------
  // Cancel payment
  // -----------------------------
  $(document).on("click", ".btnCancelPay", function () {
    const id = $(this).data("id");
    if (!id) return;

    Swal.fire({
      title: "ยกเลิกรายการชำระ",
      text: "คุณต้องการยกเลิกรายการชำระนี้ ?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      cancelButtonText: "ยกเลิก",
      confirmButtonText: "ตกลง",
    }).then((result) => {
      if (!result.isConfirmed) return;

      $.ajax({
        url: `${serverUrl}/ownerloan/cancel-payment`,
        type: "POST",
        dataType: "json",
        data: { id: id },
        success: function (res) {
          if (!res || res.ok !== true) {
            notif({
              type: "danger",
              msg: (res && res.message) || "ยกเลิกไม่สำเร็จ",
              position: "right",
              fade: true,
              time: 1400,
            });
            return;
          }

          notif({
            type: "success",
            msg: "ยกเลิกรายการชำระสำเร็จ!",
            position: "right",
            fade: true,
            time: 1200,
          });

          reloadPayments();
          reloadLandAccounts();
        },
        error: function (xhr) {
          console.log("cancel-payment error:", xhr.status, xhr.responseText);
          notif({
            type: "danger",
            msg: "ระบบขัดข้อง",
            position: "right",
            fade: true,
            time: 1400,
          });
        },
      });
    });
  });

  // -----------------------------
  // Cancel owner loan
  // -----------------------------
  window.cancelOwnerLoan = function (owner_code) {
    if (!owner_code) return;

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
      if (!result.isConfirmed) return;

      $.ajax({
        url: `${serverUrl}/ownerloan/cancel-ownerloan`,
        type: "POST",
        dataType: "json",
        data: { owner_code: owner_code },
        success: function (res) {
          if (!res || res.ok !== true) {
            notif({
              type: "danger",
              msg: (res && res.message) || "ยกเลิกไม่สำเร็จ",
              position: "right",
              fade: true,
              time: 1400,
            });
            return;
          }

          notif({
            type: "success",
            msg: "ยกเลิกสำเร็จ!",
            position: "right",
            fade: true,
            time: 1400,
          });

          // ✅ ถ้าคุณอยาก redirect ไป list เหมือน loan
          setTimeout(function () {
            window.location = "/ownerloan/list"; // <-- ปรับ path ให้ตรงระบบคุณ
          }, 1500);

          // หรือถ้าอยากแค่ reload ก็ใช้:
          // setTimeout(() => location.reload(), 800);
        },
        error: function (xhr) {
          console.log("cancel-ownerloan error:", xhr.status, xhr.responseText);
          notif({
            type: "danger",
            msg: "ระบบขัดข้อง",
            position: "right",
            fade: true,
            time: 1400,
          });
        },
      });
    });
  };

  // init
  $(document).ready(function () {
    initPayDatePicker();
    reloadPayments();
    // reloadLandAccounts();
  });
})();

let currentLoanId = null;

$("body").on("click", ".modal_Edit_Owner_Interest", function () {
  let id = $(this).data("id");
  let rate = $(this).attr("data-rate");

  currentLoanId = id;

  $("#owner_loan_id").val(id);
  $("#loan_interest_rate").val(rate);

  $("#modal_Edit_Owner_Interest").modal("show");
});

$(".btnSaveLoanInterest").on("click", function () {
  let rate = $("#loan_interest_rate").val();

  $.ajax({
    url: "/ownerloan/update-owner-loan-interest",
    type: "POST",
    data: {
      owner_loan_id: currentLoanId,
      loan_interest_rate: rate,
    },
  }).done(function (res) {
    if (res.success) {
      let $badge = $(
        ".modal_Edit_Owner_Interest[data-id='" + currentLoanId + "']",
      );

      $badge.text(rate + "% ต่อปี").attr("data-rate", rate);

      $("#modal_Edit_Owner_Interest").modal("hide");
    }
  });
});
