<?php

namespace yz\admin\contracts;


/**
 * Interface ProfileFinderInterface
 */
interface ProfileFinderInterface
{
    /**
     * @param $id
     * @return ProfileInterface
     */
    public static function findByIdentityId($id);
}