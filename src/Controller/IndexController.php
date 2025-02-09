<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Frontend\Site\Controller;

use Gm;
use Gm\Mvc\Controller\Controller;

/**
 * Основной контроллер сайта.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Frontend\Site\Controller
 */
class IndexController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public bool $sendCsrfToken = false;

    /**
     * {@inheritdoc}
     */
    public bool $enableCsrfValidation = false;

    /**
     * Действие "index" выводиь главную страницу сайта.
     * 
     * @return string
     */
    public function indexAction(): string
    {
        /** @var \Gm\Site\Page $page */
        $page = Gm::$app->page;
        $page->registerMeta();
        $page->registerScripts();

        // если главная страница
        if ($page->isHome()) {
            return $this->render('pages/main');
        }

        // если страница опубликована
        if ($page->isPublished()) {
            return $this->render($page->getTemplate() ?: 'pages/404');
        }

        // если страница не найдена
        $this->layout = 'default';
        return $this->render('pages/404');
    }
}
