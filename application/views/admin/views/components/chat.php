<style>
    .chat-container {
        height: 100vh;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .chat-content {
        height: calc(100vh - 60px);
        flex-grow: 1;
        overflow-y: auto;
        background-color: #EEF0F1;
    }

    .chat-input-group {
        width: 100%;
        padding: 10px;
        border-top: 1px solid #ccc;

    }

    .bg-primary {
        background-color: #E5EFFF !important;
    }

    .rounded-circle {
        border-radius: 50% !important;
    }

    .chat-bubble {
        max-width: 60%;
        word-wrap: break-word;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .text-muted {
        font-size: 0.75rem;
        opacity: 0.7;
    }

    .fw-bold {
        margin-bottom: 0.25rem;
    }
</style>

<!-- HTML -->
<button class="btn btn-sm btn_show_conversation_<?= $task->id ?>" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRightConversation_<?= $task->id ?>" aria-controls="offcanvasRightConversation" data-task-id="<?= $task->id ?>">
    <?php if (!empty($this->conversations_model->get_by_task($task->id))) : ?>
        <svg style="color: #0d6efd;" viewBox="0 0 20 20" fill="currentColor" width="22" height="22" aria-hidden="true" class="icon_0cb728e603 conversation-cta-module_withUpdates__lLlWU noFocusStyle_da5118627f" data-testid="icon">
            <path d="M10.4339 1.95001C11.5975 1.94802 12.7457 2.2162 13.7881 2.73345C14.8309 3.25087 15.7392 4.0034 16.4416 4.93172C17.1439 5.86004 17.6211 6.93879 17.8354 8.08295C18.0498 9.22712 17.9955 10.4054 17.6769 11.525C17.3582 12.6447 16.7839 13.675 15.9992 14.5348C15.2144 15.3946 14.2408 16.0604 13.1549 16.4798C12.0689 16.8991 10.9005 17.0606 9.74154 16.9514C8.72148 16.8553 7.73334 16.5518 6.83716 16.0612L4.29488 17.2723C3.23215 17.7786 2.12265 16.6693 2.6287 15.6064L3.83941 13.0637C3.26482 12.0144 2.94827 10.8411 2.91892 9.64118C2.88616 8.30174 3.21245 6.97794 3.86393 5.80714C4.51541 4.63635 5.46834 3.66124 6.62383 2.98299C7.77896 2.30495 9.09445 1.9483 10.4339 1.95001ZM10.4339 1.95001C10.4343 1.95001 10.4347 1.95001 10.4351 1.95001L10.434 2.70001L10.4326 1.95001C10.433 1.95001 10.4334 1.95001 10.4339 1.95001ZM13.1214 4.07712C12.2867 3.66294 11.3672 3.44826 10.4354 3.45001L10.4329 3.45001C9.3608 3.44846 8.30778 3.73387 7.38315 4.2766C6.45852 4.81934 5.69598 5.59963 5.17467 6.5365C4.65335 7.47337 4.39226 8.53268 4.41847 9.6045C4.44469 10.6763 4.75726 11.7216 5.32376 12.6319C5.45882 12.8489 5.47405 13.1198 5.36416 13.3506L4.28595 15.6151L6.54996 14.5366C6.78072 14.4266 7.05158 14.4418 7.26863 14.5768C8.05985 15.0689 8.95456 15.3706 9.88225 15.458C10.8099 15.5454 11.7452 15.4162 12.6145 15.0805C13.4837 14.7448 14.2631 14.2119 14.8912 13.5236C15.5194 12.8354 15.9791 12.0106 16.2341 11.1144C16.4892 10.2182 16.5327 9.27504 16.3611 8.35918C16.1895 7.44332 15.8075 6.57983 15.2453 5.83674C14.6831 5.09366 13.9561 4.49129 13.1214 4.07712Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
        </svg>
    <?php else : ?>
        <svg viewBox="0 0 20 20" fill="currentColor" width="22" height="22" aria-hidden="true" class="icon_0cb728e603 conversation-cta-module_withoutUpdates__LoZDn noFocusStyle_da5118627f" data-testid="icon">
            <path d="M10.4339 1.94996C11.5976 1.94797 12.7458 2.21616 13.7882 2.7334C14.8309 3.25083 15.7393 4.00335 16.4416 4.93167C17.144 5.85999 17.6211 6.93874 17.8355 8.08291C18.0498 9.22707 17.9956 10.4054 17.6769 11.525C17.3583 12.6446 16.7839 13.6749 15.9992 14.5347C15.2145 15.3945 14.2408 16.0604 13.1549 16.4797C12.069 16.8991 10.9005 17.0605 9.7416 16.9513C8.72154 16.8552 7.7334 16.5518 6.83723 16.0612L4.29494 17.2723C3.23222 17.7785 2.12271 16.6692 2.62876 15.6064L3.83948 13.0636C3.26488 12.0144 2.94833 10.8411 2.91898 9.64114C2.88622 8.30169 3.21251 6.97789 3.86399 5.8071C4.51547 4.63631 5.4684 3.66119 6.62389 2.98294C7.77902 2.30491 9.09451 1.94825 10.4339 1.94996ZM10.4339 1.94996C10.4343 1.94996 10.4348 1.94996 10.4352 1.94996L10.4341 2.69996L10.4327 1.94996C10.4331 1.94996 10.4335 1.94996 10.4339 1.94996ZM13.1214 4.07707C12.2868 3.66289 11.3673 3.44821 10.4355 3.44996L10.433 3.44996C9.36086 3.44842 8.30784 3.73382 7.38321 4.27655C6.45858 4.81929 5.69605 5.59958 5.17473 6.53645C4.65341 7.47332 4.39232 8.53263 4.41853 9.60446C4.44475 10.6763 4.75732 11.7216 5.32382 12.6318C5.45888 12.8489 5.47411 13.1197 5.36422 13.3505L4.28601 15.615L6.55002 14.5365C6.78078 14.4266 7.05164 14.4418 7.26869 14.5768C8.05992 15.0689 8.95463 15.3706 9.88231 15.458C10.81 15.5454 11.7453 15.4161 12.6145 15.0805C13.4838 14.7448 14.2631 14.2118 14.8913 13.5236C15.5194 12.8353 15.9791 12.0106 16.2342 11.1144C16.4893 10.2182 16.5327 9.27499 16.3611 8.35913C16.1895 7.44328 15.8076 6.57978 15.2454 5.8367C14.6832 5.09362 13.9561 4.49125 13.1214 4.07707Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
            <path d="M11.25 6.5C11.25 6.08579 10.9142 5.75 10.5 5.75C10.0858 5.75 9.75 6.08579 9.75 6.5V8.75H7.5C7.08579 8.75 6.75 9.08579 6.75 9.5C6.75 9.91421 7.08579 10.25 7.5 10.25H9.75V12.5C9.75 12.9142 10.0858 13.25 10.5 13.25C10.9142 13.25 11.25 12.9142 11.25 12.5V10.25H13.5C13.9142 10.25 14.25 9.91421 14.25 9.5C14.25 9.08579 13.9142 8.75 13.5 8.75H11.25V6.5Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
        </svg>
    <?php endif; ?>
</button>

<div class="offcanvas-wrapper">
    <div class="offcanvas offcanvas-end w-75" tabindex="-1" id="offcanvasRightConversation_<?= $task->id ?>" aria-labelledby="offcanvasRightConversation_<?= $task->id ?>Label" data-task-id="<?= $task->id ?>">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasRightConversation_<?= $task->id ?>Label">
                <?= $task->title ?>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body chat-container chat_container_<?= $task->id ?>">
            <div class="chat-content p-3 border border-1 flex-grow-1 chat-window chat_<?= $task->id ?>">

            </div>

            <div class="chat-input-group">
                <div class="row">
                    <div class="text-center d-inline" style="width:90%;">
                        <input type="text" class="form-control border border-1 w-100" style="background-color: #EDEDED;" placeholder="Nhập nội dung cuộc trò chuyện...">
                    </div>

                    <div class="text-center d-inline" style="width:10%;">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary w-100 btn_send_message" type="button">
                                <i class="fa fa-paper-plane" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    $(window).on('load resize', function() {
        var offcanvasElement = $("#offcanvasRightConversation_<?= $task->id ?>");

        // Kiểm tra nếu kích thước của thiết bị nhỏ hơn 768px (giao diện điện thoại)
        if ($(window).width() < 768) {
            offcanvasElement.addClass("offcanvas-full"); // Thêm lớp offcanvas-full
        } else {
            offcanvasElement.removeClass("offcanvas-full"); // Xóa lớp offcanvas-full
        }
    });

    $(document).ready(function() {

        const item_id = <?= $task->id ?>;
        const user_id = <?= $this->session->userdata('user_id') ?>;

        const chat_window = $('.chat_' + item_id);

        const svg_empty_chat_icon = `
                <svg viewBox="0 0 20 20" fill="currentColor" width="22" height="22" aria-hidden="true" class="icon_0cb728e603 conversation-cta-module_withoutUpdates__LoZDn noFocusStyle_da5118627f" data-testid="icon">
                    <path d="M10.4339 1.94996C11.5976 1.94797 12.7458 2.21616 13.7882 2.7334C14.8309 3.25083 15.7393 4.00335 16.4416 4.93167C17.144 5.85999 17.6211 6.93874 17.8355 8.08291C18.0498 9.22707 17.9956 10.4054 17.6769 11.525C17.3583 12.6446 16.7839 13.6749 15.9992 14.5347C15.2145 15.3945 14.2408 16.0604 13.1549 16.4797C12.069 16.8991 10.9005 17.0605 9.7416 16.9513C8.72154 16.8552 7.7334 16.5518 6.83723 16.0612L4.29494 17.2723C3.23222 17.7785 2.12271 16.6692 2.62876 15.6064L3.83948 13.0636C3.26488 12.0144 2.94833 10.8411 2.91898 9.64114C2.88622 8.30169 3.21251 6.97789 3.86399 5.8071C4.51547 4.63631 5.4684 3.66119 6.62389 2.98294C7.77902 2.30491 9.09451 1.94825 10.4339 1.94996ZM10.4339 1.94996C10.4343 1.94996 10.4348 1.94996 10.4352 1.94996L10.4341 2.69996L10.4327 1.94996C10.4331 1.94996 10.4335 1.94996 10.4339 1.94996ZM13.1214 4.07707C12.2868 3.66289 11.3673 3.44821 10.4355 3.44996L10.433 3.44996C9.36086 3.44842 8.30784 3.73382 7.38321 4.27655C6.45858 4.81929 5.69605 5.59958 5.17473 6.53645C4.65341 7.47332 4.39232 8.53263 4.41853 9.60446C4.44475 10.6763 4.75732 11.7216 5.32382 12.6318C5.45888 12.8489 5.47411 13.1197 5.36422 13.3505L4.28601 15.615L6.55002 14.5365C6.78078 14.4266 7.05164 14.4418 7.26869 14.5768C8.05992 15.0689 8.95463 15.3706 9.88231 15.458C10.81 15.5454 11.7453 15.4161 12.6145 15.0805C13.4838 14.7448 14.2631 14.2118 14.8913 13.5236C15.5194 12.8353 15.9791 12.0106 16.2342 11.1144C16.4893 10.2182 16.5327 9.27499 16.3611 8.35913C16.1895 7.44328 15.8076 6.57978 15.2454 5.8367C14.6832 5.09362 13.9561 4.49125 13.1214 4.07707Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                    <path d="M11.25 6.5C11.25 6.08579 10.9142 5.75 10.5 5.75C10.0858 5.75 9.75 6.08579 9.75 6.5V8.75H7.5C7.08579 8.75 6.75 9.08579 6.75 9.5C6.75 9.91421 7.08579 10.25 7.5 10.25H9.75V12.5C9.75 12.9142 10.0858 13.25 10.5 13.25C10.9142 13.25 11.25 12.9142 11.25 12.5V10.25H13.5C13.9142 10.25 14.25 9.91421 14.25 9.5C14.25 9.08579 13.9142 8.75 13.5 8.75H11.25V6.5Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                </svg>
            `;

        const svg_chat_icon = `
                <svg style="color: #0d6efd;" viewBox="0 0 20 20" fill="currentColor" width="22" height="22" aria-hidden="true" class="icon_0cb728e603 conversation-cta-module_withUpdates__lLlWU noFocusStyle_da5118627f" data-testid="icon">
                <path d="M10.4339 1.95001C11.5975 1.94802 12.7457 2.2162 13.7881 2.73345C14.8309 3.25087 15.7392 4.0034 16.4416 4.93172C17.1439 5.86004 17.6211 6.93879 17.8354 8.08295C18.0498 9.22712 17.9955 10.4054 17.6769 11.525C17.3582 12.6447 16.7839 13.675 15.9992 14.5348C15.2144 15.3946 14.2408 16.0604 13.1549 16.4798C12.0689 16.8991 10.9005 17.0606 9.74154 16.9514C8.72148 16.8553 7.73334 16.5518 6.83716 16.0612L4.29488 17.2723C3.23215 17.7786 2.12265 16.6693 2.6287 15.6064L3.83941 13.0637C3.26482 12.0144 2.94827 10.8411 2.91892 9.64118C2.88616 8.30174 3.21245 6.97794 3.86393 5.80714C4.51541 4.63635 5.46834 3.66124 6.62383 2.98299C7.77896 2.30495 9.09445 1.9483 10.4339 1.95001ZM10.4339 1.95001C10.4343 1.95001 10.4347 1.95001 10.4351 1.95001L10.434 2.70001L10.4326 1.95001C10.433 1.95001 10.4334 1.95001 10.4339 1.95001ZM13.1214 4.07712C12.2867 3.66294 11.3672 3.44826 10.4354 3.45001L10.4329 3.45001C9.3608 3.44846 8.30778 3.73387 7.38315 4.2766C6.45852 4.81934 5.69598 5.59963 5.17467 6.5365C4.65335 7.47337 4.39226 8.53268 4.41847 9.6045C4.44469 10.6763 4.75726 11.7216 5.32376 12.6319C5.45882 12.8489 5.47405 13.1198 5.36416 13.3506L4.28595 15.6151L6.54996 14.5366C6.78072 14.4266 7.05158 14.4418 7.26863 14.5768C8.05985 15.0689 8.95456 15.3706 9.88225 15.458C10.8099 15.5454 11.7452 15.4162 12.6145 15.0805C13.4837 14.7448 14.2631 14.2119 14.8912 13.5236C15.5194 12.8354 15.9791 12.0106 16.2341 11.1144C16.4892 10.2182 16.5327 9.27504 16.3611 8.35918C16.1895 7.44332 15.8075 6.57983 15.2453 5.83674C14.6831 5.09366 13.9561 4.49129 13.1214 4.07712Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
            </svg>`;

        $('body').on('click', '.btn_show_conversation_<?= $task->id ?>', function() {
            load_chat_contents();
        });

        $('#offcanvasRightConversation_<?= $task->id ?>').on('click', '.btn_send_message', function() {
            sendMessage()
        });

        $('#offcanvasRightConversation_<?= $task->id ?>').on('keydown', 'input', function(e) {
            if (e.key === 'Enter' && !e.ctrlKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        function sendMessage() {

            const input = $('#offcanvasRightConversation_<?= $task->id ?>').find('input');

            const contents = input.val().trim();

            if (contents != '') {
                $.ajax({
                    url: '<?= base_url('conversation/add_conversation') ?>',
                    method: 'post',
                    data: {
                        item_id: item_id,
                        user_id: user_id,
                        parent_id: 0,
                        contents: contents,
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (chat_window.find('.chat-item').length == 0) {
                            chat_window.empty();
                            $('.btn_show_conversation_' + item_id).html(svg_chat_icon);
                        }

                        chat_window.append(res.data);
                        scrollToBottom(chat_window);
                        input.val('');
                    }
                });
            } else {
                $('#offcanvasRightConversation_<?= $task->id ?>').find('input').focus()
            }
        }

        function load_chat_contents() {
            $('#offcanvasRightConversation_' + item_id).find('input').val('');

            $.ajax({
                url: '<?= base_url('conversation/conversations_by_task') ?>',
                method: 'get',
                data: {
                    item_id: item_id,
                    user_id: user_id,
                },
                dataType: 'json',
                success: function(res) {

                    if (res.data == '') {

                        let html = ' <div class="space_view middle_style mt-5">' +
                            '<div id="wall" class="wall new_pulse text-center">' +
                            '<div class="posts_list no_posts">' +
                            '<div class="post_empty_state_image_wrapper"><img src="<?= base_url() ?>/assets/images/pulse-page-empty-state.svg" width="50%"></div>' +
                            '<div class="unread_message">' +
                            '<div class="post_not_found_text text-center">' +
                            '<h4 class="post_not_found">Không có cuộc trò chuyện nào cho công việc này</h4>' +
                            '<p class="post_not_found_subtitle">Hãy là người đầu tiên cập nhật tiến độ, đề cập đến ai đó <br> hoặc tải tệp lên để chia sẻ với các thành viên trong nhóm của bạn</p>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>';

                        chat_window.html(html);

                    } else {
                        chat_window.html(res.data);

                        scrollToBottom(chat_window);
                    }
                }
            });
        }

        function scrollToBottom(element) {
            element.scrollTop(element.prop("scrollHeight"));
        }

    });
</script>