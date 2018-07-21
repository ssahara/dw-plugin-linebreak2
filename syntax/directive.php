<?php
/**
 * DokuWiki Plugin LineBreak2; derective syntax component
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Satoshi Sahara <sahara.satoshi@gmail.com>
 *
 * Control how line break chars in wiki text should be treated in cdata() call
 * usage:
 *  ~~LINEBREAK~~     : same as ~~LINEBREAK:br~~
 *  ~~NOLINEBREAK~~   : same as ~~LINEBREAK:LF~~
 *
 *  ~~LINEBREAK:LF~~  : render DOKU_LF (\n) as is (identical with the standard renderer)
 *  ~~LINEBREAK:br~~  : render <br> tag
 *  ~~LINEBREAK:~~    : render nothing, remove line break chars
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_LF')) define ('DOKU_LF',"\n");

class syntax_plugin_linebreak2_directive extends DokuWiki_Syntax_Plugin {

    protected $mode;
    protected $pattern = array();

    function __construct() {
        // syntax mode, drop 'syntax_' from class name
        $this->mode = substr(get_class($this), 7);

        // syntax pattern
        $this->pattern[5] = '~~(?:NO)?LINEBREAK(?::[^\r\n~]*)?~~';
    }

    function getType(){ return 'substition'; }
    function getSort(){ return 369; } // very low priority

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern($this->pattern[5], $mode, $this->mode);
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler) {
        list ($macro, $param) = explode(':', substr($match, 2, -2));

        if ($macro == 'LINEBREAK') {
            $linebreak = $param ?? 'br';
        } else {
            $linebreak = 'LF';
        }
        return $data = $linebreak;
    }

    /**
     * Create output
     */
    function render($format, Doku_Renderer $renderer, $data) {
        global $conf;

        $linebreak =& $data;

        if ($format == 'xhtml') {
            // check renderer_xhtml config parameter is set as 'linebreak2'
            global $conf, $ID;
            if ($conf['renderer_xhtml'] !== 'linebreak2') {
                $note = 'set <b>render_xhtml</b> config parameter to "LineBreak2"';
                $note.= ' (mode = '.$linebreak.')';
                msg($note, 2);
            }

            // set linebreak mode in volatile global variable
            $conf['plugin']['linebreak2']['_linebreak'] = $linebreak;
        }
        return true;
    }
}
