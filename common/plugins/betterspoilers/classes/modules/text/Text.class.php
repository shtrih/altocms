<?php

class PluginBetterspoilers_ModuleText extends PluginBetterspoilers_Inherit_ModuleText {

    /**
     * Загружает конфиг Jevix'а
     *
     * @param string $sType     Тип конфига
     * @param bool   $bClear    Очищать предыдущий конфиг или нет
     */
    public function LoadJevixConfig($sType = 'default', $bClear = true) {
        parent::LoadJevixConfig($sType, $bClear);
        if ('default' == $sType) {
            $this->oJevix->cfgAllowTags(array('spoiler', 'hide'));
            $this->oJevix->cfgAllowTagParams('spoiler', array('name'));
        }
    }

    /**
     * Разбирает текст и анализирует его на наличие сниппетов.
     * Если они найдены, то запускает хуки для их обработки.
     *
     * @version 0.1 Базовый функционал
     * @version 0.2 Добавлены блочный и шаблонный сниппеты
     *
     * @param $sText
     * @return string
     */
    public function SnippetParser($sText) {

        // Массив регулярки для поиска сниппетов
        $aSnippetRegexp = array(
            // Регулярка блочного сниппета. Сначала ищем по ней, а уже потом по непарному тегу
            // alto:name иначе блочный сниппет будет затираться поскульку регулярка одиночного сниппета
            // будет отхватывать первую его часть.
            '~<alto:(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:\'[^\']*\')|[^>\s]+))?)*)\s*>([.\s\S\r\n]*)</alto:\1>~Ui',
            // Регулярка строчного сниппета
            '~<alto:(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:\'[^\']*\')|[^>\s]+))?)*)\s*\/*>~Ui',
        );

        // Получим массив: сниппетов, их имён и параметров по каждой регклярке
        // Здесь получаем в $aMatches три/четыре массива из которых первым идет массив найденных сниппетов,
        // который позже будет заменён на результат полученный от хука. Вторым массивом идут имена
        // найденных сниппетов, которые будут использоваться для формирвоания имени хука.
        // Третим массивом будут идти параметры сниппетов. Если сниппет блочный, то четвертым параметром
        // будет текст-содержимое блока.
        foreach ($aSnippetRegexp as $sRegExp) {

            if (preg_match_all($sRegExp, $sText, $aMatches)) {

                // Данные для замены сниппетов на полученный код.
                $aReplaceData = array();

                /**
                 * @var int $k Порядковый номер найденного сниппета
                 * @var string $sSnippetName Имя (идентификатор) сниппета
                 */
                foreach ($aMatches[1] as $k => $sSnippetName) {

                    // Получим параметры в виде массива. Вообще-то их может и не быть воовсе,
                    // но мы всё-таки попробуем это сделать...
                    $aParams = array();
                    if (preg_match_all('~([a-zA-Z]+)\s*=\s*[\'"]([^\'"]+)[\'"]~Ui', $aMatches[2][$k], $aMatchesParams)) {
                        foreach ($aMatchesParams[1] as $pk => $sParamName) {
                            $aParams[$sParamName] = @$aMatchesParams[2][$pk];
                        }
                    }

                    // Добавим в параметры текст, который был в топике, вдруг какой-нибудь сниппет
                    // захочет с ним поработать.
                    $aParams['target_text'] = $sText;

                    // Если это блочный сниппет, то добавим в параметры еще и текст блока
                    $aParams['snippet_text'] = isset($aMatches[3][$k]) ? $aMatches[3][$k] : '';

                    // Добавим в параметры имя сниппета
                    $aParams['snippet_name'] = $sSnippetName;

                    // Попытаемся получить результат от обработчика
                    // Может сниппет уже был в обработке, тогда просто возьмем его из кэша
                    $sCacheKey = $sSnippetName . md5(serialize($aParams));
                    if (FALSE === ($sResult = E::ModuleCache()->GetLife($sCacheKey))) {

                        // Определим тип сниппета, может быть шаблонным, а может и исполняемым
                        // по умолчанию сниппет ссчитаем исполняемым. Если шаблонный, то его
                        // обрабатывает предопределенный хук snippet_template_type
                        $sHookName = 'snippet_' . $sSnippetName;
                        $sHookName = E::ModuleHook()->IsEnabled($sHookName)
                            ? 'snippet_' . $sSnippetName
                            : 'snippet_template_type';

                        // Установим хук
                        E::ModuleHook()->Run($sHookName, array(
                                'params' => &$aParams,
                                'result' => &$sResult,
                            ));

                        // Запишем результат обработки в кэш
                        E::ModuleCache()->SetLife($sResult, $sCacheKey);

                    }

                    $aReplaceData[$k] = is_string($sResult) ? $sResult : '';

                    // Заменяем только для конструкций, не являющимися спойлерами.
                    // Для спойлеров у нас свой хук, который возвращает полный текст с уже замененными спойлерами.
                    // Потому удаляем тексты со спойлерами из массивов для замены.
                    // В остальном, данный метод ничем не отличается от оригинального.
                    if ('spoiler' == $sSnippetName) {
                        $sText = $sResult;
                        unset($aReplaceData[$k], $aMatches[0][$k]);
                    }
                }

                // Произведем замену. Если обработчиков не было, то сниппеты
                // будут заменены на пустую строку.
                $sText = str_replace(
                    array_values($aMatches[0]),
                    array_values($aReplaceData),
                    $sText
                );
            }

        }

        return $sText;
    }
}