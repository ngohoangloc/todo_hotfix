// const URL = window.location.href;
$(document).ready(function () {
  var loadingTimeout;

  $(document).ajaxStart(function () {
    loadingTimeout = setTimeout(function () {
      $("#loading").show();
    }, 500);
  });

  $(document).ajaxStop(function () {
    clearTimeout(loadingTimeout);
    $("#loading").hide();
  });
});

const URL = "https://todo.nctu.edu.vn";

// Socket.io Notification
document.addEventListener("DOMContentLoaded", function () {
  const userId = $(".show_notification_btn").data("user-id");

  const socket = io("https://nhloc.id.vn:3000");

  socket.on("connect", function () {
    console.log("Connected to socket server");
  });

  socket.on(`fetchNotifications_${userId}`, function (message, title) {
    if (message || title) {
      $(".show_notification_btn_dot").attr("style", "display: block");
    }
  });
});

// Handle project title change
$(".project-title").change(function () {
  const id = $(this).attr("data-project-id");
  const title = $(this).val().trim("");

  $.ajax({
    url: `${baseUrl}admin/items/update/${id}`,
    method: "post",
    dataType: "json",
    data: {
      title,
    },
    success: function (response) {
      if (response.success) {
        const project_title_sidebar = $(
          `.project-title-sidebar[data-project-id=${id}]`
        );

        project_title_sidebar.text(title);

        toastr.success("Cập nhật tiêu đề thành công!");
        // $(`.project_title[data-project-id='${id}']`).find("span").text(title);
      }
    },
  });
});

//Handle project scope
$(".project-scope").change(function () {
  const id = $(this).attr("data-project-id");
  var is_private = $(this).is(":checked") ? 1 : 0;
  const listItem = $(`li[data-project-id='${id}']`).closest(
    ".list-project-item"
  );

  $.ajax({
    url: `${baseUrl}admin/items/update/${id}`,
    method: "post",
    dataType: "json",
    data: {
      is_private,
    },
    success: function (response) {
      if (response.success) {
        toastr.success("Cập nhật phạm vi của bảng thành công!");
        if (is_private === 1) {
          listItem.attr("hidden", true);
        } else {
          listItem.attr("hidden", false);
        }
      }
    },
  });
});

// Handle clear file
$(document).on("click", ".btn-clear-file", function () {
  const file_id = $(this).attr("data-file-id");
  const group_id = $(this).attr("data-group-id");
  const project_id = $(this).attr("data-project-id");

  let meta_id = $(this).attr("data-meta-id");

  const payload = {
    meta_id: meta_id,
    file_id,
    project_id,
    group_id,
  };

  $.ajax({
    url: `${baseUrl}file/update_meta`,
    method: "post",
    data: payload,
    dataType: "json",
    success: function (response) {
      if (response.success) {
        $(`.file_meta_input[data-meta-id='${meta_id}']`).html(
          response?.file_html
        );

        $(`.file_list_item[data-file-id='${file_id}']`).remove();

        toastr.success("Tệp tin đã được gở bỏ!");
      }
    },
  });
});

// Handle preview image in modal
$("body").on("click", ".file-image", function () {
  const file_id = $(this).attr("data-file-id");

  const file_info = $(this).find(".file-info");

  const title = file_info.attr("data-file-title");
  const desc = file_info.attr("data-file-desc");
  let path = file_info.attr("data-file-path");
  const type = file_info.attr("data-file-type");
  const created_at = file_info.attr("data-file-upload-date");

  $(".file_info_description").attr("data-file-id", file_id);
  $(".file_info_name").attr("data-file-id", file_id);
  $(".file-name").text(title);
  $(".file_info_description").val(desc);
  $(".file_info_name").val(title);
  $(".file_info_upload_date").text(created_at);

  if (file_info.attr("data-check") != 1) {
    $(".image-preview").attr("src", file_info.attr("data-test"));
  } else if (
    "pdf|doc|docx|xls|xlsx|ppt|pptx|rar|zip".split("|").includes(type)
  ) {
    $(".file_info_type").text(type);
    $(".image-preview").attr("src", `${baseUrl}assets/images/${type}.svg`);
  } else {
    $(".file_info_type").text("image");
    $(".image-preview").attr("src", path);
  }

  // $(".image-preview").attr("src", `${baseUrl}assets/images/data-encryption.png`);

  $(".btn-download-file-modal").attr("data-path", path.replace("enc", type));
  $(".btn-download-file-modal").attr("data-file-title", title);
  $(".btn-download-file-modal").attr("data-file-type", type);
  $(".btn-download-file-modal").attr("data-file-path", path);

  $("#imageModal").modal("show");
});

$(".btn-download-file-modal").click(function (e) {
  const title = $(this).attr("data-file-title");
  const type = $(this).attr("data-file-type");
  const path = $(this).attr("data-file-path");
  const file_name = title.replace("." + type, "") + "." + type;

  console.log(file_name);

  const link = document.createElement("a");
  document.body.appendChild(link);
  link.setAttribute("download", file_name);
  link.href = path;
  link.click();
  link.remove();
  e.stopPropagation();
});

// Handle file desc change
$("body").on("change", ".file_info_description", function () {
  const desc = $(this).val();
  const file_id = $(this).attr("data-file-id");

  $.ajax({
    url: `${baseUrl}file/update/${file_id}`,
    method: "post",
    data: { desc },
    dataType: "json",
    success: function (response) {
      if (response.success) {
        $(`.file-desc[data-file-id='${file_id}']`).text(
          response?.data?.description
        );

        toastr.success("Cập nhật dữ liệu thành công!");
      }
    },
  });
});

// Handle file title change
$("body").on("change", ".file_info_name", function () {
  const title = $(this).val();
  const file_id = $(this).attr("data-file-id");

  $.ajax({
    url: `${baseUrl}file/update/${file_id}`,
    method: "post",
    data: { title },
    dataType: "json",
    success: function (response) {
      if (response.success) {
        $(`.file-title[data-file-id='${file_id}']`).text(response?.data?.title);
        toastr.success("Cập nhật dữ liệu thành công!");
      }
    },
  });
});

const tooltipTriggerList = document.querySelectorAll(
  '[data-bs-toggle="tooltip"]'
);
const tooltipList = [...tooltipTriggerList].map(
  (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
);

let table = new DataTable("#dataTable");

// Handle Update icon collapse
$("body").on("click", ".btn-collapse", function () {
  const isCollapsed = $(this).hasClass("collapsed");

  if (isCollapsed) {
    $(this).children().attr("class", "fa fa-chevron-right text-primary");
  } else {
    $(this).children().attr("class", "fa fa-chevron-down text-primary");
  }
});

//Load num notifications
$(document).ready(function () {
  load_num_notification();
});

var user_id;

// handle show notification button
$("body").on("click", ".show_notification_btn", function () {
  user_id = $(this).data("user-id");

  load_notifications(user_id);
});

// handle read a notification
$("body").on("click", ".notification-list", function () {
  const notification_id = $(this).data("id");

  $.ajax({
    url: baseUrl + "notification/readNotification",
    data: {
      id: notification_id,
    },
    method: "post",
    dataType: "json",
    success: function (response) {
      if (response.success) {
        load_notifications(user_id);
      }
    },
  });
});

//read all notification
$("body").on("click", ".notification-button", function () {
  let unread_notifications = [];
  $(".notification-ui_dd-content")
    .find(".notification-list--unread")
    .each(function () {
      unread_notifications.push($(this).data("id"));
    });

  $.ajax({
    url: baseUrl + "notification/readNotifications",
    data: {
      ids: unread_notifications,
    },
    method: "post",
    dataType: "json",
    success: function (res) {
      if (res.success) {
        load_notifications(user_id);
      }
    },
  });
});

function load_notifications(user_id) {
  $.ajax({
    url: baseUrl + "notification/fetchUnreadNotifications",
    data: {
      user_id: user_id,
    },
    method: "get",
    contentType: "html",
    success: function (response) {
      $(".notification-ui_dd-content").html(response);

      load_num_notification();
    },
  });
}

function load_num_notification() {
  $.ajax({
    url: baseUrl + "notification/countNotification",
    method: "get",
    dataType: "json",
    success: function (response) {
      if (response.data > 0) {
        $(".show_notification_btn_dot").attr("style", "display: block");
      } else {
        $(".show_notification_btn_dot").attr("style", "display: none");
      }
    },
  });
}

//Collapse sidebar
function setSidebarState(collapsed) {
  if (collapsed) {
    $("#side-bar")
      .addClass("sidebar_home")
      .removeClass("col-md-4 col-lg-3 col-xxl-2");
    $("#sidebar_left").addClass("col-12").removeClass("col-2");
    $("#sidebar_right").attr("hidden", true);
  } else {
    $("#side-bar")
      .addClass("col-md-4 col-lg-3 col-xxl-2")
      .removeClass("sidebar_home");
    $("#sidebar_left").addClass("col-2").removeClass("col-12");
    $("#sidebar_right").removeAttr("hidden");
  }
}

//Sidebar state when reload page
function initSidebarState() {
  const collapsed = localStorage.getItem("sidebar_collapsed") === "true";
  $("#sidebar_right").toggleClass("collapsed", collapsed);
  $("#main-content").toggleClass("expanded", collapsed);
  setSidebarState(collapsed);
}

$(document).ready(function () {
  const currentURL = window.location.href;

  const urlPatterns = [
    baseUrl + "folder/view/\\d+",
    baseUrl + "table/view/\\d+",
    baseUrl + "customtable/view/\\d+",
    baseUrl + "form/view/\\d+",
    baseUrl + "file/view/\\d+",
    baseUrl + "calendar/view/\\d+",
    baseUrl + "kanban/view/\\d+",
    baseUrl + "gantt/view/\\d+",
  ];

  const isMatchingURL = urlPatterns.some((pattern) =>
    new RegExp(pattern).test(currentURL)
  );

  if (isMatchingURL) {
    initSidebarState();

    $("#toggle-sidebar").on("click", function () {
      $("#sidebar_right").toggleClass("collapsed");
      $("#main-content").toggleClass("expanded");

      const collapsed = $("#sidebar_right").hasClass("collapsed");
      setSidebarState(collapsed);

      localStorage.setItem("sidebar_collapsed", collapsed);
    });
  }
});
