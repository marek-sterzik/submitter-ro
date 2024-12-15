<?php

namespace App\Utility;

use App\Entity\User;

class RoleComparator
{
    private static ?array $roleOrder = null;

    public static function gt(string $role1, string $role2): bool
    {
        return self::getRoleOrder($role1) > self::getRoleOrder($role2);
    }

    public static function lt(string $role1, string $role2): bool
    {
        return self::getRoleOrder($role1) < self::getRoleOrder($role2);
    }

    public static function eq(string $role1, string $role2): bool
    {
        return self::getRoleOrder($role1) == self::getRoleOrder($role2);
    }

    public static function ge(string $role1, string $role2): bool
    {
        return self::getRoleOrder($role1) >= self::getRoleOrder($role2);
    }

    public static function le(string $role1, string $role2): bool
    {
        return self::getRoleOrder($role1) <= self::getRoleOrder($role2);
    }

    public static function max(string $role1, string $role2): string
    {
        return self::gt($role1, $role2) ? $role1 : $role2;
    }

    public static function min(string $role1, string $role2): string
    {
        return self::lt($role1, $role2) ? $role1 : $role2;
    }

    private static function getRoleOrder(string $role): int
    {
        self::ensureOrderDefined();
        return self::$roleOrder[$role];
    }

    private static function ensureOrderDefined(): void
    {
        if (self::$roleOrder === null) {
            self::$roleOrder = arraY_flip(array_keys(User::ROLES));
        }
    }
}
