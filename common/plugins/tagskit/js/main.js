var ls = ls || {};
ls.plugin = ls.plugin || {};


ls.plugin.tagskit = (function ($) {

	this.search = function(form) {
		$form=$(form);
		/**
		 * Параметры поиска
		 */
		var tags=$form.find('.js-tag-search').val();
		var how=$('.js-tagskit-search').find(':radio[name=tk_how]').filter(":checked").val();
		var where=$('.js-tagskit-search').find(':radio[name=tk_where]').filter(":checked").val();
		var blog_id=$('.js-tagskit-search').find(':checkbox[name=blog_id]').filter(":checked").val();
		/**
		 * Формируем URL для поиска тегов и открываем его
		 */
		var url=aRouter.tag+'?tags='+encodeURIComponent(tags)+'&tk_how='+how+'&tk_where='+where;
		if (blog_id) {
			url=url+'&blog_id='+blog_id;
		}
		window.location.href=url;
		return false;
	};

	this.searchButton = function() {
		$(".js-tag-search-form").submit();
	};

	this.init = function() {
		$(".autocomplete-tags").autocomplete("destroy");
		ls.autocomplete.add($(".autocomplete-tags"), aRouter['ajax']+'autocompleter/tag/', true);

		$(".js-tag-search-form").unbind("submit").submit(function(){
			return ls.plugin.tagskit.search(this);
		});
	};

	this.initFormWhite = function() {
		$('#modal_tk_white_list').jqm();
		$('input[name=topic_tags]').autocomplete("destroy").bind('focus',function(){
			this.showWindowWhite();
		}.bind(this));
	};

	this.initFormSearchCategory = function() {
		$('#modal_tk_search_category').jqm();
		$('.js-tagskit-tag-search').autocomplete("destroy").bind('focus',function(){
			this.showWindowSearchCategory();
		}.bind(this));
	};

	this.initSettingsGroupTags = function() {
		$('#tk-tags-main-select').bind('change',function(e){
			var val=e.target.value;
			$('#tk-tags-main-select  option:selected').each(function(){
				this.selected=false;
			});
			$("#tk-tags-main-select :contains('"+val+"')").attr("selected", "selected");
			this.clickGroupTag(val);
		}.bind(this));
	};

	this.clickGroupTag = function(text) {
		$('#tk-tags-depend-area').html('.').addClass('tk-loader');
		ls.ajax(aRouter.tk_admin+'ajax/load-depend-tags/',{ 'text': text },function(result){
			$('#tk-tags-depend-area').removeClass('tk-loader').html(result.sText);
		}.bind(this));
	};

	this.saveDependTags = function() {
		$('#tk-tags-depend-text').addClass('tk-loader');
		ls.ajax(aRouter.tk_admin+'ajax/save-depend-tags/',{ 'tag': $('#tk-tags-main-tag').val(), 'text': $('#tk-tags-depend-text').val() },function(result){
			$('#tk-tags-depend-text').removeClass('tk-loader');
			this.clickGroupTag($('#tk-tags-main-tag').val());
		}.bind(this));
	};

	this.showWindowWhite = function() {
		this.highlightTags();
		$('#modal_tk_white_list').jqmShow();
	};

	this.showWindowSearchCategory = function() {
		this.highlightTags('.js-tagskit-tag-search');
		$('#modal_tk_search_category').jqmShow();
	};

	this.clickWhiteTag = function(sTag,obj) {
		obj=$(obj);
		var terms = $('#topic_tags').val().split( /,\s*/ );
		/**
		 * Если такой тег уже есть, то удаляем его, иначе добавляем
		 */
		if (this.in_array(sTag,terms)) {
			terms=this.array_diff(terms,[sTag]);
			obj.removeClass('active');
		} else {
			if (terms.length==1 && !terms[0]) {
				terms=[sTag];
			} else {
				terms.push(sTag);
			}
			obj.addClass('active');
		}
		this.array_unique(terms);
		$('#topic_tags').val(terms.join(", "));
	};

	this.clickSearchCategoryTag = function(obj) {
		obj=$(obj);
		obj.toggleClass('active');

		var sTag=obj.html();

		if ($('.js-tagskit-tag-search').length) {
			var terms = $('.js-tagskit-tag-search').val().split( /,\s*/ );
			/**
			 * Если такой тег уже есть, то удаляем его, иначе добавляем
			 */
			if (this.in_array(sTag,terms)) {
				terms=this.array_diff(terms,[sTag]);
				obj.removeClass('active');
			} else {
				if (terms.length==1 && !terms[0]) {
					terms=[sTag];
				} else {
					terms.push(sTag);
				}
				obj.addClass('active');
			}
			this.array_unique(terms);
			$('.js-tagskit-tag-search').val(terms.join(", "));
		}
	};

	this.submitSearchCategoryTags = function(blogId) {
		var tags=[];
		$('#modal_tk_search_category .tk-tags-white.active').each(function(k,v){
			tags.push($(v).html());
		});

		if ($('.js-tagskit-tag-search').length) {
			this.searchButton();
		} else {
			var url=aRouter.tag+'?tags='+encodeURIComponent(tags.join(','))+'&tk_how=and';
			if (blogId) {
				url=url+'&blog_id='+blogId;
			}
			window.location.href=url;
		}
	};

	this.clearTags = function() {
		$('#topic_tags').val('');
		this.highlightTags();
	};

	this.highlightTags = function(selecter) {
		selecter=selecter || '#topic_tags';
		$this=this;

		if ($(selecter).length) {
			var terms = $(selecter).val().split(/,\s*/);
			$('#tk-white-tags-area .tk-tags-white').removeClass('active').each(function (k, v) {
				if ($this.in_array($(v).text(), terms)) {
					$(this).addClass('active');
				}
			});
		}
	};

	this.loadPageWhiteTag = function(iPage) {
		$('#tk-white-tags-area').html('').addClass('tk-loader');

		ls.ajax(aRouter.ajax+'tk/load-white-tags/',{ 'page': iPage },function(result){
			$('#tk-white-tags-area').html('').removeClass('tk-loader').replaceWith(result.sText);
			this.highlightTags();
		}.bind(this));

		return false;
	};

	this.autoSearchTags = function(button) {
		if ($(button).hasClass('tk-loader')) {
			return false;
		}
		$(button).addClass('tk-loader');
		var text =(BLOG_USE_TINYMCE) ? tinyMCE.activeEditor.getContent()  : $('#topic_text').val();
		ls.ajax(aRouter.ajax+'tk/auto-search-tags/',{ 'text': text },function(result){
			$(button).removeClass('tk-loader');

			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				var terms=[];
				$.each(result.aTags,function(k,v){
					terms.push(v);
				});
				$('#topic_tags').val(terms.join(", "));
				this.highlightTags();
			}

		}.bind(this));
	};

	this.array_unique = function(array) {
		var p, i, j;
		for(i = array.length; i;){
			for(p = --i; p > 0;){
				if(array[i] === array[--p]){
					for(j = p; --p && array[i] === array[p];);
					i -= array.splice(p + 1, j - p).length;
				}
			}
		}
		return true;
	};


	this.array_diff = function(a1,a2) {
		var a=[], diff=[];
		for(var i=0;i<a1.length;i++)
			a[a1[i]]=true;
		for(var i=0;i<a2.length;i++)
			if(a[a2[i]]) delete a[a2[i]];
			else a[a2[i]]=true;
		for(var k in a)
			diff.push(k);
		return diff;
	};


	this.in_array = function(needle, haystack) {
		var found = false, key;
		for (key in haystack) {
			if (haystack[key] === needle){
				found = true;
				break;
			}
		}
		return found;
	};


	return this;
}).call(ls.plugin.tagskit || {},jQuery);
