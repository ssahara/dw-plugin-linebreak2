# LineBreak2 plugin for DokuWiki

A xhtml renderer alternative which provides **LINEBREAK** directive syntax. 
The directive defines how line break (new line) chars should be rendered in the wikipage:

    ~~LINEBREAK:LF~~  : render DOKU_LF (\n) as is (identical with the standard DokuWiki XHTML renderer)
    ~~LINEBREAK:br~~  : render <br> tag. preserve line breaks in the wiki text ("Poem" mode)
    ~~LINEBREAK:~~    : render nothing, remove line break chars ("Scriptio continua" mode)

The default behavior can be configured through the DokuWiki configuration manager.

Note: You need first to change the [renderer_xhtml](https://www.dokuwiki.org/config:renderer_xhtml) parameter in advanced settings.

### Markdown newline trick emulation (optional)

The LineBreak2 renderer also provides a **force newline** syntax that “two or more spaces at the end of line” will be rendered as `<br>` in wiki page. You may use this newline trick instead of “two backslashes at the end of line” that is a DokuWiki standard feature. The newline trick can be enabled in the config manager. 

### CJK typesetting (optional)

When you write multilined paragraph, line feed chars (remaining at end of each lines) are displayed as white spaces. This behavior may not be appropriate especially for CJK language - Chinese, Japanese and Korean documents.  The LineBreak2 renderer can remove such unnecessary spaces between any full-width characters.

----

