<div class="p-2">
    <a href="<?= base_url('auth/login') ?>" class="btn btn-secondary">
        <i class="fa fa-home" aria-hidden="true"></i>
    </a>
</div>

<div class="row px-4">
    <div class="col-xl-4 col-lg-6 col-md-10 col-12 m-auto">
        <h3 class="text-uppercase text-center mb-3">Tra cứu lịch giảng dạy</h3>
        <form action="">
            <div class="d-flex">
                <input type="text" name="magv" class="input-magv form-control w-75" placeholder="Nhập mã giáo viên">
                <button class="btn-tra-cuu ms-2 btn btn-primary">Tra cứu</button>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-center g-recaptcha" data-sitekey="6LcHQAolAAAAADFxujIM6eEya5GeHIeDJi7aCMMm"></div>
                <div id="response-message" class="text-danger text-center mt-2"></div>
            </div>
        </form>
    </div>
</div>
<div class="row px-4">
    <div class="col-3">
        <div class="form-group">
            <label class="form-label fw-bold">Chọn tuần</label>
            <select class="group-select-tuan form-select mt-2" aria-label="Default select example">
                <option selected disabled> --- Chọn tuần --- </option>
            </select>
        </div>
    </div>

    <div class="col-6 col-md-8 d-flex align-items-end hoten_giangvien" hidden>
        
    </div>

    <div class="col-12 mt-3 table-responsive">
        <table class="table table-bordered">
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
                        <!-- <span>Không tìm thấy dữ liệu!</span> -->
                        <img width="30" src="https://i.gifer.com/ZKZg.gif" alt="">
                    </td>
                </tr>
        `;

    $("body").on("click", ".btn-tra-cuu", function(e) {
        e.preventDefault();

        const input_magv = $("input[name='magv']").val();

        const recaptchaResponse = grecaptcha.getResponse();

        if (input_magv == '') {
            toastr.warning("Vui lòng nhập Mã GV");
            return;
        }

        $(".table-data-show").html(loading_html);

        $.ajax({
            url: "<?= base_url("schedule/verify_recaptcha") ?>",
            method: 'POST',
            dataType: "json",
            data: {
                'g-recaptcha-response': recaptchaResponse
            },
            success: function(response) {

                loading_html = `
                                    <tr>
                                        <td class="text-center text-primary" colspan="7">
                                            <span>Không tìm thấy dữ liệu!</span> 
                                        </td> 
                                    </tr>
                                `;

                if (!response.success) {
                    $('#response-message').html(response.message);
                    grecaptcha.reset();

                    $(".table-data-show").html(loading_html);

                } else {
                    $('#response-message').html("");
                    grecaptcha.reset();

                    $.ajax({
                        "url": "<?= base_url("schedule/search_schedule") ?>",
                        method: "post",
                        data: {
                            search_key: input_magv
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

                                    const group_select_html = [];

                                    response.group_select.map(item => {

                                        group_select_html.push(`
                                            <option selected>${item.title}</option>
                                        `)
                                    })

                                    group_select_html.unshift(`<option selected disabled> --- Chọn tuần --- </option>`)

                                    $(".group-select-tuan").html(group_select_html);
                                }

                                if (response.date_select) {

                                    const date_select_html = [];

                                    response.date_select.map(item => {

                                        date_select_html.push(`
                                        <option selected>${item}</option>
                                        `);



                                    })

                                    date_select_html.unshift(`<option selected disabled> --- Chọn ngày --- </option>`)
                                    $(".group-select-ngay").html(date_select_html);
                                }

                                const start_date = date_select[0].split("/").reverse().join("-");

                                getNext7Days(start_date);

                                $(".hoten_giangvien").html(`<span>
                                Họ tên giảng viên: <strong>${response.user_info['user_name']}</strong> - 
                                Mã GV: <strong>${response.user_info['magv']}</strong>
                                </span>`);
                                $(".hoten_giangvien").prop("hidden", false);

                            } else {
                                $(".table-data-show").html(loading_html);
                            }

                        }
                    })

                }
            }
        });

    })

    $("body").on('change', '.group-select-tuan', function() {

        const tuan = $(this).val();

        const input_magv = $("input[name='magv']").val();

        if (input_magv == '') {
            toastr.warning("Vui lòng nhập Mã GV");
            return;
        }

        $(".table-data-show").html(loading_html);

        const payload = {
            search_key: input_magv
        };

        if (tuan != '') {
            payload.tuan = tuan;
        }

        $.ajax({
            "url": "<?= base_url("schedule/search_schedule") ?>",
            method: "post",
            data: {
                search_key: input_magv,
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
                    $(".table-data-show").html(loading_html);
                }

            }
        })

    });
</script>