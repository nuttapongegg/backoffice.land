<!DOCTYPE html>
<html lang="en" data-layout="horizontal" data-hor-style="hor-hover" data-logo="centerlogo">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Title -->
    <title><?php echo $title; ?></title>

    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url('/assets/img/brand/favicon.ico'); ?>" type="image/x-icon" />

    <!-- Icons css -->
    <link href="<?php echo base_url('/assets/css/icons.css'); ?>" rel="stylesheet">

    <!-- datatable -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <!--  Bootstrap css-->
    <link id="style" href="<?php echo base_url('/assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" />

    <!-- Style css -->
    <link href="<?php echo base_url('/assets/css/style.css'); ?>" rel="stylesheet">

    <!-- Plugins css -->
    <link href="<?php echo base_url('/assets/css/plugins.css'); ?>" rel="stylesheet">

    <!-- Switcher css -->
    <link href="<?php echo base_url('/assets/switcher/css/switcher.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('/assets/switcher/styles.css'); ?>" rel="stylesheet" />

    <?php if (isset($css_critical)) {
        echo $css_critical;
    } ?>

    <link href="<?php echo base_url('/assets/app/css/app.css'); ?>" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <script>
        var serverUrl = '<?php echo base_url(); ?>'
    </script>
</head>

<body class="ltr main-body app sidebar-mini">
<input type="hidden" name="actionBy" value="<?php echo $actionBy; ?>">
<input type="hidden" name="formKey" value="<?php echo isset($formKey) ? $formKey : ''; ?>">
<div class="table-responsive">
    <table class="table table-bordered table-striped text-nowrap border-bottom" id="basicDataTable">
        <thead>
        <tr>
            <th>#</th>
            <th class="wd-20p">รายการใน<?php echo $docType; ?></th>
            <th>แนะนำการใช้งาน</th>
        </tr>
        </thead>
        <tbody>
        <?php $counter = 1; ?>
        <?php foreach ($documentTitleLists as $documentTitleList) { ?>
            <tr style="cursor: pointer" onclick="postValue('<?php echo $documentTitleList->title; ?>', '<?php echo hashidsEncrypt($documentTitleList->id); ?>')">
                <td><?php echo $counter++; ?></td>
                <td><?php echo $documentTitleList->title; ?></td>
                <td><i><?php echo $documentTitleList->note; ?></i></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script>
    function postValue(data, id) {

        let $actionBy = document.getElementsByName('actionBy')[0].value,
            $formKey = document.getElementsByName('formKey')[0].value

        let $opener = opener.document.getElementById($formKey)

        switch ($actionBy) {

            case 'MAIN':
                $opener.title.value = data
                $opener.title_id.value = id
                break

            case 'ADD_BOOKING':
                $opener.title.value = data
                $opener.title_id.value = id
                break
        }

        self.close()
    }
</script>


<!-- JQuery min js -->
<script src="<?php echo base_url('/assets/plugins/jquery/jquery.min.js'); ?>"></script>

<!-- Bootstrap js -->
<script src="<?php echo base_url('/assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

<!-- Data tables -->
<script src="<?php echo base_url('/assets/plugins/datatable/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('/assets/plugins/datatable/js/dataTables.bootstrap5.js'); ?>"></script>

<!-- custom-switcher js -->
<script src="<?php echo base_url('/assets/js/custom-switcher.js'); ?>"></script>

<script src="<?php echo base_url('/assets/switcher/js/switcher.js'); ?>"></script>

<script>
    $('#basicDataTable').DataTable({
        "oLanguage": {
            "sInfo": "กำลังแสดง หน้า _PAGE_ ใน _PAGES_",
            "sSearch": '',
            "sSearchPlaceholder": "ค้นหา...",
        },
        "stripeClasses": [],
        "order": [],
        "pageLength": -1,
        "lengthMenu": [ [10, 20, 50, -1], [10, 20, 50, "ทั้งหมด"] ],
        "columnDefs": [
            {
                'className': 'text-center',
                "width": "10%",
                "targets": [0],
            },
            {
                'className': 'text-center',
                "width": "70%",
                "targets": [1],
            },
            {
                'className': 'text-center',
                "width": "20%",
                "targets": [2],
            },
        ]
    })
</script>
</body>
</html>