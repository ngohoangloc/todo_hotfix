<?php foreach ($members as $member) : ?>
    <div class="member">
        <img src="<?= base_url() . $member->avatar ?>" alt="Avatar" width="40" height="40" class="rounded-circle" style="object-fit: cover;">
        <div>
            <div class="name"><?= $member->firstname . " " . $member->lastname ?></div>
            <div class="position"><?= $member->department_name ?></div>
        </div>
    </div>
<?php endforeach; ?>