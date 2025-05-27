$(document).ready(function () {
  var date = new Date();
  slowtable(date.getFullYear());

  $("#datepicker").datepicker({
    format: "yyyy",
    // mode: "range",
    viewMode: "years",
    minViewMode: "years",
    autoclose: true,
  });

  var lastValue = null;
  $("#datepicker").on("change", function (e) {
    if (lastValue !== e.target.value) {
      console.log(e.target.value);
      slowtable(e.target.value);
      lastValue = e.target.value;
    }
  });

  $("body").on("click", "#Month_Process", function () {
    var Month_id = $(this).attr("data-id");
    var years = $(this).attr("name");
    // console.log(years);
    // console.log(Month_id);
    let $DataTable_Process = $("#DataTable_Process").DataTable({
      oLanguage: {
        sInfo:
          "กำลังแสดง _START_ ถึง _END_ จาก _TOTAL_ แถว หน้า _PAGE_ ใน _PAGES_",
        sSearch: "",
        sSearchPlaceholder: "ค้นหา...",
        oPaginate: {
          sFirst: "เิริ่มต้น",
          sPrevious: "ก่อนหน้า",
          sNext: "ถัดไป",
          sLast: "สุดท้าย",
        },
      },
      stripeClasses: [],
      order: [],
      pageLength: 10,
      lengthMenu: [
        [10, 25, 50, 50000],
        [10, 25, 50, "ทั้งหมด"],
      ],
      columnDefs: [
        {
          className: "text-center",
          targets: [0],
        },
        {
          className: "text-center",
          targets: [1],
        },
        {
          className: "text-center",
          targets: [2],
        },
        {
          className: "tx-right",
          targets: [3],
        },
        {
          className: "tx-right",
          targets: [4],
        },
        {
          className: "tx-right",
          targets: [5],
        },
        {
          className: "tx-right",
          targets: [6],
        },
        {
          className: "text-center",
          targets: [7],
        },
      ],
      destroy: true,
      // searching: false,
      processing: true,
      serverSide: true,
      ajax: {
        type: "GET",
        url: "/loan/ajaxdatatableprocess/" + Month_id,
        data: function (d) {
          d.years = years;

          // console.log(d.years);
          return d;
        },
      },
      bFilter: true, // to display datatable search
    });
  });

  $("body").on("click", "#Month_Receipt", function () {
    var Month_id = $(this).attr("data-id");
    var years = $(this).attr("name");
    // console.log(years);
    // console.log(Month_id);
    let $DataTable_Receipt = $("#DataTable_Receipt").DataTable({
      oLanguage: {
        sInfo:
          "กำลังแสดง _START_ ถึง _END_ จาก _TOTAL_ แถว หน้า _PAGE_ ใน _PAGES_",
        sSearch: "",
        sSearchPlaceholder: "ค้นหา...",
        oPaginate: {
          sFirst: "เิริ่มต้น",
          sPrevious: "ก่อนหน้า",
          sNext: "ถัดไป",
          sLast: "สุดท้าย",
        },
      },
      stripeClasses: [],
      order: [],
      pageLength: 10,
      lengthMenu: [
        [10, 25, 50, 50000],
        [10, 25, 50, "ทั้งหมด"],
      ],
      columnDefs: [
        {
          className: "text-center",
          targets: [0],
        },
        {
          className: "text-center",
          targets: [1],
        },
        {
          className: "text-center",
          targets: [2],
        },
        {
          className: "text-left",
          targets: [3],
        },
        {
          className: "text-center",
          targets: [4],
        },
        {
          className: "text-center",
          targets: [5],
        },
        {
          className: "tx-right",
          targets: [6],
        },
        {
          className: "text-center",
          targets: [7],
        },
      ],
      destroy: true,
      // searching: false,
      processing: true,
      serverSide: true,
      ajax: {
        type: "GET",
        url: "/loan/ajaxdatatablereceipt/" + Month_id,
        data: function (d) {
          d.years = years;

          // console.log(d.years);
          return d;
        },
      },
      bFilter: true, // to display datatable search
    });
  });

  $("body").on("click", "#Month_Expenses", function () {
    var Month_id = $(this).attr("data-id");
    var years = $(this).attr("name");
    // console.log(years);
    // console.log(Month_id);
    let $DataTable_Expenses = $("#DataTable_Expenses").DataTable({
      oLanguage: {
        sInfo:
          "กำลังแสดง _START_ ถึง _END_ จาก _TOTAL_ แถว หน้า _PAGE_ ใน _PAGES_",
        sSearch: "",
        sSearchPlaceholder: "ค้นหา...",
        oPaginate: {
          sFirst: "เิริ่มต้น",
          sPrevious: "ก่อนหน้า",
          sNext: "ถัดไป",
          sLast: "สุดท้าย",
        },
      },
      stripeClasses: [],
      order: [],
      pageLength: 10,
      lengthMenu: [
        [10, 25, 50, 50000],
        [10, 25, 50, "ทั้งหมด"],
      ],
      columnDefs: [
        {
          className: "text-center",
          targets: [0],
        },
        {
          className: "text-center",
          targets: [1],
        },
        {
          className: "text-center",
          targets: [2],
        },
        {
          className: "text-left",
          targets: [3],
        },
        {
          className: "text-center",
          targets: [4],
        },
        {
          className: "text-center",
          targets: [5],
        },
        {
          className: "tx-right",
          targets: [6],
        },
        {
          className: "text-center",
          targets: [7],
        },
      ],
      destroy: true,
      // searching: false,
      processing: true,
      serverSide: true,
      ajax: {
        type: "GET",
        url: "/loan/ajaxdatatableexpenses/" + Month_id,
        data: function (d) {
          d.years = years;

          // console.log(d.years);
          return d;
        },
      },
      bFilter: true, // to display datatable search
    });
  });
});

function slowtable(data) {
  $.ajax({
    type: "GET",
    url: `/loan/ajax-tablesreportrevenues/` + data,
    contentType: "application/json; charset=utf-8",
    success: function (res) {
      if (res.success) {
        let $data = res.data;
        $("#revenues").hide().html($data).fadeIn("slow");
      } else {
      }
    },
    error: function (res) {},
  });
}
