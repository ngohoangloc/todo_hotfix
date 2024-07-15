<style>

    .post_title {

        display: flex;

    }



    .user_name {

        display: grid;

        grid-template-columns: auto 1fr;

        align-items: center;

        column-gap: 10px;

        padding-left: 10px;

    }



    .post-comment {

        display: inline-flex;

        margin: 10px auto;

        width: 100%;

    }



    .post-comment .user_avatar img {

        margin-right: 10px;

    }



    .post-comment .form-control {

        height: 30px;

        border: 1px solid #ccc;

        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);

        margin: 7px 0;

        min-width: 0;

    }



</style>



<?php

$login_user_id = $this->session->userdata('user_id');

?>



<div class="post py-2 conversation_<?= $conversation->id ?>" data-conversation-id="<?= $conversation->id ?>">

    <!-- Comment -->

    <div class="card shadow">

        <div class="card-body">

            <div class="row post_header">

                <div class="col-6 post_title">

                    <div class="user_avatar">

                        <img src="<?= base_url() . $user->avatar; ?>" alt="">

                    </div>

                    <span class="user_name">

                        <h6><?= $user->firstname . ' ' . $user->lastname ?></h6>

                    </span>

                </div>

                <div class="col-6 text-end post_top_right">

                    <?php if ($login_user_id == $conversation->user_id) : ?>

                        <button class="btn" data-bs-toggle="dropdown" aria-expanded="true" aria-hidden="true">

                            <i class="fa fa-ellipsis-h"></i>

                        </button>



                        <ul class="dropdown-menu" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(0px, 56.8px, 0px);" data-popper-placement="bottom-start">

                            <li data-conversation-id="<?= $conversation->id ?>" class="px-2 edit_conversation">

                                <span><i class="fa fa-pencil" aria-hidden="true"></i></span> Cập nhật

                            </li>

                            <li data-conversation-id="<?= $conversation->id ?>" class="px-2 delete_conversation"><span><i class="fa fa-trash" aria-hidden="true"></i></span> Xóa</li>

                        </ul>

                    <?php endif; ?>

                </div>

            </div>



            <div class="row post_body">



                <div class="post_content py-2 post_content_<?= $conversation->id ?>">

                    <?= $conversation->contents ?>

                </div>



                <div class="post_action mt-2 post_action_<?= $conversation->id ?>">

                    <div class="col-12">

                        <?php if (in_array($this->session->userdata('user_id'), explode(',', $conversation->liked_users_id))) : ?>

                            <button class="btn btn-sm btn-outline-primary mx-2 btn_like_post" data-conversation-id="<?= $conversation->id ?>"><i class="fa fa-thumbs-up" aria-hidden="true"></i> <b class="numLike"><?= !empty($conversation->liked_users_id) ? count(explode(',', $conversation->liked_users_id)) : 'Thích' ?></b></button>

                        <?php else : ?>

                            <button class="btn btn-sm btn-outline-primary mx-2 btn_like_post" data-conversation-id="<?= $conversation->id ?>"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> <b class="numLike"><?= !empty($conversation->liked_users_id) ? count(explode(',', $conversation->liked_users_id)) : 'Thích' ?></b></button>

                        <?php endif; ?>



                        <button data-user="<?= $user->firstname . ' ' . $user->lastname ?>" data-conversation-id="<?= $conversation->id ?>" class="btn btn-sm btn-outline-secondary mx-2 btn_reply_post"><i class="fa fa-reply" aria-hidden="true"></i> <b>Trả lời</b></button>

                    </div>

                </div>

            </div>

            <div class="row post_footer_<?= $conversation->id ?> mt-3 ">



                <!-- Reply comment -->

                <div class="post_replies_<?= $conversation->id ?>">

                    <!-- Replies contents -->

                </div>



                <div class="row reply_post_id">

                    <div class="col-1">

                    </div>

                    <div class="col-11 reply_body_component">

                        <div class="card shadow">

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-1">

                                        <div class="user_avatar">

                                            <img src="<?= base_url() . $this->User_model->get_user_by_id($this->session->userdata('user_id'))->avatar ?>" alt="">

                                        </div>

                                    </div>

                                    <div class="col-11 new_reply_conversation_<?= $conversation->id ?>">

                                        <button class="btn btn-outline-primary btn-writing-reply-post w-100 text-start"  data-conversation-id="<?= $conversation->id ?>" style="color: #333;">Viết câu trả lời...</button>

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



<script>

    $(document).ready(function() {



        let conversation_id = <?= $conversation->id ?>;



        // Get comment for post

        $.ajax({

            url: "<?= base_url('conversation/replies_by_conversation') ?>",

            method: "get",

            data: {

                conversation_id: conversation_id,

            },

            dataType: "json",

            success: function(response) {

                

                if (tinymce.get('update_post_' + conversation_id + '_editor')) {

                    tinymce.get('update_post_' + conversation_id + '_editor').remove();

                }



                $('.post_replies_' + conversation_id).append(response.data);

            }

        });





    });

</script>