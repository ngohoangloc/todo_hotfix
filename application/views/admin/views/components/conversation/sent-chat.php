<div class="d-flex justify-content-end mb-3 chat-item">
    <div class="bg-primary p-3 rounded chat-bubble" style="color: #333 !important;">
        <?= $chat->contents ?>
        <div class="text-muted small mt-1"><?= date('H:i d-m-Y', strtotime($chat->created_at)) ?></div>
    </div>
</div>