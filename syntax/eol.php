<?php
/**
 * DokuWiki Plugin LineBreak2
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Danny Lin <danny.0838@gmail.com>
 * @author     Satoshi Sahara <sahara.satoshi@gmail.com>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class syntax_plugin_linebreak2_eol extends DokuWiki_Syntax_Plugin {

    function getType() { return 'poem'; }
    function getPType() { return 'normal'; }
    function getSort() { return 369; }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('(?:^[ \t]*)?\n',$mode,'plugin_linebreak2_eol');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        return true;
    }

    /**
     * Create output
     */
    function render($format, Doku_Renderer $renderer, $data) {
        switch ($format) {
            case 'xhtml':
                $renderer->doc .= "<br/>\n";
                return true;
            case 'metadata':
                $renderer->doc .= "\n";
                return true;
        }
        return false;
    }
}
