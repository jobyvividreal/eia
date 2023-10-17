/*
 * DataTables - Tables
 */


$(document).ready(function () { 
  $('.data-table-container').each(function () {
      var formValue;
      var columns;
      var length;
      var form;
      var url;
      var table;
      table     = $(this).find('.data-tables');
      url       = table.data('url');
      form      = table.data('form');
      length    = table.data('length');
      columns   = [];
      formValue = [];
      table.find('thead th').each(function () {
        var column = {'data': $(this).data('column')};
        columns.push(column);
      });

      //console.log(columns);
      
      table.DataTable({
          processing: true,
          serverSide: true,
          stateSave: true,
          searching: false,
          bLengthChange: false,
          pageLength: (length == null) ? 10 : length,
          ajax: {
              "type": "GET",
              "url": url,
              "data": function (data) {
                  data.form = formValue;
              }
          },
          columns: columns,
          fnDrawCallback: function () {
            $('[data-toggle="tooltip"]').tooltip();
          }
      });

      $('#' + form + '-show-result-button').click(function () {
        formValue = $('#' + form + '-form').serializeArray();
        table.DataTable().draw();
      });
      
      $('#' + form + '-clear-button').click(function () {
        $('#' + form + '-form').find("input[type=text], textarea").val("");
        $('#' + form + '-form').find(".select2").val('').trigger("change");
        $('#' + form + '-form').trigger("reset");
        formValue = $('#' + form + '-form').serializeArray();
        table.DataTable().draw();
      });

      $(document).on('keypress', function (e) {
        if (e.which == 13) {
          e.preventDefault();
          formValue = $('#' + form + '-form').serializeArray();
          table.DataTable().draw();
        }
      });

      table.on('click', '.manage-status', function() {
        var postUrl = $(this).attr('data-url');
        // var id      = $(this).attr('data-id');
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

      table.on('click', '.delete-item', function() {
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

      table.on('click', '.disable-item', function() {

        var postUrl = $(this).attr('data-url'); 
        var id      = $(this).attr('data-id');
        
        swal({ title: "Are you sure?",icon: 'warning', dangerMode: true, buttons: { cancel: 'No, Please!', delete: 'Yes, Inactivate' }
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
        
        swal({ title: "Are you sure?",icon: 'warning', dangerMode: true, buttons: { cancel: 'No, Please!', delete: 'Yes, Activate' }
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

      $(".listBtn").on("click", function()  {
        $("#status").val($(this).attr('data-type'));
        formValue = $('#' + form + '-form').serializeArray();
        table.DataTable().draw();
      });
      
  });
});

















$(function () {

  // Simple Data Table

  // $('#data-table-simple').DataTable({
  //   "responsive": true,
  // });

  // Row Grouping Table

  // var table = $('#data-table-row-grouping').DataTable({
  //   "responsive": true,
  //   "columnDefs": [{
  //     "visible": false,
  //     "targets": 2
  //   }],
  //   "order": [
  //     [2, 'asc']
  //   ],
  //   "displayLength": 25,
  //   "drawCallback": function (settings) {
  //     var api = this.api();
  //     var rows = api.rows({
  //       page: 'current'
  //     }).nodes();
  //     var last = null;

  //     api.column(2, {
  //       page: 'current'
  //     }).data().each(function (group, i) {
  //       if (last !== group) {
  //         $(rows).eq(i).before(
  //           '<tr class="group"><td colspan="5">' + group + '</td></tr>'
  //         );

  //         last = group;
  //       }
  //     });
  //   }
  // });

  // Page Length Option Table

  // $('#page-length-option').DataTable({
  //   "responsive": true,
  //   "lengthMenu": [
  //     [10, 25, 50, -1],
  //     [10, 25, 50, "All"]
  //   ]
  // });

  // Dynmaic Scroll table

  // $('#scroll-dynamic').DataTable({
  //   "responsive": true,
  //   scrollY: '50vh',
  //   scrollCollapse: true,
  //   paging: false
  // })

  // Horizontal And Vertical Scroll Table

  // $('#scroll-vert-hor').DataTable({
  //   "scrollY": 200,
  //   "scrollX": true
  // })

  // Multi Select Table

  // $('#multi-select').DataTable({
  //   responsive: true,
  //   "paging": true,
  //   "ordering": false,
  //   "info": false,
  //   "columnDefs": [{
  //     "visible": false,
  //     "targets": 2
  //   }],


  // });

});


// Datatable click on select issue fix
// $(window).on('load', function () {
//   $(".dropdown-content.select-dropdown li").on("click", function () {
//     var that = this;
//     setTimeout(function () {
//       if ($(that).parent().parent().find('.select-dropdown').hasClass('active')) {
//         // $(that).parent().removeClass('active');
//         $(that).parent().parent().find('.select-dropdown').removeClass('active');
//         $(that).parent().hide();
//       }
//     }, 100);
//   });
// });

// var checkbox = $('#multi-select tbody tr th input')
// var selectAll = $('#multi-select .select-all')

// Select A Row Function

$(document).ready(function () {

  // checkbox.on('click', function () {
  //   $(this).parent().parent().parent().toggleClass('selected');
  // })

  // checkbox.on('click', function () {
  //   if ($(this).attr("checked")) {
  //     $(this).attr('checked', false);
  //   } else {
  //     $(this).attr('checked', true);
  //   }
  // })

  // Select Every Row 

  // selectAll.on('click', function () {
  //   $(this).toggleClass('clicked');
  //   if (selectAll.hasClass('clicked')) {
  //     $('#multi-select tbody tr').addClass('selected');
  //   } else {
  //     $('#multi-select tbody tr').removeClass('selected');
  //   }

  //   if ($('#multi-select tbody tr').hasClass('selected')) {
  //     checkbox.prop('checked', true);

  //   } else {
  //     checkbox.prop('checked', false);

  //   }
  // })
})



