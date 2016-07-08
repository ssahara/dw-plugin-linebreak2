<?php
/**
 * DokuWiki Plugin LineBreak2; render component
 *
 * @author Chris Smith <chris@jalakai.co.uk>
 * @author Satoshi Sahara <sahara.satoshi@gmail.com>
 */

if(!defined('DOKU_INC')) die();
if(!defined('DOKU_LF')) define ('DOKU_LF',"\n");

//require_once DOKU_INC . 'inc/parser/xhtml.php';

/**
 * The Renderer
 */
class renderer_plugin_linebreak2 extends Doku_Renderer_xhtml {

    function canRender($format) {
      return ($format=='xhtml');
    }

    function reset() {
       $this->doc = '';
       $this->footnotes = array();
       $this->lastsec = 0;
       $this->store = '';
       $this->_counter = array();
    }

    function cdata($text) {
        switch ($this->getConf('linebreak')) {
            case 'br':
                // xbr plugin: XHTML output with preserved linebreaks
                $this->doc .= str_replace(DOKU_LF,'<br />'.DOKU_LF,$this->_xmlEntities($text));
                return;
            case '':
                // scriptio continua: concatenate next line without word delimiting space
                $this->doc .= str_replace(DOKU_LF,'',$this->_xmlEntities($text));
                return;
            case 'LF':
            default:
                // leave line break chars as is (identical with the standard xhml renderer)
                $this->doc .= $this->_xmlEntities($text);
                return;
        }
    }

}

