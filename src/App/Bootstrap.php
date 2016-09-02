<?php
namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\NullLogger;

/**
 * Class Bootstrap
 *
 * This should be called to setup the App lib environment
 *
 * ~~~php
 *     \App\Bootstrap::execute();
 * ~~~
 *
 * I am using the composer.json file to auto execute this file using the following entry:
 *
 * ~~~json
 *   "autoload":  {
 *     "psr-0":  {
 *       "":  [
 *         "src/"
 *       ]
 *     },
 *     "files" : [
 *       "src/App/Bootstrap.php"    <-- This one
 *     ]
 *   }
 * ~~~
 *
 *
 * @author Michael Mifsud <info@tropotek.com>  
 * @link http://www.tropotek.com/  
 * @license Copyright 2015 Michael Mifsud  
 */
class Bootstrap
{

    /**
     * This will also load dependant objects into the config, so this is the DI object for now.
     *
     */
    static function execute()
    {
        if (version_compare(phpversion(), '5.4.0', '<')) {
            // php version must be high enough to support traits
            throw new \Exception('Your PHP5 version must be greater than 5.4.0 [Curr Ver: '.phpversion().']');
        }
        
        // Do not call \Tk\Config::getInstance() before this point
        $config = Factory::getConfig();
        
        // Include any config overriding settings
        include($config->getSrcPath() . '/config/config.php');

        \Tk\Uri::$BASE_URL_PATH = $config->getSiteUrl();
        
        if ($config->has('date.timezone')) {
            ini_set('date.timezone', $config->get('date.timezone'));
        }

        /**
         * This makes our life easier when dealing with paths. Everything is relative
         * to the application root now.
         */
        chdir($config->getSitePath());

        // This maybe should be created in a Factory or DI Container....
        $config['log'] = new NullLogger();
        if (is_readable($config['system.log.path'])) {
            ini_set('error_log', $config['system.log.path']);
            $logger = new Logger('system');
            $handler = new StreamHandler($config['system.log.path'], $config['system.log.level']);
            $formatter = new \Tk\Log\MonologLineFormatter();
            $formatter->setScripTime($config->getScriptTime());
            $handler->setFormatter($formatter);
            $logger->pushHandler($handler);
            $config['log'] = $logger;
        }
        
        
        // * Logger [use error_log()]
        \Tk\ErrorHandler::getInstance($config->getLog());
        
        // Return if using cli (Command Line)
        if ($config->isCli()) {
            return $config;
        }

        // --- HTTP only bootstrapping from here ---
        
        // * Request
        Factory::getRequest();
        // * Cookie
        Factory::getCookie();
        // * Session    
        Factory::getSession();

        // initalise Dom Loader
        \App\Factory::getDomLoader();

        // Initiate the default database connection
        \App\Factory::getDb();
        // Import settings from DB
        $config->replace(\Ts\Db\Data::create());
        
        // Init the event dispatcher
        Factory::getEventDispatcher();

        // Init the plugins
        \Tk\Plugin\Factory::getInstance($config);

        return $config;
    }

}

// called by autoloader, see composer.json -> "autoload" : files []...
Bootstrap::execute();

