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

        $html = $this->_xmlEntities($text);

        // get linebreak mode
        $linebreak = $this->getConf('_linebreak', null) ?? $this->getConf('linebreak');

        // BR mode: XHTML output with preserved linebreaks
        if ($linebreak == 'br' || $linebreak == 'BR') {
            $this->doc .= str_replace(DOKU_LF, '<br />', $html);
            return;
        }

        // Normal mode or scriptio continua

        // CJK typesetting
        // remove unnecessary spaces between any CJK characters
        // caused by line feed (LF) in a multi-line paragraph
        if ($this->getConf('cjk')) {
            $cjk = '\p{Han}\p{Hiragana}\p{Katakana}\p{Hangul}';
            $html = preg_replace('/(?<=['.$cjk.'])\n(?=['.$cjk.'])/u', '', $html);
        }

        // Markdown linebreak syntax
        // force newline if found more than two spaces at the end of line
        if ($this->getConf('markdown')) {
            $html = preg_replace('/ {2,}\n/', '<br />', $html);
        }

        // scriptio continua: concatenate next line without word delimiting space
        if ($linebreak == '') {
            $this->doc .= str_replace(DOKU_LF, '', $html);
            return;
        }

        // Normal mode: identical with the standard xhml renderer
        // leave line break chars as is
        $this->doc .= $html;
    }

}

