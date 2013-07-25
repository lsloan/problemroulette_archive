$(document).ready(function() 
{ 
	$("#historyTable").tablesorter({
		sortList: [[1,1]]
	}); 
	
	/*
	//hide rows > 10 on page load
	$('tbody tr:gt(9)').css('display', 'none');
	
	//hide all rows for sorting, then show all rows <= 10 after sorting is done
	$("table").bind("sortStart",function() { 
		$('tbody tr:gt(0)').css('display', 'none');
		$('tbody tr:nth-child(1)').css('display', 'none');
	}).bind("sortEnd",function() { 
		$('tbody tr:lt(10)').css('display', 'table-row');
    }); */
	
	$('select.dropdown-num-rows').change(function(){
		var num_rows = $("select.dropdown-num-rows").val();
		
		if (num_rows == 'All')
		{
			$('tbody tr:gt(0)').css('display', 'table-row');
			$('tbody tr:nth-child(1)').css('display', 'table-row');
			
			$("table").bind("sortStart",function() { 
			$('tbody tr:gt(0)').css('display', 'table-row');
			$('tbody tr:nth-child(1)').css('display', 'table-row');
			}).bind("sortEnd",function() { 
			$('tbody tr:gt(0)').css('display', 'table-row');
			$('tbody tr:nth-child(1)').css('display', 'table-row');
			});

		}
		
		else
		{
			//hide rows > selected_num_rows on page load
			$('tbody tr:gt(0)').css('display', 'table-row');
			$('tbody tr:nth-child(1)').css('display', 'table-row');
			$('tbody tr:gt('+(num_rows-1)+')').css('display', 'none');
			
			//hide all rows for sorting, then show all rows <= selected_num_rows after sorting is done
			$("table").bind("sortStart",function() { 
				$('tbody tr:gt(0)').css('display', 'none');
				$('tbody tr:nth-child(1)').css('display', 'none');
			}).bind("sortEnd",function() { 
				$('tbody tr:lt('+num_rows+')').css('display', 'table-row');
			});
		}
	});
});