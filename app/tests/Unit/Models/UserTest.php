<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Lesson;
use Mockery;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    /**
     * testCanReserve
     *
     * @param  string $plan
     * @param  int $remainingCount
     * @param  int $reservationCount
     * @param  bool $canReserve
     * @dataProvider dataCanReserve
     */
    public function testCanReserve(string $plan, int $remainingCount, int $reservationCount, bool $canReserve)
    {
        /** @var User $user */
        $user = Mockery::mock(User::class)->makePartial();
        $user->shouldReceive('reservationCountThisMonth')->andReturn($reservationCount);
        $user->plan = $plan;

        /** @var Lesson $lesson */
        $lesson = Mockery::mock(Lesson::class);
        $lesson->shouldReceive('remainingCount')->andReturn($remainingCount);

        $this->assertSame($canReserve, $user->canReserve($lesson, $reservationCount));
    }

    public function dataCanReserve()
    {
        return [
            '予約可能枠数>1, レギュラー, 当月予約<5, 予約可' => [
                'plan' => 'regular',
                'remainingCount' => 1,
                'reservationCount' => 4,
                'canReserve' => true,
            ],
            '予約可能枠数>1, レギュラー, 当月予約>5, 予約不可' => [
                'plan' => 'regular',
                'remainingCount' => 1,
                'reservationCount' => 5,
                'canReserve' => false,
            ],
            '予約可能枠数0, レギュラー, 当月予約<5, 予約不可' => [
                'plan' => 'regular',
                'remainingCount' => 0,
                'reservationCount' => 4,
                'canReserve' => false,
            ],
            '予約可能枠数>1, ゴールド, 当月予約無制限, 予約可' => [
                'plan' => 'gold',
                'remainingCount' => 1,
                'reservationCount' => 5,
                'canReserve' => true,
            ],
            '予約可能枠数0, ゴールド, 当月予約無制限, 予約不可' => [
                'plan' => 'gold',
                'remainingCount' => 0,
                'reservationCount' => 5,
                'canReserve' => false,
            ],
        ];
    }
}
