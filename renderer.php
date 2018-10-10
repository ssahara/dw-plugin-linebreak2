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

    /**
     * Render plain text data
     *
     * @param string $text  the text to display
     */
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

    /**
     * Render a heading
     *
     * @param string $text  the text to display
     * @param int    $level header level
     * @param int    $pos   byte position in the original source
     */
    function header($text, $level, $pos) {
        global $conf;

        if(blank($text)) return; //skip empty headlines

        // EXPERIMENTAL formatting header
        // output text string through the parser, allows dokuwiki markup to be used
        // very ineffecient for small pieces of data - try not to use
        if ($this->getConf('header_formatting')) {
            $html = substr($this->render_text($text), 4, -5); // strip p tags
            $text = trim(htmlspecialchars_decode(strip_tags($html), ENT_QUOTES));
            $text = str_replace(DOKU_LF, '', $text); // remove linebreaks
        } else {
            $html = $this->_xmlEntities($text);
        }

        $hid = $this->_headerToLink($text, true); // Creates a linkid from a headline

        //only add items within configured levels
        $this->toc_additem($hid, $text, $level);

        // adjust $node to reflect hierarchy of levels
        $this->node[$level - 1]++;
        if ($level < $this->lastlevel) {
            for($i = 0, $m = $this->lastlevel - $level; $i < $m; $i++) {
                $this->node[$this->lastlevel - $i - 1] = 0;
            }
        }
        $this->lastlevel = $level;

        if ($level <= $conf['maxseclevel']
            && count($this->sectionedits) > 0
            && $this->sectionedits[count($this->sectionedits) - 1]['target'] === 'section'
        ) {
            $this->finishSectionEdit($pos - 1);
        }

        // write the header
        $this->doc .= DOKU_LF.'<h'.$level;
        if ($level <= $conf['maxseclevel']) {
            $data = [
                'target' => 'section',
                'name' => $text,
                'hid' => $hid,
                'codeblockOffset' => $this->_codeblock,
            ];
            $this->doc .= ' class="'.$this->startSectionEdit($pos, $data).'"';
        }
        $this->doc .= ' id="'.$hid.'">';
        $this->doc .= $html;
        $this->doc .= '</h'.$level.'>'.DOKU_LF;
    }

}

