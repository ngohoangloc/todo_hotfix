<?php

$login_user_id = $this->session->userdata('user_id');

?>



<div id="reply-<?= $reply->id ?>" class="reply-component conversation_<?= $reply->id ?> py-3">

    <div class="row">

        <div class="col-1">

        </div>

        <div class="col-11 reply_body_component">

            <div class="card shadow p-3">

                <div class="row pb-3">

                    <div class="col-6 post_title">

                        <div class="user_avatar">

                            <img id="avatarImg" src="<?= base_url() . $user->avatar; ?>" alt="">

                        </div>

                        <span class="user_name">

                            <h6><?= $user->firstname . ' ' . $user->lastname ?></h6>

                        </span>

                    </div>

                    <div class="col-6 text-end post_top_right">

                        <?php if ($login_user_id == $reply->user_id) : ?>

                            <button class="btn" data-bs-toggle="dropdown" aria-expanded="true" aria-hidden="true">

                                <i class="fa fa-ellipsis-h"></i>

                            </button>



                            <ul class="dropdown-menu" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(0px, 56.8px, 0px);" data-popper-placement="bottom-start">

                                <li data-conversation-id="<?= $reply->id ?>" class="px-2 edit_conversation"><span><i class="fa fa-pencil" aria-hidden="true"></i></span> Cập nhật</li>

                                <li data-conversation-id="<?= $reply->id ?>" class="px-2 delete_conversation_<?= $reply->id ?>"><span><i class="fa fa-trash" aria-hidden="true"></i></span> Xóa</li>

                            </ul>

                        <?php endif; ?>

                    </div>

                </div>

                <span class="reply_content pl-3 post_content_<?= $reply->id ?>"><?= $reply->contents ?></span>

            </div>

            <div class="post_action mt-2 post_action_<?= $reply->id ?>"">

                <div class=" col-12">

                <?php if (in_array($this->session->userdata('user_id'), explode(',', $reply->liked_users_id))) : ?>

                    <button class="btn btn-sm btn-outline-primary mx-2 btn_like_post" data-conversation-id="<?= $reply->id ?>"><i class="fa fa-thumbs-up" aria-hidden="true"></i> <b class="numLike"><?= !empty($reply->liked_users_id) ? count(explode(',', $reply->liked_users_id)) : 'Thích' ?></b></button>

                <?php else : ?>

                    <button class="btn btn-sm btn-outline-primary mx-2 btn_like_post" data-conversation-id="<?= $reply->id ?>"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> <b class="numLike"><?= !empty($reply->liked_users_id) ? count(explode(',', $reply->liked_users_id)) : 'Thích' ?></b></button>

                <?php endif; ?>

                <button data-user="<?= $user->firstname . ' ' . $user->lastname ?>" data-conversation-id="<?= $reply->parent_id ?>" class="btn btn-sm btn-outline-secondary mx-2 btn_reply_post"><i class="fa fa-reply" aria-hidden="true"></i> <b>Trả lời</b></button>

            </div>

        </div>

    </div>

</div>

</div>



<script>

    $(document).ready(function() {



        const reply_id = <?= $reply->id ?>;



        if (tinymce.get('update_post_' + reply_id + '_editor')) {

            tinymce.get('update_post_' + reply_id + '_editor').remove();

        }



        $('body').on('click', '.delete_conversation_<?= $reply->id ?>', function() {



            let delete_conversation_id = $(this).data('conversation-id');



            $.ajax({

                url: "<?= base_url('conversation/delete_conversation') ?>",

                method: "post",

                data: {

                    conversation_id: delete_conversation_id,

                },

                dataType: "json",

                success: function(response) {

                    $('.conversation_' + delete_conversation_id).remove();

                }

            });

        });

    });

</script>