$(document).ready(function() 
{ 
	$("#historyTable").tablesorter({
		sortList: [[1,1]]
	}); 
	
	/*$('select.dropdown-num-rows').change(function(){
		var num_rows = $("select.dropdown-num-rows").val();
		
		if (num_rows == 'All')
		{
			//show all rows the moment 'all' is chosen
			$('tbody tr:gt(0)').css('display', 'table-row');
			$('tbody tr:nth-child(1)').css('display', 'table-row');
			
			//show all rows if sorted while 'all' is chosen
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
			//hide rows > (selected_num_rows) on page load
			$('tbody tr:gt(0)').css('display', 'table-row');
			$('tbody tr:nth-child(1)').css('display', 'table-row');
			$('tbody tr:gt('+(num_rows-1)+')').css('display', 'none');
			
			//hide all rows for sorting, then show all rows <= (selected_num_rows) after sorting is done
			$("table").bind("sortStart",function() { 
				$('tbody tr:gt(0)').css('display', 'none');
				$('tbody tr:nth-child(1)').css('display', 'none');
			}).bind("sortEnd",function() { 
				$('tbody tr:lt('+num_rows+')').css('display', 'table-row');
			});
		}
	});*/
	
	$('select.dropdown-correct').change(function(){
		var correct = $("select.dropdown-correct").val();
	
		if (correct == 'correct')
		{
			//show all rows
			$('tbody tr:gt(0)').css('display', 'table-row');
			$('tbody tr:nth-child(1)').css('display', 'table-row');
			
			//show only correct answers
			$('#historyTable > tbody  > tr').each(function()
			{
				if ($('.cell-student-answer', this).html() != $('.cell-correct-answer', this).html())
				{
					$(this).css('display','none');
				}
			});
		}
		
		else if (correct == 'incorrect')
		{
			//show all rows
			$('tbody tr:gt(0)').css('display', 'table-row');
			$('tbody tr:nth-child(1)').css('display', 'table-row');
			
			//show only incorrect answers
			$('#historyTable > tbody  > tr').each(function()
			{
				if ($('.cell-student-answer', this).html() == $('.cell-correct-answer', this).html())
				{
					$(this).css('display','none');
				}
			});
		}
		
		else
		{
			//show all rows
			$('tbody tr:gt(0)').css('display', 'table-row');
			$('tbody tr:nth-child(1)').css('display', 'table-row');
		}
	});
	
});