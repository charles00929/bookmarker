$(function(){
	$.bookmarker = {
		userdata:{}
		,loadFromEnd:function(){
			$.ajax({
				url:"/handler/bookmark/loading"
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
		,modifyBookmark:function(bookmarkData){
			$.ajax({
				url:"/handler/bookmark/bu"
				,method:"post"
				,data:bookmarkData
				,dataType:"json"
				,success:function(response){
					if(response.bid != 0){
						//success
						if(bookmarkData.bid == 0){
							//created
							bookmarkData.bid = response.bid;
							$.bookmarker.userdata.bookmarks[response.bid] = bookmarkData;
							var draggableBookmark = $.bookmarkerFront.createBookmarkView(response.bid);
							$.bookmarkerFront.bookmarkList.append(draggableBookmark);
						}else{
							//modified
							$('.bookmark-row[name=bookmark-' + response.bid + ']')
								.find(".bookmark-url")
								.attr("href",bookmarkData.url)
								.html(bookmarkData.title);
							//to add tags
							for(var i in bookmarkData.tags){
								$('.bookmark-row[name=bookmark-' + response.bid + ']').find(".tagscolumn").append($.bookmarkerFront.createTagView(bookmarkData.tags[i]));
							}
						}
						$.bookmarkerFront.bookmarkForm.find('[name=reset]').click();
					}else{
						//fail
					}
				}
				,error:function(){

				}
			});
		}
		,deleteBookmark:function(bookmarkData){
			$.ajax({
				url:"/handler/bookmark/bd"
				,method:"post"
				,data:bookmarkData
				,dataType:"json"
				,success:function(response){
					$('.bookmark-row[name=bookmark-' + response.bid + ']').remove();
				}
				,error:function(){}
			});
		}
		,modifyTag:function(tagData){//creating as modified id = 0
			$.ajax({
				url:"/handler/bookmark/tu"
				,method:"post"
				,data:tagData
				,dataType:"json"
				,success:function(response){
					if(response.tid == 0){//fail
						
					}else{//success
						if(tagData.tid == 0){//added new
							tagData.tid = response.tid;
							$.bookmarker.userdata.tags[response.tid] = tagData;
							var tagView = $.bookmarkerFront.createTagView(response.tid);
							$.bookmarkerFront.tagToDraggable(tagView);
							$.bookmarkerFront.tagList.append(tagView);
						}else{//modified
							$('.tag[name=tag-' + response.tid + ']')
								.css('color','#' + tagData.font_color)
								.css('background-color','#' + tagData.bg_color)
								.html(tagData.title);
						}
					}
					$.bookmarkerFront.tagList.droppable('enable');
					$.bookmarkerFront.tagForm.find('[name=reset]').click();
				}
				,error:function(){
					$.bookmarkerFront.tagList.droppable('enable');
				}
			});
		}
		,deleteTag:function(tagData){
			$.ajax({
				url:"/handler/bookmark/td"
				,method:"post"
				,data:tagData
				,dataType:"json"
				,success:function(response){
					$('.tag[name=tag-' + response.tid + ']').remove();
				}
				,error:function(){}
			});
		}
		,logout:function(){
			window.location = "/user/logout";
			// $.ajax({
			// 	url:"/user/logout"
			// 	,method:"get"
			// 	,dataType:"json"
			// 	,success:function(response){
			// 		window.location = '/';
			// 	}
			// 	,error:function(){}
			// });
		}
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
		,search:function(){
			var tids = []
			,logic = $('input[name=logic]:checked')
			,bookmarks = $('.bookmark-row');
			//fetch tag id
			$('#search_form').find('.tag').each(function(i){
				tids.push($(this).data('tid'));
			});
			//before filter
			if(logic.val() == 'no-tag'){
				bookmarks.addClass('invisible');
			}else if(tids.length == 0){
				bookmarks.removeClass('invisible');
			}else if(logic.val() == 'or'){
				bookmarks.addClass('invisible');
			}else if(logic.val() == 'and'){
				bookmarks.removeClass('invisible');
			}

			if(logic.val() == 'no-tag'){
				$('.bookmark-row.invisible').each(function(i){
					if($(this).has('.tag').length == 0){
						$(this).removeClass('invisible');
					}
				});
			}

			for(index in tids){
				var tid = tids[index];
				if(logic.val() == 'or'){
					$('.bookmark-row.invisible').each(function(i){
						if($(this).has('.tag[name=tag-' + tid + ']').length == 1){
							$(this).removeClass('invisible');
						}
					});
				}else if(logic.val() == 'and'){
					$('.bookmark-row:not(.invisible)').each(function(i){
						if($(this).has('.tag[name=tag-' + tid + ']').length != 1){
							$(this).addClass('invisible');
						}
					});
				}
			}//for	
		}

		,createTagView:function(tid){
			var tagTemp = this.tagTemplate.clone();
			var tag = this.viewModel.userdata.tags[tid];
			//set tag template with data
			tagTemp.removeClass("template")
				.attr('name','tag-'+tid)
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
					.attr('name','bookmark-' + bid)
					.css('display','')
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

	//colpaick is not MAINTAIN any more and has no document also.....
	//so please change other plugin
	/**tag form binding**/
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
		$(this).colpickSetColor(this.value);//update color by text
	});

	$.bookmarkerFront.tagForm.find('[name=name]').keyup(function(){
		$('#sample').html($(this).val());
	});
	$.bookmarkerFront.tagForm.find('button.btn-close').click(function(){
		$.bookmarkerFront.tagForm.hide();
	});
	$.bookmarkerFront.tagForm.find('[name=reset]').click(function(){
		var tagForm = $.bookmarkerFront.tagForm;
		tagForm.find('[name=tid]').val(0);
		tagForm.find('[name=font]').val('000000').keyup();
		tagForm.find('[name=bg]').val('ffffff').keyup();
		tagForm.find('[name=name]').val('(Sample)').keyup();
	});
	$.bookmarkerFront.tagForm.find('form').submit(function(){
		return false;
	});
	/** bookmark form binding**/
	$.bookmarkerFront.bookmarkForm.find('form').submit(function(){
		return false;
	});
	$.bookmarkerFront.bookmarkForm.find('[name=submit]').click(function(){
		var form = $.bookmarkerFront.bookmarkForm;
		var tags = [];
		form.find('.tagpool>.tag').each(function(){
			tags.push($(this).data('tid'));
		});
		var bookmarkData = {
			bid:form.find('[name=bid]').val()
			,url:form.find('[name=url]').val()
			,title:form.find('[name=title]').val()
			,tags:tags
		};
		$.bookmarker.modifyBookmark(bookmarkData);
	});
	$.bookmarkerFront.bookmarkForm.find('[name=reset]').click(function(){
		var form = $.bookmarkerFront.bookmarkForm;
		form.find('[name=title]').val('');
		form.find('[name=url]').val('');
		form.find('[name=bid]').val(0);
		form.find('.tagpool').html('');
	});

	//menu button binding
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
	$('#help-btn').click(function(){
		$('#help_dialog').show();
	});
	$('#help_dialog>[name=close]').click(function(){
		$('#help_dialog').hide();
	});
	$.bookmarkerFront.bookmarkForm.find('button.btn-close').click(function(){
		$.bookmarkerFront.bookmarkForm.hide();
	});

	$.bookmarkerFront.searchForm.find('button.btn-close').click(function(){
		$.bookmarkerFront.searchForm.hide();
	});
	$('#logout-btn').click(function(){
		$.bookmarker.logout();
	});

	//filted form binding
	$('input[name=logic]').on('change',$.bookmarkerFront.search);
	$('button[name=cancel]').on('click',function(){
		$('#search_form').find('.tagpool').html('');
		$.bookmarkerFront.search();
	});
	$.bookmarkerFront.searchForm.find('[name=nonetag]').click(function(){
		$('#search_form').find('.tagpool').html('');
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
					tid:form.find('[name=tid]').val()
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
			form.find('[name=tid]').val(tagData.tid);
			form.find('[name=name]').val(tagData.title).keyup();
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
			tagView.addClass('filted_tag');
			$.bookmarkerFront.tagToDraggable(tagView);
			$(this).append(tagView);
			$.bookmarkerFront.search();
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
			form.find('[name=bid]').val(bookmarkData.bid);
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
			if(ui.draggable.hasClass('draggable_tag')){
				var tag = ui.draggable;
				//remove timing is different
				if(tag.hasClass('filted_tag')){
					tag.remove();
					$.bookmarkerFront.search();
				}else if(tag.hasClass('used_tag')){

				}else{
					if(confirm('Are you sure to delete this tag ?')){
						var tagData = {'tid':tag.data('tid')}
						$.bookmarker.deleteTag(tagData);
					}
				}
			}else if(ui.draggable.hasClass('draggable_bookmark')){
				var bookmark = ui.draggable;
				if(confirm('Are you sure to delete this bookmark ?')){
					var bookmarkData = {bid:bookmark.data('bid')};
					$.bookmarker.deleteBookmark(bookmarkData);
				}
			}
		}
	});
});