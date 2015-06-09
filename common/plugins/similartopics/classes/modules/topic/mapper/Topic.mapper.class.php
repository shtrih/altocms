<?php
/*---------------------------------------------------------------------------
 * @Project: Alto CMS
 * @Project URI: http://altocms.com
 * @Plugin Name: Similar Topics
 * @Plugin ID: similartopics
 * @Description: Similar Topics (defined by tags)
 * @Copyright: Alto CMS Team
 * @License: GNU GPL v2 & MIT
 *----------------------------------------------------------------------------
 */

class PluginSimilartopics_ModuleTopic_MapperTopic extends PluginSimilartopics_Inherits_ModuleTopic_MapperTopic {

    public function GetTopicsIdByTags($aTags, $aFilter = array()) {

        $sql = "
            SELECT
                tt.topic_id ARRAY_KEYS,
                    CASE WHEN t.topic_date_show IS NULL THEN t.topic_date_add ELSE t.topic_date_show END AS topic_date,
                    COUNT(topic_tag_id)
              FROM ?_topic_tag AS tt
              LEFT JOIN ?_topic AS t ON t.topic_id=tt.topic_id
            WHERE t.topic_id IS NOT NULL
              {AND tt.topic_tag_text IN (?a:tags)}
              {AND t.topic_id NOT IN (?a:exclude_topics)}
              {AND t.blog_id IN (?a:include_blogs)}
              {AND t.blog_id NOT IN (?a:exclude_blogs)}
            GROUP BY tt.topic_id
            ORDER BY COUNT(topic_tag_id) DESC, topic_date DESC
            {LIMIT ?d:limit}
        ";
        $aRows = $this->oDb->sqlQuery($sql, array(
                ':tags' => $aTags,
                ':exclude_topics' => (isset($aFilter['exclude_topics']) ? $aFilter['exclude_topics'] : DBSIMPLE_SKIP),
                ':include_blogs'  => (isset($aFilter['include_blogs']) && is_array($aFilter['include_blogs']) && count($aFilter['include_blogs'])) ? $aFilter['include_blogs'] : DBSIMPLE_SKIP,
                ':exclude_blogs'  => (isset($aFilter['exclude_blogs']) && is_array($aFilter['exclude_blogs']) && count($aFilter['exclude_blogs'])) ? $aFilter['exclude_blogs'] : DBSIMPLE_SKIP,
                ':limit' => !empty($aFilter['limit']) ? $aFilter['limit'] : DBSIMPLE_SKIP,
            ));
        if ($aRows) {
            $aResult = array_keys($aRows);
        } else {
            $aResult = array();
        }
        return $aResult;
    }

}

// EOF