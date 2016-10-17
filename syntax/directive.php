<?php
/**
 * DokuWiki Plugin LineBreak2; derective syntax component
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Satoshi Sahara <sahara.satoshi@gmail.com>
 *
 * Control how line break chars in wiki text should be treated in cdata() call
 * usage:
 *  ~~LINEBREAK:LF~~  : render DOKU_LF (\n) as is (identical with the standard renderer)
 *  ~~LINEBREAK:br~~  : render <br> tag
 *  ~~LINEBREAK:~~    : render nothing, remove line break chars
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_LF')) define ('DOKU_LF',"\n");

class syntax_plugin_linebreak2_directive extends DokuWiki_Syntax_Plugin {

    protected $mode;
    protected $match_pattern = '~~LINEBREAK:[^\r\n]*?~~';

    function __construct() {
        $this->mode = substr(get_class($this), 7); // drop 'syntax_' from class name
    }

    function getType(){ return 'substition'; }
    function getSort(){ return 369; } // very low priority

    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern($this->match_pattern, $mode, $this->mode);
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler) {
        $data = $match;
        return $data;
    }

    /**
     * Create output
     */
    function render($format, Doku_Renderer $renderer, $data) {
        if ($format == 'xhtml') {
            // check renderer_xhtml config parameter is set as 'linebreak2'
            global $conf, $ID;
            if ($conf['renderer_xhtml'] !== 'linebreak2') {
                msg(hsc($data).' | set <b>render_xhtml</b> config to LineBreak2 plugin', 2);
                error_log('LineBreak2 plugin: non-effective LINEBREAK macro found in '.$ID);
            }
        }
        if ($format == 'metadata') {
            $renderer->meta['plugin_linebreak2'] = substr($data, 12, -2);
        }
        return true;
    }
}
