<?php
function displayPermissions($permissions,$assigned_permissions,$parentId = 0, $level = 0)
{
  foreach ($permissions as $permission) {
    if ($permission->parent == $parentId) {
      $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level); 
      $isChecked = in_array($permission->id, array_column($assigned_permissions, 'id')) ? 'checked' : '';

      echo '<div class="form-check" style="margin-left: ' . $level * 20 . 'px;">'; 
      echo '<input type="checkbox" name="permissions[]" value="' . $permission->id . '" ' . $isChecked . ' class="parent-checkbox" data-parent-id="' . $parentId . '" data-id="' . $permission->id . '">';
      echo '<label for="' . $permission->id . '" style="margin-left: 10px;">' . $indent . $permission->permission_name . '</label>';
      echo '</div>';
      displayPermissions($permissions, $assigned_permissions, $permission->id, $level + 1);
    }
  }
}
?>

<div class="row">
  <div class="col-12">

    <h1>Cấp quyền cho Role: <strong><?= $role->role_name ?></strong></h1>

    <form action="<?= base_url('permission/savepermission') ?>" method="post">
      <input type="hidden" name="role_id" value="<?= $role->id ?>">

      <?php displayPermissions($permissions, $assigned_permissions, 0, 0); ?>

      <a class="btn btn-warning" href="<?php echo base_url('role'); ?>"> <i class="fa fa-angle-left"></i> Back</a>
      <button type="submit" class="btn btn-primary">Lưu</button>
    </form>
  </div>
</div>

<script>
$(document).ready(function() {
    $('.parent-checkbox').on('click', function() {
        let isChecked = $(this).prop('checked');
        let parentId = $(this).data('id'); 

        $('input[type="checkbox"][data-parent-id="' + parentId + '"]').each(function() {
            $(this).prop('checked', isChecked);
        });
    });

    $('.parent-checkbox').on('change', function() {
        let isChecked = $(this).prop('checked');
        let parentId = $(this).data('parent-id'); 

        let parentCheckbox = $('input[type="checkbox"][data-id="' + parentId + '"]');
        
        if (!isChecked && parentCheckbox.length) {
            let isAnyChildChecked = false;
            $('input[type="checkbox"][data-parent-id="' + parentId + '"]').each(function() {
                if ($(this).prop('checked')) {
                    isAnyChildChecked = true;
                    return false; 
                }
            });
            if (!isAnyChildChecked) {
                parentCheckbox.prop('checked', false);
            }
        } else if (parentCheckbox.length && parentCheckbox.is(':checked') !== isChecked) {
            parentCheckbox.prop('checked', isChecked);
        }
    });
});

</script>
