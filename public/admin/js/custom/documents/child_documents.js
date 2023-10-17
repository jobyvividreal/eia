"use strict";

let pageTitle 	      = $("#pageTitle").val();
let pageRoute 	      = $("#pageRoute").val();
let eiaRoute          = $("#eiaRoute").val();
let documentRoute     = $("#documentRoute").val();
let documentId        = '';
let table;
let id;
let postId;
let PageForm;
let formMethod;
let validator;
let projectId;
let eiaId;
let isFileValidated   = (documentId == '') ? false : true;
let FileUploadRoute   = $("#FileUploadRoute").val();
let FileRemoveRoute   = $("#FileRemoveRoute").val();
let FileListRoute     = $("#FileListRoute").val();
let csrfToken         = $("#" + pageTitle + "Form ").find('input[name="_token"]').val();


$('#sortBy').select2({ placeholder: "Sort By", allowClear: true});
$('#documentType').select2({ placeholder: "Please select Document Type", allowClear: true});
$('#status').select2({ placeholder: "Please select Status", allowClear: true});
$('#stage').select2({ placeholder: "Please select Stage", allowClear: true});
$('#project_id').select2({ placeholder: "Please select a Project", allowClear: true});
$('#eia_id').select2({ placeholder: "Please select EIA", allowClear: true});

$('input[name="dateOfEntry"]').daterangepicker({
  singleDatePicker: true,
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
      documentNumber: { 
        required: true, 
      },
      dateOfEntry: { 
        required: true, 
      },
      title: { 
        required: true, 
      },
      status: { 
        required: true, 
      },
      stage: { 
        required: true, 
      },
      documentType: { 
        required: true, 
      },
      briefDescription: { 
        required: true, 
      },
    },
    messages: { 
      documentNumber: {
        required: "Please enter Document Number",
      },
      title: {
        required: "Please enter Title of Document",
      },
      status: {
        required: "Please select Status",
      },
      dateOfEntry: {
        required: "Please enter Date Of Entry",
      },
      documentType: {
        required: "Please select Document Type",
      },
      stage: {
        required: "Please select Stage",
      },
      briefDescription: {
        required: "Please enter Brief Description",
      },
      comment: {
        required: "Please enter Remarks / Comments",
      },
    },
    submitHandler: function (form) {
      if(isFileValidated) {
        disableBtn("formSubmitButton");
        $('#file-error').hide();
        // projectId      = $("#" + pageTitle + "Form input[name=projectId]").val();
        // eiaId          = $("#" + pageTitle + "Form input[name=eiaId]").val();
        // documentId     = $("#" + pageTitle + "Form input[name=documentId]").val();
        // postId         = "" == documentId ? "" : "/" + documentId;
        formMethod        = "" == documentId ? "POST" : "PUT";
        var forms         = $("#" + pageTitle + "Form");

        $.ajax({ url:pageRoute, type: formMethod, processData: false, data: forms.serialize(), 
        }).done(function (data) {
          enableBtn("formSubmitButton");
          if (data.flagError == false) {
            showSuccessToaster(data.message);
            setTimeout(function () { 
              window.location.href = documentRoute;                
            }, 2000);
          } else {
            showErrorToaster(data.message);
            printErrorMsg(data.error);
          }
        });
      } else {
        $('#file-error').text("Please Browse or Drag and Drop the File");
        $('#file-error').show();
      }
    },
    errorPlacement: function(error, element) {
      if (element.is("select")) {
        error.insertAfter(element.next('.select2'));
      }else {
        error.insertAfter(element);
      }
    },
    errorElement : 'div',
  })
}

function resetForm() {
	validator.resetForm();
    $('#' + pageTitle + 'Form').find("input[type=text]").val("");
    $("#companyId").val('').trigger('change');
    $("#categoryId").val('').trigger('change');
    $("#projectTypeId").val('').trigger('change');
}

// let uploadedDocumentMap = {};
// let addRemoveLink   = (documentId == '') ? true : false;

if (FileUploadRoute != undefined) {
  Dropzone.autoDiscover = false;
  var myDropzone = new Dropzone(".dropzone", {
    url: FileUploadRoute,
    acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.docx,.xls,.xlsx",
    dictDefaultMessage: "Browse or Drag and Drop the File Here.",
    addRemoveLinks: true,
    maxFilesize: 250, //MB
    maxFiles: 1, 
    // renameFile: function (file) {
    //   let random      = Math.random().toString(36).substring(2,10);
    //   let newName     = new Date().getTime() + random + '_' + file.name;
    //   return newName;
    // },
    init:function() {
      this.on("error", function (file, message) {
        alert(message);
          this.removeFile(file);
      });
      // Get images
      // documentId    = $("#documentId").val();
      // var myDropzone = this;
      // $.ajax({ url: FileListRoute, type: 'GET', dataType: 'json', data: {documentId:documentId},
      //   success: function(data){
      //     console.log(data);
      //     $.each(data, function (key, value) {
      //       var file = {name: value.name, size: value.size};
      //       myDropzone.options.addedfile.call(myDropzone, file);
      //       myDropzone.options.thumbnail.call(myDropzone, file, value.path);
      //       myDropzone.emit("complete", file);
      //     });
      //   }
      // });
    },
    headers: { 'X-CSRF-TOKEN': csrfToken },
    removedfile: function(file) {
      var name = file.upload.filename;
      $.ajax({type: "POST", url: FileRemoveRoute, data: { "_token": csrfToken, name: name}});
      var fileRef;
      isFileValidated = (myDropzone.files.length == 0 ) ? false : true ;
      $("#" + pageTitle + "Form").find('input[name="documentFiles[]"][value="' + name + '"]').remove()
      return (fileRef = file.previewElement) != null ?
      fileRef.parentNode.removeChild(file.previewElement) : void 0;
    },
    success: function (file, response) {
      var name;
      $("#documentFile").val(response.filename);
      isFileValidated = true;
      $("#" + pageTitle + "Form").append('<input class="document-hidden" type="hidden" name="documents[]" value="' + response.filename + '">')
      $("#" + pageTitle + "Form").append('<input class="document-hidden" type="hidden" name="documentOrg[]" value="' + response.name + '">')
    },
  });
}