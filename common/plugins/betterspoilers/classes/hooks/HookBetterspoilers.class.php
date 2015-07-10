<?php

/**
 * Скрывает часть текста топика для пользователей у которых мало комментариев
 */
class PluginBetterspoilers_HookBetterspoilers extends Hook {

    /**
     * Регистрация хуков
     */
    public function RegisterHook() {
        $this->AddHook('topic_show', 'correctTopic');
        $this->AddHook('template_markitup_before_init', 'markitupBeforeInit');
        $this->AddHook('snippet_spoiler', 'snippetSpoiler');
    }

    /**
     * Хук, выполняющий смену содержимого топика
     *
     * @param array() $params
     */
    public function correctTopic($params) {
        /** @var ModuleTopic_EntityTopic $oTopic Открываемый топик */
        if (Config::Get('plugin.betterspoilers.use_hook')) {
            $oTopic = $params['oTopic'];
            $oTopic->setText(E::Module('PluginBetterspoilers_ModuleBetterspoilers')->MakeCorrection($oTopic->getText()));
        }
    }

    public function markitupBeforeInit($aParams) {
        return E::ModuleViewer()->Fetch('markitup_before_init.tpl', $aParams);
    }

    public function snippetSpoiler($aParams) {
        $sText = $aParams['params']['target_text'];

        $spoilers_positions = $this->getSpoilersPositions($sText);
        if ($spoilers_positions) {
            do {
                $end = reset($spoilers_positions);
                $start = key($spoilers_positions);
                $length = ($end - $start + 15 /* "</alto:spoiler>" length */);
                preg_match('|^<alto:spoiler(?:\s+title=[\'"]([^\'"]*)[\'"]\s*)?>(.+?)</alto:spoiler>$|is', substr($sText, $start, $length), $m);

                //$sSpoilerParsed = $oLocalViewer->Fetch(Plugin::GetTemplatePath(__CLASS__) . 'tpls/spoiler.tpl');
                $sSpoilerParsed = E::ModuleViewer()->GetLocalViewer()->Fetch(
                    'tpls/snippets/snippet.spoiler.tpl',
                    array(
                        'aParams' => array(
                            'title'        => $m[1],
                            'snippet_text' => $m[2],
                        )
                    )
                );
                $sText = substr_replace($sText, $sSpoilerParsed, $start, $length);
            } while($spoilers_positions = $this->getSpoilersPositions($sText));
        }
        $aParams['result'] = $sText;

        return $aParams['result'];
    }

    protected function getSpoilersPositions($sText) {
        $spoilers = array(
            'start' => array(),
            'end' => array(),
        );
        $offset_start = 0;
        $offset_end = 0;
        do {
            $posstart = strpos($sText, '<alto:spoiler', $offset_start);
            if (false !== $posstart) {
                $spoilers['start'][] = $posstart;
                $offset_start = $posstart + 7;
            }

            $posend = strpos($sText, '</alto:spoiler>', $offset_end);
            if (false !== $posend) {
                $spoilers['end'][] = $posend;
                $offset_end = $posend + 10;
            }
        } while(false !== $posstart || false !== $posend);
        // var_dump($spoilers);

        $haslower = function($numstart, $numend, array $array) {
            foreach($array as $v) {
                if ($v > $numstart && $v < $numend) {
                    return $v;
                }
            }

            return false;
        };

        $candidates_simple = $candidates_hard = array();
        foreach ($spoilers['start'] as $startpos) {
            foreach ($spoilers['end'] as $endpos) {
                if ($startpos < $endpos) {
                    $middle = $haslower($startpos, $endpos, $spoilers['start']);
                    if (false === $middle) {
                        // var_dump('между ' . $startpos . ' и ' . $endpos . ' ничего нет');
                        $candidates_simple[$startpos][$endpos] = $endpos;
                    }
                    elseif (!isset($candidates_simple[$startpos])) {
                        $candidates_hard[$startpos][$endpos] = $endpos;
                        // var_dump($middle . ' между ' . $startpos . ' и ' . $endpos);
                    }
                }
            }
        }
        $array_min = function (array $a) {
            $result = null;
            foreach ($a as $v) {
                if (null === $result)
                    $result = $v;
                else
                    $result = min($result, $v);
            }
            return $result;
        };
        $array_max = function (array $a) {
            $result = null;
            foreach ($a as $v) {
                if (null === $result)
                    $result = $v;
                else
                    $result = max($result, $v);
            }
            return $result;
        };
        // var_dump($candidates_simple, $candidates_hard);

        $simple = $hard = array();
        foreach ($candidates_simple as $posstart => $candidates) {
            $simple[$posstart] = $array_min($candidates);

            foreach ($candidates_hard as &$chard) {
                unset($chard[ $simple[$posstart] ]);
            }
        }

        foreach ($candidates_hard as $posstart => $chard) {
            $hard[$posstart] = $array_max($chard);
        }

        return $simple + $hard;
    }
}