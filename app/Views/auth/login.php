<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Title -->
    <title>Land | Backoffice</title>

    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url('/assets/img/brand/favicon.ico'); ?>" type="image/x-icon"/>

    <!-- Icons css -->
    <link href="<?php echo base_url('/assets/css/icons.css'); ?>" rel="stylesheet">

    <!--  Bootstrap css-->
    <link id="style" href="<?php echo base_url('/assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" />

    <!-- Style css -->
    <link href="<?php echo base_url('/assets/css/style.css'); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <!-- CUSTOM -->
    <style>
        /** BASE **/
        * {
            font-family: 'Kanit', sans-serif;
        }
        .bg-svg::before {
            content: "";
            position: absolute;
            background: url(<?php echo base_url('/assets/img/world-11047_1920.jpg'); ?>);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            width: 100%;
            height: 100%;
            opacity: 0.35;
        }

        .field-icon {
            float: right;
            margin-right: 5px;
            margin-top: -23px;
            position: relative;
            z-index: 2;
        }

        .container{
            padding-top:50px;
            margin: auto;
        }

    </style>
    <script>
        var serverUrl = '<?php echo base_url(); ?>'
    </script>
</head>
<body class="ltr error-page1 bg-primary">

<!-- Progress bar on scroll -->
<div class="progress-top-bar"></div>

<!-- Loader -->
<div id="global-loader">
    <img src="<?php echo base_url('/assets/img/loader.svg'); ?>" class="loader-img" alt="Loader">
</div>
<!-- /Loader -->

<div class="square-box">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
</div>

<div class="bg-svg">
    <div class="page" >
        <div class="z-index-10">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5 col-lg-6 col-md-8 col-sm-8 col-xs-10 mx-auto my-auto py-4 justify-content-center">
                        <div class="card-sigin">
                            <!-- Demo content-->
                            <div class="main-card-signin d-md-flex">
                                <div class="wd-100p">
                                    <div class="d-flex"><a href="#"><img src="<?php echo base_url('/assets/img/brand/favicon-white.png'); ?>" class="sign-favicon ht-40" alt="logo"></a></div>
                                    <div class="mt-3">
                                        <h2 class="tx-medium tx-primary">Land | Backoffice</h2>
                                        <h6 class="font-weight-semibold mb-4 text-white-50">ล็อคอิน เพื่อเข้าสู่ระบบ</h6>
                                        <div class="panel tabs-style7 scaleX mt-2">
                                            <div class="panel-head">
                                                <ul class="nav nav-tabs d-block d-sm-flex">
                                                    <li class="nav-item"><a class="nav-link tx-14 font-weight-semibold text-sm-center text-start active" data-bs-toggle="tab" href="#signinTab1">ชื่อผู้ใช้งาน</a></li>
                                                </ul>
                                            </div>
                                            <div class="panel-body p-0">
                                                <div class="tab-content mt-3">
                                                    <div class="tab-pane active" id="signinTab1">
                                                        <form action="#">
                                                            <div class="form-group">
                                                                <input class="form-control" placeholder="ยูสเซอร์เนม" type="text" name="username" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <input class="form-control" placeholder="*********" type="password" name="password" id="password" required>
                                                                <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password tx-primary"></span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <p class="mb-0"><a href="javascript:void(0);" class="tx-primary">ลืมรหัสผ่าน?</a></p>
                                                                <button class="btn btn-primary" id="btn-login">เข้าสู่ระบบ</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- JQuery min js -->
<script src="<?php echo base_url('/assets/plugins/jquery/jquery.min.js'); ?>"></script>

<!-- Bootstrap js -->
<script src="<?php echo base_url('/assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

<!-- generate-otp js -->
<script src="<?php echo base_url('/assets/js/generate-otp.js'); ?>"></script>

<!--Internal  Perfect-scrollbar js -->
<script src="<?php echo base_url('/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js'); ?>"></script>

<!-- custom js -->
<script src="<?php echo base_url('/assets/js/custom.js'); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.17/dist/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function () {
        $('#btn-login').on('click', function (e) {
            e.preventDefault()
            const $btnLogin = $(this)

            $btnLogin.prop('disabled', true)

            let username = $('input[name="username"]').val()
            let password = $('input[name="password"]').val()

            let dataObj = {
                username,
                password
            }

            $.ajax({
                type: 'POST',
                url: `${serverUrl}/login`,
                contentType: 'application/json; charset=utf-8;',
                processData: false,
                data: JSON.stringify(dataObj),
                success: function (res) {
                    if (res.success === 1) {

                        $btnLogin.prop('disabled', false)

                        Swal.fire({
                            icon: 'success',
                            text: `${res.message}`,
                            timer: '2000',
                            heightAuto: false
                        });

                        window.location.href = res.redirect_to;
                    }

                    else {
                        $btnLogin.prop('disabled', false)
                    }
                },
                error: function (res) {

                    $btnLogin.prop('disabled', false)

                    Swal.fire({
                        icon: 'error',
                        title: 'ไม่สามารถเข้าสู่ระบบได้',
                        text: `${res.responseJSON.message}`,
                        timer: '2000',
                        heightAuto: false
                    });
                }
            })

        });
        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    });
</script>
</body>
</html>
