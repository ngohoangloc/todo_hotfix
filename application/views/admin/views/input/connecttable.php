<?php

$key       = isset($key) ? $key : "";
$value     = isset($value) ? $value : "";
$data_id   = isset($data_id) ? $data_id : "";
$meta_id   = isset($meta_id) ? $meta_id : "";
$width     = isset($width) ? $width : "";
$type_slug = "table";
$query     = $this->db->select("*")->from("fields")->where("key", $key)->get();
$fields    = $query->num_rows() > 0 ? $query->row_object() : [];
$tables   = $this->Items_model->get_where(0, ["type_id" => 31]);
$class   = isset($class) ? $class : "";

//echo $data_id;
//var_dump($tables);
//$tables    = $query2->num_rows() > 0 ? $query2->result_object() : [];
//var_dump($tables );
if ($fields->values == '') {
?>
    <div class="input-group input-group-table priority-input">
        <a id="<?= "priority" . $meta_id; ?>" tabindex="0" class="btn btn-<?= $class; ?> table-item connect-table-item" data-bs-toggle="popover" role="button" style="width:100%;height:35px;">
            <?= isset($options[$value][0]) ? $options[$value][0] : ""; ?>
        </a>

        <div hidden>
            <div id="priority-body-<?= $meta_id; ?>" class="priority-body">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <select class="form-control table-name">
                            <option value="">Chọn bảng</option>
                            <?php foreach ($tables as $tab) : ?>
                                <option value="<?= $tab->id; ?>"><?= $tab->title; ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                    <div class="col-md-6">
                        <select class="form-control table-key">
                            <option class="form-control"></option>
                        </select>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-block btn-luu"><i class="fa-solid fa-pen-to-square"></i> Lưu lại</button>
                </div>
            </div>
        </div>

        <select data-id="<?= $meta_id ?>" data-type="connettable" name="<?= $key ?>" hidden style="width: <?= $width ?>" class="donvi_select form-select form-select-sm bg-gradient">

        </select>
    </div>
    <?php
} else {
    $values_arr = explode("|", $fields->values);
    $options = [];
    if ($values_arr[1] == 'title') {
        $options = $this->Items_model->get_where($values_arr[0], []);

    ?>
        <div class="input-group input-group-table">
            <select data-id="<?= $meta_id ?>" data-type="connettable" name="<?= $key ?>" style="width: <?= $width ?>" class="donvi_select form-select form-select-sm bg-gradient">
                <?php foreach ($options as $op) {

                ?>
                    <option value="<?= $op->title; ?>" <?php if ($op->title == $value) echo "selected"; ?>><?= $op->title; ?></option>
                <?php } ?>
            </select>
        </div>
    <?php
    } else {
        $items_arr = $this->Items_model->get_child_items($values_arr[0]);
        $options = [];
        foreach ($items_arr as $ite) {
            $options[] = $this->Items_model->get_meta_by_field($ite->id, $values_arr[1]);
        }
    ?>
        <div class="input-group input-group-table">
            <select data-id="<?= $meta_id ?>" data-type="connettable" name="<?= $key ?>" style="width: <?= $width ?>" class="donvi_select form-select form-select-sm bg-gradient">
                <?php foreach ($options as $op) {
                    //echo var_dump($op);

                ?>
                    <option value="<?= $op->value; ?>" <?php if ($op->value == $value) echo "selected"; ?>><?= $op->value; ?></option>
                <?php } ?>
            </select>
        </div>
<?php
    }
}
?>
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
        $("#<?= "priority" . $meta_id; ?>").click(function() {
            // var popover = new bootstrap.Popover(exampleEl, options);
        });
        $("#priority-body-<?= $meta_id; ?> .table-name").change(function() {
            var items_id = $(this).val();
            var html = '';
            $.ajax({
                url: '<?php echo base_url(); ?>items/get_fields',
                data: {
                    item_id: items_id,
                },
                method: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    if (data.success) {
                        html += "<option value='title'>Tiêu đề</option>";
                        $.each(data.data, function(key, value) {
                            console.log(key);
                            html += "<option value='" + value.key + "'>" + value.title + "</option>";
                        });
                        console.log(html);
                        $('#priority-body-<?= $meta_id; ?> .table-key').html(html);

                    }
                }
            });
        });

        $("#priority-body-<?= $meta_id; ?> .btn-luu").click(function() {
            var table_id = $('#priority-body-<?= $meta_id; ?> .table-name').val();
            var key = $('#priority-body-<?= $meta_id; ?> .table-key').val();
            var field_id = $("#priority<?= $meta_id; ?>").parents(".task-meta").attr("data-field-id");
            var values = table_id + '|' + key;
            var html = '';
            $.ajax({
                url: '<?php echo base_url(); ?>fields/update/' + field_id,
                data: {
                    values: values,
                },
                method: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    //console.log("ok");
                    exampleEl.popover('hide');
                    addOptions(table_id, key);
                }
            });
            exampleEl.popover('hide');
        });

        function addOptions(parent_id, key) {
            var field_id = $("#priority<?= $meta_id; ?>").parents(".task-meta").attr("data-field-id");
            var els = $("body").find(".task-meta[data-field-id='" + field_id + "'] .donvi_select");
            // var els = $('[data-field-id="'+parent_id+'"]');
            $.ajax({
                url: '<?= base_url("items/get_meta") ?>',
                data: {
                    items_id: parent_id,
                    key: key
                },
                method: 'POST',
                dataType: 'JSON',
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        var html = '';


                        response.data.map(item => {

                            if (item != null) {
                                console.log(item.value);
                                var val = item.value;
                                html += "<option value='" + val + "'>" + val + "</option>";

                            }

                            /* if(item.key === 'title')
                             {
                                 html += "<option value='" + item.title + "'>" + item.title + "</option>";
                             }
                             else html += "<option value='" + val + "'>" + val + "</option>";
                             */
                        })

                        // $.each(data.data, function(key, value1) {
                        //     console.log(value1.value);
                        //     if(key === 'title')
                        //     {
                        //         html += "<option value='" + value1.title + "'>" + value1.title + "</option>";
                        //     }
                        //     else html += "<option value='" + value1.value + "'>" + value1.value + "</option>";

                        // });
                        els.each(function(index) {
                            $(this).append(html).removeAttr("hidden");
                            $(this).prev().prev().attr("hidden", "true");
                            $(this).select2();
                        });
                    }
                }
            });

        }

    });
</script>