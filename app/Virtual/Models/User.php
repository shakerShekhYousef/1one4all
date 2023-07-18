<?php

namespace App\Virtual\Models;


/**
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */

class User
{
    /**
     * @OA\Property(
     *     title="Age",
     *     description="Age",
     *     format="int64",
     * )
     *
     * @var integer
     */
    private $age;

    /**
     * @OA\Property(
     *     title="full_name",
     *     description="full_name",
     *     format="string"
     * )
     *
     * @var string
     */
    private $full_name;

    /**
     * @OA\Property(
     *     title="email",
     *     description="email",
     *     format="string"
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @OA\Property(
     *     title="bio",
     *     description="bio",
     *     format="string"
     * )
     *
     * @var string
     */
    private $bio;

    /**
     * @OA\Property(
     *     title="level",
     *     description="level",
     *     format="string"
     * )
     *
     * @var string
     */
    private $level;

    /**
     * @OA\Property(
     *     title="mobile",
     *     description="mobile",
     *     format="string"
     * )
     *
     * @var string
     */
    private $mobile;

    /**
     * @OA\Property(
     *     title="profile_pic",
     *     description="profile_pic",
     *     format="string"
     * )
     *
     * @var string
     */
    private $profile_pic;
}
