<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initLogger()
    {
        //$writer = new Zend_Log_Writer_Stream("../logs/application.log");
        //$logger = new Zend_Log($writer);
        //Zend_Registry::set('logger', $logger);
    }
    protected function _initViewHelpers()
    {
        $view = new Zend_View();
        $view->doctype('XHTML1_STRICT');
        $view->headMeta()->appendHttpEquiv('content-type', 'text/html:charset=utf-8')
                         ->appendName('description', 'ISKCON Congregation Database-Mumbai Chowpatty');
        $view->headTitle('Radha Gopinath Mandir');
        
        // Create new view object if not already instantiated 
        //$view = new Zend_View();
        Zend_Dojo::enableView($view);
        $view->dojo()
            ->addStyleSheetModule('dijit.themes.tundra')
            ->setDjConfigOption('usePlainJson', true)
            ->disable();
        
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
         'ViewRenderer'
        );
        $viewRenderer->setView($view);
    }

    protected function _initSessions() {
        //$this->bootstrap('session');
    }   
     
    protected function _initAppAutoload()
    {
        $loader = new Zend_Application_Module_Autoloader(array('namespace' => 'Application','basePath' => dirname(__file__), ));
        return $loader;
        /*            
        $autoloader = new Zend_Application_Module_Autoloader(array(
        'namespace' => 'Application',
        'basePath' => APPLICATION_PATH));
        return $autoloader;
        */
    }
    
    protected function _initDatabase ()
    {
        $session = new Zend_Session_Namespace();
        Zend_Registry::set('session', $session);
      
        $resource = $this->getPluginResource('multidb');
        $resource->init();
 
        Zend_Registry::set('db1', $resource->getDb('db1'));
        Zend_Registry::set('db2', $resource->getDb('db2'));
        Zend_Registry::set('db3', $resource->getDb('db3'));
    }
}
