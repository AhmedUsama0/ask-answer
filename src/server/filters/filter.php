<?php
class Filter
{
    private static string $email;
    private static string $username;
    private static string $password;
    private static string $text;


    public static function filterEmail(string $email): string
    {
        self::$email = filter_var($email, FILTER_SANITIZE_EMAIL);
        self::$email = trim(self::$email);
        return self::$email;
    }

    public static function filterUserName(string $username): string
    {
        self::$username = strip_tags($username);
        self::$username = trim(self::$username);
        self::$username = preg_replace("/[^A-Za-z0-9\s]/", "", self::$username);
        return self::$username;
    }

    public static function hashPassword(string $password): string
    {
        self::$password = password_hash($password, PASSWORD_DEFAULT);
        return self::$password;
    }

    public static function verifyPassword(string $inputPassword, string $userPassword): bool
    {
        return password_verify($inputPassword, $userPassword);
    }

    public static function filterText(string $text): string
    {
        self::$text = strip_tags($text);
        self::$text = trim(self::$text);
        return self::$text;
    }
}
