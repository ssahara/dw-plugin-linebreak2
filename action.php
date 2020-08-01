<?php

use dokuwiki\Extension\EventHandler;
use dokuwiki\Extension\Event;

/**
 * DokuWiki Plugin LineBreak2; action component
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Satoshi Sahara <sahara.satoshi@gmail.com>
 */
class action_plugin_linebreak2 extends DokuWiki_Action_Plugin
{
    /**
     * Register event handlers
     */
    public function register(EventHandler $controller)
    {
        $controller->register_hook(
            'PARSER_CACHE_USE', 'BEFORE', $this, '_clearVolatileConf'
        );
        $controller->register_hook(
            'PLUGIN_ACTIONRENDERER_METHOD_EXECUTE', 'BEFORE', $this, 'xhtmlRenderer'
        );
        
    }

    /**
     * PARSER_CACHE_USE
     * clear volatile config settings that have set in syntax component
     */
    public function _clearVolatileConf(Event $event)
    {
        global $conf;
        unset($conf['plugin']['linebreak2']['_linebreak']);
    }


    /**
     * PLUGIN_ACTIONRENDERER_METHOD_EXECUTE
     * render plain text data
     */
    public function xhtmlRenderer(Event $event)
    {
        static $altRenderer;
        isset($altRenderer) || $altRenderer = $this->loadHelper($this->getPluginName());

        if ($event->data['method'] !== 'cdata') return;

     // $method    =& $event->data['method'];    // string
        $renderer  =& $event->data['renderer'];  // object
        $text      =& $event->data['arguments'][0];

        $renderer->doc .= $altRenderer->cdata($text);
        $event->preventDefault();
    }
}
