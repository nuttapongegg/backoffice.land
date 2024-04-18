<!-- Sidebar-right-->
<div class="sidebar sidebar-right sidebar-animate">
    <div class="tab-menu-heading p-3">
        <h6 class="mb-0 tx-uppercase tx-semibold">ประวัติการใช้งาน</h6>
        <a href="javascript:void(0)" class="btn btn-def def-white sidebar-remove ms-auto"><i class="fe fe-x"></i></a>
    </div>
    <div class="panel tabs-style2">
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane active" id="side1">
                    <div class="flex-between px-4 mt-3">
                        <a href="profile-notifications.html" class="tx-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalEmployeeLog">ประวัติการใช้งานทั้งหมด</a>
                    </div>
                    <ul class="list-group list-group-flush mt-3">
                        <?php echo getLogAll()['js_critical'] ?>
                        <?php try {
                            foreach (getLogAll()['employee_log_otday'] as $log_otday) : ?>
                                <?php $time = substr($log_otday->created_at, '11'); ?>
                                <li class="list-group-item d-flex">
                                    <div class="main-img-user1 avatar me-2">
                                        <?php if ($log_otday->thumbnail != '') {
                                            $thumbnail = $log_otday->thumbnail;
                                        } else {
                                            $thumbnail = 'nullthumbnail.png';
                                        } ?>
                                        <img class="rounded-circle" style="height: 40px; width: 40px;" src="https://evxspst.sgp1.cdn.digitaloceanspaces.com/uploads/img/nullthumbnail.png">
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-1"><?php echo $log_otday->username; ?><span class="tx-11 tx-muted font-weight-normal float-end"><?php echo $time ?></span>
                                        </h6>
                                        <p class="mb-0 tx-12"><?php echo $log_otday->detail; ?></p>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php } catch (Exception $e) {
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/Sidebar-right-->

<?php echo $this->include('/layouts/modal'); ?>

<!-- Footer opened -->
<div class="main-footer">
    <div class="col-md-12 col-sm-12 text-center">
        <div class="container-fluid pd-t-0 ht-100p">
            Copyright © 2022 <a href="javascript:void(0)" class="tx-primary">Backoffice</a>. Designed by <a href="javascript:void(0)"> Land </a> All rights reserved
        </div>
    </div>
</div>
<!-- Footer closed -->

</div>
<!-- End Page -->

<!-- JQuery min js -->
<script src="<?php echo base_url('/assets/plugins/jquery/jquery.min.js'); ?>"></script>

<!-- Bootstrap js -->
<script src="<?php echo base_url('/assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

<!-- Perfect-scrollbar js -->
<script src="<?php echo base_url('/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js'); ?>"></script>
<script src="<?php echo base_url('/assets/plugins/perfect-scrollbar/p-scroll.js'); ?>"></script>

<!-- Sidebar js -->
<script src="<?php echo base_url('/assets/plugins/side-menu/sidemenu.js'); ?>"></script>

<!-- Sticky js -->
<script src="<?php echo base_url('/assets/js/sticky.js'); ?>"></script>

<!-- Select2 js -->
<script src="<?php echo base_url('/assets/plugins/select2/js/select2.full.min.js'); ?>"></script>

<!-- Internal Select2.min js -->
<script src="<?php echo base_url('/assets/plugins/select2/js/select2.min.js'); ?>"></script>

<!-- Data tables -->
<script src="<?php echo base_url('/assets/plugins/datatable/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('/assets/plugins/datatable/js/dataTables.bootstrap5.js'); ?>"></script>
<script src="<?php echo base_url('/assets/plugins/datatable/js/dataTables.buttons.min.js'); ?>"></script>
<script src="<?php echo base_url('/assets/plugins/datatable/js/buttons.bootstrap5.min.js'); ?>"></script>
<script src="<?php echo base_url('/assets/plugins/datatable/js/jszip.min.js'); ?>"></script>
<script src="<?php echo base_url('/assets/plugins/datatable/pdfmake/pdfmake.min.js'); ?>"></script>
<script src="<?php echo base_url('/assets/plugins/datatable/pdfmake/vfs_fonts.js'); ?>"></script>
<script src="<?php echo base_url('/assets/plugins/datatable/dataTables.responsive.min.js'); ?>"></script>
<script src="<?php echo base_url('/assets/plugins/datatable/responsive.bootstrap5.min.js'); ?>"></script>
<script src="https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<!-- Flatpickr js -->
<script src="<?php echo base_url('/assets/plugins/flatpickr/flatpickr.js'); ?>"></script>

<!--Internal  jquery.maskedinput js -->
<script src="<?php echo base_url('/assets/plugins/jquery.maskedinput/jquery.maskedinput.js'); ?>"></script>

<!-- right-sidebar js -->
<script src="<?php echo base_url('/assets/plugins/sidebar/sidebar.js'); ?>"></script>
<script src="<?php echo base_url('/assets/plugins/sidebar/sidebar-custom.js'); ?>"></script>

<script src="https://amiryxe.github.io/easy-number-separator/easy-number-separator.js"></script>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
<!-- <script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>


<?php if (isset($js_critical)) {
    echo $js_critical;
}; ?>

<!-- pusher -->
<script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>

<script src="<?php echo base_url('/assets/plugins/fileuploads/js/fileupload.js'); ?>"></script>
<script src="<?php echo base_url('/assets/plugins/fileuploads/js/file-upload.js'); ?>"></script>

<!-- custom-switcher js -->
<script src="<?php echo base_url('/assets/js/custom-switcher.js'); ?>"></script>

<!-- custom js -->
<script src="<?php echo base_url('/assets/js/custom.js'); ?>"></script>

<!-- iziToast -->
<script src="<?php echo base_url('/assets/app/js/izitoast/iziToast.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url('/assets/switcher/js/switcher.js'); ?>"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.17/dist/sweetalert2.all.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/1.9.3/countUp.min.js" integrity="sha512-fojFLrCKRmoGiEXroMMaF88NlzkQLbBGIQ0LwgmxDM6vGSh6fnm04ClpwheRDrLnY+gi/1CfOWV5+YqcPSSh7A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="<?php echo base_url('/assets/app/js/app.js?v=' . time()); ?>"></script>
<script src="<?php echo base_url('/assets/app/js/pusher.js?v=' . time()); ?>"></script>

</body>
</html>