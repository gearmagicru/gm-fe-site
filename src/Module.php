<?php
/**
 * Модуль веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Frontend\Site;

use Gm;
use Gm\View\ClientScript;

/**
 * Основной модуль веб-сайта.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Frontend\Site
 */
class Module extends \Gm\Mvc\Module\Module
{
    /**
     * {@inheritdoc}
     */
    public string $id = 'gm.fe.site';

    /**
     * {@inheritdoc}
     */
    public function getThemePath(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     * 
     * Т.к. разрешения для роли пользователя на доступ к модулю не устанавливаются, а 
     * есть разделение по доступу для `BACKEND` и `FRONTEND`, то определяем
     * принадлежность контроллеров, которые будут доступны.
     */
    public function controllerMap(): array
    {
        if (IS_FRONTEND) return ['*' => 'IndexController'];

        return ['info' => 'Info'];
    }

    /**
     * Возвращает заголовок документа веб-страницы - тег "<title>". 
     * 
     * Шорткод "[html-title]".
     * 
     * @param array $attr Атрибуты шорткода.
     * 
     * @return string
     */
    public function htmlTitleShortcode(array $attr = []): string
    {
        // если маршрутизатор не определил в запросе модуль
        if (!Gm::alias('@hasMatch'))
            $title = Gm::$app->page->getTitle();
        else
            $title = Gm::$app->page->title;
        return Gm::$app->page->script->renderTitle($title, Gm::$app->page->titlePattern);
    }

    /**
     * Возвращает метатеги веб-страницы - теги "<meta>". 
     * 
     * Шорткод "[html-meta]".
     * 
     * @param array $attr Атрибуты шорткода.
     * 
     * @return string
     */
    public function htmlMetaShortcode(array $attr = []): string
    {
        // если маршрутизатор не определил в запросе модуль
        if (!Gm::alias('@hasMatch')) {
            Gm::$app->page->registerMeta();
        }
        return Gm::$app->page->script->renderMeta();
     }

    /**
     * Возвращает скрипты и стили подключаемые в заголовке веб-страницы. 
     * 
     * Шорткод "[html-head]".
     * 
     * @param array $attr Атрибуты шорткода.
     * 
     * @return string
     */
     public function htmlHeadShortcode(array $attr = []): string
     {
         return Gm::$app->page->script->render(ClientScript::POS_HEAD);
     }

    /**
     * Возвращает скрипты и стили подключаемые в начале тега "<body>" веб-страницы. 
     * 
     * Шорткод "[html-begin]".
     * 
     * @param array $attr Атрибуты шорткода.
     * 
     * @return string
     */
     public function htmlBeginShortcode(array $attr = []): string
     {
         return Gm::$app->page->script->render(ClientScript::POS_BEGIN);
     }

    /**
     * Возвращает скрипты и стили подключаемые  в конце тега "<body>" веб-страницы. 
     * 
     * Шорткод "[html-end]".
     * 
     * @param array $attr Атрибуты шорткода.
     * 
     * @return string
     */
     public function htmlEndShortcode(array $attr = []): string
     {
         return Gm::$app->page->script->render(ClientScript::POS_END);
     }

    /**
     * Возвращает скрипты после успешной загрузки веб-страницы. 
     * 
     * Шорткод "[html-load]".
     * 
     * @param array $attr Атрибуты шорткода.
     * 
     * @return string
     */
     public function htmlLoadShortcode(array $attr = []): string
     {
         return Gm::$app->page->script->render(ClientScript::POS_LOAD);
     }

    /**
     * Возвращает скрипты после того, как все ресурсы веб-страницы загружены. 
     * 
     * Шорткод "[html-ready]".
     * 
     * @param array $attr Атрибуты шорткода.
     * 
     * @return string
     */
     public function htmlReadyShortcode(array $attr = []): string
     {
         return Gm::$app->page->script->render(ClientScript::POS_READY);
     }

    /**
     * Возвращает контент виджета.
     * 
     * Шорткод "[widget]".
     * 
     * @param array $attr Атрибуты шорткода.
     * 
     * @return string
     */
    public function widgetShortcode(array $attr = []): string
    {
        /** @var \Gm\View\BaseWidget|null $widget  */
        $widget = Gm::$app->widgets->get($attr['wid'], ['attributes' => $attr]);
        return $widget ? $widget->renderMe() : '';
    }

    /**
     * Проверяет условие заданное атрибутами шорткода. Если верно условие, 
     * возвращает контент шорткода.
     * 
     * Шорткод "[if]".
     * 
     * @param array $attr Атрибуты шорткода.
     *    Условие задаётся атрибутами:
     *    - "select", значение сравнения, имеет вид: "true", "false", "1", "{1,2,3}", "!{name,name1}";
     *    - "for", название условия:
     *        - "main", является URL-адрес корневым;
     *        - "categorySlug", слаг категории статьи;
     *        - "categoryId", идентификатор категории статьи;
     *        - "articleSlug", слаг статьи (записи);
     *        - "articleId", идентификатор статьи (записи);
     *        - "route", маршрут;
     *        - "uri", URI-адрес;
     *        - "serverName", имя сервера;
     *        - "serverAddress", IP-адрес сервера;
     *        - "clientAddress", IP-адрес клиента;
     *        - "status", статус перенаправленного запроса;
     * 
     * @return bool Если значение `true`, то условие выполняется.
     */
    public function ifShortcode(array $attr = []): bool
    {
        $select = $attr['select'] ?? null;
        $for    = $attr['for'] ?? null;
        if (is_null($select) || is_null($for)) return false;

        $isNot   = false;
        $compare = false;
        if ($select[0] === '!') {
            $isNot = true;
            $select = trim($select, '!');
        }
        if ($select === 'true')
            $select = true;
        else
        if ($select === 'false')
            $select = false;
        else
        if ($select[0] === '{') {
            $select = trim($select, '{}');
            $select = explode(',', $select);
        }

        switch ($for) {
            // является ли URL-адрес корневым
            case 'main':
                $compare = Gm::$app->urlManager->isRootUrl == $select;
                break;

            // слаг категории статьи
            case 'categorySlug':
                $category = $this->page->findCategory();
                if ($category) {
                    if (is_array($select))
                        $compare = in_array($category->slugPath, $select);
                    else
                        $compare = $category->slugPath == $select;
                } else
                    $compare = false;
                break;
            // идентификатор категории статьи
            case 'categoryId':
                $category = $this->page->findCategory();
                if ($category) {
                    if (is_array($select))
                        $compare = in_array($category->id, $select);
                    else
                        $compare = $category->id == $select;
                } else
                    $compare = false;
                break;
            // слаг статьи (записи)
            case 'articleSlug':
                $article = $this->page->findArticle();
                if ($article) {
                    if (is_array($select))
                        $compare = in_array($article->slug, $select);
                    else
                        $compare = $article->slug == $select;
                } else
                    $compare = false;
                break;
            // идентификатор статьи (записи)
            case 'articleId':
                $article = Gm::$app->page->findArticle();
                if ($article) {
                    if (is_array($select))
                        $compare = in_array($article->id, $select);
                    else
                        $compare = $article->id == $select;
                } else
                    $compare = false;
                break;
            // маршрут "path1/path2"
            case 'route':
                $compare = $select == Gm::alias('@route');
                break;
            // URI-адрес "path/filename"
            case 'uri':
                $compare = $select == Gm::alias('@requestUri');
                break;
            // имя сервера
            case 'serverName':
                $compare = $select == $_SERVER['SERVER_NAME'] ?? null;
                break;
            // IP-адрес сервера
            case 'serverAddress':
                $compare = $select == $_SERVER['SERVER_ADDR'] ?? null;
                break;
            // IP-адрес клиента
            case 'clientAddress':
                $compare = $select == $_SERVER['REMOTE_ADDR'] ?? null;
                break;
            // статус перенаправленного запроса
            case 'status':
                $compare = $select == $_SERVER['REDIRECT_STATUS'] ?? null;
                break;
        }
        return $isNot ? !$compare : $compare;
    }
}
