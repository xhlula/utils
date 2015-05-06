<?php
/**
 * Created by PhpStorm.
 * User: Xhersi
 * Date: 5/6/2015
 * Time: 10:33 PM
 */

namespace maldoinc\utils\password;


class PasswordPolicyValidation {

    protected $maxLength;
    protected $minLength;
    protected $containsNumbers;
    protected $containsCaps;
    protected $containsSymb;

    public function __construct(){

        $this->maxLength = 20;
        $this->minLength = 6;
        $this->containsNumbers = false;
        $this->containsCaps = false;
        $this->containsSymb = false;
    }

    public function setMaxLength($value)
    {
        $this->maxLength = (int)$value;
    }

    public function getMaxLength()
    {
        return $this->maxLength;
    }

    public function setMinLength($value)
    {
        $this->minLength = (int)$value;
    }

    public function getMinLength()
    {
        return $this->minLength;
    }

    public function containsNumbers($value)
    {
        $this->containsNumbers = $value;
    }

    public function containsCaps($value)
    {
        $this->containsCaps = $value;
    }

    public function containsSymb($value)
    {
        $this->containsSymb = $value;
    }

    public function isValid($password)
    {
        if (strlen($password) < $this->minLength) {
            return false;
        }

        if (strlen($password) > $this->maxLength) {
            return false;
        }

        return true;
    }

}

$validator = new PasswordPolicyValidation();
var_dump($validator->isValid('testtest'));

