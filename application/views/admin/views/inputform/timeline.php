<?php

$key = isset($key) ? $key : time();

$title = isset($title) ? $title : "";
$required = isset($required) ? $required : false;

?>
<div class="input-group" <?= $required ? "required" : "" ?> data-title="<?= $title ?>" data-key="<?= $key ?>">
    <input name="<?= $key ?>" type="text" class="timeline-form form-control">
</div>
<script>
    $(".timeline-form").daterangepicker({
            opens: "center",
            locale: {
                format: "DD/MM/YYYY",
                applyLabel: "Áp dụng",
                cancelLabel: "Hủy",
                fromLabel: "Từ",
                toLabel: "Đến",
                daysOfWeek: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
                monthNames: [
                    "Tháng 1",
                    "Tháng 2",
                    "Tháng 3",
                    "Tháng 4",
                    "Tháng 5",
                    "Tháng 6",
                    "Tháng 7",
                    "Tháng 8",
                    "Tháng 9",
                    "Tháng 10",
                    "Tháng 11",
                    "Tháng 12",
                ],
                firstDay: 1,
            },
            startDate: Date.now(),
            endDate: Date.now()
        },
        function(start, end) {
            const value =
                start.format("DD/MM/YYYY") + " - " + end.format("DD/MM/YYYY");

            $(this)[0].element.text(value);
            console.log(value);
        }
    )

</script>