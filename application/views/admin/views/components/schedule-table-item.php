<?php
$tkb = [];

$tiet_data = [
    [
        'tiet' => 1,
        'thoigian' => '7:00 - 7:45'
    ],
    [
        'tiet' => 2,
        'thoigian' => '7:45 - 8:30'
    ],
    [
        'tiet' => 3,
        'thoigian' => '8:30 - 9:15'
    ],
    [
        'tiet' => 4,
        'thoigian' => '9:30 - 10:15'
    ],
    [
        'tiet' => 5,
        'thoigian' => '10:15 - 11:00'
    ],
    [
        'tiet' => 6,
        'thoigian' => '11:00 - 11:45'
    ],
    [
        'tiet' => 7,
        'thoigian' => '13:00 - 13:45'
    ],
    [
        'tiet' => 8,
        'thoigian' => '13:45 - 14:30'
    ],
    [
        'tiet' => 9,
        'thoigian' => '14:30 - 15:15'
    ],
    [
        'tiet' => 10,
        'thoigian' => '15:30 - 16:15'
    ],
    [
        'tiet' => 11,
        'thoigian' => '16:15 - 17:00'
    ],
    [
        'tiet' => 12,
        'thoigian' => '17:00 - 17:45'
    ],
    [
        'tiet' => 13,
        'thoigian' => '17:45 - 18:30'
    ],
    [
        'tiet' => 14,
        'thoigian' => '18:30 - 19:15'
    ],
];


if (isset($data)) {
    foreach ($data as $key => $value) {
        $tiet = explode('→', trim($value['tiet']));

        for ($i = trim($tiet[0]); $i <= trim($tiet[1]); $i++) {

            if ($i == trim($tiet[0])) {
                $tkb[$value['thu']][trim($tiet[0])] = [
                    'tiettu' => trim($tiet[0]),
                    'tietden' => trim($tiet[1]),
                    'mon' => $value['tenhocphan'],
                    'phonghoc' => $value['phonghoc'],
                    'lop' => $value['lop'],
                    'ngay' => implode("/", array_reverse(explode("-", $value['ngay']))),
                ];
            } else {
                $tkb[$value['thu']][$i] = [];
            }
        }
    }
}
?>

<?php foreach ($tiet_data as $tiet_item) : ?>
    <tr>
        <td class="tbody-tiet-number" style="background: <?= $tiet_item['tiet'] > 6 ? "#26355D" : "#2e95aba6" ?>;"><?= $tiet_item['tiet'] ?></td>
        <td class="tbody-thoigian" style="font-size: 13px;"><?= $tiet_data[$tiet_item['tiet'] - 1]['thoigian'] ?></td>

        <?php for ($i = 2; $i <= 7; $i++) :
            if (isset($tkb[$i][$tiet_item['tiet']])) :

                $row = $tkb[$i][$tiet_item['tiet']]['tietden'] - $tkb[$i][$tiet_item['tiet']]['tiettu'] + 1;
                if (count($tkb[$i][$tiet_item['tiet']]) > 0) : ?>
                    <td class="tbody-item" style="<?= $tkb[$i][$tiet_item['tiet']]['ngay'] == date("d/m/Y") ? "border-bottom: 3px solid green;" : "" ?>" rowspan="<?= $row ?>">
                        <div>
                            <strong>Môn:</strong> <span><?= $tkb[$i][$tiet_item['tiet']]['mon']; ?></span> <br>
                            <strong>Phòng:</strong> <span><?= $tkb[$i][$tiet_item['tiet']]['phonghoc']; ?></span> <br>
                            <strong>Lớp:</strong> <span><?= $tkb[$i][$tiet_item['tiet']]['lop']; ?></span> <br>
                            <strong>Ngày:</strong> <span><?= $tkb[$i][$tiet_item['tiet']]['ngay'] ?></span>
                        </div>
                    </td>
                <?php endif;
            else : ?>
                <td></td>
            <?php endif; ?>

        <?php endfor; ?>

    </tr>
<?php endforeach; ?>