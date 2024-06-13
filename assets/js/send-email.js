jQuery(document).ready(function ($) {
  $("#send-emails").on("click", function (event) {
    event.preventDefault();

    var selectedData = [];
    var postID = $('input[name="post_id"]').val();
    $('input[name="emails"]:checked').each(function () {
      var emailValue = $(this).val();
      var templateValue = $(this).data("template");
      var nameValue = $(this).data("name");

      if (emailValue && nameValue) {
        selectedData.push({
          name: nameValue,
          emails: emailValue.split(", "), // Rozbijanie stringu na tablicę emaili
          template: templateValue,
        });
      }
    });

    $.ajax({
      url: ajax_object.ajax_url,
      type: "POST",
      dataType: "json",
      data: {
        action: "send_emails",
        emails: selectedData,
        post_id: postID,
      },
      success: function (response) {
        if (response.success) {
          alert("Wiadomości zostały wysłane");
          $("#total_sent").text(response.data.sent_count);
        } else {
          alert("Wystąpił błąd: " + response.data);
        }
      },
      error: function (xhr, status, error) {
        alert("Wystąpił błąd: " + error);
      },
    });
  });

  var customEmailsInput = $("#custom_emails");
  var customEmailsCheckbox = $("#custom_emails_checkbox");

  customEmailsInput.on("input", function () {
    var customEmailsValue = customEmailsInput.val();
    customEmailsCheckbox.val(customEmailsValue);
    customEmailsCheckbox.attr("value", customEmailsValue);
  });
});
