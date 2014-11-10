$(document).ready(function() {
  $('body').on('click', '.delete_stats_file', function(eventObj){
    eventObj.preventDefault();
    var link = $(this);
    var filename = $(link).data('filename');
    var url = $(link).data('url');
    $.ajax(url, {
      type : 'post',
      dataType : 'json',
      data: {
        delete_file: true,
        filename: filename
      },
      success: function(data) {
        if(data && data.deleted) {
          $(link).closest('.export_file_for_download').empty().addClass('marked-deleted').html('This file has been deleted');
        } else {
          alert("The file was not removed for some reason.  Please refresh your browser and try again.");
        }
        
      },
      error : function(jqXHR, textStatus, errorThrown){
        alert("An unknown error occurred while trying to remove the file.  Please refresh your browser and try again.");
      }
    });
  });
});