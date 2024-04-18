<!-- ตารางประวัติการใช้งาน -->
<div class="modal fade" id="modalEmployeeLog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">ตารางประวัติการใช้งาน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closemodalPosition"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="EmployeelogAll">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px;">#</th>
                                    <th class="" style="width: 150px;">ชื่อผู้ใช้</th>
                                    <th class="" style="width: 100px;">การกระทำ</th>
                                    <th class="" style="width: 130px;">กระทำเมื่อวันที่</th>
                                    <th class="" style="width: 130px;">กระทำเมื่อเวลา</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <!--                        <div style="display: flex; justify-content: center;">-->
                        <!--                            <button type="button" class="btn btn-primary " data-bs-dismiss="modal" aria-label="Close">ปิด</button>-->
                        <!--                        </div>-->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- จบ ตารางประวัติการใช้งาน -->
<!-- <style>
    .list-group-item {
    background-color: #fff;
}
</style> -->
<!-- Message Modal -->
<div class="modal fade" id="chatModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-right chatbox" role="document">
        <div class="modal-content chat radius-7 border-0">
            <div class="card overflow-hidden mb-0 border-0">
                <!-- action-header -->
                <div class="action-header clearfix flex-between">
                    <div class="float-start hidden-xs d-flex align-items-center flex-1">
                        <div class="avatar avatar-md rounded-circle">
                            <img src="../assets/img/faces/6.jpg" class="rounded-circle" alt="img">
                        </div>
                        <div class="ms-2 mt-0">
                            <p class="tx-20 text-white mb-0 font-weight-semibold">Sarah Syd</p>
                            <span class="me-3 text-white tx-11 op-8">active now</span>
                        </div>
                    </div>
                    <div class="btn-list">
                        <a href="javascript:void(0)" class="btn btn-sm btn-def white text-white" data-bs-toggle="modal" data-bs-target="#audioModal"><i class="fe fe-phone"></i></a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-def white text-white" data-bs-toggle="modal" data-bs-target="#videoModal"><i class="fe fe-video"></i></a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-def white text-white" data-bs-toggle="dropdown" aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)" class="dropdown-item"><i class="fe fe-user me-1"></i>
                                    View profile</a></li>
                            <li><a href="javascript:void(0)" class="dropdown-item"><i class="fe fe-user-plus me-1"></i>Add friends</a></li>
                            <li><a href="javascript:void(0)" class="dropdown-item"><i class="fe fe-plus me-1"></i>
                                    Add to group</a></li>
                            <li><a href="javascript:void(0)" class="dropdown-item"><i class="fe fe-minus me-1"></i>
                                    Clear chat</a></li>
                            <li><a href="javascript:void(0)" class="dropdown-item"><i class="fe fe-slash me-1"></i>
                                    Block</a></li>
                        </ul>
                        <a href="javascript:void(0)" class="btn btn-sm btn-def white text-white" data-bs-dismiss="modal"><i class="fe fe-x-circle"></i></a>
                    </div>
                </div>
                <!-- action-header end -->

                <!-- msg_card_body -->
                <div class="card-body msg_card_body">
                    <div class="chat-box-single-line">
                        <span class="timestamp">Today</span>
                    </div>
                    <div class="d-flex justify-content-start chat_block">
                        <div class="me-1 d-flex align-items-end">
                            <div class="avatar avatar-sm">
                                <img src="../assets/img/faces/6.jpg" class="rounded-circle" alt="img">
                            </div>
                        </div>
                        <div class="msg_block">
                            <div class="msg_container">
                                <div class="msg_cotainer-main">
                                    <span>Hi there! How are you?</span>
                                </div>
                                <span class="tx-10 tx-muted msg_time">8:10 AM</span>
                            </div>
                            <div class="msg_container">
                                <div class="msg_cotainer-main">
                                    <span> Hey I'm Waiting for your reply.</span>
                                </div>
                                <div class="msg_cotainer-main">
                                    <span>I've to go to outside....</span>
                                </div>
                                <span class="tx-10 tx-muted msg_time">8:35 AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end chat_block">
                        <div class="msg_block_send">
                            <div class="msg_container_send">
                                <div class="msg_cotainer_send-main">
                                    <span>Hi, I am coming there in few minutes. Please wait!</span>
                                </div>
                                <span class="msg_time_send">8:38 AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start chat_block">
                        <div class="me-1 d-flex align-items-end">
                            <div class="avatar avatar-sm">
                                <img src="../assets/img/faces/6.jpg" class="rounded-circle" alt="img">
                            </div>
                        </div>
                        <div class="msg_block">
                            <div class="msg_container">
                                <div class="msg_cotainer-main">
                                    <span>Ok Thanks!</span>
                                </div>
                                <div class="msg_cotainer-main">
                                    <span>I'm waiting for your message.</span>
                                </div>
                                <span class="tx-10 tx-muted msg_time">8:39 AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end chat_block">
                        <div class="msg_block_send">
                            <div class="msg_container_send">
                                <div class="msg_cotainer_send-main">
                                    <span>Hey, I am at Coffee shop you said</span>
                                </div>
                                <div class="msg_cotainer_send-main">
                                    <span>I can't see you here.</span>
                                </div>
                                <div class="msg_cotainer_send-main">
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0)" class="btn btn-sm btn-def white"><i class="fe fe-play-circle"></i></a>
                                        <span class="mx-2">
                                            <svg width="20" height="20">
                                                <defs></defs>
                                                <g transform="matrix(1,0,0,1,0,0)"><svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 3" viewBox="0 0 24 24" width="20" height="20">
                                                        <path d="M6 19a1 1 0 0 1-1-1V6A1 1 0 0 1 7 6V18A1 1 0 0 1 6 19zM12 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 12 18zM9 21a1 1 0 0 1-1-1V4a1 1 0 0 1 2 0V20A1 1 0 0 1 9 21zM3 17a1 1 0 0 1-1-1V8A1 1 0 0 1 4 8v8A1 1 0 0 1 3 17zM21 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 21 16zM15 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 15 16zM18 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 18 18z" fill="#7987a1" class="color000 svgShape"></path>
                                                    </svg></g>
                                            </svg>
                                            <svg class="chat_audio" width="20" height="20">
                                                <defs></defs>
                                                <g transform="matrix(1,0,0,1,0,0)"><svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 3" viewBox="0 0 24 24" width="20" height="20">
                                                        <path d="M6 19a1 1 0 0 1-1-1V6A1 1 0 0 1 7 6V18A1 1 0 0 1 6 19zM12 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 12 18zM9 21a1 1 0 0 1-1-1V4a1 1 0 0 1 2 0V20A1 1 0 0 1 9 21zM3 17a1 1 0 0 1-1-1V8A1 1 0 0 1 4 8v8A1 1 0 0 1 3 17zM21 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 21 16zM15 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 15 16zM18 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 18 18z" fill="#7987a1" class="color000 svgShape"></path>
                                                    </svg></g>
                                            </svg>
                                            <svg class="chat_audio" width="20" height="20">
                                                <defs></defs>
                                                <g transform="matrix(1,0,0,1,0,0)"><svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 3" viewBox="0 0 24 24" width="20" height="20">
                                                        <path d="M6 19a1 1 0 0 1-1-1V6A1 1 0 0 1 7 6V18A1 1 0 0 1 6 19zM12 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 12 18zM9 21a1 1 0 0 1-1-1V4a1 1 0 0 1 2 0V20A1 1 0 0 1 9 21zM3 17a1 1 0 0 1-1-1V8A1 1 0 0 1 4 8v8A1 1 0 0 1 3 17zM21 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 21 16zM15 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 15 16zM18 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 18 18z" fill="#7987a1" class="color000 svgShape"></path>
                                                    </svg></g>
                                            </svg>
                                            <svg class="chat_audio" width="20" height="20">
                                                <defs></defs>
                                                <g transform="matrix(1,0,0,1,0,0)"><svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 3" viewBox="0 0 24 24" width="20" height="20">
                                                        <path d="M6 19a1 1 0 0 1-1-1V6A1 1 0 0 1 7 6V18A1 1 0 0 1 6 19zM12 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 12 18zM9 21a1 1 0 0 1-1-1V4a1 1 0 0 1 2 0V20A1 1 0 0 1 9 21zM3 17a1 1 0 0 1-1-1V8A1 1 0 0 1 4 8v8A1 1 0 0 1 3 17zM21 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 21 16zM15 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 15 16zM18 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 18 18z" fill="#7987a1" class="color000 svgShape"></path>
                                                    </svg></g>
                                            </svg>
                                            <svg class="chat_audio" width="20" height="20">
                                                <defs></defs>
                                                <g transform="matrix(1,0,0,1,0,0)"><svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 3" viewBox="0 0 24 24" width="20" height="20">
                                                        <path d="M6 19a1 1 0 0 1-1-1V6A1 1 0 0 1 7 6V18A1 1 0 0 1 6 19zM12 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 12 18zM9 21a1 1 0 0 1-1-1V4a1 1 0 0 1 2 0V20A1 1 0 0 1 9 21zM3 17a1 1 0 0 1-1-1V8A1 1 0 0 1 4 8v8A1 1 0 0 1 3 17zM21 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 21 16zM15 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 15 16zM18 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 18 18z" fill="#7987a1" class="color000 svgShape"></path>
                                                    </svg></g>
                                            </svg>
                                            <svg class="chat_audio" width="20" height="20">
                                                <defs></defs>
                                                <g transform="matrix(1,0,0,1,0,0)"><svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 3" viewBox="0 0 24 24" width="20" height="20">
                                                        <path d="M6 19a1 1 0 0 1-1-1V6A1 1 0 0 1 7 6V18A1 1 0 0 1 6 19zM12 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 12 18zM9 21a1 1 0 0 1-1-1V4a1 1 0 0 1 2 0V20A1 1 0 0 1 9 21zM3 17a1 1 0 0 1-1-1V8A1 1 0 0 1 4 8v8A1 1 0 0 1 3 17zM21 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 21 16zM15 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 15 16zM18 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 18 18z" fill="#7987a1" class="color000 svgShape"></path>
                                                    </svg></g>
                                            </svg>
                                            <svg class="chat_audio" width="20" height="20">
                                                <defs></defs>
                                                <g transform="matrix(1,0,0,1,0,0)"><svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 3" viewBox="0 0 24 24" width="20" height="20">
                                                        <path d="M6 19a1 1 0 0 1-1-1V6A1 1 0 0 1 7 6V18A1 1 0 0 1 6 19zM12 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 12 18zM9 21a1 1 0 0 1-1-1V4a1 1 0 0 1 2 0V20A1 1 0 0 1 9 21zM3 17a1 1 0 0 1-1-1V8A1 1 0 0 1 4 8v8A1 1 0 0 1 3 17zM21 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 21 16zM15 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 15 16zM18 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 18 18z" fill="#7987a1" class="color000 svgShape"></path>
                                                    </svg></g>
                                            </svg>
                                            <svg class="chat_audio" width="20" height="20">
                                                <defs></defs>
                                                <g transform="matrix(1,0,0,1,0,0)"><svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 3" viewBox="0 0 24 24" width="20" height="20">
                                                        <path d="M6 19a1 1 0 0 1-1-1V6A1 1 0 0 1 7 6V18A1 1 0 0 1 6 19zM12 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 12 18zM9 21a1 1 0 0 1-1-1V4a1 1 0 0 1 2 0V20A1 1 0 0 1 9 21zM3 17a1 1 0 0 1-1-1V8A1 1 0 0 1 4 8v8A1 1 0 0 1 3 17zM21 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 21 16zM15 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 15 16zM18 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 18 18z" fill="#7987a1" class="color000 svgShape"></path>
                                                    </svg></g>
                                            </svg>
                                            <svg class="chat_audio" width="20" height="20">
                                                <defs></defs>
                                                <g transform="matrix(1,0,0,1,0,0)"><svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 3" viewBox="0 0 24 24" width="20" height="20">
                                                        <path d="M6 19a1 1 0 0 1-1-1V6A1 1 0 0 1 7 6V18A1 1 0 0 1 6 19zM12 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 12 18zM9 21a1 1 0 0 1-1-1V4a1 1 0 0 1 2 0V20A1 1 0 0 1 9 21zM3 17a1 1 0 0 1-1-1V8A1 1 0 0 1 4 8v8A1 1 0 0 1 3 17zM21 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 21 16zM15 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 15 16zM18 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 18 18z" fill="#7987a1" class="color000 svgShape"></path>
                                                    </svg></g>
                                            </svg>
                                            <svg class="chat_audio" width="20" height="20">
                                                <defs></defs>
                                                <g transform="matrix(1,0,0,1,0,0)"><svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 3" viewBox="0 0 24 24" width="20" height="20">
                                                        <path d="M6 19a1 1 0 0 1-1-1V6A1 1 0 0 1 7 6V18A1 1 0 0 1 6 19zM12 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 12 18zM9 21a1 1 0 0 1-1-1V4a1 1 0 0 1 2 0V20A1 1 0 0 1 9 21zM3 17a1 1 0 0 1-1-1V8A1 1 0 0 1 4 8v8A1 1 0 0 1 3 17zM21 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 21 16zM15 16a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v6A1 1 0 0 1 15 16zM18 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0V17A1 1 0 0 1 18 18z" fill="#7987a1" class="color000 svgShape"></path>
                                                    </svg></g>
                                            </svg>
                                        </span>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-def white"><i class="fe fe-download"></i></a>
                                    </div>
                                </div>
                                <span class="msg_time_send">9:15 AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start chat_block">
                        <div class="me-1 d-flex align-items-end">
                            <div class="avatar avatar-sm">
                                <img src="../assets/img/faces/6.jpg" class="rounded-circle" alt="img">
                            </div>
                        </div>
                        <div class="msg_block">
                            <div class="msg_container">
                                <div class="msg_cotainer-main">
                                    <span>I just went to vegetable store.</span>
                                </div>
                                <div class="msg_cotainer-main">
                                    <span>You can come here or wait for just 5 min</span>
                                </div>
                                <span class="tx-10 tx-muted msg_time">9:15 AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end chat_block">
                        <div class="msg_block_send">
                            <div class="msg_container_send">
                                <div class="msg_cotainer_send-main">
                                    <span>Ok then I'll wait...</span>
                                </div>
                                <div class="msg_cotainer_send-main">
                                    <span>just call me or come inside to coffee shop when you came by</span>
                                </div>
                                <span class="msg_time_send">9:15 AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start chat_block">
                        <div class="me-1 d-flex align-items-end">
                            <div class="avatar avatar-sm">
                                <img src="../assets/img/faces/6.jpg" class="rounded-circle" alt="img">
                            </div>
                        </div>
                        <div class="msg_block">
                            <div class="msg_container">
                                <div class="msg_cotainer-main">
                                    <span>Deal... Bye</span>
                                </div>
                                <div class="msg_cotainer-main">
                                    <span>Enjoy your coffee</span>
                                </div>
                                <span class="tx-10 tx-muted msg_time">9:16 AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end chat_block">
                        <div class="msg_block_send">
                            <div class="msg_container_send">
                                <div class="msg_cotainer_send-main">
                                    <span>Definitely and Bye...</span>
                                </div>
                                <span class="msg_time_send">9:17 AM</span>
                            </div>
                            <div class="msg_container_send">
                                <div class="msg_cotainer_send-main media-files">
                                    <img src="../assets/img/ecommerce/30.jpg" alt="img">
                                </div>
                                <span class="msg_time_send">9:30 AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start chat_block">
                        <div class="me-1 d-flex align-items-end">
                            <div class="avatar avatar-sm">
                                <img src="../assets/img/faces/6.jpg" class="rounded-circle" alt="img">
                            </div>
                        </div>
                        <div class="msg_block">
                            <div class="msg_container">
                                <div class="msg_cotainer-main">
                                    <span>I'm in coffee shop..</span>
                                </div>
                                <span class="tx-10 tx-muted msg_time">9:45 AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end chat_block">
                        <div class="msg_block_send">
                            <div class="msg_container_send">
                                <div class="msg_cotainer_send-main">
                                    <span>come to 8th number table</span>
                                </div>
                                <span class="msg_time_send">9:46 AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start chat_block">
                        <div class="me-1 d-flex align-items-end">
                            <div class="avatar avatar-sm">
                                <img src="../assets/img/faces/6.jpg" class="rounded-circle" alt="img">
                            </div>
                        </div>
                        <div class="msg_block">
                            <div class="msg_container">
                                <div class="msg_cotainer-main">
                                    <span>Ok.. coming...</span>
                                </div>
                                <span class="tx-10 tx-muted msg_time">9:46 AM</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- msg_card_body end -->

                <!-- card-footer -->
                <div class="card-footer">
                    <div class="form-group mb-0 d-flex">
                        <div class="input-group pos-relative">
                            <input type="text" class="form-control radius-4" placeholder="Type something here...">
                            <div class="chat_input_icons">
                                <a href="javascript:void(0)" class="tx-muted me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="emoji"><i class="ti-face-smile"></i></a>
                                <a href="javascript:void(0)" class="tx-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="attach files"><i class="fe fe-paperclip"></i></a>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary rounded-circle ms-2">
                            <i class="far fa-paper-plane" aria-hidden="true"></i>
                        </button>
                    </div>
                </div><!-- card-footer end -->
            </div>
        </div>
    </div>
</div><!-- /modal -->

<!--Video Modal -->
<div id="videoModal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body mx-auto text-center p-7">
                <h5>Zem Video call</h5>
                <img src="../assets/img/faces/6.jpg" class="rounded-circle user-img-circle h-8 w-8 mt-4 mb-3" alt="img">
                <h4 class="mb-1 font-weight-semibold">Daneil Scott</h4>
                <h6>Calling...</h6>
                <div class="flex-center mt-4 p-2">
                    <a class="btn btn-outline-primary rounded-circle ht-40 wd-40 flex-center" href="javascript:void(0);">
                        <i class="fas fa-volume-up"></i>
                    </a>
                    <a class="btn btn-danger rounded-circle ht-50 wd-50 flex-center mx-3" href="javascript:void(0);" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-phone"></i>
                    </a>
                    <a class="btn btn-outline-primary rounded-circle ht-40 wd-40 flex-center" href="javascript:void(0);">
                        <i class="fas fa-microphone-slash"></i>
                    </a>
                </div>
            </div><!-- modal-body -->
        </div>
    </div><!-- modal-dialog -->
</div><!-- modal -->

<!-- Audio Modal -->
<div id="audioModal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body mx-auto text-center p-7">
                <h5>Zem Voice call</h5>
                <img src="../assets/img/faces/6.jpg" class="rounded-circle user-img-circle h-8 w-8 mt-4 mb-3" alt="img">
                <h4 class="mb-1  font-weight-semibold">Daneil Scott</h4>
                <h6>Calling...</h6>
                <div class="flex-center mt-4 p-2">
                    <a class="btn btn-outline-primary rounded-circle ht-40 wd-40 flex-center" href="javascript:void(0);">
                        <i class="fas fa-volume-up"></i>
                    </a>
                    <a class="btn btn-danger rounded-circle ht-50 wd-50 flex-center mx-3" href="javascript:void(0);" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-phone"></i>
                    </a>
                    <a class="btn btn-outline-primary rounded-circle ht-40 wd-40 flex-center" href="javascript:void(0);">
                        <i class="fas fa-microphone-slash"></i>
                    </a>
                </div>
            </div><!-- modal-body -->
        </div>
    </div><!-- modal-dialog -->
</div><!-- modal -->