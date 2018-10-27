<?php
/**
 * DokuWiki Plugin LineBreak2; render component
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Satoshi Sahara <sahara.satoshi@gmail.com>
 */

if(!defined('DOKU_INC')) die();

/**
 * The Renderer
 */
class renderer_plugin_linebreak2 extends Doku_Renderer_xhtml {

    function canRender($format) {
        return ($format == 'xhtml');
    }

    function reset() {
        $this->doc = '';
        $this->footnotes = array();
        $this->lastsec = 0;
        $this->store = '';
        $this->_counter = array();
    }

    /**
     * Render plain text data
     *
     * @param string $text  the text to display
     */
    function cdata($text) {

        static $renderer;

        if (!isset($renderer)) {
            $renderer = $this->loadHelper($this->getPluginName()) ?? false;
        }

        if ($renderer) {
            $this->doc .= $renderer->cdata($text);
        } else {
            parent::cdata($text);
        }
    }
}

