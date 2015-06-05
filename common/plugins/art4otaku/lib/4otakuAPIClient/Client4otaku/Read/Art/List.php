<?php
/**
 * Created by PhpStorm.
 * User: shtrih
 * Date: 26.10.13
 * Time: 17:47
 */
namespace Client4otaku;

class ReadArtList extends ReadAbstract {

	/**
	 * @var $_filter содержит экземпляр FilterList
	 */
	protected $_filter;

	/**
	 * @see http://wiki.4otaku.org/Api:Чтение:Арт:Список
	 */
	function __construct() {
		parent::__construct();

		$this->_filter = new FilterList();
	}

	public function getResponse() {
		// apply filters
		$this->_request_fields['filter'] = $this->_filter->getResult();

		return parent::getResponse();
	}

	/**
	 * Если задано, то к ответу будут присоединены дополнительные данные: теги артов, количество вариаций, рейтинг,
	 * логин залившего арт пользователя.
	 * @param bool $value
	 * @return $this
	 */
	public function setMeta($value) {
		$this->setField('add_meta', (bool)$value);

		return $this;
	}

	/**
	 * Если TRUE, то вариации одного и того же арта будут возвращены в виде отдельных элементов, иначе будет возвращаться одна основная вариация
	 * @param bool $value
	 * @return $this
	 */
	public function setNoGroup($value) {
		$this->setField('no_group', (bool)$value);

		return $this;
	}

	/**
	 * Позволяет задать значения для выбора записи по тем или иным свойствам.
	 * По умолчанию не используется ни одного фильтра.
	 * @return FilterList
	 */
	public function getFilter() {
		return $this->_filter;
	}

	/**
	 * Дополнительное значение для сортировки, может содержать номер группы, пака или манги.
	 * Принимает следующие значения: none, random, date, width, height, weight, size, parent_order, rating, comment_count, comment_date, tag_count, group, manga, pack.
	 * @param string $value
	 * @return $this
	 * @see http://wiki.4otaku.org/Api:%D0%A7%D1%82%D0%B5%D0%BD%D0%B8%D0%B5:%D0%90%D1%80%D1%82:%D0%A1%D0%BF%D0%B8%D1%81%D0%BE%D0%BA#.D0.9F.D0.BE.D0.BB.D0.B5_sort_value
	 */
	public function setSortValue($value) {
		$this->setField('sort_value', $value);

		return $this;
	}
}

class FilterList {

	/**
	 * art_tag	Тег или вариант написания тега. value должно быть текстовым.
	 */
	const FILTER_NAME_TAG = 'art_tag';
	/**
	 * state	Состояние арта. value должно быть текстовым и ограничено одним из этих значений: tagged, untagged, approved, unapproved, disapproved.
	 */
	const FILTER_NAME_STATE = 'state';
	/**
	 * art_pack	CG-пак, к которому относится арт. value должно быть целочисленным и содержать номер CG-пака.
	 */
	const FILTER_NAME_PACK = 'art_pack';
	/**
	 * art_group	Группа, к которой относится арт. value должно быть целочисленным и содержать номер группы.
	 */
	const FILTER_NAME_GROUP = 'art_group';
	/**
	 * art_manga	Манга, к которой относится арт. value должно быть целочисленным и содержать номер манги.
	 */
	const FILTER_NAME_MANGA = 'art_manga';
	/**
	 * art_artist	Галерея, к которой относится арт. value должно быть целочисленным и содержать номер галереи.
	 */
	const FILTER_NAME_ARTIST = 'art_artist';
	/**
	 * art_rating	Суммарный рейтинг арта. value должно быть целочисленным.
	 */
	const FILTER_NAME_RATING = 'art_rating';
	/**
	 * comment_count	Количество комментариев к этому арту. value должно быть целочисленным.
	 */
	const FILTER_NAME_COMMENT_COUNT = 'comment_count';
	/**
	 * comment_date	День когда был добавлен последний комментарий арта. В формате ГГГГ-ММ-ДД (например 2012-06-27). По техническим причинам пока не работает с типом "not", только с "is", "equal", "more" и "less".
	 */
	const FILTER_NAME_COMMENT_DATE = 'comment_date';
	/**
	 * translation_date	День когда была последняя правка переводов арта. В формате ГГГГ-ММ-ДД (например 2012-06-27). По техническим причинам пока не работает с типом "not", только с "is", "equal", "more" и "less".
	 */
	const FILTER_NAME_TRANSLATION_DATE = 'translation_date';
	/**
	 * tag_count	Количество тегов у этого арта. value должно быть целочисленным.
	 */
	const FILTER_NAME_TAG_COUNT = 'tag_count';
	/**
	 * date	День когда арт был добавлен или попал в основной список. В формате ГГГГ-ММ-ДД (например 2012-06-27). По техническим причинам пока не работает с типом "not", только с "is", "equal", "more" и "less".
	 */
	const FILTER_NAME_DATE = 'date';
	/**
	 * md5	md5 арта. value должно содержать 32-х символьный хешкод.
	 */
	const FILTER_NAME_MD5 = 'md5';
	/**
	 * id	Порядковый номер арта. value должно содержать целое число.
	 */
	const FILTER_NAME_ID = 'id';
	/**
	 * width	Ширина арта, в пикселях. value должно быть целочисленным.
	 */
	const FILTER_NAME_WIDTH = 'width';
	/**
	 *  height	Высота арта, в пикселях. value должно быть целочисленным.
	 */
	const FILTER_NAME_HEIGHT = 'height';
	/**
	 * weight	Размер файла арта, в байтах. value должно быть целочисленным.
	 */
	const FILTER_NAME_WEIGHT = 'weight';
	/**
	 * id_parent	Родитель, которому принадлежит этот арт как вариация. value должно быть целочисленным и являться номером арта. Сам арт с таким номером тоже будет в результатах выборки.
	 */
	const FILTER_NAME_PARENT_ID = 'id_parent';
	/**
	 * id_user	ID пользователя, добавившего арт. value должно быть целочисленным.
	 */
	const FILTER_NAME_USER_ID = 'id_user';
	/**
	 * user	Ник пользователя, добавившего арт. Строка
	 */
	const FILTER_NAME_USER = 'user';

	private $_filters;

	/**
	 * Позволяет выбирать записи по тем или иным свойствам
	 * @see ReadArtList::setFilter()
	 * @see http://wiki.4otaku.org/Api:%D0%A7%D1%82%D0%B5%D0%BD%D0%B8%D0%B5:%D0%90%D1%80%D1%82:%D0%A1%D0%BF%D0%B8%D1%81%D0%BE%D0%BA#.D0.9F.D0.BE.D0.BB.D0.B5_filter
	 */
	public function __construct() {
		$this->_filters = array();
	}

	/**
	 * @return array
	 */
	public function getResult() {
		return $this->_filters;
	}

	/**
	 * @param $field_name
	 * @param $value  Значение переданное в фильтр, то с чем надо сравнивать указанное в name значение арта
	 * @return $this
	 */
	public function is($field_name, $value) {
		$this->push($field_name, 'is', $value);

		return $this;
	}

	/**
	 * Алиас {@see is()}
	 * @param $field_name
	 * @param $value
	 * @return $this
	 */
	public function equal($field_name, $value) {
		$this->push($field_name, 'equal', $value);

		return $this;
	}

	/**
	 * @param $field_name
	 * @param $value
	 * @return $this
	 */
	public function more($field_name, $value) {
		$this->push($field_name, 'more', $value);

		return $this;
	}

	/**
	 * @param $field_name
	 * @param $value
	 * @return $this
	 */
	public function less($field_name, $value) {
		$this->push($field_name, 'less', $value);

		return $this;
	}

	/**
	 * @param $field_name
	 * @param $value
	 * @return $this
	 */
	public function not($field_name, $value) {
		$this->push($field_name, 'not', $value);

		return $this;
	}

	protected function push($field_name, $operation_type, $value) {
		$this->_filters[] = array(
			'name'  => $field_name,
			'type'  => $operation_type,
			'value' => $value,
		);
	}
}
