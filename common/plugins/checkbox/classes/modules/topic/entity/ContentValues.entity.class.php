<?php

class PluginCheckbox_ModuleTopic_EntityContentValues extends PluginCheckbox_Inherits_ModuleTopic_EntityContentValues {

    public function getValueExploded() {
        if ('checkbox' == $this->getFieldType())
           return array_filter(array_map('trim', explode(',', $this->getValue())));

        return $this->getValue();
    }

    public function setValue($xValue) {
        $this->_callTransformValue(__FUNCTION__, $xValue);
    }

    public function setValueSource($xValue) {
        $this->_callTransformValue(__FUNCTION__, $xValue);
    }

    private function _callTransformValue($sMethod, $xArgument = null) {
        if ('checkbox' == $this->getFieldType())
            $xArgument = implode(',', array_filter(array_map('trim', (array)$xArgument)));

        parent::$sMethod($xArgument);
    }
}
