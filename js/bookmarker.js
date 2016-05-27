$(function(){
	$.bookmarker = {
		userdata:{}
		,loadFromEnd:function(){
			$.ajax({
				url:"/ajaxhandler/bookmark/loading"
				,method:"post"
				,dataType:"json"
				,success:function(response){
					$.bookmarker.userdata = response;
					$.bookmarkerFront.loading();
				}
				,error:function(){
					console.log("Try to getting data fail.");
				}
			});
		}
		,search:function(type,tids){
			if(type == 'OR'){

			}else if(type == 'AND'){

			}else if(type == 'LIKE'){

			}
		}
		,createBookmark:function(){}
		,modifyBookmark:function(){}
		,deleteBookmark:function(){}
		,createTag:function(){}
		,modifyTag:function(tagData){
			$.ajax({
				url:"/index.php/ajaxhandler/bookmark/tu"
				,method:"post"
				,dataType:"json"
				,success:function(response){
					if(response.tid == 0){
						//fail
					}else{
						//success
						tagData.tid = response.tid;
						$.bookmarker.userdata.tags[response.tid] = tagData;
						var tagView = $.bookmarkerFront.createTagView(response.tid);
						$.bookmarkerFront.tagToDraggable(tagView);
						$.bookmarkerFront.tagList.append(tagView);
					}
					$('#tag_list').droppable('enable');
				}
				,error:function(){
					$('#tag_list').droppable('enable');
				}
			});
		}
		,deleteTag:function(){}
	}
	$.bookmarkerFront = {
		viewModel:$.bookmarker
		//form and dialog
		,tagList:$('#tag_list')
		,bookmarkForm:$('#bookmark_form')
		,searchForm:$('#search_form')
		,tagForm:$('#tag_form')
		//bookmark and tag view
		,bookmarkList:$("#bookmarksindex")
		,bookmarkTemplate:$(".bookmark-row.template")
		,tagTemplate:$(".tag.template")
		//functions
		,loading:function(){
			//to display bookmarkers data
			for(var i in this.viewModel.userdata.bookmarks){
				var draggableBookmark = this.createBookmarkView(i);
				draggableBookmark.addClass('draggable_bookmark');
				this.bookmarkList.append(draggableBookmark);
			}
			//It should extract to a function 
			//to display tags data 
			for(var tid in this.viewModel.userdata.tags){
				var draggableTag = this.createTagView(tid);
				this.tagToDraggable(draggableTag);
				this.tagList.append(draggableTag);
			}
			//this.initializeDynamicStyles();
		}
		,initializeDynamicStyles:function(){			
			var style = $("<style id='tag_style' type='text/css'>");
			var styleContain = '';debugger;
			for(var i in $.bookmarker.userdata.tags){
				var tagData = $.bookmarker.userdata.tags[i];
				var cssText = '.tag-'+tagData.t_id+'{'
					+'color:#' + tagData.font_color +';'
					+'background-color:#' + tagData.bg_color + ';'
					+'}'
				styleContain = styleContain.concat(cssText);
			}
			style.html(styleContain);
			style.appendTo('head');

		}
		,createTagView:function(tid){
			var tagTemp = this.tagTemplate.clone();
			var tag = this.viewModel.userdata.tags[tid];
			//set tag template with data
			tagTemp.removeClass("template")
				.addClass('tag-'+tid)
				.css("color",'#'+tag.font_color)
				.css("background-color",'#'+tag.bg_color)
				.html(tag.title)
				.data('tid',tid)
				.show();
			return tagTemp;
		}
		,tagToDraggable:function(tagElement){
			if(tagElement.hasClass('tag')){
				tagElement.addClass('draggable_tag');
				tagElement.draggable({
					'helper':'clone'
					,'opacity':0.8
				});
			}
		}
		,createBookmarkView:function(bid){this.createBookmarkView(bid,true);}
		,createBookmarkView:function(bid,enableDrag){
			var bk = this.viewModel.userdata.bookmarks[bid];
			var temp = this.bookmarkTemplate.clone();
				//set template with data
				temp.removeClass("template")
					.data('bid',bid)
					.show()
					.find(".bookmark-url")
					.attr("href",bk.url)
					.html(bk.title);
			//to add tags
			for(var j in bk.tags){
				temp.find(".tagscolumn").append(this.createTagView(bk.tags[j]));
			}
			this.bookmarkToDraggable(temp);
			return temp;
		}
		,bookmarkToDraggable:function(bookmarkElement){
			bookmarkElement.addClass('draggable_bookmark');
			bookmarkElement.draggable({
			cursor: "move",
			cursorAt: { top: -12, left: -20 },
				helper: function(event) {
					var bookmarkData = $.bookmarker.userdata.bookmarks[bookmarkElement.data('bid')];
					return $( "<div>" + bookmarkData.title + "</div>" );
				}
			});
		}
		,updateTagsView:function(){
			$('.tag').each(function(i){console.log($(this).data())});
		}
	}
	//other initialize
	$.bookmarker.loadFromEnd();
	$.bookmarkerFront.tagList.show();
	$.bookmarkerFront.bookmarkForm.hide();
	$.bookmarkerFront.searchForm.hide();
	$.bookmarkerFront.tagForm.hide();

	//colpaick is not MAINTAIN any more.
	//and it has no document .....
	//so please change other plugin
	$('.pickcolor').colpick({
		layout:'hex',
		submit:0,
		colorScheme:'dark',
		onChange:function(hsb,hex,rgb,el,bySetColor) {
			$(el).css('border-color','#'+hex);
			if(!bySetColor){
				$(el).val(hex);
			}
			if($(el).attr('name') == 'font'){
				$('#sample').css('color','#'+hex);
			}
			if($(el).attr('name') == 'bg'){
				$('#sample').css('background-color','#'+hex);
			}
		}
	}).keyup(function(){
		//update color by text
		$(this).colpickSetColor(this.value);
	});
	$.bookmarkerFront.tagForm.find('[name=name]').keyup(function(){
		$('#sample').html($(this).val());
	});
	//binding
	$('#newbookmarker-btn').click(function(){
		$.bookmarkerFront.bookmarkForm.show();
		$.bookmarkerFront.searchForm.hide();
		$.bookmarkerFront.tagForm.hide();
	});
	$('#newtag-btn').click(function(){
		$.bookmarkerFront.tagForm.show();
		$.bookmarkerFront.bookmarkForm.hide();
		$.bookmarkerFront.searchForm.hide();
	});
	$('#search-btn').click(function(){
		$.bookmarkerFront.searchForm.show();
		$.bookmarkerFront.bookmarkForm.hide();
		$.bookmarkerFront.tagForm.hide();
	});

	$.bookmarkerFront.bookmarkForm.find('button.btn-close').click(function(){
		$.bookmarkerFront.bookmarkForm.hide();
	});
	$.bookmarkerFront.tagForm.find('button.btn-close').click(function(){
		$.bookmarkerFront.tagForm.hide();
	});
	$.bookmarkerFront.searchForm.find('button.btn-close').click(function(){
		$.bookmarkerFront.searchForm.hide();
	});
	// form binding
	$('#tag_form').find('form').submit(function(){
		return false;
	});

	//drag & drop initialize
	// dragger
	$('#sample').data('tid',0);
	$.bookmarkerFront.tagToDraggable($('#sample'));

	$('.draggable_bookmark').draggable({
	cursor: "move",
		cursorAt: { top: -12, left: -20 },
		helper: function(event) {
			var bookmarkData = $.bookmarker.userdata.bookmarks[$(this).data('bid')];
		return $( "<div>" + bookmarkData.title + "</div>" );
		}
	});

	//dropper
	$('#tag_list').droppable({
		accept:function(dragging){
			return dragging.hasClass('draggable_tag') && dragging.hasClass('creating_tag');
		}
		,activeClass:'highlighted_container'
		,drop:function(event,ui){
			var tag = ui.draggable;
			if(tag.hasClass('used_tag')){
				tag.remove();
			}else if(tag.hasClass('creating_tag')){
				var form = $.bookmarkerFront.tagForm.find('form');
				var tagData = {
					tid:tag.data('tid')
					,title:form.find('[name=name]').val()
					,font_color:form.find('[name=font]').val()
					,bg_color:form.find('[name=bg]').val()
				}
				$.bookmarker.modifyTag(tagData);
			}
			$(this).droppable('disable');
		}
	});
	$('#tag_form').droppable({
		accept:function(dragging){
			return dragging.hasClass('draggable_tag') && !dragging.hasClass('creating_tag');
		}
		,activeClass:'highlighted_container'
		,drop:function(event,ui){
			var form = $(event.target);
			var tagData = $.bookmarker.userdata.tags[ui.draggable.data('tid')];
			form.find('[name=name]').val(tagData.title);
			form.find('[name=font]').val(tagData.font_color).keyup();
			form.find('[name=bg]').val(tagData.bg_color).keyup();
		}
	});

	$('#search_form').find('.tagpool').droppable({
		accept:function(dragging){
			return dragging.hasClass('draggable_tag');
		}
		,activeClass:'highlighted_container'
		,drop:function(event,ui){
			var tag = ui.draggable;
			var tagView = $.bookmarkerFront.createTagView(tag.data('tid'));
			tagView.addClass('used_tag');
			$.bookmarkerFront.tagToDraggable(tagView);
			$(this).append(tagView);
		}
	});
	$('#bookmark_form').find('.tagpool').droppable({
		accept:function(dragging){
			return dragging.hasClass('draggable_tag') && !dragging.hasClass('used_tag');
		}
		,activeClass:'highlighted_container'
		,drop:function(event,ui){
			var tag = ui.draggable;
			var tagView = $.bookmarkerFront.createTagView(tag.data('tid'));
			tagView.addClass('used_tag');
			$.bookmarkerFront.tagToDraggable(tagView);
			$(this).append(tagView);
		}
	});

	$('#bookmark_form').droppable({
		accept:function(dragging){
			return dragging.hasClass('draggable_bookmark');
		}
		,activeClass:'highlighted_container'
		,drop:function(event,ui){
			var form = $(event.target);
			var bookmarkData = $.bookmarker.userdata.bookmarks[ui.draggable.data('bid')];
			form.find('[name=title]').val(bookmarkData.title);
			form.find('[name=url]').val(bookmarkData.url);
			form.find('.tagpool').html('');
			for(var i in bookmarkData.tags){
				var tagView = $.bookmarkerFront.createTagView(bookmarkData.tags[i]);
				tagView.addClass('used_tag');
				$.bookmarkerFront.tagToDraggable(tagView);
				form.find('.tagpool').append(tagView);
			}
		}
	});

	$('#delete_form').droppable({
		activeClass:'highlighted_container'
		,drop:function(event,ui){

		}
	});
});