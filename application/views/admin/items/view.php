<h2><?= $project_item->title; ?></h2>

<div class="group-list">

    <?php foreach ($groups as $group) : ?>

        <div class="group-item">

            <div class="group-item-title mb-1"><input type="text" value="<?= $group->title; ?>" class="group-item-title-input" /></div>
            <div class="task-item d-flex task-item-header">

                <div class="stt" data-id="<?= $group->id; ?>">

                    <input type="checkbox" value="" />

                </div>

                <div class="title" data-id="<?= $group->id; ?>">

                    <input type="text" value="Title" />

                </div>

                <div class="stt" data-id="<?= $group->id; ?>">

                    <input type="text" value="Status" />

                </div>
                <div class="stt" data-id="<?= $group->id; ?>">

                    <button type="button" class="btn btn-secondary btn-add-fields" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Vivamus
sagittis lacus vel augue laoreet rutrum faucibus.">
                        +
                    </button>

                </div>

            </div>
        </div>

    <?php endforeach; ?>

</div>
<div id="fiels_list" class="popper-content hide">
    <div id="fiels_list_content">
        <div class="fiels_list_item" data-type-html="text">
            <img src="<?= base_url('assets/images/text-column-icon.svg'); ?>" class="fiels_list_item_icon" /> text
        </div>
        <div class="fiels_list_item" data-type-html="number">
            <img src="<?= base_url('assets/images/numeric-column-icon.svg'); ?>" class="fiels_list_item_icon" /> number
        </div>
        <div class="fiels_list_item" data-type-html="phone">
            <img src="<?= base_url('assets/images/phone-column-icon-v2a.png'); ?>" class="fiels_list_item_icon" /> phone
        </div>
        <div class="fiels_list_item" data-type-html="email">
            <img src="<?= base_url('assets/images/email-column-icon-v2a.png'); ?>" class="fiels_list_item_icon" /> email
        </div>

    </div>
</div>
<style>
    .fiels_list_item
    {
        display: flex;
        gap:10px;
    }
    .fiels_list_item_icon
    {
        padding: 5px;
        width: 35px;
        height: 35px;
        background-color: #ddd;

    }
    #fiels_list.hide {
        display: none;
    }

    #fiels_list_content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .task-item-header input,
    .group-item input {
        border: none;
        box-shadow: none;
        background: none;
        width: fit-content;
        text-indent: 10px;
    }

    .task-item-header {
        border: 1px solid #ddd;
    }

    .task-item-header div {
        border-right: 1px solid #ddd;
        padding: 5px;
        align-items: center;
        vertical-align: middle;
        display: flex;
        justify-content: center;
    }
</style>
<script>
    // A $( document ).ready() block.
    $(document).ready(function() {
        $('.btn-add-fields').popover({
            placement: 'bottom',
            container: 'body',
            html: true,
            content: function() {
                return $("#fiels_list").html();
            }
        });
    });
</script>