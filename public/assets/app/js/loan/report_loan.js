$(document).ready(function () {

    var date = new Date();
    slow_report_loan(date.getFullYear());
    slowgraphloan(date.getFullYear());
    slowsummarizeloan(date.getFullYear());

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
            slowsummarizeloan(e.target.value);
            lastValue = e.target.value;
        }
    })
    function slow_report_loan(data) {
        $.ajax({
            type: 'GET',
            url: `/loan/ajax-tablesreportloan/` + data,
            contentType: 'application/json; charset=utf-8',
            success: function (res) {
                if (res.success) {

                    let $data = res.data
                    $("#report_loan").hide().html($data).fadeIn('slow')

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
    function slowsummarizeloan(data) {
        $.ajax({
            type: 'GET',
            url: `/loan/ajax-summarizereportloan/` + data,
            contentType: 'application/json; charset=utf-8',
            success: function (res) {
                if (res.success) {

                    let $data = res.data
                    $("#summarizeloan").hide().html($data).fadeIn('slow')

                } else {

                }
            },
            error: function (res) { }
        });
    };

    // //modalEditTargeted
    // let $modalEditTargetedMonth = $("#modalEditTargetedMonth")
    // let $formEditTargetedMonth = $modalEditTargetedMonth.find('form')

    // $formEditTargetedMonth
    //     // บันทึกข้อมูล
    //     .on('click', '.btnSaveTargetedMonth', function (e) {
    //         e.preventDefault()

    //         let $me = $(this)

    //         $me.attr('disabled', true)

    //         let formData = new FormData($formEditTargetedMonth[0])

    //         formData.append('content', $formEditTargetedMonth.find('.ql-editor').html())

    //         $.ajax({
    //             type: "POST",
    //             url: '/report/update-targetedmonth',
    //             data: formData,
    //             processData: false,
    //             contentType: false,
    //         }).done(function (res) {

    //             if (res.success = 1) {

    //                 Swal.fire({
    //                     text: "แก้ไข เป้าหมายต่อเดือน สำเร็จ",
    //                     icon: "success",
    //                     buttonsStyling: false,
    //                     confirmButtonText: "ตกลง",
    //                     customClass: {
    //                         confirmButton: "btn btn-primary"
    //                     }
    //                 }).then(function (result) {
    //                     if (result.isConfirmed) {
    //                         setTimeout(function () {
    //                             window.location = '/report/profit'
    //                         }, 1 * 1500)
    //                     }
    //                 })

    //                 setTimeout(function () {
    //                     window.location = '/report/profit'
    //                 }, 1 * 1500)
    //             }

    //             // กรณี: Server มีการตอบกลับ แต่ไม่สำเร็จ
    //             else {
    //                 // Show error message.
    //                 Swal.fire({
    //                     text: res.message,
    //                     icon: "error",
    //                     buttonsStyling: false,
    //                     confirmButtonText: "ตกลง",
    //                     customClass: {
    //                         confirmButton: "btn btn-primary"
    //                     }
    //                 }).then(function (result) {
    //                     if (result.isConfirmed) {
    //                         // LANDING_PROMOTION.reloadPage()
    //                     }
    //                 })

    //                 $me.attr('disabled', false)
    //             }

    //         }).fail(function (context) {
    //             let messages = context.responseJSON?.messages || 'ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ'
    //             // Show error message.
    //             Swal.fire({
    //                 text: messages,
    //                 icon: "error",
    //                 buttonsStyling: false,
    //                 confirmButtonText: "ตกลง",
    //                 customClass: {
    //                     confirmButton: "btn btn-primary"
    //                 }
    //             })

    //             $me.attr('disabled', false)
    //         })
    //     });

    // //modalEditTargeted
    // let $modalEditTargeted = $("#modalEditTargeted")
    // let $formEditTargeted = $modalEditTargeted.find('form')

    // $formEditTargeted
    //     // บันทึกข้อมูล
    //     .on('click', '.btnSave', function (e) {
    //         e.preventDefault()

    //         let $me = $(this)

    //         $me.attr('disabled', true)

    //         let formData = new FormData($formEditTargeted[0])

    //         formData.append('content', $formEditTargeted.find('.ql-editor').html())

    //         $.ajax({
    //             type: "POST",
    //             url: '/report/update-targeted',
    //             data: formData,
    //             processData: false,
    //             contentType: false,
    //         }).done(function (res) {

    //             if (res.success = 1) {

    //                 Swal.fire({
    //                     text: "แก้ไข เป้าหมาย สำเร็จ",
    //                     icon: "success",
    //                     buttonsStyling: false,
    //                     confirmButtonText: "ตกลง",
    //                     customClass: {
    //                         confirmButton: "btn btn-primary"
    //                     }
    //                 }).then(function (result) {
    //                     if (result.isConfirmed) {
    //                         setTimeout(function () {
    //                             window.location = '/report/profit'
    //                         }, 1 * 1500)
    //                     }
    //                 })

    //                 setTimeout(function () {
    //                     window.location = '/report/profit'
    //                 }, 1 * 1500)
    //             }

    //             // กรณี: Server มีการตอบกลับ แต่ไม่สำเร็จ
    //             else {
    //                 // Show error message.
    //                 Swal.fire({
    //                     text: res.message,
    //                     icon: "error",
    //                     buttonsStyling: false,
    //                     confirmButtonText: "ตกลง",
    //                     customClass: {
    //                         confirmButton: "btn btn-primary"
    //                     }
    //                 }).then(function (result) {
    //                     if (result.isConfirmed) {
    //                         // LANDING_PROMOTION.reloadPage()
    //                     }
    //                 })

    //                 $me.attr('disabled', false)
    //             }

    //         }).fail(function (context) {
    //             let messages = context.responseJSON?.messages || 'ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ'
    //             // Show error message.
    //             Swal.fire({
    //                 text: messages,
    //                 icon: "error",
    //                 buttonsStyling: false,
    //                 confirmButtonText: "ตกลง",
    //                 customClass: {
    //                     confirmButton: "btn btn-primary"
    //                 }
    //             })

    //             $me.attr('disabled', false)
    //         })
    //     });

    $('body').on('click', '#Month_Loan', function () {
        var Month_id = $(this).attr('data-id');
        var years = $(this).attr('name');
        // console.log(years);
        // console.log(Month_id);
        let $DataTable_Loan = $('#DataTable_Loan').DataTable({
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
                    'className': 'text-left',
                    "targets": [2],
                },
                {
                    'className': 'text-left',
                    "targets": [3],
                },
                {
                    'className': 'text-center',
                    "targets": [4],
                },
                {
                    'className': 'text-center',
                    "targets": [5],
                },
                {
                    'className': 'tx-right',
                    "targets": [6],
                },
                {
                    'className': 'text-center',
                    "targets": [7],
                }
            ],
            destroy: true,
            // searching: false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                'type': 'GET',
                'url': '/loan/ajaxdatatableloan/' + Month_id,
                'data': function (d) {
                    d.years = years

                    // console.log(d.years);
                    return d
                },
            },
            "bFilter": true, // to display datatable search
        });

    });

    $('body').on('click', '#Month_Payment', function () {
        var Month_id = $(this).attr('data-id');
        var years = $(this).attr('name');
        // console.log(years);
        // console.log(Month_id);
        let $DataTable_Payment = $('#DataTable_Payment').DataTable({
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
                    'className': 'text-left',
                    "targets": [2],
                },
                {
                    'className': 'text-left',
                    "targets": [3],
                },
                {
                    'className': 'text-center',
                    "targets": [4],
                },
                {
                    'className': 'text-center',
                    "targets": [5],
                },
                {
                    'className': 'text-center',
                    "targets": [6],
                },
                {
                    'className': 'tx-right',
                    "targets": [7],
                },
                {
                    'className': 'text-center',
                    "targets": [8],
                }
            ],
            destroy: true,
            // searching: false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                'type': 'GET',
                'url': '/loan/ajaxdatatablepayment/' + Month_id,
                'data': function (d) {
                    d.years = years

                    // console.log(d.years);
                    return d
                },
            },
            "bFilter": true, // to display datatable search
        });

    });

});