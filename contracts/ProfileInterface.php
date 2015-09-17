<?php

namespace yz\admin\contracts;


/**
 * Interface ProfileInterface
 * @property string $backendUserProfileType
 * @property string $backendUserProfileTitle
 */
interface ProfileInterface
{
    /**
     * Returns type string to display somewhere in admin panel. Usually returns constant
     * @return string
     */
    public function getBackendUserProfileType();

    /**
     * Returns title of the profile to display somewhere in admin panel. Usually returns name of the profile
     * @return mixed
     */
    public function getBackendUserProfileTitle();
}