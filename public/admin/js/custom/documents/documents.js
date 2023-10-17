"use strict";

let pageTitle 	  = $("#pageTitle").val();
let pageRoute 	  = $("#pageRoute").val();
let eiaRoute      = $("#eiaRoute").val();
let documentId    = $("#documentId").val();
let permitRoute   = $("#permitRoute").val();
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
$('#status').select2({ placeholder: "Please select Status"});
$('#stage').select2({ placeholder: "Please select Stage"});
$('#project_id').select2({ placeholder: "Please select a Project", allowClear: true});
$('#eia_id').select2({ placeholder: "Please select EIA", allowClear: true});
$('#assigned_to').select2({ placeholder: "Please select User", dropdownParent: "#data-create-modal", allowClear: true});



$('input[name="dateOfEntry"]').daterangepicker({
  singleDatePicker: true,
  // showDropdowns: true,
  autoApply: true,
  startDate: new Date(),
  timePicker: true,
  locale: { format: 'DD-MM-YYYY h:mm A'},
  autoApply: true,
});

$( document ).ready(function() {
  listSubDocuments();
});

$(document).on('change', '#project_id', function () {
  var url = '';
  $.ajax({ type: 'POST', url: "common/get-eia-of-project", data:{'project_id':this.value }, dataType: 'json',
    success: function(data) {
      var selectTerms = '<option value="">Please select EIA</option>';
      $.each(data.data, function(key, value) {
        selectTerms += '<option value="' + value.id + '" >' + value.code_id + '</option>';
      });
      var select = $('#eia_id');
      select.empty().append(selectTerms);
    }
  });
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
      comment: { 
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
        projectId     = $("#" + pageTitle + "Form input[name=projectId]").val();
        eiaId         = $("#" + pageTitle + "Form input[name=eiaId]").val();
        documentId    = $("#" + pageTitle + "Form input[name=documentId]").val();
        
        postId        = "" == documentId ? "" : "/" + documentId;
        formMethod    = "" == documentId ? "POST" : "PUT";
        var forms     = $("#" + pageTitle + "Form");

        $.ajax({ url:pageRoute + postId, type: formMethod, processData: false, data: forms.serialize(), 
        }).done(function (data) {
          enableBtn("formSubmitButton");
          if (data.flagError == false) {
            showSuccessToaster(data.message);
            setTimeout(function () { 
              if(permitRoute != undefined) {
                window.location.href = permitRoute;   
              } else {
                window.location.href = eiaRoute;
              }
                 
              // location.reload();             
            }, 2000);
          } else {
            showErrorToaster(data.message);
            printErrorMsg(data.error);
          }
        });
      } else {
        $('#file-error').text("Please select a valid file");
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

jQuery.validator.addMethod("emailFormat", function (value, element) {
    return this.optional(element) || /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm.test(value);
}, "Please enter a valid email address");  

jQuery.validator.addMethod("mobileFormat", function (value, element) {
    return this.optional(element) || /^([0-9\s\-\+\(\)]*)$/igm.test(value);
}, "Please enter a valid mobile number");  

function resetForm() {
	validator.resetForm();
  $('#' + pageTitle + 'Form').find("input[type=text]").val("");
  $("#companyId").val('').trigger('change');
  $("#categoryId").val('').trigger('change');
  $("#projectTypeId").val('').trigger('change');
}

let uploadedDocumentMap = {};
let addRemoveLink   = (documentId == '') ? true : false;

if (FileUploadRoute != undefined) {
  Dropzone.autoDiscover = false;
  var myDropzone = new Dropzone(".dropzone", {
    url: FileUploadRoute,
    acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.docx,.xls,.xlsx",
    dictDefaultMessage: "Browse or Drag and Drop the File Here.",
    addRemoveLinks: addRemoveLink,
    maxFilesize: 250, //MB
    maxFiles: 1, 
    // renameFile: function (file) {
    //   let random      = Math.random().toString(36).substring(2,10);
    //   let newName     = new Date().getTime() + random + '_' + file.name;
    //   return newName;
    // },
    init:function() {
      // Get images
      documentId      = $("#documentId").val();
      var myDropzone  = this;
      $.ajax({ url: FileListRoute, type: 'GET', dataType: 'json', data: {documentId:documentId},
        success: function(data){
          console.log(data);
          $.each(data, function (key, value) {
            var file = {name: value.name, size: value.size};
            myDropzone.options.addedfile.call(myDropzone, file);
            myDropzone.options.thumbnail.call(myDropzone, file, value.path);
            myDropzone.emit("complete", file);
          });
        }
      });
      this.on("error", function (file, message) {
        alert(message);
          this.removeFile(file);
      });
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

// DataTable Initialization
var columns;
var formValue;
table         = $('#data-table-documents');
var url       = table.data('url');
var form      = table.data('form');
var length    = table.data('length');
columns       = [];
formValue     = [];

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
    ajax: { "type": "GET", "url": url, "data": function (data) {
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

$("#eia_id").change(function() {
  formValue = $('#' + form + '-form').serializeArray();
  table.DataTable().draw();
});

$('#' + form + '-filterFormClearButton').click(function () {
    $('#' + form + '-form').find("input[type=text]").val("");
    $('#' + form + '-form').find(".select2").val('').trigger("change");
    $('#' + form + '-form').trigger("reset");
    $("#archiveStatus").val(0);
    formValue = $('#' + form + '-form').serializeArray();
    table.DataTable().draw();
});

// Show active and Inactive Lists
$(".listBtn").on("click", function()  {
    $("#status").val($(this).attr('data-type'));
    formValue = $('#' + form + '-form').serializeArray();
    table.DataTable().draw();
});

$(".archiveBtn").on("click", function()  {
  $("#archiveStatus").val($(this).attr('data-type'));
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


table.on('click', '.archive-item', function() {
  var postUrl = $(this).attr('data-url'); 
  var id      = $(this).attr('data-id');
  var title   = $(this).attr('data-title');

  swal({ title: "Are you sure?", text: "Document will move to Archive folder!", icon: 'warning', dangerMode: true, buttons: { cancel: 'No, Please!', delete: 'Yes, '+ title }
  }).then(function (willDelete) {
    if (willDelete) {
      $.ajax({url: postUrl , type: "POST", dataType: "html"})
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

table.on('click', '.view-more-details', function() {
  var postUrl   = $(this).attr('data-url'); 
  var id        = $(this).attr('data-id');
  var column    = $(this).attr('data-column');

  $.ajax({url: postUrl, type: "GET"}).done(function (data) {
    if (data.flagError == false) {
      var details = '';
      if (column == 'comment') {
        details = data.document.comment;
      } else {
        details = data.document.brief_description;
      }
      $("#fullTextSection").text(details);
      $("#viewMoreDetailsModel").modal("open");
    } else {
      showErrorToaster(data.message);
      printErrorMsg(data.error);
    }   
  }).fail(function () {
    showErrorToaster("Something went wrong!");
  });
});

function listSubDocuments() {
  $.ajax({ url: 'sub-documents/list/', type: 'GET', dataType: 'json', data: {documentId:documentId},
    success: function(data) {
      $("#subDocumentsDiv").html(data.html);
      $(".subDocument-save-comment-btn").on("click", function () {
        var comment       = $(this).closest('.commentContainer').find('textarea').val();
        var document_Id   = $(this).attr('data-id');
        addComment(comment, document_Id);
      });
    }
  });
}

function addComment(comment, document_Id) {
  $(".documentComment-error").hide();
  if(comment == '') {
    $("#documentComment-error-"+document_Id).html("Please enter comment");
    $("#documentComment-error-"+document_Id).show();
  } else {
    $.ajax({ url: 'comments/store', type: 'POST', dataType: 'json', data: {document_id:document_Id, comment:comment},
    }).done(function (data) {
      if (data.flagError == false) {
        $("#latestComment"+document_Id).prepend(data.html);
        $(".commentField").val('');
      } else {
        showErrorToaster(data.message);
        printErrorMsg(data.error);
      }
    }); 
  }
}

$(".save-comment-btn").on("click", function () {
  var comment       = $(this).closest('.commentContainer').find('textarea').val();
  var document_Id   = $(this).attr('data-id');
  addComment(comment, document_Id);
});

$(".assign-task").on("click", function () {
  $('#description').val("");
  $("#assigned_to").val('').trigger('change');
  taskAssignValidator.resetForm();
  var assignedId = $("#assignedId").val();
  if(assignedId != '') {
    $.ajax({ url: "task-assign/" + assignedId + "/edit" , type: 'get',
      success: function(data) {
        if (data.flagError == false) { 
          $("#assigned_to").val(data.data.assigned_to).trigger('change');                                                                                  
          $("#description").val(data.data.details);  
          $("#assignedId").val(data.data.id);  
          $(".task-complete-checkbox").show();                                                                                     
        } else {                                                                                                            
          showErrorToaster(data.message);
        }                                                                                                                                                           
        // $(".subDocument-save-comment-btn").on("click", function () {                                                                                                    
        //   var comment       = $(this).closest('.commentContainer').find('textarea').val();                                                                             
        //   var document_Id   = $(this).attr('data-id');                                                                                                               
        //   addComment(comment, document_Id); 
        // });
      }
    });
  }
  $("#data-create-modal").modal("open");
});

// Form Validation with Ajax Submit
if ($("#assignTaskForm").length > 0) {
  var taskAssignValidator = $("#assignTaskForm").validate({ 
    rules: {
      assigned_to: { required: true },
      details: { required: true }
    },
    messages: { 
      assigned_to: { required: "Please select User" },
      details: { required: "Please enter task details" },
    },
    submitHandler: function (form) {
      disableBtn("formSubmitBtn");
      var assignRoute   = $("#assignTaskForm input[name=assignRoute]").val();
      var taskId        = $("#assignTaskForm input[name=assignedId]").val();
      var post_id       = "" == taskId ? "" : "/" + taskId;
      formMethod        = "" == taskId ? "POST" : "PUT";
      var forms         = $("#assignTaskForm");

      $.ajax({ url: assignRoute + post_id , type: formMethod, processData: false, data: forms.serialize(), })
      .done(function (data) {
        enableBtn("formSubmitBtn");
        if (data.flagError == false) {
          showSuccessToaster(data.message);
          $("#data-create-modal").modal("close");
          setTimeout(function () { 
            location.reload();   
          }, 1000);
        } else {
          showErrorToaster(data.message);
          printErrorMsg(data.error);
        }
      });
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

$('#taskCompleted').click(function() {
  // $('#taskCompleteBtn').attr('disabled', !this.checked)
  // $('#formSubmitBtn').attr('disabled', this.checked)

  if(this.checked) {
    $('.task-completed-section').show()
    // $('.task-store-input').attr('disabled', true)
  } else {
    $('.task-completed-section').hide()
    // $('.task-store-input').attr('disabled', false)
  }
});


$('#taskCompleteBtn').click(function(){
  $("#assignTaskForm").submit();
});


$("#documentStatusUpdateBtn").click(function() {
  swal({ title: "Are you sure update status?",icon: 'warning', dangerMode: true, buttons: { cancel: 'Cancel !', delete: 'Yes Update, ' }
  }).then(function (willDelete) {
    if (willDelete) {
      var stage_id  = $("#stage").val();
      var status_id = $("#status").val();
      var document_Id = $("#documentStatusId").val();

      $.ajax({url: "documents/update-status", type: "POST", dataType: "json", data:{'document_Id':document_Id, 'stage_id':stage_id, 'status_id':status_id}})
        .done(function (data) {
          if (data.flagError == false) {
            showSuccessToaster(data.message);          
            setTimeout(function () {
              location.reload();  
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

// $(".view-more-details").click(function() {
//   var postUrl   = $(this).attr('data-url'); 
//   var id        = $(this).attr('data-id');
//   var column    = $(this).attr('data-column');

//   $.ajax({url: postUrl, type: "GET"}).done(function (data) {
//     if (data.flagError == false) {
//       var details = '';
//       if (column == 'comment') {
//         details = data.document.comment;
//       } else {
//         details = data.document.brief_description;
//       }
//       $("#fullTextSection").text(details);
//       $("#viewMoreDetailsModel").modal("open");
//     } else {
//       showErrorToaster(data.message);
//       printErrorMsg(data.error);
//     }   
//   }).fail(function () {
//     showErrorToaster("Something went wrong!");
//   });
// });

function getFullDescription(id) {
  $.ajax({url: 'documents/'+id, type: "GET"}).done(function (data) {
    if (data.flagError == false) {
      var details = '';
      details = data.document.brief_description;
      $("#fullTextSection").text(details);
      $("#viewMoreDetailsModel").modal("open");
    } else {
      showErrorToaster(data.message);
      printErrorMsg(data.error);
    }   
  }).fail(function () {
    showErrorToaster("Something went wrong!");
  });
}

function getFullComment(id) {
  $.ajax({url: 'documents/'+id, type: "GET"}).done(function (data) {
    if (data.flagError == false) {
      var details = '';
      details = data.document.comment;
      $("#fullTextSection").text(details);
      $("#viewMoreDetailsModel").modal("open");
    } else {
      showErrorToaster(data.message);
      printErrorMsg(data.error);
    }   
  }).fail(function () {
    showErrorToaster("Something went wrong!");
  });
}

function deleteDocument(dataDeleteID) {
  swal({ title: "Are you sure?", icon: 'warning', dangerMode: true, buttons: { cancel: 'No, Please!', delete: 'Yes, Delete' }
    }).then(function (willDelete) {
      if (willDelete) {
        $.ajax({url: "documents/delete-version/" + dataDeleteID, type: "POST", dataType: "html"})
          .done(function (a) {
              var data = JSON.parse(a);
              if (data.flagError == false) {
                showSuccessToaster(data.message);          
                setTimeout(function () {
                  location.reload();
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
}


function proceedToDocument() {
  $("#proceed_project_id").select2({
      placeholder: "Please select a Project",
      allowClear: true,
      dropdownParent: "#proceed-to-document-modal"
  });
  $("#proceed_to_eia_id").select2({
    placeholder: "Please select EIA",
    allowClear: true,
    dropdownParent: "#proceed-to-document-modal"
  });
  
  $("#proceed-to-document-modal").modal("open");
}

$(document).on('change', '#proceed_project_id', function () {
  var url = '';
  $.ajax({ type: 'POST', url: "common/get-eia-of-project", data:{'project_id':this.value }, dataType: 'json',
    success: function(data) {
      var selectTerms = '<option value="">Please select EIA</option>';
      $.each(data.data, function(key, value) {
        selectTerms += '<option value="' + value.id + '" >' + value.code_id + '</option>';
      });
      var select = $('#proceed_to_eia_id');
      select.empty().append(selectTerms);
    }
  });
});

if ($("#proceedDocumentForm").length > 0) {
  validator = $("#proceedDocumentForm").validate({
      rules: {
        proceed_project_id: {
          required: true,
        },
        proceed_to_eia_id: {
          required: true,
        },
      },
      messages: {
        proceed_project_id: {
          required: "Please select a project",
        },
        proceed_to_eia_id: {
          required: "Please select EIA",
        },
      },
      submitHandler: function (form) {
        var proceed_to_eia_id   = $("#proceed_to_eia_id").val();
        window.location.href = `/eias/${proceed_to_eia_id}/documents/create`;
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
