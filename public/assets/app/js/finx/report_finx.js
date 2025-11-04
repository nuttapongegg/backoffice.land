$(document).ready(function () {

    var date = new Date();
    slow_report_loan(date.getFullYear());
    slowgraphloan(date.getFullYear());
    // slowsummarizeloan(date.getFullYear());

    $("#datepicker").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true
    });

    var lastValue = null;
    $("#datepicker").on("change", function (e) {
        if (lastValue !== e.target.value) {
            slow_report_loan(e.target.value);
            slowgraphloan(e.target.value);
            // slowsummarizeloan(e.target.value);
            lastValue = e.target.value;
        }
    })
    function slow_report_loan(data) {
        $.ajax({
            type: 'GET',
            url: `/finx/ajax-tablesreportfinx/` + data,
            contentType: 'application/json; charset=utf-8',
            success: function (res) {
                if (res.success) {

                    let $data = res.data
                    $("#report_finx").hide().html($data).fadeIn('slow')

                } else {

                }
            },
            error: function (res) { }
        });
    };
    function slowgraphloan(data) {
        $.ajax({
            type: 'GET',
            url: `/loan/ajax-graphloan/` + data,
            contentType: 'application/json; charset=utf-8',
            success: function (res) {
                if (res.success) {

                    let $data = res.data
                    $("#graphloan").hide().html($data).fadeIn('slow')

                } else {

                }
            },
            error: function (res) { }
        });
    };
    
    // function slowsummarizeloan(data) {
    //     $.ajax({
    //         type: 'GET',
    //         url: `/loan/ajax-summarizereportloan/` + data,
    //         contentType: 'application/json; charset=utf-8',
    //         success: function (res) {
    //             if (res.success) {

    //                 let $data = res.data
    //                 $("#summarizeloan").hide().html($data).fadeIn('slow')

    //             } else {

    //             }
    //         },
    //         error: function (res) { }
    //     });
    // };

    $('body').on('click', '#Month_Open_Loan', function () {
        var Month_id = $(this).attr('data-id');
        var years = $(this).attr('name');
        // console.log(years);
        // console.log(Month_id);
        let $DataTable_OpenLoan = $('#DataTable_OpenLoan').DataTable({
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
            "lengthMenu": [[10, 25, 50, 50000], [10, 25, 50, "ทั้งหมด"]],
            "columnDefs": [
                {
                    'className': 'text-center',
                    "targets": [0],
                },
                {
                    'className': 'text-center',
                    "targets": [1],
                },
                {
                    'className': 'text-center',
                    "targets": [2],
                },
                {
                    'className': 'text-center',
                    "targets": [3],
                },
                {
                    'className': 'tx-right',
                    "targets": [4],
                },
                {
                    'className': 'text-center',
                    "targets": [5],
                }
            ],
            destroy: true,
            // searching: false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                'type': 'GET',
                'url': '/finx/ajaxdatatableopenloanfinx/' + Month_id,
                'data': function (d) {
                    d.years = years

                    // console.log(d.years);
                    return d
                },
            },
            "bFilter": true, // to display datatable search
        });
    });

    $('body').on('click', '#Month_Loan_Payment', function () {
        var Month_id = $(this).attr('data-id');
        var years = $(this).attr('name');
        let $DataTable_LoanPayment = $('#DataTable_LoanPayment').DataTable({
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
            "lengthMenu": [[10, 25, 50, 50000], [10, 25, 50, "ทั้งหมด"]],
            "columnDefs": [
                       {
                    'className': 'text-center',
                    "targets": [0],
                },
                {
                    'className': 'text-center',
                    "targets": [1],
                },
                {
                    'className': 'text-center',
                    "targets": [2],
                },
                {
                    'className': 'text-center',
                    "targets": [3],
                },
                {
                    'className': 'tx-right',
                    "targets": [4],
                },
                {
                    'className': 'text-center',
                    "targets": [5],
                }
            ],
            destroy: true,
            // searching: false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                'type': 'GET',
                'url': '/finx/ajaxdatatableloanfinxpayment/' + Month_id,
                'data': function (d) {
                    d.years = years

                    // console.log(d.years);
                    return d
                },
            },
            "bFilter": true, // to display datatable search
        });
    });

    $('body').on('click', '#Month_Loan_Close_Payment', function () {
        var Month_id = $(this).attr('data-id');
        var years = $(this).attr('name');
        let $DataTable_LoanFinxClosePayment = $('#DataTable_LoanFinxClosePayment').DataTable({
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
            "lengthMenu": [[10, 25, 50, 50000], [10, 25, 50, "ทั้งหมด"]],
            "columnDefs": [
                {
                    'className': 'text-center',
                    "targets": [0],
                },
                {
                    'className': 'text-center',
                    "targets": [1],
                },
                {
                    'className': 'text-center',
                    "targets": [2],
                },
                {
                    'className': 'text-center',
                    "targets": [3],
                },
                {
                    'className': 'tx-right',
                    "targets": [4],
                },
                {
                    'className': 'text-center',
                    "targets": [5],
                }
            ],
            destroy: true,
            // searching: false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                'type': 'GET',
                'url': '/finx/ajaxdatatableloanfinxclosepayment/' + Month_id,
                'data': function (d) {
                    d.years = years

                    // console.log(d.years);
                    return d
                },
            },
            "bFilter": true, // to display datatable search
        });
    });

    $("body").on("click", "#Month_Doc_Pay_Month", function () {
        var Month_id = $(this).attr('data-id');
        var years = $(this).attr('name');
        let $DataTable_DocumentsPay = $("#DataTable_DocumentsPay").DataTable({
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

    $('body').on('click', '.pdf_loan_pay', function () {
        var month = $(this).attr('data-month');
        var year = $(this).attr('data-year');
        let url = `${serverUrl}/pdf_loan_pay/` + month +'/'+ year;

        window.open(
            url,
            "Doc",
            "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=992,height=700,top=10,left=10 "
        );
    });

    $('body').on('click', '.pdf_finx_receipt', function () {
        var month = $(this).attr('data-month');
        var year = $(this).attr('data-year');
        let url = `${serverUrl}/pdf_finx_receipt/` + month +'/'+ year;

        window.open(
            url,
            "Doc",
            "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=992,height=700,top=10,left=10 "
        );
    });
});