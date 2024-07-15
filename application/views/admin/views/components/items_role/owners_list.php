<?php foreach ($owners as $owner) : ?>
    <div class="list-group-item d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <input type="checkbox" class="form-check-input me-2" name="owner_ids[]" value="<?= $owner->id ?>">
            <img src="<?= base_url() . $owner->avatar ?>" class="rounded-circle" alt="Avatar" width="40" height="40" style="object-fit: cover;">
            <div class="ms-3">
                <div class="name"><?= $owner->firstname . " " . $owner->lastname ?></div>
                <div class="username text-muted"><?= $owner->email ?></div>
            </div>
        </div>
    </div>
<?php endforeach; ?>