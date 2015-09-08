<?php

class PluginBetterspoilers_HookBetterspoilers extends Hook {

    /**
     * Регистрация хуков
     */
    public function RegisterHook() {
        $this->AddHook('template_markitup_before_init', 'markitupBeforeInit');
        $this->AddHook('snippet_spoiler', 'snippetSpoiler');
    }

    public function markitupBeforeInit($aParams) {
        return E::ModuleViewer()->Fetch('markitup_before_init.tpl', $aParams);
    }

    public function snippetSpoiler($aParams) {
        $sText = $aParams['params']['target_text'];

        $aSpoilersPositions = $this->getSpoilersPositions($sText);
        if ($aSpoilersPositions) {
            do {
                $iEndPos = reset($aSpoilersPositions);
                $iStartPos = key($aSpoilersPositions);
                $iLength = ($iEndPos - $iStartPos + 15 /* 15 — это длина </alto:spoiler> */);
                preg_match('~^<alto:spoiler(?:\s*(\w+)=(?:["]([^"]*)["]|[\']([^\']*)[\'])\s*)*>(.*?)</alto:spoiler>$~is', substr($sText, $iStartPos, $iLength), $aMatches);
                list( , $sAttr, $sValue1, $sValue2, $sContent) = $aMatches;

                $sSpoilerParsed = E::ModuleViewer()->GetLocalViewer()->Fetch(
                    'tpls/snippets/snippet.spoiler.tpl',
                    array(
                        'aParams' => array(
                            'title' =>
                                ('title' == $sAttr)
                                    ? ($sValue1 ?: $sValue2)
                                    : '',
                            'snippet_text' => $sContent,
                        )
                    )
                );
                $sText = substr_replace($sText, $sSpoilerParsed, $iStartPos, $iLength);
            } while($aSpoilersPositions = $this->getSpoilersPositions($sText));
        }
        $aParams['result'] = $sText;

        return $aParams['result'];
    }

    protected function getSpoilersPositions($sText) {
        static $sTagStart = '<alto:spoiler';
        static $sTagEnd = '</alto:spoiler>';
        $aSpoilersPos = array(
            'start' => array(),
            'end' => array(),
        );
        $iOffsetStart = 0;
        $iOffsetEnd = 0;
        do {
            $iPosStart = strpos($sText, $sTagStart, $iOffsetStart);
            if (false !== $iPosStart) {
                $aSpoilersPos['start'][] = $iPosStart;
                $iOffsetStart = $iPosStart + strlen($sTagStart);
            }

            $iPosEnd = strpos($sText, $sTagEnd, $iOffsetEnd);
            if (false !== $iPosEnd) {
                $aSpoilersPos['end'][] = $iPosEnd;
                $iOffsetEnd = $iPosEnd + strlen($sTagEnd);
            }
        } while(false !== $iPosStart || false !== $iPosEnd);

        $fHasLower = function($numstart, $numend, array $array) {
            foreach($array as $v) {
                if ($v > $numstart && $v < $numend) {
                    return $v;
                }
            }

            return false;
        };

        $aCandidatesSimple = $aCandidatesNested = array();
        foreach ($aSpoilersPos['start'] as $startpos) {
            foreach ($aSpoilersPos['end'] as $endpos) {
                if ($startpos < $endpos) {
                    $middle = $fHasLower($startpos, $endpos, $aSpoilersPos['start']);
                    if (false === $middle) {
                        // var_dump('между ' . $startpos . ' и ' . $endpos . ' ничего нет');
                        $aCandidatesSimple[$startpos][$endpos] = $endpos;
                    }
                    elseif (!isset($aCandidatesSimple[$startpos])) {
                        $aCandidatesNested[$startpos][$endpos] = $endpos;
                        // var_dump($middle . ' между ' . $startpos . ' и ' . $endpos);
                    }
                }
            }
        }
        $fArrayMin = function (array $a) {
            $result = null;
            foreach ($a as $v) {
                if (null === $result)
                    $result = $v;
                else
                    $result = min($result, $v);
            }
            return $result;
        };
        $fArrayMax = function (array $a) {
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

        $aResultSimple = $aResultNested = array();
        foreach ($aCandidatesSimple as $iPosStart => $candidates) {
            $aResultSimple[$iPosStart] = $fArrayMin($candidates);

            foreach ($aCandidatesNested as &$chard) {
                unset($chard[ $aResultSimple[$iPosStart] ]);
            }
        }

        foreach ($aCandidatesNested as $iPosStart => $chard) {
            $aResultNested[$iPosStart] = $fArrayMax($chard);
        }

        return $aResultSimple + $aResultNested;
    }
}
