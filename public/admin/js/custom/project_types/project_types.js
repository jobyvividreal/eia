"use strict";

var pageTitle = $("#pageTitle").val();
var pageRoute = $("#pageRoute").val();
var table;
var projectTypeId;
var post_id;
var formMethod;
var validator;


if ($("#" + pageTitle + "Form").length > 0) {
  validator = $("#" + pageTitle + "Form").validate({
    rules: {
      name: {
        required: true,
        maxlength: 200,
      },
    },
    messages: {
      name: {
        required: "Please enter Project type",
        maxlength: "Length cannot be more than 200 characters",
      },
    },
    submitHandler: function (form) {
      disableBtn("projectTypeSubmitBtn");
      projectTypeId = $("#" + pageTitle + "Form input[name=projectTypeId]").val();
      post_id = "" == projectTypeId ? "" : "/" + projectTypeId;
      formMethod = "" == projectTypeId ? "POST" : "PUT";
      var forms = $("#" + pageTitle + "Form");

      $.ajax({
        url: pageRoute + post_id, type: formMethod, processData: false, data: forms.serialize(), dataType: "html",
      }).done(function (a) {
        var data = JSON.parse(a);
        if (data.flagError == false) {
          enableBtn("projectTypeSubmitBtn");
          showSuccessToaster(data.message);
          $("#data-create-modal").modal("close");
          setTimeout(function () {
            // table.DataTable().draw();
            window.location.href = pageRoute;
          }, 1000);
        } else {
          showErrorToaster(data.message);
          printErrorMsg(data.error);
        }
      });
    },
    errorElement: 'div',
  })
}

function resetForm() {
  validator.resetForm();
  $('#' + pageTitle + 'Form').find("input[type=text]").val("");
  $("#data_id").val('');
}

// table.on('click', '.disable-item', function() {

//   var postUrl = $(this).attr('data-url'); 
//   var id      = $(this).attr('data-id');

//   swal({ title: "Are you sure?",icon: 'warning', dangerMode: true, buttons: { cancel: 'No, Please!', delete: 'Yes, Disable' }
//   }).then(function (willDelete) {
//     if (willDelete) {
//       $.ajax({url: postUrl + "/" + id, type: "DELETE", dataType: "html"})
//         .done(function (a) {
//             var data = JSON.parse(a);
//             if (data.flagError == false) {
//               showSuccessToaster(data.message);          
//               setTimeout(function () {
//                 table.ajax.reload();
//               }, 2000);

//           } else {
//             showErrorToaster(data.message);
//             printErrorMsg(data.error);
//           }   
//       }).fail(function () {
//         showErrorToaster("Something went wrong!");
//       });
//     } 
//   });

// });

// table.on('click', '.manage-status', function() {
//   var postUrl = $(this).attr('data-url');
//   $.ajax({url: postUrl, data:{'data_id':this.id }, type: 'POST', dataType: "html"})
//   .done(function (a) {
//     var data = JSON.parse(a);
//     if (data.flagError == false) {
//       showSuccessToaster(data.message);          
//       setTimeout(function () {
//         table.DataTable().draw();
//       }, 1000);
//     } else {
//       showErrorToaster(data.message);
//       printErrorMsg(data.error);
//     }   
//   }).fail(function () {
//     showErrorToaster("Something went wrong!");
//   });
// });



