$(document).ready(function () {
    //When click edit set_Up_Doc
    $('body').on('click', '.setting_Overdue_Status', function () {
        $.ajax({
            url: '/setting_land/edit-overdue-status',
            type: "GET",
            dataType: 'json',
            success: function (res) {
                // let $data = res.data
                $('#setting_Overdue_Status').modal('show');
                // $('#form_Setting_Overdue_Status #token_Loan').val(res.data.token_loan);
                $('#form_Setting_Overdue_Status #overdue_Loan').val(res.data.token_overdue_loan);
                if (res.data.token_loan_status == 1) {
                    $('#form_Setting_Overdue_Status #checkbox_Token_Loan').attr("checked", "checked");
                }
            },
            error: function (data) {
                $('input:checkbox').removeAttr('checked');
                $('#setting_Overdue_Status').modal('show');
            }
        });
    });

    //ModalUpdate
    let $Modal_Setting_Overdue_Status = $("#setting_Overdue_Status")

    let $form_Edit_Setting_Overdue_Status = $Modal_Setting_Overdue_Status.find('form')

    $form_Edit_Setting_Overdue_Status

        // บันทึกข้อมูล
        .on('click', '.btnEditSettingOverdueStatus', function (e) {
            e.preventDefault()

            let $me = $(this)

            $me.attr('disabled', true)

            let formData = new FormData($form_Edit_Setting_Overdue_Status[0])

            formData.append('content', $form_Edit_Setting_Overdue_Status.find('.ql-editor').html())

            $.ajax({
                type: "POST",
                url: `/setting_land/update-overdue-status`,
                data: formData,
                processData: false,
                contentType: false,
            }).done(function (res) {

                if (res.success = 1) {

                    Swal.fire({
                        text: "แก้ไข ตั้งค่าแจ้งเตือนสินเชื่อ สำเร็จ",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "ตกลง",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            setTimeout(function () {
                                window.location = '/setting_land/index'
                            }, 1 * 1500)
                        }
                    })
                    setTimeout(function () {
                        window.location = '/setting_land/index'
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
                            // LANDING_PROMOTION.reloanPage()
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

})