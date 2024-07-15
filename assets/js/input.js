$(document).ready(function () {
  $("body").on("click", ".hover", function () {
    $(this).css("z-index", "-1");

    $(this).prev().focus();

    $(this).prev().prev().css("z-index", "2");
  });

  $("body").on("click", ".btn-clear", function () {
    $(this).css("z-index", "-1");

    $(this).next().next().css("z-index", "2");

    $(this).next().val("");
  });

  $(".main-content").scroll(function () {
    if ($(this).scrollLeft()) {
      $(".task-title")
        .addClass("sc")
        .css("left", $(this).scrollLeft() + 10);

      $(".header-title")
        .addClass("sc")
        .css("left", $(this).scrollLeft() + 10);

      $(".checkall_btn")
        .addClass("sc")
        .css({
          left: $(this).scrollLeft() - 25,
          borderLeft: "1px solid #ddd",
          borderRight: "1px solid #ddd",
        });

      $(".stt")
        .addClass("sc")
        .css({
          left: $(this).scrollLeft() - 25,
          borderLeft: "1px solid #ddd",
          borderRight: "1px solid #ddd",
        });
    } else {
      $(".task-title").removeClass("sc");

      $(".header-title").removeClass("sc");

      $(".checkall_btn").removeClass("sc");

      $(".stt").removeClass("sc");
    }
  });
});
