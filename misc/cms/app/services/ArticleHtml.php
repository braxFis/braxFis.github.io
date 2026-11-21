<?php
declare(strict_types=1);
namespace app\services;

/** Keep portable editorial HTML, never WordPress scripts, styles or embeds. */
final class ArticleHtml
{
    public static function url(string $url): string
    {
        $url = trim($url);
        $parts = parse_url($url);
        return is_array($parts) && in_array(strtolower($parts['scheme'] ?? ''), ['https', 'http'], true)
            && !empty($parts['host']) && !isset($parts['user']) && !isset($parts['pass'])
            && !preg_match('/[\x00-\x20\x7f]/', $url) ? $url : '';
    }

    public static function clean(string $html): string
    {
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        try {
            $doc->loadHTML('<?xml encoding="UTF-8"><html><body>' . $html . '</body></html>', LIBXML_NONET);
            $body = $doc->getElementsByTagName('body')->item(0);
            if (!$body) { return ''; }
            self::children($body);
            $out = '';
            foreach ($body->childNodes as $child) { $out .= $doc->saveHTML($child); }
            return $out;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
    }

    private static function children(\DOMNode $parent): void
    {
        $allowed = ['p','br','hr','h2','h3','h4','h5','h6','strong','b','em','i','u','s',
            'ul','ol','li','blockquote','pre','code','a','img','figure','figcaption',
            'table','thead','tbody','tfoot','tr','th','td','caption','sup','sub'];
        foreach (iterator_to_array($parent->childNodes) as $node) {
            if ($node instanceof \DOMComment || $node instanceof \DOMProcessingInstruction) {
                $parent->removeChild($node);
                continue;
            }
            if (!$node instanceof \DOMElement) { continue; }
            $tag = strtolower($node->tagName);
            if (in_array($tag, ['script','style','iframe','object','embed','svg','math','form','input','button','textarea','select','template','noscript'], true)) {
                $parent->removeChild($node);
                continue;
            }
            self::children($node);
            if (!in_array($tag, $allowed, true)) {
                while ($node->firstChild) { $parent->insertBefore($node->firstChild, $node); }
                $parent->removeChild($node);
                continue;
            }
            $attributes = [];
            foreach (iterator_to_array($node->attributes) as $attribute) {
                $name = strtolower($attribute->name);
                $value = $attribute->value;
                if (($tag === 'a' && $name === 'href') || ($tag === 'img' && $name === 'src')) {
                    $safe = self::url($value);
                    if ($safe !== '') { $attributes[$name] = $safe; }
                } elseif (in_array($name, ['alt','title'], true)) {
                    $attributes[$name] = $value;
                } elseif (in_array($tag, ['td','th'], true) && in_array($name, ['colspan','rowspan'], true)
                    && ctype_digit($value) && (int) $value <= 20) {
                    $attributes[$name] = $value;
                }
                $node->removeAttributeNode($attribute);
            }
            foreach ($attributes as $name => $value) { $node->setAttribute($name, $value); }
            if ($tag === 'a') { $node->setAttribute('rel', 'noopener noreferrer'); }
            if ($tag === 'img') {
                if (!$node->hasAttribute('src')) { $parent->removeChild($node); continue; }
                $node->setAttribute('loading', 'lazy');
            }
        }
    }
}
