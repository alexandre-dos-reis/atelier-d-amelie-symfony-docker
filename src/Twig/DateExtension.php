<?php

namespace App\Twig;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DateExtension extends AbstractExtension
{
    private static $months = [
        '01' => 'Janvier',
        '02' => 'Février',
        '03' => 'Mars',
        '04' => 'Avril',
        '05' => 'Mai',
        '06' => 'Juin',
        '07' => 'Juillet',
        '08' => 'Août',
        '09' => 'Septembre',
        '10' => 'Octobre',
        '11' => 'Novembre',
        '12' => 'Décembre',
    ];

    private static $days = [
        '1' => 'Lundi',
        '2' => 'Mardi',
        '3' => 'Mercredi',
        '4' => 'Jeudi',
        '5' => 'Vendredi',
        '6' => 'Samedi',
        '7' => 'Dimanche',
    ];

    public function getFunctions()
    {
        return [
            new TwigFunction('frDateMonthYear', [$this, 'frDateMonthYear']),
            new TwigFunction('frDateDayMonthYear', [$this, 'frDateDayMonthYear']),
            new TwigFunction('frDateDayMonth', [$this, 'frDateDayMonth'])
        ];
    }

    public function frDateMonthYear(string $strDateTime): string
    {
        return self::monthNumberToFr($strDateTime) . ' ' . self::year($strDateTime);
    }

    public function frDateDayMonthYear(string $strDateTime): string
    {
        return
            self::dayofWeekNumberToFr($strDateTime) . ' ' .
            self::dayNumber($strDateTime) . ' ' .
            self::monthNumberToFr($strDateTime) . ' ' .
            self::year($strDateTime);
    }

    public function frDateDayMonth(string $strDateTime): string
    {
        return
            self::dayofWeekNumberToFr($strDateTime) . ' ' .
            self::dayNumber($strDateTime) . ' ' .
            self::monthNumberToFr($strDateTime);
    }

    private static function dayNumber(string $strDateTime): string
    {
        return (new DateTime($strDateTime))->format(('j'));
    }

    private static function dayofWeekNumberToFr(string $strDateTime): string
    {
        return self::$days[(new DateTime($strDateTime))->format(('N'))];
    }

    private static function monthNumberToFr(string $strDateTime): string
    {
        return self::$months[(new DateTime($strDateTime))->format(('m'))];
    }

    private static function year(string $strDateTime): string
    {
        return (new DateTime($strDateTime))->format(('Y'));
    }
}
