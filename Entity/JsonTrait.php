<?php

namespace Lthrt\EntityBundle\Entity;

/**
 * GetSet Trait.
 *
 * For discussion see: http://www.epixa.com/2010/05/the-best-models-are-easy-models.html
 */
trait JsonTrait
{
    /**
     *  Using class must "implements \JsonSerializable".
     */
    public function jsonSerialize()
    {
        $fields = array_filter(get_object_vars($this),
            function ($v) {
                return "_" != substr($v, 0, 1);
            },
            ARRAY_FILTER_USE_KEY
        );

        return $fields;
    }
}
