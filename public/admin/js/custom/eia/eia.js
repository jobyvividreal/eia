"use strict";

var pageTitle 	  = $("#pageTitle").val();
var pageRoute 	  = $("#pageRoute").val();
var projectRoute  = $("#projectRoute").val();
var table;
var id;
var postId;
var PageForm;
var formMethod;
var validator;
var projectId;
var eiaId;

$('#sortBy').select2({ placeholder: "Sort By", allowClear: true});
$('#documentType').select2({ placeholder: "Please select Document Type", allowClear: true});
$('#status').select2({ placeholder: "Please select Status", allowClear: true});
$('#stage').select2({ placeholder: "Please select Stage", allowClear: true});
$('#project_id').select2({ placeholder: "Please select a Project", allowClear: true});

$('input[name="dateOfEntry"]').daterangepicker({
  singleDatePicker: true,
  // showDropdowns: true,
  autoApply: true,
  startDate: new Date(),
  timePicker: true,
  locale: { format: 'DD-MM-YYYY h:mm A'},
  autoApply: true,
});

// Form Validation with Ajax Submit
if ($("#" + pageTitle + "Form").length > 0) {
  validator = $("#" + pageTitle + "Form").validate({ 
    rules: {
      codeId: { 
        required: true, 
      },
      dateOfEntry: { 
        required: true, 
      },
      address: { 
        required: true, 
      },
      status: { 
        required: true, 
      },
      stage: { 
        required: true, 
      },
      projectTeamLeader: { 
        required: true, 
      },
      costOfDevelop: { 
        required: true, 
        number:true,
      },
      latitude: { 
        required: true, 
        latLonFormat: true,
      },
      longitude: { 
        required: true, 
        latLonFormat: true,
      },
    },
    messages: { 
      address: {
        required: "Please enter Address",
      },
      codeId: {
        required: "Please enter EIA ID",
      },
      status: {
        required: "Please select Status",
      },
      dateOfEntry: {
        required: "Please enter Date Of Entry",
      },
      projectTeamLeader: {
        required: "Please enter Project Team Leader",
      },
      costOfDevelop: {
        required: "Please enter Cost Of Develop",
        number: "Accept only Numbers! Please enter a valid Coast",
      },
      latitude: {
        required: "Please enter Latitude",
        latLonFormat: "Please enter valid format",
      },
      longitude: {
        required: "Please enter Longitude",
        latLonFormat: "Please enter valid format",
      },
      stage: {
        required: "Please select Stage",
      },
    },
    submitHandler: function (form) {
      disableBtn("formSubmitButton");
      projectId     = $("#" + pageTitle + "Form input[name=projectId]").val();
      eiaId         = $("#" + pageTitle + "Form input[name=eiaId]").val();
      postId        = "" == eiaId ? "" : "/" + eiaId;
      formMethod    = "" == eiaId ? "POST" : "PUT";
      var forms     = $("#" + pageTitle + "Form");

      $.ajax({ url:pageRoute + postId, type: formMethod, processData: false, data: forms.serialize(), 
      }).done(function (data) {
        enableBtn("formSubmitButton");
        if (data.flagError == false) {
          showSuccessToaster(data.message);
          setTimeout(function () { 
            window.location.href = pageRoute + '/' + data.id ; ;              
          }, 2000);
        } else {
          showErrorToaster(data.message);
          printErrorMsg(data.error);
        }
      });
    },
    errorPlacement: function(error, element) {
      if (element.is("select")) {
        error.insertAfter(element.next('.select2'));
      } else {
        error.insertAfter(element);
      }
    },
    errorElement : 'div',
  })
}

jQuery.validator.addMethod("emailFormat", function (value, element) {
  return this.optional(element) || /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm.test(value);
}, "Please enter a valid email address");  

jQuery.validator.addMethod("mobileFormat", function (value, element) {
  return this.optional(element) || /^([0-9\s\-\+\(\)]*)$/igm.test(value);
}, "Please enter a valid mobile number");  

jQuery.validator.addMethod(
  "latLonFormat",
  function (value, element) {
      return (
          this.optional(element) ||
          /^-?([0-8]?[0-9]|90)(\.[0-9]{1,20})$/gim.test(value)
      );
  },
  "Please enter a valid format"
);

function resetForm() {
	validator.resetForm();
  $('#' + pageTitle + 'Form').find("input[type=text]").val("");
  $("#companyId").val('').trigger('change');
  $("#categoryId").val('').trigger('change');
  $("#projectTypeId").val('').trigger('change');
}

// DataTable Initialization
var columns;
var formValue;

var table     = $('#data-table-eia');
var url       = table.data('url');
var form      = table.data('form');
var length    = table.data('length');
columns   = [];
formValue = [];

table.find('thead th').each(function () {
  var column = {'data': $(this).data('column')};
  columns.push(column);
});

table.DataTable({
  processing: true,
  serverSide: true,
  searching: false,
  bLengthChange: false,
  pageLength: 10,
  ajax: {
    "type": "GET",
    "url": url,
    "data": function (data) {
      data.form = formValue;
    }
  },
  columns: columns,
});

$('#' + form + '-show-result-button').click(function () {
  formValue = $('#' + form + '-form').serializeArray();
  table.DataTable().draw();
});

$("#sortBy").change(function() {
  formValue = $('#' + form + '-form').serializeArray();
  table.DataTable().draw();
});

$('#' + form + '-filterFormClearButton').click(function () {
  $('#' + form + '-form').find("input[type=text]").val("");
  $('#' + form + '-form').find(".select2").val('').trigger("change");
  $('#' + form + '-form').trigger("reset");
  formValue = $('#' + form + '-form').serializeArray();
  table.DataTable().draw();
});

$("#project_id").change(function() {
  formValue = $('#' + form + '-form').serializeArray();
  table.DataTable().draw();
});

// Show active and Inactive Lists
$(".listBtn").on("click", function()  {
  $("#eia_status").val($(this).attr('data-type'));
  formValue = $('#' + form + '-form').serializeArray();
  table.DataTable().draw();
});

table.on('click', '.disable-item', function() {
  var postUrl = $(this).attr('data-url'); 
  var id      = $(this).attr('data-id');
  var title   = $(this).attr('data-title');
  swal({ title: "Are you sure?",icon: 'warning', dangerMode: true, buttons: { cancel: 'No, Please!', delete: 'Yes, '+ title }
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

table.on('click', '.restore-item', function() {
  var postUrl = $(this).attr('data-url'); 
  var id      = $(this).attr('data-id');
  var title   = $(this).attr('data-title');
    swal({ title: "Are you sure?",icon: 'warning', dangerMode: true, buttons: { cancel: 'No, Please!', delete: 'Yes, '+ title }
    }).then(function (willDelete) {
      if (willDelete) {
        $.ajax({url: postUrl + "/" + id, type: "POST", dataType: "html"})
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

// Display Project Full name;
function showFullName(fullName) {
  $("#projectFullName").text(fullName)
  $("#fullNameModel").modal("open");
}

if ($("#proceedProjectForm").length > 0) {
  validator = $("#proceedProjectForm").validate({
      rules: {
        proceed_project_id: {
          required: true,
        },
      },
      messages: {
        proceed_project_id: {
          required: "Please select a project",
        },
      },
      submitHandler: function (form) {
        var proceed_project_id = $("#proceed_project_id").val();
        window.location.href = `${projectRoute}/${proceed_project_id}/eias/create`;
      },
      errorPlacement: function (error, element) {
          if (element.is("select")) {
              error.insertAfter(element.next(".select2"));
          } else {
              error.insertAfter(element);
          }
      },
      errorElement: "div",
  });
}

function proceedToEIAForm() {
    $("#proceed_project_id").select2({
        placeholder: "Please select a Project",
        allowClear: true,
    });
    $("#eia-proceed-modal").modal("open");
}
