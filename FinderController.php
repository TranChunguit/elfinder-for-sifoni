<?php

namespace App\Controller\admin;

use elFinder;
use elFinderConnector;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as LocalAdapter;

class FinderController extends AuthedController {
    public function finderAccess($attr, $path, $data, $volume) {
        return strpos(basename($path), '.') === 0 ? !($attr == 'read' || $attr == 'write') : null;
    }

    private function getOpts() {
        $root_dir = ROOT_PATH . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'upload';

        $opts = [
            'locale' => 'en_US.UTF-8',
            'debug' => $this->app['debug'],
            'roots' => [
                [
                    'driver' => 'Flysystem',
                    'path' => '/',
                    'URL' => '/upload',
                    'filesystem' => new Filesystem(new LocalAdapter($root_dir)),
                    'icon' => '',
                    'imgLib' => 'auto',
                    'tmbPath' => $root_dir . DIRECTORY_SEPARATOR . '.tmb',
                    'tmbBgColor' => 'transparent',
                    'tmbURL' => '/upload/.tmb/',
                    'tmbCrop' => true,
                    'tmbSize' => 48,
                    'accessControl' => array($this, 'finderAccess'),
                    'uploadAllow' => array('image'),
                    'uploadDeny' => array(),
                    'uploadOrder' => array('allow', 'deny'),
                    'disabled' => array('mkfile', 'rename', 'archive', 'extract')
                ],
            ]
        ];

        return $opts;
    }

    private function allowAccess() {
        return true;
    }

    public function connectorAction() {
        if (!$this->allowAccess()) {
            $this->response->setStatusCode(403);
            return $this->response;
        }

        $connector = new \elFinderConnector(new elFinder($this->getOpts()));
        $connector->run();
    }

    public function browseAction() {
        $data = [];

        return $this->render('admin/utils/finder.html.twig', $data);
    }
}