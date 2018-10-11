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
        $controller->register_hook(
            'PARSER_METADATA_RENDER', 'AFTER', $this, '_modifyTableOfContents'
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

    /**
     * PARSER_METADATA_RENDER
     *
     * remove wiki markup from metadata stored in description_tableofcontents
     */
    function _modifyTableOfContents(Doku_Event $event) {
        if (!$this->getConf('header_formatting')) return;

        $toc =& $event->data['current']['description']['tableofcontents'];
        if (!isset($toc)) return;

        $headers = [];
        foreach ($toc as &$item) {
            $item['_text'] = $item['title'];
            $html = substr($this->render_text($item['title']), 4, -5); // drop p tags
            $text = trim(htmlspecialchars_decode(strip_tags($html), ENT_QUOTES));
            $text = str_replace(DOKU_LF, '', $text); // remove any linebreak
            $item['title'] = $text;
            $item['hid'] = sectionID($text, $headers); // ensure unique hid
        }
        unset($item);
    }
}
