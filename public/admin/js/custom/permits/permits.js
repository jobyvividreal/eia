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
var permitID;
var eiaId;

$('#status').select2({ placeholder: "Please select Status", allowClear: true});

$('input[name="date_of_approval"]').daterangepicker({
  singleDatePicker: true,
  // showDropdowns: true,
  autoApply: true,
  startDate: new Date(),
  timePicker: true,
  locale: { format: 'DD-MM-YYYY h:mm A'},
  autoApply: true,
});

// Form Validation with Ajax Submit
if ($("#permitForm").length > 0) {
  validator = $("#permitForm").validate({ 
    rules: {
      permit_code: { 
        required: true, 
      },
      status: { 
        required: true, 
      },
      date_of_approval: { 
        required: true, 
      },
      certificate_number: { 
        required: true, 
      }
    },
    messages: { 
      permit_code: {
        required: "Please enter Permit ID",
      },
      status: {
        required: "Please select Status",
      },
      date_of_approval: {
        required: "Please select Date of approval",
      },
      certificate_number: {
        required: "Please enter Certificate Number",
      }
    },
    submitHandler: function (form) {
      disableBtn("formSubmitButton");
      permitID      = $("#permitForm input[name=permitID]").val();
      postId        = "" == permitID ? "" : "/" + permitID;
      var forms     = $("#permitForm");

      $.ajax({ url:pageRoute + postId, type: "PUT", processData: false, data: forms.serialize(), 
      }).done(function (data) {
        enableBtn("formSubmitButton");
        if (data.flagError == false) {
          showSuccessToaster(data.message);
          setTimeout(function () {         
            window.location.href = pageRoute;  
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

// DataTable Initialization
var columns;
var formValue;

var table     = $('#data-table-permit-documents');
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
