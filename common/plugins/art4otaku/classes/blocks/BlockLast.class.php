<?php
/**
 * Created by PhpStorm.
 * User: shtrih
 * Date: 08.12.14
 * Time: 16:42
 */
class PluginArt4otaku_BlockLast extends Block {
	/**
	 * Метод запуска обработки блока.
	 * Его необходимо определять в конкретном блоге.
	 *
	 */
	public function Exec() {
		F::IncludeFile(F::GetPluginsDir() . 'art4otaku/lib/4otakuAPIClient/Client4otaku/autoloader.php');

		if (!($aArtList = $this->Cache_Get('art4otaku'))) {
			$artList = new Client4otaku\ReadArtList();
			$artList
				//->setSortBy('random')
				->setPerPage(Config::Get('art4otaku.slider.limit'))
				->getFilter()
				->not(Client4otaku\FilterList::FILTER_NAME_TAG, 'nsfw')
				->is(Client4otaku\FilterList::FILTER_NAME_STATE, 'approved')
			;

			try {
				$response = $artList->getResponse();
				$aArtList = $response->data;

				// Кешируем массив топиков на один час
				$this->Cache_Set($aArtList, 'art4otaku', array(), 60 * 10);
			}
			catch (\RequestException $e) {
				$this->Viewer_Assign('sError', $e->getMessage());
			}
			catch (\Client4otaku\ReadException $e) {
				$this->Viewer_Assign('sError', $e->getMessage());
			}
		}
		$this->Viewer_Assign('aItems', $aArtList);
	}

}