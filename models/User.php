<?php

namespace app\models;

use app\core\Application;
use app\core\db\DbModel;

use app\core\Model;
use app\core\UserModel;
use app\core\Utils;

class User extends UserModel
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;


    public int $id;

    public string $email = '';
    public int $status = self::STATUS_INACTIVE;
    public string $password = '';
    public string $confirmPassword = '';
    public string $referral_code = '';
    public int $points = 0;

    public function tableName(): string
    {
        return 'users';
    }

    public function primaryKey(): string
    {
        return 'id';
    }

    public function save(): bool
    {
        $this->status = self::STATUS_INACTIVE;
        $this->password = strval(password_hash($this->password, PASSWORD_DEFAULT));
        $this->setReferralCode();
        return parent::save();
    }


    public function rules(): array
    {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [
                self::RULE_UNIQUE, 'class' => self::class
            ]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 24]],
            'confirmPassword' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
        ];
    }

    public function attributes(): array
    {
        return ['email', 'password', 'status', 'referral_code', 'points'];
    }

    public function labels(): array
    {
        return [
            'email' => Application::$app->getText("E-mail"),
            'password' => Application::$app->getText("Password"),
            'confirmPassword' => Application::$app->getText("Repeat password")
        ];
    }

    public function getDisplayName(): string
    {
        return $this->email;
    }

    public function setReferralCode(): bool
    {
        $newReferralCode = RefClick::generateNewReferralCode();
        $this->referral_code = $newReferralCode;
        return true;
    }

    public function getReferralCode(): string
    {
        return $this->referral_code;
    }

    public function addPoints(int $points)
    {
        $this->points += $points;
        parent::updateColumn('points');
    }
}