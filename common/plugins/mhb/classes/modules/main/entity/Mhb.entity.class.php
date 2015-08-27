<?php

/*
 *
 * Project Name : Must Have Blogs
 * Copyright (C) 2011 Alexei Lukin. All rights reserved.
 * License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 */


class PluginMhb_ModuleMain_EntityMhb extends Entity
{
    public function getId() {
        return $this->getProp('mhb_id');
    }

    public function setId($iMhbId) {
        return $this->setProp('mhb_id', $iMhbId);
    }
}
