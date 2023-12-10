<?php

namespace App\Library;

class Markdown
{
    // escape
    public static function __MDE($str, $x)
    {
        return preg_replace('#([' . preg_quote($x, '#') . '])#', '\\\$1', $str);
    }

    // un-escape
    public static function __MDD($str, $x)
    {
        return preg_replace('#\\\\([' . preg_quote($x, '#') . '])#', '$1', $str);
    }

    public static function __fnc($args)
    {
        // return 'this is function  for: '.$args;

        $c = explode('|', $args);

        if (isset($c[0]) && class_exists($c[0])) {
            $args = explode(':', $c[2]);
            if (count($args) >= 2) {
                $value = call_user_func([$c[0], $c[1]], $args);
            } else {
                $value = call_user_func([$c[0], $c[1]], str_replace(':', "','", $c[2]));
            }
        } else {
            $value = 'Class Doest Not Exists';
        }

        return $value;
    }

    public static function __php($code)
    {
        $attr['code'] = $code;

        return view('core.code', $attr);
        // return $result;
    }

    // main function
    public static function MD($content)
    {
        // character(s) to escape
        $x = '`~!#^*()-_+={}[]:\'"<>.';
        // URL pattern
        $url = '(?:ht|f)tps?:\/\/[a-z0-9\-._~!$&\'()*+,;=:\/?\#\[\]@%]+';
        // empty element suffix
        $suffix = '>';
        // normalize white-space
        $content = trim(str_replace(["\r\n", "\r"], "\n", $content));
        // parse fenced code block to indented code block
        $content = preg_replace_callback('#(^|\n)([`~]{3,})(?: *\.?([a-zA-Z0-9\-.]+))?\n+([\s\S]+?)\n+\2(\n|$)#', function ($m) {
            $s = "\0" . str_replace('.', ' ', $m[3]) . "\0\n";

            return $m[1] . $s . '    ' . str_replace("\n", "\n    ", $m[4]) . $m[5];
        }, $content);
        // parse code block
        $content = preg_replace_callback('#^(?:\0(.*?)\0\n)?( {4}|\t)(.*?)$#m', function ($m) use ($x) {
            $s1 = ! empty($m[1]) ? ' class="' . $m[1] . '"' : '';
            $s3 = str_replace("\t", '    ', htmlentities($m[3], ENT_NOQUOTES));

            return '<pre><code' . $s1 . '>' . self:: __MDE($s3, $x) . '</code></pre>';
        }, $content);

        // parse code Function / Shortcode
        $content = preg_replace_callback('#(?<!\\\)!!([^\n]+?)!!#', function ($m) use ($x) {
            $s = htmlentities($m[1], ENT_NOQUOTES);

            return  self::__fnc($s, $x);
        }, $content);
        // parse code inline
        $content = preg_replace_callback('#(?<!\\\)`([^\n]+?)`#', function ($m) use ($x) {
            $s = htmlentities($m[1], ENT_NOQUOTES);

            return '\\<code>' . self::__MDE($s, $x) . '</code>';
        }, $content);

        // parse image and link
        $content = preg_replace_callback('#(!)?\[(.*?)\]\((.*?)( +([\'"])(.*?)\5)?\)#', function ($m) use ($x, $suffix) {
            $s2 = $m[2];
            $s3 = self::__MDE($m[3], $x);
            $s6 = ! empty($m[4]) && ! empty($m[6]) ? self::__MDE($m[6], $x) : '';
            $s6 = $s6 ? ' title="' . htmlentities($s6) . '"' : '';
            if (! empty($m[1])) {
                return '\\<img alt="' . htmlentities($s2) . '" src="' . $s3 . '"' . $s6 . $suffix;
            }

            return '\\<a href="' . $s3 . '"' . $s6 . '>' . $s2 . '</a>';
        }, $content);
        // parse link
        $content = preg_replace_callback('#<(' . $url . ')>#', function ($m) use ($x) {
            return '\\<a href="' . self::__MDE($m[1], $x) . '">' . $m[1] . '</a>';
        }, $content);
        // parse ATX header(s)
        $content = preg_replace_callback('#^(\#{1,6})\s*([^\#]+?)\s*\#*$#m', function ($m) {
            $i = strlen($m[1]);

            return '<h' . $i . '>' . $m[2] . '</h' . $i . '>';
        }, $content);
        $content = preg_replace(
        [
            // parse SEText header(s)
         //   '(^(.+?)\n={2,}$)m',
            '#^(.+?)\n={2,}$#m',
            '#^(.+?)\n-{2,}$#m',
            // parse horizontal rule
            '#^ {0,3}([*\-+] *){3,}$#m',
            // parse bold-italic text
            '#(?<!\\\)([*_]{2})([*_])([^\n]+?)\2\1#',
            // parse bold text
            '#(?<!\\\)([*_]{2})([^\n]+?)\1#',
            // parse italic text
            '#(?<!\\\)([*_])([^\n]+?)\1#',
            // parse strike text
            // '#(?<!\\\)(~{2})([^\n]+?)\1#',
            // parse unordered-list
            '#^ *[*\-+] +(.*?)$#m',
            // parse ordered-list
            '#^ *\d+\. +(.*?)$#m',
            // parse quote block
            '#^(?:>|&gt;) +(.*?)$#m',
            // clean-up list ...
            '#\s*<\/(ol|ul)>\n<\1>\s*#',
            // clean-up quote block ...
            '#\s*<\/blockquote>\n<blockquote>\s*#',
            // parse two or more white-space(s) at the end of text into a line-break
            '#(\S) {2,}\n#',
        ],
        [
          //  'This is function',
            '<h1>$1</h1>',
            '<h2>$1</h2>',
            '<hr' . $suffix,
            '\\<strong><em>$3</em></strong>',
            '\\<strong>$2</strong>',
            '\\<em>$2</em>',
            // '\\<del>$2</del>',
            "<ul>\n  <li>$1</li>\n</ul>",
            "<ol>\n  <li>$1</li>\n</ol>",
            "<blockquote>\n  <p>$1</p>\n</blockquote>",
            "\n  ",
            "\n  ",
            '$1<br' . $suffix . "\n",
        ],
    $content);
        // parse table
        $content = preg_replace_callback('#((?:\|[^|]+?\|[^|]+?)+)\|?\n((?:\| *(?:\-+|:\-+|\-+:|:\-+:) *)+\|?)((?:\n(?:\|[^|]+?)+\|?)+)$#m', function ($m) {
            $a = explode('|', trim($m[2], '|'));
            $str = "<table border=\"1\">\n";
            $str .= "  <thead>\n";
            $str .= "    <tr>\n";
            foreach (explode('|', trim($m[1], '|')) as $k => $v) {
                $aa = isset($a[$k]) ? ' ' . trim($a[$k]) . ' ' : '';
                if (false !== strpos($aa, ' :') && false !== strpos($aa, ': ')) {
                    $a[$k] = ' align="center"';
                } elseif (false !== strpos($aa, ' :')) {
                    $a[$k] = ' align="left"';
                } elseif (false !== strpos($aa, ': ')) {
                    $a[$k] = ' align="right"';
                } elseif (false === strpos($aa, ':')) {
                    $a[$k] = '';
                }
                $str .= '      <th' . $a[$k] . '>' . trim($v) . "</th>\n";
            }
            $str .= "    </tr>\n  </thead>\n";
            $str .= "  <tbody>\n";
            foreach (explode("\n", trim($m[3], "\n")) as $v) {
                $str .= "    <tr>\n";
                foreach (explode('|', trim($v, '|')) as $kk => $vv) {
                    $str .= '      <td' . $a[$kk] . '>' . trim($vv) . "</td>\n";
                }
                $str .= "    </tr>\n";
            }

            return $str . "  </tbody>\n</table>\n";
        }, $content);
        // parse new-line to paragraph
        foreach ($content = explode("\n\n", $content) as &$line) {
            if (
               '' !== $line // not empty
            && 0 !== strpos($line, '    ') // not a code block
            && 0 !== strpos($line, "\t") // --ibid
            && 0 !== strpos($line, '<') // not a HTML tag
        ) {
                $line = '<p>' . trim($line) . '</p>';
            }
        }
        $content = implode("\n\n", $content);
        // clean-up code block ...
        $content = preg_replace('#<\/code><\/pre>\n<pre><code(>| .*?>)#', "\n", $content);
        // typography (anything outside the HTML tag)
        $content = preg_replace_callback('#(^|<\/?[a-z]+[^>\n]*?>)(.*?)(<\/?[a-z]+[^>\n]*?>|$)#', function ($m) {
            $s = str_replace(
            [
                '&',
                '<',
                '>',
                '---',
                '--',
                '...',
            ],
            [
                '&amp;',
                '&lt;',
                '&gt;',
                '&mdash;',
                '&ndash;',
                '&hellip;',
            ],
        $m[2]);

            return $m[1] . preg_replace(
            [
                '#\'([^\'"]*?)\'#',
                '#"([^"]*?)"#',
                '#\b\'#',
                '#\'\b#',
                '#&amp;([a-z0-9]+|\#[0-9]+|\#x[a-f0-9]+);#', // restore the encoded html entity
            ],
            [
                '&lsquo;$1&rsquo;',
                '&ldquo;$1&rdquo;',
                '&rsquo;',
                '&lsquo;',
                '&$1;',
            ],
        $s) . $m[3];
        }, $content);
        // un-escape character(s)
        $content = self::__MDD($content, $x);
        // output the result
        return rtrim($content);
    }
}
