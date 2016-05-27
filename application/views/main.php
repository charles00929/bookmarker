<main>
	<div id='bookmark_list'>
		<button class='switchbtn btn btn-default' value='private'>my bookmark</button>
		<button class='switchbtn btn btn-default' value='public'>public bookmark</button>
		<table id='materialsindex' style='width:1000px' class='table-hover'>
			<tr>
				<td class='col-xs-2'></td>
				<td class= 'col-xs-1'></td>
				<td class= 'col-xs-3'>標題</td>
				<td class= 'col-xs-6'>標籤</td>
			</tr>
			<?php foreach($bookmarks as $bookmark):?>
			<tr class='materialrow' data-mid='<?=$bookmark->b_id?>'>
				<td class= 'col-xs-2'>
					<a class='btn btn-primary edit_m'>修改</a>
					<a class='btn btn-danger delete_m'>刪除</a>
				</td>
				<td class= 'col-xs-1'><img src='' class='favicon'/></td>
				<td class='col-xs-3'><a class='bookmark' href="<?=$bookmark->url?>" target='_blank'><?=$bookmark->title?></a></td>
				<td class= 'col-xs-6 tagscolumn'>
					<?php foreach($bookmark->tags as $tag):?>
						<span class='mtag' style='color:#<?=$tag->font_color?>;
							background-color:#<?=$tag->bg_color?>;'>
							<?=$tag->title?>
						</span>
					<?php endforeach;?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
		<style type="text/css">
		#bookmark_list{
			margin-top: 100px;
			min-height: 250px;
		}
		</style>
	</div>
	</style>
</main>