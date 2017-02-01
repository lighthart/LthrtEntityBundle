<?php

namespace Lthrt\EntityBundle\Model;

class FlushFinder
{
    /*
     * Returns the index of the debug_backtrace() array
     * that called 'flush'
     */
    public function getFlusher($debugBacktrace)
    {
        $found     = false;
        $backtrace = ['function' => false, 'class' => false];

        while (
            (
                isset($backtrace['class'])
                && 'Lthrt\EntityBundle\Model\EntityLogger' == $backtrace['class']
            )
            || !$backtrace['class']

        ) {
            while (
                isset($backtrace['function'])
                && 'flush' != $backtrace['function']

            ) {
                $backtrace = array_shift($debugBacktrace);
            }
            $backtrace = array_shift($debugBacktrace);
        }

        return $backtrace;
    }
}
