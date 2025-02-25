<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\ModelStates;

use App\ModelStates\ModelStates\ModelState;
use App\ModelStates\ModelStates\StateA;
use App\ModelStates\ModelStates\StateB;
use App\ModelStates\ModelStates\StateC;
use App\ModelStates\ModelStates\StateD;
use App\ModelStates\OtherModelStates\StateX;
use App\ModelStates\OtherModelStates\StateY;
use App\ModelStates\OtherModelStates\StateZ;
use App\ModelStates\TestModel;
use App\ModelStates\TestModelWithCustomTransition;
use App\ModelStates\Transitions\CustomInvalidTransition;
use App\ModelStates\Transitions\CustomTransition;
use Illuminate\Support\Collection as SupportCollection;
use MLL\LaravelUtils\ModelStates\DefaultTransition;
use MLL\LaravelUtils\ModelStates\Exceptions\DuplicateTransitionException;
use MLL\LaravelUtils\ModelStates\Exceptions\TransitionNotAllowed;
use MLL\LaravelUtils\ModelStates\Exceptions\TransitionNotFound;
use MLL\LaravelUtils\ModelStates\Exceptions\UnknownStateException;
use MLL\LaravelUtils\ModelStates\MermaidStateConfigValidator;
use MLL\LaravelUtils\ModelStates\StateConfig;
use MLL\LaravelUtils\ModelStates\TransitionDirection;
use MLL\LaravelUtils\Tests\DBTestCase;

final class StateTest extends DBTestCase
{
    public function testModelWillBeCreatedWithDefault(): void
    {
        $model = new TestModel();
        $model->save();

        self::assertSame(StateA::name(), $model->stateManager->state_name);
    }

    public function testIfOldAndNewStateAreIdenticalNoTransitionNotAllowedExceptionWillBeThrown(): void
    {
        // A -> A
        $model1 = new TestModel();
        $model1->save();
        self::assertSame('STATE_A', StateA::name());
        self::assertSame(StateA::name(), $model1->stateManager->state_name);
        $model1->state = new StateA();
        self::assertSame(StateA::name(), $model1->stateManager->state_name);
    }

    public function testCanTransitionToAllowedStates(): void
    {
        // A -> B
        $model1 = new TestModel();
        $model1->save();
        self::assertSame(StateA::name(), $model1->stateManager->state_name);

        $model1->state = new StateB();
        self::assertSame(StateB::name(), $model1->stateManager->state_name);

        // B -> C
        $model1->state = new StateC();
        self::assertSame(StateC::name(), $model1->stateManager->state_name);

        // A -> C
        $model2 = new TestModel();
        $model2->save();
        self::assertSame(StateA::name(), $model2->stateManager->state_name);

        $model2->state = new StateC();
        self::assertSame(StateC::name(), $model2->stateManager->state_name);

        // A -> C
        $model3 = new TestModel();
        $model3->save();
        self::assertSame(StateA::name(), $model3->stateManager->state_name);

        $model3->state = new StateD();
        self::assertSame(StateD::name(), $model3->stateManager->state_name);
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
        self::assertSame(StateA::name(), $model->stateManager->state_name);

        $model->state = new StateB();
        self::assertSame(StateB::name(), $model->stateManager->state_name);

        // B -> D
        $this->expectException(TransitionNotFound::class);
        $model->state = new StateD();
        self::assertSame(StateD::name(), $model->stateManager->state_name);
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

    public function testReturnsListOfPossibleNextTransitionsForCustomTransition(): void
    {
        $model = new TestModelWithCustomTransition();
        $model->save();

        $first = $model->stateManager->possibleTransitions()->first();
        self::assertInstanceOf(CustomTransition::class, $first);
        self::assertSame(TransitionDirection::FORWARD, $first->direction());
        self::assertInstanceOf(StateX::class, $first->from());
        self::assertSame(StateY::name(), $first->to()::name());

        $last = $model->stateManager->possibleTransitions()->last();
        self::assertInstanceOf(CustomInvalidTransition::class, $last);
        self::assertSame(TransitionDirection::REVERSE, $last->direction());
        self::assertFalse($last->isVisibleFromFrontend());
        self::assertSame(StateX::name(), $last->from()::name());
        self::assertSame(StateZ::name(), $last->to()::name());
    }

    public function testReturnsListOfPossibleNextTransitionsForDefaultTransition(): void
    {
        $model = new TestModel();
        $model->save();

        $default = $model->stateManager->possibleTransitions()->first();
        self::assertInstanceOf(DefaultTransition::class, $default);
        self::assertSame(TransitionDirection::FORWARD, $default->direction());
        self::assertTrue($default->isVisibleFromFrontend());
    }

    public function testCustomTransition(): void
    {
        $model = new TestModelWithCustomTransition();
        $model->save();

        $model->state = new StateY();
        self::assertSame(StateY::name(), $model->stateManager->state_name);
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

        $model->stateManager->state_name = 'UnknownState';
        self::assertSame('UnknownState', $model->stateManager->state_name);

        $this->expectException(UnknownStateException::class);
        // @phpstan-ignore-next-line only meant to trigger an error
        $model->state;
    }

    public function testDuplicateTransitionException(): void
    {
        $stateConfig = new StateConfig();
        $stateConfig->allowTransition(StateA::class, StateB::class);

        $this->expectExceptionObject(new DuplicateTransitionException(StateA::class, StateB::class));
        $stateConfig->allowTransition(StateA::class, StateB::class);
    }

    public function testGenerateMermaidGraph(): void
    {
        $workflowAsMermaidGraph = /** @lang Mermaid */ <<<'MERMAID'
graph TB;
    FOO_BAR("FOO_BAR");
    STATE_A("STATE_A");
    STATE_C("STATE_C");
    STATE_D("STATE_D");

    FOO_BAR-->STATE_C;
    STATE_A-->FOO_BAR;
    STATE_A-->STATE_C;
    STATE_A-->STATE_D;

linkStyle default interpolate basis;
MERMAID;

        MermaidStateConfigValidator::assertStateConfigEquals($workflowAsMermaidGraph, ModelState::config());
    }
}
