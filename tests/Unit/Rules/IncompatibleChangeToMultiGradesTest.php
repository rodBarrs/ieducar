<?php

namespace Tests\Unit\Rules;

use App\Models\LegacySchoolClass;
use App\Rules\IncompatibleChangeToMultiGrades;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class IncompatibleChangeToMultiGradesTest extends TestCase
{
    /**
     * @var IncompatibleChangeToMultiGrades
     */
    protected $rule;

    public function setUp(): void
    {
        parent::setUp();
        $this->rule = new IncompatibleChangeToMultiGrades();
    }

    /**
     * @return void
     */
    public function testCanNotChangeToMultiGrades()
    {
        $legacySchoolClass = $this->instance(
            LegacySchoolClass::class,
            Mockery::mock(LegacySchoolClass::class, function (MockInterface $mock) {
                // Informação que a turma se tornará de multiseriada
                $mock->shouldReceive('getLegacyColumn', 'getAttribute')
                    ->withArgs(['multiseriada'])
                    ->andReturn(true);

                $mock->shouldReceive('getLegacyColumn')
                    ->with('originalMultiGradesInfo')
                    ->andReturn('originalMultiGradesInfo')
                    ->shouldReceive('getAttribute')
                    ->andReturn(0);

                // Serie original diferente da listagem de series propostas
                $mock->shouldReceive('getLegacyColumn', 'getAttribute')
                    ->withArgs(['originalGrade'])
                    ->andReturn(1);

                // Contém alunos com matrículas ativas
                $mock->shouldReceive('getLegacyColumn', 'getTotalEnrolled')
                    ->andReturn(1);
            })
        );

        // Listagem de séries que não contém a série original
        $series = [
            ['serie_id' => 2],
            ['serie_id' => 3],
        ];

        $values = [
            $legacySchoolClass,
            $series,
        ];

        $message = 'Não foi possível alterar a turma para ser multisseriada, pois a série original possui matrículas vinculadas.';

        $this->assertFalse($this->rule->passes('teste', $values));
        $this->assertEquals($message, $this->rule->message());
    }

    /**
     * @return void
     */
    public function testCanChangeToMultiGradesWhitContainsOriginalGrade()
    {
        $legacySchoolClass = $this->instance(
            LegacySchoolClass::class,
            Mockery::mock(LegacySchoolClass::class, function (MockInterface $mock) {
                // Informação que a turma se tornará de multiseriada
                $mock->shouldReceive('getLegacyColumn', 'getAttribute')
                    ->withArgs(['multiseriada'])
                    ->andReturn(true);

                $mock->shouldReceive('getLegacyColumn')
                    ->with('originalMultiGradesInfo')
                    ->andReturn('originalMultiGradesInfo')
                    ->shouldReceive('getAttribute')->once()
                    ->andReturn(0);

                $mock->shouldReceive('getLegacyColumn')
                    ->with('originalGrade')
                    ->andReturn('originalGrade')
                    ->shouldReceive('getAttribute')->once()
                    ->andReturn(1);

                // Contém alunos com matrículas ativas
                $mock->shouldReceive('getLegacyColumn', 'getTotalEnrolled')
                    ->andReturn(1);
            })
        );

        // Listagem de series contém a serie original
        $series = [
            ['serie_id' => 1],
            ['serie_id' => 2],
        ];

        $values = [
            $legacySchoolClass,
            $series,
        ];

        $message = 'Não foi possível alterar a turma para ser multisseriada, pois a série original possui matrículas vinculadas.';

        $this->assertTrue($this->rule->passes('teste', $values));
        $this->assertEquals($message, $this->rule->message());
    }

    public function testCanChangeToMultiGradesWithNotContainsActiveEnrollments()
    {
        $legacySchoolClass = $this->instance(
            LegacySchoolClass::class,
            Mockery::mock(LegacySchoolClass::class, function (MockInterface $mock) {
                // Informação que a turma se tornará de multiseriada
                $mock->shouldReceive('getLegacyColumn', 'getAttribute')
                    ->withArgs(['multiseriada'])
                    ->andReturn(true);

                // Não era multisseriada anteriormenente
                $mock->shouldReceive('getLegacyColumn')
                    ->with('originalMultiGradesInfo')
                    ->andReturn('originalMultiGradesInfo')
                    ->shouldReceive('getAttribute')
                    ->andReturn(0);

                // Serie original diferente da listagem de series propostas
                $mock->shouldReceive('getLegacyColumn', 'getAttribute')
                    ->withArgs(['originalGrade'])
                    ->andReturn(1);

                // Contém alunos com matrículas ativas
                $mock->shouldReceive('getLegacyColumn', 'getTotalEnrolled')
                    ->andReturn(0);
            })
        );

        // Listagem de series contém a serie original
        $series = [
            ['serie_id' => 1],
            ['serie_id' => 2],
        ];

        $values = [
            $legacySchoolClass,
            $series,
        ];

        $message = 'Não foi possível alterar a turma para ser multisseriada, pois a série original possui matrículas vinculadas.';

        $this->assertTrue($this->rule->passes('teste', $values));
        $this->assertEquals($message, $this->rule->message());
    }

    public function testCanChangeToMultiGradesWithNotMultiGrades()
    {
        $legacySchoolClass = $this->instance(
            LegacySchoolClass::class,
            Mockery::mock(LegacySchoolClass::class, function (MockInterface $mock) {
                // Não se tornará uma turma multiseriada
                $mock->shouldReceive('getAttribute')
                    ->with('multiseriada')
                    ->andReturn(false);

                // Não era multisseriada anteriormenente
                $mock->shouldReceive('getLegacyColumn')
                    ->with('originalMultiGradesInfo')
                    ->andReturn('originalMultiGradesInfo')
                    ->shouldReceive('getAttribute')
                    ->andReturn(0);

                // Serie original
                $mock->shouldReceive('getLegacyColumn', 'getAttribute')
                    ->withArgs(['originalGrade'])
                    ->andReturn(1);

                // Contém alunos com matrículas ativas
                $mock->shouldReceive('getLegacyColumn', 'getTotalEnrolled')
                    ->andReturn(0);
            })
        );

        // Listagem de series
        $series = [];

        $values = [
            $legacySchoolClass,
            $series,
        ];

        $message = 'Não foi possível alterar a turma para ser multisseriada, pois a série original possui matrículas vinculadas.';

        $this->assertTrue($this->rule->passes('teste', $values));
        $this->assertEquals($message, $this->rule->message());
    }

    public function testCanChangeToMultiGradesWithoriginalMultiGradesInfoIsTrue()
    {
        $legacySchoolClass = $this->instance(
            LegacySchoolClass::class,
            Mockery::mock(LegacySchoolClass::class, function (MockInterface $mock) {
                // Informação que a turma se tornará de multiseriada
                $mock->shouldReceive('getLegacyColumn', 'getAttribute')
                    ->withArgs(['multiseriada'])
                    ->andReturn(true);

                // Era multisseriada anteriormenente
                $mock->shouldReceive('getLegacyColumn')
                    ->with('originalMultiGradesInfo')
                    ->andReturn('originalMultiGradesInfo')
                    ->shouldReceive('getAttribute')
                    ->andReturn(true);

                // Serie original
                $mock->shouldReceive('getLegacyColumn', 'getAttribute')
                    ->withArgs(['originalGrade'])
                    ->andReturn(1);

                // Contém alunos com matrículas ativas
                $mock->shouldReceive('getLegacyColumn', 'getTotalEnrolled')
                    ->andReturn(0);
            })
        );

        // Listagem de series contém a serie original
        $series = [
            ['serie_id' => 1],
            ['serie_id' => 2],
        ];

        $values = [
            $legacySchoolClass,
            $series,
        ];

        $message = 'Não foi possível alterar a turma para ser multisseriada, pois a série original possui matrículas vinculadas.';

        $this->assertTrue($this->rule->passes('teste', $values));
        $this->assertEquals($message, $this->rule->message());
    }
}
