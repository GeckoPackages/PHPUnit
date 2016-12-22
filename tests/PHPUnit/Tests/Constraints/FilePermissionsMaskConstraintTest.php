<?php

/*
 * This file is part of the GeckoPackages.
 *
 * (c) GeckoPackages https://github.com/GeckoPackages
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use GeckoPackages\PHPUnit\Constraints\FilePermissionsMaskConstraint;

/**
 * @requires PHPUnit 5.2
 *
 * @internal
 *
 * @author SpacePossum
 */
final class FilePermissionsMaskConstraintTest extends AbstractGeckoPHPUnitFileTest
{
    /**
     * @param int $mask
     *
     * @dataProvider provideFileMasks
     */
    public function testFilePermissionsMaskConstraint($mask)
    {
        $constraint = new FilePermissionsMaskConstraint($mask);
        $this->assertTrue($constraint->evaluate($this->getTestFile(), '', true));
    }

    public function provideFileMasks()
    {
        return array(
            array(0644),
            array(0000),
            array(0004),
            array(0040),
            array(0044),
            array(0600),
            array(0604),
            array(0640),
        );
    }

    public function testFilePermissionsMaskConstraintBasic()
    {
        $constraint = new FilePermissionsMaskConstraint(1);
        $this->assertSame(1, $constraint->count());
        $this->assertSame('permissions matches mask', $constraint->toString());
    }

    public function testFilePermissionsMaskConstraintFalse()
    {
        $this->expectException(\PHPUnit_Framework_ExpectationFailedException::class);
        $this->expectExceptionMessageRegExp('#^Failed asserting that boolean\# permissions matches mask.$#');

        $constraint = new FilePermissionsMaskConstraint(1);
        $constraint->evaluate(false);
    }

    public function testFilePermissionsMaskConstraintFileLink()
    {
        $link = $this->getAssetsDir().'test_link_file';
        $this->createSymlink(
            $this->getAssetsDir().'_link_test_target_dir_/placeholder.tmp',
            $link
        );

        $constraint = new FilePermissionsMaskConstraint(0xA000);
        $constraint->evaluate($link);
    }

    public function testFilePermissionsMaskConstraintFileNotExists()
    {
        $this->expectException(\PHPUnit_Framework_ExpectationFailedException::class);
        $this->expectExceptionMessageRegExp('#^Failed asserting that not file or directory\#_does_not_exists_ permissions matches mask.$#');

        $constraint = new FilePermissionsMaskConstraint(1);
        $constraint->evaluate('_does_not_exists_');
    }

    public function testFilePermissionsMaskConstraintInt()
    {
        $this->expectException(\PHPUnit_Framework_ExpectationFailedException::class);
        $this->expectExceptionMessageRegExp('#^Failed asserting that integer\#1443 permissions matches mask.$#');

        $constraint = new FilePermissionsMaskConstraint(1);
        $constraint->evaluate(1443);
    }

    public function testFilePermissionsMaskConstraintMaskMismatch()
    {
        $this->expectException(\PHPUnit_Framework_ExpectationFailedException::class);
        $this->expectExceptionMessageRegExp('#^Failed asserting that file\#/.*tests/assets/dir/test_file.txt 100644 permissions matches mask 777.$#');

        $constraint = new FilePermissionsMaskConstraint(0777);
        $constraint->evaluate($this->getTestFile());
    }

    public function testFilePermissionsMaskConstraintNull()
    {
        $this->expectException(\PHPUnit_Framework_ExpectationFailedException::class);
        $this->expectExceptionMessageRegExp('#^Failed asserting that null permissions matches mask.$#');

        $constraint = new FilePermissionsMaskConstraint(1);
        $constraint->evaluate(null);
    }

    public function testFilePermissionsMaskConstraintObject()
    {
        $this->expectException(\PHPUnit_Framework_ExpectationFailedException::class);
        $this->expectExceptionMessageRegExp('#^Failed asserting that stdClass\# permissions matches mask.$#');

        $constraint = new FilePermissionsMaskConstraint(1);
        $constraint->evaluate(new \stdClass());
    }

    private function getTestFile()
    {
        return realpath($this->getAssetsDir().'/dir/test_file.txt');
    }
}
