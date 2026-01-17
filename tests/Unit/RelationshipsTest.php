<?php

namespace Tests\Unit;

use App\Models\{
    User,
    School,
    Visit,
    VisitAttachment,
    WorkEvent,
    Sector,
    Program,
    ProgramCycle,
    ProgramCycleIndicator,
    AcademicYear
};
use PHPUnit\Framework\TestCase;

/**
 * Test for all Model Relationships
 * تجربة شاملة لجميع العلاقات في النماذج
 */
class RelationshipsTest extends TestCase
{
    /**
     * Test User relationships
     */
    public function test_user_has_relationships()
    {
        // User must have methods for relationships
        $this->assertTrue(method_exists(User::class, 'sector'));
        $this->assertTrue(method_exists(User::class, 'visits'));
        $this->assertTrue(method_exists(User::class, 'workEvents'));
        $this->assertTrue(method_exists(User::class, 'uploadedAttachments'));
        $this->assertTrue(method_exists(User::class, 'schoolsAsCoordinator'));
        $this->assertTrue(method_exists(User::class, 'schoolsAsPrincipal'));
    }

    /**
     * Test School relationships
     */
    public function test_school_has_relationships()
    {
        $this->assertTrue(method_exists(School::class, 'sector'));
        $this->assertTrue(method_exists(School::class, 'coordinator'));
        $this->assertTrue(method_exists(School::class, 'principal'));
        $this->assertTrue(method_exists(School::class, 'programCycles'));
        $this->assertTrue(method_exists(School::class, 'visits'));
    }

    /**
     * Test Visit relationships
     */
    public function test_visit_has_relationships()
    {
        $this->assertTrue(method_exists(Visit::class, 'user'));
        $this->assertTrue(method_exists(Visit::class, 'school'));
        $this->assertTrue(method_exists(Visit::class, 'academicYear'));
        $this->assertTrue(method_exists(Visit::class, 'programCycle'));
        $this->assertTrue(method_exists(Visit::class, 'attachments'));
    }

    /**
     * Test VisitAttachment relationships
     */
    public function test_visit_attachment_has_relationships()
    {
        $this->assertTrue(method_exists(VisitAttachment::class, 'visit'));
        $this->assertTrue(method_exists(VisitAttachment::class, 'uploadedBy'));
    }

    /**
     * Test WorkEvent relationships
     */
    public function test_work_event_has_relationships()
    {
        $this->assertTrue(method_exists(WorkEvent::class, 'user'));
        $this->assertTrue(method_exists(WorkEvent::class, 'academicYear'));
        $this->assertTrue(method_exists(WorkEvent::class, 'programCycle'));
    }

    /**
     * Test Sector relationships
     */
    public function test_sector_has_relationships()
    {
        $this->assertTrue(method_exists(Sector::class, 'schools'));
        $this->assertTrue(method_exists(Sector::class, 'users'));
        $this->assertTrue(method_exists(Sector::class, 'visits'));
        $this->assertTrue(method_exists(Sector::class, 'programCycles'));
    }

    /**
     * Test Program relationships
     */
    public function test_program_has_relationships()
    {
        $this->assertTrue(method_exists(Program::class, 'programCycles'));
        $this->assertTrue(method_exists(Program::class, 'indicators'));
        $this->assertTrue(method_exists(Program::class, 'visits'));
        $this->assertTrue(method_exists(Program::class, 'workEvents'));
    }

    /**
     * Test ProgramCycle relationships
     */
    public function test_program_cycle_has_relationships()
    {
        $this->assertTrue(method_exists(ProgramCycle::class, 'program'));
        $this->assertTrue(method_exists(ProgramCycle::class, 'academicYear'));
        $this->assertTrue(method_exists(ProgramCycle::class, 'indicators'));
        $this->assertTrue(method_exists(ProgramCycle::class, 'schools'));
        $this->assertTrue(method_exists(ProgramCycle::class, 'visits'));
        $this->assertTrue(method_exists(ProgramCycle::class, 'workEvents'));
    }

    /**
     * Test ProgramCycleIndicator relationships
     */
    public function test_program_cycle_indicator_has_relationships()
    {
        $this->assertTrue(method_exists(ProgramCycleIndicator::class, 'programCycle'));
    }

    /**
     * Test AcademicYear relationships
     */
    public function test_academic_year_has_relationships()
    {
        $this->assertTrue(method_exists(AcademicYear::class, 'programCycles'));
        $this->assertTrue(method_exists(AcademicYear::class, 'programCycleIndicators'));
        $this->assertTrue(method_exists(AcademicYear::class, 'visits'));
        $this->assertTrue(method_exists(AcademicYear::class, 'workEvents'));
    }
}
