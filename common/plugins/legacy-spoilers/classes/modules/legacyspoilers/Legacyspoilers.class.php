<?php

/**
 * Class PluginLegacyspoilers_ModuleLegacyspoilers
 */
class PluginLegacyspoilers_ModuleLegacyspoilers extends Module {

	/**
	 * Абстрактный метод инициализации модуля, должен быть переопределен в модуле
	 */
	public function Init() {
	}

	/**
	 * Смена содержимого на спойлер. Для старых тегов <spoiler> и <hide>
	 *
	 * @param string $sText    Редактируемый текст
	 * @return string
	 */
	public function MakeCorrection($sText) {
		$aMatches = array();
		/*
		preg_match_all('|<spoiler(?:\s+name="([^"]+)"\s*)?>(.+?)<\/spoiler>|is', $sText, $aMatches, PREG_SET_ORDER);
		foreach($aMatches as $Match){
			$oLocalViewer = $this->Viewer_GetLocalViewer();
			$oLocalViewer->Assign('sText', $Match[2]);
			$oLocalViewer->Assign('sTitle', $Match[1]);
			$sText = str_replace($Match[0], $oLocalViewer->Fetch(Plugin::GetTemplatePath(__CLASS__) . 'spoiler.tpl'), $sText);
		}
		*/
/*		$before = memory_get_usage();
		$start = microtime();*/
		$get_spoilers_positions = function ($sText) {
			$spoilers = array(
				'start' => array(),
				'end' => array(),
			);
			$offset_start = 0;
			$offset_end = 0;
			do {
				$posstart = strpos($sText, '<spoiler', $offset_start);
				if (false !== $posstart) {
					$spoilers['start'][] = $posstart;
					$offset_start = $posstart + 7;
				}

				$posend = strpos($sText, '</spoiler>', $offset_end);
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
			// var_dump($candidates_simple, $candidates_hard);

			$simple = $hard = array();
			foreach ($candidates_simple as $posstart => $candidates) {
				$simple[$posstart] = min($candidates);

				foreach ($candidates_hard as &$chard) {
					unset($chard[ $simple[$posstart] ]);
				}
			}

			foreach ($candidates_hard as $posstart => $chard) {
				$hard[$posstart] = max($chard);
			}

			return $simple + $hard;
		};

		$spoilers_positions = $get_spoilers_positions($sText);
		if ($spoilers_positions) {
			do {
				$end = reset($spoilers_positions);
				$start = key($spoilers_positions);
				$length = ($end - $start + 10 /* "</spoiler>" length */);
				preg_match('#^<spoiler(?:\s+name="([^"]+|)"\s*)?>(.+?)</spoiler>$#is', substr($sText, $start, $length), $m);

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
			} while($spoilers_positions = $get_spoilers_positions($sText));
		}
		/*
		$after = memory_get_usage();
		$diff = ($after - $before) / 1024 / 1024;
		$end = microtime();
		$diff_time = $end - $start;
		var_dump(sprintf("Память: было %0.2f, стало %0.2f, разница %0.2f\nВремя: %0.4f", $before / 1024 / 1024, $after / 1024 / 1024, $diff, $diff_time / 100));
*/
		preg_match_all('#<hide>(.+?|)</hide>#is', $sText, $aMatches, PREG_SET_ORDER);
		foreach($aMatches as $Match) {
			$sText = str_replace($Match[0], '<span class="hidetext">'.$Match[1].'</span>', $sText);
		}

		// Если остались теги <hide>, удаляем их
		$sText = str_replace(array('<hide>', '</hide>'), '', $sText);

		preg_match_all('|(?!<a[^>]*>)\s*<img src="'.str_replace('.', '\.', Config::Get('path.root.web')).'(/uploads/images/[^"]+)\.([^"]+)"[^>]*>\s*(?!</a>)|is', $sText, $aMatches, PREG_SET_ORDER);

		foreach ($aMatches as $Match) {
			$filename   = $Match[1] . '_full.' . $Match[2];
			$file       = rtrim(Config::Get('path.root.server'), "/") . $Match[1] . '_full.' . $Match[2];
			$dimensions = E::ModuleCache()->Get('fullimg_' . md5($filename));
			if (!$dimensions && file_exists($file)) {
				$size       = getimagesize($file);
				$dimensions = $size[0] . 'x' . $size[1];
				E::ModuleCache()->Set($dimensions, 'fullimg_' . md5($filename), array(), 60 * 60 * 24 * 30);
			}
			if ($dimensions) {
				$filesize = $this->getHumanizedSize(filesize($file));
				$sText    = str_replace($Match[0], '<a class="unfoldable" href="' . $filename . '" rel="' . $dimensions . '" title="' . $filesize . '">' . $Match[0] . '</a>', $sText);
			}
		}

		return $sText;
	}

	protected function getHumanizedSize($iSizeBytes) {
		$aSizes = array('B', 'KB', 'MB', 'GB', 'TB');
		$i = 0;
		while ($iSizeBytes > 1000) {
			$iSizeBytes /= 1024;
			$i++;
		}
		return sprintf('%.2f %s', $iSizeBytes, $aSizes[$i]);
	}
}
