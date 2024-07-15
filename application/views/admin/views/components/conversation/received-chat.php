<?php if($flag) : ?>
    <div class="d-flex mb-3 chat-item">
        <img src="<?= base_url() . $user->avatar ?>" alt="User 1" class="rounded-circle me-2" width="40" height="40">
        <div class="bg-light p-3 rounded chat-bubble">
            <div class="fw-bold text-muted"><?= $user->firstname . ' ' . $user->lastname ?></div>
            <?= $chat->contents ?>
            <div class="text-muted small mt-1"><?= date('H:i d-m-Y', strtotime($chat->created_at)) ?></div>
        </div>
    </div>
<?php else : ?>
    <div class="d-flex mb-3">
        <div class="bg-light p-2 rounded chat-bubble" style="margin-left: 48px;">
            <?= $chat->contents ?>
            <div class="text-muted small mt-1"><?= date('H:i d-m-Y', strtotime($chat->created_at)) ?></div>
        </div>
    </div>
<?php endif; ?>