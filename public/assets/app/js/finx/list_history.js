(function ($) {
  callTableFinxHistory();
})(jQuery);

$(document).ready(function () {
  flatpickr("#daterange_finx_close", {
    mode: "range",
    dateFormat: "Y-m-d",
    onChange: function (selectedDates) {
      tableFinxClose.ajax.reload();
    },
  });
});

function callTableFinxHistory() {
  $("#tableFinxClose").DataTable().clear().destroy();
  tableFinxClose = $("#tableFinxClose").DataTable({
    responsive: false,
    oLanguage: {
      // sInfo: "กำลังแสดง หน้า _PAGE_ ใน _PAGES_",
      sSearch: "",
      sSearchPlaceholder: "ค้นหา...",
    },
    order: [],
    stripeClasses: [],
    scrollX: "TRUE",
    paging: true,
    pagingType: "full_numbers",
    pageLength: 10,
    lengthMenu: [
      [10, 20, 50, 100, -1],
      [10, 20, 50, 100, "All"],
    ],
    // Processing indicator
    processing: true,
    // DataTables server-side processing mode
    serverSide: true,
    // Initial no order.
    order: [],
    ajax: {
      type: "POST",
      url: serverUrl + "/finx/tableFinxHistory",
      data: function (d) {
        const $date = $("#daterange_finx_close").val();
        if ($date !== "") {
          d.date = $date;
        } else {
          d.date = "";
        }
        return d;
      },
    },
    columnDefs: [
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
        data: "loan_date_close",
        targets: 6,
        render: function (data, type, row, meta) {
          if (type == "display") {
            const date = new Date(data["loan_date_close"]);
            const result = date.toLocaleDateString("th-TH", {
              year: "numeric",
              month: "short",
              day: "numeric",
            });
            return result;
          }
          return data["loan_date_close"];
        },
      },
      // loan_payment_date_fix วันเริ่มชำระ เปลี่ยนเป็น  loan_date_promise วันที่ขอสินเชื่อ
      {
        data: "loan_date_promise",
        targets: 12,
        render: function (data, type, row, meta) {
          if (type == "display") {
            const date = new Date(data["loan_date_promise"]);
            const newDate = new Date(date.setMonth(date.getMonth()));
            const result = newDate.toLocaleDateString("th-TH", {
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
        orderable: false,
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
        data: "loan_address",
      },
      {
        data: "loan_area",
      },
      {
        data: "loan_number",
      },
      {
        data: null,
      },
      {
        data: "loan_type",
        className: "text-center",
      },
      {
        data: null,
      },
      {
        data: null,
        render: function (data, type, row, meta) {
          if (data["loan_status"] == "ON_STATE") {
            return "<font class='tx-success'>กำลังผ่อนชำระ</font>";
          } else if (data["loan_status"] == "CLOSE_STATE") {
            return "<font>สินเชื่อชำระเสร็จสิ้น</font>";
          }
        },
      },
      {
        data: null,
        className: "text-right",
        render: function (data, type, row, meta) {
          return (
            "<font>" +
            new Intl.NumberFormat().format(
              Number(data["installment_3pct"]).toFixed(2)
            ) +
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
              Number(data["loan_close_payment"]).toFixed(2)
            ) +
            "</font>"
          );
        },
      },
      {
        data: null,
      },
      {
        data: "loan_remnark",
      },
      {
        orderable: false,
        data: null,
        render: function (data, type, row) {
          var id;
          if (data["loan_code"] != "") {
            id = data["loan_code"];
          } else {
            id = "-";
          }
          return (
            '<div class="d-flex align-items-center"><button type="button" class="btn btn-primary-light btn-icon pdf_kleanx ms-1" id="' +
            data["loan_code"] +
            '" onclick="reportFinxPrint(this.id)"><i class="fe fe-printer"> </i></button></div>'
          );
        },
      },
    ],
    drawCallback: function (settings, data, start, end, max, total, pre) {
      var api = this.api();
      var num_rows = api.page.info().recordsDisplay;

      $("#count_car_history").html(
        '<div class="tx-primary tx-18" id="count_car_history">รายการสินเชื่อที่ปิดแล้ว (' +
          num_rows +
          " ราย)</div>"
      );
    },
    createdRow: function (tr, tdsContent) {},

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
      Total_close_payment = api
        .column(11, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b.loan_close_payment);
        }, 0);

      Total_summary_no_vat = api
        .column(8, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b.loan_summary_no_vat);
        }, 0);

      Total_installment_3pct = api
        .column(10, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return intVal(a) + intVal(b.installment_3pct);
        }, 0);

      // Update footer
      number_close_payment = parseFloat(Total_close_payment).toFixed(2);
      $(api.column(11).footer()).html(
        Number(number_close_payment).toLocaleString()
      );

      number_installment_3pct = parseFloat(Total_installment_3pct).toFixed(2);
      $(api.column(10).footer()).html(
        Number(number_installment_3pct).toLocaleString()
      );

      number_summary_no_vat = parseFloat(Total_summary_no_vat).toFixed(2);
      $(api.column(8).footer()).html(
        Number(number_summary_no_vat).toLocaleString()
      );
    },
    bFilter: true,
  });
}

function reportFinxPrint(id) {
  let url = `${serverUrl}/pdf_finx/` + id;

  window.open(
    url,
    "Doc",
    "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=1024,height=900,top=10,left=500"
  );
}
