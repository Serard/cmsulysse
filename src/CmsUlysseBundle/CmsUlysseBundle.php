<?php

namespace CmsUlysseBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CmsUlysseBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSMessageBundle';
    }
}
