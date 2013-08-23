<?php

class ManageController extends \VJ\Controller\Basic
{

    public function initialize()
    {

        // TODO: Check privilege

        $this->view->CURRENT_ACTION = $this->dispatcher->getActionName();

        $this->view->MANAGE_MENU = [

            ['type' => 'link', 'href' => '/manage/statistics', 'text' => 'Statistics', 'action' => 'statistics'],
            ['type' => 'link', 'href' => '/', 'text' => 'Vijos homepage', 'action' => 'home'],
            ['type' => 'link', 'href' => '/user/logout', 'text' => 'Logout', 'action' => 'logout'],
            ['type' => 'headline', 'text' => 'System'],
            ['type' => 'link', 'href' => '/', 'text' => 'Error center', 'action' => 'error'],
            ['type' => 'link', 'href' => '/', 'text' => 'Cache', 'action' => 'cache'],
            ['type' => 'headline', 'text' => 'Settings'],
            ['type' => 'link', 'href' => '/', 'text' => 'ACL', 'action' => 'acl'],
            ['type' => 'link', 'href' => '/', 'text' => 'RP credit', 'action' => 'rp'],
            ['type' => 'headline', 'text' => 'Problem set'],
            ['type' => 'link', 'href' => '/', 'text' => 'Manage Problems', 'action' => 'problem'],
            ['type' => 'link', 'href' => '/', 'text' => 'Manage Data', 'action' => 'problemdata'],
            ['type' => 'headline', 'text' => 'Other'],
            ['type' => 'link', 'href' => '/', 'text' => 'Manage Team', 'action' => 'team'],
            ['type' => 'link', 'href' => '/', 'text' => 'Manage App', 'action' => 'app'],
            ['type' => 'link', 'href' => '/', 'text' => 'Manage Contest', 'action' => 'contest'],
        ];

    }

    public function statisticsAction()
    {

        $this->view->setVars([
            'PAGE_CLASS' => 'manage_statistics page_manage',
            'TITLE'      => gettext('Statistics')
        ]);

    }

    public function indexAction()
    {

        global $__CONFIG;

        header('Location: '.$__CONFIG->Misc->basePrefix.'/manage/statistics');
        exit();
    }

}