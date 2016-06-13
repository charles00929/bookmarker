<div id='tag_form' class='dialog_container tag_container'>
	<button class='btn btn-default btn-close' style='float:right;'>x</button>
	<form name='form' action='' method='post'>
		<h2>Tag it!</h2>
		<table>
			<tr>
				<td >name:</td>
				<td >
					<input type='text' name='name'class='namefield' value='(Sample)' style='width:100px'>
				</td>
			</tr>
			<tr>
				<td >font:</td>
				<td ><input type='text' name='font' class='pickcolor' value='000000' style='width:100px'></td>
			</tr>
			<tr>
				<td >bg:</td>
				<td ><input type='text' name='bg' class='pickcolor' value='ffffff' style='width:100px'></td>
			</tr>
			<tr>
				<td><input type='hidden' name='tid' value=''></td>
				<td><button name='reset'>Reset</button></td>
			</tr>
		</table>
		<span class='tag creating_tag' id='sample'>(sample)</span>
	</form>
</div>