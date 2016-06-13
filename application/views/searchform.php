<div id='search_form' class='dialog_container'>
	<button class='btn btn-default btn-close' style='float:right;'>x</button>
	<form id='tagindex' action='' method='get'>
		<h2>SEARCH</h2>
		<hr>
		字串搜尋:<input type='text' name='str' value='Building' disabled>
		<hr>
		<p>篩選邏輯:
		<input type='radio' name='logic' value='or' checked >OR  
		<input type='radio' name='logic' value='and' >AND
		<input type='radio' name='logic' value='no-tag' >No-tag
		</p>
		<hr>
		Tags:
		<div class='tagpool' style='width:100%;height:100px'></div>
		<hr>
		<p>
			<button type='button' name='cancel'>取消篩選</button>
		</p>
	</form>
</div>