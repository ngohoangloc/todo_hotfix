<?php
$key = isset($key) ? $key : "";
$value = isset($value) ? $value : "";
$data_id = isset($data_id) ? $data_id : "";
$meta_id = isset($meta_id) ? $meta_id : "";
$hover = isset($hover) ? $hover : false;
$width = isset($width) ? $width : "";
$placeholder = isset($placeholder) ? $placeholder : "";
$showBtnClear = isset($showBtnClear) ? $showBtnClear : false;
$disabled = isset($disabled) ? $disabled : null;
$showMenu = isset($showMenu) ? $showMenu : false;
?>
<style>
    .priority-body {
        padding: 0px;
    }

    .priority-body .btn {
        width: 100%;
        margin-bottom: 10px;

    }
</style>
<?php

$options = [
    'Critical' => ['Critical', 'secondary'],
    'High' => ['High', 'danger'],
    'Medium' => ['Medium', 'primary'],
    'Low' => ['Low', 'info'],
];

$class = $value != '' ? $options[$value][1] : 'info';

if (empty($value) || trim($value) == '') {
    $value = "Chọn độ ưu tiên";
}

?>
<div class="input-group input-group-table priority-input">
    <a id="<?= "priority" . $meta_id; ?>" tabindex="0" class="btn btn-<?= $class; ?> priority-item text-light" role="button" data-bs-toggle="popover" title="Độ ưu tiên" style="width:100%;height:35px;">
        <?= $value; ?>
    </a>

    <div hidden>
        <div id="priority-body-<?= $meta_id; ?>" class="priority-body">
            <?php foreach ($options as $key => $op) : ?>
                <button class="btn btn-<?= $op[1]; ?> priority-select-item" data-value="<?= $key; ?>" data-color="<?= $op[1]; ?>">
                    <?= $op[0]; ?>
                </button>
            <?php endforeach; ?>
            <button class="btn btn-info"><i class="fa-solid fa-pen-to-square"></i> Chỉnh sửa</button>
        </div>
    </div>

    <input type="text" placeholder="<?= $placeholder ?>" data-meta-id="<?= $meta_id ?>" data-id="<?= $data_id ?>" hidden name="<?= $key ?>" value="<?= $value; ?>" class="form-control input-table text-truncate" />
</div>

<script>
    $(document).ready(function() {
        var options = {
            html: true,
            title: "",
            //html element
            //content: $("#popover-content")
            content: $('#priority-body-<?= $meta_id; ?>')
            //Doing below won't work. Shows title only
            //content: $("#popover-content").html()

        };
        var exampleEl = $('#<?= "priority" . $meta_id; ?>');

        var popover = new bootstrap.Popover(exampleEl, options);
        $("body").on("click", "#priority-body-<?= $meta_id; ?> .priority-select-item", function() {
            var value = $(this).attr("data-value");
            var color = $(this).attr("data-color");
            //alert(value);
            // $(this).parents('.priority-input').find('.form-control').val(value).trigger('change');
            // $(this).parents('.priority-input').find('.priority-item').click();
            exampleEl.next().next().val(value).change();
            exampleEl.popover('hide');
            exampleEl.html(value);
            // exampleEl.removeClass('btn-<?= $class; ?>');
            exampleEl.removeClass(function(index, className) {
                return (className.match(/(^|\s)btn-\S+/g) || []).join(' ');
            });
            exampleEl.addClass('btn-' + color);
        });
        $('html').on('click', function(e) {
            if (typeof $(e.target).data('original-title') == 'undefined' &&
                !$(e.target).parents().is('.popover.in')) {
                $('[data-bs-toggle="popover"]').popover('hide');
            }
        });

        $('html').on('click', function(e) {
            if (typeof $(e.target).data('original-title') == 'undefined' &&
                !$(e.target).parents().is('.popover.in')) {
                $('[data-bs-toggle="popover"]').popover('hide');
            }
        });

    });
</script>