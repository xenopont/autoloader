<?php

namespace Core{

    abstract class AbstractObject implements IBaseObject{
        //
    }

    class Object extends AbstractObject {
        public function toString(){
            return var_export($this, true);
        }
    }

}

