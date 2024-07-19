<style>
    body {
        font-family: Roboto, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        background-color: #fff;
    }

    .zalo-button {
        display: inline-block;
        width: 50px;
        height: 50px;
        background-color: #0068ff;
        text-align: center;
        border-radius: 50%;
        text-decoration: none;
        overflow: hidden;
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    .zalo-button img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .zalo-button:hover {
        background-color: #0057d9;
    }

    .container {
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }

    .logo {
        width: 50px;
        margin-bottom: 20px;
    }

    h1 {
        margin-bottom: 20px;
        font-size: 24px;
    }

    .google-signin {
        width: 100%;
        background-color: #fff;
        color: #000;
        border: 2px solid #000;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .schedule {
        width: 100%;
        background-color: #fff;
        color: #000;
        border: 2px solid #000;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .schedule:hover {
        border: 2px solid #198754;
    }

    input {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 2px solid #000;
        border-radius: 6px;
    }

    button[type="submit"] {
        background-color: black;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        margin: 30px 0;
    }

    .div-or {
        margin: 15px 0;
        font-size: 80%;
        color: blue;
    }

    .google-signin:hover {
        border: 2px solid #0d6efd;
    }
</style>


<div class="container">

    <img src="<?= base_url("assets/images/dnc_logo.png") ?>" alt="Logo" class="logo">
    <h1>HỆ THỐNG QUẢN LÝ CÔNG VIỆC</h1>
    <a class="google-signin" href="<?php echo $login_url; ?>">
        <img width="20" src="<?= base_url("assets/images/logo-google.svg") ?>" alt="Google Logo">
        <span style="font-weight: bold;">Đăng nhập với Google</span>
    </a>
    <?php $isCheck = $this->Config_model->get_by_key('login_with_account')->value; ?>
    <div class="div-or">
        <p <?= $isCheck ? '' : 'hidden' ?>>or</p>
    </div>
    <?php if ($isCheck) : ?>
        <form action="<?= base_url('auth/login') ?>" method="post" autocomplete="off">
            <input type="text" placeholder="Tên đăng nhập" name="username" required>
            <input type="password" placeholder="Mật khẩu" name="password" required>
            <button type="submit">Đăng nhập</button>
        </form>
    <?php endif; ?>
    <a class="schedule" href="<?= base_url('schedule') ?>">
        <i class="fa fa-search" aria-hidden="true"></i>
        <span style="font-weight: bold;">Tra cứu Thời khóa biểu</span>
    </a>
    <div class="div-or">
        <i>*<strong>Lưu ý:</strong> Dùng địa chỉ email tên miền thuộc trường để đăng nhập</i>
    </div>
</div>

<!-- Zalo Button -->
<div class="zalo">
    <a href="https://zalo.me/g/aheekk676" class="zalo-button zalo-button-fixed" target="_blank">
        <img src="<?= base_url() ?>/assets/images/LogoZalo.svg" alt="Zalo Icon" width="20" height="20">
    </a>
</div>

<script>
    $(document).ready(function() {
        let isShowPass = false;

        $("body").on("click", ".form-control-icon-show-password", function() {

            if (isShowPass) {
                $(this).children().attr("class", "fa fa-eye");
                $(".form-control-input[name='password']").attr("type", "password");
                isShowPass = false;
            } else {
                $(this).children().attr("class", "fa fa-eye-slash");
                $(".form-control-input[name='password']").attr("type", "text");
                isShowPass = true;
            }

        })
    })
</script>