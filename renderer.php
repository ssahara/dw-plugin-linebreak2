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
        global $conf;

        $html = $this->_xmlEntities($text);

        // CJK typesetting
        // remove unnecessary spaces between any full-width characters
        // caused by line feed (LF) in a paragraph of CJK language
        // using negated one-byte character class [^\x20-\x7E\xA1-\xFF]
        if ($this->getConf('cjk')) {
            $html = preg_replace('/(?<=[^\x20-\x7E\xA1-\xFF])\n(?=[^\x20-\x7E\xA1-\xFF])/', '', $html);
        }

        // Markdown linebreak syntax
        // force newline if found more than two spaces at the end of line
        if ($this->getConf('markdown')) {
            $html = preg_replace('/ {2,}\n/', '<br />', $html);
        }

        // get linebreak mode
        $linebreak = $conf['plugin']['linebreak2']['_linebreak'] ?? $this->getConf('linebreak');

        switch ($linebreak) {
            case 'br':
                // xbr plugin: XHTML output with preserved linebreaks
                if (!empty(trim($html)) {
                    $this->doc .= str_replace(DOKU_LF, '<br />', $html);
                }
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

