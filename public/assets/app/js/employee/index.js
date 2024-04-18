//previewImage
function PreviewImage(input, previewImage) {
    if (input.files && input.files[0]) {
        var reader = new FileReader()

        reader.onload = function (e) {
            $('#' + previewImage).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
$(document).ready(function () {

    // ModalAdd
    let $ModalAdd = $("#ModalAdd")
    let $formAdd = $ModalAdd.find('form')

    $formAdd
    // บันทึกข้อมูล
    $('body').on('click', '.btnSave', function (e) {
        e.preventDefault()

        // เช็คข้อมูล
        if ($formAdd.find('input[name=name]').val() == '') {
            alert('กรุณาระบุชื่อ-นามสกุล')
            return false;
        }
        // else if ($formAdd.find('input[name=phone_number]').val() == '') {
        //     alert('กรุณาระบุเบอร์โทรศัพท์')
        //     return false;
        // }
        else if ($formAdd.find('select[name=branch_id]').val() == '0') {
            alert('กรุณาเลือกสาขา')
            return false;
        }
        else if ($formAdd.find('select[name=position_id]').val() == '0') {
            alert('กรุณาเลือกตำแหน่ง')
            return false;
        }
        else if ($formAdd.find('input[name=username]').val() == '') {
            alert('กรุณาระบุชื่อผู้ใช้')
            return false;
        }
        // else if ($formAdd.find('input[name=password]').val() == '') {
        //     alert('กรุณาระบุรหัสผ่าน')
        //     return false;
        // }
        if ($formAdd.find('input[name=username]').val() != '') {

            let formData = new FormData($formAdd[0])

            formData.append('content', $formAdd.find('.ql-editor').html())

            $.ajax({
                type: "POST",
                url: `${serverUrl}/employee/username`,
                data: formData,
                processData: false,
                contentType: false,
            }).done(function (res) {

                //กรณี: บันทึกสำเร็จ
                if (res.success == 0) {

                    let $me = $(this)

                    $me.attr('disabled', true)

                    let formData = new FormData($formAdd[0])

                    formData.append('content', $formAdd.find('.ql-editor').html())

                    $.ajax({
                        type: "POST",
                        url: `${serverUrl}/employee/submit-form`,
                        data: formData,
                        processData: false,
                        contentType: false,
                    }).done(function (res) {

                        //กรณี: บันทึกสำเร็จ
                        if (res.success = 1) {

                            Swal.fire({
                                text: "เพิ่ม พนักงาน สำเร็จ",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "ตกลง",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(function (result) {
                                if (result.isConfirmed) {

                                    setTimeout(function () {
                                        window.location = '/employee/list'
                                    }, 1 * 1500)

                                }
                            })
                            setTimeout(function () {
                                window.location = '/employee/list'
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
                else {
                    alert('ชื่อผู้ใช้นี้มีผู้ใช้แล้ว กรุณาระบุชื่อผู้ใช้ใหม่');
                }
            });
            // alert('กรุณาระบุรหัสผ่าน')
            // return false;
        }
        // ผ่าน
    });

    //ModalUpdate
    let $Modaledit = $("#Modaledit")
    let $formEdit = $Modaledit.find('form')

    $formEdit
        // บันทึกข้อมูล
        .on('click', '.btnSave', function (e) {
            e.preventDefault()
            // console.log($formEdit.find('select[name=branch_id]').val())
            // console.log($formEdit.find('input[name=name]').val())
            // เช็คข้อมูล
            if ($formEdit.find('input[name=name]').val() == '') {
                alert('กรุณาระบุชื่อ-นามสกุล')
                return false;
            }
            // else if ($formEdit.find('input[name=phone_number]').val() == '') {
            //     alert('กรุณาระบุเบอร์โทรศัพท์')
            //     return false;
            // }
            else if ($formEdit.find('select[name=branch_id]').val() == '0' || $formEdit.find('select[name=branch_id]').val() == null) {
                alert('กรุณาเลือกสาขา')
                return false;
            }
            // else if ($formEdit.find('select[name=branch_id]').val() == null) {
            //     alert('กรุณาเลือกสาขา')
            //     return false;
            // }
            else if ($formEdit.find('select[name=position_id]').val() == '0') {
                alert('กรุณาเลือกตำแหน่ง')
                return false;
            }
            // else if ($formEdit.find('input[name=username]').val() == '') {
            //     alert('กรุณาระบุชื่อผู้ใช้')
            //     return false;
            // }
            // else if ($formEdit.find('input[name=password]').val() == '') {
            //     alert('กรุณาระบุรหัสผ่าน')
            //     return false;
            // }
            // ผ่าน
            else {

                let $me = $(this)

                $me.attr('disabled', true)

                let formData = new FormData($formEdit[0])

                formData.append('content', $formEdit.find('.ql-editor').html())

                $.ajax({
                    type: "POST",
                    url: `/employee/update`,
                    data: formData,
                    processData: false,
                    contentType: false,
                }).done(function (res) {

                    if (res.success = 1) {

                        Swal.fire({
                            text: "แก้ไข ข้อมูลพนักงาน สำเร็จ",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "ตกลง",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                setTimeout(function () {
                                    window.location = '/employee/list'
                                }, 1 * 1500)
                            }
                        })

                        setTimeout(function () {
                            window.location = '/employee/list'
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

    //modalUpdatePassword
    let $modalEditPassword = $("#modalEditPassword")
    let $formEditPassword = $modalEditPassword.find('form')

    $formEditPassword
        // บันทึกข้อมูล
        .on('click', '.btnSave', function (e) {
            e.preventDefault()

            // เช็คข้อมูล
            if ($formEditPassword.find('input[name=newPassword]').val() == '') {
                alert('กรุณากรอกรหัสผ่านใหม่')
                return false;
            }
            else if ($formEditPassword.find('input[name=confirmPassword]').val() == '') {
                alert('กรุณากรอกยืนยันรหัสผ่านใหม่')
                return false;
            }
            else if ($formEditPassword.find('input[name=newPassword]').val() != $formEditPassword.find('input[name=confirmPassword]').val()) {
                alert('รหัสผ่านไม่ตรงกัน กรุณากรอกยืนยันรหัสผ่านใหม่')
                return false;
            }
            // ผ่าน
            else {
                let $me = $(this)

                $me.attr('disabled', true)

                let formData = new FormData($formEditPassword[0])

                formData.append('content', $formEditPassword.find('.ql-editor').html())

                $.ajax({
                    type: "POST",
                    url: '/employee/update-password',
                    data: formData,
                    processData: false,
                    contentType: false,
                }).done(function (res) {

                    if (res.success = 1) {

                        Swal.fire({
                            text: "แก้ไข รหัสผ่าน สำเร็จ",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "ตกลง",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                setTimeout(function () {
                                    window.location = '/employee/list'
                                }, 1 * 1500)
                            }
                        })

                        setTimeout(function () {
                            window.location = '/employee/list'
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

    //When click edit password
    $('body').on('click', '.btnEditPassword', function () {
        var employee_id = $(this).attr('data-id');
        //    alert(employee_id);
        //    exit();
        $.ajax({
            url: '/employee/edit-paaaword/' + employee_id,
            type: "GET",
            dataType: 'json',
            success: function (res) {
                // let $data = res.data
                $('#modalEditPassword').modal('show');
                $('#updateEmployeePlassword #EmployeeId').val(res.data.id);
                $('#updateEmployeePlassword #username').val(res.data.username);
            },
            error: function (data) { }
        });
    });

    //When click edit Employee
    $('body').on('click', '.btnEdit', function () {
        var employee_id = $(this).attr('data-id');
        //    alert(employee_id);
        //    exit();
        $.ajax({
            url: '/employee/edit/' + employee_id,
            type: "GET",
            dataType: 'json',
            success: function (res) {
                if (res.data.thumbnail != '') {
                    thumbnail = res.data.thumbnail;
                } else {
                    thumbnail = 'nullthumbnail.png';
                }
                // let $data = res.data
                $('#Modaledit').modal('show');
                $('#updateEmployee #EmployeeId').val(res.data.id);
                $('#updateEmployee #name').val(res.data.name);
                $('#updateEmployee #nickname').val(res.data.nickname);
                $('#updateEmployee #phone_number').val(res.data.phone_number);
                $('#updateEmployee #employee_email').val(res.data.employee_email);
                $('#updateEmployee #branch_id').val(res.data.branch_id);
                $('#updateEmployee #position_id').val(res.data.position_id);
                $('#updateEmployee #ePreviewThumbnail').attr('src', `${CDN_IMG}/uploads/img/${thumbnail}`);
            },
            error: function (data) { }
        });
    });

    // Check Url
    if ($("#addUrl").val() == 'add') {
        $("#addEmployee").click();
    }
    //datatable
    $("#basicDataTable-employee").DataTable({
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
                'className': 'text-left',
                "targets": [4],
            },
            {
                'className': 'text-left',
                "targets": [5],
            },
            {
                'className': 'text-left',
                "targets": [6],
            },
            {
                'className': 'text-center',
                "targets": [7],
            }
        ]
    });

    // $("#DataTable-EmployeelogAll").DataTable()
    // $("#DataTable-Employeelogs").DataTable()

    //bthDeleted alert
    $('body').on('click', '.btnDelete', function () {
        var employee_id = $(this).attr('data-id');

        // let $me = $(this)
        // let $url = $me.data('url')
        // alert ($me);
        // exit();
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
                    url: '/employee/delete/' + employee_id,
                    method: 'get',
                    success: function (response) {
                        Swal.fire(
                            'ลบสำเร็จ',
                            response.message,
                            'success'

                        )
                        setTimeout(function () {
                            window.location = '/employee/list'
                        }, 1 * 1500)
                    }
                });
            }
        })
    });
    var employee_id = $("#Url").val();
    $('#DataTableLogs').DataTable({
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
        "columnDefs": [
            {
                'className': 'text-center',
                "width": "5%",
                "targets": [0],
            },
            {
                'className': 'text-left',
                "width": "35%",
                "targets": [1],
            },
            {
                'className': 'text-left',
                "width": "15%",
                "targets": [2],
            },
            {
                'className': 'text-left',
                "width": "15%",
                "targets": [3],
            }
        ],
        "processing": true,
        "serverSide": true,
        "order": [], //init datatable not ordering
        "ajax": {
            'type': 'POST',
            'url': "/employee/ajax-datatable",
            'data': {
                id: employee_id,
                // etc..
            }
        },
        "columns": [{
            data: null,
            "sortable": false,
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            data: "detail"
        },
        {
            data: "formatted_date"
        },
        {
            data: "formatted_time"
        }
        ],
        "bFilter": true, // to display datatable search
    });

    // $('#EmployeelogAll').DataTable({
    //     "oLanguage": {
    //         "sInfo": "กำลังแสดง _START_ ถึง _END_ จาก _TOTAL_ แถว หน้า _PAGE_ ใน _PAGES_",
    //         "sSearch": '',
    //         "sSearchPlaceholder": "ค้นหา...",
    //         "oPaginate": {
    //             "sFirst": "เิริ่มต้น",
    //             "sPrevious": "ก่อนหน้า",
    //             "sNext": "ถัดไป",
    //             "sLast": "สุดท้าย"
    //         },
    //     },
    //     "stripeClasses": [],
    //     "order": [],
    //     "pageLength": 10,
    //     "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "ทั้งหมด"]],
    //     "columnDefs": [
    //         {
    //             'className': 'text-center',
    //             "width": "5%",
    //             "targets": [0],
    //         },
    //         {
    //             'className': 'text-left',
    //             "width": "30%",
    //             "targets": [1],
    //         },
    //         {
    //             'className': 'text-left',
    //             "width": "30%",
    //             "targets": [2],
    //         },
    //         {
    //             'className': 'text-left',
    //             "width": "15%",
    //             "targets": [3],
    //         },
    //         {
    //             'className': 'text-left',
    //             "width": "15%",
    //             "targets": [4],
    //         }
    //     ],
    //     "processing": true,
    //     "serverSide": true,
    //     "order": [], //init datatable not ordering
    //     "ajax": {
    //         'type': 'POST',
    //         'url': "/footer/ajax-datatable"
    //     },
    //     "columns": [{
    //         data: null,
    //         "sortable": false,
    //         render: function (data, type, row, meta) {
    //             return meta.row + meta.settings._iDisplayStart + 1;
    //         }
    //     },
    //     {
    //         data: "username"
    //     },
    //     {
    //         data: "detail"
    //     },
    //     {
    //         data: "formatted_date"
    //     },
    //     {
    //         data: "formatted_time"
    //     }
    //     ],
    //     "bFilter": true, // to display datatable search
    // });


    // $('body').on('click', '.close_checkbox', function () { 
    //     $('input:checkbox').removeAttr('checked');
    // })
    //When click edit Employee
    // $('body').on('click', '.btnEditSetting', function () {
    //     var employee_id = $(this).attr('data-id');
    //     //    alert(employee_id);
    //     //    exit();
    //     $.ajax({
    //         url: '/employee/edit-employee-setting/' + employee_id,
    //         type: "GET",
    //         dataType: 'json',
    //         success: function (res) {
    //             // var checked = 'checked';
    //             $('input:checkbox').removeAttr('checked');
    //             $('#modalSetting').modal('show');
    //             $('#updateEmployeeSetting #EmployeeId').val(employee_id);
    //             // $('#updateEmployeeSetting #checkbox_Document').click();
    //             if(res.employee_setting_status_document == '1'){
    //                 // $('#updateEmployeeSetting #checkbox_Report').click();
    //                 $('#updateEmployeeSetting #checkbox_Document').attr("checked","checked");
    //             }
    //             if(res.employee_setting_status_report == 1){
    //                 $('#updateEmployeeSetting #checkbox_Report').attr("checked","checked");
    //             }
    //             if(res.employee_setting_status_setting  == 1){
    //                 $('#updateEmployeeSetting #checkbox_Setting').attr("checked","checked");
    //             }
    //             if(res.employee_setting_status_landing == 1){
    //                 $('#updateEmployeeSetting #checkbox_Landing').attr("checked","checked");
    //             }
    //             if(res.employee_setting_status_buy_type == 1){
    //                 $('#updateEmployeeSetting #checkbox_Buy_Type').attr("checked","checked");
    //             }
    //             if(res.employee_setting_status_check_car == 1){
    //                 $('#updateEmployeeSetting #checkbox_Check_Car').attr("checked","checked");
    //             }
    //             if(res.employee_setting_status_doc_payment == 1){
    //                 $('#updateEmployeeSetting #checkbox_Doc_Payment').attr("checked","checked");
    //             }

    //             // $("#checkbox_Document").attr("checked","checked");
    //             // if (res.success) {

    //             //     let $data = res.data

    //             //     function mountHTML() {
    //             //         $("#modalSetting").find('.modal-body').html($data)
    //             //     }
    //             //     async function main() {
    //             //         await mountHTML()
    //             //     }

    //             //     main()
    //             // }
    //             // let data = res.data.employee_setting_status_document;
    //             // $('#updateEmployeeSetting #checkbox_Document').className(checked);
    //             // res.data.employee_setting_status_landing;
    //             // $('#updateEmployee #name').val(res.data.name);
    //             // $('#updateEmployee #phone_number').val(res.data.phone_number);
    //             // $('#updateEmployee #branch_id').val(res.data.branch_id);
    //             // $('#updateEmployee #position_id').val(res.data.position_id);
    //             // $('#updateEmployee #ePreviewThumbnail').attr('src', `${serverUrl}/uploads/img/${thumbnail}`);
    //         },
    //         error: function (data) { 
    //             $('input:checkbox').removeAttr('checked');
    //             $('#modalSetting').modal('show');
    //             // if (res.success) {

    //             //     let $data = res.data

    //             //     function mountHTML() {
    //             //         $("#modalSetting").find('.modal-body').html($data)
    //             //     }
    //             //     async function main() {
    //             //         await mountHTML()
    //             //     }

    //             //     main()
    //             // }
    //         }
    //     });
    // });

    // //modalUpdatePassword
    // let $modalSetting = $("#modalSetting")
    // let $formEditSetting = $modalSetting.find('form')

    // $formEditSetting
    //     // บันทึกข้อมูล
    //     .on('click', '.btnSaveSetting', function (e) {
    //         e.preventDefault()

    //             let $me = $(this)

    //             $me.attr('disabled', true)

    //             let formData = new FormData($formEditSetting[0])

    //             $.ajax({
    //                 type: "POST",
    //                 url: '/employee/update-employee-setting',
    //                 data: formData,
    //                 processData: false,
    //                 contentType: false,
    //             }).done(function (res) {

    //                 if (res.success = 1) {

    //                     Swal.fire({
    //                         text: "ตั้งค่าพนักงาน",
    //                         icon: "success",
    //                         buttonsStyling: false,
    //                         confirmButtonText: "ตกลง",
    //                         customClass: {
    //                             confirmButton: "btn btn-primary"
    //                         }
    //                     }).then(function (result) {
    //                         if (result.isConfirmed) {
    //                             setTimeout(function () {
    //                                 window.location = '/employee/list'
    //                             }, 1 * 1500)
    //                         }
    //                     })

    //                     setTimeout(function () {
    //                         window.location = '/employee/list'
    //                     }, 1 * 1500)
    //                 }

    //                 // กรณี: Server มีการตอบกลับ แต่ไม่สำเร็จ
    //                 else {
    //                     // Show error message.
    //                     Swal.fire({
    //                         text: res.message,
    //                         icon: "error",
    //                         buttonsStyling: false,
    //                         confirmButtonText: "ตกลง",
    //                         customClass: {
    //                             confirmButton: "btn btn-primary"
    //                         }
    //                     }).then(function (result) {
    //                         if (result.isConfirmed) {
    //                             // LANDING_PROMOTION.reloadPage()
    //                         }
    //                     })

    //                     $me.attr('disabled', false)
    //                 }

    //             }).fail(function (context) {
    //                 let messages = context.responseJSON?.messages || 'ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ'
    //                 // Show error message.
    //                 Swal.fire({
    //                     text: messages,
    //                     icon: "error",
    //                     buttonsStyling: false,
    //                     confirmButtonText: "ตกลง",
    //                     customClass: {
    //                         confirmButton: "btn btn-primary"
    //                     }
    //                 })

    //                 $me.attr('disabled', false)
    //             })
    //         // }
    //     });
    $(".toggle-password").click(function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    // $(".toggle-newpassword").click(function () {
    //     $(this).toggleClass("fa-eye fa-eye-slash");
    //     var input = $($(this).attr("toggle"));
    //     if (input.attr("type") == "password") {
    //         input.attr("type", "text");
    //     } else {
    //         input.attr("type", "password");
    //     }
    // });
    // $(".toggle-confirmpassword").click(function () {
    //     $(this).toggleClass("fa-eye fa-eye-slash");
    //     var input = $($(this).attr("toggle"));
    //     if (input.attr("type") == "password") {
    //         input.attr("type", "text");
    //     } else {
    //         input.attr("type", "password");
    //     }
    // });
});

