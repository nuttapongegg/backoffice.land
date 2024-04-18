function select2Banks(data) {

    if (!data.id) { return data.text; }

    if (data.id == 'เลือกธนาคาร') { return data.text; }

    var $data = $(
        '<span class="d-flex align-items-center"><img src="../assets/img/banks/ic-' + data.element.dataset.icon + '.png" class="rounded-circle avatar-xs me-1" /> '
        + data.text + '</span>'
    );
    return $data;
}

$(document).ready(function () {

    let timeleft = 29;
    let downloadTimer = setInterval(function () {
        if (timeleft <= 0) {
            clearInterval(downloadTimer)
            $('#switchbtn-light-theme').click()
            $('.profiles-gate-container').fadeOut(1000)
        } else {
            document.getElementById("countdown").innerHTML = '(' + timeleft + ')'
        }
        timeleft -= 1
    }, 3000)

    const APP = {

        // ลิสใบสำคัญ
        docTable() {
            $('#tableDoc').DataTable().clear().destroy()
            let $table = $('#tableDoc').DataTable({
                "oLanguage": {
                    "sInfo": "กำลังแสดง หน้า _PAGE_ ใน _PAGES_",
                    "sSearch": '',
                    "sSearchPlaceholder": "ค้นหา...",
                },
                "stripeClasses": [],
                "pageLength": 5,
                "lengthMenu": [[5, 25, 50, -1], [5, 25, 50, "ทั้งหมด"]],

                // Processing indicator
                "processing": true,
                // DataTables server-side processing mode
                "serverSide": true,
                // Initial no order.
                "order": [],
                // Load data from an Ajax source
                "ajax": {
                    "url": `${serverUrl}/document/getLists`,
                    "type": "POST",
                    "data": function (d) {

                        // d.data = {
                        //     docType: $docType
                        // }
                        const $date = $("#daterange_doc").val();
                        if ($date !== "") {
                            d.date = $date;
                        }
                        let $tab = $(".tabDocType a.active")

                        d.docType = $tab.data('doc-type')

                        return d
                    }
                },
                //Set column definition initialisation properties
                "columnDefs": [
                    {
                        'className': 'text-center',
                        "targets": [0],
                        "width": '10%'
                    },
                    {
                        'className': 'text-center',
                        "targets": [1],
                        "width": '10%'
                    },
                    {
                        'className': 'text-left',
                        "targets": [2],
                        "width": '10%'
                    },
                    {
                        'className': 'text-left',
                        "targets": [3],
                        "width": '10%'
                    },
                    {
                        'className': 'text-center',
                        "targets": [4],
                        "width": '10%'
                    },
                    {
                        'className': 'text-center',
                        "targets": [5],
                        "width": '10%'
                    },
                    {
                        'className': 'text-center',
                        "targets": [6],
                        "width": '10%'
                    },
                    {
                        'className': 'text-center',
                        "targets": [7],
                        "orderable": false,
                        "width": '20%'
                    }
                ]
            })
        },

        // ล้าง DocForm
        docFormClear() {
            $('form[name=formDoc]').trigger("reset")
        },

        // ตั้งค่าฟอร์มใบสำคัญเริ่มต้น
        docSetEnvForm() {

            $("#formDate").flatpickr()

            easyNumberSeparator({
                selector: '.price',
                separator: ','
            })

            $("#chequeBankDate").flatpickr()

            $(".select2-banks").select2({
                templateResult: select2Banks,
                templateSelection: select2Banks,
                escapeMarkup: function (m) { return m }
            })
        },

        // จัดการฟอร์มใบสำคัญเมื่อเปิด
        docOpenForm($docType) {

            $("#wrapperDocPaymentTypeEtc").hide()
            $("#wrapperACForm").hide()

            let $tab = $(".tabDocType"),
                $modal = $("#docModal"),
                $inputType = $("input[name=doc_type]")
            var date = new Date();

            if (date.getMonth() <= 8) {
                var date_doc = date.getFullYear() + '0' + (date.getMonth() + 1);
            } else {
                var date_doc = date.getFullYear() + '' + (date.getMonth() + 1);
            }
            // $.ajax({
            //     type: 'GET',
            //     url: `${serverUrl}/document/docNumber/${$docType}`,
            //     contentType: 'application/json; charset=utf-8',
            //     success: function (res) {
            //        
            //             console.log('YES')
            //         
            //             
            //         
            //     },
            //     error: function (res) { console.log('NO') }
            // });
            // jQuery.ajax({
            //     type: "GET",
            //     url: `${serverUrl}/document/docNumber/${$docType}`,
            //     success: function (data) {
            //         console.log('Test data');
            //     }
            // });

            switch ($docType) {
                case 'ใบสำคัญรับ':
                    $title = 'ใบสำคัญรับ'
                    // $inputDocType = 'RV-' + date_doc
                    $tabAhrefClass = '.tabDocType1'
                    $modal.find('.selectCar').show()
                    $modal.find('.selectCustomer').show()
                    $modal.find('.selectSeller').hide()
                    $modal.find('.selectWHT').show()
                    $('.tableTitle-x').html('ลูกค้า')
                    $('.wrapperFile').hide()
                    break

                case 'ใบสำคัญจ่าย':
                    $title = 'ใบสำคัญจ่าย'
                    // $inputDocType = 'PV-' + date_doc
                    $tabAhrefClass = '.tabDocType2'
                    $modal.find('.selectCar').show()
                    $modal.find('.selectCustomer').hide()
                    $modal.find('.selectSeller').show()
                    $modal.find('.selectWHT').show()
                    $('.tableTitle-x').html('ผู้ขาย')
                    $('.wrapperFile').show()
                    break

                case 'ใบส่วนลด':
                    $title = 'ใบส่วนลด'
                    // $inputDocType = 'CN-' + date_doc
                    $tabAhrefClass = '.tabDocType3'
                    $modal.find('.selectCar').show()
                    $modal.find('.selectCustomer').show()
                    $modal.find('.selectSeller').hide()
                    $modal.find('.selectWHT').show()
                    $('.tableTitle-x').html('ลูกค้า')
                    $('.wrapperFile').hide()
                    break

                case 'รายจ่าย':
                    $title = 'รายจ่าย'
                    // $inputDocType = 'DN-' + date_doc
                    $tabAhrefClass = '.tabDocType4'
                    $modal.find('.selectCar').hide()
                    $modal.find('.selectCustomer').hide()
                    $modal.find('.selectSeller').show()
                    $modal.find('.selectWHT').hide()
                    $('.tableTitle-x').html('ผู้ขาย')
                    $('.wrapperFile').hide()
                    break
            }

            // จัดการ Modal
            $modal.find('.modal-title').html($title)
            $modal.find('.table-title').html('ประวัติ' + $title)

            // จัดการ Tab
            $tab.find('a').removeClass('active')
            $tab.find($tabAhrefClass).addClass('active')

            // จัดการ Form
            $inputType.val($docType)

            var $tabDocType = $(".tabDocType a.active"),

            documentType = $tabDocType.data('doc-type')

            $.ajax({
                url: `${serverUrl}/document/docNumber/`+documentType,
                type: "GET",
                dataType: 'json',
                success: function (res) {
                    switch (documentType) {
                        case 'ใบสำคัญรับ':
                            $inputDocType = 'RV-' + date_doc + res
                            break
                        case 'ใบสำคัญจ่าย':
                            $inputDocType = 'PV-' + date_doc + res
                            break
                        case 'ใบส่วนลด':
                            $inputDocType = 'CN-' + date_doc + res
                            break
                        case 'รายจ่าย':
                            $inputDocType = 'DN-' + date_doc + res
                            break
                    }
                    $('input[name=doc_number]').val($inputDocType);
                    // console.log(res)
                },
                error: function (data) { }
            });

            // $('input[name=doc_number]').val($inputDocType)
            $('.wrapperFile').find('.dropify-preview').css('display', 'none')
            $('.wrapperFile').find('.dropify-render').html('')
            $('.docDropify').dropify()

            // LOAD ใบสำคัญ
            APP.docTable()
        },

        // จัดการฟอร์มใบสำคัญ
        handleDocForm() {

            let $modalDoc = $("#docModal")
            let $form = $modalDoc.find('form')

            let $FORM_KEY = $modalDoc.find('form').data('form-key')

            /*************************************************************
             * EVENT
             */

            // เมนู NAV
            $('.selectDoc').on('click', function () {

                let $me = $(this),
                    $docType = $me.data('doc-type')

                APP.docFormClear()
                APP.docSetEnvForm()
                APP.docOpenForm($docType)
            })

            // เมนู TAB
            $('.tabDocType').on('click', function () {

                let $me = $(this),
                    $docType = $me.find('a').data('doc-type')

                APP.docFormClear()
                APP.docSetEnvForm()
                APP.docOpenForm($docType)
            })

            $modalDoc
                // เรียกข้อมูลมาแก้ไข
                .on('click', '.btnEdit', function () {
                    let $me = $(this),
                        $docID = $me.data('doc-id')

                    $.ajax({
                        type: "GET",
                        url: `${serverUrl}/document/edit/${$docID}`,
                        contentType: "application/json; charset=utf-8"
                    }).done(function (res) {
                        if (res.success == 1) {
                            $("#checkbox_price_vat").removeClass("show")
                            $("#open_wht").removeClass("show");
                            $('input:checkbox').removeAttr('checked');
                            APP.docFormClear()
                            APP.docOpenForm()
                            $("#docModal").animate({ scrollTop: 100 }, 'slow')
                            let $data = res.data

                            APP.docOpenForm($data.doc_type)

                            let $docPaymentType = $data.doc_payment_type,
                                $docPrice = $data.price,
                                $cash_flow_name = $data.cash_flow_name

                            $form.find('input[name=doc_id]').val($data.id)
                            $form.find('input[name=doc_type]').val($data.doc_type)
                            $form.find('input[name=doc_number]').val($data.doc_number)
                            $("#formDate").flatpickr()
                            $form.find('input[name=doc_date]').val($data.doc_date)
                            $form.find('input[name=title]').val($data.title)
                            $form.find('input[name=price]').val($docPrice)
                            easyNumberSeparator({
                                selector: '.price',
                                separator: ','
                            })
                            $form.find('input[name=car_stock_id]').val($data.car_stock_id)
                            $form.find('input[name=car_title]').val($data.car_title)

                            $form.find('select[name=doc_payment_type]').val($docPaymentType)
                            $form.find('select[name=cash_flow_name]').val($cash_flow_name)

                            $form.find('input[name=doc_payment_type_etc]').val($data.doc_payment_type_etc)
                            $form.find('input[name=cheque_name]').val($data.cheque_name)
                            $form.find('input[name=cheque_ref]').val($data.cheque_ref)
                            $form.find('input[name=cheque_bank_title]').val($data.cheque_bank_title)
                            $form.find('input[name=cheque_bank_branch]').val($data.cheque_bank_branch)
                            $form.find('input[name=cheque_bank_no]').val($data.cheque_bank_no)
                            $("#chequeBankDate").flatpickr()
                            $form.find('input[name=cheque_bank_date]').val($data.cheque_bank_date)
                            $form.find('input[name=note]').val($data.note)
                            $form.find('input[name=doc_detail]').val($data.doc_detail)
                            $form.find('input[name=reference_number]').val($data.reference_number)
                            $form.find('select[name=price_vat]').val($data.price_vat)
                            if ($data.price_vat == '') {
                                $form.find('select[name=price_vat]').val('ราคาไม่รวมภาษี')
                            }
                            if ($data.price_vat != 'ราคารวมภาษี') {
                                $("#checkbox_price_vat").toggleClass("show");
                            }
                            if ($data.doc_vat == 1) {
                                $('#docModal #checkbox_vat').attr("checked", "checked");
                            }
                            if ($data.doc_wht == 1) {
                                $('#docModal #checkbox_wht').attr("checked", "checked");
                                $("#open_wht").toggleClass("show");
                            }
                            $form.find('input[name=wht_percent]').val($data.wht_percent)

                            switch ($data.doc_type) {
                                case 'ใบสำคัญรับ':
                                    $form.find('input[name=customer_id]').val($data.customer_id)
                                    $form.find('input[name=customer_title]').val($data.customer_title)
                                    $form.find('input[name=seller_id]').val(0)
                                    $form.find('input[name=seller_title]').val('')
                                    break

                                case 'ใบสำคัญจ่าย':
                                    $form.find('input[name=customer_id]').val(0)
                                    $form.find('input[name=customer_title]').val('')
                                    $form.find('input[name=seller_id]').val($data.seller_id)
                                    $form.find('input[name=seller_title]').val($data.seller_title)

                                    if ($data.filePath != '') {
                                        $form.find('.dropify-preview').css('display', 'block')
                                        $form.find('.dropify-render').html('<img src="' + `${CDN_IMG}/uploads/file/${$data.filePath}` + '" alt="">')
                                    }
                                    break

                                case 'ใบส่วนลด':
                                    $form.find('input[name=customer_id]').val($data.customer_id)
                                    $form.find('input[name=customer_title]').val($data.customer_title)
                                    $form.find('input[name=seller_id]').val(0)
                                    $form.find('input[name=seller_title]').val('')
                                    break

                                case 'รายจ่าย':
                                    $form.find('input[name=customer_id]').val(0)
                                    $form.find('input[name=customer_title]').val('')
                                    $form.find('input[name=seller_id]').val($data.seller_id)
                                    $form.find('input[name=seller_title]').val($data.seller_title)
                                    break
                            }

                            switch ($docPaymentType) {
                                case 'เงินสด':
                                    $("#wrapperDocPaymentTypeEtc").hide()
                                    $("#wrapperACForm").hide()
                                    break

                                case 'A/C':
                                    $("#wrapperACForm").show()
                                    $("#wrapperDocPaymentTypeEtc").hide()
                                    break

                                case 'อื่น ๆ':
                                    $("#wrapperACForm").hide()
                                    $("#wrapperDocPaymentTypeEtc").show()
                                    break
                            }

                            $form.find('.btnSave').html('ยืนยันแก้ไขข้อมูล')
                        }
                    })
                })
                // ลบใบสำคัญ
                .on('click', '.btnDelete', function () {

                    let $me = $(this)

                    let $url = $me.data('url'),
                        $name = $me.data('document-number')

                    $me.prop('disabled', true)

                    Swal.fire({
                        text: `คุณต้องการลบ ${$name}`,
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "ตกลง",
                        showCloseButton: true,
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(function (result) {
                        if (result.isConfirmed) {

                            $.ajax({
                                type: 'POST',
                                url: $url,
                                success: function (res) {

                                    $me.prop('disabled', false)

                                    Swal.fire({
                                        icon: "success",
                                        text: `${res.message}`,
                                        timer: '2000',
                                        heightAuto: false
                                    })

                                    APP.docTable()
                                },
                                error: function (res) {
                                    Swal.fire({
                                        icon: "error",
                                        text: `ไม่สามารถลบได้ กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ให้บริการ`,
                                        timer: '2000',
                                        heightAuto: false
                                    })
                                }
                            })
                        }
                    })
                })
                // ดึงรายการ
                .on('click', '#openTitleList', function () {
                    let x = screen.width / 2 - 800 / 2
                    let y = screen.height / 2 - 600 / 2

                    let $tab = $(".tabDocType a.active")
                    let $docType = $tab.data('doc-type')

                    let configParam = $.param({
                        'actionBy': 'MAIN',
                        'formKey': $FORM_KEY,
                        'docType': $docType
                    })

                    let url = `${serverUrl}/document/listTitle?${configParam}`
                    window.open(url, 'DF', 'menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no height=600,width=800,left=' + x + ',top=' + y)
                })
                // ดึงสต๊อก
                .on('click', '#openCarStock', function () {
                    let x = screen.width / 2 - 800 / 2
                    let y = screen.height / 2 - 600 / 2

                    let configParam = $.param({
                        'actionBy': 'MAIN',
                        'formKey': $FORM_KEY
                    })

                    let url = `${serverUrl}/document/listCarStock?${configParam}`
                    window.open(url, 'DF', 'menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no height=600,width=800,left=' + x + ',top=' + y)
                })
                // ดึงรายชื่อลูกค้า
                .on('click', '#openCustomerList', function () {
                    let x = screen.width / 2 - 800 / 2
                    let y = screen.height / 2 - 600 / 2

                    let configParam = $.param({
                        'actionBy': 'MAIN',
                        'formKey': $FORM_KEY
                    })

                    let url = `${serverUrl}/document/listCustomer?${configParam}`
                    window.open(url, 'DF', 'menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no height=600,width=800,left=' + x + ',top=' + y)
                })
                // ดึงรายชื่อผู้ขาย
                .on('click', '#openSellerList', function () {
                    let x = screen.width / 2 - 800 / 2
                    let y = screen.height / 2 - 600 / 2

                    let configParam = $.param({
                        'actionBy': 'MAIN',
                        'formKey': $FORM_KEY
                    })

                    let url = `${serverUrl}/document/listSeller?${configParam}`
                    window.open(url, 'DF', 'menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no height=600,width=800,left=' + x + ',top=' + y)
                })
                // สั่งพิมพ์
                // .on('click', '.btnPrint', function () {
                //     let $me = $(this),
                //         $id = $me.data('doc-id')

                //     let url = `${serverUrl}/document/print/${$id}`
                //     window.open(url, 'Print', 'menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=994,height=768,top=20,left=20')
                // })
                .on('click', '.btnPrint', function () {
                    let $me = $(this),
                        $id = $me.data('doc-id')
                    $type = $me.data('doc-type-')
                    if ($type == 'ใบสำคัญรับ') {
                        let url = `${serverUrl}/pdf_document/` + $id;
                        window.open(
                            url,
                            "Doc",
                            "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=992,height=700,top=10,left=10 "
                        );
                    } else if ($type == 'ใบสำคัญจ่าย') {
                        let url = `${serverUrl}/pdf_doc_pay/` + $id;
                        window.open(
                            url,
                            "Doc",
                            "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=992,height=700,top=10,left=10 "
                        );
                    } else if ($type == 'ใบส่วนลด') {
                        let url = `${serverUrl}/pdf_doc_rebate/` + $id;
                        window.open(
                            url,
                            "Doc",
                            "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=992,height=700,top=10,left=10 "
                        );
                    }
                    else {
                        let url = `${serverUrl}/pdf_doc_expenses/` + $id;
                        window.open(
                            url,
                            "Doc",
                            "menubar=no,toorlbar=no,location=no,scrollbars=yes, status=no,resizable=no,width=992,height=700,top=10,left=10 "
                        );
                    }
                })

            $form
                // บันทึกข้อมูล
                .on('click', '.btnSave', function (e) {
                    e.preventDefault()

                    if ($form.find('input[name=doc_date]').val() == '') {
                        alert('กรุณาเลือกวันที่')
                        return false
                    }

                    if ($form.find('input[name=title]').val() == '') {
                        alert('กรุณาเลือกรายการ')
                        return false
                    }

                    if ($form.find('input[name=price]').val() == '') {
                        alert('กรุณาเลือกระบุจำนวนเงิน')
                        return false
                    }

                    let $inputCar = false
                    if ($('input[name=doc_type]').val() == 'ใบสำคัญรับ' || $('input[name=doc_type]').val() == 'ใบสำคัญจ่าย' || $('input[name=doc_type]').val() == 'ใบส่วนลด') {
                        if ($form.find('input[name=car_title]').val() == '') {
                            alert('กรุณาเลือกรถ')
                            return false
                        } else {
                            $inputCar = true
                        }
                    } else if ($('input[name=doc_type]').val() == 'รายจ่าย') {
                        $inputCar = true
                    }

                    if ($form.find('select[name=cash_flow_name]').val() == null) {
                        alert('กรุณาเลือกบัญชีบริษัท')
                        return false
                    }

                    if ($form.find('select[name=doc_payment_type]').val() == '') {
                        alert('กรุณาเลือกประเภท')
                        return false
                    }

                    let $inputClient = false
                    if ($('input[name=doc_type]').val() == 'ใบสำคัญรับ') {
                        if ($form.find('input[name=customer_title]').val() == '') {
                            alert('กรุณาเลือกลูกค้า')
                            return false
                        } else {
                            $inputClient = true
                        }
                    } else if ($('input[name=doc_type]').val() == 'ใบสำคัญจ่าย') {
                        if ($form.find('input[name=seller_title]').val() == '') {
                            alert('กรุณาเลือกผู้ขาย')
                            return false
                        } else {
                            $inputClient = true
                        }
                    } else if ($('input[name=doc_type]').val() == 'ใบส่วนลด') {
                        if ($form.find('input[name=customer_title]').val() == '') {
                            alert('กรุณาเลือกลูกค้า')
                            return false
                        } else {
                            $inputClient = true
                        }
                    } else if ($('input[name=doc_type]').val() == 'รายจ่าย') {
                        $inputClient = true
                    }

                    // ผ่าน
                    if (
                        $form.find('input[name=doc_date]').val() != '' &&
                        $form.find('input[name=title]').val() != '' &&
                        $form.find('input[name=price]').val() != '' &&
                        $inputCar &&
                        $inputClient &&
                        $form.find('select[name=doc_payment_type]').val() != ''
                    ) {

                        let $me = $(this)

                        $me.attr('disabled', true)

                        let formData = new FormData($form[0])

                        let $url = ''
                        if ($form.find('input[name=doc_id]').val() != '') {
                            $url = `${serverUrl}/document/update`
                        } else {
                            $url = `${serverUrl}/document/store`
                        }

                        $.ajax({
                            type: "POST",
                            url: $url,
                            data: formData,
                            processData: false,
                            contentType: false,
                        }).done(function (res) {

                            if (res.success) {

                                Swal.fire({
                                    text: res.message,
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "ตกลง",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
                                    if (result.isConfirmed) {
                                        APP.docTable()
                                    }
                                })

                                $me.attr('disabled', false)
                                APP.docFormClear()
                                APP.docOpenForm()
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
                                        // LANDING_KNOWLEDGE.reloadPage()
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
                })
                // เลือกประเภทเงิน
                .on('change', 'select[name=doc_payment_type]', function () {

                    let $me = $(this)

                    if ($me.val() == 'เงินสด') {
                        $("#wrapperDocPaymentTypeEtc").hide()
                        $("#wrapperACForm").hide()
                    }

                    else if ($me.val() == 'A/C') {
                        $("#wrapperACForm").show()
                        $("#wrapperDocPaymentTypeEtc").hide()
                    }

                    else if ($me.val() == 'อื่น ๆ') {
                        $("#wrapperACForm").hide()
                        $("#wrapperDocPaymentTypeEtc").show()
                    }
                })
        },
        tableDocFilter() {
            flatpickr("#daterange_doc", {
                mode: "range",
                dateFormat: "Y-m-d",
                onChange: function (selectedDates) {
                    APP.docTable();
                },
            });
        },

        // เลือกธีม
        selectTheme() {

            $('.theme').on('click', function () {

                clearInterval(downloadTimer)

                let $me = $(this),
                    $themeName = $me.data('theme-name')

                $('#' + $themeName).click()
                $('.profiles-gate-container').fadeOut(1000)
            })

            $('.btn--snakeBorder').on('click', function () {
                clearInterval(downloadTimer)
                $('#switchbtn-light-theme').click()
                $('.profiles-gate-container').fadeOut(1000)
            })
        },

        // SET UP
        init() {
            APP.selectTheme()
            APP.handleDocForm()
            APP.tableDocFilter()

            if (!localStorage.getItem('zemthemecolors')) {
                $('.profiles-gate-container').show()
            } else {
                clearInterval(downloadTimer)
            }
        }
    }

    APP.init()

    // set_Up_DocModal
    let $Modal_Set_Up = $("#set_Up_DocModal")

    let $form_Set_Up_Add = $Modal_Set_Up.find('form')

    $form_Set_Up_Add
    // บันทึกข้อมูล
    $('body').on('click', '.btnAddModalSetUp', function (e) {
        e.preventDefault()

        // เช็คข้อมูล
        if ($form_Set_Up_Add.find('input[name=company_Name]').val() == '') {
            alert('กรุณาระบุชื่อ')
            return false;
        }
        else if ($form_Set_Up_Add.find('input[name=company_Taxpayer_Number]').val() == '') {
            alert('กรุณาระบุเลขประจำตัวผู้เสียภาษี')
            return false;
        }
        else if ($form_Set_Up_Add.find('input[name=company_Phone_Number]').val() == '') {
            alert('กรุณาระบุเบอร์โทรศัพท์')
            return false;
        }
        else if ($form_Set_Up_Add.find('input[name=company_Address]').val() == '') {
            alert('กรุณาระบุที่อยู่')
            return false;
        }
        else if ($form_Set_Up_Add.find('input[name=company_Postcode]').val() == '') {
            alert('กรุณาระบุรหัสไปรษณีย์')
            return false;
        }
        else if ($form_Set_Up_Add.find('input[name=company_Branch]').val() == '') {
            alert('กรุณาระบุสำนักงาน/สาขา')
            return false;
        }
        // ผ่าน
        else {

            let $me = $(this)

            $me.attr('disabled', true)

            let formData = new FormData($form_Set_Up_Add[0])

            formData.append('content', $form_Set_Up_Add.find('.ql-editor').html())

            $.ajax({
                type: "POST",
                url: `${serverUrl}/document/addSetUp`,
                data: formData,
                processData: false,
                contentType: false,
            }).done(function (res) {

                //กรณี: บันทึกสำเร็จ
                if (res.success = 1) {

                    Swal.fire({
                        text: "เพิ่ม ตั้งค่าใบสำคัญ สำเร็จ",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "ตกลง",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            $("#closeset_Up_DocModal").click();
                        }
                    })
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

    //When click edit set_Up_Doc
    $('body').on('click', '.set_Up_Doc', function () {
        $.ajax({
            url: '/document/editSetUp',
            type: "GET",
            dataType: 'json',
            success: function (res) {
                // let $data = res.data
                $('#set_Up_DocModal').modal('show');
                // $('#form_Set_Up #id').val(res.data.id);
                $('#form_Set_Up #company_Name').val(res.data.set_up_name);
                $('#form_Set_Up #company_Taxpayer_Number').val(res.data.set_up_taxpayer_number);
                $('#form_Set_Up #company_Phone_Number').val(res.data.set_up_phone_number);
                // $('#form_Set_Up #company_Backup_Number').val(res.data.set_up_backup_number);
                $('#form_Set_Up #company_Address').val(res.data.set_up_address);
                $('#form_Set_Up #company_Email').val(res.data.set_up_email);
                $('#form_Set_Up #company_Branch').val(res.data.set_up_branch);
                $('#form_Set_Up #company_Postcode').val(res.data.set_up_postcode);
            },
            error: function (data) { $('#set_Up_DocModal').modal('show'); }
        });
    });

    //ModalUpdate
    let $form_Set_Up_Update = $Modal_Set_Up.find('form')

    $form_Set_Up_Update

        // บันทึกข้อมูล
        .on('click', '.btnUpdateModalSetUp', function (e) {
            e.preventDefault()

            // เช็คข้อมูล
            if ($form_Set_Up_Add.find('input[name=company_Name]').val() == '') {
                alert('กรุณาระบุชื่อ')
                return false;
            }
            else if ($form_Set_Up_Add.find('input[name=company_Taxpayer_Number]').val() == '') {
                alert('กรุณาระบุเลขประจำตัวผู้เสียภาษี')
                return false;
            }
            else if ($form_Set_Up_Add.find('input[name=company_Phone_Number]').val() == '') {
                alert('กรุณาระบุเบอร์โทรศัพท์')
                return false;
            }
            else if ($form_Set_Up_Add.find('input[name=company_Address]').val() == '') {
                alert('กรุณาระบุที่อยู่')
                return false;
            }
            else if ($form_Set_Up_Add.find('input[name=company_Postcode]').val() == '') {
                alert('กรุณาระบุรหัสไปรษณีย์')
                return false;
            }
            else if ($form_Set_Up_Add.find('input[name=company_Branch]').val() == '') {
                alert('กรุณาระบุสำนักงาน/สาขา')
                return false;
            }
            // ผ่าน
            else {
                let $me = $(this)

                $me.attr('disabled', true)

                let formData = new FormData($form_Set_Up_Update[0])

                formData.append('content', $form_Set_Up_Update.find('.ql-editor').html())

                $.ajax({
                    type: "POST",
                    url: `/document/updateSetUp`,
                    data: formData,
                    processData: false,
                    contentType: false,
                }).done(function (res) {

                    if (res.success = 1) {

                        Swal.fire({
                            text: "แก้ไข ตั้งค่าใบสำคัญ สำเร็จ",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "ตกลง",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                $("#closeset_Up_DocModal").click();
                            }
                        })
                        $me.attr('disabled', false)
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

    $("#note").keyup(function () {
        let searchText = $(this).val();
        let $tab = $(".tabDocType a.active");
        let $docType = $tab.data('doc-type');
        if (searchText != "") {
            $.ajax({
                url: `/document/search`,
                method: "POST",
                data: {
                    query: searchText,
                    type: $docType
                },
                success: function (response) {
                    $("#show-list").html(response);
                }
            })
        } else {
            $("#show-list").html("");
        }
        $(document).on('click', 'a', function () {
            $("#note").val($(this).text())
            $("#show-list").html("");
        })
    })
})

$("#checkbox_wht").on("change", function () {
    $("#open_wht").toggleClass("show");
});

var price_vat = $("#price_vat");
price_vat.on("change", function () {
    var vat_type = $("#price_vat").val();
    if (vat_type == "ราคารวมภาษี") {
        $("#checkbox_price_vat").removeClass("show");
    } else {
        $("#checkbox_price_vat").addClass("show");
    }
});
