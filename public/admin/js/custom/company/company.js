"use strict";

var pageTitle 	= $("#pageTitle").val();
var pageRoute 	= $("#pageRoute").val();
var table;
var companyId;
var post_id;
var formMethod;
var validator;


// Form Validation with Ajax Submit

if ($("#" + pageTitle + "Form").length > 0) {
  validator = $("#" + pageTitle + "Form").validate({ 
    rules: {
      name: {
        required: true,
      },
      contact_name: {
        // required: true,
      },
      email: {
        required: true,
        emailFormat: true,
      },
      contact:{
        minlength:3,
        maxlength:15,
        digits:true
      },
    },
    messages: { 
      name: {
        required: "Please enter Company name",
      },
      contact_name: {
        required: "Please enter Contact Name",
      },
      email: {
        required: "Please enter E-mail",
        emailFormat: "Please enter a valid E-mail",
      },
      contact: {
        digits: "Please enter a valid contact number",
        maxlength: "Length cannot be more than 15 characters",
        minlength: "Length must be minimum 3 characters",
      },
    },
    submitHandler: function (form) {
      disableBtn("formSubmitBtn");
      companyId     = $("#" + pageTitle + "Form input[name=companyId]").val();
      post_id       = "" == companyId ? "" : "/" + companyId;
      formMethod    = "" == companyId ? "POST" : "PUT";
      var forms     = $("#" + pageTitle + "Form");

      $.ajax({ url: pageRoute + post_id, type: formMethod, processData: false, data: forms.serialize(), dataType: "html",
      }).done(function (a) {
        enableBtn("formSubmitButton");
        var data = JSON.parse(a);
        if(data.flagError == false){
            showSuccessToaster(data.message);      
            setTimeout(function () {
              window.location.href = pageRoute;  
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

jQuery.validator.addMethod("emailFormat", function (value, element) {
  return this.optional(element) || /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm.test(value);
}, "Please enter a valid email address"); 

function resetForm() {
	validator.resetForm();
	$('#' + pageTitle + 'Form').find("input[type=text]").val("");
	$("#data_id").val('');
}