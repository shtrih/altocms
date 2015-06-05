/*
 Audiorecordslight plugin
 (P) PSNet, 2008 - 2013
 http://psnet.lookformp3.net/
 http://livestreet.ru/profile/PSNet/
 https://catalog.livestreetcms.com/profile/PSNet/
 http://livestreetguide.com/developer/PSNet/
 */

/**
 * Хак для предовращения ошибок в мобильном шаблоне, который отключает модальные окна
 * добавляет нужные пустые методы, если их нет
 */
jQuery.fn.jqm = jQuery.fn.jqm || function() {};
jQuery.fn.jqmShow = jQuery.fn.jqmShow || function() {};


var ls = ls || {};

ls.audiorecordslight = (function ($) {

	/**
	 * Селекторы
	 */
	this.selectors = {
		/*
			Где искать mp3 теги
		 */
		mp3_tags_parent: '#content .text ',
		/*
			Класс, который будет добавлен для тегов, когда внутри них будет помещен плеер (чтобы отключить визуальное оформление через ксс)
		 */
		active_class_for_mp3_tags: 'player-active',
		/*
			Начало ид места вставки прикрепленного другими плагинами аудио
		 */
		attached_topic_prefix_id: '#ARL_AudioPlace',
		/*
			Класс контейнера, в котором будут храниться плееры прикрепленного аудио
		 */
		attached_players_container_class: 'AudioPlayersLightContainer',


		/*
			Последний элемент без запятой (для удобства)
		 */
		last_element: true
	};


	/**
	 * Получить код плеера
	 *
	 * @param iWidth		ширина
	 * @param iHeight		высота
	 * @param sFilename		путь к файлу плеера
	 * @param sAudioParam	имя параметра, указывающее на аудио-файл
	 * @param sFileToPlay	аудио файл
	 * @return string		код плеера
	 */
	this.GetPlayerCode = function (iWidth, iHeight, sFilename, sAudioParam, sFileToPlay) {
		return '' +
			'<object width="' + iWidth + '" height="' + iHeight + '" align="middle">' +
				'<param name="movie" value="' + sFilename + '" />' +
				'<param name="allowScriptAccess" value="always" />' +
				'<param name="wmode" value="transparent" />' +
				'<param name="quality" value="best" />' +
				'<param name="flashvars" value="' + sAudioParam + '=' + sFileToPlay + '">' +
				'<embed src="' + sFilename + '" width="' + iWidth + '" height="' + iHeight + '" menu="false" quality="best" align="middle" type="application/x-shockwave-flash" allowscriptaccess="always" flashvars="' + sAudioParam + '=' + sFileToPlay + '" wmode="transparent"></embed>' +
			'</object>';
	};


	/**
	 * Получить код плеера со значениями по-умолчанию для указанного аудио-файла
	 *
	 * @param sFileToPlay	аудио файл
	 * @return string
	 */
	this.GetPlayerCodeWithDefaultValues = function (sFileToPlay) {
		return this.GetPlayerCode (ARL_Player_Width, ARL_Player_Height, ARL_Player_Filename, ARL_Player_AudioParam, sFileToPlay);
	};


	/**
	 * Добавить плеер внутрь всех mp3 тегов в текстах топиков, комментариев, ЛС и стен пользователей
	 */
	this.AddPlayerForAllMP3Tags = function () {
		$ (this.selectors.mp3_tags_parent + ARL_Tag_Name).each (function (i, o) {
			/*
				добавить класс, отключающий визуальное оформление пустого блока тега mp3
			 */
			var sLink = $ (o).addClass (ls.audiorecordslight.selectors.active_class_for_mp3_tags).attr ('src');
			$ (o).html (ls.audiorecordslight.GetPlayerCodeWithDefaultValues (sLink));
		});
	};
	

	/**
	 * Получить id обьекта для места вставки аудио
	 *
	 * @param iTopicId		ид топика
	 * @returns {string}
	 */
	this.GetAttachedTopicPlace = function (iTopicId) {
		return this.selectors.attached_topic_prefix_id + iTopicId;
	};


	/**
	 * Добавить плеер для топика с прикрепленным аудио-файлом или ссылкой на аудио-файл в тексте
	 *
	 * @param sFileToPlay	имя файла
	 * @param iTopicId		ид топика
	 */
	this.AddPlayerForAttachedAudioFileInTopic = function (sFileToPlay, iTopicId) {
		var AudioBlock = $ ('<div />', {
			'class': this.selectors.attached_players_container_class,
			'html': this.GetPlayerCodeWithDefaultValues (sFileToPlay)
		});
		var Place = $ (this.GetAttachedTopicPlace (iTopicId)).closest ('.topic').children ('div.topic-content');

		if (Place != null) {
			Place.before (AudioBlock);
		} else {
			ls.msg.error (ARL_Wrong_Document_Structure_Msg);
		}
	};
	
	// ---

	return this;
	
}).call (ls.audiorecordslight || {}, jQuery);

// ---

jQuery (document).ready (function ($) {
	/*
		Добавить плеер внутрь mp3 тегов
	 */
	ls.audiorecordslight.AddPlayerForAllMP3Tags ();
});
