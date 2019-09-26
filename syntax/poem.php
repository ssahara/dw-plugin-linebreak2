<?php
/**
 * DokuWiki Plugin LineBreak2
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Danny Lin <danny.0838@gmail.com>
 * @author     Satoshi Sahara <sahara.satoshi@gmail.com>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

class syntax_plugin_linebreak2_poem extends DokuWiki_Syntax_Plugin
{
    public function getType()
    {   // Syntax Type
        return 'container';
    }

    public function getAllowedTypes()
    {   // Allowed Mode Types
        return array('formatting', 'substition', 'disabled', 'poem');
    }

    public function getPType()
    {   // Paragraph Type
        return 'stack';
    }

    public function getSort()
    {   // sort number used to determine priority of this mode
        return 20;
    }

    /**
     * Connect pattern to lexer
     */
    protected $mode, $pattern;

    public function preConnect()
    {
        // syntax mode, drop 'syntax_' from class name
        $this->mode = substr(get_class($this), 7);

        // syntax pattern
        $this->pattern[1] = '<poem>\n?';
        $this->pattern[4] = '</poem>';
    }

    public function connectTo($mode)
    {
        $this->Lexer->addEntryPattern($this->pattern[1], $mode, $this->mode);
    }

    public function postConnect()
    {
        $this->Lexer->addExitPattern($this->pattern[4], $this->mode);
    }

    /**
     * Handle the match
     */
    public function handle($match, $state, $pos, Doku_Handler $handler)
    {
        if ($state == DOKU_LEXER_UNMATCHED) {
            $handler->base($match, $state, $pos);
        }
        return false;
    }

    /**
     * Create output
     */
    public function render($format, Doku_Renderer $renderer, $data)
    {
        return true;
    }
}
