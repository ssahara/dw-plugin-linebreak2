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
if(!defined('DOKU_LF')) define ('DOKU_LF',"\n");

class syntax_plugin_linebreak2_eol extends DokuWiki_Syntax_Plugin {

    protected $mode;
    protected $pattern = array();

    function __construct() {
        // syntax mode, drop 'syntax_' from class name
        $this->mode = substr(get_class($this), 7);

        // syntax pattern
        $this->pattern[5] = '(?:^[ \t]*)?\n';
    }

    function getType() { return 'poem'; }
    function getPType() { return 'normal'; }
    function getSort() { return 369; }

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
        return true;
    }

    /**
     * Create output
     */
    function render($format, Doku_Renderer $renderer, $data) {
        switch ($format) {
            case 'xhtml':
                $renderer->doc .= '<br/>'.DOKU_LF;
                return true;
            case 'metadata':
                $renderer->doc .= DOKU_LF;
                return true;
        }
        return false;
    }
}
