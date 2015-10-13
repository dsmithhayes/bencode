<?php

namespace Bencode\Core\Traits;

/**
 * Consider that every Element has a REGEX pattern that can be matched
 * and that each Element object implements a PATTERN constant. This
 * method is used only in the Collection Elements (List, Dictionary)
 * due to their strict rules of being becoding.
 */
trait Pattern {

    /**
     * Matches the stream to the Element's regex pattern, removed it from the
     * stream. For example, if you are decoding a List, you want to remove the
     * `l` and `e` from the beginning and end of the list.
     *
     * Note that this method is a *fail-safe* implementation that will return
     * the original stream value given.
     *
     * @param  string   $stream    The raw encoded stream
     * @param  string   $pattern   The REGEX pattern of the element.
     *
     * @return string   If pattern matches, the stream sans encoding
     */
    public function dropEncoding($stream, $pattern)
    {
        if (preg_match($pattern, $stream)) {
            $stream = substr($stream, 1);
            $stream = substr($stream, 0, -1);
        }

        return $stream;
    }
}
