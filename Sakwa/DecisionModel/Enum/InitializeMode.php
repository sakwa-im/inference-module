<?php

namespace Sakwa\DecisionModel\Enum;

/**
 * InitializeMode
 * enum class declaring valid initialize modes
 */
class InitializeMode extends Base
{
    const None = 0,
          SessionStart = 1,
          SessionStartDefaultValue = 2,
          CycleStart = 3,
          CycleStartDefaultValue = 4;

    protected static $enum = array(
        self::None => 'None',
        self::SessionStart => 'SessionStart',
        self::SessionStartDefaultValue => 'SessionStartDefaultValue',
        self::CycleStart => 'CycleStart',
        self::CycleStartDefaultValue => 'CycleStartDefaultValue'
    );

}