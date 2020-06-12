<?php

namespace ZxingSPE;

interface Reader
{
    public function decode(BinaryBitmap $image);

    public function reset();
}
