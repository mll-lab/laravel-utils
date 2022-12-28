<?php declare(strict_types=1);

namespace MLL\LaravelUtils\ModelStates\Tests;

use Illuminate\Support\Collection as SupportCollection;
use MLL\LaravelUtils\ModelStates\Exceptions\TransitionNotAllowed;
use MLL\LaravelUtils\ModelStates\Exceptions\TransitionNotFound;
use MLL\LaravelUtils\ModelStates\Exceptions\UnknownStateException;
use MLL\LaravelUtils\Tests\DBTestCase;
use MLL\LaravelUtils\Tests\ModelStates\Dummy\ModelStates\StateA;
use MLL\LaravelUtils\Tests\ModelStates\Dummy\ModelStates\StateB;
use MLL\LaravelUtils\Tests\ModelStates\Dummy\ModelStates\StateC;
use MLL\LaravelUtils\Tests\ModelStates\Dummy\ModelStates\StateD;
use MLL\LaravelUtils\Tests\ModelStates\Dummy\OtherModelStates\StateY;
use MLL\LaravelUtils\Tests\ModelStates\Dummy\OtherModelStates\StateZ;
use MLL\LaravelUtils\Tests\ModelStates\Dummy\TestModel;
use MLL\LaravelUtils\Tests\ModelStates\Dummy\TestModelWithCustomTransition;

final class StateTest extends DBTestCase
{
    private string $columnName;

    public function setUp(): void
    {
        parent::setUp();
        $columnName = config('model-state.column_name');
        assert(is_string($columnName));
        $this->columnName = $columnName;
    }

    public function testModelWillBeCreatedWithDefault(): void
    {
        $model = new TestModel();
        $model->save();

        self::assertSame(StateA::name(), $model->stateManager->{$this->columnName});
    }

    public function testIfOldAndNewStateAreIdenticalNoTransitionNotAllowedExceptionWillBeThrown(): void
    {
        // A -> A
        $model1 = new TestModel();
        $model1->save();
        self::assertSame('STATE_A', StateA::name());
        self::assertSame(StateA::name(), $model1->stateManager->{$this->columnName});
        $model1->state = new StateA();
        self::assertSame(StateA::name(), $model1->stateManager->{$this->columnName});
    }

    public function testCanTransitionToAllowedStates(): void
    {
        // A -> B
        $model1 = new TestModel();
        $model1->save();
        self::assertSame(StateA::name(), $model1->stateManager->{$this->columnName});

        $model1->state = new StateB();
        self::assertSame(StateB::name(), $model1->stateManager->{$this->columnName});

        // B -> C
        $model1->state = new StateC();
        self::assertSame(StateC::name(), $model1->stateManager->{$this->columnName});

        // A -> C
        $model2 = new TestModel();
        $model2->save();
        self::assertSame(StateA::name(), $model2->stateManager->{$this->columnName});

        $model2->state = new StateC();
        self::assertSame(StateC::name(), $model2->stateManager->{$this->columnName});

        // A -> C
        $model3 = new TestModel();
        $model3->save();
        self::assertSame(StateA::name(), $model3->stateManager->{$this->columnName});

        $model3->state = new StateD();
        self::assertSame(StateD::name(), $model3->stateManager->{$this->columnName});
    }

    public function testCanNotTransitionForNotAssignedTransitionFromBToA(): void
    {
        $model = new TestModel();
        $model->save();

        self::assertInstanceOf(StateA::class, $model->state);

        $model->state = new StateB();
        // @phpstan-ignore-next-line false positive by assuming the property is not mutated
        self::assertInstanceOf(StateB::class, $model->state);

        // B -> A
        $this->expectException(TransitionNotFound::class);
        $model->state = new StateA();
    }

    public function testCanNotTransitionForNotAssignedTransitionFromBToD(): void
    {
        $model = new TestModel();
        $model->save();
        self::assertSame(StateA::name(), $model->stateManager->{$this->columnName});

        $model->state = new StateB();
        self::assertSame(StateB::name(), $model->stateManager->{$this->columnName});

        // B -> D
        $this->expectException(TransitionNotFound::class);
        $model->state = new StateD();
        self::assertSame(StateD::name(), $model->stateManager->{$this->columnName});
    }

    public function testReturnsListOfPossibleNextStates(): void
    {
        $model = new TestModel();
        $model->save();
        self::assertEquals(new SupportCollection([
            StateB::class => new StateB(),
            StateC::class => new StateC(),
            StateD::class => new StateD(),
        ]), $model->stateManager->canTransitionTo);

        $model->state = new StateB();
        self::assertEquals(new SupportCollection([StateC::class => new StateC()]), $model->stateManager->canTransitionTo);
    }

    public function testCustomTransition(): void
    {
        $model = new TestModelWithCustomTransition();
        $model->save();

        $model->state = new StateY();
        self::assertSame(StateY::name(), $model->stateManager->{$this->columnName});
        self::assertSame('my custom action', $model->message);
    }

    public function testCannotCustomTransition(): void
    {
        $model = new TestModelWithCustomTransition();
        $model->save();

        $this->expectException(TransitionNotAllowed::class);
        $model->state = new StateZ();
    }

    public function testUnknownStateThrowsExceptionsForRetrieval(): void
    {
        $model = new TestModel();
        $model->save();
        $model->stateManager->setAttribute($this->columnName, 'UnknownState');
        self::assertEquals('UnknownState', $model->stateManager->{$this->columnName});
        $this->expectException(UnknownStateException::class);
        self::assertEquals('UnknownState', $model->state);
    }

    public function testGenerateMermaidGraph(): void
    {
        $model = new TestModel();

        $workflowAsMermaidGraph = 'graph TB;
    FOO_BAR("FOO_BAR");
    STATE_A("STATE_A");
    STATE_C("STATE_C");
    STATE_D("STATE_D");

    FOO_BAR-->STATE_C;
    STATE_A-->FOO_BAR;
    STATE_A-->STATE_C;
    STATE_A-->STATE_D;

linkStyle default interpolate basis;';

        self::assertSame(
            $workflowAsMermaidGraph,
            $model->generateMermaidGraphAsString(),
            'Use https://mermaid.live/ for debugging.'
        );
    }
}
