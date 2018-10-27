<?php
/**
 * DokuWiki Plugin LineBreak2; action component
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Satoshi Sahara <sahara.satoshi@gmail.com>
 */

if(!defined('DOKU_INC')) die();

class action_plugin_linebreak2 extends DokuWiki_Action_Plugin {

    /**
     * Register event handlers
     */
    function register(Doku_Event_Handler $controller) {
        $controller->register_hook(
            'PARSER_CACHE_USE', 'BEFORE', $this, '_clearVolatileConf'
        );
    }

    /**
     * PARSER_CACHE_USE
     * clear volatile config settings that have set in syntax component
     */
    function _clearVolatileConf(Doku_Event $event) {
        global $conf;
        unset($conf['plugin']['linebreak2']['_linebreak']);
    }
}
