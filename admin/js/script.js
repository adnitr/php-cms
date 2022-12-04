$(document).ready(function () {
  function loadUsersOnline() {
    $.get("../includes/functions.php?onlineusers=result", function (data) {
      $(".usersonline").text(data);
    });
  }

  setInterval(function () {
    loadUsersOnline();
  }, 500);
  $("#summernote").summernote({
    height: 200,
  });
  const checkbox = document.getElementById("selectAllBoxes");
  const otherCheckBoxes = document.getElementsByClassName("checkBoxes");
  const arr = [...otherCheckBoxes];

  checkbox.addEventListener("change", function () {
    if (this.checked) {
      arr.forEach((element) => {
        element.checked = true;
      });
    } else {
      arr.forEach((element) => {
        element.checked = false;
      });
    }
  });

  var divbox = "<div id='load-screen'><div id='loading'></div></div>";
  $("body").prepend(divbox);
  $("#load-screen")
    .delay(700)
    .fadeOut(600, function () {
      $(this).remove();
    });
});
