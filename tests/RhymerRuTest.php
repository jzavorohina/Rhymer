<?php

use PHPUnit\Framework\TestCase;

require_once("src\Rhymer.class.php");

class RhymerRuTest extends TestCase
{
    /**
     * @dataProvider providerByEnd
     */
    public function testFindByEnd($text, $foundExpected)
    {
        $found = Rhymer::findByEnd($text);
        $this->assertEquals($foundExpected, $found);
    }

    /**
     * @dataProvider providerByStart
     */
    public function testFindByStart($text, $foundExpected)
    {
        $found = Rhymer::findByStart($text);
        $this->assertEquals($foundExpected, $found);
    }

    public function providerByEnd()
    {
        return array(
            array('', array()),
            array(NULL, array()),
            array(array(), array()),
            array('процесс', array('Абсцесс', 'Эксцесс')),
            array('салют', array('Абсолют'))
        );
    }

    public function providerByStart()
    {
        return array(
            array('', array()),
            array(NULL, array()),
            array(array(), array()),
            array('', array()),
            array(
                'процесс', array(
                    'Процедить',
                    'Процедура',
                    'Процедурная',
                    'Процент',
                    'Процентомания',
                    'Процессия',
                    'Процессор'
                )
            ),
            array(
                'салют', array(
                    'Салютовать'
                )
            )
        );
    }
}
