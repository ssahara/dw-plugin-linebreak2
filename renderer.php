<?php
/**
 * DokuWiki Plugin LineBreak2; render component
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Chris Smith <chris@jalakai.co.uk>
 * @author     Satoshi Sahara <sahara.satoshi@gmail.com>
 */

if(!defined('DOKU_INC')) die();
if(!defined('DOKU_LF')) define ('DOKU_LF',"\n");

//require_once DOKU_INC . 'inc/parser/xhtml.php';

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

    function cdata($text) {
        global $INFO;

        // Markdown linebreak syntax
        // force newline if found more than two spaces at the end of line
        if ($this->getConf('markdown')) {
            $html = preg_replace('/ {2,}\n/', '<br />', $this->_xmlEntities($text));
        } else {
            $html = $this->_xmlEntities($text);
        }

        // check LINEBREAK directive in the page metadata
        if (isset($INFO['meta']['plugin_linebreak2'])) {
            $linebreak = $INFO['meta']['plugin_linebreak2'];
        } else {
            $linebreak = $this->getConf('linebreak');
        }

        switch ($linebreak) {
            case 'br':
                // xbr plugin: XHTML output with preserved linebreaks
                $this->doc .= str_replace(DOKU_LF, '<br />', $html);
                return;
            case '':
                // scriptio continua: concatenate next line without word delimiting space
                $this->doc .= str_replace(DOKU_LF, '', $html);
                return;
            case 'LF':
            default:
                // leave line break chars as is (identical with the standard xhml renderer)
                $this->doc .= $html;
                return;
        }
    }

}

