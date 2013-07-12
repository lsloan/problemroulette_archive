        $(document).ready(function() {
          $('.topic-selector input:checkbox').click(function() {
            if ($(this).prop('checked')) {
              $(this).parents('li').addClass('checked');  
            } else {
              $(this).parents('li').removeClass('checked');
            }
            var count = 0;
            $('.topic-selector input:checkbox').each(function() {
              if ($(this).prop('checked')) {
                count++;
              }
            })
            if (count > 0) {
                $('#use-selected').removeClass('disabled');
            } else {
                $('#use-selected').addClass('disabled');
            }
          });

          $('a#statistics-tab').click(function() {
             $.get('statistics.php', function(data) {
                $('#statistics').html(data);
             });
          })});