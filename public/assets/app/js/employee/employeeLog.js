$(document).ready(function () {
    let tableInitialized = false; // ตรวจสอบว่า DataTable ถูกสร้างหรือยัง

    $('#modalEmployeeLog').on('shown.bs.modal', function () {
        if (!tableInitialized) {
            $('#EmployeelogAll').DataTable({
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
                "lengthMenu": [[10, 25, 50, 100000], [10, 25, 50, "ทั้งหมด"]],
                "columnDefs": [
                    {
                        'className': 'text-center',
                        "width": "5%",
                        "targets": [0],
                    },
                    {
                        'className': 'text-left',
                        "width": "30%",
                        "targets": [1],
                    },
                    {
                        'className': 'text-left',
                        "width": "30%",
                        "targets": [2],
                    },
                    {
                        'className': 'text-left',
                        "width": "15%",
                        "targets": [3],
                    },
                    {
                        'className': 'text-left',
                        "width": "15%",
                        "targets": [4],
                    }
                ],
                "processing": true,
                "serverSide": true,
                "order": [], //init datatable not ordering
                "ajax": {
                    'type': 'POST',
                    'url': "/footer/ajax-datatable"
                },
                "columns": [
                    {
                        data: null,
                        "sortable": false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: "username" },
                    { data: "detail" },
                    { data: "formatted_date" },
                    { data: "formatted_time" }
                ],
                "bFilter": true,
            });

            tableInitialized = true; // ตั้งค่าเป็น true เพื่อไม่ให้โหลดซ้ำ
        }
    });
});
