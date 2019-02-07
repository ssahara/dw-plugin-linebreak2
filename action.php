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
     * NOTE: common plugin function render_text()
     * output text string through the parser, allows DokuWiki markup to be used
     * very ineffecient for small pieces of data - try not to use
     */
    function _modifyTableOfContents(Doku_Event $event) {
        if (!$this->getConf('header_formatting')) return;

        $toc =& $event->data['current']['description']['tableofcontents'] ?? [];
        if (!isset($toc)) return;

        $headers = [];
        foreach ($toc as &$item) {
            $item['_text'] = $item['title'];
            $item['_html'] = substr($this->render_text($item['_text']), 5, -6); // drop p tags
            $text = htmlspecialchars_decode(strip_tags($item['_html']), ENT_QUOTES);
            $item['title'] = str_replace(DOKU_LF, '', trim($text)); // remove any linebreak
            $item['hid'] = sectionID($item['title'], $headers); // ensure unique hid
        }
        unset($item);

        // set pagename
        if (isset($event->data['persistent']['title'])) {
            $event->data['current']['title'] = $event->data['persistent']['title'];
        } elseif (count($toc) && $toc[0]['title']) {
            $event->data['current']['title'] = $toc[0]['title'];
        }

    }
}
