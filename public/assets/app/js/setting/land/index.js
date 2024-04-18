$(document).ready(function () {

    //modalEditTargeted
    let $modalEditRealInvestment = $("#modalEditRealInvestment")
    let $formEditRealInvestment = $modalEditRealInvestment.find('form')

    $formEditRealInvestment
        // บันทึกข้อมูล
        .on('click', '.btnSaveRealInvestment', function (e) {
            e.preventDefault()

            let $me = $(this)

            $me.attr('disabled', true)

            let formData = new FormData($formEditRealInvestment[0])

            formData.append('content', $formEditRealInvestment.find('.ql-editor').html())

            $.ajax({
                type: "POST",
                url: '/setting_land/update-RealInvestment',
                data: formData,
                processData: false,
                contentType: false,
            }).done(function (res) {

                if (res.success = 1) {

                    Swal.fire({
                        text: "แก้ไข เงินลงทุนจริง สำเร็จ",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "ตกลง",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            setTimeout(function () {
                                window.location = '/setting_land/land'
                            }, 1 * 1500)
                        }
                    })

                    setTimeout(function () {
                        window.location = '/setting_land/land'
                    }, 1 * 1500)
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
                            confirmButton: "btn btn-primary"
                        }
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            // LANDING_PROMOTION.reloadPage()
                        }
                    })

                    $me.attr('disabled', false)
                }

            }).fail(function (context) {
                let messages = context.responseJSON?.messages || 'ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ'
                // Show error message.
                Swal.fire({
                    text: messages,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "ตกลง",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                })

                $me.attr('disabled', false)
            })
        });

    // modalAddLandAccount
    let $modalAddLandAccount = $("#modalAddLandAccount")
    let $formAddLandAccount = $modalAddLandAccount.find('form')

    $formAddLandAccount
        // บันทึกข้อมูล
        .on('click', '.btnSaveLandAccount', function (e) {
            e.preventDefault()

            // เช็คข้อมูล
            if ($formAddLandAccount.find('input[name=land_account_name]').val() == '') {
                alert('กรุณาระบุชื่อบัญชี')
                return false;
            }
            if ($formAddLandAccount.find('input[name=land_account_cash]').val() == '') {
                alert('กรุณาระบุจำนวนเงิน')
                return false;
            }
            // ผ่าน
            else {

                let $me = $(this)

                $me.attr('disabled', true)

                let formData = new FormData($formAddLandAccount[0])

                formData.append('content', $formAddLandAccount.find('.ql-editor').html())

                $.ajax({
                    type: "POST",
                    url: `${serverUrl}/setting_land/add-land-account`,
                    data: formData,
                    processData: false,
                    contentType: false,
                }).done(function (res) {

                    //กรณี: บันทึกสำเร็จ
                    if (res.success = 1) {

                        Swal.fire({
                            text: "เพิ่ม บัญชีสินเชื่อ สำเร็จ",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "ตกลง",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {

                                setTimeout(function () {
                                    window.location = '/setting_land/land'
                                }, 1 * 1500)

                            }
                        })
                        setTimeout(function () {
                            window.location = '/setting_land/land'
                        }, 1 * 1500)
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
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                // LANDING_PROMOTION.reloadPage()
                            }
                        })

                        $me.attr('disabled', false)
                    }

                }).fail(function (context) {
                    let messages = context.responseJSON?.messages || 'ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ'
                    // Show error message.
                    Swal.fire({
                        text: messages,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "ตกลง",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    })

                    $me.attr('disabled', false)
                })
            }
        });

    //When click edit LandAccoun
    $('body').on('click', '.btnEditLandAccount', function () {
        var LandAccoun_id = $(this).attr('data-id');
        //    alert(employee_id);
        //    exit();
        $.ajax({
            url: '/setting_land/edit-land-account/' + LandAccoun_id,
            type: "GET",
            dataType: 'json',
            success: function (res) {
                // let $data = res.data
                $('#modalEditLandAccount').modal('show');
                $('#updateLandAccount #LandAccountId').val(res.data.id);
                $('#updateLandAccount #edit_land_account_name').val(res.data.land_account_name);
                $('#updateLandAccount #edit_land_account_cash').val(res.data.land_account_cash);
            },
            error: function (data) { }
        });
    });

    //modalEditLandAccount
    let $modalEditLandAccount = $("#modalEditLandAccount")
    let $formEditLandAccount = $modalEditLandAccount.find('form')

    $formEditLandAccount
        // บันทึกข้อมูล
        .on('click', '.btnEditAccount', function (e) {
            e.preventDefault()

            // เช็คข้อมูล
            if ($formEditLandAccount.find('input[name=edit_land_account_name]').val() == '') {
                alert('กรุณาระบุชื่อบัญชี')
                return false;
            }
            if ($formEditLandAccount.find('input[name=edit_land_account_cash]').val() == '') {
                alert('กรุณาระบุจำนวนเงิน')
                return false;
            }
            // ผ่าน
            else {
                let $me = $(this)

                $me.attr('disabled', true)

                let formData = new FormData($formEditLandAccount[0])

                formData.append('content', $formEditLandAccount.find('.ql-editor').html())

                $.ajax({
                    type: "POST",
                    url: '/setting_land/update-land-account',
                    data: formData,
                    processData: false,
                    contentType: false,
                }).done(function (res) {

                    if (res.success = 1) {

                        Swal.fire({
                            text: "แก้ไข บัญชีสินเชื่อ สำเร็จ",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "ตกลง",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                setTimeout(function () {
                                    window.location = '/setting_land/land'
                                }, 1 * 1500)
                            }
                        })

                        setTimeout(function () {
                            window.location = '/setting_land/land'
                        }, 1 * 1500)
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
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                // LANDING_PROMOTION.reloadPage()
                            }
                        })

                        $me.attr('disabled', false)
                    }

                }).fail(function (context) {
                    let messages = context.responseJSON?.messages || 'ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ'
                    // Show error message.
                    Swal.fire({
                        text: messages,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "ตกลง",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    })

                    $me.attr('disabled', false)
                })
            }
        });

    //btnDeleteLandAccount alert
    $('body').on('click', '.btnDeleteLandAccount', function () {
        var LandAccoun_id = $(this).attr('data-id');
        Swal.fire({
            text: `คุณต้องการลบ`,
            icon: "warning",
            buttonsStyling: false,
            confirmButtonText: "ตกลง",
            showCloseButton: true,
            customClass: {
                confirmButton: "btn btn-primary",
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/setting_land/delete-land-account/' + LandAccoun_id,
                    method: 'get',
                    success: function (response) {
                        Swal.fire(
                            'ลบสำเร็จ',
                            response.message,
                            'success'

                        )
                        setTimeout(function () {
                            window.location = '/setting_land/land'
                        }, 1 * 1500)
                    }
                });
            }
        })
    });

    // modalAddLandAccountPlus
    let $modalAddLandAccountPlus = $("#modalAddLandAccountPlus")
    let $formAddLandAccountPlus = $modalAddLandAccountPlus.find('form')

    $formAddLandAccountPlus
        // บันทึกข้อมูล
        .on('click', '.btnSaveLandAccountPlus', function (e) {
            e.preventDefault()

            // เช็คข้อมูล
            if ($formAddLandAccountPlus.find('input[name=land_account_money_plus]').val() == '') {
                alert('กรุณาระบุจำนวนเงิน')
                return false;
            }

            // ผ่าน
            else {

                let $me = $(this)

                $me.attr('disabled', true)

                let formData = new FormData($formAddLandAccountPlus[0])

                formData.append('content', $formAddLandAccountPlus.find('.ql-editor').html())

                $.ajax({
                    type: "POST",
                    url: `${serverUrl}/setting_land/add-land-account-plus`,
                    data: formData,
                    processData: false,
                    contentType: false,
                }).done(function (res) {

                    //กรณี: บันทึกสำเร็จ
                    if (res.success = 1) {

                        Swal.fire({
                            text: "เพิ่ม เงินเข้าบัญชี สำเร็จ",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "ตกลง",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {

                                setTimeout(function () {
                                    window.location = '/setting_land/land'
                                }, 1 * 1500)

                            }
                        })
                        setTimeout(function () {
                            window.location = '/setting_land/land'
                        }, 1 * 1500)
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
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                // LANDING_PROMOTION.reloadPage()
                            }
                        })

                        $me.attr('disabled', false)
                    }

                }).fail(function (context) {
                    let messages = context.responseJSON?.messages || 'ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ'
                    // Show error message.
                    Swal.fire({
                        text: messages,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "ตกลง",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    })

                    $me.attr('disabled', false)
                })
            }
        });

    // modalAddLandAccountMinus
    let $modalAddLandAccountMinus = $("#modalAddLandAccountMinus")
    let $formAddLandAccountMinus = $modalAddLandAccountMinus.find('form')

    $formAddLandAccountMinus
        // บันทึกข้อมูล
        .on('click', '.btnSaveLandAccountMinus', function (e) {
            e.preventDefault()

            // เช็คข้อมูล
            if ($formAddLandAccountMinus.find('input[name=land_account_money_minus]').val() == '') {
                alert('กรุณาระบุจำนวนเงิน')
                return false;
            }
            
            // ผ่าน
            else {

                let $me = $(this)

                $me.attr('disabled', true)

                let formData = new FormData($formAddLandAccountMinus[0])

                formData.append('content', $formAddLandAccountMinus.find('.ql-editor').html())

                $.ajax({
                    type: "POST",
                    url: `${serverUrl}/setting_land/add-land-account-minus`,
                    data: formData,
                    processData: false,
                    contentType: false,
                }).done(function (res) {

                    //กรณี: บันทึกสำเร็จ
                    if (res.success = 1) {

                        Swal.fire({
                            text: "ลบ เงินออกจากบัญชี สำเร็จ",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "ตกลง",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {

                                setTimeout(function () {
                                    window.location = '/setting_land/land'
                                }, 1 * 1500)

                            }
                        })
                        setTimeout(function () {
                            window.location = '/setting_land/land'
                        }, 1 * 1500)
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
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                // LANDING_PROMOTION.reloadPage()
                            }
                        })

                        $me.attr('disabled', false)
                    }

                }).fail(function (context) {
                    let messages = context.responseJSON?.messages || 'ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ'
                    // Show error message.
                    Swal.fire({
                        text: messages,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "ตกลง",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    })

                    $me.attr('disabled', false)
                })
            }
        });

    // modalTransferLandAccount
    let $modalTransferLandAccount = $("#modalTransferLandAccount")
    let $formlTransferLandAccount = $modalTransferLandAccount.find('form')

    $formlTransferLandAccount
        // บันทึกข้อมูล
        .on('click', '.btnSaveTransferLandAccount', function (e) {
            e.preventDefault()

            // เช็คข้อมูล
            if ($formlTransferLandAccount.find('select[name=land_account_name]').val() == null) {
                alert('กรุณาเลือกชื่อบัญชีที่ต้องการโอน')
                return false;
            } else if ($formlTransferLandAccount.find('input[name=transfer_money_land_account]').val() == '') {
                alert('กรุณาระบุจำนวนเงิน')
                return false;
            }

            // ผ่าน
            else {

                let $me = $(this)

                $me.attr('disabled', true)

                let formData = new FormData($formlTransferLandAccount[0])

                formData.append('content', $formlTransferLandAccount.find('.ql-editor').html())

                $.ajax({
                    type: "POST",
                    url: `${serverUrl}/setting_land/transfer-land-account`,
                    data: formData,
                    processData: false,
                    contentType: false,
                }).done(function (res) {

                    //กรณี: บันทึกสำเร็จ
                    if (res.success = 1) {

                        Swal.fire({
                            text: "โอนเงิน สำเร็จ",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "ตกลง",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {

                                setTimeout(function () {
                                    window.location = '/setting_land/land'
                                }, 1 * 1500)

                            }
                        })
                        setTimeout(function () {
                            window.location = '/setting_land/land'
                        }, 1 * 1500)
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
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                // LANDING_PROMOTION.reloadPage()
                            }
                        })

                        $me.attr('disabled', false)
                    }

                }).fail(function (context) {
                    let messages = context.responseJSON?.messages || 'ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ'
                    // Show error message.
                    Swal.fire({
                        text: messages,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "ตกลง",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    })

                    $me.attr('disabled', false)
                })
            }
        });

    //When click btnAddLandAccountMinus
    $('body').on('click', '.btnAddLandAccountMinus', function () {
        var LandAccount_id = $(this).attr('data-id');
        //    alert(employee_id);
        //    exit();
        $('#modalAddLandAccountMinus').modal('show');
        $('#AddLandAccountMinus #LandAccountId').val(LandAccount_id);
    });

    //When click LandAccountPlus
    $('body').on('click', '.btnAddLandAccountPlus', function () {
        var LandAccount_id = $(this).attr('data-id');
        //    alert(employee_id);
        //    exit();
        $('#modalAddLandAccountPlus').modal('show');
        $('#AddLandAccountPlus #LandAccountId').val(LandAccount_id);
    });

    $('body').on('click', '.btnTransferLandAccount', function () {
        var LandAccount_id = $(this).attr('data-id');
        $('#modalTransferLandAccount').modal('show');
        $('#TransferLandAccount #LandAccountId').val(LandAccount_id);
        // console.log(LandAccount_id);
        // ใช้ AJAX เรียก PHP script เพื่อดึงข้อมูล Cash Flow
        $.ajax({
            url: '/setting_land/get-land-account', // เปลี่ยนเป็น URL ของ PHP script ที่ดึงข้อมูล Cash Flow
            method: 'GET', // หรือ 'POST' ขึ้นกับการรับข้อมูลจากฝั่ง PHP
            dataType: 'json', // รูปแบบข้อมูลที่คุณต้องการใช้งาน (JSON, XML, HTML, เป็นต้น)
            success: function (data) {
                var selectElement = $('#TransferLandAccount select[name="land_account_name"]');

                // ลบค่าทั้งหมดออกจากเมนูเลือก
                selectElement.empty();

                // โหลดรายการ Cash Flow และเพิ่มตัวเลือกในเมนูเลือก
                var LandAccounts = data.data;
                // console.log(data.data);
                LandAccounts.forEach(function (LandAccount) {
                    var LandAccountId = parseInt(LandAccount.id);

                    // ตรวจสอบว่า LandAccountId ไม่เท่ากับ parseInt(LandAccount_id)
                    if (LandAccountId !== parseInt(LandAccount_id)) {
                        // เซ็ตค่าเริ่มต้นให้เป็นตัวเลือกที่ถูกเลือกเมื่อตรงกับ LandAccount_id ที่เลือก
                        var isSelected = LandAccountId === parseInt(LandAccount_id) ? 'selected' : '';
                        var option = '<option value="' + LandAccount.id + '" ' + isSelected + '>' + LandAccount.land_account_name + '</option>';
                        selectElement.append(option);
                    }
                });
            },
            error: function (error) {
                console.error('เกิดข้อผิดพลาดในการดึงข้อมูล Cash Flow: ' + error);
            }
        });
    });

    $("#DataTable-LandAccount").DataTable({
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
        // "scrollX": "TRUE",
        "stripeClasses": [],
        "order": [],
        "pageLength": 5,
        "lengthMenu": [[5, 15, 25, 100, 50000], [5, 15, 25, 100, "ทั้งหมด"]],
        "columnDefs": [
            {
                'className': 'text-center',
                "targets": [0],
            },
            {
                'className': 'text-left',
                "targets": [1],
            },
            {
                'className': 'tx-right',
                "targets": [2],
            },
            {
                'className': 'tx-center',
                "targets": [3],
            },
            {
                'className': 'text-center',
                "targets": [4],
            }
            // {
            //     'className': 'text-center',
            //     "targets": [5],
            // }
        ]
    });
    $("#DataTable-LandAccountLogs").DataTable({
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
        // "scrollX": "TRUE",
        "stripeClasses": [],
        "order": [],
        "pageLength": 5,
        "lengthMenu": [[5, 15, 25, 100, 50000], [5, 15, 25, 100, "ทั้งหมด"]],

        "processing": true,
        "serverSide": true,
        "ajax": {
            'type': 'POST',
            'url': "/setting_land/ajax-tableslandaccountlogs",
        },
        "columnDefs": [
            {
                'className': 'text-center',
                "targets": [0],
            },
            {
                'className': 'text-left',
                "targets": [1],
            },
            {
                'className': 'text-left',
                "targets": [2],
            },
            {
                'className': 'tx-right',
                "targets": [3],
            },
            {
                'className': 'text-left',
                "targets": [4],
            },
            {
                'className': 'text-center',
                "targets": [5],
            },
            {
                'className': 'text-center',
                "targets": [6],
            }
        ]
    });

    $('body').on('click', '#ReportLandAccount', function () {
        var LandAccount_id = $(this).attr('data-id');
        $('#DataTable_ReportLandAccount').DataTable().clear().destroy();
        $('#DataTable_ReportLandAccount').DataTable({
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
            // "scrollX": "TRUE",
            "stripeClasses": [],
            "order": [],
            "pageLength": 5,
            "lengthMenu": [[5, 15, 25, 100, 50000], [5, 15, 25, 100, "ทั้งหมด"]],

            "processing": true,
            "serverSide": true,
            "ajax": {
                'type': 'POST',
                'url': "/setting_land/ajax-tableslandaccountreport/" + LandAccount_id,
            },
            "columnDefs": [
                {
                    'className': 'text-center',
                    "targets": [0],
                },
                {
                    'className': 'text-left',
                    "targets": [1],
                },
                {
                    'className': 'text-left',
                    "targets": [2],
                },
                {
                    'className': 'tx-right',
                    "targets": [3],
                },
                {
                    'className': 'text-left',
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
            ]
        });
    });
});