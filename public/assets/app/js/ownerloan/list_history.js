let tableOwnerLoanClose = null;

(function ($) {
  $(document).ready(function () {
    // ✅ init datepicker ก่อน
    flatpickr("#daterange_owner_loan_close", {
      mode: "range",
      dateFormat: "Y-m-d",
      onChange: function () {
        // ✅ เช็คก่อน reload กันพัง
        if (tableOwnerLoanClose) {
          tableOwnerLoanClose.ajax.reload();
        }
      },
    });

    // ✅ ค่อยสร้าง DataTable หลัง ready (ปลอดภัยสุด)
    callTableOwnerLoanHistory();
  });
})(jQuery);

function money(n) {
  const x = Number(n || 0);
  return x.toLocaleString(undefined, {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });
}

function fmtDateTH(d) {
  if (!d) return "-";
  const dt = new Date(d);
  if (isNaN(dt.getTime())) return d; // เผื่อ backend ส่งมาเป็น string ที่แปลไม่ได้
  return dt.toLocaleDateString("th-TH", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
}

function callTableOwnerLoanHistory() {
  // ✅ ถ้าเคยสร้างแล้ว ให้ destroy แบบไม่พัง
  if ($.fn.DataTable.isDataTable("#tableOwnerLoanClose")) {
    $("#tableOwnerLoanClose").DataTable().clear().destroy();
  }

  tableOwnerLoanClose = $("#tableOwnerLoanClose").DataTable({
    responsive: false,
    oLanguage: {
      sSearch: "",
      sSearchPlaceholder: "ค้นหา...",
    },
    order: [],
    stripeClasses: [],
    scrollX: true,
    paging: true,
    pagingType: "full_numbers",
    pageLength: 10,
    lengthMenu: [
      [10, 20, 50, 100, -1],
      [10, 20, 50, 100, "All"],
    ],
    processing: true,
    serverSide: true,
    ajax: {
      type: "POST",
      url: serverUrl + "/ownerloan/tableOwnerLoanHistory",
      data: function (d) {
        d.date = $("#daterange_owner_loan_close").val() || "";
        return d;
      },
    },
    columns: [
      {
        data: null,
        orderable: false,
        className: "text-center",
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        },
      },
      {
        data: null,
        className: "text-center",
        render: function (data) {
          return (
            '<a href="' +
            serverUrl +
            "/ownerloan/detail/" +
            data["owner_code"] +
            '" target="_blank"><font>' +
            data["owner_code"] +
            "</font></a>"
          );
        },
      },
      {
        data: "owner_loan_date",
        className: "text-center",
        render: function (data, type, row) {
          if (type === "display") return fmtDateTH(row.owner_loan_date);
          return row.owner_loan_date;
        },
      },
      {
        data: "closed_at",
        className: "text-center",
        render: function (data, type, row) {
          if (type === "display") return fmtDateTH(row.closed_at);
          return row.closed_at;
        },
      },

      {
        // amount
        data: "amount",
        className: "text-right",
        render: function (data, type, row) {
          if (type === "display")
            return "<font>" + money(row.amount) + "</font>";
          return row.amount;
        },
      },
      {
        // paid_total
        data: "paid_total",
        className: "text-right",
        render: function (data, type, row) {
          if (type === "display")
            return "<font>" + money(row.paid_total) + "</font>";
          return row.paid_total;
        },
      },
      {
        // paid_principal_total
        data: "paid_principal_total",
        className: "text-right",
        render: function (data, type, row) {
          if (type === "display")
            return "<font>" + money(row.paid_principal_total) + "</font>";
          return row.paid_principal_total;
        },
      },
      {
        // paid_interest_total
        data: "paid_interest_total",
        className: "text-right",
        render: function (data, type, row) {
          if (type === "display")
            return "<font>" + money(row.paid_interest_total) + "</font>";
          return row.paid_interest_total;
        },
      },

      {
        data: "last_pay_date",
        className: "text-center",
        render: function (data, type, row) {
          if (type === "display") return fmtDateTH(row.last_pay_date);
          return row.last_pay_date;
        },
      },
      {
        data: "days_from_loan",
        className: "text-center",
        render: function (data, type, row) {
          return row.days_from_loan ?? "-";
        },
      },

      { data: "land_account_name", className: "text-center" },
      { data: "username", className: "text-center" },
      { data: "note" },
    ],

    drawCallback: function (settings, data, start, end, max, total, pre) {
      var api = this.api();
      var num_rows = api.page.info().recordsDisplay;

      $("#count_car_history").html(
        '<div class="tx-primary tx-18" id="count_car_history">ประวัติรายการยืม (' +
          num_rows +
          " รายการ)</div>",
      );
    },
    footerCallback: function (row, data) {
      const api = this.api();

      const num = (v) => {
        if (v === null || v === undefined) return 0;
        // กันกรณีเป็น "10,000.00"
        return parseFloat(String(v).replace(/,/g, "")) || 0;
      };

      const sumByField = (field) => {
        let total = 0;
        api
          .rows({ page: "current" })
          .data()
          .each(function (r) {
            total += num(r[field]);
          });
        return total;
      };

      // ✅ ใส่ค่า footer ตาม index ของคอลัมน์จริงบนตาราง (ดูจาก your columns)
      $(api.column(4).footer()).html(money(sumByField("amount")));
      $(api.column(5).footer()).html(money(sumByField("paid_total")));
      $(api.column(6).footer()).html(money(sumByField("paid_principal_total")));
      $(api.column(7).footer()).html(money(sumByField("paid_interest_total")));
    },

    bFilter: true,
  });
}
