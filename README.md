# LineBreak2 plugin for DokuWiki

A xhtml renderer alternative which provides **LINEBREAK** directive syntax. 
The directive defines how line break (new line) chars should be rendered in the wikipage:

    ~~LINEBREAK:LF~~  : render DOKU_LF (\n) as is (identical with the standard DokuWiki XHTML renderer)
    ~~LINEBREAK:br~~  : render <br> tag. preserve line breaks in the wiki text ("Poem" mode)
    ~~LINEBREAK:~~    : render nothing, remove line break chars ("Scriptio continua" mode)

The default behavior can be configured through the DokuWiki configuration manager.

Note: You need first to change the [renderer_xhtml](https://www.dokuwiki.org/config:renderer_xhtml) parameter in advanced settings.


----

