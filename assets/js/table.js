// Handle change timeline
$("body").on("mouseup", "input[name='daterange']", function () {
  $(this).daterangepicker(
    {
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
      autoUpdateInput: false,
    },
    function (start, end) {
      const oneDay = 24 * 60 * 60 * 1000;

      const meta_id = $(this)[0].element.attr("data-meta-id");

      const value =
        start.format("DD/MM/YYYY") + " - " + end.format("DD/MM/YYYY");

      var currentDate = new Date();

      var month = currentDate.getMonth() + 1;
      var day = currentDate.getDate();
      var year = currentDate.getFullYear();

      var monthStr = month < 10 ? "0" + month : month;
      var dayStr = day < 10 ? "0" + day : day;

      var formattedDate = monthStr + "-" + dayStr + "-" + year;

      const diffDays = Math.round(
        Math.abs(
          (new Date(end.format("MM-DD-YYYY")) - new Date(formattedDate)) /
            oneDay
        )
      );

      $(this)[0]
        .element.parent()
        .attr(
          "class",
          `text-light rounded-2 w-100 h-100 d-flex align-items-center justify-content-center ${
            diffDays <= 3 ? "bg-danger" : "bg-success"
          }`
        );

      $(this)[0].element.val(value);

      const icon_el = $(this)[0].element.prev();

      if (icon_el.length > 0) {
        icon_el.html(
          `<i class="fa ${
            diffDays <= 3 ? "fa-exclamation-triangle" : "fa fa-clock-o"
          }" aria-hidden="true"></i>`
        );
      } else {
        const html = `<span style="position: absolute; left: 10px; transform: translateY(-50%); top: 50%;">
        <i class="fa ${
          diffDays <= 3 ? "fa-exclamation-triangle" : "fa fa-clock-o"
        }" aria-hidden="true"></i>
        </span>`;
        $(html).insertBefore($(this)[0].element);
      }

      $('[data-bs-toggle="tooltip"]').tooltip();

      $.ajax({
        url: `${baseUrl}admin/items/update_meta`,
        method: "post",
        dataType: "json",
        data: {
          meta_id,
          value,
        },
        success: function (res) {
          if (res.success) {
            toastr.success("Cập nhật dữ liệu thành công!");
          }
        },
      });
    }
  );
});

$("body").ready(function () {
  const sortables = $(".sortable");

  sortables.each(function () {
    const height = $(this).height();

    if (height >= 500) {
      $(this).css({ "max-height": "500px", "overflow-y": "auto" });
    }
  });
});

// Handle checkbox task
$("body").on("click", ".stt input", function () {
  const task_id = $(this).parent().attr("data-id");

  const parent = $(this).parents(".group-item");

  const stt_input_length = parent.find(".stt").length;

  const total_checked_input = $(".stt input:checked").length;

  const checked_length = parent.find(".stt input:checked").length;

  $(".batch-actions-number span").text(total_checked_input + " đã chọn");

  // Handle disable stt child
  const sub_item = $(this).parents(".sort-item").find(".subitem");

  const sub_task_item = sub_item.find(".task-item");
  const checkall_subitem = sub_item.find(".checkall_subitem input");

  if ($(this).is(":checked")) {
    if (sub_item.length > 0) {
      checkall_subitem.attr("disabled", true);

      sub_task_item.each(function () {
        $(this).find(".stt_subitem input").attr("disabled", true);
      });
    }
  } else {
    if (sub_item.length > 0) {
      checkall_subitem.attr("disabled", false);

      sub_task_item.each(function () {
        $(this).find(".stt_subitem input").attr("disabled", false);
      });
    }
  }

  if (checked_length > 0) {
    $(".batch-actions").css("visibility", "visible");
  }

  if (total_checked_input == 0) {
    $(".batch-actions").css("visibility", "hidden");
  }

  if (stt_input_length == checked_length) {
    parent.find(".checkall_btn input").prop("checked", true);
  }
  if (checked_length == 0) {
    parent.find(".checkall_btn input").prop("checked", false);
  }

  if (checked_length < stt_input_length) {
    parent.find(".checkall_btn input").prop("checked", false);
  }
});
// Handle check stt subitem
$("body").on("click", ".stt_subitem input", function () {
  const task_id = $(this).parent().attr("data-id");

  const parent = $(this).parents(".subitem");

  const stt_input_length = parent.find(".stt_subitem").length;

  const total_checked_input = $(".stt_subitem input:checked").length;

  const checked_length = parent.find(".stt_subitem input:checked").length;

  $(".batch-actions-number span").text(total_checked_input + " đã chọn");

  if (checked_length > 0) {
    $(".batch-actions").css("visibility", "visible");
  }

  if (total_checked_input == 0) {
    $(".batch-actions").css("visibility", "hidden");
  }

  if (stt_input_length == checked_length) {
    parent.find(".checkall_subitem input").prop("checked", true);
  }
  if (checked_length == 0) {
    parent.find(".checkall_subitem input").prop("checked", false);
  }

  if (checked_length < stt_input_length) {
    parent.find(".checkall_subitem input").prop("checked", false);
  }
});

// Handle checkall task
$("body").on("click", ".checkall_btn input", function () {
  const parent = $(this).parents(".group-list-item");
  const input_children = parent.find(".stt input");

  if ($(this).is(":checked")) {
    input_children.each(function () {
      $(this).prop("checked", true);
    });

    $(".batch-actions").css("visibility", "visible");

    $(".batch-actions-number span").text(
      $(".stt input:checked").length + " đã chọn"
    );
  } else {
    input_children.each(function () {
      $(this).prop("checked", false);
    });

    if ($(".stt input:checked").length == 0) {
      $(".batch-actions").css("visibility", "hidden");
    }

    $(".batch-actions-number span").text(
      $(".stt input:checked").length + " đã chọn"
    );
  }
});

// Handle checkall sub task
$("body").on("click", ".checkall_subitem input", function () {
  const parent = $(this).parents(".subitem");
  const input_children = parent.find(".stt_subitem input");

  if ($(this).is(":checked")) {
    input_children.each(function () {
      $(this).prop("checked", true);
    });

    $(".batch-actions").css("visibility", "visible");

    $(".batch-actions-number span").text(
      $(".stt_subitem input:checked").length + " đã chọn"
    );
  } else {
    input_children.each(function () {
      $(this).prop("checked", false);
    });

    if ($(".stt_subitem input:checked").length == 0) {
      $(".batch-actions").css("visibility", "hidden");
    }

    $(".batch-actions-number span").text(
      $(".stt_subitem input:checked").length + " đã chọn"
    );
  }
});

// Handle close popover when click outside
$(document).ready(function () {
  $(".popover").click(function (e) {
    e.stopPropagation();
  });

  $(document).on("click", function (e) {
    // Check if the click was outside the popover
    if (!$(e.target).closest(".popover").length) {
      if ($(".popover").hasClass("show")) {
        $(".popover").removeClass("show").hide();
      }
    }
  });
});

//Giá trị của select trước khi thay đổi
var previousStatusValue;

$("body").on("focus", ".task-meta .input-group .status_select", function () {
  previousStatusValue = $(this).val();
});

//Giá trị của confirm select
var previousConfirmValue;
var confirm_select_el;

$("body").on("focus", ".task-meta .input-group .confirm_select", function () {
  confirm_select_el = $(this);
  previousConfirmValue = $(this).val();
});


$("body").on("click", ".user_list_item", function(){

  const meta_id = $(this).attr("data-meta-id");
  const project_id = $(this).attr("data-project-id");
  const group_id = $(this).attr("data-group-id");
  const value = $(this).attr("data-user-id");

  const task_meta = $(this).parents('.task-meta');

  const payload = {
      meta_id,
      group_id,
      project_id,
      value,
      type: "people_add"
  }


  $.ajax({
      url: baseUrl + "admin/items/update_meta",
      method: "post",
      data: payload,
      dataType: "json",
      success: function(response) {
          console.log(response.data);
          if (response.success) {

              $(`.task-meta[data-meta-id='${meta_id}']`).html(response.data);

              toastr.success("Thay đổi đã được cập nhật!");

              hide_popover();
          }
      }
  })

})

