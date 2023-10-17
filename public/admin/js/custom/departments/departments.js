"use strict";

var pageTitle 	= $("#pageTitle").val();
var pageRoute 	= $("#pageRoute").val();
var table;
var dataId;
var projectTypeId;
var post_id;
var formMethod;
var validator;

var formValue;
var form;
var url;
var length;
formValue = [];
      

table     = $('#dataTableDepartments');
url       = table.data('url');
form      = table.data('form');
length    = table.data('length');


table.DataTable({
  processing: true,
  serverSide: true,
  searching: false, 
  bLengthChange: false,
  ajax: pageRoute + '/lists',
  columns: [
    { data: 'DT_RowIndex', orderable: false, searchable: false, width:'50px'},	
    { data: 'name'},	
    { data: 'status', orderable: false, searchable: false, width:'200px'},	
    { data: 'action', orderable: false, searchable: false, width:'200px'},	
  ],
});

table.on('click', '.manage-status', function() {
  var postUrl = $(this).attr('data-url');
  $.ajax({url: postUrl, data:{'post_id':this.id }, type: 'POST', dataType: "html"})
  .done(function (a) {
    var data = JSON.parse(a);
    if (data.flagError == false) {
      showSuccessToaster(data.message);          
      setTimeout(function () {
        table.DataTable().draw();
      }, 1000);
    } else {
      showErrorToaster(data.message);
      printErrorMsg(data.error);
    }   
  }).fail(function () {
    showErrorToaster("Something went wrong!");
  });
});


table.on('click', '.disable-item', function() {
  var postUrl = $(this).attr('data-url'); 
  var id      = $(this).attr('data-id');
  swal({ title: "Are you sure?",icon: 'warning', dangerMode: true, buttons: { cancel: 'No, Please!', delete: 'Yes, Delete' }
  }).then(function (willDelete) {
    if (willDelete) {
      $.ajax({url: postUrl + "/" + id, type: "DELETE", dataType: "html"})
        .done(function (a) {
            var data = JSON.parse(a);
            if (data.flagError == false) {
              showSuccessToaster(data.message);          
              setTimeout(function () {
                table.DataTable().draw();
              }, 2000);

          } else {
            showErrorToaster(data.message);
            printErrorMsg(data.error);
          }   
      }).fail(function () {
        showErrorToaster("Something went wrong!");
      });
    } 
  });

});


function manageCreateForm(data_id) {
	resetForm()
  
	if (data_id === null) {
		$("#data-create-modal").modal("open");
	} else {
		$.ajax({url: pageRoute + "/" + data_id + "/edit", type: "GET"})
		.done(function (data) {
		if(data.flagError == false) {
			$("#departmentId").val(data.data.id);
			$("#" + pageTitle + "Form input[name=name]").val(data.data.name);
			$("#description").val(data.data.description);
			$("#data-create-modal").modal("open");
		}
		}).fail(function () {
		printErrorMsg("Please try again...", "error");
		});
	}
}


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
        required: "Please enter Department",
        maxlength: "Length cannot be more than 200 characters",
      },
    },
    submitHandler: function (form) {
      disableBtn("formSubmitBtn");
      dataId              = $("#" + pageTitle + "Form input[name=departmentId]").val();
      post_id             = "" == dataId ? "" : "/" + dataId;
      formMethod          = "" == dataId ? "POST" : "PUT";
      var forms           = $("#" + pageTitle + "Form");

      $.ajax({ url: pageRoute + post_id, type: formMethod, processData: false, data: forms.serialize(), dataType: "html",
      }).done(function (a) {
        var data = JSON.parse(a);
        if(data.flagError == false){
            enableBtn("formSubmitBtn");
            showSuccessToaster(data.message);                
            $("#data-create-modal").modal("close");
            setTimeout(function () {
              table.DataTable().draw();
              // window.location.href = pageRoute;   
            }, 1000);
        } else {
          showErrorToaster(data.message);
          printErrorMsg(data.error);
        }
      });
    },
    errorElement : 'div',
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


  
