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

class PluginSimilartopics_ModuleTopic extends PluginSimilartopics_Inherits_ModuleTopic {

    /** @var PluginSimilartopics_ModuleTopic_MapperTopic */
    protected $oMapper;

    /**
     * Returns tags array from topic
     *
     * @param object|int $xTopic
     *
     * @return array
     */
    protected function _getTagsFromTopic($xTopic) {

        if (is_object($xTopic)) {
            $aTopicTags = F::Str2Array($xTopic->getTags());
        } else {
            $aTopicTags = array();
            $iTopicId = intval($xTopic);
            $aTopics = $this->GetTopicsByArrayId(array($iTopicId));
            if ($aTopics) {
                $oTopic = reset($aTopics);
                $aTopicTags = F::Str2Array($oTopic->getTags());
            }
        }
        return $aTopicTags;
    }

    /**
     * @param object|int $xTopic
     * @param int|null $iLimit
     *
     * @return array
     */
    public function GetSimilarTopics($xTopic, $iLimit = null) {

        $aTopics = array();

        $aTopicTags = $this->_getTagsFromTopic($xTopic);
        if ($aTopicTags) {
            if (is_object($xTopic)) {
                $iTopicId = $xTopic->getId();
            } else {
                $iTopicId = intval($xTopic);
            }
            $aTopics = $this->GetSimilarTopicsByTags($aTopicTags, $iTopicId, $iLimit);
        }

        return $aTopics;
    }

    /**
     * @param array     $aTags
     * @param array|int $aExcludeTopics
     * @param int       $iLimit
     *
     * @return array
     */
    public function GetSimilarTopicsByTags($aTags, $aExcludeTopics = array(), $iLimit = null) {

        if (is_null($iLimit)) {
            $iLimit = 10;
        }

        $aTopicsId = $this->GetSimilarTopicsIdByTags($aTags, $aExcludeTopics, $iLimit);
        if ($aTopicsId) {
            $aTopics = E::ModuleTopic()->GetTopicsAdditionalData($aTopicsId);
        } else {
            $aTopics = array();
        }

        return $aTopics;
    }

    /**
     * Returns IDs of similar topics by tags
     *
     * @param array $aTags
     * @param array $aExcludeTopics
     * @param null  $iLimit
     *
     * @return array
     */
    public function GetSimilarTopicsIdByTags($aTags, $aExcludeTopics = array(), $iLimit = null) {

        $iUserId = E::UserId();
        $aFilter = array(
            'exclude_blogs' => E::ModuleBlog()->GetInaccessibleBlogsByUser(E::User()),
        );
        if ($aExcludeTopics) {
            $aFilter['exclude_topics'] = (is_array($aExcludeTopics) ? $aExcludeTopics : array(intval($aExcludeTopics)));
        }
        if ($iLimit) {
            $aFilter['limit'] = $iLimit;
        }

        $sCacheKey = 'similar_topics_id_' . $iUserId . '_' . serialize($aFilter);
        if (false === ($aTopicsId = E::ModuleCache()->Get($sCacheKey))) {
            $aTopicsId = $this->oMapper->GetTopicsIdByTags($aTags, $aFilter);
            E::ModuleCache()->Set($aTopicsId, $sCacheKey, array('content_new', 'content_update', 'blog_new', 'blog_update'), 'P1D');
        }

        return $aTopicsId;
    }

    /**
     * @param object|int $xTopic
     *
     * @return int
     */
    public function CountSimilarTopics($xTopic) {

        $aTopicTags = $this->_getTagsFromTopic($xTopic);
        $nTopics = $this->CountSimilarTopicsByTags($aTopicTags);

        return $nTopics ? $nTopics - 1 : 0;
    }

    /**
     * Counts similar topics by tags
     *
     * @param array $aTags
     *
     * @return int
     */
    public function CountSimilarTopicsByTags($aTags) {

        $aTopicsId = $this->GetSimilarTopicsIdByTags($aTags);
        return is_array($aTopicsId) ? count($aTopicsId) : 0;
    }

}

// EOF