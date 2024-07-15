<div class="config-item d-flex p-3" style="width: 700px">
    <div>
        <span class="h5"><?= htmlspecialchars($name) ?></span>
    </div>

    <?php
    $data['id'] = $id;
    $data['key'] = $key;
    $data['value'] = $value;
    $this->load->view("admin/views/components/setting/" . $type, $data);
    ?>
</div>