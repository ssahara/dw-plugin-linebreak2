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

class syntax_plugin_linebreak2_poem extends DokuWiki_Syntax_Plugin {

    protected $mode;

    function __construct() {
        $this->mode = substr(get_class($this), 7); // drop 'syntax_' from class name
    }

    function getType() { return 'container'; }
    function getPType() { return 'stack'; }
    function getAllowedTypes() { return array('formatting', 'substition', 'disabled', 'poem'); }
    function getSort() { return 20; }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('<poem>\n?', $mode, $this->mode);
    }

    function postConnect() {
        $this->Lexer->addExitPattern('</poem>', $this->mode);
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        if ($state == DOKU_LEXER_UNMATCHED) {
            $handler->_addCall('cdata', array($match), $pos);
        }
        return false;
    }

    /**
     * Create output
     */
    function render($format, Doku_Renderer $renderer, $data) {
        return true;
    }
}
