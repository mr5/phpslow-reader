<?php if (!defined('IN_PHP_SLOW_READER')) {
    header('HTTP/1.1 404 Not Found');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $tracesArr['server'] . '::' . $tracesArr['file']; ?></title>

<style>.cf:before, .cf:after {
    content: " ";
    display: table;
}

.cf:after {
    clear: both;
}

.cf {
    *zoom: 1;
}

body {
    font: 14px "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif;
    color: #2B2B2B;
    background-color: #e7e7e7;
    padding: 0;
    margin: 0;
    max-height: 100%;
}

a {
    text-decoration: none;
    color: #FE8A59;
}

.container {
    height: 100%;
    width: 100%;
    position: fixed;
    margin: 0;
    padding: 0;
    left: 0;
    top: 0;
}

.repetition-badge {
    padding: 3px 7px;
    border-radius: 14px;
    margin-left: 24px;
    background: #ED591A;
    color: white;
}

.active .repetition-badge {
    background: white;
    color: #ED591A;
}

.branding {
    position: absolute;
    top: 10px;
    right: 20px;
    color: #777777;
    font-size: 10px;
    z-index: 100;
}

.branding a {
    color: #CD3F3F;
}

header {
    padding: 20px 20px;
    color: #555;
    background: #ddd;
    box-sizing: border-box;
    border-left: 5px solid #ED3D1A;

}

.exc-title {
    margin: 0;
    color: #616161;
    font-weight: normal;
}

.exc-title-primary {
    color: #ED591A;
}

.exc-message {
    font-size: 16px;
    margin: 5px 0;
    word-wrap: break-word;
}

.stack-container {
    height: 100%;
    position: relative;
}

.details-container {
    height: 100%;
    overflow: auto;
    float: right;
    width: 70%;
    background: #fff;
}

.details {
    padding: 10px 20px;
    border-left: 5px solid rgba(0, 0, 0, .2);
}

.frames-container {
    height: 100%;
    overflow: auto;
    float: left;
    width: 30%;
    background: #FFF;
}
.frame-code-container {
    padding-bottom: 60px;
}
.frame {
    padding: 14px;
    background: #F3F3F3;
    border-right: 1px solid rgba(0, 0, 0, .2);
    border-bottom: 1px solid #ddd;
    cursor: pointer;
    font-size: 12px;
}

.frame.active {
    background-color: #ED591A;
    color: #F3F3F3;

}

.frame:not(.active):hover {
    background: #F0E5DF;
}

.frame-class, .frame-function, .frame-index {
    font-weight: bold;
}

.frame-index {
    font-size: 11px;
    color: #BDBDBD;
}

.frame-class {
    color: #ED591A;
}

.frame-class span {
    color: #999;
}

.active .frame-class {
    color: #5E2204;
}

.active .frame-class span {
    color: #5E2204;
}

.frame-file {
    font-family: 'Source Code Pro', Monaco, Consolas, "Lucida Console", monospace;
    color: #999;
    word-wrap: break-word;
}

.editor-link {
    color: inherit;
}

.editor-link:hover strong {
    color: #F0E5DF;
}

.editor-link-callout {
    padding: 2px 4px;
    background: #872D00;
}

.frame-line {
    font-weight: bold;
    color: #A33202;
}

.active .frame-file {
    color: #872D00;
}

.active .frame-line {
    color: #fff;
}

.frame-line:before {
    content: ":";
}

.frame-code {
    padding: 20px;
    background: #f0f0f0;
    display: none;
    border-left: 5px solid #EDA31A;
}

.frame-code.active {
    display: block;
}

.frame-code .frame-file {
    background: #ED591A;
    color: #fff;
    padding: 10px 10px 10px 10px;
    font-size: 11px;
    font-weight: normal;
}

.code-block {
    padding: 10px;
    margin: 0;
    box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
}

.linenums {
    margin: 0;
    margin-left: 10px;
}

.frame-comments {
    border-top: none;
    padding: 5px;
    font-size: 12px;
    background: #404040;
}

.frame-comments.empty {
    padding: 8px 15px;
}

.frame-comments.empty:before {
    content: "No comments for this stack frame.";
    color: #828282;
}

.frame-comment {
    padding: 10px 5px;
    color: #D2D2D2;
}

.frame-comment:not(:last-child) {
    border-bottom: 1px dotted rgba(0, 0, 0, .3);
}

.frame-comment-context {
    font-size: 10px;
    font-weight: bold;
    color: #86D2B6;
}

.data-table-container label {
    font-size: 16px;
    font-weight: bold;
    color: #ED591A;
    margin: 10px 0;
    padding: 10px 0;

    display: block;
    margin-bottom: 5px;
    padding-bottom: 5px;
    border-bottom: 1px solid rgba(0, 0, 0, .08);
}

.data-table {
    width: 100%;
    margin: 10px 0;
    font: 12px 'Source Code Pro', Monaco, Consolas, "Lucida Console", monospace;
}

.data-table thead {
    display: none;
}

.data-table tr {
    padding: 5px 0;
}

.data-table td:first-child {
    width: 20%;
    min-width: 130px;
    overflow: hidden;
    color: #463C54;
    padding-right: 5px;

}

.data-table td:last-child {
    width: 80%;
    color: #999;
    -ms-word-break: break-all;
    word-break: break-all;
    word-break: break-word;
    -webkit-hyphens: auto;
    -moz-hyphens: auto;
    hyphens: auto;
}

.data-table .empty {
    color: rgba(0, 0, 0, .3);
    font-style: italic;
}

.handler {
    padding: 10px;
    font: 14px monospace;
}

.handler.active {
    color: #BBBBBB;
    background: #989898;
    font-weight: bold;
}

/* prettify code style
Uses the Doxy theme as a base */
pre .str, code .str {
    color: #E3B446;
}

/* string  */
pre .kwd, code .kwd {
    color: #DB613B;
    font-weight: bold;
}

/* keyword*/
pre .com, code .com {
    color: #555;
    font-weight: bold;
}

/* comment */
pre .typ, code .typ {
    color: #fff;
}

/* type  */
pre .lit, code .lit {
    color: #17CFB6;
}

/* literal */
pre .pun, code .pun {
    color: #93a1a1;
    font-weight: bold;
}

/* punctuation  */
pre .pln, code .pln {
    color: #ccc;
}

/* plaintext  */
pre .tag, code .tag {
    color: #DB613B;
}

/* html/xml tag  */
pre .htm, code .htm {
    color: #dda0dd;
}

/* html tag */
pre .xsl, code .xsl {
    color: #d0a0d0;
}

/* xslt tag */
pre .atn, code .atn {
    color: #fff;
    font-weight: normal;
}

/* html/xml attribute name */
pre .atv, code .atv {
    color: #E3B446;
}

/* html/xml attribute value  */
pre .dec, code .dec {
    color: #fff;
}

/* decimal  */
pre.prettyprint, code.prettyprint {
    font-weight: normal;
    font-family: 'Source Code Pro', Monaco, Consolas, "Lucida Console", monospace;
    background: #272727;
    color: #929292;
    font-size: 11px;
    line-height: 1.5em;
}

pre.prettyprint {
    white-space: pre-wrap;
}

pre.prettyprint a, code.prettyprint a {
    text-decoration: none;
}

.linenums li {
    color: #A5A5A5;
}

.linenums li.current {
    background: rgba(255, 255, 255, .05);
    padding-top: 4px;
    padding-left: 1px;
}

.linenums li.current.active {
    background: rgba(255, 255, 255, .1);
}
</style>
</head>
<body>
<div class="container">
    <header>
        <div class="exception">
            <h3 class="exc-title">
                <span class="exc-title-primary"><?php echo $tracesArr['server']; ?>
                    :: </span><?php echo $tracesArr['file']; ?>
            </h3>

            <p class="exc-message">
            </p>
        </div>
    </header>
    <div class="stack-container">

        <div class="frames-container cf ">
            <?php $code_fragments = ''; ?>
            <?php foreach ($tracesArr['traces'] as $k => $traces): ?>
                <?php $_active = $k == 0 ? 'active' : ''; ?>

                <div class="frame <?php echo $_active; ?>" id="frame-line-<?php echo $k; ?>">
                    <div class="frame-method-info">
                        <span class="frame-index"><?php echo $k; ?>.</span>
                        <span class="frame-class"><?php echo $traces['time']; ?>
                            <span><?php echo $traces['title'] ?></span></span>
                        <span
                            class="frame-function"><?php echo $traces['repetitions'] > 1 ? '<span class="repetition-badge">' . $traces['repetitions'] . '</span>' : '' ?></span>
                    </div>

      <span class="frame-file">
        <?php echo $traces['script']; ?>
      </span>
                </div>

                <?php
                foreach ($traces['traces'] as $trace) {
                    if (!isset($trace['code_fragment'])) {
                        $trace['code_fragment_start_line'] = 1;
                        $trace['code_fragment'] = 'This trace has not any related codes shown by php slow log.';
                    }
                    $code_fragments .= <<<HTML
 <div class="frame-code frame-code-{$k} {$_active}" data-current-line="{$trace['line']}">
    <div class="frame-file">
        <a href="subl://open?url=file://{$trace['file']}&line={$trace['line']}"
           class="editor-link">
            <span class="editor-link-callout">open:</span> <strong> {$trace['file']}:${trace['line']}</strong>
        </a>
    </div>
<pre class="code-block prettyprint linenums:{$trace['code_fragment_start_line']}">
{$trace['code_fragment']}</pre>

    <div class="frame-comments ">
        <span style="color: white;margin-left:12px;">Method or Function：</span><span class="frame-comment-context">{$trace['function']}</span>
    </div>

</div>
HTML;
                }

                ?>
            <?php endforeach; ?>


        </div>

        <div class="details-container cf">


            <div class="frame-code-container ">
                <?php echo $code_fragments; ?>
            </div>

        </div>

    </div>
</div>

<script src="http://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    $(function () {
        prettyPrint();

        var $frameLines = $('[id^="frame-line-"]');
        var $activeFrame = $('.active[id^="frame-code-"]').show();
        var $container = $('.details-container');
        var headerHeight = $('header').css('height');
        var highlightCurrentLine = function () {
            // Highlight the active and neighboring lines for this frame:
            var $activeLine = $('.frame-code.active').each(function (i, item) {
                item = $(item);
                var activeLineNumber = +(item.attr('data-current-line'));
                var $lines = item.find('.linenums li');
                var firstLine = +($lines.first().val());

                $($lines[activeLineNumber - firstLine - 1]).addClass('current');
                $($lines[activeLineNumber - firstLine]).addClass('current active');
                $($lines[activeLineNumber - firstLine + 1]).addClass('current');
            });

        };

        // Highlight the active for the first frame:
        highlightCurrentLine();

        $frameLines.click(function () {
            var $this = $(this);
            var id = /frame\-line\-([\d]*)/.exec($this.attr('id'))[1];
            var $codeFrame = $('.frame-code-' + id);

            if ($codeFrame) {
                $('.frame.active').removeClass('active');
                $('.frame-code.active').removeClass('active');
                $activeFrame.removeClass('active');

                $this.addClass('active');
                $codeFrame.addClass('active');

                $activeFrame = $codeFrame;

                highlightCurrentLine();

//                $container.animate({scrollTop: headerHeight}, "fast");
            }
        });
    });
</script>
</body>
</html>
