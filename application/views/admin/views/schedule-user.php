<?php

$user_id = $this->session->userdata('user_id');

$user = $this->User_model->get_user_by_id($user_id);

?>

<div class="row px-4 pt-3">
    <div class="col-xl-4 col-lg-6 col-md-10 col-12 m-auto">
        <h3 class="text-uppercase text-center mb-3">lịch giảng dạy</h3>
    </div>
</div>
<div class="row px-4">
    <div class="col-6 col-md-4">
        <div class="form-group">
            <label class="form-label fw-bold">Chọn tuần</label>
            <select class="group-select-tuan form-select mt-2" aria-label="Default select example">
                <option selected disabled> --- Chọn tuần --- </option>
            </select>
        </div>
    </div>

    <div class="col-6 col-md-8 d-flex align-items-end">
        <span>
            Họ tên giảng viên: <strong><?= $user->firstname . " " . $user->lastname; ?></strong>
        </span>
        <?php if (isset($user->magv)) : ?>
            - Mã GV: <strong><?= $user->magv ?></strong>
        <?php endif; ?>
    </div>

    <div class="col-12 mt-3">
        <div style="overflow: auto;">
            <table class="table table-bordered" style="width: 100%;">
                <thead class="text-center align-middle">
                    <tr>
                        <th class="thead-tiet" rowspan="2">Tiết</th>
                        <th class="thead-tiet" rowspan="2">Thời gian</th>
                        <th class="thead-thu" colspan="6">Thứ</th>
                    </tr>
                    <tr>
                        <th class="thead-thu-number">2</th>
                        <th class="thead-thu-number">3</th>
                        <th class="thead-thu-number">4</th>
                        <th class="thead-thu-number">5</th>
                        <th class="thead-thu-number">6</th>
                        <th class="thead-thu-number">7</th>
                    </tr>
                </thead>
                <tbody class="table-data-show">
                    <tr>
                        <td class="text-center text-primary" colspan="9">
                            <span>Không tìm thấy dữ liệu!</span>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function getNext7Days(startDate) {
        const dateArray = [];
        const start = new Date(startDate);

        for (let i = 0; i < 6; i++) {
            const nextDate = new Date(start);
            nextDate.setDate(start.getDate() + i);
            dateArray.push(nextDate.toISOString().split('T')[0]);
        }

        const thead_thu_number = $(".thead-thu-number");

        thead_thu_number.each(function(index, _) {

            const date_value = dateArray[index].split("-").reverse().join("/");

            const date_html = `
                        <span>THỨ ${index+=2}</span> <br/>
                        <span>${date_value}</span>
                    `;

            $(this).html(date_html)

        })
    }

    let loading_html = `
                <tr>
                    <td class="text-center text-primary" colspan="7">
                        <img width="30" src="https://i.gifer.com/ZKZg.gif" alt="">
                    </td>
                </tr>
        `;

    let magv = "<?= $user->magv ?>";

    if (magv != "") {
        $(".table-data-show").html(loading_html);

        $.ajax({
            "url": "<?= base_url("schedule/search_schedule") ?>",
            method: "post",
            data: {
                search_key: magv
            },
            dataType: "json",
            success: function(response) {

                const {
                    date_select
                } = response;

                if (response.success) {

                    if (response.html) {
                        $(".table-data-show").html(response.html);
                    }

                    if (response.group_select) {
                        response.group_select.map(item => {
                            const html = `
                                            <option selected>${item.title}</option>
                                        `;

                            $(".group-select-tuan").append(html);

                        })
                    }
                    const start_date = date_select[0].split("/").reverse().join("-");

                    getNext7Days(start_date);


                } else {

                    loading_html = `
                    <tr>
                        <td class="text-center text-primary" colspan="7">
                            <span>Không tìm thấy dữ liệu!</span>
                        </td>
                    </tr>
                `;

                    $(".table-data-show").html(loading_html);
                }

            }
        })

    }

    $("body").on('change', '.group-select-tuan', function() {

        const tuan = $(this).val();

        $(".table-data-show").html(loading_html);

        const payload = {
            search_key: magv
        };

        if (tuan != '') {
            payload.tuan = tuan;
        }

        $.ajax({
            "url": "<?= base_url("schedule/search_schedule") ?>",
            method: "post",
            data: {
                search_key: magv,
                tuan,
            },
            dataType: "json",
            success: function(response) {

                const {
                    date_select
                } = response;

                if (response.success) {

                    if (response.html) {
                        $(".table-data-show").html(response.html);
                    }

                    const start_date = date_select[0].split("/").reverse().join("-");

                    getNext7Days(start_date);

                } else {
                    $(".table-data-show").html('<span>Không tìm thấy dữ liệu!</span>');
                }

            }
        })

    });
</script>