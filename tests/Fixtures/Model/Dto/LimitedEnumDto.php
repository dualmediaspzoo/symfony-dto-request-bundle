<?php

namespace DM\DtoRequestBundle\Tests\Fixtures\Model\Dto;

use DM\DtoRequestBundle\Attributes\Dto\AllowEnum;
use DM\DtoRequestBundle\Model\AbstractDto;
use DM\DtoRequestBundle\Tests\Fixtures\Enum\IntegerEnum;

class LimitedEnumDto extends AbstractDto
{
    #[AllowEnum([IntegerEnum::INTEGER_KEY])]
    public ?IntegerEnum $int = null;
}
