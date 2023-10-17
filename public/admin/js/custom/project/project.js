"use strict";

var pageTitle = $("#pageTitle").val();
var pageRoute = $("#pageRoute").val();
var table;
var id;
var postId;
var PageForm;
var formMethod;
var validator;
var projectId;

$("#sortBy").select2({ placeholder: "Sort By", allowClear: true });
$("#companyId").select2({
    placeholder: "Please select Company",
    allowClear: true,
});
$("#companyId").select2({
    placeholder: "Please select Company",
    allowClear: true,
});
$("#categoryId").select2({
    placeholder: "Please select Category",
    allowClear: true,
});
$("#projectTypeId").select2({
    placeholder: "Please select Project Type",
    allowClear: true,
});

$('input[name="dateOfCreated"]').daterangepicker({
    singleDatePicker: true,
    // showDropdowns: true,
    startDate: new Date(),
    timePicker: true,
    locale: { format: "DD-MM-YYYY h:mm A" },
    autoApply: true,
});

$(document).on("change", "#categoryId", function () {
    $.ajax({
        type: "POST",
        url: "/common/project-types",
        data: { value: this.value, field: "category_d" },
        dataType: "json",
        success: function (data) {
          console.log(data.data);
            var selectTerms =
                '<option value="">Please select project type</option>';
            $.each(data.data, function (key, value) {
                selectTerms +=
                    '<option value="' +
                    value.id +
                    '" >' +
                    value.name +
                    "</option>";
            });
            var select = $("#projectTypeId");
            select.empty().append(selectTerms);
        },
    });
});

// Form Validation with Ajax Submit
if ($("#" + pageTitle + "Form").length > 0) {
    validator = $("#" + pageTitle + "Form").validate({
        rules: {
            name: {
                required: true,
            },
            dateOfCreated: {
                required: true,
            },
            companyId: {
                required: true,
            },
            project_code_id: {
                required: true,
            },
            projectTypeId: {
                required: true,
            },
            categoryId: {
                required: true,
            },
            totalBudget: {
                // required: true,
                digits: true,
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
            name: {
                required: "Please enter project title",
            },
            dateOfCreated: {
                required: "Please select date of creation",
            },
            companyId: {
                required: "Please select company",
            },
            project_code_id: {
                required: "Please enter projectID",
            },
            projectTypeId: {
                required: "Please select project type",
            },
            category: {
                required: "Please enter category",
            },
            totalBudget: {
                required: "Please enter total budget",
                digits: "Please enter number only",
            },
            latitude: {
                required: "Please enter latitude",
                latLonFormat: "Please enter valid format",
            },
            longitude: {
                required: "Please enter longitude",
                latLonFormat: "Please enter valid format",
            },
        },
        submitHandler: function (form) {
            disableBtn("formSubmitButton");
            projectId = $("#" + pageTitle + "Form input[name=projectId]").val();
            postId = "" == projectId ? "" : "/" + projectId;
            formMethod = "" == projectId ? "POST" : "PUT";
            var forms = $("#" + pageTitle + "Form");

            $.ajax({
                url: pageRoute + postId,
                type: formMethod,
                processData: false,
                data: forms.serialize(),
            }).done(function (data) {
                enableBtn("formSubmitButton");
                if (data.flagError == false) {
                    resetForm();
                    showSuccessToaster(data.message);
                    setTimeout(function () {
                        window.location.href = pageRoute + "/" + data.id;
                    }, 2000);
                } else {
                    showErrorToaster(data.message);
                    printErrorMsg(data.error);
                }
            });
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

jQuery.validator.addMethod(
    "emailFormat",
    function (value, element) {
        return (
            this.optional(element) ||
            /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/gim.test(value)
        );
    },
    "Please enter a valid email address"
);

jQuery.validator.addMethod(
    "mobileFormat",
    function (value, element) {
        return this.optional(element) || /^([0-9\s\-\+\(\)]*)$/gim.test(value);
    },
    "Please enter a valid mobile number"
);

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

// ^-?([0-8]?[0-9]|90)(\.[0-9]{1,10})$

function resetForm() {
    validator.resetForm();
    $("#" + pageTitle + "Form")
        .find("input[type=text]")
        .val("");
    $("#companyId").val("").trigger("change");
    $("#categoryId").val("").trigger("change");
    $("#projectTypeId").val("").trigger("change");
}

// DataTable Initialization
var columns;
var formValue;
var table = $("#data-table-projects");
var url = table.data("url");
var form = table.data("form");
var length = table.data("length");

columns = [];
formValue = [];

table.find("thead th").each(function () {
    var column = { data: $(this).data("column") };
    columns.push(column);
});

table.DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    bLengthChange: false,
    pageLength: 10,
    ajax: {
        type: "GET",
        url: url,
        data: function (data) {
            data.form = formValue;
        },
    },
    columns: columns,
});

$("#" + form + "-show-result-button").click(function () {
    formValue = $("#" + form + "-form").serializeArray();
    table.DataTable().draw();
});

$("#sortBy").change(function () {
    formValue = $("#" + form + "-form").serializeArray();
    table.DataTable().draw();
});

$("#" + form + "-filterFormClearButton").click(function () {
    $("#" + form + "-form")
        .find("input[type=text]")
        .val("");
    $("#" + form + "-form")
        .find(".select2")
        .val("")
        .trigger("change");
    $("#" + form + "-form").trigger("reset");
    formValue = $("#" + form + "-form").serializeArray();
    table.DataTable().draw();
});

// Show active and Inactive Lists
$(".listBtn").on("click", function () {
    $("#status").val($(this).attr("data-type"));
    formValue = $("#" + form + "-form").serializeArray();
    $("#dropdown1 li a.active").removeClass("active");
    $(this).addClass("active");
    table.DataTable().draw();
});

table.on("click", ".disable-item", function () {
    var postUrl = $(this).attr("data-url");
    var id = $(this).attr("data-id");
    var title = $(this).attr("data-title");
    swal({
        title: "Are you sure?",
        icon: "warning",
        dangerMode: true,
        buttons: { cancel: "No, Please!", delete: "Yes, " + title },
    }).then(function (willDelete) {
        if (willDelete) {
            $.ajax({
                url: postUrl + "/" + id,
                type: "DELETE",
                dataType: "html",
            })
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
                })
                .fail(function () {
                    showErrorToaster("Something went wrong!");
                });
        }
    });
});

table.on("click", ".restore-item", function () {
    var postUrl = $(this).attr("data-url");
    var id = $(this).attr("data-id");
    var title = $(this).attr("data-title");
    swal({
        title: "Are you sure?",
        icon: "warning",
        dangerMode: true,
        buttons: { cancel: "No, Please!", delete: "Yes, " + title },
    }).then(function (willDelete) {
        if (willDelete) {
            $.ajax({ url: postUrl + "/" + id, type: "POST", dataType: "html" })
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
                })
                .fail(function () {
                    showErrorToaster("Something went wrong!");
                });
        }
    });
});

// Display Project Full name;
function showFullName(fullName) {
    $("#projectFullName").text(fullName);
    $("#fullNameModel").modal("open");
}

table.on("click", ".view-more-details", function () {
    var postUrl = $(this).attr("data-url");
    var id = $(this).attr("data-id");
    var column = $(this).attr("data-column");

    $.ajax({ url: postUrl, type: "GET" })
        .done(function (data) {
            if (data.flagError == false) {
                var details = "";
                if (column == "comment") {
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
        })
        .fail(function () {
            showErrorToaster("Something went wrong!");
        });
});

// Form Validation with Ajax Submit
if ($("#moveToPermitForm").length > 0) {
    validator = $("#moveToPermitForm").validate({
        rules: {
            moveToPermit: {
                // required: true,
            },
        },
        messages: {
            moveToPermit: {
                required: "Please confirm before submitting ",
            },
        },
        submitHandler: function (form) {
            disableBtn("moveToPermitSubmitBtn");
            var forms = $("#moveToPermitForm");
            $.ajax({
                url: "permits",
                type: "POST",
                processData: false,
                data: forms.serialize(),
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
        errorPlacement: function (error, element) {
            if (element.is("select")) {
                error.insertAfter(element.next(".select2"));
            } else {
                error.insertAfter(element);
            }
        },
        errorElement: "span",
    });
}
