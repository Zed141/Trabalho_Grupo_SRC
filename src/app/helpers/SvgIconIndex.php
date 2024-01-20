<?php

namespace app\helpers;

final class SvgIconIndex {

    public const string PLUS = 'PLUS';
    public const string CHECK = 'CHECK';
    public const string KEY = 'KEY';
    public const string HOME = 'HOME';
    public const string SHIELD = 'SHIELD';
    public const string SEARCH = 'SEARCH';

    /**
     * @param string $index
     * @param int    $width
     * @param int    $height
     *
     * @return string
     */
    public static function icon(string $index, int $width = 24, int $height = 24): string {
        return match ($index) {
            self::PLUS => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>',
            self::CHECK => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>',
            self::KEY => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1 -4.069 0l-.301 -.301l-6.558 6.558a2 2 0 0 1 -1.239 .578l-.175 .008h-1.172a1 1 0 0 1 -.993 -.883l-.007 -.117v-1.172a2 2 0 0 1 .467 -1.284l.119 -.13l.414 -.414h2v-2h2v-2l2.144 -2.144l-.301 -.301a2.877 2.877 0 0 1 0 -4.069l2.643 -2.643a2.877 2.877 0 0 1 4.069 0z" /><path d="M15 9h.01" /></svg>',
            self::HOME => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0"/><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/></svg>',
            self::SHIELD => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3"/><path d="M12 11m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"/><path d="M12 12l0 2.5"/></svg>',
            self::SEARCH => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"/><path d="M21 21l-6 -6"/></svg>',
            default => ''
        };
    }
}