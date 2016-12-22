<?php

/*
 * This file is part of the GeckoPackages.
 *
 * (c) GeckoPackages https://github.com/GeckoPackages
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace GeckoPackages\PHPUnit\Constraints;

/**
 * @api
 *
 * @author SpacePossum
 */
final class FileExistsConstraint extends \PHPUnit_Framework_Constraint
{
    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return 'is a file';
    }

    /**
     * {@inheritdoc}
     */
    protected function matches($other)
    {
        return is_string($other) && is_file($other);
    }

    /**
     * {@inheritdoc}
     */
    protected function failureDescription($other)
    {
        if (is_object($other)) {
            $type = sprintf('%s#%s', get_class($other), method_exists($other, '__toString') ? $other->__toString() : '');
        } elseif (null === $other) {
            $type = 'null';
        } elseif (!is_string($other)) {
            $type = gettype($other).'#'.$other;
        } elseif (is_link($other)) {
            $type = 'link to directory#'.$other;
        } elseif (is_dir($other)) {
            $type = 'directory#'.$other;
        } else {
            $type = $other;
        }

        return $type.' '.$this->toString();
    }
}
