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

        static $renderer;

        if (!isset($renderer)) {
            $renderer = $this->loadHelper($this->getPluginName()) ?? false;
        }

        if (!$renderer) {
            $this->doc .= $renderer->cdata($text);
        } else {
            parent::cdata($text);
        }
    }

    /**
     * Render a heading
     *
     * @param string $text  the text to display
     * @param int    $level header level
     * @param int    $pos   byte position in the original source
     */
    function header($text, $level, $pos) {

        if (!$this->getConf('header_formatting')) {
            parent::header($text, $level, $pos);
            return;
        }

        if(blank($text)) return; //skip empty headlines

        /*
         * EXPERIMENTAL: Render a formatted heading
         */
        global $ID, $conf;
        static $toc;

        if (!isset($toc)) {
            // use toc metadata that has modified in PARSER_METADATA_RENDER event
            $toc = p_get_metadata($ID, 'description tableofcontents') ?? [];
        }
        if (($k = array_search($text, array_column($toc, '_text'))) !== false) {
            $html = $toc[$k]['_html'];
            $text = $toc[$k]['title'];
        } else {
            // NOTE: common plugin function render_text()
            // output text string through the parser, allows DokuWiki markup to be used
            // very ineffecient for small pieces of data - try not to use
            $html = substr($this->render_text($text), 5, -6); // strip p tags and \n
            $text = htmlspecialchars_decode(strip_tags($html), ENT_QUOTES);
            $text = str_replace(DOKU_LF, '', trim($text)); // remove any linebreak
        }

        // creates a linkid from a headline
        $hid = $this->_headerToLink($text, true); // ensure unique hid

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

