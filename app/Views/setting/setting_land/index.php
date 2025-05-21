<!-- main-content -->
<div class="main-content app-content">
    <!-- container -->
    <div class="main-container container-fluid">

        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between">
            <div class="left-content">
                <span class="main-content-title tx-primary tx-18 mg-b-0 mg-b-lg-1">ตั้งค่าบัญชี</span>
            </div>
            <div class="justify-content-center mt-2">
                <ol class="breadcrumb breadcrumb-style3">
                    <li class="breadcrumb-item tx-15"><a href="<?php echo base_url('/setting_land/index'); ?>">ตั้งค่า</a></li>
                    <li class="breadcrumb-item active" aria-current="page">ตั้งค่าบัญชี</li>
                </ol>
            </div>
        </div>

        <!-- /breadcrumb -->
        <div class="card">
            <div class="card-header">
                <?php $n = 0;
                $sum_total = 0;
                foreach ($setting_lands as $cash) {
                    $n++;
                    $sum_total = $sum_total + $cash->land_account_cash;
                } ?>
                <h5 class="tx-primary tx-17 mb-1">บัญชี (<?php echo $n ?> รายการ)
                    <a href="javascript:void(0);" class="btn btn-outline-primary mb-3 ms-3 float-end" data-bs-toggle="modal" data-bs-target="#modalAddLandAccount" style="margin-top: -6px;"><i class="fa-solid fa-plus"></i>เพิ่มบัญชี</a>
                    <a href="javascript:void(0);" class="btn btn-outline-primary mb-3 float-end" data-bs-toggle="modal" data-bs-target="#modalEditRealInvestment" style="margin-top: -6px;"><i class="fa-solid fa-plus"></i>ตั้งค่าเงินลงทุนจริง</a>
                </h5>
                <!-- <h6 class="mb-1">จำนวน <php echo $n ?> </h6> -->
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="dataTables_scroll">
                            <div class="dataTables_scrollBody" style="position: relative; overflow: auto; width: 100%;">
                                <table class="table table-bordered text-nowrap border-bottom no-footer dataTable" id="DataTable-LandAccount">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th class="text-center" style="width: 450px;">ชื่อบัญชี</th>
                                            <th class="text-right" style="width: 250px;">จำนวนเงิน</th>
                                            <th style="width: 220px;">สร้างขึ้นเมื่อ</th>
                                            <th style="width: 220px;">แก้ไขล่าสุด</th>
                                            <th class="text-center" style="width: 56px;">จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($setting_lands) : ?>
                                            <?php $i = 0 ?>
                                            <?php foreach ($setting_lands as $setting_land) { ?>
                                                <?php $i++ ?>

                                                <tr id="<?php echo $setting_land->id; ?>">
                                                    <td><?php echo $i; ?></td>
                                                    <td>
                                                        <div class="mb-2 mt-2">
                                                            <a href="javascript:void(0);" id="ReportLandAccount" data-bs-toggle="modal" data-bs-target="#modalReportLandAccount" data-id="<?php echo $setting_land->id; ?>"><?php echo $setting_land->land_account_name; ?></a>
                                                            <a href="javascript:void(0);" class="btn btn-outline-secondary btnAddLandAccountMinus float-end" style="margin-top: -6px;" data-id="<?php echo $setting_land->id; ?>"><i class="fa-solid fa-minus" id="addLandAccountMinus" name="addLandAccountMinus"></i></a>
                                                            <a href="javascript:void(0);" class="btn btn-outline-success btnAddLandAccountPlus float-end me-2" style="margin-top: -6px;" data-id="<?php echo $setting_land->id; ?>"><i class="fa-solid fa-plus" id="addLandAccountPlus" name="addLandAccountPlus"></i></a>
                                                            <a href="javascript:void(0);" class="btn btn-outline-primary btnTransferLandAccount float-end me-2" style="margin-top: -6px;" data-id="<?php echo $setting_land->id; ?>"><i class="fas fa-exchange-alt" id="transferLandAccount" name="transferLandAccount"></i></a>
                                                        </div>
                                                    </td>
                                                    <td><?php echo number_format($setting_land->land_account_cash, 2); ?></td>
                                                    <td><?php echo datetime_compare($setting_land->created_at); ?></td>
                                                    <?php if ($setting_land->updated_at == '') {
                                                        $updated = $setting_land->created_at;
                                                    } else {
                                                        $updated = $setting_land->updated_at;
                                                    }
                                                    ?>
                                                    <td><?php echo dateThai($updated); ?></td>
                                                    <td name="bstable-actions">
                                                        <div class="d-flex align-items-center" style="justify-content: center;">
                                                            <button type="button" class="btn btn-primary-light btn-icon btnEditLandAccount me-2" data-id="<?php echo $setting_land->id; ?>"><i class="fe fe-edit"> </i></button>
                                                            <button type="button" class="btn btn-danger-light btn-icon btnDeleteLandAccount" data-id="<?php echo $setting_land->id; ?>"><i class="fe fe-trash"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-primary">
                                            <th colspan="1">
                                                <h5 class="mb-0">รวม</h5>
                                            </th>
                                            <!-- <php if (session('status_edit_in_land_account') == 1) {
                                                $sum_total_land_account = '<th colspan="5">
                                                <h5 class="mb-0">' . number_format($sum_total, 2) . '</h5>
                                            </th>';
                                            } else {
                                                $sum_total_land_account = '<th colspan="4">
                                                <h5 class="mb-0">' . number_format($sum_total, 2) . '</h5>
                                            </th>';
                                            }
                                            ?> -->
                                            <!-- <php echo $sum_total_land_account; ?> -->
                                            <th colspan="5">
                                                <h5 class="mb-0"><?php echo number_format($sum_total, 2)  ?></h5>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="tx-primary mb-2">ประวัติการแก้ไขบัญชี</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="dataTables_scroll">
                            <div class="dataTables_scrollBody" style="position: relative; overflow: auto; width: 100%;">
                                <table class="table table-bordered text-nowrap border-bottom no-footer dataTable" id="DataTable-LandAccountLogs">
                                    <thead>
                                        <tr>
                                            <th style="width: 45px;">#</th>
                                            <th class="text-center" style="width: 250px;">การกระทำ</th>
                                            <th class="text-center" style="width: 250px;">ชื่อบัญชี</th>
                                            <th class="text-right" style="width: 150px;">จำนวนเงิน</th>
                                            <th class="text-center" style="width: 350px;">หมายเหตุ</th>
                                            <th class="text-center" style="width: 150px;">กระทำโดย</th>
                                            <th class="text-center" style="width: 100px;">กระทำเมื่อ</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Container -->
    </div>
    <!-- /main-content -->
</div>

<!-- modalAddLandAccount -->
<div class="modal fade" id="modalAddLandAccount" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">เพิ่มบัญชี</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="#">
                    <div class="form-group">
                        <div align="left">
                            <div class="row">
                                <label for="land_account_name">ชื่อบัญชี</label>
                                <input type="text" class="form-control" id="land_account_name" name="land_account_name" placeholder="ชื่อบัญชี">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div align="left">
                            <div class="row">
                                <label for="land_account_cash">จำนวนเงิน</label>
                                <input type="text" class="form-control" id="land_account_cash" name="land_account_cash" placeholder="จำนวนเงิน">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div style="display: flex; justify-content: center;">
                        <button type="submit" class="btn btn-primary btn-block btnSaveLandAccount" role="button">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end modalAddLandAccount-->
<!-- Edit LandAccount -->
<div align="center">
    <div class="modal fade" id="modalEditLandAccount" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row col-xl-12">
                        <h7 class="modal-title">แก้ไขบัญชี</h7>
                    </div>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="updateLandAccount" name="updateLandAccount" action="#">
                        <input type="hidden" name="LandAccountId" id="LandAccountId" />

                        <div class="form-group">
                            <div align="left">
                                <div class="row">
                                    <label for="edit_land_account_name">ชื่อบัญชี</label>
                                    <input type="text" class="form-control" id="edit_land_account_name" name="edit_land_account_name" placeholder="ชื่อบัญชี">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div align="left">
                                <div class="row">
                                    <label for="edit_land_account_cash">จำนวนเงิน</label>
                                    <input type="text" class="form-control" id="edit_land_account_cash" name="edit_land_account_cash" placeholder="จำนวนเงิน">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div style="display: flex; justify-content: center;">
                            <button type="submit" class="btn btn-primary btn-block btnEditAccount" role="button">ยืนยัน</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Edit LandAccount -->
<!-- modalAddLandAccountPlus -->
<div class="modal fade" id="modalAddLandAccountPlus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">เพิ่มเงินเข้าบัญชี</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form method="post" id="AddLandAccountPlus" name="AddLandAccountPlus" action="#">
                    <input type="hidden" name="LandAccountId" id="LandAccountId" />
                    <div class="form-group">
                        <div align="left">
                            <div class="row">
                                <label for="land_account_money_plus">จำนวนเงิน</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="land_account_money_plus" name="land_account_money_plus" placeholder="จำนวนเงิน">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div align="left">
                            <div class="row">
                                <label for="land_account_note_plus">หมายเหตุ</label>
                                <div class="input-group">
                                    <textarea class="form-control" placeholder="หมายเหตุ..." rows="3" type="text" id="land_account_note_plus" name="land_account_note_plus"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div style="display: flex; justify-content: center;">
                        <button type="submit" class="btn btn-primary btn-block btnSaveLandAccountPlus" role="button">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end modalAddLandAccountPlus-->
<!-- modalAddLandAccountMinus -->
<div class="modal fade" id="modalAddLandAccountMinus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ถอนเงินออกจากบัญชี</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form method="post" id="AddLandAccountMinus" name="AddLandAccountMinus" action="#">
                    <input type="hidden" name="LandAccountId" id="LandAccountId" />
                    <div class="form-group">
                        <div align="left">
                            <div class="row">
                                <label for="land_account_money_minus">จำนวนเงิน</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="land_account_money_minus" name="land_account_money_minus" placeholder="จำนวนเงิน">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div align="left">
                            <div class="row">
                                <label for="land_account_note_minus">หมายเหตุ</label>
                                <div class="input-group">
                                    <textarea class="form-control" placeholder="หมายเหตุ..." rows="3" type="text" id="land_account_note_minus" name="land_account_note_minus"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div style="display: flex; justify-content: center;">
                        <button type="submit" class="btn btn-primary btn-block btnSaveLandAccountMinus" role="button">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end modalAddLandAccountMinus-->

<!-- รายการLandAccount -->
<div class="modal fade" id="modalReportLandAccount" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h5 class="card-title">รายการเคลื่อนไหวบัญชี</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalReportLandAccount"><span aria-hidden="true">&times;</span></button>

            </div>
            <form action="#">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="DataTable_ReportLandAccount">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">การกระทำ</th>
                                    <th class="text-center">ชื่อบัญชี</th>
                                    <th class="text-right">จำนวนเงิน</th>
                                    <th class="text-center">หมายเหตุ</th>
                                    <th class="text-center">กระทำโดย</th>
                                    <th class="text-right">จำนวนเงินคงเหลือ</th>
                                    <th class="text-center">กระทำเมื่อ</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2" style="display: flex; justify-content: center;">
                        <button type="button" class="btn btn-primary " data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modalTransferLandAccount -->
<div class="modal fade" id="modalTransferLandAccount" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">โอนเงิน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form method="post" id="TransferLandAccount" name="TransferLandAccount" action="#">
                    <input type="hidden" name="LandAccountId" id="LandAccountId" />
                    <div class="form-group">
                        <div class="row">
                            <label class="form-label">ชื่อบัญชี</label>
                            <div class="input-group">
                                <select name="land_account_name" class="form-control form-select " data-bs-placeholder="Select Country" required>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div align="left">
                            <div class="row">
                                <label for="transfer_money_land_account">จำนวนเงิน</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="transfer_money_land_account" name="transfer_money_land_account" placeholder="จำนวนเงิน">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div align="left">
                            <div class="row">
                                <label for="transfer_land_account_note">หมายเหตุ</label>
                                <div class="input-group">
                                    <textarea class="form-control" placeholder="หมายเหตุ..." rows="3" type="text" id="transfer_land_account_note" name="transfer_land_account_note"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div style="display: flex; justify-content: center;">
                        <button type="submit" class="btn btn-primary btn-block btnSaveTransferLandAccount" role="button">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end modalTransferLandAccount-->
<div align="center">
    <div class="modal fade" id="modalEditRealInvestment" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row col-xl-12">
                        <h7 class="modal-title">ตั้งค่าเงินลงทุนจริง</h7>
                    </div>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="updateRealInvestment" name="updateRealInvestment" action="#">
                        <input type="hidden" name="RealInvestmentId" id="RealInvestmentId" value="<?php echo $real_investment->id ?>" />

                        <div class="form-group">
                            <div align="left">
                                <div class="row">
                                    <label for="realInvestment">เงินลงทุนจริง</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="realInvestment" name="realInvestment" placeholder="เงินลงทุนจริง" value="<?php echo $real_investment->investment ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div align="left">
                                <div class="row">
                                    <label class="form-label">การดำเนินการ</label>
                                    <div class="input-group">
                                        <select id="unitEditRealInvestment" name="unitEditRealInvestment" class="form-control form-select" required>
                                            <option value="แก้ไข">แก้ไข</option>
                                            <option value="เพิ่ม">เพิ่ม</option>
                                            <option value="ลบ">ลบ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="editAmountGroup" class="form-group" style="display: none;">
                            <div align="left">
                                <div class="row">
                                    <label for="editRealInvestment">จำนวนเงิน<span id="calculatedAmount" style="font-weight: normal; color: gray;"></span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="editRealInvestment" name="editRealInvestment" placeholder="จำนวนเงิน">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div style="display: flex; justify-content: center;">
                            <button type="submit" class="btn btn-primary btn-block btnSaveRealInvestment" role="button">ยืนยัน</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>