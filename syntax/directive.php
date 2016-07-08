<?php
/**
 * DokuWiki Plugin LineBreak2
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Danny Lin <danny.0838@gmail.com>
 * @author     Satoshi Sahara <sahara.satoshi@gmail.com>
 *
 * usage:
 *  ~~LINEBREAK:LF~~  : render DOKU_LF (\n) as is (identical with the standard renderer)
 *  ~~LINEBREAK:br~~  : render <br> tag
 *  ~~LINEBREAK:~~    : render nothing, remove line break chars
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class syntax_plugin_linebreak2_directive extends DokuWiki_Syntax_Plugin {

    protected $match_pattern = '~~LINEBREAK:[^\r\n]*?~~';

    function getType(){ return 'substition'; }
    function getSort(){ return 369; } // very low priority

    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern($this->match_pattern, $mode, 'plugin_linebreak2_directive');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        $data = substr($match, 12, -2);
        return $data;
    }

    /**
     * Create output
     */
    function render($format, Doku_Renderer $renderer, $data) {
        if ($format == 'metadata') {
            $renderer->meta['plugin_linebreak2'] = $data;
        }
        return true;
    }
}
